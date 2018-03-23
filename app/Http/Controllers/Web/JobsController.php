<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\JobsModel;
use App\Model\InsuranceModel;
use App\Model\AssignJob;
use App\Model\QuotationModel;
use App\Model\DocumentsModel;
use App\User;

class JobsController extends Controller
{
    public function getJobList(Request $request)
    {
        $jobs = JobsModel::all();
        $i = 1;
        try {
            if (count($jobs) > 0) {
                foreach ($jobs as $job) {
                    if ($job->job_status==1) {
                        $jobStatus = '<a href="#" class="btn btn-primary-alt">Completed</a>';
                    } elseif ($job->job_status==0) {
                        $jobStatus = '<a href="#" class="btn btn-warning-alt">In progreesing</a>';
                    } else {
                        $jobStatus = '<a href="#" class="btn btn-inverse-alt">Rejected</a>';
                    }
                    $job_id = '<input type="hidden" class="job_id" value="' . $job->id . '">';
                    try {
                        $insurance_name = InsuranceModel::findOrFail($job->insurance_type)->insurance_name;
                        $user_name = User::findOrFail($job->user_id)->username;
                    } catch (\Exception $e) {
                        $user_name = "undefinded";
                        $insurance_name = "undefinded";
                    }
                    $job_list['data'][] = array(
                      $i++.$job_id,
                      $job->name,
                      $job->nric,
                      $insurance_name,
                      $job->indicative_sum,
                      $job->address,
                      $job->postcode,
                      $job->state,
                      $job->expired_date,
                      $user_name,
                      $jobStatus,
                    );
                }
            } else {
                $job_list['data'][0] = array(
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' ',
                ' '
              );
            }
        } catch (\Exception $e) {
            $job_list['data'][0] = array(
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' '
          );
            return response()->json($job_list);
        }
        return response()->json($job_list);
    }

    public function removeJob(Request $request)
    {
        if ($request->has('job_id')) {
            $job_id = $request->job_id;
            try {
                JobsModel::findOrFail($job_id)->delete();
                return response()->json(['message' => 'Delete successfully!', 'data' => null, 'response_code' => 1], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Delete failed!', 'data' => null, 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'Please insert Job ID', 'data' => null, 'response_code' => 0], 200);
        }
    }

    public function JobDetailView(Request $request)
    {
        if ($request->has('job_id')) {
            try {
                $job_id = $request->job_id;
                $job_data = JobsModel::findOrFail($job_id);
                $user = $job_data->users;
                $assignedJob = $job_data->assignedJob;
                $quotatioin = $job_data->quotations;
                return response()->json([
                  'message' => 'successfully!',
                  'data' => $job_data,
                  'response_code' => 1
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Can not get job Data!', 'data' => $e, 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'job_id is required', 'data' => null, 'response_code' => 0], 200);
        }
    }

    public function getQuotationList(Request $request)
    {
        $send_data = [];

        if ($job_id = $request->job_id) {
            $quotations = QuotationModel::where('job_id', $job_id)->get();
        } else {
            $quotations = QuotationModel::all();
        }
        try {
            if (count($quotations)>0) {
                $i = 1;
                foreach ($quotations as $quotation) {
                    $send_data['data'][]=[
                    $i++,
                    $quotation->agent->username,
                    $quotation->quotation_price,
                    $quotation->quotation_description,
                    $quotation->jobs->job_status
                  ];
                }
            } else {
                $send_data['data'][0]=[
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
              ];
            }
        } catch (\Exception $e) {
            $send_data['data'][0]=[
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
          ];
        }
        return response()->json($send_data);
    }

    public function getDocumentList(Request $request)
    {
        $send_document_list = [];
        if ($request->has('job_id')) {
            $document_lists = DocumentsModel::where('job_id', $request->job_id)->get();
        } else {
            $document_lists = DocumentsModel::all();
        }
        if (count($document_lists) > 0) {
            $index = 1;
            try {
                foreach ($document_lists as $document) {
                    $send_document_list['data'][] = [
                    $index++,
                    $document->customer->username,
                    $document->document
                  ];
                }
            } catch (\Exception $e) {
                $send_document_list['data'][0] =[
                '',
                '',
                '',
                '',
              ];
            }
        } else {
            $send_document_list['data'][0] =[
            '',
            '',
            '',
            '',
          ];
        }
        return response()->json($send_document_list);
    }

    public function getAssignedJobList(Request $request)
    {
        $send_assignedList = [];
        if ($request->has('job_id')) {
            $assignLists = AssignJob::where('job_id', $request->job_id)->get();
        } else {
            $assignLists = AssignJob::all();
        }
        if (count($assignLists) > 0) {
            $index = 1;
            try {
                foreach ($assignLists as $job) {
                    if ($job->jobstatus == 1) {
                        $status = '<a href="#" class="btn btn-primary-alt">Accepted</a>';
                    } elseif ($job->jobstatus == 2) {
                        $status = '<a href="#" class="btn btn-primary-alt">Rejected</a>';
                    } elseif ($job->jobstatus == 3) {
                        $status = '<a href="#" class="btn btn-primary-alt">Completed</a>';
                    } else {
                        $status = '<a href="#" class="btn btn-primary-alt">Assigning</a>';
                    }
                    $send_assignedList['data'][] = [
                      $index++,
                      User::findOrFail($job->customer_id)->username,
                      User::findOrFail($job->agent_id)->username,
                      $status
                    ];
                }
            } catch (\Exception $e) {
                $send_assignedList['data'][0] =[
                  '',
                  '',
                  '',
                  '',
                ];
            }
        } else {
            $send_assignedList['data'][0] =[
              '',
              '',
              '',
              '',
            ];
        }
        return response()->json($send_assignedList);
    }
}
