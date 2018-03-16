<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\DocumentsModel;
use App\Model\JobsModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Model\InsuranceModel;
use JWTAuth;

class ApiCustomerController extends Controller
{
    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
                  'photo' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Submit error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        try {
            $url = asset('/').'storage/app/'.((Storage::disk('local')->put('/public/photos', $request->photo)));
            return response()->json(['message' => 'success', 'data' => $url, 'response_code' => 1], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'upload failed', 'data' => $e, 'response_code' => 0], 500);
        }
    }

    public function addDocuments(Request $request)
    {
        // uploading multiple documents

        $validator = Validator::make($request->all(), [
                  'document' => 'required',
                  'userId' => 'required',
                  'jobId' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Submit error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        $documentArr = array();
        $arrLength = sizeof($request->document);
        if ($arrLength == 0) {
            return response()->json(['message' => 'No file uploaded', 'data' => null, 'response_code' => 0], 200);
        }
        try {
            foreach ($request->document as $docfile) {
                $document = new DocumentsModel();
                $document->document = asset('/').'storage/app/'.((Storage::disk('local')->put('/public/uploads/documents', $docfile)));
                $docName = explode('/', $document->document);
                $document->fileName = $docName[7];
                $document->user_id = $request->userId;
                $document->job_id = $request->jobId;
                $result = $document->save();
                array_push($documentArr, $docName[7]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'upload failed', 'data' => $e, 'response_code' => 0], 500);
        }
        return response()->json(['message' => 'success', 'data' => $documentArr, 'response_code' => 1], 200);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'phoneno' => 'required|string|max:255',
                'devicename' => 'required|string|max:255',
                'usertype' => 'required|string|max:255'
          ], $messages);
    }
    // create job records.
    public function addJob(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->usertype=="agent") {
            return response()->json(['message' => 'This Account is no customer', 'data' => null, 'response_code' => 0], 200);
        }
        $validator = Validator::make($request->all(), [
          'name' => 'required',
          'nric' => 'required',
          'insurencetype' => 'required',
          'phoneno' => 'required',
          'indicative_sum' => 'required',
          'address' => 'required',
          'state' => 'required',
          'expired_date' => 'required',
          'country' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Submit error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        $userid = $user->id;
        $name = $request->name;
        $nric = $request->nric;
        $phone = $request->phoneno;
        $instype = $request->insurencetype;
        $sum = $request->indicative_sum;
        $addres = $request->address;
        $pcode = $request->postcode;
        $state = $request->state;
        $country = $request->country;
        $expired_date = $request->expired_date;
        // create jobs transaction record
        $jobmodel = new JobsModel();
        $jobmodel->user_id = $userid;
        $jobmodel->name = $name;
        $jobmodel->nric = $nric;
        $jobmodel->phoneno = $phone;
        $jobmodel->insurance_type = $instype;
        $jobmodel->indicative_sum = $sum;
        $jobmodel->address = $addres;
        $jobmodel->postcode = $pcode;
        $jobmodel->state = $state;
        $jobmodel->country = $country;
        $jobmodel->job_status = 0;
        $jobmodel->expired_date = $expired_date;

        try {
            $result = $jobmodel->save();
            if ($result) {
                // Update documents table
                for ($i = 0; $i < count($request->documents); $i++) {
                    $data = [
                      "user_id" => $userid,
                      "job_id" => $result->id,
                  ];
                    $document = DocumentsModel::where(['fileName' => $request->documents[$i]])->update($data);
                }
                $jobCreated['job'] = JobsModel::where(['id' => $jobmodel->id])->get();
                $jobCreated['documents'] = DocumentsModel::where(['job_id' => $jobmodel->id])->get();
                return response()->json(['message' => 'New job is created', 'data' => $jobCreated, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'Create job problem', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }
    /**
     * fetching job posted by a particular user api
     */
    public function fetchJob(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        try {
            if ($user->usertype == 'agent') {
                $userJobs = JobsModel::join('users', 'users.id', '=', 'jobs.user_id')
                ->select()
                ->addSelect('jobs.id as job_id')
                ->get();
                $jobData = array();
                foreach ($userJobs as $userJob) {
                  $documents = DocumentsModel::where(['job_id' => $userJob['id']])->get();
                  $insuranceData = InsuranceModel::findOrFail($userJob['insurance_type']);
                  $userJob['documents'] = $documents;
                  $userJob['company'] = $insuranceData->companys;
                  $data = ["jobs" => $userJob];
                  array_push($jobData, $data);
                }
                if (count($jobData) > 0) {
                    return response()->json(['message' => 'All  posted jobs by cutomer', 'data' => $jobData, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'there is no posted jobs', 'data' => null, 'response_code' => 0], 200);
                }
            } else {
                $userJobs = JobsModel::join('users', 'users.id', '=', 'jobs.user_id')
                  ->where(['user_id' => $user->id])
                  ->select()
                  ->addSelect('jobs.id as job_id')
                  ->get();
                $jobData = array();
                foreach ($userJobs as $userJob) {
                  $documents = DocumentsModel::where(['job_id' => $userJob['id']])->get();
                  $insuranceData = InsuranceModel::findOrFail($userJob['insurance_type']);
                  $userJob['documents'] = $documents;
                  $userJob['company'] = $insuranceData->companys;
                  $data = ["jobs" => $userJob];
                  array_push($jobData, $data);
                }
                if (count($jobData) > 0) {
                    return response()->json(['message' => 'All  created jobs by person', 'data' => $jobData, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'No job is created by this person', 'data' => null, 'response_code' => 0], 200);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
        }
    }
    // GET Job Details
    public function jobDetail(Request $request)
    {
        if ($request->has('jobId')) {
            try {
                $userJobs = JobsModel::where(['id' => $request->jobId])->first();
                $jobData = array();
                $documents = DocumentsModel::where(['job_id' => $userJobs->id])->get();
                $insuranceData = InsuranceModel::findOrFail($userJobs[$i]['insurance_type']);
                $insuranceData->companys;
                $userJobs['documents'] = $documents;
                $userJobs['insurance_id'] = $insuranceData->id;
                $userJobs['insurance_name'] = $insuranceData->insurance_name;
                $data = [
                  "jobs" => $userJobs,
                ];
                array_push($jobData, $data);
                if (count($jobData) > 0) {
                    return response()->json(['message' => 'job', 'data' => $jobData, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'No job is created by this person', 'data' => null, 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'job id not given', 'data' => null, 'response_code' => 0], 200);
        }
    }

    public function getInsuranceType(Request $request)
    {
        if (!empty($request->insurId)) {
            try {
                $insuranceData = InsuranceModel::findOrFail($userJobs[$i]['insurance_type']);
                $insuranceData->companys;
                $send_data = array('insurance_name'=>$InsuranceData->insurance_name,'id'=>$InsuranceData->id );

                if (count($InsuranceData) > 0) {
                    return response()->json(['message' => 'insurance', 'data' => $send_data, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'No insurance is no data', 'data' => null, 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
            }
        } else {
            $InsuranceDatas = InsuranceModel::all();
            $send_data = [];
            foreach ($InsuranceDatas as $InsuranceData) {
                $send_data[] = array(
                  'insurance_name'=>$InsuranceData->insurance_name,
                  'id'=>$InsuranceData->id,
                  'companys'=>$InsuranceData->companys
              );
            }

            if (count($InsuranceDatas) > 0) {
                return response()->json(['message' => 'insurance', 'data' => $send_data, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'There is no INsurance Data', 'data' => [], 'response_code' => 0], 200);
            }
        }
    }
}
