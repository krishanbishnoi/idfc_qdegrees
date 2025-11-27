<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SavedAudit;
use App\Audit;
use Carbon\Carbon;
use App\Qc;

class DashboardController extends Controller
{
      public function dashboard(Request $request)
    {
    	if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
         $getUser=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$getUser) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
        //
        //set_time_limit(0);
        
        // ini_set('memory_limit', '-1');
        // ini_set('max_execution_time', 600);
        $data=$this->qaDashboardMeta($request, $getUser);
        $totalAudit=$data['totalAudit'];
        $old=$data['old'];
        $totalsaved=$data['totalsaved'];
        $totalpending=$data['totalpending'];
        $totalpass=$data['totalpass'];
        $totalfaild=$data['totalfaild'];
        $jsonOutput = ['totalAudit'=>$totalAudit, 'old'=>$old, 'totalsaved'=>$totalsaved, 'totalpending'=>$totalpending, 'totalpass'=>$totalpass, 'totalfailed'=>$totalfaild];
        
        // return view('dashboardQa',compact('totalAudit','old','totalsaved','totalpending','totalpass','totalfaild'));

         $response=array('status'=>1,'message'=>'Audit Sheet List',
                                'data' => $jsonOutput);       
        return response(json_encode($response), 200);
    }



      public function qaDashboardMeta($request, $user){
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
        
        if(isset($request->product)){
            if($request->product == "all" || $request->product == "All" || $request->product == "ALL"){

            }else {
                
                $query->where('product_id',$request->product);
            }
            
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
}
