<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class touchstarUser extends Authenticatable
{
    use HasFactory, Notifiable;
    //
    public $table = "touchstaraccount";
    public $timestamps = false;
    public $fillable = ['touch_acc_email', 'touch_acc_password'];
    public $primaryKey = 'touch_acc_id';
    public $hidden = ['touch_acc_password'];
}
