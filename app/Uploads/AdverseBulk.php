<?php

namespace App\Uploads;

use Illuminate\Database\Eloquent\Model;

class AdverseBulk extends Model
{
    protected $table="adverse_bukets";
    protected $fillable=['AGRMNTID',
    'PRODUCTFLAG',
    'PRODUCTFLAG_Q',
    'BRANCH',
    'prev_month2_BOM_BUCKET',
    'prev_month1_BOM_BUCKET',
    'month_BOM_BUCKET',
    'prev_month2_BOM_POS',
    'prev_month1_BOM_POS',
    'month_BOM_POS',
    'prev_month2_Agency_Name',
    'prev_month1_Agency_Name',
    'month_Agency_Name',
    'prev_month2_Agent_Code',
    'prev_month1_Agent_Code',
    'month_Agent_Code',
    'Repeat_Agency',
    'Buket_Match_Status',
    'POS_Status',
    'Formula1',
    'Formula2',
    'Formula3',
    'Formula4',
    'Formula5',
    'Formula6',
    'Formula7',
    'Formula8',
    'Formula9',
    'lob'];
}
