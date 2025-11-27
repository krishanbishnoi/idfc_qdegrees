<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Agency;
use App\User;
use App\Model\Branch;
use Validator;
use App\Exports\AgencyExport;
use Maatwebsite\Excel\Facades\Excel;
class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Agency::with('branch')->get();
        return view('agency.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user=User::get(['id', 'name']);
        $branch=Branch::get(['id', 'name']);
        return view('agency.create',
        compact('user','branch')
    );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'branch_name' => 'required',
            'agency_id' => 'required',
            'agency_manager' => 'required',
            'location' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
        $Agency=Agency::create(
            ['name'=>$request->name,
            'branch_id'=>$request->branch_name,
            'agency_id'=>$request->agency_id,
            'agency_manager'=>$request->agency_manager,
            'location'=>$request->location,
            'addresss'=>$request->address]
        );
        if($Agency){
            return redirect('agency')->with('success', ['Agency created successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Agency creation unsuccessfully.']);
        }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=Agency::where('id',Crypt::decrypt($id))->delete();
        if($data){
            return redirect('agency')->with('success', ['Agency deleted successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Agency deletion unsuccessfully.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Agency::find(Crypt::decrypt($id));
        $user=User::get(['id', 'name']);
        
        $branch=Branch::get(['id', 'name']);
        return view('agency.edit', compact('data','user','branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'branch_name' => 'required',
            'agency_id' => 'required',
            'agency_manager' => 'required',
            'location' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
        $agency=Agency::where('id',Crypt::decrypt($id))->update(
            ['name'=>$request->name,
            'branch_id'=>$request->branch_name,
            'agency_id'=>$request->agency_id,
            'agency_manager'=>$request->agency_manager,
            'location'=>$request->location,
            'addresss'=>$request->address]
        );
        if($agency){
            return redirect('agency')->with('success', ['Agency updated successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Agency updation unsuccessfully.']);
        }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function excelDownloadAgency(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 3000);
        return Excel::download(new AgencyExport, 'Agency.xlsx');
    }
}
