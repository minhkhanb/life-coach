<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    protected $table = 'verify_user';

    protected $fillable = ['email', 'confirmation_code', 'time_start', 'time_end'];
}
