<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Branch;
use App\Model\YardRepo;
use Illuminate\Support\Facades\Crypt;
use Validator;
class YardRepoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=YardRepo::with('branch')->get();
        return view('yardrepo.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branch=Branch::get(['id', 'name']);
        return view('yardrepo.create',
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
            $data=YardRepo::Create(
            [
                'branch_id'=>$request->branch_name,
                'name'=>$request->name,
                'location'=>$request->location,
                'product_id'=>$request->product_name,
            ]);
            if($data){
                return redirect('yardrepo')->with('success', ['Branch Repo created successfully.']);
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
        $yardrepo=YardRepo::where('id',Crypt::decrypt($id))->first();
        return view('yardrepo.edit',
            compact('branch','yardrepo')
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
        $data=YardRepo::where('id',Crypt::decrypt($id))->update(
            [
                'branch_id'=>$request->branch_name,
                'name'=>$request->name,
                'location'=>$request->location,
                'product_id'=>$request->product_name,
            ]);
            if($data){
                return redirect('yardrepo')->with('success', ['Branch Repo updated successfully.']);
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
        $data=YardRepo::where('id',Crypt::decrypt($id))->delete();
        if($data){
            return redirect('yardrepo')->with('success', ['Branch Repo deleted successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Branch Repo delete unsuccessfully.']);
        }
    }
}
