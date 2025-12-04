<?php

namespace App\Imports;

use App\Holiday;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\ValidationException;

class HolidayImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validate "date"
        $excelDate = $this->convertExcelDate($row['date'], 'date');

        // Validate "working_date"
        $excelWorkingDate = $this->convertExcelDate($row['working_date'], 'working_date');

        return Holiday::updateOrCreate(
            ['date' => $excelDate],
            [
                'day_name'     => $row['day'],
                'holiday_name' => $row['holiday'],
                'working_date' => $excelWorkingDate,
            ]
        );
    }

    private function convertExcelDate($value, $columnName)
    {
        // If cell is empty → error
        if (!$value) {
            throw ValidationException::withMessages([
                $columnName => "❌ Missing value in column: $columnName"
            ]);
        }

        // Case 1: Excel numeric date (GOOD)
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // Case 2: Try to parse manually if user typed a date like 01/02/2025
        $parsed = date_create($value);

        if ($parsed) {
            return $parsed->format('Y-m-d');
        }

        // Case 3: FAIL → Throw validation error
        throw ValidationException::withMessages([
            $columnName => "❌ Invalid date format '$value'. Use Excel date format or MM-DD-YYYY."
        ]);
    }
}
