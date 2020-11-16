<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ManageAffiliate extends Model
{
    protected $table = 'manager_affiliate';

    protected $fillable = ['admin_id', 'coach_id', 'link_affiliate'];
}
