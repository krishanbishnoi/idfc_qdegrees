<?php



namespace App\Http\Controllers;
ini_set('max_execution_time', 380);


use App\Audit;

use App\OldScore;

use App\AuditAlertBox;

use App\AuditParameterResult;

use App\AuditResult;

use App\AuditCycle;

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

use App\Model\AcrReportData;



use App\Model\CashDepositionData;

use App\Model\ReceiptCutData;

use App\Model\DelaySeconAllocData;



use DB;

use App\AuditQc;

use App\QcParameterResult;

use App\QcResult;

ini_set('memory_limit', '-1');



use Maatwebsite\Excel\Facades\Excel;

use App\Exports\QcAndQaChangesExport;

use App\Imports\AcrImport;
use App\Imports\CashDespositionImport;
use App\Imports\ReceiptCutImport;
use App\Imports\SecondaryAllocationImport;
use App\Imports\OldscoreImport;



class AuditController extends Controller

{

    
    public function sendTestMail($val) {
        
        // $email=array("abhilasha.kenge@qdegrees.com");
        // $subject="Qdegrees Test Mail";
        // $data=array();
        // Mail::send('audit.test_mail',["data1"=>$data], function ($message) use($email,$subject){
        //     $message->from('noreply@qdegrees.com', 'IDFC FIRST Bank')->to($email)->subject($subject); 
        // });
        // echo "mail sent";
        // die;
        $audit=Audit::with('branchnew','branchnew.city','branchnew.city.state','branchnew.city.state.region','yard','agency','branchRepo','agencyRepo','collectionManagerData','qmsheet','productnew','qa_qtl_detail')->find($val);
        //dd($audit);
        $audit_data=AuditResult::with('parameter_detail','sub_parameter_detail')->where(["audit_id"=>$val,"is_alert"=>1])->get();
        
        
        //echo "jdfjd"; die;
            $branchables=Branchable::with('acm','rcm')->where(['branch_id'=>$audit->parent_branch_id,'product_id'=>$audit->product_id,'manager_id'=>$audit->branchnew->manager_id])->first();
            
            $nameAudit="";
            
            $tl_email="";
            //North
            if($audit->branchnew->city && $audit->branchnew->city->state && $audit->branchnew->city->state->region && $audit->branchnew->city->state->region->id == 1) {
                 $tl_email="pradeep.singh@qdegrees.com";
            }
            //East
            if($audit->branchnew->city && $audit->branchnew->city->state && $audit->branchnew->city->state->region && $audit->branchnew->city->state->region->id == 2) {
                 $tl_email="junaid.kashif@qdegrees.com";
            }
            //West
            if($audit->branchnew->city && $audit->branchnew->city->state && $audit->branchnew->city->state->region && $audit->branchnew->city->state->region->id == 3) {
                 $tl_email="abdul.kadir@qdegrees.com";
            }
            //South
            if($audit->branchnew->city && $audit->branchnew->city->state && $audit->branchnew->city->state->region && $audit->branchnew->city->state->region->id == 4) {
                 $tl_email="sreedhar.gajula@qdegrees.com";
            }
    									    
    	    if($audit->branchRepo && $audit->branchRepo->name != "" && !is_null($audit->branchRepo->name)) {
    	        $nameAudit=$audit->branchRepo->name;
    	    }
    	    elseif($audit->agency && $audit->agency->name != "" && !is_null($audit->agency->name)) {
    	        $nameAudit=$audit->agency->name;
    	    }
    	    elseif($audit->agencyRepo && $audit->agencyRepo->name != "" && !is_null($audit->agencyRepo->name)) {
    	        $nameAudit=$audit->agencyRepo->name;
    	    }
    	    elseif($audit->yard && $audit->yard->name != "" && !is_null($audit->yard->name)) {
    	        $nameAudit=$audit->yard->name;
    	    }
    	    else {
    	        $nameAudit=$audit->branchnew->name;
    	    }
    		
    		$email=array('abhishek.gupta@qdegrees.com');
            
            $cc=array('devendra.saini@qdegrees.com','aditya.diwakar@qdegrees.com');
            
             $bcc=array('devendra.saini@qdegrees.com');
            
            
            if($audit->collectionManagerData->email != "") {
                //$email[]=$audit->collectionManagerData->email;
            }
            
            if($audit->qa_qtl_detail->email != "") {
                $email[]=$audit->qa_qtl_detail->email;
            }
            
            if($branchables && $branchables->acm && $branchables->acm->email != "") {
               // $cc[]=$branchables->acm->email;
            }
            if($branchables && $branchables->rcm && $branchables->rcm->email != "") {
               // $cc[]=$branchables->rcm->email;
            }
            
            if($tl_email != "") {
                $cc[]=$tl_email;
            }
           
            
            //echo '<pre>'; print_r($audit->lob); die;
            $newDate = date("m/Y", strtotime($audit->audit_date_by_aud));  
            $subject=$audit->branchnew->name."_".$audit->qmsheet->type."_".$nameAudit."_".$audit->collectionManagerData->name."_".$audit->productnew->name."_".$newDate;
            
           	
            $errLevel = error_reporting(E_ALL ^ E_NOTICE);
            Mail::send('audit.test_mail',["audit"=>$audit,"audit_data"=>$audit_data], function ($message) use($email,$cc,$bcc,$subject){
                $message->from('auditemail.noreply@qdegrees.org', 'IDFC FIRST Bank Audit Acknowledgment Email')->to($email)->cc($cc)->bcc($bcc)->subject($subject); 
            });
            error_reporting($errLevel);
            //return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'audit_id'=>$val], 200);
        
         echo "Mail sent successfully."; die;
        //return view("audit.test_mail",compact('audit','audit_data'));
    }

    public function render_audit_sheet($qm_sheet_id)

    {

        //dd(all_non_scoring_obs_options(1));



        $data = QmSheet::with('parameter.qm_sheet_sub_parameter')->find(Crypt::decrypt($qm_sheet_id));
        
        $cycle=AuditCycle::orderBy('id','desc')->limit(3)->get();

        // $branch=Branch::all();

        // $agency=Agency::all();

        // $yard=Yard::all();

        /*if($user_role = Auth::user()->roles()->first()->name == 'Quality Auditor'){

            $brancid_data =Branchable::distinct()->where('auditor_id',Auth::user()->id)->where('status',1)->get()->pluck('branch_id');

            $branch=Branch::where('lob',$data->lob)->whereIn('id',$brancid_data)->get();

        }

        else{

            $branch=Branch::where('lob',$data->lob)->get();

        }*/

        $branch=Branch::where('lob',$data->lob)->orderBy('name', 'ASC')->get();

        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

      	return view('audit.render_sheet',compact('qm_sheet_id','data','branch','agency','yard','branchRepo','agencyRepo','cycle'));

        // return view('audit.render_sheet',compact('qm_sheet_id','data','branch'));

    }
    
    public function render_audit_sheet_new($qm_sheet_id)

    {

        //dd(all_non_scoring_obs_options(1));



        $data = QmSheet::with('parameter.qm_sheet_sub_parameter')->find(Crypt::decrypt($qm_sheet_id));
        
        $cycle=AuditCycle::orderBy('id','desc')->limit(3)->get();

        // $branch=Branch::all();

        // $agency=Agency::all();

        // $yard=Yard::all();

        /*if($user_role = Auth::user()->roles()->first()->name == 'Quality Auditor'){

            $brancid_data =Branchable::distinct()->where('auditor_id',Auth::user()->id)->where('status',1)->get()->pluck('branch_id');

            $branch=Branch::where('lob',$data->lob)->whereIn('id',$brancid_data)->get();

        }

        else{

            $branch=Branch::where('lob',$data->lob)->get();

        }*/

        $branch=Branch::where('lob',$data->lob)->orderBy('name', 'ASC')->get();

        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->orderBy('name', 'ASC')->get();

      	return view('audit.render_sheet_new',compact('qm_sheet_id','data','branch','agency','yard','branchRepo','agencyRepo','cycle'));

        // return view('audit.render_sheet',compact('qm_sheet_id','data','branch'));

    }

