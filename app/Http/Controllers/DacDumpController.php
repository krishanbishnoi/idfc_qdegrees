<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DacDump;
use PhpOffice\PhpSpreadsheet\IOFactory;


class DacDumpController extends Controller
{

    public function index(Request $request)
{
    $query = DacDump::query();

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

    return view('dac_dump.index', compact('records'));
}

      public function edit($id)
    {
        $record = DacDump::findOrFail($id);
        return view('dac_dump.edit', compact('record'));
    }

    public function update(Request $request, $id)
{
    $record = DacDump::findOrFail($id);

    // update everything from the form
    $record->update($request->all());

    return redirect()->route('dacdump.index')->with('success', 'Record updated successfully!');
}


    public function uploadForm()
    {
        return view('dac_dump.dac-upload');
    }

    public function uploadFile(Request $request)
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt'
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        // If XLS/XLSX -> convert to CSV
        if ($extension === 'xlsx' || $extension === 'xls') {
            $csvPath = $this->convertXlsxToCsv($file);
            return $this->importCsv($csvPath);
        }

        // If CSV directly
        return $this->importCsv($file->getRealPath());
    }


    private function convertXlsxToCsv($file)
    {
        $csvName = 'dac_csv_' . time() . '.csv';
        $csvPath = storage_path('app/' . $csvName);

        $reader = IOFactory::createReader('Xlsx');
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


    private function importCsv($path)
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $handle = fopen($path, 'r');

        if ($handle === false) {
            return back()->with('error', 'Cannot open CSV file');
        }

        $header = fgetcsv($handle); // skip header

        while (($row = fgetcsv($handle, 10000, ',')) !== false) {

            DacDump::create([
                'sl_no'               => $row[0],
                'PaymentId'           => $row[1],
                'Location'            => $row[2],
                'State'               => $row[3],
                'BranchName'          => $row[4],
                'AgencyId'            => $row[5],
                'AgencyName'          => $row[6],
                'AgentEmail'          => $row[7],
                'AgentName'           => $row[8],
                'AgentId'             => $row[9],
                'ReceiptNo'           => $row[10],
                'ReceiptDate'         => $row[11],
                'ReceiptTime'         => $row[12],
                'Month'               => $row[13],
                'ReferenceNo'         => $row[14],
                'CustomerName'        => $row[15],
                'Product_1'           => $row[16],
                'Current_Bucket_1'    => $row[17],
                'Combo'               => $row[18],
                'CollectionManager'   => $row[19],
                'TotalReceiptAmount'  => $row[20],
                'PaymentMode'         => $row[21],
                'PANCardNo'           => $row[22],
                'BatchID'             => $row[23],
                'BatchIDCreatedDate'  => $row[24],
                'DepositDate'         => $row[25],
                'ENCollect_PayInSlip_ID' => $row[26],
                'CMS_PayInSlip_ID'       => $row[27],
                'DepositAmount'          => $row[28],
                'BBPAY_BATCHACKDATE'    => $row[29],
                'product_group'         => $row[30],
            ]);
        }

        fclose($handle);

        return back()->with('success', 'DAC Dump imported successfully!');
    }
}
