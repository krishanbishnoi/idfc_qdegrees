<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Allocation;
use App\QmSheet;
use App\User;
use App\Audit;
use App\SavedAudit;
use App\Qc;
use Validator;
use Crypt;
use Auth;
use App\Exports\AllocationExport;
use Maatwebsite\Excel\Facades\Excel;
class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Allocation::with('user','sheet')->get();
        return view('allocation.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $ids=Allocation::all()->pluck('sheet_id');
        // $sheet=QmSheet::whereNotIn('id',$ids)->get();
        $sheet=QmSheet::all();
        $user=User::role('Quality Auditor')->get();
        return view('allocation.create',compact('sheet','user'));
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
            'sheet_id' => 'required',
            'user_id' => 'required',
            ]);

        if($validator->fails())
        {
            return redirect('allocation/create')
                        ->with('error',[$validator->error()->all()])
                        ->withInput();
        }else
        {
            $allocation=Allocation::create(['sheet_id'=>$request->sheet_id,'user_id'=>$request->user_id]);
            if($allocation){
                return redirect('allocation')->with('success', 'QM Sheet Allocation successfully.');
            }
            else{
                return redirect('allocation/create')->with('error', 'QM Sheet Allocation faild.');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete=Allocation::find(Crypt::decrypt($id))->delete();
        if($delete){
            return redirect('allocation')->with('success', 'QM Sheet Allocation deleted.');
        }
        else{
            return redirect('allocation')->with('error', 'QM Sheet Allocation deletation faild.');
        }
    }
    public function getSheets(){
        $data=Allocation::with('user','sheet')->where('user_id',Auth::user()->id)->get();
        return view('qa.list',compact('data'));
    }
    public function done_audited_list()
    {   
        $user=Auth::user();
        $ids=[];
        $savedIds=SavedAudit::all()->pluck('audit_id')->toArray();
        if($user->hasRole('Admin')){
            $auditIds=Audit::whereNotIn('id',$savedIds)->get()->pluck('id');
        }
        else{
            $auditIds=Audit::where('audited_by_id',$user->id)->whereNotIn('id',$savedIds)->get()->pluck('id');
        }
        if(count($auditIds)>0){
            $ids=Qc::with('user')->whereIn('audit_id',$auditIds)->get()->keyBy('audit_id');
        }
        $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail'])->whereIn('id',$auditIds)->get();
        // dd($auditIds,$ids);
        return view('audit.audit_list_qa',compact('data','ids','savedIds'));
    }
    public function save_audited_list()
    {   
        $user=Auth::user();
        $ids=[];
        $auditIds=Audit::where('audited_by_id',$user->id)->get()->pluck('id');
        if(count($auditIds)>0){
            $ids=Qc::with('user')->whereIn('audit_id',$auditIds)->get()->keyBy('audit_id');
        }
        $savedIds=SavedAudit::all()->pluck('audit_id')->toArray();
        $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail'])->whereIn('id',$savedIds)->whereIn('id',$auditIds)->get();
        // dd($auditIds,$user);
        return view('audit.audit_list_qa',compact('data','ids','savedIds'));
    }
    public function excelDownloadAllocation(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 3000);
        return Excel::download(new AllocationExport, 'Allocation.xlsx');
    }
}
