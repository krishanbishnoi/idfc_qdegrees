<?php

namespace App\Imports;

use App\AllocationDump;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Support\Facades\Storage;




class AllocationDumpImport implements ToModel, WithHeadingRow
{

    
    public function model(array $row)
    {
        return new AllocationDump([
            'month'                         => $row['month'],
            'loan_number'                   => $row['loan_number'],
            'productflag_1'                 => $row['productflag_1'],
            'product'                       => $row['product'], 
            'branch'                        => $row['branch'],
            'state'                         => $row['state'],
            'agency_name'                   => $row['agency_name'],
            'agency_yard_code'              => $row['agency_code'],
            'collection_manager'            => $row['collection_manager'],
            'collection_manager_emp_code'   => $row['collection_manager_employee_code'],
            'agent_name'                    => $row['agent_name'],
            'agent_sfdc_code'               => $row['agect_id'],
            'bucket'                        => $row['bucket'],
        ]);
    }
}

