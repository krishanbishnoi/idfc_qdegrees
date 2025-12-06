<?php

namespace App\Uploads;

use Illuminate\Database\Eloquent\Model;

class TrailIntensity extends Model
{
    protected $fillable=['agreement_id','agreement_no','npa_stage_id','bom_bucket','product_flag_1','bom_pos','branch','mailing_state','region','collection_manager_name','agency_code','agency_name','status','date_stamp_1','customer_name','customer_met','last_payment_date',
        'ptp_date','ptp_amount','collection_name',
        'feetback_date','disposition_code','trail_status',
        'date_stamp','remarks','attempts','agent_id',
        'lob'
            ];
    protected $table="trail_intensity";
}
