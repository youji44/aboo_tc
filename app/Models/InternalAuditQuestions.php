<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalAuditQuestions extends Model{
    protected $table = 'internal_audit_questions';
    protected $guarded  = array('id');
}