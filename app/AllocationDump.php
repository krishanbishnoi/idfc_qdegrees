<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllocationDump extends Model
{
    protected $table = 'allocation_dump';

    protected $fillable = [
        'month',
        'loan_number',
        'productflag_1',
        'product',
        'branch',
        'state',
        'agency_name',
        'agency_yard_code',
        'collection_manager',
        'collection_manager_emp_code',
        'agent_name',
        'agent_sfdc_code',
        'bucket',
    ];
}
