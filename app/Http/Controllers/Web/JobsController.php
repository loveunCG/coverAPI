<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\JobsModel;
use App\Model\InsuranceModel;

class JobsController extends Controller
{
    public function getJobList(Request $request)
    {
        $jobs = JobsModel::all();
        $i = 1;
        if (count($jobs) > 0) {
            foreach ($jobs as $job) {

                if ($job->job_status==1) {
                    $jobStatus = '<a href="#" class="btn btn-primary-alt">Accepted</a>';
                } elseif ($job->job_status==0) {
                    $jobStatus = '<a href="#" class="btn btn-warning-alt">Un Accepted</a>';
                } else {
                    $jobStatus = '<a href="#" class="btn btn-inverse-alt">Rejected</a>';
                }
                $job_list['data'][] = array(
                $i++,
                $job->name,
                $job->nric,
                InsuranceModel::where('insur_id', $job->insurance_type)->first()->insurance_name,
                $job->indicative_sum,
                $job->address,
                $job->postcode,
                $job->state,
                $job->expired_date,
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
        return response()->json($job_list);
    }

    public function removeJob(Request $request){



    }

    public function JobDetailView(Request $request){

    }

}
