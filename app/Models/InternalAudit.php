<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalAudit extends Model{
    protected $table = 'internal_audit';
    protected $guarded  = array('id');
}