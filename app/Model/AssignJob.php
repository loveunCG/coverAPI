<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AssignJob extends Model
{
    //
    protected $table='assignJobs';
    protected $fillable=['agent_id','customer_id','job_id'];

    public function jobs()
    {
        return $this->belongsTo(JobsModel::class, 'job_id');
    }
}
