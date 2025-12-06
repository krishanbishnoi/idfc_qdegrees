<?php

namespace App\Uploads;

use Illuminate\Database\Eloquent\Model;

class Dacs extends Model
{
    protected $fillable=['si','PaymentId','Location','BranchId','BranchName','AgencyId','AgencyName','AgentEmail','AgentName','Agent_Id','ReceiptBookNo','ReceiptNo','PhysicalReceiptNo_online_transaction_ID','ReceiptDate','Month','ReferenceNo','CUSTOMERNAME','PRODUCT','CURRENT_BUCKET','PaymentTowards','EMIAMT','LatePaymentPenalty','BounceChargesAmt','Excess','IMD','ProcFee','Swap','EBCCharge','CollectionPickupCharge','ForeclosureAmount','TotalReceiptAmount','PaymentMode','InstrumentDate','InstrumentNo','InstrumentAmount','MICRCode','PANCardNo','BatchID','BatchIDCreatedDate','DepositDate','ENCollect_Pay_in_slip_ID','CMS_Pay_In_Slip_ID','DepositAccountNumber','Rectified_Depslip_number','DepositAmount','PaymentStatus','DepositSlipNo_Status','Finnone_Update','Vintage','a','s','MerchantReferenceNumber','MID','BankTransactionId','BankTId','Amount','StatusCode','CreatedDate','RRN','AuthCode','CardNumber','CardType','CardHolderName','ProductGroup','MerchantId','MerchantTransactionId','BBPayPartnerAgentCode','BBPayPartnerAgentEmailId','BBPayPartnerAgentMobileNumber','BBPayPartnerBranchCode','BBPayBatchAckDate','DepositeBankName','lob'];
    protected $table = "dacs";
}
