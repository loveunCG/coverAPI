<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class QuotationModel extends Model
{
  protected $table='quotation';
  protected $fillable=['user_id','jod_id','quotation_price','quotation_description'];
  public function quotationJob()
  {
      return $this->hasMany(JobsModel::class);
  }
  public function users()
  {
      return $this->belongsTo(User::class);
  }
}
