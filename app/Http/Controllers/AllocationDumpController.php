<?php

namespace App\Http\Controllers;

use App\AllocationDump;
use App\DacDump;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AllocationDumpImport;
set_time_limit(0);
ini_set('max_execution_time', 0);


class AllocationDumpController extends Controller
{
    // Show file upload form
     public function index(Request $request)
{
    $query = AllocationDump::query();

    if ($request->search) {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            $q->where('loan_number', 'like', "%$search%")
              ->orWhere('branch', 'like', "%$search%")
              ->orWhere('state', 'like', "%$search%")
              ->orWhere('agency_name', 'like', "%$search%")
              ->orWhere('productflag_1', 'like', "%$search%")
              ->orWhere('product', 'like', "%$search%");
        });
    }

    $records = $query->paginate(20);

    return view('allocation_dump.index', compact('records'));
}

public function count_allocation()
{
    $counts = AllocationDump::select('agency_name', DB::raw('COUNT(*) as total'))
    ->groupBy('agency_name')
    ->get()
    ->toArray();


    return $counts;
}

public function count_allocation_cm()
{
    $cm = AllocationDump::select('collection_manager')
    ->distinct()
    ->pluck('collection_manager');



    return $cm;
}

public function count_allocation_product()
{
    $product = AllocationDump::select('product')
    ->distinct()
    ->pluck('product');



    return $product;
}

public function count_allocation_branch()
{
    $branch = AllocationDump::select('branch')
    ->distinct()
    ->pluck('branch');


    return $branch;
}

public function show_allocation_summary()
{
    // Agency + Total Rows
    $counts = AllocationDump::select('agency_name', DB::raw('COUNT(*) as total'))
        ->groupBy('agency_name')
        ->orderBy('agency_name')
        ->get();

    // Collection Manager + Count
    $cm = AllocationDump::select('collection_manager', DB::raw('COUNT(*) as total'))
        ->groupBy('collection_manager')
        ->orderBy('collection_manager')
        ->get();

    // Product + Count
    $product = AllocationDump::select('product', DB::raw('COUNT(*) as total'))
        ->groupBy('product')
        ->orderBy('product')
        ->get();

    // Branch + Count
    $branch = AllocationDump::select('branch', DB::raw('COUNT(*) as total'))
        ->groupBy('branch')
        ->orderBy('branch')
        ->get();

    return view('allocation_summary', compact('counts', 'cm', 'product', 'branch'));
}

public function agencyDetails($agency)
{
    $data = AllocationDump::where('agency_name', $agency)->get();

    return response()->json($data);
}
public function filterByAgency($value)
{
    return AllocationDump::where('agency_name', $value)->get();
}

public function filterByCM($value)
{
    return AllocationDump::where('collection_manager', $value)->get();
}

public function filterByProduct($value)
{
    return AllocationDump::where('product', $value)->get();
}

public function filterByBranch($value)
{
    return AllocationDump::where('branch', $value)->get();
}




    public function edit($id)
{
    $record = AllocationDump::findOrFail($id);
    
    

    return view('allocation_dump.edit', compact('record'));
}

    public function update(Request $request, $id)
    {
        $record = AllocationDump::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('allocationdump.index')->with('success', 'Record updated successfully!');
    }
    public function uploadForm()
    {   set_time_limit(0);
    ini_set('max_execution_time', 0);
        return view('allocation_dump.allocation-upload');
    }

    // Handle uploaded file
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt'
        ]);

        $extension = $request->file('file')->getClientOriginalExtension();

        if ($extension === 'xlsx' || $extension === 'xls') {
            // Convert XLSX → CSV
            $csvPath = $this->convertXlsxToCsv($request->file('file'));

            // Now import CSV instead
            return $this->importCsv(new \SplFileObject($csvPath));
        }

        // If already CSV
        return $this->importCsv($request->file('file'));
    }


    // ✔ ADD THIS FUNCTION
    private function convertXlsxToCsv($file)
{
    set_time_limit(0);
    ini_set('max_execution_time', 0);

    $csvName = 'converted_' . time() . '.csv';
    $csvPath = storage_path('app/' . $csvName);

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(true);

    $spreadsheet = $reader->load($file->getRealPath());
    $sheet = $spreadsheet->getActiveSheet();

    $fh = fopen($csvPath, 'w');

    foreach ($sheet->getRowIterator() as $row) {
        $data = [];
        foreach ($row->getCellIterator() as $cell) {
            $data[] = $cell->getValue();
        }
        fputcsv($fh, $data);
    }

    fclose($fh);
    return $csvPath;
}



    private function importCsv($file)
    {
        set_time_limit(0);
    ini_set('max_execution_time', 0);
        ini_set('auto_detect_line_endings', true);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return back()->with('error', 'Could not read CSV file');
        }

        $header = fgetcsv($handle); // skip header

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {
            AllocationDump::create([
                'month'                         => $row[0],
                'loan_number'                   => $row[1],
                'productflag_1'                 => $row[2],
                'product'                       => $row[3],
                'branch'                        => $row[4],
                'state'                         => $row[5],
                'agency_name'                   => $row[6],
                'agency_yard_code'              => $row[7],
                'collection_manager'            => $row[8],
                'collection_manager_emp_code'   => $row[9],
                'agent_name'                    => $row[10],
                'agent_sfdc_code'               => $row[11],
                'bucket'                        => $row[12],
            ]);
        }

        fclose($handle);

        return back()->with('success', 'CSV imported successfully!');
    }



    public function allocationdac(Request $request)
{
    $query = DacDump::query();

// Only rows where CollectionManager has value
$query->whereNotNull('CollectionManager')
      ->where('CollectionManager', '!=', '');

// Search filter
if ($request->search) {
    $search = $request->search;

    $query->where(function($q) use ($search) {
        $q->where('ReceiptNo', 'like', "%$search%")
          ->orWhere('BranchName', 'like', "%$search%")
          ->orWhere('Location', 'like', "%$search%")
          ->orWhere('AgencyName', 'like', "%$search%")
          ->orWhere('Product_1', 'like', "%$search%")
          ->orWhere('AgentName', 'like', "%$search%");
    });
}

$records = $query->paginate(20);


    return view('allocation_dump.allocationdac', compact('records'));
}

 public function allocationdacedit($id)
{
    $dac = DacDump::findOrFail($id);
    $record = AllocationDump::where('loan_number', $dac->ReferenceNo)->first();

    // If $dac is null, create an empty object or set default values
    if (!$dac) {
        $dac = (object) [
            'sl_no' => '',
            'PaymentId' => '',
            'Location' => '',
            'State' => '',
            'BranchName' => '',
            'AgencyId' => '',
            'AgencyName' => '',
            'AgentEmail' => '',
            'AgentName' => '',
            'AgentId' => '',
            'ReceiptNo' => '',
            'ReceiptDate' => '',
            'ReceiptTime' => '',
            'Month' => '',
            'ReferenceNo' => '',
            'CustomerName' => '',
            'Product_1' => '',
            'Current_Bucket_1' => '',
            'Combo' => '',
            'CollectionManager' => '',
            'TotalReceiptAmount' => '',
            'PaymentMode' => '',
            'PANCardNo' => '',
            'BatchID' => '',
            'BatchIDCreatedDate' => '',
            'DepositDate' => '',
            'ENCollect_PayInSlip_ID' => '',
            'CMS_PayInSlip_ID' => '',
            'DepositAmount' => ''
        ];
    }

    return view('allocation_dump.allocationdacview', compact('record', 'dac'));
}
}
