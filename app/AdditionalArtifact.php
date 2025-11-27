<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdditionalArtifact extends Model
{
    protected $fillable = [
        'agency_id',
        'agency_name_cycle',
        'artifact',
        'audit_id',
        'validate_at',
        'branch_id',
        'yard_id',
        'agency_repo_id',
        'branch_repo_id',
        'yard_repo_id',
        'type',
        'artifact_name',
        'user_id',
        'audit_cycle',
    ];
}

