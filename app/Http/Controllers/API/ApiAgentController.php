<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AssignJob;
use App\User;
use Illuminate\Support\Facades\DB;

class ApiAgentController extends Controller
{
    //
    public function assignAgent(Request $request)
    {
        if (!empty($request->customerid)) {
            try {
                $customer = User::where('id', $request->customerid)->select('latitude', 'longitude')->get();
                if (count($customer) > 0) {
                    $lat = 0;
                    $lon = 0;
                    foreach ($customer as $val) {
                        $lat = $val->latitude;
                        $lon = $val->latitude;
                    }
                    $agents = DB::table("customers")
                        ->select("customers.*", DB::raw("6371 * acos(cos(radians(" . $lat . "))
		                     * cos(radians(customers.latitude))
		                   * cos(radians(customers.longitude) - radians(" . $lon . "))
		                 + sin(radians(" . $lat . "))
		                 * sin(radians(customers.latitude))) AS distance"))
                        ->leftJoin('assignJobs', 'customers.id', '=', 'assignJobs.agent_id')->orWhere('assignJobs.agent_id', '=', null)
                        ->where(['customers.usertype' => 'agent', 'customers.isAvailable' => 1])
                        ->groupBy("customers.id")
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
        try {
            $ajob = new AssignJob();
            $ajob->agent_id = $request->agentid;
            $ajob->customer_id = $request->customerid;
            $ajob->job_id = $request->jobid;
            $result = $ajob->save();
            if ($result) {
                return response()->json(['message' => 'job is assigned', 'data' => AssignJob::where(['id' => $ajob->id])->get(), 'response_code' => 1], 200);
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
            $job = User::join('jobs', 'customers.id', '=', 'jobs.user_id')
                ->join('documents', 'customers.id', '=', 'documents.user_id')
                ->join('assignJobs', 'customers.id', '=', 'assignJobs.customer_id')
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
                'quotationPrice' => $request->quotationPrice,
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
    public function allJobView(Request $request)
    {
        try {
            $job = User::join('assignJobs', 'customers.id', '=', 'assignJobs.agent_id')
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
            $job = User::join('assignJobs', 'customers.id', '=', 'assignJobs.agent_id')
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
}
