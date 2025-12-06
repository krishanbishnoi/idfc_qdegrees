<?php

namespace App\Uploads;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $fillable=['agrmnt_id','agreement_no','npa_stage_id','bom_bucket','product_flag','bom_pos','branch','mailing_state','region','collection_manager','agency_code','agency_name','status','date_stamp','agent_code','agent_name','agent_allocation_status','agent_allocation_date_stamp','remarks',
    
    'lob'];
    protected $table = "collector_allocations";
}
