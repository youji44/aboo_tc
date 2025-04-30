<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsAudit extends Model{
    protected $table = 'settings_audit';
    protected $guarded  = array('id');
}