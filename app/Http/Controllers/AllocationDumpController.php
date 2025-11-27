<?php

namespace App\Http\Controllers;

use App\AllocationDump;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AllocationDumpImport;
set_time_limit(0);
ini_set('max_execution_time', 0);


class AllocationDumpController extends Controller
{
    // Show file upload form
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
}
