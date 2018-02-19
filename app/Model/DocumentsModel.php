<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class DocumentsModel extends Model
{
    //
    protected $table='documents';
    protected $fillable=['user_id','job_id','document'];
    public function customer()
    {
        return  $this->belongsTo(User::class);
    }
    public function job()
    {
        return  $this->belongsTo(JobsModel::class, 'job_id');
    }
}
