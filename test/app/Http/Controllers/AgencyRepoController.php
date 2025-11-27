<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Branch;
use App\Model\AgencyRepo;
use Illuminate\Support\Facades\Crypt;
use Validator;
use App\Exports\AgencyRepoExport;
use Maatwebsite\Excel\Facades\Excel;
class AgencyRepoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=AgencyRepo::with('branch')->get();
        return view('agencyrepo.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branch=Branch::get(['id', 'name']);
        return view('agencyrepo.create',
            compact('branch')
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
            'location' => 'required',
            'product_name' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
            $data=AgencyRepo::Create(
            [
                'branch_id'=>$request->branch_name,
                'name'=>$request->name,
                'location'=>$request->location,
                'product_id'=>$request->product_name,
            ]);
            if($data){
                return redirect('agencyrepo')->with('success', ['Branch Repo created successfully.']);
            }
            else{
                return redirect()->back()->with('error', ['Branch Repo creation unsuccessfully.']);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch=Branch::get(['id', 'name']);
        $agencyrepo=AgencyRepo::where('id',Crypt::decrypt($id))->first();
        return view('agencyrepo.edit',
            compact('branch','agencyrepo')
        );
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
            'location' => 'required',
            'product_name' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
        $data=AgencyRepo::where('id',Crypt::decrypt($id))->update(
            [
                'branch_id'=>$request->branch_name,
                'name'=>$request->name,
                'location'=>$request->location,
                'product_id'=>$request->product_name,
            ]);
            if($data){
                return redirect('agencyrepo')->with('success', ['Branch Repo updated successfully.']);
            }
            else{
                return redirect()->back()->with('error', ['Branch Repo update unsuccessfully.']);
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
        $data=AgencyRepo::where('id',Crypt::decrypt($id))->delete();
        if($data){
            return redirect('agencyrepo')->with('success', ['Branch Repo deleted successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Branch Repo delete unsuccessfully.']);
        }
    }
    public function excelDownloadAgencyRepo(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 3000);
        return Excel::download(new AgencyRepoExport, 'AgencyRepo.xlsx');
    }
}
