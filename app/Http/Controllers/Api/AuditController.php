<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\SavedAudit;
use App\SavedQcAudit;
use App\Audit;
use App\Qc;
use App\User;
use App\QmSheet;
use App\Model\Branch;
use App\Agency;
use App\Model\BranchRepo;
use App\TempArtifact;
use App\Model\AgencyRepo;
use App\Yard;
use Auth;
use DB;
use App\AuditQc;
use App\AuditParameterResult;
use App\QcParameterResult;
use App\QmSheetParameter;
use App\QmSheetSubParameter;

use App\Artifact;
use App\AuditResult;
use App\QcResult;
use App\Model\Branchable;
use App\Model\Products;
use URL;
use App\RedAlert;


class AuditController extends Controller
{
    public function render_audit_sheet(Request $request){

            //dd(all_non_scoring_obs_options(1));

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
            //     'qm_sheet_id' => 'required'
            // ]);
            // if($validator->fails()) {
            //     $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            //     return response(json_encode($data), 200);
            // }
            else{
               // $data = QmSheet::with('parameter.qm_sheet_sub_parameter')->find(Crypt::decrypt($qm_sheet_id));
                // id = 1
                $qm_sheet_id = $request->qm_sheet_id;
                $data = QmSheet::with('parameter.qm_sheet_sub_parameter')->find($qm_sheet_id);
            // $branch=Branch::all();
            // $agency=Agency::all();
            // $yard=Yard::all();
            // if($user_role = Auth::user()->roles()->first()->name == 'Quality Auditor'){
            //     $brancid_data =Branchable::distinct()->where('auditor_id',Auth::user()->id)->where('status',1)->get()->pluck('branch_id');
            //     $branch=Branch::where('lob',$data->lob)->whereIn('id',$brancid_data)->get();
            // }
            // else{

            //     $branch=Branch::where('lob',$data->lob)->get();

            // }
     
            $branch=Branch::where('lob',$data->lob)->orderBy('name', 'ASC')->get();

            $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();
            
            /* print_r($agency);
            die; */

            $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

            $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

            $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

            // return view('audit.render_sheet',compact('qm_sheet_id','data','branch','agency','yard','branchRepo','agencyRepo'));

            $output = ['qm_sheet_id'=>$qm_sheet_id, 'data'=>$data, 'branch'=>$branch,
                        'agency'=>$agency, 'yard'=>$yard, 'branchRepo'=>$branchRepo, 
                        'agencyRepo'=>$agencyRepo];

            $jsonOutput=array('status'=>1,'message'=>"Submitted Audit List.",'data'=> $output);
            return response(json_encode($jsonOutput),200);

            // return view('audit.render_sheet',compact('qm_sheet_id','data','branch'));

            } 
     
    }


