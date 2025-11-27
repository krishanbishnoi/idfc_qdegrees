<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
class UsersExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return User::all();
    // }
    public function array(): array
    {
        $data=User::with('roles')->get();
        $final[]=[
            'Name','Role','Email id','phone_number','Employee_id'
        ];
        foreach($data as $k=>$item){
            $final[]=[
                'name'=>$item->name,
                'role'=>implode(',',$item->roles->pluck('name')->toArray()),
                'email'=>$item->email,
                'phone_number'=>$item->mobile,
                'employee_id'=>$item->employee_id    
            ];
        }
        return $final;
    }
}
