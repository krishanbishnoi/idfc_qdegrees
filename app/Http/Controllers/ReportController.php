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
        ->where('Product_1', $request->product)
        ->where('AgencyName', $request->agency)
        ->where('PaymentMode', $request->payment_mode)
        ->groupBy('AgencyName', 'AgentId')
        ->get();

    return view('report.partials.monthly_table', compact('results'))->render();
}





}