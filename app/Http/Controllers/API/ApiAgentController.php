<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AssignJob;
use App\Model\JobsModel;
use App\Model\QuotationModel;
use App\User;
use Illuminate\Support\Facades\DB;

class ApiAgentController extends Controller
{
    //
    public function assignAgent(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!empty($user->id)) {
            try {
                $customer = User::where('id', $user->id)->select('latitude', 'longitude')->get();
                if (count($customer) > 0) {
                    $lat = 0;
                    $lon = 0;
                    foreach ($customer as $val) {
                        $lat = $val->latitude;
                        $lon = $val->latitude;
                    }
                    $agents = DB::table("users")
                        ->select("users.*", DB::raw("6371 * acos(cos(radians(" . $lat . "))
		                     * cos(radians(users.latitude))
		                   * cos(radians(users.longitude) - radians(" . $lon . "))
		                 + sin(radians(" . $lat . "))
		                 * sin(radians(users.latitude))) AS distance"))
                        ->leftJoin('assignJobs', 'users.id', '=', 'assignJobs.agent_id')->orWhere('assignJobs.agent_id', '=', null)
                        ->where(['users.usertype' => 'agent', 'users.isAvailable' => 1])
                        ->groupBy("users.id")
                        ->orderBy('distance', 'desc')
                        ->take(3)
                        ->get();
                    return response()->json(['message' => 'Nearest agents', 'data' => $agents, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'Custmor is not exist', 'data' => [], 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'Customer id is missing', 'data' => [], 'response_code' => 0], 200);
        }
    }

    public function handOverJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'agentid' => 'required',
          'customerid' => 'required',
          'customerid' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Submit error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        try {
            $ajob = new AssignJob();
            $ajob->agent_id = $request->agentid;
            $ajob->customer_id = $request->customerid;
            $ajob->job_id = $request->jobid;
            $result = $ajob->save();
            if ($result) {
                return response()->json(['message' => 'job is assigned', 'data' => AssignJob::findOrFail($ajob->id), 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'job  assigning error', 'data' => [], 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
        }
    }
    /**
     * view assigned job by a specific agent
     */
    public function assignedJobView(Request $request)
    {
        try {
            $job = User::join('jobs', 'users.id', '=', 'jobs.user_id')
                ->join('documents', 'users.id', '=', 'documents.user_id')
                ->join('assignJobs', 'users.id', '=', 'assignJobs.customer_id')
                ->where(['assignJobs.id' => $request->assignedjobid])->get();
            if (count($job) > 0) {
                return response()->json(['message' => 'job is assigned', 'data' => $job, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'This job is not assigned yet', 'data' => [], 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
        }
    }
    /**
     * update   assigned job status i.e reject or accept
     */
    public function jobAction(Request $request)
    {
        if (!empty($request->agentid) && !empty($request->jobid) && !empty($request->status)&&!empty($request->remarks)&&!empty($request->status)) {
            $data = array(
                'jobstatus' => $request->status
                );
            $updateJob = array(
                'quotation_price' => $request->quotation_price,
                'remarks' => $request->remarks
            );
            try {
                $saveResult = AssignJob::where(['id' => $request->jobid])->update($updateJob);
                $action = AssignJob::where(['agent_id' => $request->agentid, 'job_id' => $request->jobid])->update($data);
                if ($action&&$saveResult) {
                    $agent = AssignJob::where(['job_id' => $request->jobid])->get();
                    return response()->json(['message' => 'This job is updated', 'data' => $agent, 'response_code' => 0], 200);
                } else {
                    return response()->json(['message' => 'This job status is not updated', 'data' => [], 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'Some fields missing', 'data' => [], 'response_code' => 0], 200);
        }
    }

    /**
     * view all  job assigned to a  particular agent
     * which are not taken any action
     */

    public function assignedJobList(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        try {
            if ($user->usertype=='agent') {
                $jobList = AssignJob::join('jobs', 'jobs.id', '=', 'assignjobs.job_id')
                    ->where(['assignJobs.agent_id' => $user->id])->get();
                if (count($jobList) > 0) {
                    return response()->json(['message' => 'This is job list', 'data' => $jobList, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'This agent is no assigned jobs list', 'data' => null, 'response_code' => 0], 200);
                }
            } else {
                $jobList = AssignJob::join('jobs', 'jobs.id', '=', 'assignjobs.job_id')
                  ->where(['assignJobs.customer_id' => $user->id])->get();
                if (count($jobList) > 0) {
                    return response()->json(['message' => 'This is job list', 'data' => $jobList, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'This agent is no assigned jobs list', 'data' => null, 'response_code' => 0], 200);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
        }
    }


    public function allJobView(Request $request)
    {
        try {
            $job = User::join('assignJobs', 'users.id', '=', 'assignJobs.agent_id')
                ->where(['assignJobs.agent_id' => $request->agentid])
                ->where('assignJobs.jobstatus', '=', null)
                ->get();
            if (count($job) > 0) {
                return response()->json(['message' => 'All assigned job', 'data' => $job, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'No job is assigned to this agent', 'data' => [], 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
        }
    }
    /**
     * Agent history i.e view all job that are accepted are rejected by particular agent
     */
    public function agentHistory(Request $request)
    {
        try {
            $job = User::join('assignJobs', 'users.id', '=', 'assignJobs.agent_id')
                ->where(['assignJobs.agent_id' => $request->agentid])
                ->where('assignJobs.jobstatus', '<>', null)
                ->get();
            if (count($job) > 0) {
                return response()->json(['message' => 'Agent History', 'data' => $job, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'No job is completed by this agent', 'data' => [], 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => [], 'response_code' => 0], 500);
        }
    }

    // add quotation to jobs.
    public function addQuotation(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->usertype=='customer') {
            return response()->json(['message' => 'this is no agent', 'data' => null, 'response_code' => 0], 200);
        }
        $validator = Validator::make($request->all(), [
          'quotation_price' => 'required',
          'jod_id' => 'jod_id',
          'quotation_description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Insert data error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        $quotaion = new QuotationModel();
        $question->quotation_price = $request->quotation_price;
        $question->jod_id = $request->jod_id;
        $question->jod_id = $user->id;
        $question->quotation_description = $request->quotation_description;
        if ($question->save()) {
            return response()->json(['message' => 'Successfully added quotation', 'data' => $question, 'response_code' => 0], 200);
        } else {
            return response()->json(['message' => 'Addition is failed', 'data' => null, 'response_code' => 0], 200);
        }
    }
}
