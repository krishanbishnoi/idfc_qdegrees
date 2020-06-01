<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Allocation;
use App\QmSheet;
use App\User;
use Validator;
use Crypt;
use Auth;
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
}
