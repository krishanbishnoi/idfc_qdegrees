<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Products;
use App\Model\ProductUser;
use App\User;
use Validator;
use Illuminate\Support\Facades\Crypt;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product=Products::all();
        // dd($product);
        return view('product.list',compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
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
            // 'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
        $yard=Products::create(
            [
            'name'=>$request->name,
            'bucket'=>$request->bucket,
            'type'=>0
            ]
        );
        if($yard){
            return redirect('product')->with('success', ['Product updated successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Product updation unsuccessfully.']);
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
        $data = Products::where('id', Crypt::decrypt($id))->delete();
        if ($data) {
            return redirect()->route('product.index')->with('success', ['Product deleted successfully.']);
        } else {
            return redirect()->back()->with('error', ['Product deletion unsuccessfully.']);
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
        $data=Products::find(Crypt::decrypt($id));
        return view('product.edit', 
        compact('data'));
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
            // 'type' => 'required'
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with('error', [$validator->errors()->all()])->withInput();
        } else {
        $yard=Products::where('id',Crypt::decrypt($id))->update(
            [
                'name'=>$request->name,
                'bucket'=>$request->bucket,
                'type'=>0
            ]
        );
        if($yard){
            return redirect('product')->with('success', ['Product created successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Product creation unsuccessfully.']);
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
    public function hierarchy(){
        $product=Products::all();
        $puser=ProductUser::all()->pluck('user_id');
        $user=User::with('roles')->whereNotIn('id',$puser)->get();
        $finalUser=[];
        foreach($user as $k => $item){
            if(in_array('Area Collection Manager',$item->roles->pluck('name')->toArray())){
                $finalUser['Area Collection Manager'][]=$item;
            }
            if(in_array('Regional Collection Manager',$item->roles->pluck('name')->toArray())){
                $finalUser['Regional Collection Manager'][]=$item;
            }
            if(in_array('Zonal Collection Manager ',$item->roles->pluck('name')->toArray())){
                $finalUser['Zonal Collection Manager'][]=$item;
            }
            if(in_array('National Collection Manager',$item->roles->pluck('name')->toArray())){
                $finalUser['National Collection Manager'][]=$item;
            }
            if(in_array('Group Product Head',$item->roles->pluck('name')->toArray())){
                $finalUser['Group Product Head'][]=$item;
            }
            if(in_array('Head of the Collections',$item->roles->pluck('name')->toArray())){
                $finalUser['Head of the Collections'][]=$item;
            }
        }
        // dd($user);
        return view('product.productHierarchy',compact('product','finalUser'));
    }
    public function doHierarchy(Request $request){
        // dd($request->all());
        $data=[];
        foreach($request->except(['_token','type']) as $k=>$item)
        {
            $data[]=['product_id'=>$request->type,'user_id'=>$item,'type'=>$k];
        }
        $ProductUser=ProductUser::insert($data);
        if($ProductUser){
            return redirect('product')->with('success', ['Product created successfully.']);
        }
        else{
            return redirect()->back()->with('error', ['Product creation unsuccessfully.']);
        }
    }
    public function hierarchyView(){
        $product=ProductUser::all();
        $productids=$product->pluck(['product_id'])->unique();
        $productUserType=$product->pluck(['type'])->unique();
        $productUser=Products::with('productUser')->find($productids);
        return view('product.view',compact('productUser','productUserType'));
    }
}
