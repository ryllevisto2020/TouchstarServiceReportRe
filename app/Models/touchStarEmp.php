<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class touchStarEmp extends Model
{
    //
    public $table = "touchstaremployee";
    public $timestamps = false;
    public $fillable = [
        'emp_first_name',
        'emp_last_name',
        'emp_phone',
        'emp_viber',
        'emp_socmed',
        'emp_deparment',
        'emp_position',
        'emp_role',
        'emp_signature',
        'emp_profile',
        'emp_status'
    ];
}
