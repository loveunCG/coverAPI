<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InsuranceModel extends Model
{
    //
    protected $table='insurances';

    protected $fillable=['insurance_name', 'insur_comment'];
}
