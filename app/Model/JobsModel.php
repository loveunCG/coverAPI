<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class JobsModel extends Model
{
    //
    protected $table='jobs';
    protected $fillable=['user_id','name','nric','phoneno','insurance_type',
       'indicative_sum','address','postcode','state','country','remarks','quotation_price', 'expired_date'];
    public function documentJob()
    {
        return $this->hasMany(DocumentsModel::class, 'job_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedJob()
    {
        return $this->hasMany(AssignJob::class, 'job_id');
    }

    public function quotations()
    {
        return $this->hasMany(QuotationModel::class, 'job_id');
    }
}
