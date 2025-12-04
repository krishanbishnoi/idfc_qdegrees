<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AllocationDump;
use App\DacDump;

class SyncCollectionManager extends Command
{
    protected $signature = 'sync:collection-manager';

    protected $description = 'Sync Collection Manager from AllocationDump to DacDump';

    public function handle()
    {
        $this->info("Sync started...");

        // process in chunks to avoid memory issues
        AllocationDump::chunk(500, function ($allocationRecords) {

            foreach ($allocationRecords as $alloc) {

                // match loan_number with ReferenceNo 
                $dac = DacDump::where('ReferenceNo', $alloc->loan_number)->first();

                if ($dac) {
                    // update collection manager in dac_dump
                    $dac->update([
                        'CollectionManager' => $alloc->collection_manager,
                    ]);

                    $this->info("Updated DacDump ID: {$dac->id}");
                }
            }
        });

        $this->info("Sync completed successfully.");
    }
}
