<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class JobsModel extends Model
{
    //
    protected $table='jobs';
    protected $fillable=['user_id','name','nric','phoneno','insurancetype',
       'indicativesum','address','postcode','state','country','remarks','quotationPrice', 'expired_date'];
    public function documentJob()
    {
        return $this->hasMany(DocumentsModel::class);
    }
    public function customers()
    {
        return $this->belongsTo(User::class);
    }
}
