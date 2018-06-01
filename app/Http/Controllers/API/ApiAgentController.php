<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AssignJob;
use App\Model\JobsModel;
use App\Model\QuotationModel;
use App\Model\DocumentsModel;
use App\Model\rating;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use DateTime;

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
                        $lon = $val->longitude;
                    }
                    $agents = DB::table("users")
                            ->select("users.*", DB::raw("6371 * acos(cos(radians(" . $lat . "))
                         * cos(radians(users.latitude))
                       * cos(radians(users.longitude) - radians(" . $lon . "))
                     + sin(radians(" . $lat . "))
                     * sin(radians(users.latitude))) AS distance"))
                            ->where(['users.usertype' => 'agent', 'users.isAvailable' => 1])
                            ->orderBy('distance', 'asc')
                            ->take(3)
                            ->get();
                    return response()->json(['message' => 'Nearest agents', 'data' => $agents, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'Custmor is not exist', 'data' => null, 'response_code' => 0], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'Customer id is missing', 'data' => null, 'response_code' => 0], 200);
        }
    }

    /**
    *
    *
    *
    *
    *
    */

    public function handOverJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'agentid' => 'required',
                    'customerid' => 'required',
                    'jobid' => 'required'
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
                return response()->json(['message' => 'job  assigning error', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
        }
    }

    /**
     *
     *
     * View assigned job by a specific agent
     *
     *
     *
     */
    public function assignedJobView(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$request->has('assignedjobid')) {
            return response()->json(['message' => 'please submit assign job ID', 'data' => null, 'response_code' => 0], 200);
        }
        try {
            $job = User::join('jobs', 'users.id', '=', 'jobs.user_id')
                            ->join('assignJobs', 'users.id', '=', 'assignJobs.customer_id')
                            ->where(['assignJobs.job_id' => $request->assignedjobid])->get();
            if (count($job) > 0) {
                return response()->json(['message' => 'job is assigned', 'data' => $job, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'This job is not assigned yet', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }

    /**
     *
     *
     * Update   assigned job status i.e reject or accept
     *
     *
     */
    public function jobAction(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->usertype == 'customer') {
            return response()->json(['message' => 'You must be agent', 'data' => null, 'response_code' => 0], 200);
        }
        $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'jobid' => 'required',
                    'costomerId' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Some fields missing', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }

        try {
            if ($ajob = AssignJob::where(['job_id'=>$request->jobid,'agent_id'=>$user->id,'customer_id'=>$request->costomerId,])->first()) {
                $ajob->jobstatus = $request->status;
                $ajob->update();
                $action = $ajob;
            } else {
                $action = new AssignJob();
                $action->jobstatus = $request->status;
                $action->job_id = $request->jobid;
                $action->agent_id = $user->id;
                $action->customer_id = $request->costomerId;
                $action->save();
            }
            if ($action->id) {
                return response()->json(['message' => 'This job is updated', 'data' => $action, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'This job status is not updated', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
        }
        return response()->json(['message' => 'Some fields missing', 'data' => null, 'response_code' => 0], 200);
    }

    /**
     *
     *
     * Update job status 1 :: It's complete
     *
     *
     */
    public function completeJob(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->usertype == 'customer') {
            return response()->json(['message' => 'You must be agent', 'data' => null, 'response_code' => 0], 200);
        }
        $validator = Validator::make($request->all(), [
                    'jobid' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Some fields missing', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }

        try {
          $job = JobsModel::where('id', '=', $request->jobid)->first();
          $job->job_status = 1;
          $job->update();
          $ajob = AssignJob::where(["job_id" => $job->id, "agent_id" => $user->id])->get()->first();
          $ajob->jobstatus = 4;
          $ajob->update();
          return response()->json(['message' => 'Complete this job success', 'response_code' => 1], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'response_code' => 0], 500);
        }
        return response()->json(['message' => 'Some fields missing', 'response_code' => 0], 200);
    }

    /**
     *
     *
     * Update customer accept agent
     *
     *
     */
    public function acceptAgent(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->usertype == 'agent') {
            return response()->json(['message' => 'You must be customer', 'data' => null, 'response_code' => 0], 200);
        }
        $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'jobid' => 'required',
                    'agentId' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Some fields missing', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }

        try {
            if ($ajob = AssignJob::where(['job_id'=>$request->jobid,'agent_id'=>$request->agentId,'customer_id'=>$user->id,])->first()) {
                $ajob->jobstatus = $request->status;
                $ajob->update();
                AssignJob::where(['job_id'=>$request->jobid,'customer_id'=>$user->id,])->where('agent_id', '!=', $request->agentId)->delete();
                DocumentsModel::where('job_id', '=', $request->jobid)->where('user_id', '!=', $request->agentId)->where('user_id', '!=', $user->id)->delete();
                QuotationModel::where('agent_id', '!=', $request->agentId)->where('job_id', '=', $request->jobid)->delete();
                $action = $ajob;
            } else {
                return response()->json(['message' => 'There is not that job', 'data' => null, 'response_code' => 0], 200);
            }
            if ($action->id) {
                return response()->json(['message' => 'This job is updated', 'data' => $action, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'This job status is not updated', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
        }
        return response()->json(['message' => 'Some fields missing', 'data' => null, 'response_code' => 0], 200);
    }

    /**
     *
     *
     * view all  job assigned to a  particular agent
     *
     * which are not taken any action
     *
     *
     */
    public function acceptedJobList(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        try {
            if ($user->usertype == 'agent') {
                $jobList = AssignJob::join('jobs', 'jobs.id', '=', 'assignJobs.job_id')
                                ->join('users', 'assignJobs.customer_id', '=', 'users.id')
                                ->where(['assignJobs.jobstatus' => '1','assignJobs.agent_id' => $user->id])
                                ->get();
                if (count($jobList) > 0) {
                    return response()->json(['message' => 'This is job list', 'data' => $jobList, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'This agent is no assigned jobs list', 'data' => null, 'response_code' => 0], 200);
                }
            } else {
                $jobList = AssignJob::join('jobs', 'jobs.id', '=', 'assignJobs.job_id')
                                ->join('users', 'assignJobs.customer_id', '=', 'users.id')
                                ->where(['assignJobs.customer_id' => $user->id , 'assignJobs.jobstatus' => '1'])->get();
                if (count($jobList) > 0) {
                    return response()->json(['message' => 'This is job list', 'data' => $jobList, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'This agent is no assigned jobs list', 'data' => null, 'response_code' => 0], 200);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }
    /**
     *
     *
     * view all  job assigned to a  particular agent
     *
     * which are not taken any action
     *
     *
     */
    public function allJobView(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        try {
            $jobs = User::join('assignJobs', 'users.id', '=', 'assignJobs.agent_id')
                    ->join('jobs', 'assignJobs.job_id', '=', 'jobs.id')
                    ->where(['assignJobs.agent_id' => $user->id, 'assignJobs.jobstatus' => null])
                    ->select()
                    ->addSelect('assignJobs.id as assignJobsId')
                    ->addSelect('assignJobs.updated_at as assUpdatedAt')
                    ->get();
            foreach ($jobs as $k => $job) {
              $customer = User::where('users.id', '=', $job['customer_id'])->get()->first();
              $jobs[$k]['image'] = $customer['image'];
            }
            if (count($jobs) > 0) {
                return response()->json(['message' => 'All assigned job', 'data' => $jobs, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'No job is assigned to this agent', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }
    /**
     *
     *
     *
     *
     * Agent history i.e view all job that are accepted are rejected by particular agent
     *
     *
     *
     */
    public function agentHistory(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        try {
            $job = User::join('assignJobs', 'users.id', '=', 'assignJobs.agent_id')
                    ->join('jobs', 'assignJobs.job_id', '=', 'jobs.id')
                    ->where(['assignJobs.agent_id' => $user->id])
                    ->where('assignJobs.jobstatus', '<>', null)
                    ->where(['jobs.job_status' => '1'])
                    ->get();
            if (count($job) > 0) {
                return response()->json(['message' => 'Agent History', 'data' => $job, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'No job is completed by this agent', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }


    /**
     *
     *
     *
     *
     * Get Completed Job list
     *
     *
     *
     */
    public function agentCompletedJob(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        try {
            $job = User::join('assignJobs', 'users.id', '=', 'assignJobs.agent_id')
                    ->join('jobs', 'assignJobs.job_id', '=', 'jobs.id')
                    ->where(['assignJobs.agent_id' => $user->id])
                    ->where('assignJobs.jobstatus', '=', '4')
                    ->where(['jobs.job_status' => '1'])
                    ->get();
            if (count($job) > 0) {
                return response()->json(['message' => 'Agent Completed Jobs', 'data' => $job, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'No job is completed by this agent', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }

    /**
     *
     *
     *
     *
     * Get Completed Job list by Customer
     *
     *
     *
     */
    public function customerGetCompletedJob(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        try {
            $job = User::join('assignJobs', 'users.id', '=', 'assignJobs.agent_id')
                    ->join('jobs', 'assignJobs.job_id', '=', 'jobs.id')
                    ->where(['assignJobs.customer_id' => $user->id])
                    ->where('assignJobs.jobstatus', '=', '4')
                    ->where(['jobs.job_status' => '1'])
                    ->get();
            if (count($job) > 0) {
                return response()->json(['message' => 'Agent Completed Jobs for this customer', 'data' => $job, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'No job is completed by this agent', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }

    //----------------------quotaion Modules
    // add quotation to jobs.
    public function addQuotation(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->usertype == 'customer') {
            return response()->json(['message' => 'this is no agent', 'data' => null, 'response_code' => 0], 200);
        }
        $validator = Validator::make($request->all(), [
                    'quotation_price' => 'required',
                    'job_id' => 'required',
                    'assign_id' => 'required',
                    'quotation_description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Insert data error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        $save_data = array(
          'agent_id' => $user->id,
          'job_id' => $request->job_id,
          'quotation_price' => $request->quotation_price,
          'quotation_description' => $request->quotation_description
        );
        try {
            $quot = QuotationModel::where(['agent_id' => $user->id, 'job_id' => $request->job_id ])
            ->get();
            if (count($quot) > 0) {
                $quot[0]->quotation_price = $request->quotation_price;
                $quot[0]->quotation_description = $request->quotation_description;
                $quot[0]->save();
                /*$job = JobsModel::where("id", "=", $request->job_id)->get()->first();
                $job->quotation_price = $request->quotation_price;
                $job->update();*/
                // Update documents table
                foreach ($request->documents as $docurl) {
                    $document = new DocumentsModel();
                    $document->user_id = $user->id;
                    $document->job_id = $request->job_id;
                    $document->fileName = $docurl;
                    $document->save();
                }
                return response()->json(['message' => 'Successfully update quotation', 'data' => $quot[0], 'response_code' => 1], 200);
            }
            if ($question = QuotationModel::create($save_data)) {
                $ajob = AssignJob::findOrFail($request->assign_id);
                $ajob->quotation_id = $question->id;
                $ajob->save();
                $job = JobsModel::where("id", "=", $request->job_id)->get()->first();
                $job->quotation_price = $request->quotation_price;
                $job->update();
                // Update documents table
                foreach ($request->documents as $docurl) {
                    $document = new DocumentsModel();
                    $document->user_id = $user->id;
                    $document->job_id = $request->job_id;
                    $document->fileName = $docurl;
                    $document->save();
                }
                return response()->json(['message' => 'Successfully added quotation', 'data' => $question, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'Addition is failed', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
        }
    }

    // get quotation
    public function getQuotation(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($request->has('quotation_id')) {
            $quotation = QuotationModel::join('jobs', 'jobs.id', '=', 'quotations.job_id')
                            ->where(['quotations.id' => $request->quotation_id])->get();
            return response()->json(['message' => 'Get quotation by quotation ID', 'data' => $quotation, 'response_code' => 1], 200);
        } else {
            if ($user->usertype == 'agent') {
                //$quotations = QuotationModel::join('jobs', 'jobs.id', '=', 'quotations.job_id')
                //                ->where(['quotations.agent_id' => $user->id])->get();
                return response()->json(['message' => 'Get quotation list by agent id', 'data' => null, 'response_code' => 1], 200);
            } else {
                $quotations = JobsModel::join('quotations', 'jobs.id', '=', 'quotations.job_id')
                                ->join('users', 'quotations.agent_id', '=', 'users.id')
                                ->where(['jobs.user_id' => $user->id])
                                ->select()
                                ->addSelect('quotations.id as quotation_id')
                                ->get();
                foreach ($quotations as $k => $quotation) {
                  $res = AssignJob::where('quotation_id', $quotation["quotation_id"])->get()->first();
                  if ($res == null) {
                    unset($quotations[$k]);
                  } else if($res["jobstatus"] != 3){
                    unset($quotations[$k]);
                  }
                }
                return response()->json(['message' => 'Get quotation list by customer id', 'data' => $quotations, 'response_code' => 1], 200);
            }
        }
    }

    //RENEW project
    public function renewJob(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$request->has('jobid')) {
            return response()->json(['message' => 'No jobid', 'data' => null, 'response_code' => 0], 200);
        }
        try {
          $ojob = JobsModel::where(['id'=>$request->jobid])->first();
          $format = 'Y-m-d H:i:s';
          $exp_d = DateTime::createFromFormat($format, $ojob->expired_date);
          $exp_d->modify('+1 year');
          $ojob->expired_date = $exp_d;
          $ojob->job_status = 0;
          $ojob->save();
          $ajob = AssignJob::where(['job_id'=>$ojob->id,'agent_id'=>$request->agent_id,'customer_id'=>$user->id,])->first();
          $ajob->jobstatus = null;
          $ajob->save();
          return response()->json(['message' => 'jobs Successfully renewed', 'data' => null, 'response_code' => 1], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
        }
    }

    //getRatings
    public function getRatings(Request $request)
    {
      $user = JWTAuth::parseToken()->authenticate();

    }

    //addRatings
    public function addRatings(Request $request)
    {
      $user = JWTAuth::parseToken()->authenticate();
      if (!$request->has('agent_id')) {
          return response()->json(['message' => 'No agent_id', 'data' => null, 'response_code' => 0], 200);
      }
      if (!$request->has('rating')) {
          return response()->json(['message' => 'No rating', 'data' => null, 'response_code' => 0], 200);
      }
      $rating = new rating();
        $rating->user_id = $request->agent_id;
        $rating->rating = $request->rating;
        $rating->save();
        return response()->json(['message' => 'Add Rating success', 'response_code' => 1], 200);
      try
      {
        $rating = new rating();
        //$ratings->user_id = $request->agent_id;
        $ratings->rating = $request->rating;
        $rating->save();
        return response()->json(['message' => 'Add Rating success', 'response_code' => 1], 200);
      } catch (\Exception $exception) {
          return response()->json(['message' => 'Server Error', 'data' => $exception, 'response_code' => 0], 500);
      }
    }
}
