<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\DocumentsModel;
use App\Model\JobsModel;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Model\InsuranceModel;

class ApiJobController extends Controller
{
    public function uploadFile(Request $request)
    {
        //print_r($request->document);exit;
        $document = new DocumentsModel();
        $document->document = url('/') . '/public/uploads/' . Storage::disk('public_uploads')->put('/', $request->document);
        $docName = explode('/', $document->document);
        $document->fileName = $docName[6];
        $result = $document->save();
        return response()->json(['message' => 'success', 'data' => $document->fileName, 'response_code' => 1], 200);
    }

    public function addDocuments(Request $request)
    {

        // uploading multiple documents
        $documentArr = array();
        $arrLength = sizeof($request->document);
        if ($arrLength == 0) {
            return response()->json(['message' => 'No file uploaded', 'data' => [], 'response_code' => 0], 200);
        }
        for ($i = 0; $i < count($request->document); $i++) {
            $document = new DocumentsModel();
            $document->document = url('/') . '/public/documents/' . Storage::disk('document_uploads')->put('/', $request->document[$i]);
            $docName = explode('/', $document->document);
            $document->fileName = $docName[6];
            $result = $document->save();
            if ($result) {
                array_push($documentArr, $docName[6]);
            }
        }
        return response()->json(['message' => 'success', 'data' => $documentArr, 'response_code' => 1], 200);
    }

    public function addJob(Request $request)
    {
        // Recieving All Fields By User
        $userid = (int) $request->userid;
        $name = $request->name;
        $nric = $request->nric;
        $phone = $request->phoneno;
        $instype = $request->insurencetype;
        $sum = $request->indicativesum;
        $addres = $request->address;
        $pcode = $request->postcode;
        $state = $request->state;
        $country = $request->country;
        $expired_date = $request->expired_date;
        // Adding Job
        $jobmodel = new JobsModel();
        $jobmodel->user_id = $userid;
        $jobmodel->name = $name;
        $jobmodel->nric = $nric;
        $jobmodel->phoneno = $phone;
        $jobmodel->insurancetype = $instype;
        $jobmodel->indicativesum = $sum;
        $jobmodel->address = $addres;
        $jobmodel->postcode = $pcode;
        $jobmodel->state = $state;
        $jobmodel->country = $country;
        $jobmodel->expired_date = $expired_date;
        $result = $jobmodel->save();
        //print_r($result);exit();
        try {
            //$result = $jobmodel->save();
            if ($result) {
                // Update documents table
                for ($i = 0; $i < count($request->documents); $i++) {
                    $data = [
                        "user_id" => $userid,
                        "job_id" => $jobmodel->id,
                    ];
                    $document = DocumentsModel::where(['fileName' => $request->documents[$i]])->update($data);
                }
                $jobCreated['job'] = JobsModel::where(['id' => $jobmodel->id])->get();
                $jobCreated['documents'] = DocumentsModel::where(['job_id' => $jobmodel->id])->get();
                return response()->json(['message' => 'New job is created', 'data' => $jobCreated, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'Create job problem', 'data' => [], 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            print_r($exception);
            exit;
            return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
        }
    }
    /**
     * fetching job posted by a particular user api
     */
    public function fetchJob(Request $request)
    {
        if (!empty($request->userId)) {
            try {
                $userJobs = JobsModel::where(['user_id' => $request->userId])->get();
                $usrData = User::where(['id' => $request->userId])->select('id as agent_id', 'username as agent_name', 'image as agent_photo_url')->first();

                $jobData = array();
                for ($i = 0; $i < count($userJobs); $i++) {
                    $documents = DocumentsModel::where(['job_id' => $userJobs[$i]['id']])->get();
                    $insuranceData = InsuranceModel::where(['insur_id' => $userJobs[$i]['insurancetype']])->first();
                    $userJobs[$i]['documents'] = $documents;
                    $userJobs[$i]['agent_id'] = $usrData->agent_id;
                    $userJobs[$i]['agent_name'] = $usrData->agent_name;
                    $userJobs[$i]['agent_photo_url'] = $usrData->agent_photo_url;
                    $userJobs[$i]['insurance_id'] = $insuranceData->insur_id;
                    $userJobs[$i]['insurance_name'] = $insuranceData->insurance_name;
                    $data = [
                        "jobs" => $userJobs[$i],
                    ];
                    array_push($jobData, $data);
                }
                if (count($jobData) > 0) {
                    return response()->json(['message' => 'All  created jobs by person', 'data' => $jobData, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'No job is created by this person', 'data' => [], 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'User id id not given', 'data' => [], 'response_code' => 0], 200);
        }
    }
    // GET Job Details
    public function jobDetail(Request $request)
    {
        if (!empty($request->jobId)) {
            try {
                $userJobs = JobsModel::where(['id' => $request->jobId])->first();
                $jobData = array();
                $documents = DocumentsModel::where(['job_id' => $userJobs->id])->get();
                $insuranceData = InsuranceModel::where(['insur_id' => $userJobs->insurancetype])->first();
                $userJobs['documents'] = $documents;
                $userJobs['insurance_id'] = $insuranceData->insur_id;
                $userJobs['insurance_name'] = $insuranceData->insurance_name;
                $data = [
                    "jobs" => $userJobs,
                ];
                array_push($jobData, $data);
                if (count($jobData) > 0) {
                    return response()->json(['message' => 'job', 'data' => $jobData, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'No job is created by this person', 'data' => [], 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'User id not given', 'data' => [], 'response_code' => 0], 200);
        }
    }

    public function getInsuranceType(Request $request)
    {
        if (!empty($request->insurId)) {
            try {
                $InsuranceData = InsuranceModel::where(['insur_id' => $request->insurId])->first();
                $send_data = array('insurance_name'=>$InsuranceData->insurance_name,'id'=>$InsuranceData->insur_id );

                if (count($InsuranceData) > 0) {
                    return response()->json(['message' => 'insurance', 'data' => $send_data, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'No insurance is no data', 'data' => [], 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
            }
        } else {
            $InsuranceDatas = InsuranceModel::all();
            $send_data = [];
            foreach ($InsuranceDatas as $InsuranceData) {
                $send_data[] = array(
                    'insurance_name'=>$InsuranceData->insurance_name,
                    'id'=>$InsuranceData->insur_id
                );
            }

            if (count($InsuranceDatas) > 0) {
                return response()->json(['message' => 'insurance', 'data' => $send_data, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'insurId id not given', 'data' => [], 'response_code' => 0], 200);
            }
        }
    }
    //
}
