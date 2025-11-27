<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;
use App\Model\Allocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Audit;
use App\Qc;
use App\AuditCycle;
use App\SavedAudit;
use App\User;
use App\SavedQcAudit;
 


class AllocationController extends Controller
{
    public function getSheets(Request $request){
        if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
         $getUser=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$getUser) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
	  	// $validator = Validator::make($request->all(), [
    //         'user_id' => 'required'
    //     ]);
        // if($validator->fails())
        // {
        //     $data = array('status'=>0, 'message'=>'Beat Plan Id required', 'data'=>$validator->errors());
        //     return response(json_encode($data),200);
        // }
       

    	$data=Allocation::with('user','sheet')->where('user_id',$getUser->id)->get();
        // return view('qa.list',compact('data'));
        $jsonOutput = $data;
        $response=array('status'=>1,'message'=>'Audit Sheet List',
                                'data' => $jsonOutput);       
        return response(json_encode($response), 200);
    }

      public function savedAuditList(Request $request){
        if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
         $getUser=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$getUser) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
        // $validator = Validator::make($request->all(), [
        //      'user_id' => 'required'
        // ]);
        // if($validator->fails()){
        //  $data = array('status'=>0, 'message'=>'Validation Error', 'data'=>$validator->errors());
        //  return response(json_encode($data),200);
        // }
        else{ 


            $user=User::find($getUser->id);
            $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();

            $ids=[];
            $auditIds=Audit::where('audited_by_id',$user->id)->get()->pluck('id');

            if(count($auditIds)>0){
                $ids=Qc::with('user')->whereIn('audit_id',$auditIds)->get()->keyBy('audit_id');
            }
            $savedIds=SavedAudit::all()->pluck('audit_id')->toArray();
            $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail'])->whereIn('id',$savedIds)->where('audited_by_id',$user->id)->whereNotIn('id',$ids)->get();


            $output = array();

            foreach ($data as $key => $row) {
             // echo "<pre/>";
              //print_r($row->qmsheet);
              $audit_date = (string)$row->created_at;
              /* echo (string)$audit_date;
              die; */
            $name='';

                    switch ($row->qmsheet->type) {

                        case 'agency':

                            $name=$row->agency->name ?? '';

                            $branch=$row->agency->branch->name ?? '';

                            $state=$row->agency->branch->city->state->name ?? '';

                            break;

                        case 'branch':

                            $name=$row->branch->name ?? '';

                            $branch=$row->branch->name ?? '';

                            $state=$row->branch->city->state->name ?? '';

                            break;
 
                        case 'repo_yard':

                            $name=$row->yard->name ?? '';

                            $branch=$row->yard->branch->name ?? '';

                            $state=$row->yard->branch->city->state->name ?? '';

                            break;

                        case 'branch_repo':

                            $name=$row->branchRepo->name ?? '';

                            $branch=$row->branchRepo->branch->name ?? '';

                            $state=$row->branchRepo->branch->city->state->name ?? '';

                            break;

                        case 'agency_repo':

                            $name=$row->agencyRepo->name ?? '';

                            $branch=$row->agencyRepo->branch->name ?? '';

                            $state=$row->agencyRepo->branch->city->state->name ?? '';

                            break;

                        

                    }

                    switch($ids[$row->id]->status ?? ''){

                        case 1:

                            $status='Pass with edit';

                        break;

                        case 2:

                            $status='Pass';

                        break;

                        case 3:

                            $status='Failed';

                        break;

                        default:

                            $status=(in_array($row->id,$savedIds))?'Saved':'Submited';

                        break;

                    }


                    $audit_cycle_name = AuditCycle::where('id' , $row->audit_cycle_id)->first();

                    // print_r($row);
                    // die();
                    $rowOutput = ['month'=>\Carbon\Carbon::parse($row->created_at)            ->formatLocalized("%b'%y"),
                                    'audit_date'=>$audit_date,
                                    'lob'=>$row->qmsheet->lob ?? '',
                                    'state'=>$state ?? '',
                                    'qm_sheet_id'=>$row->qm_sheet_id,
                                    'audit_date_by_aud' => $row->audit_date_by_aud,
                                    'audit_cycle_id' => $audit_cycle_name['name'], 
                                    'audit_id'=>$row->id,
                                    'branch'=>$branch,
                                    'product'=>$row->product->name ?? '',
                                    'audit_type'=>$row->qmsheet->type ?? '',
                                    'agency_name'=>$name,
                                    'collection_manager'=>$row->user->name ?? '',
                                    'collection_manager_email'=>$row->user->email ?? '',
                                    'collection_manager_employee_id'=>$row->user->code ?? '',
                                    'auditor_name'=>$row->qa_qtl_detail->name ?? '',
                                    'visited_date_and_time'=>$audit_date,
                                    'status'=>$status ?? '',
                                    'audit_approved_on'=>$ids[$row->id]->created_at  ?? '',
                                    'audit_approved_name'=>$ids[$row->id]->user->name  ?? '',
                                    'artifact'=>$row->artifact_count ?? 0,
                                    'feedback'=>$ids[$row->id]->feedback  ?? '',
                                    // 'location'=> $row->latitude ??
                                    //             {$row->latitude.','.$row->longitude}

                                    ];
                    $output[] = $rowOutput;
                    }








            // dd($auditIds,$user);
            $outputData = ['data'=>$data, 'ids'=>$ids, 'savedIds'=>$savedIds];


            $jsonOutput=array('status'=>1,'message'=>"Saved Audit List.",'data'=> $output);
            return response(json_encode($jsonOutput),200);

        }   
       
    }


     public function submittedAuditList(Request $request){ 
        if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
         $user=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$user) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        } 
        // $validator = Validator::make($request->all(), [
        //      'user_id' => 'required'
        // ]);
        // if($validator->fails()){
        //  $data = array('status'=>0, 'message'=>'Validation Error', 'data'=>$validator->errors());
        //  return response(json_encode($data),200);
        // }
        else{
         
        $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();

            
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
 

        $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail'])->whereIn('id',$auditIds)->where('audited_by_id',$user->id) ->orderBy('id', 'DESC')->get();

        


         $output = array();
            foreach ($data as $key => $row) {
                $audit_date = (string)$row->created_at;
               
            switch ($row->qmsheet->type) {

                        case 'agency':

                            $name=$row->agency->name ?? '';

                            $branch=$row->agency->branch->name ?? '';

                            $state=$row->agency->branch->city->state->name ?? '';

                            break;

                        case 'branch':

                            $name='';

                            $branch=$row->branch->name ?? '';

                            $state=$row->branch->city->state->name ?? '';

                            break;

                        case 'repo_yard':

                            $name=$row->yard->name ?? '';

                            $branch=$row->yard->branch->name ?? '';

                            $state=$row->yard->branch->city->state->name ?? '';

                            break;

                        case 'branch_repo':

                            $name=$row->branchRepo->name ?? '';

                            $branch=$row->branchRepo->branch->name ?? '';

                            $state=$row->branchRepo->branch->city->state->name ?? '';

                            break;

                        case 'agency_repo':

                            $name=$row->agencyRepo->name ?? '';

                            $branch=$row->agencyRepo->branch->name ?? '';

                            $state=$row->agencyRepo->branch->city->state->name ?? '';

                            break;

                    }
              switch($ids[$row->id]->status ?? ''){


                        case 1:

                            $status='Pass with edit';

                        break;
                        
                        case 2:

                            $status='Pass';

                        break;

                        case 3:

                            $status='Failed';

                        break;

                        default:

                            $status=(in_array($row->id,$savedQcIds))?'Saved':'Pending';

                        break;

                    }
                   
                    $audit_cycle_name = AuditCycle::where('id' , $row->audit_cycle_id)->first();
                   
                    $rowOutput = ['month'=>\Carbon\Carbon::parse($row->created_at)            ->formatLocalized("%b'%y"),
                                    'audit_date'=>$audit_date,
                                    'lob'=>$row->qmsheet->lob ?? '',
                                    'state'=>$state ?? '',
                                    'branch'=>$branch,
                                    'product'=>$row->product->name ?? '',
                                    'audit_type'=>$row->qmsheet->type ?? '',
                                    'agency_name'=>$name,
                                    'collection_manager'=>$row->user->name ?? '',
                                    'collection_manager_email'=>$row->user->email ?? '',
                                    'collection_manager_employee_id'=>$row->user->code ?? '',
                                    'auditor_name'=>$row->qa_qtl_detail->name ?? '',
                                    'visited_date_and_time'=>$audit_date,
                                    'status'=>$status ?? '',
                                    'audit_approved_on'=>$ids[$row->id]->created_at  ?? '',
                                    'audit_approved_name'=>$ids[$row->id]->user->name  ?? '',
                                    'artifact'=>$row->artifact_count ?? 0,
                                    'feedback'=>$ids[$row->id]->feedback  ?? '',
                                    // 'location'=> $row->latitude ??
                                    //             {$row->latitude.','.$row->longitude}


                                    'audit_date_by_aud' => $row->audit_date_by_aud,
                                    'audit_cycle_id' => $audit_cycle_name['name'], 

                                    ];
                    $output[] = $rowOutput;
                    }




        // dd($auditIds,$ids);
        $outputData = ['data'=>$data, 'ids'=>$ids, 'savedIds'=>$savedIds];
        $jsonOutput=array('status'=>1,'message'=>"Submitted Audit List.",'data'=> $output);
        return response(json_encode($jsonOutput),200);

        } 

        

    }
}
