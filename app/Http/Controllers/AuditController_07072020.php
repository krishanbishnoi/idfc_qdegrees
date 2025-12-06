<?php

namespace App\Http\Controllers;

use App\Audit;
use App\AuditAlertBox;
use App\AuditParameterResult;
use App\AuditResult;
use App\SavedAudit;
use App\SavedQcAudit;
use App\Partner;
use App\QmSheet;
use App\RawData;
use App\RcaMode;
use App\RcaType;
use App\Reason;
use App\ReasonType;
use App\TypeBScoringOption;
use Auth;
use Crypt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\Branch;
use App\Model\Branchable;
use App\Model\ProductUser;
use App\Agency;
use App\Yard;
use App\Qc;
use App\Model\Products;
use App\User;
use App\RedAlert;
use App\Model\BranchRepo;
use App\Model\AgencyRepo;
use Mail;
use App\Artifact;
class AuditController extends Controller
{
    public function render_audit_sheet($qm_sheet_id)
    {
        //dd(all_non_scoring_obs_options(1));

        $data = QmSheet::with('parameter.qm_sheet_sub_parameter')->find(Crypt::decrypt($qm_sheet_id));
        // $branch=Branch::all();
        // $agency=Agency::all();
        // $yard=Yard::all();
        $branch=Branch::where('lob',$data->lob)->get();
        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();
        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();
        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();
      	return view('audit.render_sheet',compact('qm_sheet_id','data','branch','agency','yard','branchRepo','agencyRepo'));
        // return view('audit.render_sheet',compact('qm_sheet_id','data','branch'));
    }
    public function getProduct($id,$type){
        if($type=='branch'){
            // $branchable=Branch::with('branchable','city')->where('id',$id)->first();
            $productIds=Branchable::where('branch_id',$id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        }
        else if($type=='agency'){
            $agency=Agency::with('user')->find($id);
            // $branchable=Branch::with('branchable','city')->where('id',$agency->branch_id)->first();
            // $branchable=Branchable::with('product')->where('branch_id',$agency->branch_id)->get();
            $productIds=Branchable::where('branch_id',$agency->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        }
        else if($type=='yard'){
            $yard=Yard::with('user')->find($id);
            // $agency=Agency::with('user')->find($yard->agency_id);
            // $branchable=Branch::with('branchable','city')->where('id',$yard->branch_id)->first();
            // $branchable=Branchable::with('product')->where('branch_id',$yard->branch_id)->get();
            $productIds=Branchable::where('branch_id',$yard->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        } 
        else if($type=='branch_repo'){
            $yard=BranchRepo::find($id);
           $productIds=Branchable::where('branch_id',$yard->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        } 
        else if($type=='agency_repo'){
            $yard=AgencyRepo::find($id);
            $productIds=Branchable::where('branch_id',$yard->branch_id)->get()->pluck('product_id')->toArray();
            $branchable=Products::whereIn('id',array_unique($productIds))->get();
        } 
        return response()->json(['data'=>$branchable]);
    }
    public function renderBranch($id,$type,$product_id){
        $agency=[];
        $yard=[];
        $AgencyRepo=[];
        $BranchRepo=[];
        // dd($type);
        if($type=='branch'){
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$id)->first();
        }
        else if($type=='agency'){
            $agency=Agency::with('user')->find($id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$agency->branch_id)->first();
        }
        else if($type=='yard'){
            $yard=Yard::with('user')->find($id);
            $agency=Agency::with('user')->find($yard->agency_id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$yard->branch_id)->first();
        }
        else if($type=='branch_repo'){
            $BranchRepo=BranchRepo::find($id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$BranchRepo->branch_id)->first();
        }
        else if($type=='agency_repo'){
            $AgencyRepo=AgencyRepo::find($id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$AgencyRepo->branch_id)->first();
        }
        // dd($agency);
        return view('audit.branch',compact('branchable','type','agency','yard','AgencyRepo','BranchRepo'));

    }
    public function renderBranchQc($id,$type,$product_id){
        $agency=[];
        $yard=[];
        $AgencyRepo=[];
        $BranchRepo=[];
        // dd($type);
        if($type=='branch'){
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$id)->first();
        }
        else if($type=='agency'){
            $agency=Agency::with('user')->find($id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$agency->branch_id)->first();
        }
        else if($type=='yard'){
            $yard=Yard::with('user')->find($id);
            $agency=Agency::with('user')->find($yard->agency_id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$yard->branch_id)->first();
        }
        else if($type=='branch_repo'){
            $BranchRepo=BranchRepo::find($id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$BranchRepo->branch_id)->first();
        }
        else if($type=='agency_repo'){
            $AgencyRepo=AgencyRepo::find($id);
            $branchable=Branch::with(['branchable'=>function($q) use($product_id){
                $q->where('product_id',$product_id);
            },'city'])->where('id',$AgencyRepo->branch_id)->first();
        }
        // dd($agency);
        return view('audit.branchQc',compact('branchable','type','agency','yard','AgencyRepo','BranchRepo'));

    }
    public function render_audit_sheet_edit($qm_sheet_id)
    {
        //dd(all_non_scoring_obs_options(1));
        $result=Audit::with(['audit_parameter_result','audit_results'])->where('id',Crypt::decrypt($qm_sheet_id))->first();
        $resultPar=AuditParameterResult::where('audit_id',$result->id)->get()->keyBy('parameter_id');
        $resultSubPar=AuditResult::where('audit_id',$result->id)->get()->keyBy('sub_parameter_id');
        $data = QmSheet::with('parameter.qm_sheet_sub_parameter.artifact')->find($result->qm_sheet_id);
        $branch=Branch::where('lob',$data->lob)->get();
        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();
        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();
        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $artifactIds = Artifact::where('audit_id',$result->id)->get()->pluck('id')->toArray();
        // dd($data,$resultPar,$resultSubPar);
       return view('audit.render_sheet_edit',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','artifactIds'));
    }
    public function render_audit_sheet_View($qm_sheet_id)
    {
        //dd(all_non_scoring_obs_options(1));
        $result=Audit::with(['audit_parameter_result','audit_results'])->where('id',Crypt::decrypt($qm_sheet_id))->first();
        $resultPar=AuditParameterResult::where('audit_id',$result->id)->get()->keyBy('parameter_id');
        $resultSubPar=AuditResult::where('audit_id',$result->id)->get()->keyBy('sub_parameter_id');
        $data = QmSheet::with('parameter.qm_sheet_sub_parameter.artifact')->find($result->qm_sheet_id);
        $branch=Branch::where('lob',$data->lob)->get();
        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();
        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();
        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $artifactIds = Artifact::where('audit_id',$result->id)->get()->pluck('id')->toArray();
        // dd($data,$resultPar,$resultSubPar);
    	return view('audit.view_sheet',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','artifactIds'));
    
    }
    public function render_audit_sheet_View_QC($qm_sheet_id)
    {
        //dd(all_non_scoring_obs_options(1));
        $result=Audit::with(['audit_parameter_result','audit_results'])->where('id',Crypt::decrypt($qm_sheet_id))->first();
        $resultPar=AuditParameterResult::where('audit_id',$result->id)->get()->keyBy('parameter_id');
        $resultSubPar=AuditResult::where('audit_id',$result->id)->get()->keyBy('sub_parameter_id');
        $data = QmSheet::with('parameter.qm_sheet_sub_parameter.artifact')->find($result->qm_sheet_id);
        $branch=Branch::where('lob',$data->lob)->get();
        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();
        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();
        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        // dd($data,$resultPar,$resultSubPar);
        $qc=Qc::where('audit_id',$result->id)->first();
        $artifactIds = Artifact::where('audit_id',$result->id)->get()->pluck('id')->toArray();
    	return view('audit.view_sheet_qc',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','qc','artifactIds'));
    
    }
    public function detail_audit_sheet_edit($qm_sheet_id)
    {
        //dd(all_non_scoring_obs_options(1));
        $result=Audit::with(['audit_parameter_result','audit_results'])->where('id',Crypt::decrypt($qm_sheet_id))->first();
        $resultPar=AuditParameterResult::where('audit_id',$result->id)->get()->keyBy('parameter_id');
        $resultSubPar=AuditResult::where('audit_id',$result->id)->get()->keyBy('sub_parameter_id');
        $data = QmSheet::with('parameter.qm_sheet_sub_parameter')->find($result->qm_sheet_id);
        $branch=Branch::where('lob',$data->lob)->get();
        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();
        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();
        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();
        $artifactIds = Artifact::where('audit_id',$result->id)->get()->pluck('id')->toArray();
        $qc=Qc::where('audit_id',$result->id)->first();
        // dd($data,$resultPar,$resultSubPar);
    	return view('audit.detail_sheet_edit',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','artifactIds','qc'));
    }
    public function save_qc_status(Request $request){
        // dd($request->all());
        if($request->type=='save'){
            SavedQcAudit::updateOrCreate(['audit_id'=>$request->audit_id],['audit_id'=>$request->audit_id,'status'=>1]);
        }
        else if($request->type=='submit'){
            SavedQcAudit::where(['audit_id'=>$request->audit_id])->delete();
        }
        if($request->qc_id!=''){
            $data=Qc::where('id',$request->qc_id)->update(['qm_sheet_id'=>$request->qm_sheet_id,'audit_id'=>$request->audit_id,'status'=>$request->status,'feedback'=>$request->feedback,'qc_by_id'=>Auth::user()->id]);
        }
        else{
            $data=Qc::create(['qm_sheet_id'=>$request->qm_sheet_id,'audit_id'=>$request->audit_id,'status'=>$request->status,'feedback'=>$request->feedback,'qc_by_id'=>Auth::user()->id]);
        }
        if($data && $request->type=='submit'){
            $ids=[];
            $audit_id=$request->audit_id;
            $otherDetails=[];
            $audit=Audit::with(['qmsheet','redAlert.parameter','redAlert.subParameter','product'])->where('id',$audit_id)->first();
            $auditResult=AuditResult::where('audit_id',$audit_id)->get()->pluck('remark','sub_parameter_id');
            // dd($auditResult);
                switch($audit->qmsheet->type){
                    case 'branch':
                        $branch=Branch::with('city.state.region')->find($audit->branch_id);
                        $otherDetails['region']=$branch->city->state->region->name ?? '';
                        $otherDetails['state']=$branch->city->state->name ?? '';
                        $otherDetails['city']=$branch->city->name ?? '';
                        $otherDetails['name']=$branch->name ?? '';
                    $ids= Branchable::where('branch_id',$audit->branch_id)->get(['id','manager_id'])->pluck('manager_id');
                    break;
                    case 'agency':
                        $agency=Agency::find($audit->agency_id);
                        $branch=Branch::with('city.state.region')->find($agency->branch_id);
                        $otherDetails['region']=$branch->city->state->region->name ?? '';
                        $otherDetails['state']=$branch->city->state->name ?? '';
                        $otherDetails['city']=$branch->city->name ?? '';
                        $otherDetails['name']=$agency->name ?? '';
                        $ids= Branchable::where('branch_id',$agency->branch_id)->get(['id','manager_id'])->pluck('manager_id');
                    break;
                    case 'yard':
                        $agency=Yard::find($audit->yard_id);
                        $branch=Branch::with('city.state.region')->find($agency->branch_id);
                        $otherDetails['region']=$branch->city->state->region->name ?? '';
                        $otherDetails['state']=$branch->city->state->name ?? '';
                        $otherDetails['city']=$branch->city->name ?? '';
                        $otherDetails['name']=$agency->name ?? '';
                        $ids= Branchable::where('branch_id',$agency->branch_id)->get(['id','manager_id'])->pluck('manager_id');
                    break;
                    case 'branch_repo':
                        $BranchRepo=BranchRepo::find($audit->branch_repo_id);
                        $branch=Branch::with('city.state.region')->find($BranchRepo->branch_id);
                        $otherDetails['region']=$branch->city->state->region->name ?? '';
                        $otherDetails['state']=$branch->city->state->name ?? '';
                        $otherDetails['city']=$branch->city->name ?? '';
                        $otherDetails['name']=$BranchRepo->name ?? '';
                        $ids= Branchable::where('branch_id',$BranchRepo->branch_id)->get(['id','manager_id'])->pluck('manager_id');
                    break;
                    case 'agency_repo':
                        $AgencyRepo=AgencyRepo::find($audit->agency_repo_id);
                        $branch=Branch::with('city.state.region')->find($AgencyRepo->branch_id);
                        $otherDetails['region']=$branch->city->state->region->name ?? '';
                        $otherDetails['state']=$branch->city->state->name ?? '';
                        $otherDetails['city']=$branch->city->name ?? '';
                        $otherDetails['name']=$AgencyRepo->name ?? '';
                        $ids= Branchable::where('branch_id',$AgencyRepo->branch_id)->get(['id','manager_id'])->pluck('manager_id');
                    break;
                }
                $attach=[];
                foreach($audit->redAlert as $item){
                    if($item->file!=null){
                        $attach[]=$item->file;
                    }
                }
                // $emails=User::whereIn('id',$ids)->role('Area Collection Manager')->get(['id','email'])->pluck('email')->toArray();
                $otherDetails['collection']=User::whereIn('id',$ids)->role('Collection Manager')->first(['id','name'])->name;
                $emails[]='ravindra.swami9@gmail.com';
                $url=url('red-alert/').'/'.Crypt::encrypt($audit->id);
                Mail::send('emails.alert', ['data' => $audit,'otherDetails'=>$otherDetails,'auditResult'=>$auditResult,'url'=>$url], function ($m) use ($emails,$attach) {
                    $m->from('hello@app.com', 'Your Application');
                    $m->to($emails)->subject('Alert');
                    foreach($attach as $item){
                        $m->attach($item);
                    }
                });
            return response()->json(['status'=>true,'msg'=>'status saved ']);
        }
        else{
            return response()->json(['status'=>false,'msg'=>'status not saved ']);
        }
    }
    public function get_qm_sheet_details_for_audit($qm_sheet_id)
    {

    	$data = QmSheet::with(['client','process','parameter','parameter.qm_sheet_sub_parameter'])->find(Crypt::decrypt($qm_sheet_id));

    	$partners_list = Partner::where('client_id',$data->client_id)->pluck('name','id');
    	$final_data['partners_list'] = $partners_list;

        $temp_my_alloted_call_list = RawData::where('qtl_id',Auth::user()->id)->orWhere('qa_id',Auth::user()->id)->where('status',0)->pluck('call_id','id');
        foreach ($temp_my_alloted_call_list as $key => $value) {
            $my_alloted_call_list[] = ['key'=>$key,"value"=>$value];
        }

        $final_data['my_alloted_call_list'] = $my_alloted_call_list;

    	$final_data['sheet_details'] = $data->toArray();
    	//$final_data['type_b_scoring_option'] = TypeBScoringOption::all();
        $all_type_b_scoring_option = TypeBScoringOption::where('company_id',$data->company_id)->pluck('name','id');

        //process data
        $pds = [];
        foreach ($data->parameter as $key => $value_p) {
            $pds[$value_p->id]['name'] = $value_p->parameter;
            $pds[$value_p->id]['is_non_scoring']=$value_p->is_non_scoring;
            $total_parameter_weight = 0;
            $pds[$value_p->id]['is_fatal']=0;
            $pds[$value_p->id]['score']=0;
            $pds[$value_p->id]['score_with_fatal']=0;
            $pds[$value_p->id]['score_without_fatal']=0;
            $pds[$value_p->id]['temp_total_weightage']=0;
            foreach ($value_p->qm_sheet_sub_parameter as $key => $value_s) {

                $pds[$value_p->id]['subs'][$value_s->id]['name'] = $value_s->sub_parameter;
                $pds[$value_p->id]['subs'][$value_s->id]['details'] = $value_s->details;
                $pds[$value_p->id]['subs'][$value_s->id]['is_fatal']=0;
                $pds[$value_p->id]['subs'][$value_s->id]['is_non_scoring'] = $value_p->is_non_scoring;
                $pds[$value_p->id]['subs'][$value_s->id]['failure_reason'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['remark'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['orignal_weight'] = $value_s->weight;
                $pds[$value_p->id]['subs'][$value_s->id]['temp_weight'] = 0;
                $scoring_opts = [];
                if($value_p->is_non_scoring)
                {   
                    //total weight
                    $total_parameter_weight +=0;
                    if($value_s->non_scoring_option_group)
                    {                  
                        foreach (all_non_scoring_obs_options($value_s->non_scoring_option_group) as $key_ns => $value_ns) {
                                                $scoring_opts[$value_p->id."_".$value_s->id."_".$value_ns."_".$key_ns."_0"] = ["key"=>$value_p->id."_".$value_s->id."_".$value_ns."_".$key_ns."_0","value"=>$value_ns,"alert_box"=>null];
                                        }
                    }else
                    {
                        $scoring_opts=null;
                    }

                    
                }else
                {
                    //total weight
                    $total_parameter_weight +=$value_s->weight;
                    //total weight
                    $alert_box=null;

                    $all_reason_type_fail=null;
                    $all_reason_type_cric=null;

                    if($value_s->pass)
                    { 
                        if($value_s->pass_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pass_alert_box_id);
                        else
                            $alert_box = null;


                        $scoring_opts[$value_p->id."_".$value_s->id."_".$value_s->weight."_1_0"] = ["key"=>$value_p->id."_".$value_s->id."_".$value_s->weight.'_1_0',"value"=>"Pass","alert_box"=>$alert_box];
                   }

                    if($value_s->fail)
                     {
                        if($value_s->fail_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->fail_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->fail_reason_types)
                        {
                            $temp_index_f = $value_p->id."_".$value_s->id."_"."0"."_2_1";
                            $temp_r_fail = ReasonType::find(explode(',',$value_s->fail_reason_types))->pluck('name','id');
                            foreach ($temp_r_fail as $keycc => $valuecc) {
                                $all_reason_type_fail[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_f = $value_p->id."_".$value_s->id."_"."0"."_2_0";
                            $all_reason_type_fail = null;
                        }

                        $scoring_opts[$temp_index_f] = ["key"=>$temp_index_f,"value"=>"Fail","alert_box"=>$alert_box];
                    }

                    if($value_s->critical)
                     {
                        if($value_s->critical_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->critical_alert_box_id);
                        else
                            $alert_box = null;

                        if($value_s->critical_reason_types)
                        {
                            $temp_index_cri = $value_p->id."_".$value_s->id."_"."Critical"."_3_1";
                            $temp_cric = ReasonType::find(explode(',',$value_s->critical_reason_types))->pluck('name','id');
                            foreach ($temp_cric as $keycc => $valuecc) {
                                $all_reason_type_cric[] = ["key"=>$value_p->id."_".$value_s->id."_".$keycc,"value"=>$valuecc]; 
                            }
                        }
                        else
                        {
                            $temp_index_cri = $value_p->id."_".$value_s->id."_"."Critical"."_3_0";
                            $all_reason_type_cric = null;
                        }


                        $scoring_opts[$temp_index_cri] = ["key"=>$temp_index_cri,"value"=>"Critical","alert_box"=>$alert_box];
                    }

                    if($value_s->na)
                     {

                        if($value_s->na_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->na_alert_box_id);
                        else
                            $alert_box = null;


                        $scoring_opts[$value_p->id."_".$value_s->id."_"."N/A"."_4_0"] = ["key"=>$value_p->id."_".$value_s->id."_"."N/A"."_4_0","value"=>"N/A","alert_box"=>$alert_box];
                    }

                    if($value_s->pwd)
                     {  
                        if($value_s->pwd_alert_box_id)
                            $alert_box = AuditAlertBox::find($value_s->pwd_alert_box_id);
                        else
                            $alert_box = null;


                        $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];
                    }

                }


                $pds[$value_p->id]['subs'][$value_s->id]['options'] = $scoring_opts;
                $pds[$value_p->id]['subs'][$value_s->id]['score'] = 0;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_options'] = null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_option_model'] = '';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_fail'] = $all_reason_type_fail;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_cric'] = $all_reason_type_cric;
                $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type']=null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason_type']='';
                $pds[$value_p->id]['subs'][$value_s->id]['all_reasons']=null;
                $pds[$value_p->id]['subs'][$value_s->id]['selected_reason']='';

            }
            $pds[$value_p->id]['parameter_weight'] = $total_parameter_weight;
            
        }
        
        $final_data['simple_data'] = $pds;

        // rca starts
        $rca_type =  RcaType::where('company_id',$data->company_id)->where('process_id',$data->process_id)->pluck('name','id');
        $rca_mode =  $data = RcaMode::where('company_id',$data->company_id)->where('process_id',$data->process_id)->pluck('name','id');
        // rca end
        $final_data['rca_type'] = $rca_type;
        $final_data['rca_mode'] = $rca_mode;

    	return response()->json(['status'=>200,'message'=>"Success",'data'=>$final_data,], 200);
    }
    public function get_raw_data_for_audit($comm_instance_id)
    {
    	$data = RawData::with('partner_detail')->find($comm_instance_id);
    	if($data)
    	{
    		return response()->json(['status'=>200,'message'=>"Call found.",'data'=>$data], 200);
    	}else
    	{
    		return response()->json(['status'=>404,'message'=>"Call not found."], 404);
    	}
    	
    }
    public function audited_list()
    {
        $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();
        $ids=Qc::with('user')->whereNotIn('audit_id',$savedQcIds)->get()->keyBy('audit_id');
        $savedIds=SavedAudit::all()->pluck('audit_id')->toArray();
        if($ids->count()>0){
            $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','user','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereNotIn('id',$ids->pluck('audit_id'))->whereNotIn('id',$savedIds)->get();
        }
        else{
            $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','user','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereNotIn('id',$savedIds)->get();
        }
        // dd($data,$ids);
        return view('audit.audit_list',compact('data','ids','savedQcIds'));
    }
    public function done_audited_list()
    {
        $ids=Qc::with('user')->get()->keyBy('audit_id');
        $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();
        if($ids->count()>0){
            $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereIn('id',$ids->pluck('audit_id'))->whereNotIn('id',$savedQcIds)->get();
        }
        else{
            $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereNotIn('id',$savedQcIds)->get();
        }
        // dd($data,$ids);
        return view('audit.audit_list',compact('data','ids'));
    }
    public function audited_list_Post(Request $request)
    {
        $sheetids=[];
        $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();
        $ids=Qc::with('user')->whereNotIn('audit_id',$savedQcIds)->get()->keyBy('audit_id');
        $savedIds=SavedAudit::all()->pluck('audit_id')->toArray();
        if($ids->count()>0){
            $query = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereIn('id',$ids->pluck('audit_id'))->whereNotIn('id',$savedQcIds);
        }
        else{
            $query = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereNotIn('id',$savedQcIds);
        }
        // $query = Audit::with(['qmsheet','product','branch','yard','agency'])->whereNotIn('id',$ids);
        if(!empty($request->lob)){
            $sheetids = QmSheet::where('lob',$request->lob)->get(['id'])->pluck('id');
            $query->whereIn('qm_sheet_id',$sheetids);
        }
        if($request->start_date){
            $start=Carbon::parse($request->start_date)->format('y-m-d 00:00:00');
            $query->whereDate('created_at','>=',$start);
        }
        if($request->end_date){
            $end=Carbon::parse($request->end_date)->format('y-m-d 23:59:59');
            $query->whereDate('created_at','<=',$end);
        }
        $data=$query->get();
        // dd($request->all(),$start,$data,$query->toSql());
        return view('audit.audit_list',compact('data','ids','savedQcIds'));
    }
    public function store_audit(Request $request)
    {
        logger($request);
        
        //return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'data'=>$request], 200);
        //create audit record
        $new_ar = new Audit;
        $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
        $new_ar->audited_by_id = Auth::user()->id;
        $new_ar->is_critical = isset($request->submission_data[0]['is_critical'])?($request->submission_data[0]['is_critical']):0;
        $new_ar->overall_score = $request->submission_data[0]['overall_score'];
        // $new_ar->audit_date = Carbon::now()->format('Y-m-d');
        // $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
        $new_ar->branch_id = (isset($request->submission_data[0]['branch_id']))?$request->submission_data[0]['branch_id']:null;
        $new_ar->agency_id = (isset($request->submission_data[0]['agency_id']))?$request->submission_data[0]['agency_id']:null;
        $new_ar->yard_id = (isset($request->submission_data[0]['yard_id']))?$request->submission_data[0]['yard_id']:null;
        $new_ar->branch_repo_id = (isset($request->submission_data[0]['branch_repo_id']))?$request->submission_data[0]['branch_repo_id']:null;
        $new_ar->agency_repo_id = (isset($request->submission_data[0]['agency_repo_id']))?$request->submission_data[0]['agency_repo_id']:null;
        $new_ar->product_id = (isset($request->submission_data[0]['product_id']))?$request->submission_data[0]['product_id']:null;
        $new_ar->collection_manager_email = (isset($request->submission_data[0]['collection_manager_email']))?$request->submission_data[0]['collection_manager_email']:null;
        $new_ar->agency_manager_email = (isset($request->submission_data[0]['agency_manager_email']))?$request->submission_data[0]['agency_manager_email']:null;
        $new_ar->yard_manager_email = (isset($request->submission_data[0]['yard_manager_email']))?$request->submission_data[0]['yard_manager_email']:null;
        $new_ar->collection_manager_id = (isset($request->submission_data[0]['collection_manager_id']))?$request->submission_data[0]['collection_manager_id']:null;



        $new_ar->save();
        if(isset($request->submission_data[0]['agency_id']) && $request->submission_data[0]['agency_manager'] != '')
        {
            Agency::where('id',$new_ar->agency_id)->update(['agency_manager'=>$request->submission_data[0]['agency_manager'],'agency_phone'=>$request->submission_data[0]['agency_phone']]);
        }
        if($request->submission_data[0]['status'] == 'save')
        {
            SavedAudit::create(['audit_id'=>$new_ar->id,'status'=>1]);
        }
        if(isset($request->submission_data[0]['artifactIds'])){
            $artifactIds=json_decode($request->submission_data[0]['artifactIds']);
            foreach($artifactIds as $item){
                Artifact::where('id',$item)->update(['audit_id'=>$new_ar->id]);
            }
        }
        if($new_ar->id)
        {
           // store parameter wise data
            foreach ($request->parameters as $key => $value) {

                $new_arb = new AuditParameterResult;
                $new_arb->audit_id =  $new_ar->id;
                $new_arb->parameter_id = $key;
                $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                $new_arb->orignal_weight = ($value['parameter_weight']!=null)?$value['parameter_weight']:0;
                $new_arb->temp_weight = $value['temp_total_weightage'];
                $new_arb->with_fatal_score = $value['score_with_fatal'];
                $new_arb->without_fatal_score = $value['score_with_fatal'];
                // $new_arb->without_fatal_score = $value['score_without_fatal'];

                if($value['temp_total_weightage']!=0)
                {
                    $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage'])*100;
                    // $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage'])*100;
                    $new_arb->without_fatal_score_pre = ($value['score_with_fatal'] / $value['temp_total_weightage'])*100;
                }
                // $new_arb->is_critical = $value['is_fatal'];

                $new_arb->save();

                // store sub parameter wise data
                foreach ($value['subs'] as $key_sb => $value_sb) {
                    if($value_sb['temp_weight']);
                    {
                        $new_arc = new AuditResult;
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
                            $new_arc->selected_per = ($value_sb['selected_per']!='select percentage')?$value_sb['selected_per']:null;
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
                        $new_arc->save();
                    }
                }

            }

        }
        return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'audit_id'=>$new_ar->id], 200);
    }
    public function update_audit(Request $request)
    {
        logger($request);
        
        //return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'data'=>$request], 200);
        //create audit record
        $new_ar = Audit::find($request->submission_data[0]['id']);
        $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
        // $new_ar->audited_by_id = Auth::user()->id;
        $new_ar->is_critical = isset($request->submission_data[0]['is_critical'])?($request->submission_data[0]['is_critical']):0;
        $new_ar->overall_score = $request->submission_data[0]['overall_score'];
        // $new_ar->audit_date = Carbon::now()->format('Y-m-d');
        // $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
        $new_ar->branch_id = (isset($request->submission_data[0]['branch_id']))?$request->submission_data[0]['branch_id']:null;
        $new_ar->agency_id = (isset($request->submission_data[0]['agency_id']))?$request->submission_data[0]['agency_id']:null;
        $new_ar->yard_id = (isset($request->submission_data[0]['yard_id']))?$request->submission_data[0]['yard_id']:null;
        $new_ar->product_id = (isset($request->submission_data[0]['product_id']))?$request->submission_data[0]['product_id']:null;
        $new_ar->branch_repo_id = (isset($request->submission_data[0]['branch_repo_id']))?$request->submission_data[0]['branch_repo_id']:null;
        $new_ar->agency_repo_id = (isset($request->submission_data[0]['agency_repo_id']))?$request->submission_data[0]['agency_repo_id']:null;
        $new_ar->collection_manager_id = (isset($request->submission_data[0]['collection_manager_id']))?$request->submission_data[0]['collection_manager_id']:null;
        $new_ar->update();
        
        if(isset($request->submission_data[0]['agency_id']) && isset($request->submission_data[0]['agency_manager']) && $request->submission_data[0]['agency_manager'] != '')
        {
            Agency::where('id',$new_ar->agency_id)->update(['agency_manager'=>$request->submission_data[0]['agency_manager'] ,'agency_phone'=>$request->submission_data[0]['agency_phone']]);
        }
        if(isset($request->submission_data[0]['status']) && $request->submission_data[0]['status'] == 'submit')
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
                $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
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
        return response()->json(['status'=>200,'message'=>"Audit saved successfully."], 200);
    }
    public function get_reasons_by_type($type_id)
    {
        $all_reasons = Reason::where('reason_type_id',$type_id)->pluck('name','id');
        return response()->json(['status'=>200,'message'=>".",'data'=>$all_reasons], 200);
    }
    public function getUsers($val,$type){
        $user=User::role('Collection Manager')->where('name','like',"%$val%")->get();
        return response()->json(['data'=>$user]);
    }
    public function rejectUsers($email,$auditId,$type){
        $audit=Audit::find($auditId);
        switch($type){
            case 'collection':
                $audit->collection_manager_email=null;
            break;
            case 'agency':
                $audit->agency_manager_email=null;
            break;
            case 'yard':
                $audit->yard_manager_email=null;
            break;
        }
        $audit->save();
        return response()->json(['status'=>true]);
    }
    public function saveUsers($email,$auditId,$type,$userid){
        $audit=Audit::find($auditId);
        if($audit->branch_id!=null){
            $branch=$audit->branch_id;
        }
        else if($audit->agency_id!=null){
            $branch=Agency::find($audit->agency_id)->branch_id;
        }
        else{
            $branch=Yard::find($audit->yard_id)->branch_id;
        }
        switch($type){
            case 'collection':
                $audit->collection_manager_email=null;
                if($branch){
                    $user=User::where('email',$email)->first();
                    $branchableID=Branchable::where(['branch_id'=>$branch,'manager_id'=>$userid])->update(['manager_id'=>$user->id]);
                }
            break;
            case 'agency':
                $audit->agency_manager_email=null;
                $user=User::where('email',$email)->first();
                  $agency=Agency::where('id',$audit->agency_id)->update(['agency_manager'=>$user->id]);
            break;
            case 'yard':
                $audit->yard_manager_email=null;
                $user=User::where('email',$email)->first();
                $yard=Yard::where('id',$audit->yard_id)->update(['agency_manager'=>$user->id]);
            break;
        }
        $audit->save();
        return response()->json(['status'=>true]);
    }
}
