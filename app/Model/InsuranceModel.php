<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InsuranceModel extends Model
{
    //
    protected $table='insurance_type';

    protected $fillable=['insur_id','insurance_name', 'insur_comment'];
}
