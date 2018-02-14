<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QuotationModel extends Model
{
    //
    protected $table='quotations';
    protected $fillable=['agent_id','job_id','quotation_description','quotation_price'];
}
