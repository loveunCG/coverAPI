<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ReferralCodeModel extends Model
{
    //
    protected $table='referralcode';
    protected $fillable=['user_id','rederral_code'];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
