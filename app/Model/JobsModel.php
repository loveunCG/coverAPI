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
        return $this->hasMany(DocumentsModel::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
