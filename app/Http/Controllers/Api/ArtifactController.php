<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\User;
use App\Artifact;
use App\TempArtifact;
use Storage;
use URL;

class ArtifactController extends Controller
{
   	public function storeArtifact(Request $request)
    {
         //echo $_POST['sheet_id'];
        //die; 
    	if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
        $getUser=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$getUser) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
        $validator = Validator::make($request->all(), [
            'sheet_id' => 'required',
            'parameter_id' => 'required',
            'sub_parameter_id' => 'required',
            'totalFile'=>'required|max:10000',
            'status'=>'required'  // if status 1 = getting audit id  , 0 = temp audit id
        ]);
        if($validator->fails()) {
         
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        }
        if($request->status == 1)
        {
            $data=[];
            if($request->file('totalFile')){
                 //dd('yess');

                 
                //$path1 = $request->file('totalFile')->store('public'); 

                $image=$request->file('totalFile');
                $imageName = $request->audit_id.'_'.$request->sub_parameter_id.'_'.time().'_'.$image->getClientOriginalName();  
                $image->move(public_path('artifects'), $imageName); 
                $path1 = $imageName;

                 $artifact=Artifact::create(['sheet_id'=>$request->sheet_id,'parameter_id'=>$request->parameter_id,'sub_parameter_id'=>$request->sub_parameter_id,'file'=>$path1,'audit_id'=>isset($request->audit_id)?$request->audit_id:null]);
                 $artifact->file=$path1;
                 $data[]=$artifact;
                }

        }else{
            $data=[];
            if($request->file('totalFile')){
                 //dd('yess');
                 //$path1 = $request->file('totalFile')->store('public'); 

                 $image=$request->file('totalFile');
                $imageName = $request->temp_audit_id.'_'.$request->sub_parameter_id.'_'.time().'_'.$image->getClientOriginalName();  
                $image->move(public_path('artifects'), $imageName); 
                $path1 = $imageName;

                 $artifact=TempArtifact::create(['sheet_id'=>$request->sheet_id,'parameter_id'=>$request->parameter_id,'sub_parameter_id'=>$request->sub_parameter_id,'file'=>$path1,'temp_audit_id'=>isset($request->temp_audit_id)?$request->temp_audit_id:null]);
                 $artifact->file=$path1;
                 $data[]=$artifact;
                }

        }

        
       
        if(count($data)>0){
        	$response=array('status'=>1,'message'=>'Artifact Saved',
                                'data' => $data);                     
                            
        	return response(json_encode($response), 200);
            // return response()->json(['status'=>true,'msg'=>'artifact save','data'=>$data]);
        }
        else{
        	$response=array('status'=>0,'message'=>'Artifact Not Saved',
                                'data' => $data); 
        	return response(json_encode($response), 200);
            // return response()->json(['status'=>false,'msg'=>'artifact not save','data'=>$data]);
        }

    }

    public function transfer_artifact_from_temp_to_main(Request $request)
    {
        //  echo"hello";
        //  die; 
    	if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
        $getUser=User::select('id','auth_key')->where('auth_key',$request->header('Authorization'))->first();
        if(!$getUser) {
            $data=array('status'=>0,'message'=>'User not found','data' => array());
           return response(json_encode($data), 200); 
        }
        $validator = Validator::make($request->all(), [
            'temp_audit_id'=>'required',
            'audit_id'=>'required',
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        }else{
        $data=[];
        $data = TempArtifact::all()
        ->where('temp_audit_id',($request->temp_audit_id))->all();
        foreach ($data as $key => $value) {
        $movedata = new Artifact;
        $movedata->sheet_id = $value->sheet_id;
        $movedata->parameter_id = $value->parameter_id;
        $movedata->sub_parameter_id = $value->sub_parameter_id;
        $movedata->file = $value->file;
        $movedata->audit_id = $request->audit_id;
        $movedata->save();
        }
        // $data = TempArtifact::delete(where('temp_audit_id',($request->temp_audit_id)));
        foreach ($data as $value) {
                 $value->delete();
                }
        return response()->json(['status'=>200,'message'=>"Artifact moved to main artifact successfully" , 'data'=>$movedata], 200);
        
        }


    }

    public function artifact_audit_update(Request $request)
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
        $validator = Validator::make($request->all(), [
            'artifact_id' => 'required',
            'audit_id' => 'required'
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        }
        $data=[];

               
            if($request->file('totalFile')){
                /* echo "hiii";
                die; */
                $path1 = $request->artifact_id; 

                
           
            }
        // dd($data);
        if(count($data)>0){
        	$response=array('status'=>1,'message'=>'Artifact Saved',
                                'data' => $data); 
        	return response(json_encode($response), 200);
            // return response()->json(['status'=>true,'msg'=>'artifact save','data'=>$data]);
        }
        else{
        	$response=array('status'=>0,'message'=>'Artifact Not Saved',
                                'data' => $data); 
        	return response(json_encode($response), 200);
            // return response()->json(['status'=>false,'msg'=>'artifact not save','data'=>$data]);
        }

    }
    public function artifacts_list(Request $request){
        if(!$request->header('Authorization') || $request->header('Authorization') == "") {
           $data=array('status'=>0,'message'=>'Authorization key is required in api headers.','data' => array());
           return response(json_encode($data), 200); 
        }
        if(!empty($request->sheet_id) && !empty($request->parameter_id) && !empty($request->sub_parameter_id)){
           $getArtifacts=Artifact::leftjoin('qm_sheets','artifacts.sheet_id','qm_sheets.id')->leftjoin('qm_sheet_parameters','artifacts.parameter_id','qm_sheet_parameters.id')->leftjoin('qm_sheet_sub_parameters','artifacts.sub_parameter_id','qm_sheet_sub_parameters.id')->where([['artifacts.sheet_id', '=', $request->sheet_id],['artifacts.parameter_id', '=', $request->parameter_id],['artifacts.sub_parameter_id','=',$request->sub_parameter_id]])->select('artifacts.file')->get();

          // $getArtifacts=Artifact::where([['sheet_id', '=', $request->sheet_id],['parameter_id', '=', $request->parameter_id],['sub_parameter_id','=',$request->sub_parameter_id]])->select('file')->get(); 
           if(!empty($getArtifacts)){
            foreach ($getArtifacts as $key => $getArtifacts_values) {
                $p=URL::to('/');
                //echo $p.'/storage/app/'.$getArtifacts_values->file;die;
               $files[]=$p.'/storage/app/'.$getArtifacts_values->file;
            }
            $response=array('status'=>1,'message'=>'Artifact List',
                                'data' => $files); 
            return response(json_encode($response), 200);
           }else{
            $data=array('status'=>0,'message'=>'List not found','data' => array());
            return response(json_encode($data), 200);

           }

        }else{
         $data=array('status'=>0,'message'=>'SheetId,ParameterId or SubParameterId can not be empty','data' => array());
          return response(json_encode($data), 200); 

        }

    }
}