    public function getProduct($id,$type){

        if($type=='branch'){

            // $branchable=Branch::with('branchable','city')->where('id',$id)->first();

            //$productIds=Branchable::where('branch_id',$id)->where('auditor_id',Auth::user()->id)->where('status',1)->get()->pluck('product_id')->toArray();

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

            $branch_repo=BranchRepo::find($id);

           $productIds=Branchable::where('branch_id',$branch_repo->branch_id)->get()->pluck('product_id')->toArray();

            $branchable=Products::whereIn('id',array_unique($productIds))->get();

        } 

        else if($type=='agency_repo'){

            $agency_repo=AgencyRepo::find($id);

            $productIds=Branchable::where('branch_id',$agency_repo->branch_id)->get()->pluck('product_id')->toArray();

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


            
              //  $q->where('product_id',$product_id);
              // following code done by Amit Pareek
              //$q->where('product_id',$product_id)->latest();
              $q->where('product_id',$product_id)->orderBy('id','DESC');  
            },'city','branchable.ncm','branchable.rcm','branchable.ghead','branchable.zcm','branchable.acm'])->where('id',$id)->first();
            
        }

        else if($type=='agency'){

            $agency=Agency::with('user')->find($id);

            $branchable=Branch::with(['branchable'=>function($q) use($product_id){

               // $q->where('product_id',$product_id);

               //$q->where('product_id',$product_id)->latest();
                $q->where('product_id',$product_id)->orderBy('id','DESC');  

            },'city','branchable.ncm','branchable.rcm','branchable.ghead','branchable.zcm','branchable.acm'])->where('id',$agency->branch_id)->first();

        }

        else if($type=='yard'){

            $yard=Yard::with('user')->find($id);

            $agency=Agency::with('user')->find($yard->agency_id);

            $branchable=Branch::with(['branchable'=>function($q) use($product_id){

               // $q->where('product_id',$product_id);

                //$q->where('product_id',$product_id)->latest();
              $q->where('product_id',$product_id)->orderBy('id','DESC');  


            },'city','branchable.ncm','branchable.rcm','branchable.ghead','branchable.zcm','branchable.acm'])->where('id',$yard->branch_id)->first();

        }

        else if($type=='branch_repo'){

            $BranchRepo=BranchRepo::find($id);

            $branchable=Branch::with(['branchable'=>function($q) use($product_id){

              //  $q->where('product_id',$product_id);

             //$q->where('product_id',$product_id)->latest();
                  $q->where('product_id',$product_id)->orderBy('id','DESC');  

            },'city','branchable.ncm','branchable.rcm','branchable.ghead','branchable.zcm','branchable.acm'])->where('id',$BranchRepo->branch_id)->first();

        }

        else if($type=='agency_repo'){

            $AgencyRepo=AgencyRepo::find($id);

            $branchable=Branch::with(['branchable'=>function($q) use($product_id){

              //  $q->where('product_id',$product_id);

                //$q->where('product_id',$product_id)->latest();
                  $q->where('product_id',$product_id)->orderBy('id','DESC');  

            },'city','branchable.ncm','branchable.rcm','branchable.ghead','branchable.zcm','branchable.acm'])->where('id',$AgencyRepo->branch_id)->first();

        }

        // dd($agency);
       /*  echo "<pre>";
        print_r($branchable);
        die; */
        return view('audit.branch',compact('branchable','type','agency','yard','AgencyRepo','BranchRepo'));



    }

    public function renderBranchQc($id,$type,$auditid,$product_id){
        $agency=[];

        $yard=[];

        $AgencyRepo=[];

        $BranchRepo=[];
        $userData=[];
        if((!empty($auditid)) && ($auditid != 'null') && ($auditid != 'undefined')){
        $manager_id=Audit::with('user')->where('id',$auditid)->orderby('id','desc')->first();
        $userData=['id'=>$manager_id->user->id,'name'=>$manager_id->user->name];
        }
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

         //dd($agency);

        return view('audit.branchQc',compact('branchable','type','agency','yard','AgencyRepo','BranchRepo','userData'));



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

        $cycle=AuditCycle::orderBy('id','desc')->limit(3)->get();


        $redalertIds = RedAlert::where('audit_id',$result->id)->get()->pluck('sub_parameter_id')->toArray();
        //echo $result->id; die; 71706 (test audit ID)
        // dd($data,$artifactIds,$result->id);

       return view('audit.render_sheet_edit',compact('qm_sheet_id','cycle','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','artifactIds','redalertIds'));

    }

    public function render_audit_sheet_View($qm_sheet_id)

    {

        ini_set('memory_limit', '-1');

        //dd(all_non_scoring_obs_options(1));

        $result=Audit::with(['audit_parameter_result','audit_results'])->where('id',Crypt::decrypt($qm_sheet_id))->first();

	//dd($result->id);        
$resultPar=AuditParameterResult::where('audit_id',$result->id)->get()->keyBy('parameter_id');

        $resultSubPar=AuditResult::where('audit_id',$result->id)->get()->keyBy('sub_parameter_id');

        $data = QmSheet::with('parameter.qm_sheet_sub_parameter.artifact')->find($result->qm_sheet_id);

        $branch=Branch::where('lob',$data->lob)->get();

        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();

        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();

        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();

        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();

        $artifactIds = Artifact::where('audit_id',$result->id)->get()->pluck('id')->toArray();

        $redalertIds = RedAlert::where('audit_id',$result->id)->get()->pluck('sub_parameter_id')->toArray();

        // dd($data,$resultPar,$resultSubPar);

    	return view('audit.view_sheet',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','artifactIds','redalertIds'));

    

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

        $redalertIds = RedAlert::where('audit_id',$result->id)->get()->pluck('sub_parameter_id')->toArray();

    	return view('audit.view_sheet_qc',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','qc','artifactIds','redalertIds'));

    

    }

    public function detail_audit_sheet_edit($qm_sheet_id)

    {
       // echo '<pre>'; print_r(Crypt::decrypt($qm_sheet_id)); die;
        //dd(all_non_scoring_obs_options(1));

        $result=Audit::with(['audit_parameter_result','audit_results','user'])->where('id',Crypt::decrypt($qm_sheet_id))->first();

        $resultPar=AuditParameterResult::where('audit_id',$result->id)->get()->keyBy('parameter_id');

        $resultSubPar=AuditResult::where('audit_id',$result->id)->get()->keyBy('sub_parameter_id');
       // DB::enableQueryLog();
        $data = QmSheet::with('parameter.qm_sheet_sub_parameter')->find($result->qm_sheet_id);
        //dd(DB::getQueryLog());
        //echo $result->qm_sheet_id;
       // dd($data);

        $branch=Branch::where('lob',$data->lob)->get();

        $agency=Agency::whereIn('branch_id',$branch->pluck('id'))->get();

        $yard=Yard::whereIn('branch_id',$branch->pluck('id'))->get();

        $branchRepo=BranchRepo::whereIn('branch_id',$branch->pluck('id'))->get();

        $agencyRepo=AgencyRepo::whereIn('branch_id',$branch->pluck('id'))->get();

        $products=Products::get();
        
        $artifactIds = Artifact::where('audit_id',$result->id)->get()->pluck('id')->toArray();

        $qc=Qc::where('audit_id',$result->id)->first();

        

        $redalertIds = RedAlert::where('audit_id',$result->id)->get()->pluck('sub_parameter_id')->toArray();

        // dd($data,$resultPar,$resultSubPar);

        

        $preview_redalert = DB::table('qm_sheet_parameters as parameter')

        ->join('qm_sheet_sub_parameters as subp', 'subp.qm_sheet_parameter_id', '=', 'parameter.id')

        ->join('red_alerts as ra','ra.sub_parameter_id','=','subp.id')

        ->select('ra.*', 'subp.sub_parameter','parameter.parameter')

        ->where('ra.audit_id',$result->id)

        ->get()->toArray();
        

    	return view('audit.detail_sheet_edit',compact('qm_sheet_id','data','result','resultPar','resultSubPar','branch','agency','yard','branchRepo','agencyRepo','artifactIds','qc','redalertIds','preview_redalert','products'));

    }

    public function save_qc_status(Request $request){

        // dd($request->all());

        if($request->type=='save'){

            SavedQcAudit::updateOrCreate(['audit_id'=>$request->audit_id],['audit_id'=>$request->audit_id,'status'=>1]);

        }
        
       
        else if($request->type=='submit' || $request->type=='savebyqc'){

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

                $emails=User::whereIn('id',$ids)->role('Area Collection Manager')->get(['id','email'])->pluck('email')->toArray();

                $otherDetails['collection']=User::whereIn('id',$ids)->role('Collection Manager')->first(['id','name'])->name;

                //$emails[]='ravindra.swami9@gmail.com';

                $url=url('red-alert/').'/'.Crypt::encrypt($audit->id);

               /* Mail::send('emails.alert', ['data' => $audit,'otherDetails'=>$otherDetails,'auditResult'=>$auditResult,'url'=>$url], function ($m) use ($emails,$attach) {

                    //$m->from('hello@app.com', 'Your Application');

                    $m->to($emails)->subject('Alert');

                    foreach($attach as $item){

                        $m->attach($item);

                    }

                });*/

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

    public function audited_list(Request $request)

    {
        
        // die('Under Maintenance');
        $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();

        $ids=Qc::with('user')->whereNotIn('audit_id',$savedQcIds)->get()->keyBy('audit_id');

        $savedIds=SavedAudit::all()->pluck('audit_id')->toArray();

        if($ids->count()>0){
            if(isset($request->search)){
                
               /*  echo "in";
                die; */
                $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','user','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereNotIn('id',$ids->pluck('audit_id'))->whereNotIn('id',$savedIds)
                
                ->orderby('id','desc')->paginate(10);
            } else {
                $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','user','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereNotIn('id',$ids->pluck('audit_id'))->whereNotIn('id',$savedIds)
                
                ->orderby('id','desc')->paginate(10);
            }
            
       //  $data= Audit::select('id','latitude','longitude','created_at','qm_sheet_id','branch_id','product_id','agency_id','collection_manager_id','agency_repo_id','branch_repo_id','audited_by_id','qm_sheet_id')->with(array('qmsheet'=>function($query){
       //   $query->select('id','name','type','lob');
       // }))->with(array('product'))->with(array('product'=>function($query){
       //  $query->select('id','name');
       // }))->with(array('branch.branchable'))->with(array('branch'=>function($query){
       //  $query->select('name','city_id');
       // }))->with(array('branch.city.state'))->with(array('user'=>function($query){
       //  $query->select('id','name','email');
       // }))->with(array('yard'=>function($query){
       //  $query->select('name');
       // }))->with(array('yard.branch.city.state'))->with(array('agency'=>function($query){
       //  $query->select('id','name','branch_id');
       // }))->with(array('agency.branch.city.state'))->with(array('qa_qtl_detail'=>function($query){
       //  $query->select('name');
       // }))->with(array('branchRepo'=>function($query){
       //  $query->select('name');
       // }))->with(array('agencyRepo'=>function($query){
       //  $query->select('name');
       // }))->withCount('artifact')->whereNotIn('id',$ids->pluck('audit_id'))->whereNotIn('id',$savedIds)->orderby('id','desc')->get();

       }

        else{

            $data = Audit::with(['qmsheet','product','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','user','branchRepo.branch.city.state','agencyRepo.branch.city.state'])->withCount('artifact')->whereNotIn('id',$savedIds)->orderby('id','desc')->paginate(10);

            /*     $data= Audit::select('id','latitude','longitude','created_at','qm_sheet_id','branch_id','product_id','agency_id','collection_manager_id','agency_repo_id','branch_repo_id','audited_by_id','qm_sheet_id')->with(array('qmsheet'=>function($query){
            $query->select('id','name','type','lob');
            }))->with(array('product'))->with(array('product'=>function($query){
            $query->select('id','name');
            }))->with(array('branch.branchable'))->with(array('branch'=>function($query){
            $query->select('name','city_id');
            }))->with(array('branch.city.state'))->with(array('user'=>function($query){
            $query->select('id','name','email');
            }))->with(array('yard'=>function($query){
            $query->select('name');
            }))->with(array('yard.branch.city.state'))->with(array('agency'=>function($query){
            $query->select('id','name','branch_id');
            }))->with(array('agency.branch.city.state'))->with(array('qa_qtl_detail'=>function($query){
            $query->select('name');
            }))->with(array('branchRepo'=>function($query){
            $query->select('name');
            }))->with(array('agencyRepo'=>function($query){
            $query->select('name');
            }))->withCount('artifact')->whereNotIn('id',$savedIds)->orderby('id','desc')->get(); */

        }

        // dd($data,$ids);

        return view('audit.audit_list',compact('data','ids','savedQcIds'));

    }

    public function audited_list_new(Request $request)
    {  
        $savedQcIds = SavedQcAudit::all()->pluck('audit_id')->toArray();
        $ids = Qc::with('user')->whereNotIn('audit_id', $savedQcIds)->get()->keyBy('audit_id');
        $savedIds = SavedAudit::all()->pluck('audit_id')->toArray();
    
        $query = Audit::with([
                'qmsheet',
                'product',
                'audit_cycle',
                'branch.city.state',
                'branch.branchable',
                'yard.branch.city.state',
                'agency.branch.city.state',
                'qa_qtl_detail',
                'user',
                'branchRepo.branch.city.state',
                'agencyRepo.branch.city.state'
            ])
            ->withCount('artifact')
            ->whereNotIn('id', $savedIds);
    
        if ($ids->count() > 0) {
            $query->whereNotIn('id', $ids->pluck('audit_id'));
        }
    
        if ($request->start_date) {
            $query->whereDate('created_at', '>', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }
    
        $data = $query->orderby('id', 'desc')->get();
    
        return view('audit.audit_list_new', compact('data', 'ids', 'savedQcIds'));
    }
    public function done_audited_list(Request $request)
    {
        if($request->start_date) {
            $ids = Qc::with('user')->get()->keyBy('audit_id');
    
            $savedQcIds = SavedQcAudit::all()->pluck('audit_id')->toArray();
    
            if ($ids->count() > 0) {
                $data = Audit::with(['qmsheet','product','audit_cycle','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])
                    ->withCount('artifact')
                    ->whereIn('id', $ids->pluck('audit_id'))
                    ->whereNotIn('id', $savedQcIds)
                    ->whereDate('created_at', '>', $request->start_date)
                    ->whereDate('created_at', '<=', $request->end_date)
                    ->orderby('id', 'desc')
                    ->get();
            } else {
                $data = Audit::with(['qmsheet','product','audit_cycle','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])
                    ->withCount('artifact')
                    ->whereNotIn('id', $savedQcIds)
                    ->whereDate('created_at', '>', $request->start_date)
                    ->whereDate('created_at', '<=', $request->end_date)
                    ->orderby('id', 'desc')
                    ->get();
            }
    
            return view('audit.audit_list_done', compact('data', 'ids'));
        } else {
            $ids = Qc::with('user')->get()->keyBy('audit_id');
    
            $savedQcIds = SavedQcAudit::all()->pluck('audit_id')->toArray();
    
            $currentMonthStart = Carbon::now()->startOfMonth()->toDateString();
            $currentMonthEnd = Carbon::now()->endOfMonth()->toDateString();
    
            if ($ids->count() > 0) {
                $data = Audit::with(['qmsheet','product','audit_cycle','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])
                    ->withCount('artifact')
                    ->whereIn('id', $ids->pluck('audit_id'))
                    ->whereNotIn('id', $savedQcIds)
                    ->whereDate('created_at', '>=', $currentMonthStart)
                    ->whereDate('created_at', '<=', $currentMonthEnd)
                    ->orderby('id', 'desc')
                    ->get();
            } else {
                $data = Audit::with(['qmsheet','product','audit_cycle','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state'])
                    ->withCount('artifact')
                    ->whereNotIn('id', $savedQcIds)
                    ->whereDate('created_at', '>=', $currentMonthStart)
                    ->whereDate('created_at', '<=', $currentMonthEnd)
                    ->orderby('id', 'desc')
                    ->get();
            }
    
            return view('audit.audit_list_done', compact('data', 'ids'));
        }
    
        // dd($data,$ids);
    
        // return view('audit.audit_list_new',compact('data','ids'));
    }
    

//    public function done_audited_list(Request $request)
// {
//     $ids = Qc::with('user')->get()->keyBy('audit_id');
//     $savedQcIds = SavedQcAudit::all()->pluck('audit_id')->toArray();

//     $query = Audit::with([
//         'qmsheet', 'product', 'audit_cycle', 'branch.city.state', 'branch.branchable', 
//         'yard.branch.city.state', 'agency.branch.city.state', 'qa_qtl_detail', 
//         'branchRepo.branch.city.state', 'agencyRepo.branch.city.state'
//     ])->withCount('artifact')
//     ->whereNotIn('id', $savedQcIds)
//     ->orderBy('id', 'desc');

//     if ($request->start_date) {
//         $query->whereDate('created_at', '>', $request->start_date)
//               ->whereDate('created_at', '<=', $request->end_date);
//     } else {
//         $query->whereMonth('created_at', date('m'))
//               ->whereYear('created_at', date('Y'));
//     }

//     if ($ids->count() > 0) {
//         $query->whereIn('id', $ids->pluck('audit_id'));
//     }

//     $data = $query->get();
    
//     echo '<pre>'; print_r($data); die;
//     return view('audit.audit_list_new', compact('data', 'ids'));
// }

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

    {   //dd("fefef");
    //dd($request);
        DB::beginTransaction();
        try{
        logger($request);

        $user_role = Auth::user()->roles()->first()->name;

        $latlong = explode(" ",$request->submission_data[0]['geotag']); 

        //return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'data'=>$request], 200);

        //create audit record

        $new_ar = new Audit;
        
        $parent_br_id=0;
    
        if(isset($request->submission_data[0]['agency_id'])) {
            $fi=Agency::find($request->submission_data[0]['agency_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['yard_id'])) {
            $fi=Yard::find($request->submission_data[0]['yard_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['branch_repo_id'])) {
            $fi=BranchRepo::find($request->submission_data[0]['branch_repo_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['agency_repo_id'])) {
            $fi=AgencyRepo::find($request->submission_data[0]['agency_repo_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['branch_id'])) {
            $parent_br_id=$request->submission_data[0]['branch_id'];
        }
        
        $new_ar->parent_branch_id =  $parent_br_id;
        $new_ar->audit_cycle_id = $request->submission_data[0]['audit_cycle'];
        $new_ar->audit_date_by_aud = date('Y-m-d',strtotime($request->submission_data[0]['audit_date']));
        $new_ar->latitude = $latlong[0];
        $new_ar->longitude = $latlong[1];
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
         $get_parameters=DB::table('qm_sheet_parameters')->select('id')->where('qm_sheet_id',$request->submission_data[0]['qm_sheet_id'])->get()->toArray();
             $db_parameterids=array_column($get_parameters, 'id');



        // added for qc audit records

        $audit_qc = new AuditQc;

        $audit_qc->qm_sheet_id = $new_ar->qm_sheet_id ;

        $audit_qc->audited_by_id = Auth::user()->id;

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



        $new_ar->save();

        if( $request->submission_data[0]['status'] == 'submit')

        {

            $audit_qc->save();

        }



        if(isset($request->submission_data[0]['agency_id']) && $request->submission_data[0]['agency_manager'] != '')

        {

            Agency::where('id',$new_ar->agency_id)->update(['agency_manager'=>$request->submission_data[0]['agency_manager'],'agency_phone'=>$request->submission_data[0]['agency_phone']]);

        }



        //added by  nisha for change status in branchable if audit submitting

            if(!empty($request->submission_data[0]['branch_id'])){

                $idupdate = $request->submission_data[0]['branch_id'];

                

            }

            if(!empty($request->submission_data[0]['agency_id'])){

                $branch_idfromagency = Agency::where('id',$request->submission_data[0]['agency_id'])->pluck('branch_id');

                $idupdate = $branch_idfromagency[0];



            }

            

            if(!empty($request->submission_data[0]['yard_id'])){



                $branch_idfromyard = Yard::where('id',$request->submission_data[0]['yard_id'])->pluck('branch_id');

                $idupdate = $branch_idfromyard[0];

            }

            if(!empty($request->submission_data[0]['branch_repo_id'])){



                $branch_idfrombranchrepo = BranchRepo::where('id',$request->submission_data[0]['branch_repo_id'])->pluck('branch_id');

                $idupdate = $branch_idfrombranchrepo[0];

            }

            if(!empty($request->submission_data[0]['agency_repo_id'])){

                

                $branch_idfromagencyrepo = AgencyRepo::where('id',$request->submission_data[0]['agency_repo_id'])->pluck('branch_id');

                

                $idupdate = $branch_idfromagencyrepo[0];

            }

            



        if($request->submission_data[0]['status'] == 'save')

        {

            SavedAudit::create(['audit_id'=>$new_ar->id,'status'=>1]);

        }

       

           // DB::enableQueryLog();

            $id4update_branchable_status = DB::table('branchables')

                ->where('branch_id',$idupdate)->where('status',1)->where('product_id',$request->submission_data[0]['product_id'])

                ->where('type','Collection_Manager')

                ->where('manager_id',$request->submission_data[0]['collection_manager_id'])

                ->update(['status'=> 2]);
           

        if(isset($request->submission_data[0]['artifactIds'])){

            $artifactIds=json_decode($request->submission_data[0]['artifactIds']);

            foreach($artifactIds as $item){

                Artifact::where('id',$item)->update(['audit_id'=>$new_ar->id]);

            }

        }

        if($new_ar->id)

        {
           $getparameterids=array_keys($request->parameters);
                $notmatched_param=array();
                $notmatched_param=array_diff($db_parameterids, $getparameterids);
                if(!empty($notmatched_param))
                {
                   foreach($notmatched_param as $parm_values)
                   {
                    $new_arb = new AuditParameterResult; 
                    $new_arb->audit_id =  $new_ar->id;
                    $new_arb->parameter_id = $parm_values;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight =0;
                    $new_arb->temp_weight = 0;
                    $new_arb->with_fatal_score = 0;
                    $new_arb->without_fatal_score = 0;
                    $new_arb->with_fatal_score_per=0;
                    $new_arb->without_fatal_score_pre=0;

                     if( $request->submission_data[0]['status'] == 'submit'){
                            $qc_arb = new QcParameterResult;
                            $qc_arb->audit_id = $new_arb->audit_id ;
                            $qc_arb->parameter_id= $new_arb->parameter_id ;
                            $qc_arb->qm_sheet_id = $new_arb->qm_sheet_id ;
                            $qc_arb->orignal_weight = $new_arb->orignal_weight ;
                            $qc_arb->temp_weight = $new_arb->temp_weight;
                            $qc_arb->with_fatal_score = $new_arb->with_fatal_score;
                            $qc_arb->without_fatal_score = $new_arb->without_fatal_score;

                            if($value['temp_total_weightage']!=0)
                            {
                                $qc_arb->with_fatal_score_per = $new_arb->with_fatal_score_per;
                                $qc_arb->without_fatal_score_pre = $new_arb->without_fatal_score_pre;
                            }
                        }
                     $new_arb->save();

                    if($request->submission_data[0]['status'] == 'submit'){
                        $qc_arb->save();
                        }   

                   }
                }
           // store parameter wise data

            foreach ($request->parameters as $key => $value) {
               if(in_array($key,$db_parameterids))
                {
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
            }

                // $new_arb->is_critical = $value['is_fatal'];



                if( $request->submission_data[0]['status'] == 'submit'){

                        $qc_arb = new QcParameterResult;

                        $qc_arb->audit_id = $new_arb->audit_id ;

                        $qc_arb->parameter_id= $new_arb->parameter_id ;

                        $qc_arb->qm_sheet_id = $new_arb->qm_sheet_id ;



                        $qc_arb->orignal_weight = $new_arb->orignal_weight ;



                        $qc_arb->temp_weight = $new_arb->temp_weight;



                        $qc_arb->with_fatal_score = $new_arb->with_fatal_score;

                        $qc_arb->without_fatal_score = $new_arb->without_fatal_score;

                        

                        if($value['temp_total_weightage']!=0)

                        {

                            $qc_arb->with_fatal_score_per = $new_arb->with_fatal_score_per;



                            $qc_arb->without_fatal_score_pre = $new_arb->without_fatal_score_pre;

                        }

                }



                $new_arb->save();

                if($request->submission_data[0]['status'] == 'submit')

                {

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

                        $new_arc->parameter_id = $key;

                        $new_arc->sub_parameter_id = $key_sb;

                        $new_arc->selected_option = ($value_sb['temp_weight']!='Critical')?$value_sb['temp_weight']:0;

                        $new_arc->option_selected = (isset($value_sb['option']))?$value_sb['option']:null;

                        $new_arc->is_critical = ($value_sb['temp_weight']!='Critical')?0:1;
                        
                        $new_arc->is_alert = (array_key_exists('ackalert',$value_sb) && $value_sb['ackalert'] == 1) ? 1 : 0;

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



                        if($request->submission_data[0]['status'] == 'submit'){

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

                            $qc_arc->is_alert = ($new_arc->is_alert == 1) ? 1 : 0;

                            $qc_arc->remark = $new_arc->remark;

                            $qc_arc->save();
                        }

                        $new_arc->save();

                    }

                }

                }

            }



        }
        DB::commit();
        // if($request->submission_data[0]['status'] == 'submit'){
        //     $callMail=$this->sendTestMail($new_ar->id);
        // }
        return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'audit_id'=>$new_ar->id], 200);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'message'=>"Audit saved unsuccessfully.",'audit_id'=>$e->getMessage()], 500);
        }
    }
    
    public function store_audit_new(Request $request)

    {   //dd("fefef");
    //dd($request);
        DB::beginTransaction();
        try{
        logger($request);

        $user_role = Auth::user()->roles()->first()->name;

        $latlong = explode(" ",$request->submission_data[0]['geotag']); 

        //return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'data'=>$request], 200);

        //create audit record

        $new_ar = new Audit;
        
        $parent_br_id=0;
    
        if(isset($request->submission_data[0]['agency_id'])) {
            $fi=Agency::find($request->submission_data[0]['agency_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['yard_id'])) {
            $fi=Yard::find($request->submission_data[0]['yard_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['branch_repo_id'])) {
            $fi=BranchRepo::find($request->submission_data[0]['branch_repo_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['agency_repo_id'])) {
            $fi=AgencyRepo::find($request->submission_data[0]['agency_repo_id']);
            $parent_br_id=$fi->branch_id;
        }
        
        if(isset($request->submission_data[0]['branch_id'])) {
            $parent_br_id=$request->submission_data[0]['branch_id'];
        }
        
        $new_ar->parent_branch_id =  $parent_br_id;
        $new_ar->audit_cycle_id = $request->submission_data[0]['audit_cycle'];
        $new_ar->audit_date_by_aud = date('Y-m-d',strtotime($request->submission_data[0]['audit_date']));
        $new_ar->latitude = $latlong[0];
        $new_ar->longitude = $latlong[1];
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
         $get_parameters=DB::table('qm_sheet_parameters')->select('id')->where('qm_sheet_id',$request->submission_data[0]['qm_sheet_id'])->get()->toArray();
             $db_parameterids=array_column($get_parameters, 'id');



        // added for qc audit records

        $audit_qc = new AuditQc;

        $audit_qc->qm_sheet_id = $new_ar->qm_sheet_id ;

        $audit_qc->audited_by_id = Auth::user()->id;

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



        $new_ar->save();

        if( $request->submission_data[0]['status'] == 'submit')

        {

            $audit_qc->save();

        }



        if(isset($request->submission_data[0]['agency_id']) && $request->submission_data[0]['agency_manager'] != '')

        {

            Agency::where('id',$new_ar->agency_id)->update(['agency_manager'=>$request->submission_data[0]['agency_manager'],'agency_phone'=>$request->submission_data[0]['agency_phone']]);

        }



        //added by  nisha for change status in branchable if audit submitting

            if(!empty($request->submission_data[0]['branch_id'])){

                $idupdate = $request->submission_data[0]['branch_id'];

                

            }

            if(!empty($request->submission_data[0]['agency_id'])){

                $branch_idfromagency = Agency::where('id',$request->submission_data[0]['agency_id'])->pluck('branch_id');

                $idupdate = $branch_idfromagency[0];



            }

            

            if(!empty($request->submission_data[0]['yard_id'])){



                $branch_idfromyard = Yard::where('id',$request->submission_data[0]['yard_id'])->pluck('branch_id');

                $idupdate = $branch_idfromyard[0];

            }

            if(!empty($request->submission_data[0]['branch_repo_id'])){



                $branch_idfrombranchrepo = BranchRepo::where('id',$request->submission_data[0]['branch_repo_id'])->pluck('branch_id');

                $idupdate = $branch_idfrombranchrepo[0];

            }

            if(!empty($request->submission_data[0]['agency_repo_id'])){

                

                $branch_idfromagencyrepo = AgencyRepo::where('id',$request->submission_data[0]['agency_repo_id'])->pluck('branch_id');

                

                $idupdate = $branch_idfromagencyrepo[0];

            }

            



        if($request->submission_data[0]['status'] == 'save')

        {

            SavedAudit::create(['audit_id'=>$new_ar->id,'status'=>1]);

        }

       

           // DB::enableQueryLog();

            $id4update_branchable_status = DB::table('branchables')

                ->where('branch_id',$idupdate)->where('status',1)->where('product_id',$request->submission_data[0]['product_id'])

                ->where('type','Collection_Manager')

                ->where('manager_id',$request->submission_data[0]['collection_manager_id'])

                ->update(['status'=> 2]);
           

        if(isset($request->submission_data[0]['artifactIds'])){

            $artifactIds=json_decode($request->submission_data[0]['artifactIds']);

            foreach($artifactIds as $item){

                Artifact::where('id',$item)->update(['audit_id'=>$new_ar->id]);

            }

        }

        if($new_ar->id)

        {
           $getparameterids=array_keys($request->parameters);
                $notmatched_param=array();
                $notmatched_param=array_diff($db_parameterids, $getparameterids);
                if(!empty($notmatched_param))
                {
                   foreach($notmatched_param as $parm_values)
                   {
                    $new_arb = new AuditParameterResult; 
                    $new_arb->audit_id =  $new_ar->id;
                    $new_arb->parameter_id = $parm_values;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight =0;
                    $new_arb->temp_weight = 0;
                    $new_arb->with_fatal_score = 0;
                    $new_arb->without_fatal_score = 0;
                    $new_arb->with_fatal_score_per=0;
                    $new_arb->without_fatal_score_pre=0;

                     if( $request->submission_data[0]['status'] == 'submit'){
                            $qc_arb = new QcParameterResult;
                            $qc_arb->audit_id = $new_arb->audit_id ;
                            $qc_arb->parameter_id= $new_arb->parameter_id ;
                            $qc_arb->qm_sheet_id = $new_arb->qm_sheet_id ;
                            $qc_arb->orignal_weight = $new_arb->orignal_weight ;
                            $qc_arb->temp_weight = $new_arb->temp_weight;
                            $qc_arb->with_fatal_score = $new_arb->with_fatal_score;
                            $qc_arb->without_fatal_score = $new_arb->without_fatal_score;

                            if($value['temp_total_weightage']!=0)
                            {
                                $qc_arb->with_fatal_score_per = $new_arb->with_fatal_score_per;
                                $qc_arb->without_fatal_score_pre = $new_arb->without_fatal_score_pre;
                            }
                        }
                     $new_arb->save();

                    if($request->submission_data[0]['status'] == 'submit'){
                        $qc_arb->save();
                        }   

                   }
                }
           // store parameter wise data

            foreach ($request->parameters as $key => $value) {
               if(in_array($key,$db_parameterids))
                {
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
            }

                // $new_arb->is_critical = $value['is_fatal'];



                if( $request->submission_data[0]['status'] == 'submit'){

                        $qc_arb = new QcParameterResult;

                        $qc_arb->audit_id = $new_arb->audit_id ;

                        $qc_arb->parameter_id= $new_arb->parameter_id ;

                        $qc_arb->qm_sheet_id = $new_arb->qm_sheet_id ;



                        $qc_arb->orignal_weight = $new_arb->orignal_weight ;



                        $qc_arb->temp_weight = $new_arb->temp_weight;



                        $qc_arb->with_fatal_score = $new_arb->with_fatal_score;

                        $qc_arb->without_fatal_score = $new_arb->without_fatal_score;

                        

                        if($value['temp_total_weightage']!=0)

                        {

                            $qc_arb->with_fatal_score_per = $new_arb->with_fatal_score_per;



                            $qc_arb->without_fatal_score_pre = $new_arb->without_fatal_score_pre;

                        }

                }



                $new_arb->save();

                if($request->submission_data[0]['status'] == 'submit')

                {

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

                        $new_arc->parameter_id = $key;

                        $new_arc->sub_parameter_id = $key_sb;

                        $new_arc->selected_option = ($value_sb['temp_weight']!='Critical')?$value_sb['temp_weight']:0;

                        $new_arc->option_selected = (isset($value_sb['option']))?$value_sb['option']:null;

                        $new_arc->is_critical = ($value_sb['temp_weight']!='Critical')?0:1;
                        
                        $new_arc->is_alert = (array_key_exists('ackalert',$value_sb) && $value_sb['ackalert'] == 1) ? 1 : 0;

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



                        if($request->submission_data[0]['status'] == 'submit'){

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

                            $qc_arc->is_alert = ($new_arc->is_alert == 1) ? 1 : 0;

                            $qc_arc->remark = $new_arc->remark;

                            $qc_arc->save();
                        }

                        $new_arc->save();

                    }

                }

                }

            }



        }
        DB::commit();
        // if($request->submission_data[0]['status'] == 'submit'){
        //     $callMail=$this->sendTestMail($new_ar->id);
        // }
        return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'audit_id'=>$new_ar->id], 200);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>500,'message'=>"Audit saved unsuccessfully.",'audit_id'=>$e->getMessage()], 500);
        }
    }



    public function update_audit(Request $request)

    {
        
        DB::beginTransaction();
        try{
        logger($request);

       /*  echo $request->submission_data[0]['id'];
        die; */

        //return response()->json(['status'=>200,'message'=>"Audit saved successfully.",'data'=>$request], 200);

        //create audit record
        
        $new_ar = Audit::find($request->submission_data[0]['id']);

        $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id']; 

        /*$new_ar->audited_by_id = Auth::user()->id;*/

        $new_ar->is_critical = isset($request->submission_data[0]['is_critical'])?($request->submission_data[0]['is_critical']):0;
        
        if(in_array('overall_score',$request->submission_data[0]) && !is_null($request->submission_data[0]['overall_score'])) {
            $new_ar->overall_score = $request->submission_data[0]['overall_score'];
            $new_ar->audit_date = Carbon::now()->format('Y-m-d');   
            $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];
        }

        

        $new_ar->branch_id = (isset($request->submission_data[0]['branch_id']))?$request->submission_data[0]['branch_id']:$new_ar->branch_id;

        $new_ar->agency_id = (isset($request->submission_data[0]['agency_id']))?$request->submission_data[0]['agency_id']:$new_ar->agency_id;

        $new_ar->yard_id = (isset($request->submission_data[0]['yard_id']))?$request->submission_data[0]['yard_id']:$new_ar->yard_id;

        $new_ar->product_id = (isset($request->submission_data[0]['product_id']))?$request->submission_data[0]['product_id']:$new_ar->product_id;

        $new_ar->branch_repo_id = (isset($request->submission_data[0]['branch_repo_id']))?$request->submission_data[0]['branch_repo_id']:$new_ar->branch_repo_id;

        $new_ar->agency_repo_id = (isset($request->submission_data[0]['agency_repo_id']))?$request->submission_data[0]['agency_repo_id']:$new_ar->agency_repo_id;

        $new_ar->collection_manager_id = (isset($request->submission_data[0]['collection_manager_id']))?$request->submission_data[0]['collection_manager_id']:$new_ar->collection_manager_id; 

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
                            
                           // $new_arc->is_alert = (array_key_exists('ackalert',$value_sb) && $value_sb['ackalert'] == 1) ? 1 : 0;

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
                            
                           // $new_arc->is_alert = (array_key_exists('ackalert',$value_sb) && $value_sb['ackalert'] == 1) ? 1 : 0;

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
        DB::commit();
        
        return response()->json(['status'=>200,'message'=>"Audit saved successfully."], 200);
    }
    catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status'=>500,'message'=>"Audit saved unsuccessfully.",'audit_id'=>''], 500);
    }

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

    

    public function excelDownloadQaChanges(Request $request){


        ini_set('memory_limit', '-1');

        ini_set('max_execution_time', 3000);
        $filter_data = $request->all();
        return Excel::download(new QcAndQaChangesExport($filter_data), 'Qa_changes.xlsx');

    }
    
    public function reportAutomation(Request $request) {

        $branchList=Branch::select('id','name')->get();
        $has_data=0;
        return view('audit.report_automate',compact('branchList','has_data'));
    }


    public function reportAutomationData(Request $request) {

        $branchList=Branch::select('id','name')->get();
        if($request->isMethod('get')) {

            $ids=Qc::with('user')->get()->keyBy('audit_id');
            $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();
            //echo "<pre>"; print_r($ids); die;
            $getBranch=Branch::with('city','city.state','branchable','yard','branchRepo','agencyRepo','agency','branchable.user')->find($request->branch);

            $yardID=array();
            $agencyID=array();
            $branchRepoID=array();
            $agencyRepoID=array();
            $collectionManagerID=array();

            if($getBranch) {
                foreach ($getBranch->yard as $key => $value) {
                   $yardID[]=$value->id;
                }
                foreach ($getBranch->agency as $key => $value) {
                   $agencyID[]=$value->id;
                }
                foreach ($getBranch->branchRepo as $key => $value) {
                   $branchRepoID[]=$value->id;
                }
                foreach ($getBranch->agencyRepo as $key => $value) {
                   $agencyRepoID[]=$value->id;
                }
                foreach ($getBranch->branchable as $key => $value) {
                    if(trim($value->type) == "Collection_Manager") {
                        $collectionManagerID[]=$value->manager_id;
                    }                   
                }
            }
            
            $brID=$request->branch;
            $start_date=$request->start_date;
            $end_date=$request->end_date;
            
            
            //$start_date=date('Y-m-d', strtotime("-3 month", strtotime($request->start_date))); 
            
            if($ids->count()>0){
                $data = Audit::with(['qmsheet','qmsheet.qm_sheet_sub_parameter','branch.branchableCollection','productnew','branch.yard','branch.agency','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state','audit_parameter_result','audit_results','qc','qc.user','collectionManagerData'])
                ->whereIn('id',$ids->pluck('audit_id'))->where('parent_branch_id',$request->branch)->whereNotIn('id',$savedQcIds)->whereDate('audit_date_by_aud', '>=', $request->start_date)->whereDate('audit_date_by_aud', '<=', $request->end_date)->orderBy('id','desc')->get();
            }
            else{
                $data = Audit::with(['qmsheet','qmsheet.qm_sheet_sub_parameter','productnew','branch.branchableCollection','branch.yard','branch.agency','branch.city.state','branch.branchable','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state','audit_parameter_result','audit_results','qc','qc.user','collectionManagerData'])
                  ->whereNotIn('id',$savedQcIds)->where('parent_branch_id',$request->branch)->whereDate('audit_date_by_aud', '>=', $request->start_date)->whereDate('audit_date_by_aud', '<=', $request->end_date)->orderBy('id','desc')->get();
            }
            
            
            $oldSco=OldScore::where('branch_id',$request->branch)->where('type',0)->first();
            
            $proWiseSco=OldScore::where('branch_id',$request->branch)->where('type',1)->get();
            
            
            $has_data=1;

            $auditCycle=AuditCycle::orderBy('id','desc')->limit(3)->get()->toArray();
            
           

            $getAcr=$this->getAcrReportData($getBranch->id,$request->start_date);
            
            

            return view('audit.report_automate_data',compact('data','oldSco','proWiseSco','auditCycle','branchList','getBranch','has_data','start_date','end_date','brID','collectionManagerID','agencyRepoID','branchRepoID','agencyID','yardID','getAcr'));
        }      
        
    }

    public function reportAutomationDataColl(Request $request) {

        $ids=Qc::with('user')->get()->keyBy('audit_id');
        $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();
        //echo "<pre>"; print_r($ids); die;
        $getBranch=Branch::with('branchable','yard','branchRepo','agencyRepo','agency','branchable.user')->find($request->branch);

        $yardID=array();
        $agencyID=array();
        $branchRepoID=array();
        $agencyRepoID=array();
        $collectionManagerID=array();

        if($getBranch) {
            foreach ($getBranch->yard as $key => $value) {
               $yardID[]=$value->id;
            }
            foreach ($getBranch->agency as $key => $value) {
               $agencyID[]=$value->id;
            }
            foreach ($getBranch->branchRepo as $key => $value) {
               $branchRepoID[]=$value->id;
            }
            foreach ($getBranch->agencyRepo as $key => $value) {
               $agencyRepoID[]=$value->id;
            }
            foreach ($getBranch->branchable as $key => $value) {
                if(trim($value->type) == "Collection_Manager" && $value->status == 2) {
                    $collectionManagerID[]=$value->manager_id;
                }                   
            }
        }
        $brID=$request->branch;
        if($ids->count()>0){
            $data = Audit::with(['qmsheet','qmsheet.qm_sheet_sub_parameter','productnew','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state','audit_parameter_result','audit_results','qc','qc.user','collectionManagerData','audit_results.parameter_detail','audit_results.sub_parameter_detail'])
            ->whereIn('id',$ids->pluck('audit_id'))->where('parent_branch_id',$brID)->whereIn('collection_manager_id',$collectionManagerID)->whereNotIn('id',$savedQcIds)->whereDate('audit_date_by_aud', '>=', $request->start_date)->whereDate('audit_date_by_aud', '<=', $request->end_date)->get();
        }
        else{
            $data = Audit::with(['qmsheet','qmsheet.qm_sheet_sub_parameter','productnew','yard.branch.city.state','agency.branch.city.state','qa_qtl_detail','branchRepo.branch.city.state','agencyRepo.branch.city.state','audit_parameter_result','audit_results','qc','qc.user','collectionManagerData','audit_results.parameter_detail','audit_results.sub_parameter_detail'])
              ->whereNotIn('id',$savedQcIds)->whereIn('collection_manager_id',$collectionManagerID)->where('parent_branch_id',$brID)->whereDate('audit_date_by_aud', '>=', $request->start_date)->whereDate('audit_date_by_aud', '<=', $request->end_date)->get();
        }
        
        
        $calculation = DB::select(DB::raw("Select * from 6040_calculation"));
        
        
        
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $selecollMan=($request->cmid) ? $request->cmid : $collectionManagerID[0];
        $has_data=1;

        
        return view('audit.report_automate_collec',compact('data','calculation','getBranch','has_data','start_date','end_date','collectionManagerID','selecollMan'));          
        
    }

    public function reportAutomationDatagap(Request $request) {

        $ids=Qc::with('user')->get()->keyBy('audit_id');
        $savedQcIds=SavedQcAudit::all()->pluck('audit_id')->toArray();
        //echo "<pre>"; print_r($ids); die;
        $getBranch=Branch::with('branchable','yard','branchRepo','agencyRepo','agency','branchable.user')->find($request->branch);

        $yardID=array();
        $agencyID=array();
        $branchRepoID=array();
        $agencyRepoID=array();
        $collectionManagerID=array();

        if($getBranch) {
            foreach ($getBranch->yard as $key => $value) {
               $yardID[]=$value->id;
            }
            foreach ($getBranch->agency as $key => $value) {
               $agencyID[]=$value->id;
            }
            foreach ($getBranch->branchRepo as $key => $value) {
               $branchRepoID[]=$value->id;
            }
            foreach ($getBranch->agencyRepo as $key => $value) {
               $agencyRepoID[]=$value->id;
            }
            foreach ($getBranch->branchable as $key => $value) {
                if(trim($value->type) == "Collection_Manager" && $value->status == 2) {
                    $collectionManagerID[]=$value->manager_id;
                }                   
            }
        }
        
        $brID=$request->branch;
        
        if($ids->count()>0){
            $data = Audit::whereIn('id',$ids->pluck('audit_id'))->whereIn('agency_id',$agencyID)->where('parent_branch_id',$brID)->whereNotIn('id',$savedQcIds)->whereDate('audit_date_by_aud', '>=', $request->start_date)->whereDate('audit_date_by_aud', '<=', $request->end_date)->get();
        }
        else{
            $data = Audit::whereNotIn('id',$savedQcIds)->whereIn('agency_id',$agencyID)->where('parent_branch_id',$brID)->whereDate('audit_date_by_aud', '>=', $request->start_date)->whereDate('audit_date_by_aud', '<=', $request->end_date)->get();
        }
        
        
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $selecollMan=($request->cmid) ? $request->cmid : 'all';
        $has_data=1;

        $getAgency=Agency::find($selecollMan);
        $auditCycle=AuditCycle::orderBy('id','desc')->limit(3)->get()->toArray();
        
        $depositionData=CashDepositionData::with('agency','branch')->whereDate('date','>=',$start_date)->whereDate('date','<=',$end_date)->get();
        $receiptData=ReceiptCutData::with('agency','branch')->whereDate('date','>=',$start_date)->whereDate('date','<=',$end_date)->get();
        $secData=DelaySeconAllocData::with('agency','branch')->whereDate('date','>=',$start_date)->whereDate('date','<=',$end_date)->get();
          
        return view('audit.report_automate_gap',compact('data','getBranch','auditCycle','has_data','start_date','end_date','agencyID','selecollMan','getAgency','depositionData','receiptData','secData'));
          
        
    }

    public function reportDataUploader(Request $request) {

        if($request->hasFile('acr_report')) {
            $data = Excel::import(new AcrImport([
                 'uploaded_by' => Auth::User()->id                   
            ]), $request->acr_report);
        }

        if($request->hasFile('dac_uploader')) {
            $data = Excel::import(new CashDespositionImport([
                   'uploaded_by' => Auth::User()->id                   
            ]), $request->dac_uploader);
        }
        
        if($request->hasFile('score_upload')) {
            $data = Excel::import(new OldscoreImport([
                   'uploaded_by' => Auth::User()->id                   
            ]), $request->score_upload);
        }

        
        return redirect()->route('reportAutomation')->withStatus(__('Data Uploaded Successfully.'));
    }

    function getMonths($quarter,$year){
        switch($quarter) {
            case 1: return array('Jan_'.$year, 'Feb_'.$year, 'Mar_'.$year);
            case 2: return array('Apr_'.$year, 'May_'.$year, 'Jun_'.$year);
            case 3: return array('Jul_'.$year, 'Aug_'.$year, 'Sep_'.$year);
            case 4: return array('Oct_'.$year, 'Nov_'.$year, 'Dec_'.$year);
        }
    }

    function getAcrReportData($branch_id,$start_date) 
    {
		
		$final_data=array();
		
		$pre_mon=array();
		
        $pre_mon[0]=date('Y-M', strtotime("-1 month", strtotime(date("Y-m-d",strtotime($start_date)))));
        $pre_mon[1]=date('Y-M', strtotime("-2 month", strtotime(date("Y-m-d",strtotime($start_date)))));
        $pre_mon[2]=date('Y-M', strtotime("-3 month", strtotime(date("Y-m-d",strtotime($start_date)))));
        

        $get=AcrReportData::with('agency','product')->where('branch_id',$branch_id)->whereIn('month',$pre_mon)->get();
        
        

        $agency_arr_id=array();
        $product_arr_id=array();
        foreach($get as $g){
            if(!in_array($g->agency_id, $agency_arr_id)) {
                $agency_arr_id[]=$g->agency_id;         
            }
			if(!in_array($g->product_group, $product_arr_id)) {
                $product_arr_id[]=$g->product_group;         
            }
        }
		$allocation_capacity=array();
		foreach($agency_arr_id as $a) {
			$data=array();
			$data['agency_code']=$a;
			$data['agency_name']="";
			$data['fos_count'][0]=0;
			$data['fos_count'][1]=0;
			$data['fos_count'][2]=0;
			$data['alloc_count'][0]=0;
			$data['alloc_count'][1]=0;
			$data['alloc_count'][2]=0;			
			$data['capacity']=0;
			$data['avg_alloc_count']=0;
			$data['avg_fos_count']=0;
			$data['gap']=0;
			
			$uniqFos[0]=array();
			$uniqFos[1]=array();
			$uniqFos[2]=array();
			$is_flow=0;
			$recovery=array();
			$pro=array();
			foreach($get as $g){				
				if($g->agency_id == $a) {
					
					$data['agency_name']=$g->agency->name;
					if($g->month == $pre_mon[0]) {
						if(!in_array($g->agent_id,$uniqFos[0])) {
							$data['fos_count'][0]+=1;
							$uniqFos[0][]=$g->agent_id;
						}
						$data['alloc_count'][0]+=1;						
					}
					if($g->month == $pre_mon[1]) {
						if(!in_array($g->agent_id,$uniqFos[1])) {
							$data['fos_count'][1]+=1;
							$uniqFos[1][]=$g->agent_id;
						}
						$data['alloc_count'][1]+=1;
					}
					if($g->month == $pre_mon[2]) {
						if(!in_array($g->agent_id,$uniqFos[2])) {
							$data['fos_count'][2]+=1;
							$uniqFos[2][]=$g->agent_id;
						}
						$data['alloc_count'][2]+=1;
					}
					
					if($g->product) {
						if(!in_array($g->product->id,$pro)) {
							if($g->product->is_recovery == 0) {
								$is_flow+=1;
							} else {
								$recovery[]=$g->product->capacity;
							}	
						}																	
					}
				}
			}
			
			$flow_capacity=$is_flow*80;
			$data['capacity']=$flow_capacity*array_sum($data['fos_count']);
			if(count($recovery) > 0) {
				foreach($recovery as $r) {
					$data['capacity']+=(array_sum($data['fos_count'])*$r);
				}
			}			
			$data['avg_fos_count']=(array_sum($data['fos_count']) != 0) ? round(array_sum($data['fos_count'])/3) : 0;
			$data['avg_alloc_count']=(array_sum($data['alloc_count']) != 0) ? round(array_sum($data['alloc_count'])/3) : 0;
			$data['gap']=$data['avg_alloc_count']-$data['capacity'];
			$allocation_capacity[]=$data;
		}
        $pro_detail=array();
		foreach($product_arr_id as $p) {
			$data=array();
			$data['pro_name']=$p;
			$pro_cap=0;
			$uni_agen[0]=array();
			$uni_agen[1]=array();
			$uni_agen[2]=array();
			$data['main'][0]=0;
			$data['main'][1]=0;
			$data['main'][2]=0;
			$data['crossed'][0]=0;
			$data['crossed'][1]=0;
			$data['crossed'][2]=0;
			
			foreach($get as $g) {
				if($g->product_group == $p) {
					$pro_cap=($g->product) ? $g->product->capacity : 0;
					if($g->month == $pre_mon[0]) {
						if(!array_key_exists($g->agent_id,$uni_agen[0])) {
							$uni_agen[0][$g->agent_id]=0;
						}					
						if(array_key_exists($g->agent_id,$uni_agen[0])) {
							$uni_agen[0][$g->agent_id]+=1;
						}
					}
					if($g->month == $pre_mon[1]) {
						if(!array_key_exists($g->agent_id,$uni_agen[1])) {
							$uni_agen[1][$g->agent_id]=0;
						}					
						if(array_key_exists($g->agent_id,$uni_agen[1])) {
							$uni_agen[1][$g->agent_id]+=1;
						}
					}
					if($g->month == $pre_mon[2]) {
						if(!array_key_exists($g->agent_id,$uni_agen[2])) {
							$uni_agen[2][$g->agent_id]=0;
						}					
						if(array_key_exists($g->agent_id,$uni_agen[2])) {
							$uni_agen[2][$g->agent_id]+=1;
						}
					}
				}				
			}
			
			
			foreach($uni_agen[2] as $a) {
				if($a == $pro_cap) {
					$data['main'][2]+=1;
				} else {
				    if($a > $pro_cap) {
				        $data['crossed'][2]+=1;
				    }
				}
			}
			
			foreach($uni_agen[0] as $a) {
				if($a == $pro_cap) {
					$data['main'][0]+=1;
				} else {
				    if($a > $pro_cap) {
				        $data['crossed'][0]+=1;
				    }
				}
			}
			
			foreach($uni_agen[1] as $a) {
				if($a == $pro_cap) {
					$data['main'][1]+=1;
				} else {
				    if($a > $pro_cap) {
				        $data['crossed'][1]+=1;
				    }
				}
			}
			
			$pro_detail[]=$data;			
		}
		
		$final_data['pre_mon']=$pre_mon;
		$final_data['allocation_capacity']=$allocation_capacity;
		$final_data['pro_detail']=$pro_detail;
		
		return $final_data;
	}
    
    public function createCycle(Request $request) {
        if($request->isMethod('post')) {
            if($request->cycle_name) {
                $data=array();
                $data['created_by']=Auth::user()->id;
                $data['name']=$request->cycle_name;
                $has=AuditCycle::where('name',$data['name'])->first();
                if(!$has){
                    AuditCycle::create($data);
                    return redirect('list-audit-cycle')->withStatus(__('Cycle created successfully.'));
                } else {
                    return redirect('list-audit-cycle')->withStatus(__('Cycle already available.'));
                }
            } else {
                return redirect('list-audit-cycle')->withStatus(__('Cycle name not available.'));
            }
            
        }
        return view('audit.create_audit_cycle');
    }
    
    public function listCycle(Request $request) {
        $data=AuditCycle::orderBy("id","desc")->get();
        return view('audit.audit_cycle_list',compact('data'));
    }
    
    public function duplicate_audit($audit_id) {

      $source_auditid = Crypt::decrypt($audit_id);
      $source_audit = Audit::find($source_auditid);
      $newAudit = $source_audit->replicate();
      $newAudit->save();

      $source_auditResult = AuditResult::where("audit_id", $source_auditid)
              ->get();
      foreach ($source_auditResult as $val) {
         $auditResult = AuditResult::find($val->id);
         $newAuditResult = $auditResult->replicate();
         $newAuditResult->audit_id = $newAudit->id;
         $newAuditResult->save();
      }

      $sourcePar = AuditParameterResult::where("audit_id", $source_auditid)
              ->get();
      foreach ($sourcePar as $val) {
         $auditpar = AuditParameterResult::find($val->id);
         $newAuditPar = $auditpar->replicate();
         $newAuditPar->audit_id = $newAudit->id;
         $newAuditPar->save();
      }

      $sourceArt = Artifact::where("audit_id", $source_auditid)
              ->get();
      foreach ($sourceArt as $val) {
         $sourceArti = Artifact::find($val->id);
         $newAuditArt = $sourceArti->replicate();
         $newAuditArt->audit_id = $newAudit->id;
         $newAuditArt->save();
      }
      
      SavedAudit::create(['audit_id'=>$newAudit->id,'status'=>1]);

      return redirect('audit_sheet/{'.Crypt::encrypt($newAudit->id).'}/edit');
    }

}

