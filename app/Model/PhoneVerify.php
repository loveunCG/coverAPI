<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PhoneVerify extends Model
{

  protected $table='phone_verify';
  protected $fillable=['phone_num','verify_num'];

}
