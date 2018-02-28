<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    //

    protected $table='companys';
    protected $fillable=['company_name','insurance_id','company_conment'];
    public function insurance()
    {
        return $this->belongsTo(InsuranceModel::class, 'insurance_id');
    }
}
