<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Yard;
use App\User;
use App\Agency;
use App\Model\Branch;
use Validator;
use App\Exports\YardExport;
use Maatwebsite\Excel\Facades\Excel;
class YardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Yard::with('branch','agency','user')->get();
        return view('yard.list', 
        compact('data')
    );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $user=User::get(['id', 'name']);
        $user=[];
        $branch=Branch::get(['id', 'name']);
        // $agency=Agency::get(['id', 'name','branch_id','agency_manager']);
        $agency=[];
        return view('yard.create', 
        compact('user','branch','agency')
    );
    }
    public function getAgency($id){
        $data=Agency::where('branch_id',$id)->get(['id', 'name','branch_id']);
        return response()->json($data);
    }
    public function getAgencyManager($id){
        $agency=Agency::find($id,['id','agency_manager']);
        $data=User::where('id',$agency->agency_manager)->get(['id', 'name']);
        return response()->json($data);
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
            'agency_name' => 'required',
            'yard_id' => 'required',
            'agency_manager' => 'required',
            'location' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
        $yard=Yard::create(
            ['name'=>$request->name,
            'branch_id'=>$request->branch_name,
            'agency_id'=>$request->agency_name,
             'yard_id'=>$request->yard_id,
            'agency_manager'=>$request->agency_manager,
            'location'=>$request->location,
            'addresss'=>$request->address]
        );
        if($yard){
            return redirect('yard')->with('success', ['Yard created successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Yard creation unsuccessfully.']);
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
        $data=Yard::where('id',Crypt::decrypt($id))->delete();
        if($data){
            return redirect('yard')->with('success', ['Yard deleted successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Yard deletion unsuccessfully.']);
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
        $data=Yard::find(Crypt::decrypt($id));
        $user=User::get(['id', 'name']);
        $branch=Branch::get(['id', 'name']);
        $agency=Agency::get(['id', 'name','branch_id','agency_manager']);
        return view('yard.edit', 
        compact('data','user','branch','agency')
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
            'agency_name' => 'required',
            'yard_id' => 'required',
            'agency_manager' => 'required',
            'location' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
        $yard=Yard::where('id',Crypt::decrypt($id))->update(
            ['name'=>$request->name,
            'branch_id'=>$request->branch_name,
            'agency_id'=>$request->agency_name,
             'yard_id'=>$request->yard_id,
            'agency_manager'=>$request->agency_manager,
            'location'=>$request->location,
            'addresss'=>$request->address]
        );
        if($yard){
            return redirect('yard')->with('success', ['Yard updated successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Yard updation unsuccessfully.']);
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
    public function excelDownloadYard(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 3000);
        return Excel::download(new YardExport, 'Yard.xlsx');
    }
}
