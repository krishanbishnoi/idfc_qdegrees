<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AllocationDump;
use App\DacDump;
use Carbon\Carbon;

class SyncCollectionManager extends Command
{
    protected $signature = 'sync:collection-manager';

    protected $description = 'Sync Collection Manager from AllocationDump to DacDump';

    public function handle()
    {
        $this->info("Sync started...");

        AllocationDump::chunk(500, function ($allocationRecords) {

            foreach ($allocationRecords as $alloc) {

                $dac = DacDump::where('ReferenceNo', $alloc->loan_number)->first();

                if ($dac) {

                    /* ----------------------------------------------------------
                   PARSE ALL DATES PROPERLY (dd/mm/yyyy, h:i am/pm)
                ---------------------------------------------------------- */

                    // ReceiptDate → Carbon
                    $receiptDateObj = null;
                    if (!empty($dac->ReceiptDate)) {
                        try {
                            $receiptDateObj = Carbon::createFromFormat('d/m/Y, g:i a', trim($dac->ReceiptDate));
                        } catch (\Exception $e) {
                            $receiptDateObj = null;
                        }
                    }

                    // DepositDate → Carbon
                    $depositDateObj = null;
                    if (!empty($dac->DepositDate)) {
                        try {
                            $depositDateObj = Carbon::createFromFormat('d/m/Y, g:i a', trim($dac->DepositDate));
                        } catch (\Exception $e) {
                            $depositDateObj = null;
                        }
                    }

                    // BBPAY_BATCHACKDATE → Carbon
                    $batchAckDateObj = null;
                    if (!empty($dac->BBPAY_BATCHACKDATE)) {
                        try {
                            $batchAckDateObj = Carbon::createFromFormat('d/m/Y, g:i a', trim($dac->BBPAY_BATCHACKDATE));
                        } catch (\Exception $e) {
                            $batchAckDateObj = null;
                        }
                    }


                    /* ----------------------------------------------------------
                   1. DATE_NEW LOGIC (DATE ONLY)
                ---------------------------------------------------------- */
                    if (empty($batchAckDateObj) && empty($depositDateObj)) {
                        $dateNew = "NA";
                    } elseif (empty($batchAckDateObj)) {
                        $dateNew = $depositDateObj ? $depositDateObj->format('Y-m-d') : null;
                    } else {
                        $dateNew = $batchAckDateObj->format('Y-m-d');
                    }


                    /* ----------------------------------------------------------
                   2. DELAY DEPOSIT (DIFF IN DAYS)
                ---------------------------------------------------------- */

                    if ($depositDateObj && $receiptDateObj) {
                        // DepositDate exists
                        $delayDeposit = $depositDateObj->diffInDays($receiptDateObj);
                    } elseif ($receiptDateObj && $dateNew !== "NA") {
                        // Use dateNew if deposit missing
                        $delayDeposit = Carbon::parse($dateNew)->diffInDays($receiptDateObj);
                    } else {
                        $delayDeposit = null;
                    }


                    /* ----------------------------------------------------------
                   3. DELAY DEPOSIT BUCKET
                ---------------------------------------------------------- */
                    $delayBucket = $this->getDelayDepositBucket($delayDeposit);


                    /* ----------------------------------------------------------
                   4. PAN REQUIRED
                ---------------------------------------------------------- */
                    $panRequired = 0;

                    if (
                        ($dac->TotalReceiptAmount >= 50000) ||
                        (strtoupper($dac->PaymentMode) === "CASH")
                    ) {
                        $panRequired = 1;
                    }


                    /* ----------------------------------------------------------
                   5. PAN STATUS
                ---------------------------------------------------------- */
                    $panStatus = $panRequired === 1 ? "Pan Available" : "Not Required";


                    /* ----------------------------------------------------------
                   6. TIME_BKT (EXTRACT TIME FROM ReceiptDate)
                ---------------------------------------------------------- */
                    if ($receiptDateObj) {
                        $receiptTime = $receiptDateObj->format('H:i:s');
                    } else {
                        $receiptTime = null;
                    }

                    $workingStart = "07:00:00";
                    $workingEnd   = "22:00:00";

                    $timeBkt = ($receiptTime >= $workingStart && $receiptTime <= $workingEnd)
                        ? "Working"
                        : "Not Working";

                    // dd($alloc->collection_manager,$dateNew,$delayDeposit,$delayBucket,$panRequired, $panStatus,$timeBkt,$receiptTime,);
                    /* ----------------------------------------------------------
                   7. SAVE ALL UPDATED FIELDS
                ---------------------------------------------------------- */
                    $dac->update([
                        'CollectionManager'      => $alloc->collection_manager,
                        'date_new'               => $dateNew,
                        'delay_deposit'          => $delayDeposit,
                        'delay_deposit_bucket'   => $delayBucket,
                        'pan_required'           => $panRequired,
                        'pan_status'             => $panStatus,
                        'time_bkt'               => $timeBkt,
                        'ReceiptTime'            => $receiptTime,
                    ]);

                    $this->info("Updated DacDump ID: {$dac->id}");
                }
            }
        });

        $this->info("Sync completed successfully.");
    }

    function getDelayDepositBucket($delayDays)
    {
        if ($delayDays <= -1) {
            return "Deposit Date Not Available";
        } elseif ($delayDays >= 0 && $delayDays <= 2) {
            return "Same Day";
        } elseif ($delayDays >= 3 && $delayDays <= 5) {
            return "3 To 5 Day";
        } elseif ($delayDays >= 6 && $delayDays <= 10) {
            return "6 To 10 Day";
        } elseif ($delayDays >= 11) {
            return "Above 10 Day";
        } else {
            return null;
        }
    }

    // public function handle()
    // {
    //     $this->info("Sync started...");
    //     // process in chunks to avoid memory issues
    //     AllocationDump::chunk(500, function ($allocationRecords) {
    //         foreach ($allocationRecords as $alloc) {
    //             // match loan_number with ReferenceNo 
    //             $dac = DacDump::where('ReferenceNo', $alloc->loan_number)->first();
    //             if ($dac) {
    //                 // update collection manager in dac_dump
    //                 $dac->update([
    //                     'CollectionManager' => $alloc->collection_manager,
    //                 ]);
    //                 $this->info("Updated DacDump ID: {$dac->id}");
    //             }
    //         }
    //     });
    //     $this->info("Sync completed successfully.");
    // }
}
