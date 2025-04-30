<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocations extends Model{
    protected $table = 'user_locations';
    protected $guarded  = array('id');
}