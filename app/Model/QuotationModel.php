<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class QuotationModel extends Model
{
    //
    protected $table='quotations';
    protected $fillable=['agent_id','job_id','quotation_description','quotation_price'];
    public function jobs()
    {
        return $this->belongsTo(JobsModel::class, 'job_id');
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function assignJob(){
       return $this->hasOne(AssignJob::class);
    }
}
