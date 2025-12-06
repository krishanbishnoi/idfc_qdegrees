<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{

    public function selectBranch()
    {
        // Fetch distinct branch names
        $branches = DB::table('dac_dump')
            ->select('BranchName')
            ->distinct()
            ->orderBy('BranchName')
            ->get();
        return view('report.select_branch', compact('branches'));
    }

    public function showBranchData(Request $request)
    {
        $branch = $request->branch;

        return view('report.home', compact('branch'));
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

        return view('report.monthly_analysis', compact('branch', 'products'));
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
        $results = DB::table('dac_dump')
            ->select(
                'AgencyName',
                'AgentId',
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(TotalReceiptAmount) as total_receipt')
            )
            ->where('BranchName', $request->branch)
            ->where('Product_1', $request->product);

        // Apply filters if present
        if ($request->agency) {
            $results->where('AgencyName', $request->agency);
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
        

        // Final result
        $results = $results
            ->groupBy('AgencyName', 'AgentId')
            ->get();

        return view('report.partials.monthly_table', compact('results'))->render();
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

        return view('report.agent_wise_delay_deposition', compact('branch', 'products'));
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
        // dd($request->all());
        $results = DB::table('dac_dump')
            ->select(
                'AgencyName',
                'AgentId',
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(TotalReceiptAmount) as total_receipt')
            )
            ->where('BranchName', $request->branch)
            ->where('Product_1', $request->product);

        // Apply filters if present
        if ($request->agency) {
            $results->where('AgencyName', $request->agency);
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
            $results->where('CollectionManager', $request->pan_required);
        }
        

        // Final result
        $results = $results
            ->groupBy('AgencyName', 'AgentId')
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

        return view('report.agency_wise', compact('branch', 'products'));
    }
}
