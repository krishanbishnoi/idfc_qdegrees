<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DacDump extends Model
{
    protected $table = 'dac_dump';

    protected $fillable = [
        'sl_no',
        'PaymentId',
        'Location',
        'State',
        'BranchName',
        'AgencyId',
        'AgencyName',
        'AgentEmail',
        'AgentName',
        'AgentId',
        'ReceiptNo',
        'ReceiptDate',
        'ReceiptTime',
        'Month',
        'ReferenceNo',
        'CustomerName',
        'Product_1',
        'Current_Bucket_1',
        'Combo',
        'CollectionManager',
        'TotalReceiptAmount',
        'PaymentMode',
        'PANCardNo',
        'BatchID',
        'BatchIDCreatedDate',
        'DepositDate',
        'ENCollect_PayInSlip_ID',
        'CMS_PayInSlip_ID',
        'DepositAmount',
        'BBPAY_BATCHACKDATE',
        'product_group',

        'date_new',
        'delay_deposit',
        'delay_deposit_bucket',
        'pan_required',
        'pan_status',
        'time_bkt',
        'ReceiptTime',
    ];
}
