<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurdAjax extends Model
{
    //
    protected $table ='students_data_ajax';
    protected $fillable=['first_name','last_name','image'];
}
