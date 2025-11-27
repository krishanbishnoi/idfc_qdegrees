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
use App\SavedAudit;
use App\SavedQcAudit;
use App\Model\BranchRepo;
use App\Model\AgencyRepo;
use App\RedAlert;
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
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 180);
        $user=Auth::user();
        if($user->hasRole('Quality Auditor') && $user->hasRole('Admin')!=true){
            return $this->qaDashboard($request);
        }
        else if($user->hasRole('Quality Control') && $user->hasRole('Admin')!=true){
            return $this->qcDashboard($request);
        }
        else{
            $qa=[];
            $qc=[];
            if($user->hasRole('Admin')){
                $qa=$this->qaDashboardMeta($request);
                $qc=$this->qcDashboardMeta($request);
                $totalalert=RedAlert::count();
            }
            $request->merge(['lob_audit_cycle'=>'current']);
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
                $request->merge([
                    "filterProduct"=>"all","filterlob"=>"all","filteraudit_cycle"=>"All","filteraudit_cycle_custom"=>null,"filterzone"=>"all","filterstate"=>"all","filterbranch"=>"all"
                    ]);
                $filterData=$this->getFilterData($request);
                // $filterData=[];
            }
            $old=$request->all();
            // dd($topCollectionManager,$topAgency,$bottomProductParameter);
            return view('dashboard',compact('lob','totalalert','product','old','productList','topCollectionManager','topAgency','filterData','bottomProductParameter','qa','qc'));
        }
    }
    public function qaDashboardMeta($request){
        $user=Auth::user();
        $ids=[];
        $totalpass=0;
        $totalfaild=0;
        $totalsaved=0;
        $totalpending=0;
        $cycle=['start'=>'1970-01-01','end'=>'2120-12-31'];
        // $cycle=['start'=>'17-06-2020','end'=>'17-06-2020'];
        if($request->has('lob_audit_cycle') && $request->lob_audit_cycle !='custom'){
            $cycle=$this->getAuditCycle($request->lob_audit_cycle);
        }
        else if($request->has('lob_audit_cycle') && $request->lob_audit_cycle =='custom'){
            $dates=explode(' - ',$request->lob_audit_cycle_custom);
            $cycle=['start'=>Carbon::parse($dates[0])->toDateString(),'end'=>Carbon::parse($dates[1])->toDateString()];
        }
        $query=Audit::with('qmsheet');
        if($user->hasRole('Admin')!=true){
            $query->where('audited_by_id',$user->id);
        }
        $audit=$query->get();
        // dd($audit);
        $audit=$audit->filter(function($item) use($cycle){
            return(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59"));
        });
        if($request->has('productlob') && $request->productlob!= 'all'){
            $audit=$audit->filter(function($item) use($request){
                return ($item->qmsheet->lob == $request->productlob);
            });
        }
        if(count($audit)>0){
            $query=Qc::whereIn('audit_id',$audit->pluck('id'))->get()->unique('audit_id');
            // dd($query->pluck('audit_id','id'),$audit->pluck('id'));
            $totalpass=$query->whereIn('status',[1,2])->count();
            $totalfaild=$query->whereIn('status',[3])->count();
        }
        $savedIds=SavedAudit::whereIn('audit_id',$audit->pluck('id'))->get()->pluck('audit_id')->toArray();
        $totalsaved = Audit::whereIn('id',$savedIds)->count();
        $totalAudit=$audit->count();
        $totalpending=$totalAudit-($totalpass+$totalfaild);
        $old=$request->all();
        return ['totalAudit'=>$totalAudit,'old'=>$old,'totalsaved'=>$totalsaved,'totalpending'=>$totalpending,'totalpass'=>$totalpass,'totalfaild'=>$totalfaild];
    }
    public function qaDashboard($request){
        $data=$this->qaDashboardMeta($request);
        $totalAudit=$data['totalAudit'];
        $old=$data['old'];
        $totalsaved=$data['totalsaved'];
        $totalpending=$data['totalpending'];
        $totalpass=$data['totalpass'];
        $totalfaild=$data['totalfaild'];
        return view('dashboardQa',compact('totalAudit','old','totalsaved','totalpending','totalpass','totalfaild'));
    }
    public function qcDashboardMeta($request){
        $ids=[];
        $totalpass=0;
        $totalfaild=0;
        $totalsaved=0;
        $totalpending=0;
        $saved=SavedAudit::all()->pluck('audit_id')->toArray();
        $savedIds=SavedQcAudit::all()->pluck('audit_id')->toArray();
        // dd($savedIds);
        $cycle=['start'=>'1970-01-01','end'=>'2120-12-31'];
        // $cycle=['start'=>'17-06-2020','end'=>'17-06-2020'];
        if($request->has('lob_audit_cycle') && $request->lob_audit_cycle !='custom'){
            $cycle=$this->getAuditCycle($request->lob_audit_cycle);
        }
        else if($request->has('lob_audit_cycle') && $request->lob_audit_cycle =='custom'){
            $dates=explode(' - ',$request->lob_audit_cycle_custom);
            $cycle=['start'=>Carbon::parse($dates[0])->toDateString(),'end'=>Carbon::parse($dates[1])->toDateString()];
        }
        $audit=Audit::with('qmsheet')->whereNotIn('id',$saved)->get();
        $audit=$audit->filter(function($item) use($cycle){
            return(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59"));
        });
        if($request->has('productlob') && $request->productlob!= 'all'){
            $audit=$audit->filter(function($item) use($request){
                return ($item->qmsheet->lob == $request->productlob);
            });
        }
        if(count($audit)>0){
            $queryrow=Qc::whereIn('audit_id',$audit->pluck('id'))->get()->unique('audit_id');
            $query=$queryrow->whereNotIn('audit_id',$savedIds);
            // dd($query->pluck('status','audit_id'),$queryrow->pluck('status','audit_id'),$savedIds);
            $totalpass=$query->where('status',1)->count();
            $totalpassChange=$query->where('status',2)->count();
            $totalfaild=$query->where('status',3)->count();
            // dd($totalpassChange,$totalpass,$totalfaild,$query->pluck('audit_id'));
        }
        $totalsaved = Audit::whereIn('id',$savedIds)->count();
        $totalAudit=$audit->whereIn('id',$queryrow->pluck('audit_id')->toArray())->count();
        // dd($totalAudit,$queryrow->pluck('audit_id')->toArray());
        $totalpending=$totalAudit-($totalpass+$totalfaild+$totalpassChange);
        $totalApproved=($totalpassChange+$totalpass);
        $totalPendingList=$audit->whereNotIn('id',$queryrow->pluck('audit_id')->toArray());
        $old=$request->all();
        return ['totalAudit'=>$totalAudit,'old'=>$old,'totalsaved'=>$totalsaved,'totalpending'=>$totalpending,'totalpass'=>$totalpass,'totalfaild'=>$totalfaild,'totalpassChange'=>$totalpassChange,'totalApproved'=>$totalApproved,'totalPendingList'=>$totalPendingList];
    }
    public function qcDashboard($request){
        $data=$this->qcDashboardMeta($request);
        $totalAudit=$data['totalAudit'];
        $old=$data['old'];
        $totalsaved=$data['totalsaved'];
        $totalpending=$data['totalpending'];
        $totalpass=$data['totalpass'];
        $totalfaild=$data['totalfaild'];
        $totalpassChange=$data['totalpassChange'];
        $totalApproved=$data['totalApproved'];
        $totalPendingList=$data['totalPendingList'];
        return view('dashboardQc',compact('totalAudit','old','totalsaved','totalpending','totalpass','totalfaild','totalpassChange','totalApproved','totalPendingList'));
    }
    public function getProduct(){
        $product=Products::all(['id','name']);
        return $product;
    }
    public function lobBaseData($request){
        // dd($request->lob_audit_cycle);
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
        $qc=Qc::all()->pluck('audit_id')->toArray();
        if($audit!=null){
            $audit=$audit->whereIn('id',$qc);
        }
        // dd($audit->pluck('id'));
        $dataPoint=[];
        foreach($audit as $item){
            $total=0;
            // $total=$item->qmsheet->parameter->map(function($val) use($total){
            //     $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
            //     return $total;
            // });
            // dd($cycle);
            $total=$this->getTotalPoint($item->id);
            $dataPoint[$item->id]=$total;
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if(isset($data[$item->qmsheet->lob])){
                    // $data[$item->qmsheet->lob]=['point'=>$data[$item->qmsheet->lob]['point']+$item->overall_score,'total'=>$data[$item->qmsheet->lob]['total']+array_sum($total->toArray())];
                    $data[$item->qmsheet->lob]=['point'=>$data[$item->qmsheet->lob]['point']+$item->overall_score,'total'=>$data[$item->qmsheet->lob]['total']+$total];
                }
                else{
                    // $data[$item->qmsheet->lob]=['point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                    $data[$item->qmsheet->lob]=['point'=>$item->overall_score,'total'=>$total];
                }
            }
        }
        // dd($dataPoint);
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
        $qc=Qc::all()->pluck('audit_id')->toArray();
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
        $audit=$audit->whereIn('id',$qc);
        $data=[];
        foreach($audit as $item){
            $total=0;
            // $total=$item->qmsheet->parameter->map(function($val) use($total){
            //     $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
            //     return $total;
            // });
            $total=$this->getTotalPoint($item->id);
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if($request->has('productlob') && $request->productlob!='all'){
                    if($request->productlob == $item->qmsheet->lob){
                        if(isset($data[$item->product_id])){
                            // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score)
                            // ,'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                            $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score)
                            ,'total'=>($data[$item->product_id]['total']+$total)];
                        }
                        else{
                            // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                            // ,'total'=>array_sum($total->toArray())];
                            $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                            ,'total'=>$total];
                        }
                    }
                }
                else{
                    if(isset($data[$item->product_id])){
                        // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score),
                        // 'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score),
                        'total'=>($data[$item->product_id]['total']+$total)];
                    }
                    else{
                        // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                        // ,'total'=>array_sum($total->toArray())];
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                        ,'total'=>$total];
                    }
                }
            }
        }
        usort($data, function($a, $b) {
            return (($b['point']/$b['total'])*100)-(($a['point']/$a['total'])*100);
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
            // $total=$item->qmsheet->parameter->map(function($val) use($total){
            //     $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
            //     return $total;
            // });
            $total=$this->getTotalPoint($item->id);
            if($request->has('productlob') && $request->productlob!='all'){
                if($request->productlob == $item->qmsheet->lob){
                    if(isset($data[$item->product_id])){
                        // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score)
                        // ,'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score)
                        ,'total'=>($data[$item->product_id]['total']+$total)];
                    }
                    else{
                        // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                        // ,'total'=>array_sum($total->toArray())];
                        $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                        ,'total'=>$total];
                    }
                }
            }
            else{
                if(isset($data[$item->product_id])){
                    // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score),
                    // 'total'=>($data[$item->product_id]['total']+array_sum($total->toArray()))];
                    $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>($data[$item->product_id]['point']+$item->overall_score),
                    'total'=>($data[$item->product_id]['total']+$total)];
                }
                else{
                    // $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                    // ,'total'=>array_sum($total->toArray())];
                    $data[$item->product_id]=['lob'=>$item->qmsheet->lob,'name'=>$item->product->name,'point'=>$item->overall_score
                    ,'total'=>$total];
                }
            }
        }
        usort($data, function($a, $b) {
            return (($b['point']/$b['total'])*100)-(($a['point']/$a['total'])*100);
        });
        return response()->json(['data'=>$data]);
    }

    public function getBranch($state_id){
        $cids=DB::table('cities')->where('state_id',$state_id)->get(['id','name'])->pluck('id');
        $branch=Branch::whereIn('city_id',$cids)->get(['id','name']);

        return response()->json(['data'=>$branch]);
    }
    public function fetchMapData(Request $request){
        $lob=($request->lob=='all')?['collection','commercial_vehicle','rural','alliance',]:[$request->lob];
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
            $branchrepo=BranchRepo::whereIn('branch_id',$branch)->get()->pluck('id');
            $agencyrepo=AgencyRepo::whereIn('branch_id',$branch)->get()->pluck('id');
        }
        else{
            $branch=Branch::where('id',$request->branch)->whereIn('lob',$lob)->first();
            $agency=Agency::where('branch_id',$branch)->get()->pluck('id');
            $yard=Yard::where('branch_id',$branch)->get()->pluck('id');
            $branchrepo=BranchRepo::whereIn('branch_id',$branch)->get()->pluck('id');
            $agencyrepo=AgencyRepo::whereIn('branch_id',$branch)->get()->pluck('id');
        }
        $qc=Qc::all()->pluck('audit_id')->toArray();
        $query=Audit::with(['qmsheet','branch.city.state','agency.branch.city.state','yard.branch.city.state','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->whereIn('branch_id',$branch)->orWhereIn('yard_id',$yard)
        ->orWhereIn('agency_id',$agency)->orWhereIn('branch_repo_id',$branchrepo)->orWhereIn('agency_repo_id',$agencyrepo);
        $audit=$query->get();
        if($request->product!='all'){
            $audit=$audit->Where('product_id',$request->product);
        }
        $cycle=['start'=>'1970-01-01','end'=>'2120-12-31'];
        if($request->has('audit_cycle') && $request->audit_cycle !='custom'){
            $cycle=$this->getAuditCycle($request->audit_cycle);
        }
        else if($request->has('audit_cycle') && $request->audit_cycle =='custom'){
            $dates=explode(' - ',$request->audit_cycle_custom);
            $cycle=['start'=>Carbon::parse($dates[0])->toDateString(),'end'=>Carbon::parse($dates[1])->toDateString()];
        }
        $audit=$audit->whereIn('id',$qc);
        // dd($audit->pluck('product_id','id'));
        $data=[];
        $dataTotal=[];
        $idTotal=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    $state=$item->branch->city->state->name;
                break;
                case 'repo_yard':
                    $state=$item->yard->branch->city->state->name;
                break;
                case 'agency':
                    $state=$item->agency->branch->city->state->name;
                break;
                case 'branch_repo':
                    $state=$item->branchRepo->branch->city->state->name;
                break;
                case 'agency_repo':
                    $state=$item->agencyRepo->branch->city->state->name;
                break;
            }
            $key=$this->getKey($state);
            // dd($cycle);
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if(isset($data[$key])){
                    $data[$key]=$data[$key]+$item->overall_score;
                    $dataTotal[$key]=$dataTotal[$key]+$this->getTotalPoint($item->id);
                }
                else{
                    $data[$key]=$item->overall_score;
                    $dataTotal[$key]=$this->getTotalPoint($item->id);
                }
                $idTotal[]=$item->id;
            }
        }
        $final=[];
        $total=0;
        $per=0;
        foreach($data as $k=>$val){
            // $final[]=[$k,$val];
            // $total=$total+$val;
            $per=($val/$dataTotal[$k])*100;
            $final[]=[$k,round($per,2)];
            $total=$total+$val;
        }
        if(array_sum($dataTotal)>0){
            $totalper=($total/array_sum($dataTotal))*100;
        }
        else{
            $totalper=0;
        }
        return response()->json(['data'=>$final,'total'=>round($totalper,2),'mainT'=>$total,'final'=>array_sum($dataTotal),'count'=>$idTotal]);
    }
    public function getTotalPoint($id){
        $resultAudit=AuditResult::where('audit_id',$id)->get(['id','audit_id','selected_option','sub_parameter_id'])->where('selected_option','N/A')->pluck('selected_option','sub_parameter_id');
        $item=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter'])->find($id);
        $total=0;
        // dd($resultAudit);
        $total=$item->qmsheet->parameter->map(function($val) use($total,$resultAudit){
            $subTotal=0;
            // $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
            $subTotal=$val->qm_sheet_sub_parameter->map(function($value) use($subTotal,$resultAudit){
                if(!isset($resultAudit[$value->id])){
                    return $value;
                 }
            });
            $total=$total+$subTotal->sum('weight');
            return $total;
        });
        return $total->sum();
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

    public function getStateData($state,Request $request){
            $state_id=DB::table('states')->where('name',$state)->get(['id','name'])->pluck('id');
            $cids=DB::table('cities')->whereIn('state_id',$state_id)->get(['id','name'])->pluck('id');
            $branch=Branch::whereIn('city_id',$cids)->get()->pluck('id');
            $agency=Agency::whereIn('branch_id',$branch)->get()->pluck('id');
            $yard=Yard::whereIn('branch_id',$branch)->get()->pluck('id');
            // $br=BranchRepo::whereIn('branch_id',$branch)->get()->pluck('id');
            // $ar=AgencyRepo::whereIn('branch_id',$branch)->get()->pluck('id');
            $qc=Qc::all()->pluck('audit_id')->toArray();
            $audit=Audit::with(['qmsheet','branch.city.state','agency.branch.city.state','yard.branch.city.state','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->whereIn('branch_id',$branch)
            ->orWhereIn('yard_id',$yard)
            ->orWhereIn('agency_id',$agency)
            // ->orWhereIn('agency_repo_id',$ar)->orWhereIn('branch_repo_id',$br)
            ->get();
            if($audit!=null){
                $audit=$audit->whereIn('id',$qc);
            }
        $data=[];
        $dataTotal=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    $state=$item->branch->city->name;
                break;
                case 'repo_yard':
                    $state=$item->yard->branch->city->name;
                break;
                case 'agency':
                    $state=$item->agency->branch->city->name;
                break;
                case 'branch_repo':
                    $state=$item->branchRepo->branch->city->name ?? '';
                    break;
                case 'agency_repo':
                    $state=$item->agencyRepo->branch->city->name ?? '';
                    break;
            }
            // dump($item->qmsheet->type,$state);
           if(isset($data[$state])){
                $data[$state]=$data[$state]+$item->overall_score;
                $dataTotal[$state]=$dataTotal[$state]+$this->getTotalPoint($item->id);;
                
            }
            else{
                $data[$state]=$item->overall_score;
                $dataTotal[$state]=$this->getTotalPoint($item->id);
            }
        }
        $result=[];
        $total=0;
        $per=0;
        foreach($data as $key=>$item){
            $per=($item/$dataTotal[$key])*100;
            $result[$key]=round($per,2);
            $total=$total+$item;
        }
        if(array_sum($dataTotal)>0){
            $totalper=($total/array_sum($dataTotal))*100;
        }
        else{
            $totalper=0;
        }
        // dd($data,$dataTotal,$audit);
        return response()->json(['data'=>$result,'total'=>round($totalper,2)]);
    }

    function getTopCollectionManager(){
        $qc=Qc::all()->pluck('audit_id')->toArray();
        $audit=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter','branch.branchable','agency.branch.branchable','yard.branch.branchable','branchRepo.branch.branchable','agencyRepo.branch.branchable'])->whereIn('id',$qc)->get();
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    if($item->branch!=null){
                        $user=array_filter($item->branch->branchable->toArray(), function($val) use($item){
                            // dd($val);
                            // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                            return $val['manager_id']==$item->collection_manager_id;
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
                            // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                            return $val['manager_id']==$item->collection_manager_id;
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
                            // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                            return $val['manager_id']==$item->collection_manager_id;
                        });
                        if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                    }
                break;
                case 'agency_repo':
                    // $state=$item->agency->branch->name;
                     $user=array_filter($item->agencyRepo->branch->branchable->toArray(), function($val) use($item){
                        // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        return $val['manager_id']==$item->collection_manager_id;
                    });
                    if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                break;
                case 'branch_repo':
                    // $state=$item->agency->branch->name;
                     $user=array_filter($item->branchRepo->branch->branchable->toArray(), function($val) use($item){
                        // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        return $val['manager_id']==$item->collection_manager_id;
                    });
                    if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                break;
            }
            $total=0;
            
            $resultAudit=AuditResult::where('audit_id',$item->id)->get(['id','audit_id','selected_option','sub_parameter_id'])->where('selected_option','N/A')->pluck('selected_option','sub_parameter_id');
            $total=$item->qmsheet->parameter->map(function($val) use($total,$resultAudit){
                $subTotal=0;
                // $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
                $subTotal=$val->qm_sheet_sub_parameter->map(function($value) use($subTotal,$resultAudit){
                    if(!isset($resultAudit[$value->id])){
                        return $value;
                     }
                });
                $total=$total+$subTotal->sum('weight');
                return $total;
            });
            // $total=$item->qmsheet->parameter->map(function($val) use($total){
            //     $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
            //     return $total;
            // });
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
        // dd($data);
        usort($data, function($a, $b) {
            return (($b['point']/$b['total'])*100)-(($a['point']/$a['total'])*100);
        });
        $top10 = array_slice($data, 0, 10);
        usort($data, function($a, $b) {
            return (($a['point']/$a['total'])*100)-(($b['point']/$b['total'])*100);
        });
        $bottom10 = array_slice($data, 0, 10);
        return ['top'=>$top10,'bottom'=>$bottom10,'data'=>$data];
    }

    function getTopAgency(){
        $qc=Qc::all()->pluck('audit_id')->toArray();
        $audit=Audit::with(['qmsheet.parameter.qm_sheet_sub_parameter','branch.branchable','agency.branch.branchable','yard.branch.branchable'])->where('agency_id','!=',null)->get();
        $audit=$audit->whereIn('id',$qc);
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $total=0;
            // $total=$item->qmsheet->parameter->map(function($val) use($total){
            //     $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
            //     return $total;
            // });
            $total=$this->getTotalPoint($item->id);
           if(isset($data[$item->agency_id])){
                // $data[$item->agency_id]=['name'=>$item->agency->name,'point'=>($data[$item->agency_id]['point']+$item->overall_score)
                // ,'total'=>($data[$item->agency_id]['total']+array_sum($total->toArray()))];
                $data[$item->agency_id]=['name'=>$item->agency->name,'point'=>($data[$item->agency_id]['point']+$item->overall_score)
                ,'total'=>($data[$item->agency_id]['total']+$total)];
            }
            else{
                // $data[$item->agency_id]=['name'=>$item->agency->name,'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                $data[$item->agency_id]=['name'=>$item->agency->name,'point'=>$item->overall_score,'total'=>$total];
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
        $qc=Qc::all()->pluck('audit_id')->toArray();
        $audit=$audit->whereIn('id',$qc);
        // dd($audit);
        $data=[];
        foreach($audit as $k=>$item){
            $state='';
            switch($item->qmsheet->type){
                case 'branch':
                    $user=array_filter($item->branch->branchable->toArray(), function($val) use($item){
                        // dd($val);
                        // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        return $val['manager_id']==$item->collection_manager_id;
                    });
                    if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                    }
                break;
                case 'repo_yard':
                    // $state=$item->yard->branch->name;
                     $user=array_filter($item->yard->branch->branchable->toArray(), function($val) use($item){
                        // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        return $val['manager_id']==$item->collection_manager_id;
                    });
                    if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                    }
                break;
                case 'agency':
                    // $state=$item->agency->branch->name;
                     $user=array_filter($item->agency->branch->branchable->toArray(), function($val) use($item){
                        // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        return $val['manager_id']==$item->collection_manager_id;
                    });
                    if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                    }
                break;
                case 'agency_repo':
                     $user=array_filter($item->agencyRepo->branch->branchable->toArray(), function($val) use($item){
                        // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        return $val['manager_id']==$item->collection_manager_id;
                    });
                    if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                break;
                case 'branch_repo':
                     $user=array_filter($item->branchRepo->branch->branchable->toArray(), function($val) use($item){
                        // return($val['type']=='Collection_Manager' && $val['product_id']==$item->product_id);
                        return $val['manager_id']==$item->collection_manager_id;
                    });
                    if(!empty($user)){
                            $k=array_key_first($user);
                            $state=['name'=>$user[$k]['user']['name'],'id'=>$user[$k]['user']['id']];
                        }
                break;
            }

            $total=0;
            // $total=$item->qmsheet->parameter->map(function($val) use($total){
            //     $total=$total+$val->qm_sheet_sub_parameter->sum('weight');
            //     return $total;
            // });
            $total=$this->getTotalPoint($item->id);
            if(($item->created_at>=$cycle['start']." 00:00:00") && ($item->created_at<=$cycle['end']." 23:59:59")){
                if($request->filterProduct!='all' && $item->product_id == $request->filterProduct){
                    if(isset($data[$state['id']])){
                        // $data[$state['id']]=['name'=>$state['name'],'point'=>($data[$state['id']]['point']+$item->overall_score)
                        // ,'total'=>($data[$state['id']]['total']+array_sum($total->toArray()))];
                        $data[$state['id']]=['name'=>$state['name'],'point'=>($data[$state['id']]['point']+$item->overall_score)
                        ,'total'=>($data[$state['id']]['total']+$total)];
                    }
                    else{
                        // $data[$state['id']]=['name'=>$state['name'],'point'=>$item->overall_score,'total'=>array_sum($total->toArray())];
                        $data[$state['id']]=['name'=>$state['name'],'point'=>$item->overall_score,'total'=>$total];
                    }
                }
                else if($request->filterProduct == 'all'){
                    if(isset($data[$state['id']])){
                        // $data[$state['id']]=['name'=>$state['name'],'point'=>($data[$state['id']]['point']+$item->overall_score)
                        // ,'total'=>($data[$state['id']]['total']+array_sum($total->toArray()))];
                        $data[$state['id']]=['name'=>$state['name'],'point'=>($data[$state['id']]['point']+$item->overall_score)
                        ,'total'=>($data[$state['id']]['total']+$total)];
                    }
                    else{
                        $data[$state['id']]=['name'=>$state['name'],'point'=>$item->overall_score,'total'=>$total];
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
        // $branch=Branch::whereIn('id',$branchId)->get(['id','name']);
        // $br=BranchRepo::whereIn('branch_id',$branchId)->get(['id','name']);
        // $ar=AgencyRepo::whereIn('branch_id',$branchId)->get(['id','name']);
        $audit=Audit::whereIn('agency_id',$agency->pluck('id'))->get()->pluck('overall_score','agency_id');
        // $audit=Audit::whereIn('agency_id',$agency->pluck('id'))->where('collection_manager_id',$id)->get()->pluck('overall_score','agency_id');
        // $branchaudit=Audit::whereIn('branch_id',$branch->pluck('id'))->where('collection_manager_id',$id)->get()->pluck('overall_score','branch_id');
        // $braudit=Audit::whereIn('branch_repo_id',$br->pluck('id'))->where('collection_manager_id',$id)->get()->pluck('overall_score','branch_repo_id');
        // $araudit=Audit::whereIn('agency_repo_id',$ar->pluck('id'))->where('collection_manager_id',$id)->get()->pluck('overall_score','agency_repo_id');
        return response()->json(['data'=>$agency,
        // 'branch'=>$branch,'br'=>$br,'ar'=>$ar,
        // 'branchpoint'=>$branchaudit,'brpoint'=>$braudit,'arpoint'=>$araudit,
        'point'=>$audit]);
    }
    public function getAgencyParameter($agency_id){
        $audit=Audit::with(['audit_parameter_result.parameter_detail','audit_results'])->where('agency_id',$agency_id)->latest()->first();
        $data=['id'=>$audit->id,'audit_parameter_result'=>[]];
        foreach($audit->audit_parameter_result as $k=>$item){
            $data['audit_parameter_result'][$k]=$item;
            $data['audit_parameter_result'][$k]['orignal_weight']=$audit->audit_results->where('parameter_id',$item->id)->sum('score');
        }
        // dd($data);
        return response()->json(['data'=>$data]);
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
        ini_set('memory_limit', '-1');
        $qc=Qc::all()->pluck('audit_id')->toArray();
        $audit=Audit::with(['audit_parameter_result.parameter_detail','audit_parameter_result.result2.sub_parameter_detail'])->get(['id']);
        $audit=$audit->whereIn('id',$qc);
        $data=[];
        foreach($audit as $k=>$item){
            foreach($item->audit_parameter_result as $k=>$value){
                if($value->parameter_detail!=null && $value->orignal_weight!=null || $value->orignal_weight!=0 ){
                    $totalWeight=0;
                    $weight=0;
                    $resultData=$value->result2->where('parameter_id',$value->parameter_id);
                    foreach($resultData as $k=>$rd){
                        $weight=$weight+((int)$rd->score);
                        if($rd->score!='N/A'){
                            $totalWeight=$totalWeight+((int)$rd->sub_parameter_detail->weight);
                        }
                    }
                    $per=0;
                    if($totalWeight>0){
                        $per=($weight/$totalWeight)*100;
                    }
                    if($per>0){
                        $data[]=['name'=>$value->parameter_detail->parameter,'point'=>round($per,2),'id'=>$value->audit_id];
                    }
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
        // dd(['top'=>$top10,'bottom'=>$bottom10]);
        return ['top'=>$top10,'bottom'=>$bottom10];
    }
}
