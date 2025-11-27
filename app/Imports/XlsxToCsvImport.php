<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class XlsxToCsvImport implements ToArray
{
    protected $csvName;

    public function __construct($csvName)
    {
        $this->csvName = $csvName;
    }

    public function array(array $array)
    {
        // Write actual array from XLSX → CSV file
        $f = fopen(storage_path('app/' . $this->csvName), 'w');

        foreach ($array as $row) {
            fputcsv($f, $row);
        }

        fclose($f);
    }
}
