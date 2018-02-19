<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Model\JobsModel;

class User extends Authenticatable
{
    use Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=['username','email','phoneno','password','verifyToken','status',
                          'devicename','devicetoken','usertype','refferalcode',
                          'longitude','latitude','isAvailable'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $table = 'users';

    public function jobs()
    {
        return $this->hasMany(JobsModel::class, 'user_id');
    }

    public function quotationAgent()
    {
        return $this->hasMany(QuotationModel::class, 'agent_id');
    }
}