    public function render_audit_sheet_edit(Request $request){
        if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
         $user=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$user) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }

        $validator = Validator::make($request->all(), [
            'audit_id' => 'required'
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        }

        //dd(all_non_scoring_obs_options(1));

        $result=Audit::where('id',$request->audit_id)->first();
        $full_data = array();
        $full_data['sheet_detail'] = QmSheet::find($result->qm_sheet_id);
        /* $full_data['sheet_detail'][''] = $result-> */
        $parameters = QmSheetParameter::where('qm_sheet_id',$result->qm_sheet_id)->get();
        
        foreach($parameters as $key=>$para){
            $full_data['sheet_detail']['parameter'][$key] = $para;
            $audit_parameter_result = AuditParameterResult::where('parameter_id',$para['id'])->where('audit_id',$request->audit_id)->first();
            
            $full_data['sheet_detail']['parameter'][$key]['orignal_weight'] = $audit_parameter_result->orignal_weight;
            $full_data['sheet_detail']['parameter'][$key]['temp_weight'] = $audit_parameter_result->temp_weight;
            $full_data['sheet_detail']['parameter'][$key]['with_fatal_score'] = $audit_parameter_result->with_fatal_score;
            $full_data['sheet_detail']['parameter'][$key]['without_fatal_score'] = $audit_parameter_result->without_fatal_score;
            $full_data['sheet_detail']['parameter'][$key]['with_fatal_score_per'] = $audit_parameter_result->with_fatal_score_per;
            $full_data['sheet_detail']['parameter'][$key]['without_fatal_score_pre'] = $audit_parameter_result->without_fatal_score_pre;
            $full_data['sheet_detail']['parameter'][$key]['is_critical'] = $audit_parameter_result->is_critical;

            $sub_parameter = QmSheetSubParameter::where('qm_sheet_parameter_id',$para['id'])->get();
            $sub = array();
            foreach($sub_parameter as $key1=>$sub_para){
                $sub['subparameter'][$key1] = $sub_para;
                $audit_result = AuditResult::where('sub_parameter_id',$sub_para['id'])->where('audit_id',$request->audit_id)->first();
                $sub['subparameter'][$key1]['selected_option'] = $audit_result['selected_option'];
                $sub['subparameter'][$key1]['is_critical'] = $audit_result['is_critical'];
                $sub['subparameter'][$key1]['score'] = $audit_result['score'];
                $sub['subparameter'][$key1]['failure_reason'] = $audit_result['failure_reason'];
                $sub['subparameter'][$key1]['remark'] = $audit_result['remark'];
                $sub['subparameter'][$key1]['is_percentage'] = $audit_result['is_percentage'];
                $sub['subparameter'][$key1]['selected_per'] = $audit_result['selected_per'];
                $sub['subparameter'][$key1]['option_selected'] = $audit_result['option_selected'];
            }
            /* echo json_encode($sub);
            die; */
            $full_data['sheet_detail']['parameter'][$key]['subparameter'] = $sub['subparameter'];
        
        }
        
        /* echo json_encode($full_data);
        die; */
        /* $resultPar=AuditParameterResult::where('audit_id',$result->id)->get()->keyBy('parameter_id');
        $resultSubPar=AuditResult::where('audit_id',$result->id)->get()->keyBy('sub_parameter_id');
        $data = QmSheet::with('parameter.qm_sheet_sub_parameter.artifact')->find($result->qm_sheet_id);
        $branch=Branch::where('lob',$data->lob)->get();
        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();
        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();
        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $artifactIds = Artifact::where('audit_id',$result->id)->get()->pluck('id')->toArray();
        $redalertIds = RedAlert::where('audit_id',$result->id)->get()->pluck('sub_parameter_id')->toArray();
        */
        // dd($data,$artifactIds,$result->id);

       // return view('audit.render_sheet_edit',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','artifactIds','redalertIds'));

        /* $output = ['audit_id'=>$request->audit_id, 'data'=>$data,'result'=>$result,
                     'resultPar'=>$resultPar, 'resultSubPar'=>$resultSubPar, 'branch'=>$branch,
                        'agency'=>$agency, 'yard'=>$yard, 'branchRepo'=>$branchRepo, 
                        'agencyRepo'=>$agencyRepo, 'agencyRepo'=>$agencyRepo, 'artifactIds'=>$artifactIds,
                        'redalertIds'=>$redalertIds
                    ]; */
        $output = ['audit_details'=>$result,'sheet_details'=>$full_data['sheet_detail']
        ];

        $jsonOutput=array('status'=>1,'message'=>"Submitted Audit List.",'data'=> $output);
            return response(json_encode($jsonOutput),200);
    }


 

    public function getProduct(Request $request){
        if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
         $user=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$user) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type'=>'required',
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200); 

         }else{
            
            $id = $request->id;
            $type = $request->type;

            if($type=='branch'){
            $productIds=Branchable::where('branch_id',$id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        }
        else if($type=='agency'){
            $agency=Agency::with('user')->find($id);
            $productIds=Branchable::where('branch_id',$agency->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        }
        else if($type=='yard' || $type=='repo_yard'){
            $yard=Yard::with('user')->find($id);
            $productIds=Branchable::where('branch_id',$yard->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        } 
        else if($type=='branch_repo'){
            $branch_repo=BranchRepo::find($id);
           $productIds=Branchable::where('branch_id',$branch_repo->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        } 
        else if($type=='agency_repo'){
            $agency_repo=AgencyRepo::find($id);
            $productIds=Branchable::where('branch_id',$agency_repo->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        }
        $jsonOutput=array('status'=>1,'message'=>'Product List for for dropdown','data'=> $branchable);
        return response(json_encode($jsonOutput),200); 
        // return response()->json(['data'=>$branchable]);
         }
    }


    public function renderBranch(Request $request){
        // echo "string";die;
        
        if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
        $user=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$user) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type'=>'required',
            'product_id'=>'required'
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        }else{

            $id = $request->id;
            $type = $request->type;
            $product_id = $request->product_id;

            $agency=[];
            $yard=[];
            $AgencyRepo=[];
            $BranchRepo=$myData=$managerData=[];
            $code='';
            $bucket='';

            // dd($type);
            if($type=='branch'){
                $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id)->latest();
                },'city'])->where('id',$id)->first();

            }
            else if($type=='agency'){
                $agency=Agency::with('user')->find($id);
                $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
                },'city'])->where('id',$agency->branch_id)->first();
            }
            else if($type=='yard' || $type=='repo_yard'){
                $yard=Yard::with('user')->find($id);
                $agency=Agency::with('user')->find($yard->agency_id);
                $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                    $q->where('product_id',$product_id);
                },'city'])->where('id',$yard->branch_id)->first();
            }
            else if($type=='branch_repo'){
                $BranchRepo=BranchRepo::find($id);
                $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id)->latest();
                },'city'])->where('id',$BranchRepo->branch_id)->first();
            }
            else if($type=='agency_repo'){
                $AgencyRepo=AgencyRepo::find($id);
                $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
                },'city'])->where('id',$AgencyRepo->branch_id)->first();
            }
            foreach($branchable->branchable as $item)
            {
                
                 $mydata[$item->type]=$item;
                 foreach($mydata as $collectionmanager)
                 {
                    $managerData[$item->type]= array('id'=>$collectionmanager->user->id,'name'=>$collectionmanager->user->name,'employee_id'=>$collectionmanager->user->employee_id,'bucket'=>$collectionmanager->bucket);
                 }
            }

            $output = ['branchable'=>$branchable, 'type'=>$type, 'agency'=>$agency,
                        'yard'=>$yard, 'agencyRepo'=>$AgencyRepo, 'branchRepo'=>$BranchRepo,'managers_data'=>$managerData];
            

            $jsonOutput=array('status'=>1,'message'=>"Render Branch",'data'=> $output);
            return response(json_encode($jsonOutput),200); 
            // return view('audit.branch',compact('branchable','type','agency','yard','AgencyRepo','BranchRepo'));

        } 
    }

    public function store_audit(Request $request)
    {   
        // dd("fefef");
     if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
         $getUser=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$getUser) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
        
        // $request = json_decode($request->data,200);
        // dd($request->all());
        /* echo $request->submission_data['qm_sheet_id'];
        die; */

        /* $new_ar = new Audit;
        $new_ar->qm_sheet_id = 1;
        $new_ar->audited_by_id = 1;
        $new_ar->is_critical = 1;
        $new_ar->save(); */


        DB::beginTransaction();


        try 
        {
            if(isset($request->submission_data['audit_id'])){
                logger($request);
                $user_role = $getUser->roles()->first()->name;
                $latlong = explode(" ",$request->submission_data['geotag']);
                // dd($request->all());
                // calculate score following code done by shailendra kumar
                $total = 0;
                $parameter_total = 0;
                $audit_crital = 0;
                $pera_score = [];
                foreach($request->parameters as $key => $para){
                    $pera_total = 0;
                    $pera_fatal = 0;
                    $pera_without_fatal = 0;
                    $pera_is_critical = 0;
                    $total_temp_weight = 0;
                    $pera_fatal_per = 0;
                    $pera_without_fatal_par = 0;
                    $is_critical_para = 0;
                    $subtotal = 0;
                    foreach($para['subs'] as $sub_para){
                        $paramterValue = 0;
                        if($sub_para['temp_weight'] != "N/A"){
                            $total_temp_weight = $total_temp_weight + $sub_para['temp_weight'];
                        } else {
                            $total_temp_weight = $total_temp_weight + 0;
                        }
                        if($sub_para['option'] != 'N/A'){
                            $paramterValue = $sub_para['score'];
                            
                            if($sub_para['score'] == 'N/A') {
                                $pera_fatal = $pera_fatal + 0;
                            } else {
                                $pera_fatal = $pera_fatal + $sub_para['score'];
                            }
                        }
                        if($sub_para['option'] != 'Critical'){
                            if($sub_para['score'] == 'N/A'){
                                $subtotal = $subtotal + 0;
                            } else {
                                $subtotal = $subtotal + $sub_para['score'];
                                $parameter_total = $parameter_total + $paramterValue;
                            }
                            
                        } else {
                            $subtotal = 0;
                            $is_critical_para = 1;
                            $audit_crital = 1;
                            $parameter_total = $parameter_total + $paramterValue;
                            break;
                        }

                    }
                    $total = $total + $subtotal;
                    if($total_temp_weight != 0){
                        $fat_score = round(($pera_fatal/$total_temp_weight)*100);
                        $wfat_score = round(($subtotal/$total_temp_weight)*100);
                    } else {
                        $fat_score = 0;
                        $wfat_score = 0;
                    }
                    $para_id = $para['id'];
                    $pera_score[$para_id]['pera_total'] = $subtotal;
                    $pera_score[$para_id]['total_temp_weight'] = $total_temp_weight;
                    $pera_score[$para_id]['pera_fatal'] = $pera_fatal;
                    $pera_score[$para_id]['pera_without_fatal'] = $subtotal;
                    $pera_score[$para_id]['pera_fatal_per'] = $fat_score;
                    $pera_score[$para_id]['pera_without_fatal_par'] = $wfat_score;
                    $pera_score[$para_id]['pera_is_critical'] = $is_critical_para;
                }
                $overall_score = $total;
                $new_ar = Audit::find($request->submission_data['audit_id']);
                $new_ar->latitude = $latlong[0];
                $new_ar->longitude = $latlong[1];
                $new_ar->qm_sheet_id = $request->submission_data['qm_sheet_id'];
                $new_ar->audit_date_by_aud = $request->submission_data['audit_date_by_aud'];
                $new_ar->audit_cycle_id = $request->submission_data['audit_cycle_id'];
                $new_ar->audited_by_id = $getUser->id;
                $new_ar->is_critical = $audit_crital;
                $new_ar->overall_score = $overall_score;
                // $new_ar->audit_date = Carbon::now()->format('Y-m-d');

                // $new_ar->with_fatal_score_per = $request->submission_data['overall_score'];

                $new_ar->branch_id = (isset($request->submission_data['branch_id']))?$request->submission_data['branch_id']:null;

                $new_ar->agency_id = (isset($request->submission_data['agency_id']))?$request->submission_data['agency_id']:null;

                $new_ar->yard_id = (isset($request->submission_data['yard_id']))?$request->submission_data['yard_id']:null;

                $new_ar->branch_repo_id = (isset($request->submission_data['branch_repo_id']))?$request->submission_data['branch_repo_id']:null;

                $new_ar->agency_repo_id = (isset($request->submission_data['agency_repo_id']))?$request->submission_data['agency_repo_id']:null;
                $new_ar->product_id = (isset($request->submission_data['product_id']))?$request->submission_data['product_id']:null;
                $new_ar->collection_manager_email = (isset($request->submission_data['collection_manager_email']))?$request->submission_data['collection_manager_email']:null;
                $new_ar->agency_manager_email = (isset($request->submission_data['agency_manager_email']))?$request->submission_data['agency_manager_email']:null;
                $new_ar->yard_manager_email = (isset($request->submission_data['yard_manager_email']))?$request->submission_data['yard_manager_email']:null;
                $new_ar->collection_manager_id = (isset($request->submission_data['collection_manager_id']))?$request->submission_data['collection_manager_id']:null;
                $new_ar->save();
                // added for qc audit records

                
                $audit_qc = new AuditQc;
                $audit_qc->qm_sheet_id = $new_ar->qm_sheet_id ;
                $audit_qc->audited_by_id = $getUser->id;
                $audit_qc->is_critical = $new_ar->is_critical;
                $audit_qc->overall_score = $new_ar->overall_score;
                $audit_qc->branch_id = $new_ar->branch_id ;
                $audit_qc->agency_id = $new_ar->agency_id ;
                $audit_qc->yard_id = $new_ar->yard_id ;
                $audit_qc->branch_repo_id = $new_ar->branch_repo_id ;
                $audit_qc->agency_repo_id = $new_ar->agency_repo_id ;
                $audit_qc->product_id = $new_ar->product_id ;

                $audit_qc->collection_manager_email = $new_ar->collection_manager_email;
                $audit_qc->agency_manager_email = $new_ar->agency_manager_email ;
                $audit_qc->yard_manager_email = $new_ar->yard_manager_email;

                $audit_qc->collection_manager_id = $new_ar->collection_manager_id;
                if( $request->submission_data['status'] == 'submit')
                    {
                    $audit_qc->save();
                    SavedAudit::where(['audit_id'=>$new_ar->id,'status'=>1])->delete();
                    }
                    /* if(isset($request->submission_data['agency_id']) && $request->submission_data['agency_manager'] != '')
                    {
                        Agency::where('id',$new_ar->agency_id)->update(['agency_manager'=>$request->submission_data['agency_manager'],'agency_phone'=>$request->submission_data['agency_phone']]);
                    } */
                    
                //added by  nisha for change status in branchable if audit submitting

                if(!empty($request->submission_data['branch_id'])){
                    $idupdate = $request->submission_data['branch_id'];
                }
                if(!empty($request->submission_data['agency_id'])){
                    $branch_idfromagency = Agency::where('id',$request->submission_data['agency_id'])->pluck('branch_id');
                    $idupdate = $branch_idfromagency[0];
                }
                if(!empty($request->submission_data['yard_id'])){
                    $branch_idfromyard = Yard::where('id',$request->submission_data['yard_id'])->pluck('branch_id');
                    $idupdate = $branch_idfromyard[0];
                }
                if(!empty($request->submission_data['branch_repo_id'])){
                    $branch_idfrombranchrepo = BranchRepo::where('id',$request->submission_data['branch_repo_id'])->pluck('branch_id');

                    $idupdate = $branch_idfrombranchrepo[0];
                }
                if(!empty($request->submission_data['agency_repo_id'])){
                    $branch_idfromagencyrepo = AgencyRepo::where('id',$request->submission_data['agency_repo_id'])->pluck('branch_id');

                    $idupdate = $branch_idfromagencyrepo[0];
                }
                if($request->submission_data['status'] == 'save'){
                    SavedAudit::create(['audit_id'=>$new_ar->id,'status'=>1]);
                }
                // DB::enableQueryLog();
                if(isset($idupdate)){
                    $id4update_branchable_status = DB::table('branchables')

                    ->where('branch_id',$idupdate)->where('status',1)->where('product_id',$request->submission_data['product_id'])

                    ->where('type','Collection_Manager')

                    ->where('manager_id',$request->submission_data['collection_manager_id'])

                    ->update(['status'=> 2]);
                }
                
                if(isset($request->submission_data['artifactIds'])){

                    $artifactIds=json_decode($request->submission_data['artifactIds']);

                    foreach($artifactIds as $item){

                        Artifact::where('id',$item)->update(['audit_id'=>$new_ar->id]);

                    }
                }
                
                if($new_ar->id)
                {
                // store parameter wise data
                    foreach ($request->parameters as $key => $value) 
                    {
                        $para = AuditParameterResult::where('audit_id',$request->submission_data['audit_id'])
                        ->where('parameter_id',$value['id'])->first();

                        $new_arb = AuditParameterResult::find($para->id);
                        $new_arb->audit_id =  $new_ar->id;
                        $new_arb->parameter_id = $value['id'];
                        $new_arb->qm_sheet_id = $request->submission_data['qm_sheet_id'];
                        $new_arb->orignal_weight = ($value['parameter_weight']!=null)?$value['parameter_weight']:0;
                        $new_arb->temp_weight = $pera_score[$value['id']]['total_temp_weight'];
                        $new_arb->with_fatal_score = $pera_score[$value['id']]['pera_fatal'];
                        $new_arb->without_fatal_score = $pera_score[$value['id']]['pera_without_fatal'];
                        $new_arb->is_critical = $pera_score[$value['id']]['pera_is_critical'];
                        // $new_arb->without_fatal_score = $value['score_without_fatal'];

                        if($pera_score[$value['id']]['total_temp_weight']!=0)
                        {
                            $new_arb->with_fatal_score_per = ($pera_score[$value['id']]['pera_fatal'] / $pera_score[$value['id']]['total_temp_weight'])*100;
                            // $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage'])*100;
                            $new_arb->without_fatal_score_pre = ($pera_score[$value['id']]['pera_without_fatal'] / $pera_score[$value['id']]['total_temp_weight'])*100;
                        }
                        // $new_arb->is_critical = $value['is_fatal'];
                        if( $request->submission_data['status'] == 'submit'){
                                $qc_arb = new QcParameterResult;
                                $qc_arb->audit_id = $new_arb->audit_id ;
                                $qc_arb->parameter_id= $new_arb->parameter_id ;
                                $qc_arb->qm_sheet_id = $new_arb->qm_sheet_id ;
                                $qc_arb->orignal_weight = $new_arb->orignal_weight ;
                                $qc_arb->temp_weight = $new_arb->temp_weight;
                                $qc_arb->with_fatal_score = $new_arb->with_fatal_score;
                                $qc_arb->without_fatal_score = $new_arb->without_fatal_score;
                                $qc_arb->is_critical = $new_arb->is_critical;

                                if($pera_score[$value['id']]['total_temp_weight']!=0)
                                {
                                    $qc_arb->with_fatal_score_per = $new_arb->with_fatal_score_per;
                                    $qc_arb->without_fatal_score_pre = $new_arb->without_fatal_score_pre;
                                }
                            }   

                        $new_arb->save();

                        if($request->submission_data['status'] == 'submit'){
                            $qc_arb->save();
                            }

                        if(isset($value['subs']) && count($value['subs']) > 0 )
                        // store sub parameter wise data
                        {
                        foreach ($value['subs'] as $key_sb => $value_sb) {
                            if($value_sb['temp_weight']);
                            {
                                $sub_para = AuditResult::where('audit_id',$request->submission_data['audit_id'])
                                ->where('sub_parameter_id',$value_sb['id'])->first();
                                $new_arc = AuditResult::find($sub_para->id);
                                $new_arc->audit_id =  $new_ar->id;
                                $new_arc->parameter_id = $value['id'];
                                $new_arc->sub_parameter_id = $value_sb['id'];
                                $new_arc->selected_option = ($value_sb['temp_weight']!='Critical')?$value_sb['temp_weight']:0;
                                $new_arc->option_selected = (isset($value_sb['option']))?$value_sb['option']:null;
                                $new_arc->is_critical = ($value_sb['temp_weight']!='Critical')?0:1;
                                if($value_sb['score']!='rating'){
                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['score']:0;
                                    $new_arc->is_percentage = $value_sb['is_percentage'];
                                    $new_arc->selected_per = ($value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;
                                }

                                else{

                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['temp_weight']:0;

                                    $new_arc->is_percentage = $value_sb['is_percentage'];

                                    $new_arc->selected_per = ($value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;

                                }

                                

                                $new_arc->remark = $value_sb['remark'];



                                if($request->submission_data['status'] == 'submit'){

                                    $qc_arc = new QcResult;

                                    $qc_arc->audit_id =  $new_arc->audit_id;

                                    $qc_arc->parameter_id = $new_arc->parameter_id;

                                    $qc_arc->sub_parameter_id = $new_arc->sub_parameter_id;

                                    $qc_arc->selected_option = $new_arc->selected_option;

                                    $qc_arc->option_selected = $new_arc->option_selected;



                                    $qc_arc->is_critical = $new_arc->is_critical;

                                    $qc_arc->score = $new_arc->score;

                                    $qc_arc->is_percentage = $new_arc->is_percentage;

                                    $qc_arc->selected_per = $new_arc->selected_per;

                                    

                                    $qc_arc->remark = $new_arc->remark;

                                   // $qc_arc->save();

                                }

                                $new_arc->save();

                            }
                        }
                        }
                    }
                }

                // Commit Transaction
                DB::commit();
            } else {

            	// echo "string";
            	// die();
                logger($request);
                $user_role = $getUser->roles()->first()->name;
                $latlong = explode(" ",$request->submission_data['geotag']);
                // dd($request->all());
                // calculate score following code done by shailendra kumar
                $total = 0;
                $parameter_total = 0;
                $audit_crital = 0;
                $pera_score = [];

                // print_r($request);
                // die();
                foreach($request->parameters as $key => $para){
                    $pera_total = 0;
                    $pera_fatal = 0;
                    $pera_without_fatal = 0;
                    $pera_is_critical = 0;
                    $total_temp_weight = 0;
                    $pera_fatal_per = 0;
                    $pera_without_fatal_par = 0;
                    $is_critical_para = 0;
                    $subtotal = 0;
                    foreach($para['subs'] as $sub_para){
                        $paramterValue = 0;
                        if($sub_para['temp_weight'] != "N/A"){
                            $total_temp_weight = $total_temp_weight + $sub_para['temp_weight'];
                        } else {
                            $total_temp_weight = $total_temp_weight + 0;
                        }
                        if($sub_para['option'] != 'N/A'){
                            $paramterValue = $sub_para['score'];
                            
                            if($sub_para['score'] == 'N/A') {
                                $pera_fatal = $pera_fatal + 0;
                            } else {
                                $pera_fatal = $pera_fatal + $sub_para['score'];
                            }
                        }
                        if($sub_para['option'] != 'Critical'){
                            if($sub_para['score'] == 'N/A'){
                                $subtotal = $subtotal + 0;
                            } else {
                                $subtotal = $subtotal + $sub_para['score'];
                                $parameter_total = $parameter_total + $paramterValue;
                            }
                            
                        } else {
                            $subtotal = 0;
                            $is_critical_para = 1;
                            $audit_crital = 1;
                            $parameter_total = $parameter_total + $paramterValue;
                            break;
                        }

                    }
                    $total = $total + $subtotal;
                    if($total_temp_weight != 0){
                        $fat_score = round(($pera_fatal/$total_temp_weight)*100);
                        $wfat_score = round(($subtotal/$total_temp_weight)*100);
                    } else {
                        $fat_score = 0;
                        $wfat_score = 0;
                    }
                    $para_id = $para['id'];
                    $pera_score[$para_id]['pera_total'] = $subtotal;
                    $pera_score[$para_id]['total_temp_weight'] = $total_temp_weight;
                    $pera_score[$para_id]['pera_fatal'] = $pera_fatal;
                    $pera_score[$para_id]['pera_without_fatal'] = $subtotal;
                    $pera_score[$para_id]['pera_fatal_per'] = $fat_score;
                    $pera_score[$para_id]['pera_without_fatal_par'] = $wfat_score;
                    $pera_score[$para_id]['pera_is_critical'] = $is_critical_para;
                }
                $overall_score = $total;
                $new_ar = new Audit;
                $new_ar->latitude = $latlong[0];
                $new_ar->longitude = $latlong[1];
                $new_ar->qm_sheet_id = $request->submission_data['qm_sheet_id'];

				$new_ar->audit_date_by_aud = $request->submission_data['audit_date_by_aud'];
                $new_ar->audit_cycle_id = $request->submission_data['audit_cycle_id'];

                $new_ar->audited_by_id = $getUser->id;
                $new_ar->is_critical = $audit_crital;
                $new_ar->overall_score = $overall_score;
                // $new_ar->audit_date = Carbon::now()->format('Y-m-d');

                // $new_ar->with_fatal_score_per = $request->submission_data['overall_score'];

                $new_ar->branch_id = (isset($request->submission_data['branch_id']))?$request->submission_data['branch_id']:null;

                $new_ar->agency_id = (isset($request->submission_data['agency_id']))?$request->submission_data['agency_id']:null;

                $new_ar->yard_id = (isset($request->submission_data['yard_id']))?$request->submission_data['yard_id']:null;

                $new_ar->branch_repo_id = (isset($request->submission_data['branch_repo_id']))?$request->submission_data['branch_repo_id']:null;

                $new_ar->agency_repo_id = (isset($request->submission_data['agency_repo_id']))?$request->submission_data['agency_repo_id']:null;
                $new_ar->product_id = (isset($request->submission_data['product_id']))?$request->submission_data['product_id']:null;
                $new_ar->collection_manager_email = (isset($request->submission_data['collection_manager_email']))?$request->submission_data['collection_manager_email']:null;
                $new_ar->agency_manager_email = (isset($request->submission_data['agency_manager_email']))?$request->submission_data['agency_manager_email']:null;
                $new_ar->yard_manager_email = (isset($request->submission_data['yard_manager_email']))?$request->submission_data['yard_manager_email']:null;
                $new_ar->collection_manager_id = (isset($request->submission_data['collection_manager_id']))?$request->submission_data['collection_manager_id']:null;
                $new_ar->save();

                // added by sumeet to store artifact

                $artifact_data=[];
                $artifact_data = TempArtifact::all()
                ->where('temp_audit_id',($request->submission_data['temp_audit_id']))->all();
                foreach ($artifact_data as $key => $value) {
                $movedata = new Artifact;
                $movedata->sheet_id = $value->sheet_id;
                $movedata->parameter_id = $value->parameter_id;
                $movedata->sub_parameter_id = $value->sub_parameter_id;
                $movedata->file = $value->file;
                $movedata->audit_id = $new_ar->id;
                $movedata->save();
                }
                // $data = TempArtifact::delete(where('temp_audit_id',($request->temp_audit_id)));
                foreach ($artifact_data as $value) {
                         $value->delete();
                        }
               

                // added by sumeet
                // added for qc audit records
                $audit_qc = new AuditQc;
                $audit_qc->qm_sheet_id = $new_ar->qm_sheet_id ;
                $audit_qc->audited_by_id = $getUser->id;
                $audit_qc->is_critical = $new_ar->is_critical;
                $audit_qc->overall_score = $new_ar->overall_score;
                $audit_qc->branch_id = $new_ar->branch_id ;
                $audit_qc->agency_id = $new_ar->agency_id ;
                $audit_qc->yard_id = $new_ar->yard_id ;
                $audit_qc->branch_repo_id = $new_ar->branch_repo_id ;
                $audit_qc->agency_repo_id = $new_ar->agency_repo_id ;
                $audit_qc->product_id = $new_ar->product_id ;

                $audit_qc->collection_manager_email = $new_ar->collection_manager_email;
                $audit_qc->agency_manager_email = $new_ar->agency_manager_email ;
                $audit_qc->yard_manager_email = $new_ar->yard_manager_email;

                $audit_qc->collection_manager_id = $new_ar->collection_manager_id;
                if( $request->submission_data['status'] == 'submit')
                    {
                    $audit_qc->save();
                    }
                    /* if(isset($request->submission_data['agency_id']) && $request->submission_data['agency_manager'] != '')
                    {
                        Agency::where('id',$new_ar->agency_id)->update(['agency_manager'=>$request->submission_data['agency_manager'],'agency_phone'=>$request->submission_data['agency_phone']]);
                    } */
                    
                //added by  nisha for change status in branchable if audit submitting

                if(!empty($request->submission_data['branch_id'])){
                    $idupdate = $request->submission_data['branch_id'];
                }
                if(!empty($request->submission_data['agency_id'])){
                    $branch_idfromagency = Agency::where('id',$request->submission_data['agency_id'])->pluck('branch_id');
                    $idupdate = $branch_idfromagency[0];
                }
                if(!empty($request->submission_data['yard_id'])){
                    $branch_idfromyard = Yard::where('id',$request->submission_data['yard_id'])->pluck('branch_id');
                    $idupdate = $branch_idfromyard[0];
                }
                if(!empty($request->submission_data['branch_repo_id'])){
                    $branch_idfrombranchrepo = BranchRepo::where('id',$request->submission_data['branch_repo_id'])->pluck('branch_id');

                    $idupdate = $branch_idfrombranchrepo[0];
                }
                if(!empty($request->submission_data['agency_repo_id'])){
                    $branch_idfromagencyrepo = AgencyRepo::where('id',$request->submission_data['agency_repo_id'])->pluck('branch_id');

                    $idupdate = $branch_idfromagencyrepo[0];
                }
                if($request->submission_data['status'] == 'save'){
                    SavedAudit::create(['audit_id'=>$new_ar->id,'status'=>1]);
                }
            // DB::enableQueryLog();
                if(isset($idupdate)){
                    $id4update_branchable_status = DB::table('branchables')

                    ->where('branch_id',$idupdate)->where('status',1)->where('product_id',$request->submission_data['product_id'])

                    ->where('type','Collection_Manager')

                    ->where('manager_id',$request->submission_data['collection_manager_id'])

                    ->update(['status'=> 2]);
                }
                
                // if(isset($request->submission_data['artifactIds'])){

                //     $artifactIds=json_decode($request->submission_data['artifactIds']);

                //     foreach($artifactIds as $item){

                //         Artifact::where('id',$item)->update(['audit_id'=>$new_ar->id]);

                //     }
                // }
                
                if($new_ar->id)
                {
                // store parameter wise data
                    foreach ($request->parameters as $key => $value) 
                    {
                        $new_arb = new AuditParameterResult;
                        $new_arb->audit_id =  $new_ar->id;
                        $new_arb->parameter_id = $value['id'];
                        $new_arb->qm_sheet_id = $request->submission_data['qm_sheet_id'];
                        $new_arb->orignal_weight = ($value['parameter_weight']!=null)?$value['parameter_weight']:0;
                        $new_arb->temp_weight = $pera_score[$value['id']]['total_temp_weight'];
                        $new_arb->with_fatal_score = $pera_score[$value['id']]['pera_fatal'];
                        $new_arb->without_fatal_score = $pera_score[$value['id']]['pera_without_fatal'];
                        $new_arb->is_critical = $pera_score[$value['id']]['pera_is_critical'];
                        // $new_arb->without_fatal_score = $value['score_without_fatal'];

                        if($pera_score[$value['id']]['total_temp_weight']!=0)
                        {
                            $new_arb->with_fatal_score_per = ($pera_score[$value['id']]['pera_fatal'] / $pera_score[$value['id']]['total_temp_weight'])*100;
                            // $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage'])*100;
                            $new_arb->without_fatal_score_pre = ($pera_score[$value['id']]['pera_without_fatal'] / $pera_score[$value['id']]['total_temp_weight'])*100;
                        }
                        // $new_arb->is_critical = $value['is_fatal'];
                        if( $request->submission_data['status'] == 'submit'){
                                $qc_arb = new QcParameterResult;
                                $qc_arb->audit_id = $new_arb->audit_id ;
                                $qc_arb->parameter_id= $new_arb->parameter_id ;
                                $qc_arb->qm_sheet_id = $new_arb->qm_sheet_id ;
                                $qc_arb->orignal_weight = $new_arb->orignal_weight ;
                                $qc_arb->temp_weight = $new_arb->temp_weight;
                                $qc_arb->with_fatal_score = $new_arb->with_fatal_score;
                                $qc_arb->without_fatal_score = $new_arb->without_fatal_score;
                                $qc_arb->is_critical = $new_arb->is_critical;

                                if($pera_score[$value['id']]['total_temp_weight']!=0)
                                {
                                    $qc_arb->with_fatal_score_per = $new_arb->with_fatal_score_per;
                                    $qc_arb->without_fatal_score_pre = $new_arb->without_fatal_score_pre;
                                }
                            }   

                        $new_arb->save();

                        if($request->submission_data['status'] == 'submit'){
                            $qc_arb->save();
                            }

                        if(isset($value['subs']) && count($value['subs']) > 0 )
                        // store sub parameter wise data
                        {
                        foreach ($value['subs'] as $key_sb => $value_sb) {
                            if($value_sb['temp_weight']);
                            {
                                $new_arc = new AuditResult;
                                $new_arc->audit_id =  $new_ar->id;
                                $new_arc->parameter_id = $value['id'];
                                $new_arc->sub_parameter_id = $value_sb['id'];
                                $new_arc->selected_option = ($value_sb['temp_weight']!='Critical')?$value_sb['temp_weight']:0;
                                $new_arc->option_selected = (isset($value_sb['option']))?$value_sb['option']:null;
                                $new_arc->is_critical = ($value_sb['temp_weight']!='Critical')?0:1;
                                if($value_sb['score']!='rating'){
                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['score']:0;
                                    $new_arc->is_percentage = $value_sb['is_percentage'];
                                    $new_arc->selected_per = ($value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;
                                }

                                else{

                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['temp_weight']:0;

                                    $new_arc->is_percentage = $value_sb['is_percentage'];

                                    $new_arc->selected_per = ($value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;

                                }

                                

                                $new_arc->remark = $value_sb['remark'];



                                if($request->submission_data['status'] == 'submit'){

                                    $qc_arc = new QcResult;

                                    $qc_arc->audit_id =  $new_arc->audit_id;

                                    $qc_arc->parameter_id = $new_arc->parameter_id;

                                    $qc_arc->sub_parameter_id = $new_arc->sub_parameter_id;

                                    $qc_arc->selected_option = $new_arc->selected_option;

                                    $qc_arc->option_selected = $new_arc->option_selected;



                                    $qc_arc->is_critical = $new_arc->is_critical;

                                    $qc_arc->score = $new_arc->score;

                                    $qc_arc->is_percentage = $new_arc->is_percentage;

                                    $qc_arc->selected_per = $new_arc->selected_per;

                                    

                                    $qc_arc->remark = $new_arc->remark;

                                    $qc_arc->save();

                                }

                                $new_arc->save();

                            }
                        }
                        }
                    }
                }

                // Commit Transaction
                DB::commit();
            }
            
        }     
        catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            // return redirect('user')->with('success', ['Retry Again!']);
            $response=array('status'=>0,'message'=>'Retry Again.',
                                'audit_id' => array());       
            return response(json_encode($response), 200);
        }
        $p='submitted';
        if($request->submission_data['status'] == 'save'){
            $p='saved';
        }
         $response=array('status'=>1,'message'=>'Audit '. $p. ' successfully.',
                                'audit_id' => $new_ar->id);       
        return response(json_encode($response), 200);

    }

      public function update_audit(Request $request)
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

        logger($request);

        DB::beginTransaction();
        try 
        {

            //return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'data'=>$request], 200);

            //create audit record

            $new_ar = Audit::find($request->submission_data['id']);

            $new_ar->qm_sheet_id = $request->submission_data['qm_sheet_id'];

            // $new_ar->audited_by_id = Auth::user()->id;

            $new_ar->is_critical = isset($request->submission_data['is_critical'])?($request->submission_data['is_critical']):0;

            $new_ar->overall_score = $request->submission_data['overall_score'];

            // $new_ar->audit_date = Carbon::now()->format('Y-m-d');

            // $new_ar->with_fatal_score_per = $request->submission_data['overall_score'];

            $new_ar->branch_id = (isset($request->submission_data['branch_id']))?$request->submission_data['branch_id']:null;

            $new_ar->agency_id = (isset($request->submission_data['agency_id']))?$request->submission_data['agency_id']:null;

            $new_ar->yard_id = (isset($request->submission_data['yard_id']))?$request->submission_data['yard_id']:null;

            $new_ar->product_id = (isset($request->submission_data['product_id']))?$request->submission_data['product_id']:null;

            $new_ar->branch_repo_id = (isset($request->submission_data['branch_repo_id']))?$request->submission_data['branch_repo_id']:null;

            $new_ar->agency_repo_id = (isset($request->submission_data['agency_repo_id']))?$request->submission_data['agency_repo_id']:null;

            $new_ar->collection_manager_id = (isset($request->submission_data['collection_manager_id']))?$request->submission_data['collection_manager_id']:null;

            $new_ar->update();

            

            if(isset($request->submission_data['agency_id']) && isset($request->submission_data['agency_manager']) && $request->submission_data['agency_manager'] != '')

            {

                Agency::where('id',$new_ar->agency_id)->update(['agency_manager'=>$request->submission_data['agency_manager'] ,'agency_phone'=>$request->submission_data['agency_phone']]);

            }

            if(isset($request->submission_data['status']) && $request->submission_data['status'] == 'submit')

            {

                SavedAudit::where('audit_id',$new_ar->id)->delete();

            }

            if($new_ar->id)

            {

               // store parameter wise data

                foreach ($request->parameters as $key => $value) {



                    $new_arb = AuditParameterResult::find($value['id']);

                    $new_arb->audit_id =  $new_ar->id;

                    $new_arb->parameter_id = $key;

                    $new_arb->qm_sheet_id = $request->submission_data['qm_sheet_id'];

                    $new_arb->orignal_weight = $value['parameter_weight'];

                    $new_arb->temp_weight = $value['temp_total_weightage'];

                    $new_arb->with_fatal_score = $value['score_with_fatal'];

                    // $new_arb->without_fatal_score = $value['score_without_fatal'];

                    $new_arb->without_fatal_score = $value['score_with_fatal'];



                    if($value['temp_total_weightage']!=0)

                    {

                        $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage'])*100;

                        // $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage'])*100;

                        $new_arb->without_fatal_score_pre = ($value['score_with_fatal'] / $value['temp_total_weightage'])*100;

                    }

                    // $new_arb->is_critical = $value['is_fatal'];



                    $new_arb->update();



                    // store sub parameter wise data

                    if(isset($value['subs'])){

                    foreach ($value['subs'] as $key_sb => $value_sb) {

                        if($value_sb['temp_weight']);

                        {

                            if(isset($value_sb['id'])){

                                $new_arc = AuditResult::find($value_sb['id']);

                                $new_arc->audit_id =  $new_ar->id;

                                $new_arc->parameter_id = $key;

                                $new_arc->sub_parameter_id = $key_sb;

                                // $new_arc->is_critical = $value_sb['is_fatal'];

                                // $new_arc->is_non_scoring = $value_sb['is_non_scoring'];

                                // $temp_selected_opt = explode("_",$value_sb['selected_option_model']);

                                $new_arc->selected_option = ($value_sb['temp_weight']!='Critical')?$value_sb['temp_weight']:0;

                                $new_arc->option_selected = (isset($value_sb['option']))?$value_sb['option']:null;

                                $new_arc->is_critical = ($value_sb['temp_weight']!='Critical')?0:1;

                                // $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['score']:0;

                                if($value_sb['score']!='rating'){

                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['score']:0;

                                    $new_arc->is_percentage = $value_sb['is_percentage'];

                                    $new_arc->selected_per = (isset($value_sb['selected_per']) && $value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;

                                }

                                else{

                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['temp_weight']:0;

                                    $new_arc->is_percentage = $value_sb['is_percentage'];

                                    $new_arc->selected_per = ($value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;

                                }

                                // $new_arc->after_audit_weight = $value_sb['temp_weight'];

        

                                // if($temp_selected_opt[3]==2||$temp_selected_opt[3]==3)

                                // if(isset($value_sb['selected_reason_type'])==1&&$value_sb['selected_reason_type']!='')

                                // {

                                //     $temp_selected_reason_type = explode("_",$value_sb['selected_reason_type']);

                                //     $new_arc->reason_type_id = $temp_selected_reason_type[2];

                                //     $new_arc->reason_id = $value_sb['selected_reason'];

                                // }

                                $new_arc->remark = $value_sb['remark'];

                                $new_arc->update(); 

                            }

                            else{

                            $new_arc = new AuditResult;

                                $new_arc->audit_id =  $new_ar->id;

                                $new_arc->parameter_id = $key;

                                $new_arc->sub_parameter_id = $key_sb;

                                $new_arc->selected_option = ($value_sb['temp_weight']!='Critical')?$value_sb['temp_weight']:0;

                                $new_arc->option_selected = (isset($value_sb['option']))?$value_sb['option']:null;

                                $new_arc->is_critical = ($value_sb['temp_weight']!='Critical')?0:1;

                                if($value_sb['score']!='rating'){

                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['score']:0;

                                    $new_arc->is_percentage = $value_sb['is_percentage'];

                                    $new_arc->selected_per = (isset($value_sb['selected_per']) && $value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;

                                }

                                else{

                                    $new_arc->score = ($value_sb['score']!='Critical')?$value_sb['temp_weight']:0;

                                    $new_arc->is_percentage = $value_sb['is_percentage'];

                                    $new_arc->selected_per = ($value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;

                                }

                                $new_arc->remark = $value_sb['remark'];

                                $new_arc->save();

                            }

                        }

                    }

                    }



                }



            }

           
        
        // Commit Transaction
        DB::commit();

        }     
        catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            // return redirect('user')->with('success', ['Retry Again!']);
             $response=array('status'=>0,'message'=>'Retry Again.',
                                'audit_id' => array());       
            return response(json_encode($response), 200);
        }



        

         $response=array('status'=>1,'message'=>'Audit saved successfully.',
                                'audit_id' => $new_ar->id);       
        return response(json_encode($response), 200);

         // return response()->json(['status'=>200,'message'=>"Audit saved successfully."], 200); 
    }

    public function artifact_audit_file_links(Request $request)
    {
        
    	if(!$request->header('Authorization') || $request->header('Authorization') == "") {
            $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
            return response()->json(['status'=>0,'message'=>"Error",'data'=>$data], 400);
         }
         $validator = Validator::make($request->all(), [
            'audit_id' => 'required | exists:artifacts,audit_id',
            'parameter_id' => 'required | exists:artifacts,parameter_id',
            'sub_parameter_id' => 'required | exists:artifacts,sub_parameter_id'
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response()->json(['status'=>0,'message'=>"Error",'data'=>$data], 400);
        }
        else{
            
            $data = Artifact::where('audit_id',$request->audit_id)
                                     ->where('parameter_id',$request->parameter_id)
                                     ->where('sub_parameter_id',$request->sub_parameter_id)
                                     ->get();
            $data1 = [];
            if(!empty($data)){
                
                foreach ($data as $key => $getArtifacts_values) {
                    $p =array();
                    $url=URL::to('/');
                    $p['id']=$getArtifacts_values->id;
                    $p['sheet_id']=$getArtifacts_values->sheet_id;
                    $p['parameter_id']=$getArtifacts_values->parameter_id;
                    $p['sub_parameter_id']=$getArtifacts_values->sub_parameter_id;
                    $p['file']=$url.'/public/artifects/'.$getArtifacts_values->file;

                    $p['created_at']=(string)$getArtifacts_values->created_at;
                    $p['updated_at']=(string)$getArtifacts_values->updated_at;
                    $p['audit_id']=$getArtifacts_values->audit_id;
                    
                    $data1[]=$p;
                }
            }
            return response()->json(['status'=>1,'message'=>"Success",'data'=>$data1], 200);
            
            
           // return response(json_encode($data), 200);
        }
    }
  	

  	#getaudit cycle api

  	public function get_audit_cycle(){

        // echo "string";
        // die();
  		$get_audits_cycle = DB::table('audit_cycles')
  								->select('id','name')
  								->get();

  		// print_r($get_audits_cycle);
  		// die();

  		return response()->json(['status' => True, 'message' => 'Complete Audit Cycle is Here', 'details' => $get_audits_cycle]);
  	}
}
