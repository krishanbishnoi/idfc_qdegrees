<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Branch;
use App\Model\Branchable;
use App\Model\ProductUser;
use App\Agency;
use App\Yard;
use App\Qc;
use App\Model\Products;
use App\Audit;
use App\AuditAlertBox;
use App\AuditParameterResult;
use App\AuditResult;
use App\QmSheet;
use DB;
use Auth;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function getUserBranch(){
        $user=Auth::user();
        $branchable=Branchable::where('manager_id',$user->id)->get()->pluck('branch_id');
        $branch=Branch::whereIn('id',$branchable)->get();
        return $branch->pluck('id');
    }
    public function index(Request $request)
    {
        //
        $lob=$this->lobBaseData($request);
        $product=$this->getProductBaseData($request);
        $productList=$this->getProduct();
        $topCollectionManager=$this->getTopCollectionManager();
        $topAgency=$this->getTopAgency();
        $bottomProductParameter=$this->bottomProductParameter();
        if($request->has('filterlob')){
            $filterData=$this->getFilterData($request);
        }
        else{
            $filterData=[];
        }
        $old=$request->all();
        // dd($topCollectionManager,$topAgency,$bottomProductParameter);
        return view('dashboard',compact('lob','product','old','productList','topCollectionManager','topAgency','filterData','bottomProductParameter'));
    }
    public function getProduct(){
        $product=Products::all(['id','name']);
        return $product;
    }
    public function lobBaseData($request){
        
        $cycle=['start'=>'1970-01-01','end'=>'2120-12-31'];
        if($request->has('lob_audit_cycle') && $request->lob_audit_cycle!='custom'){
            $cycle=$this->getAuditCycle($request->lob_audit_cycle);
        }
        else if($request->has('lob_audit_cycle') && $request->lob_audit_cycle =='custom'){
            $dates=explode(' - ',$request->lob_audit_cycle_custom);
            $cycle=['start'=>Carbon::parse($dates[0])->toDateString(),'end'=>Carbon::parse($dates[1])->toDateString()];
        }
        $audit=[];
        if(!Auth::user()->hasRole('Admin')){
            $branch=$this->getUserBranch();
            if(count($branch)>0){
                $query=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter',
                // 'product','branch','yard','agency'
                ]);
                $agency=Agency::where('branch_id',$branch)->get('id')->pluck('id');
                $yard=Yard::where('branch_id',$branch)->get('id')->pluck('id');
                $query->whereIn('branch_id',$branch)->orWhereIn('agency_id',$agency)->orWhereIn('agency_id',$yard);
                $audit=$query->get();
            }
        }
        else{
            $query=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter',
            // 'product','branch','yard','agency'
            ]);
            $audit=$query->get();
        }
        $data=[];
        foreach($audit as $item){
            $total=0;
            $total=$item->qmsheet->parameter->map(function($val) use($total){
                $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
                return $total;
            });
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if(isset($data[$item->qmsheet->lob])){
                    $data[$item->qmsheet->lob]=['point'=>$data[$item->qmsheet->lob]['point']+$item->overall_score,'total'=>$data[$item->qmsheet->lob]['total']+array_sum($total->toArray())];
                }
                else{
                    $data[$item->qmsheet->lob]=['point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                }
            }
        }
        return $data;
    }

    public function getProductBaseData($request){
        // dd($request->all());
        if(!Auth::user()->hasRole('Admin')){
            $branchIds=$this->getUserBranch();
        }
        else{
            $branchIds=Branch::all()->pluck('id');
        }

        $pids=Branchable::whereIn('branch_id',$branchIds)->get(['id','product_id'])->pluck('product_id');
        $query=Audit::with(['qmsheet','product'])->whereIn('product_id',$pids);
        $cycle=['start'=>'1970-01-01','end'=>'2120-12-31'];
        if($request->has('product_audit_cycle') && $request->product_audit_cycle !='custom'){
            $cycle=$this->getAuditCycle($request->product_audit_cycle);
            // $query->whereBetween('created_at',[$cycle['start']." 00:00:00",$cycle['end']." 23:59:59"]);
        }
        else if($request->has('product_audit_cycle') && $request->product_audit_cycle =='custom'){
            $dates=explode(' - ',$request->product_audit_cycle_custom);
            $cycle=['start'=>Carbon::parse($dates[0])->toDateString(),'end'=>Carbon::parse($dates[1])->toDateString()];
        }
        $audit=$query->get();
        $data=[];
        foreach($audit as $item){
            $total=0;
            $total=$item->qmsheet->parameter->map(function($val) use($total){
                $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
                return $total;
            });
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if($request->has('productlob') && $request->productlob!='all'){
                    if($request->productlob == $item->qmsheet->lob){
                        if(isset($data[$item->product_id])){
                            $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score)
                            ,'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                        }
                        else{
                            $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                            ,'total'=>array_sum($total->toArray())];
                        }
                    }
                }
                else{
                    if(isset($data[$item->product_id])){
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score),
                        'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                    }
                    else{
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                        ,'total'=>array_sum($total->toArray())];
                    }
                }
            }
        }
        usort($data, function($a, $b) {
            return $b['point']-$a['point'];
        });
        $top3 = array_slice($data, 0, 4);
        // dd($data,$top3);
        return $top3;
    }
    function allProduct(Request $request){
        if(!Auth::user()->hasRole('Admin')){
            $branchIds=$this->getUserBranch();
        }
        else{
            $branchIds=Branch::all()->pluck('id');
        }
        $pids=Branchable::whereIn('branch_id',$branchIds)->get(['id','product_id'])->pluck('product_id');
        $audit=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter','product'])->whereIn('product_id',$pids)->get();
        $data=[];
        foreach($audit as $item){
            $total=0;
            $total=$item->qmsheet->parameter->map(function($val) use($total){
                $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
                return $total;
            });
            if($request->has('productlob') && $request->productlob!='all'){
                if($request->productlob == $item->qmsheet->lob){
                    if(isset($data[$item->product_id])){
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score)
                        ,'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                    }
                    else{
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                    }
                }
            }
            else{
                if(isset($data[$item->product_id])){
                    $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score),'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                }
                else{
                    $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                }
            }
        }
        usort($data, function($a, $b) {
            return $b['point']-$a['point'];
        });
        return response()->json(['data'=>$data]);
    }

    public function getBranch($state_id){
        $cids=DB::table('cities')->where('state_id',$state_id)->get(['id','name'])->pluck('id');
        $branch=Branch::whereIn('city_id',$cids)->get(['id','name']);

        return response()->json(['data'=>$branch]);
    }
    public function fetchMapData(Request $request){
        $lob=($request->lob=='all')?['collection','commercial_vehicle','rural','alliance',]:$request->lob;
        $cids=[];
        if($request->zone=='all'){
            if($request->state=='all'){
                $state_id=DB::table('states')->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
            else{
                $state_id=DB::table('states')->where('id',$request->state)->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
        }
        else{
            if($request->state=='all'){
                $state_id=DB::table('states')->where('region_id',$request->zone)->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
            else{
                $state_id=DB::table('states')->where('region_id',$request->zone)->where('id',$request->state)->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
        }
        if($request->branch == 'all'){
            $branch=Branch::whereIn('city_id',$cids)->whereIn('lob',$lob)->get()->pluck('id');
            $agency=Agency::whereIn('branch_id',$branch)->get()->pluck('id');
            $yard=Yard::whereIn('branch_id',$branch)->get()->pluck('id');
        }
        else{
            $branch=Branch::where('id',$request->branch)->whereIn('lob',$lob)->first();
            $agency=Agency::where('branch_id',$branch)->get()->pluck('id');
            $yard=Yard::where('branch_id',$branch)->get()->pluck('id');
        }

        if($request->product=='all'){
            $query=Audit::with(['qmsheet','branch.city.state','agency.branch.city.state','yard.branch.city.state'])->whereIn('branch_id',$branch)->orWhereIn('yard_id',$yard)
            ->orWhereIn('agency_id',$agency);
        }
        else{
            $query=Audit::with(['qmsheet','branch.city.state','agency.branch.city.state','yard.branch.city.state'])->whereIn('branch_id',$branch)->orWhereIn('agency_id',$agency)
            ->orWhereIn('yard_id',$yard)
            ->orWhereIn('product_id',$request->product);
        }
        $cycle=['start'=>'1970-01-01','end'=>'2120-12-31'];
        if($request->has('audit_cycle') && $request->audit_cycle !='custom'){
            $cycle=$this->getAuditCycle($request->audit_cycle);
        }
        else if($request->has('audit_cycle') && $request->audit_cycle =='custom'){
            $dates=explode(' - ',$request->audit_cycle_custom);
            $cycle=['start'=>Carbon::parse($dates[0])->toDateString(),'end'=>Carbon::parse($dates[1])->toDateString()];
        }
        $audit=$query->get();
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    $state=$item->branch->city->state->name;
                break;
                case 'yard':
                    $state=$item->yard->branch->city->state->name;
                break;
                case 'agency':
                    $state=$item->agency->branch->city->state->name;
                break;
            }
            $key=$this->getKey($state);
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if(isset($data[$key])){
                    $data[$key]=$data[$key]+$item->overall_score;
                }
                else{
                    $data[$key]=$item->overall_score;
                }
            }
        }
        $final=[];
        $total=0;
        foreach($data as $k=>$val){
            $final[]=[$k,$val];
            $total=$total+$val;
        }
        return response()->json(['data'=>$final,'total'=>$total]);
    }

    public function getKey($state){
        $data=[
        'Puducherry'=>'in-py',
        'Lakshadweep'=>'in-ld',
        'West Bengal'=>'in-wb',
        'Orissa'=>'in-or',
        'Bihar'=>'in-br',
        'Sikkim'=>'in-sk',
        'Chhattisgarh'=>'in-ct',
        'Tamil Nadu'=>'in-tn',
        'Madhya Pradesh'=>'in-mp',
        'Gujarat'=>'in-2984',
        'Goa'=>'in-ga',
        'Nagaland'=>'in-nl',
        'Manipur'=>'in-mn',
        'Arunachal Pradesh'=>'in-ar',
        'Mizoram'=>'in-mz',
        'Tripura'=>'in-tr',
        'Daman and Diu'=>'in-3464',
        'Delhi'=>'in-dl',
        'Haryana'=>'in-hr',
        'Chandigarh'=>'in-ch',
        'Himachal Pradesh'=>'in-hp',
        'Jammu and Kashmir'=>'in-jk',
        'Kerala'=>'in-kl',
        'Karnataka'=>'in-ka',
        'Dadra and Nagar Haveli'=>'in-dn',
        'Maharashtra'=>'in-mh',
        'Assam'=>'in-as',
        'Andhra Pradesh'=>'in-ap',
        'Meghalaya'=>'in-ml',
        'Punjab'=>'in-pb',
        'Rajasthan'=>'in-rj',
        'Uttar Pradesh'=>'in-up',
        'Uttarkhand'=>'in-ut',
        'Jharkhand'=>'in-jh',
    ];
    // return $data[$state];
    return strtolower($state);
    }

    public function getStateData($state){
            $state_id=DB::table('states')->where('name',$state)->get(['id','name'])->pluck('id');
            $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            $branch=Branch::whereIn('city_id',$cids)->get()->pluck('id');
            $agency=Agency::whereIn('branch_id',$branch)->get()->pluck('id');
            $yard=Yard::whereIn('branch_id',$branch)->get()->pluck('id');
            $audit=Audit::with(['qmsheet','branch','agency.branch','yard.branch'])->whereIn('branch_id',$branch)->orWhereIn('yard_id',$yard)
            ->orWhereIn('agency_id',$agency)->get();
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    $state=$item->branch->name;
                break;
                case 'yard':
                    $state=$item->yard->branch->name;
                break;
                case 'agency':
                    $state=$item->agency->branch->name;
                break;
            }
           if(isset($data[$state])){
                $data[$state]=$data[$state]+$item->overall_score;
            }
            else{
                $data[$state]=$item->overall_score;
            }
        }
        return response()->json(['data'=>$data]);
    }

    function getTopCollectionManager(){
        $audit=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter','branch.branchable','agency.branch.branchable','yard.branch.branchable'])->get();
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    if($item->branch!=null){
                        $user=array_filter($item->branch->branchable->toArray(), function($val) use($item){
                            // dd($val);
                            return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        });
                        if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                    }
                break;
                case 'yard':
                    if($item->yard!=null){
                        $user=array_filter($item->yard->branch->branchable->toArray(), function($val) use($item){
                            return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        });
                        if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                    }
                break;
                case 'agency':
                    // $state=$item->agency->branch->name;
                    if($item->agency!=null){
                        $user=array_filter($item->agency->branch->branchable->toArray(), function($val) use($item){
                            return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        });
                        if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                    }
                break;
            }
            $total=0;
            $total=$item->qmsheet->parameter->map(function($val) use($total){
                $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
                return $total;
            });
            if(isset($state['id'])){
                if(isset($data[$state['id']])){
                     $data[$state['id']]=['name'=>$state['name'],'point'=>($data[$state['id']]['point']+$item->overall_score)
                     ,'total'=>($data[$state['id']]['total']+array_sum($total->toArray()))];
                 }
                 else{
                     $data[$state['id']]=['name'=>$state['name'],'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                 }
            }
        }
        usort($data, function($a, $b) {
            return (($b['point']/$b['total'])*100)-(($a['point']/$a['total'])*100);
        });
        $top10 = array_slice($data, 0, 10);
        usort($data, function($a, $b) {
            return (($a['point']/$a['total'])*100)-(($b['point']/$b['total'])*100);
        });
        $bottom10 = array_slice($data, 0, 10);
        return ['top'=>$top10,'bottom'=>$bottom10];
    }

    function getTopAgency(){
        $audit=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter','branch.branchable','agency.branch.branchable','yard.branch.branchable'])->where('agency_id','!=',null)->get();
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $total=0;
            $total=$item->qmsheet->parameter->map(function($val) use($total){
                $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
                return $total;
            });
           if(isset($data[$item->agency_id])){
                $data[$item->agency_id]=['name'=>$item->agency->name,'point'=>($data[$item->agency_id]['point']+$item->overall_score)
                ,'total'=>($data[$item->agency_id]['total']+array_sum($total->toArray()))];
            }
            else{
                $data[$item->agency_id]=['name'=>$item->agency->name,'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
            }
        }
        usort($data, function($a, $b) {
            return (($b['point']/$b['total'])*100)-(($a['point']/$a['total'])*100);
        });
        $top10 = array_slice($data, 0, 10);
        usort($data, function($a, $b) {
            return (($a['point']/$a['total'])*100)-(($b['point']/$b['total'])*100);
        });
        $bottom10 = array_slice($data, 0, 10);
        return ['top'=>$top10,'bottom'=>$bottom10];
    }


    public function getFilterData($request){
        // dd($request->all());
        $lob=($request->filterlob=='all')?['collection','commercial_vehicle','rural','alliance',]:[$request->filterlob];
        $cids=[];
        if($request->filterzone=='all'){
            if($request->filterstate=='all'){
                $state_id=DB::table('states')->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
            else{
                $state_id=DB::table('states')->where('id',$request->filterstate)->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
        }
        else{
            if($request->filterstate=='all'){
                $state_id=DB::table('states')->where('region_id',$request->filterzone)->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
            else{
                $state_id=DB::table('states')->where('region_id',$request->filterzone)->where('id',$request->filterstate)->get(['id','name'])->pluck('id');
                $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            }
        }
        if($request->filterbranch == 'all'){
            $branch=Branch::whereIn('city_id',$cids)->whereIn('lob',$lob)->get()->pluck('id');
            $agency=Agency::whereIn('branch_id',$branch)->get()->pluck('id');
            $yard=Yard::whereIn('branch_id',$branch)->get()->pluck('id');
        }
        else{
            $branch=Branch::where('id',$request->filterbranch)->whereIn('lob',$lob)->first();
            $agency=Agency::where('branch_id',$branch)->get()->pluck('id');
            $yard=Yard::where('branch_id',$branch)->get()->pluck('id');
        }
        // dd($branch,$agency,$yard);
        if($request->filterProduct=='all'){
            $query=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter','branch.branchable','agency.branch.branchable','yard.branch.branchable'])->whereIn('branch_id',$branch)->orWhereIn('yard_id',$yard)
            ->orWhereIn('agency_id',$agency);
        }
        else{
            $query=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter','branch.branchable','agency.branch.branchable','yard.branch.branchable'])->whereIn('branch_id',$branch)->orWhereIn('agency_id',$agency)
            ->orWhereIn('yard_id',$yard)
            ->Where('product_id',$request->filterProduct);
        }
        $cycle=['start'=>'1970-01-01','end'=>'2120-12-31'];
        if($request->has('filteraudit_cycle') && $request->filteraudit_cycle!='All' && $request->filteraudit_cycle!='custom'){
            $cycle=$this->getAuditCycle($request->filteraudit_cycle);
            // $query->whereBetween('created_at',[$cycle['start']." 00:00:00",$cycle['end']." 23:59:59"]);
        }
        else if($request->has('filteraudit_cycle') && $request->filteraudit_cycle =='custom'){
            $dates=explode(' - ',$request->filteraudit_cycle_custom);
            $cycle=['start'=>Carbon::parse($dates[0])->toDateString(),'end'=>Carbon::parse($dates[1])->toDateString()];
        }
        $audit=$query->get();
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    $user=array_filter($item->branch->branchable->toArray(), function($val) use($item){
                        // dd($val);
                        return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                    });
                    $state=['name'=>$user[0]['user']['name'],'id'=>$user[0]['user']['id']];
                break;
                case 'yard':
                    // $state=$item->yard->branch->name;
                     $user=array_filter($item->yard->branch->branchable->toArray(), function($val) use($item){
                        return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                    });
                    $state=['name'=>$user[0]['user']['name'],'id'=>$user[0]['user']['id']];
                break;
                case 'agency':
                    // $state=$item->agency->branch->name;
                     $user=array_filter($item->agency->branch->branchable->toArray(), function($val) use($item){
                        return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                    });
                    $state=['name'=>$user[0]['user']['name'],'id'=>$user[0]['user']['id']];
                break;
            }

            $total=0;
            $total=$item->qmsheet->parameter->map(function($val) use($total){
                $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
                return $total;
            });
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if($request->filterProduct!='all' && $item->product_id == $request->filterProduct){
                    if(isset($data[$state['id']])){
                        $data[$state['id']]=['name'=>$state['name'],'point'=>($data[$state['id']]['point']+$item->overall_score)
                        ,'total'=>($data[$state['id']]['total']+array_sum($total->toArray()))];
                    }
                    else{
                        $data[$state['id']]=['name'=>$state['name'],'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                    }
                }
                else if($request->filterProduct == 'all'){
                    if(isset($data[$state['id']])){
                        $data[$state['id']]=['name'=>$state['name'],'point'=>($data[$state['id']]['point']+$item->overall_score)
                        ,'total'=>($data[$state['id']]['total']+array_sum($total->toArray()))];
                    }
                    else{
                        $data[$state['id']]=['name'=>$state['name'],'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                    }
                }
            }
        }
        // dd($data);
        return $data;
    }

    public function getagencyOfCollection($id){
        $branchId=Branchable::where(['manager_id'=>$id,'type'=>'Collection_Manager'])->get(['id','branch_id'])->pluck('branch_id');
        $agency=Agency::whereIn('branch_id',$branchId)->get(['id','name']);
        $audit=Audit::whereIn('agency_id',$agency->pluck('id'))->get()->pluck('overall_score','agency_id');
        return response()->json(['data'=>$agency,'point'=>$audit]);
    }
    public function getAgencyParameter($agency_id){
        $audit=Audit::with(['audit_parameter_result.parameter_detail'])->where('agency_id',$agency_id)->first();
        return response()->json(['data'=>$audit]);
    }

    public function getAuditCycle($type){
        $month=Carbon::now()->month;
        $cycle=$this->getCycle($month);
        switch($type){
            case 'current':
                return $cycle;
            break;
            case 'last_2':
                $start=$cycle['start'];
                $end=$cycle['end'];
                $cycle['start']=Carbon::parse($start)->modify('-6 month')->toDateString();
                $cycle['end']=Carbon::parse($end)->modify('-3 month')->toDateString();
                return $cycle;
            break;
            case 'last_3':
               $start=$cycle['start'];
                $end=$cycle['end'];
                $cycle['start']=Carbon::parse($start)->modify('-9 month')->toDateString();
                $cycle['end']=Carbon::parse($end)->modify('-3 month')->toDateString();
                return $cycle;
            break;
            case 'last_4':
                $start=$cycle['start'];
                $end=$cycle['end'];
                $cycle['start']=Carbon::parse($start)->modify('-12 month')->toDateString();
                $cycle['end']=Carbon::parse($end)->modify('-3 month')->toDateString();
                return $cycle;
            break;
        }
    }
    function getCycle($month){
        $startdate='';
        $enddate='';
        $year=Carbon::now()->year;
        $startMonth='';
        $endMonth='';
        if($month>0 && $month<4){
            $startdate=Carbon::parse('1-1-'.$year)->toDateString();
            $enddate=Carbon::parse('31-3-'.$year)->toDateString();
            // $startMonth=0;
            // $endMonth=3;
        }
        else if($month>3 && $month<7){
            $startdate=Carbon::parse('1-4-'.$year)->toDateString();
            $enddate=Carbon::parse('30-6-'.$year)->toDateString();
            // $startMonth=3;
            // $endMonth=6;
        }
        else if($month>6 && $month<10){
            $startdate=Carbon::parse('1-7-'.$year)->toDateString();
            $enddate=Carbon::parse('30-9-'.$year)->toDateString();
            // $startMonth=6;
            // $endMonth=9;
        }
        else if($month>9 && $month<13){
            $startdate=Carbon::parse('1-10-'.$year)->toDateString();
            $enddate=Carbon::parse('31-12-'.$year)->toDateString();
            // $startMonth=9;
            // $endMonth=12;
        }
        return ['start'=>$startdate,'end'=>$enddate];

    }
    public function bottomProductParameter(){
        $audit=Audit::with(['audit_parameter_result.parameter_detail'])->get();
        $data=[];
        foreach($audit as $k=>$item){
            foreach($item->audit_parameter_result as $k=>$value){
                if($value->parameter_detail!=null){
                    $data[]=['name'=>$value->parameter_detail->parameter,'point'=>$value->orignal_weight];
                }
                }
            }

        usort($data, function($a, $b) {
            return $b['point']-$a['point'];
        });
        $top10 = array_slice($data, 0, 10);
        usort($data, function($a, $b) {
            return $a['point']-$b['point'];
        });
        $bottom10 = array_slice($data, 0, 10);
        return ['top'=>$top10,'bottom'=>$bottom10];
    }
}
