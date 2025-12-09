<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{

    public function selectBranch(Request $request)
    {
        // Fetch branches
        $branches = DB::table('dac_dump')
            ->select('BranchName')
            ->distinct()
            ->orderBy('BranchName')
            ->get();

        // Branch selected only when POST
        $branch = $request->branch ?? null;

        return view('report.select_branch', compact('branches', 'branch'));
    }


    public function monthly($branch)
    {
        // Fetch distinct products for selected branch
        $products = DB::table('dac_dump')
            ->select('Product_1')
            ->where('BranchName', $branch)
            ->distinct()
            ->orderBy('Product_1')
            ->get();

        $cycle = DB::table('dac_dump')->distinct()->pluck('Month');

        return view('report.monthly_analysis', compact('branch', 'products', 'cycle'));
    }
    public function getDelayBuckets($branch, $product)
    {
        return DB::table('dac_dump')->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->select('delay_deposit_bucket')
            ->whereNotNull('delay_deposit_bucket')
            ->distinct()
            ->orderBy('delay_deposit_bucket')
            ->get();
    }

    public function getlocation($branch, $product)
    {
        return DB::table('dac_dump')->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->select('Location')
            ->whereNotNull('Location')
            ->distinct()
            ->orderBy('Location')
            ->get();
    }

    public function getpanrequired($branch, $product)
    {
        return DB::table('dac_dump')->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->select('pan_required')
            ->whereNotNull('pan_required')
            ->distinct()
            ->orderBy('pan_required')
            ->get();
    }

    public function getAgencies($branch, $product)
    {
        return DB::table('dac_dump')
            ->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->select('AgencyName')
            ->distinct()
            ->orderBy('AgencyName')
            ->get();
    }


    public function getPaymentModes($branch, $product)
    {
        return DB::table('dac_dump')
            ->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->select('PaymentMode')
            ->distinct()
            ->orderBy('PaymentMode')
            ->get();
    }




    public function monthlySearch(Request $request)
    {
        // Get months array safely (handle if empty)
        $months = $request->filled('months') ? array_filter(explode(',', $request->months)) : [];

        // Base query
        $query = DB::table('dac_dump')
            ->select('AgencyName', 'AgentId')
            ->where('BranchName', $request->branch)
            ->where('Product_1', $request->product);

        // Apply filters if present
        if ($request->agency)        $query->where('AgencyName', $request->agency);
        if ($request->payment_mode)  $query->where('PaymentMode', $request->payment_mode);
        if ($request->delay_bucket)  $query->where('delay_deposit_bucket', $request->delay_bucket);
        if ($request->location)      $query->where('Location', $request->location);
        if ($request->pan_required)  $query->where('pan_required', $request->pan_required);

        // Add dynamic month columns using bindings to avoid SQL syntax errors
        foreach ($months as $m) {
            // Create a safe alias: remove any char that is not letter, number or underscore
            $safe = preg_replace('/[^A-Za-z0-9_]/', '_', $m);

            // Use parameter bindings (the '?' will be safely replaced with the month string)
            // Note: put backticks around Month column name in case it's a reserved word
            $query->selectRaw("SUM(CASE WHEN `Month` = ? THEN 1 ELSE 0 END) AS count_{$safe}", [$m]);
            $query->selectRaw("SUM(CASE WHEN `Month` = ? THEN TotalReceiptAmount ELSE 0 END) AS receipt_{$safe}", [$m]);
        }

        // Group and fetch
        $results = $query->groupBy('AgencyName', 'AgentId')->get();

        // Pass months to view so blade can build dynamic headers
        return view('report.partials.monthly_table', compact('results', 'months'))->render();
    }



    public function agentWise($branch)
    {
        // Fetch distinct products for selected branch
        $products = DB::table('dac_dump')
            ->select('Product_1')
            ->where('BranchName', $branch)
            ->distinct()
            ->orderBy('Product_1')
            ->get();

        $cycle = DB::table('dac_dump')->distinct()->pluck('Month');

        return view('report.agent_wise_delay_deposition', compact('branch', 'products', 'cycle'));
    }


    public function getCollectionManager($branch, $product)
    {
        return DB::table('dac_dump')
            ->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->select('CollectionManager')
            ->distinct()
            ->orderBy('CollectionManager')
            ->get();
    }

    public function getTimeBkt($branch, $product)
    {
        return DB::table('dac_dump')
            ->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->select('time_bkt')
            ->distinct()
            ->orderBy('time_bkt')
            ->get();
    }


    public function agentWiseSearch(Request $request)
    {
        $months = $request->filled('months') ? array_filter(explode(',', $request->months)) : [];
        $results = DB::table('dac_dump')
            ->select(
                'AgencyName',
                'AgentId',
                'AgentName',
                DB::raw("DATE(STR_TO_DATE(ReceiptDate, '%d/%m/%Y, %h:%i %p')) as receipt_date"),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(TotalReceiptAmount) as total_receipt')
            )
            ->where('BranchName', $request->branch)
            ->where('Product_1', $request->product);

        // Apply filters if present
        if ($request->agency) {
            $results->where('AgencyName', $request->agency);
        }

        if (!empty($months)) {
            $results->whereIn('Month', $months);
        }

        if ($request->payment_mode) {
            $results->where('PaymentMode', $request->payment_mode);
        }

        if ($request->delay_bucket) {
            $results->where('delay_deposit_bucket', $request->delay_bucket);
        }

        if ($request->location) {
            $results->where('Location', $request->location);
        }

        if ($request->pan_required) {
            $results->where('pan_required', $request->pan_required);
        }

        if ($request->collection_manager) {
            $results->where('CollectionManager', $request->collection_manager);
        }
        if ($request->pan_status) {
            $results->where('pan_status', $request->pan_status);
        }

        // FINAL GROUPING
        $results = $results
            ->groupBy(
                'AgencyName',
                'AgentId',
                'AgentName',
                DB::raw("DATE(STR_TO_DATE(ReceiptDate, '%d/%m/%Y, %h:%i %p'))")
            )
            ->get();

        return view('report.partials.agent_wise_table', compact('results'))->render();
    }


    public function agencyWise($branch)
    {
        // Fetch distinct products for selected branch
        $products = DB::table('dac_dump')
            ->select('Product_1')
            ->where('BranchName', $branch)
            ->distinct()
            ->orderBy('Product_1')
            ->get();

        $cycle = DB::table('dac_dump')->distinct()->pluck('Month');

        return view('report.agency_wise', compact('branch', 'products', 'cycle'));
    }

    //     public function agencyWiseSearch(Request $request)
    // {
    //     $results = DB::table('dac_dump')
    //         ->select(
    //             'AgencyName',
    //             'AgentId',
    //             'AgentName',
    //             DB::raw("DATE(STR_TO_DATE(ReceiptDate, '%d/%m/%Y, %h:%i %p')) as receipt_date"),
    //             DB::raw('COUNT(*) as total_count'),
    //             DB::raw('SUM(TotalReceiptAmount) as total_receipt')
    //         )
    //         ->where('BranchName', $request->branch)
    //         ->where('Product_1', $request->product);

    //     // Apply filters if present
    //     if ($request->agency) {
    //         $results->where('AgencyName', $request->agency);
    //     }

    //     if ($request->payment_mode) {
    //         $results->where('PaymentMode', $request->payment_mode);
    //     }

    //     if ($request->delay_bucket) {
    //         $results->where('delay_deposit_bucket', $request->delay_bucket);
    //     }

    //     if ($request->location) {
    //         $results->where('Location', $request->location);
    //     }

    //     if ($request->pan_required) {
    //         $results->where('pan_required', $request->pan_required);
    //     }

    //     if ($request->collection_manager) {
    //         $results->where('CollectionManager', $request->pan_required);
    //     }

    //     // FINAL GROUPING
    //     $results = $results
    //         ->groupBy(
    //             'AgencyName',
    //             'AgentId',
    //             'AgentName',
    //             DB::raw("DATE(STR_TO_DATE(ReceiptDate, '%d/%m/%Y, %h:%i %p'))")
    //         )
    //         ->get();

    //     return view('report.partials.agent_wise_table', compact('results'))->render();
    // }
    public function agencyWiseSearch(Request $request)
    {
        $branch = $request->branch;
        $product = $request->product;
        $agency = $request->agency;
        $time_bkt = $request->time_bkt;
        $months = explode(',', $request->months);   // Selected months

        // BUCKETS
        $buckets = [
            "Same Day",
            "3 To 5 Day",
            "6 To 10 Day",
            "Above 10 Day",
            "Deposit Date Not Available"
        ];

        $rawData = DB::table('dac_dump')
            ->select(
                'AgencyName',
                'Month',
                'delay_deposit_bucket',
                DB::raw('COUNT(*) as bucket_count'),
                DB::raw('SUM(TotalReceiptAmount) as bucket_amount')
            )
            ->where('BranchName', $branch)
            ->where('Product_1', $product)
            ->whereIn('Month', $months)
            ->when($agency, fn($q) => $q->where('AgencyName', $agency))
            ->when($time_bkt, fn($q) => $q->where('time_bkt', $time_bkt))
            ->groupBy('AgencyName', 'Month', 'delay_deposit_bucket')
            ->get();

        // FORMAT DATA FOR VIEW
        $result = [];

        foreach ($rawData as $row) {
            $agency = $row->AgencyName;
            $month  = $row->Month;
            $bucket = $row->delay_deposit_bucket;

            // Initialize structure
            foreach ($months as $m) {
                foreach ($buckets as $b) {
                    $result[$agency][$m][$b]['count']  = $result[$agency][$m][$b]['count']  ?? 0;
                    $result[$agency][$m][$b]['amount'] = $result[$agency][$m][$b]['amount'] ?? 0;
                }
            }

            // Assign values
            $result[$agency][$month][$bucket]['count']  = $row->bucket_count;
            $result[$agency][$month][$bucket]['amount'] = $row->bucket_amount;
        }

        return view('report.agency_wise_table', compact('result', 'months', 'buckets'));
    }
}
