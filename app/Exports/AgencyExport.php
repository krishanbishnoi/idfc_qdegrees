<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Agency;
class AgencyExport implements FromArray,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        //
        $data=Agency::with(['branch.branchable'=>function($q){
            $q->with('product')->where('type','Collection_Manager');
        },'branch.city.state.region'])->get();
        // dd($data->first());
        $final=[];
        foreach($data as $item){
            foreach($item->branch->branchable as $val){
                $final[]=[
                    'LOB'=>$item->branch->lob ?? '',
                    'Zone'=>$item->branch->city->state->region->name ?? '',
                    'Agency_Code'=>$item->agency_id,
                    'Agency_Name'=>$item->name,
                    'Agency_Location'=>$item->location,
                    'Agency_Address'=>$item->addresss,
                    'Branch'=>$item->branch->name,
                    'Product'=>$val->product->name ?? '',
                    'Collection_manager_name'=>$val->user->name ?? '', 
                ];
            }
        }
        return $final;
    }
    public function headings(): array
    {
        return [
            'LOB',
            'Zone',
            'Agency_Code',
            'Agency_Name',
            'Agency_Location',
            'Agency_Address',
            'Branch',
            'Product',
            'Collection_manager_name',
        ];
    }
}
