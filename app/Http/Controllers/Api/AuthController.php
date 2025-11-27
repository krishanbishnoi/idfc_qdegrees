<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\User;



class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $getUser=User::select('id','name','email','password','auth_key')->where(['email'=>$request->email])->first();
            if($getUser) {
                if(!Hash::check(trim($request->password),$getUser->password)) {
                    $data=array('status'=>0,'message'=>'Password not matched','data'=> array());
                } else { 
                    /*if($getUser->user_type != 5 && $getUser->user_type != 6 && $getUser->user_type != 7) {
                        $data=array('status'=>2,'message'=>'You are not authorised to access RetailQ Auditor App.','data'=> array());
                    }*/
                     /* if($getUser->is_email_verified == 0) {
                        $data=array('status'=>2,'message'=>'Please verify your email first.','data'=> array());
                     }  */
                     /* else { */
                         $getUser->email=$request->email;
                         $getUser->auth_key=Hash::make($request->email);
                         $getUser->save();
                         $finalData=array('auth_key'=>$getUser->auth_key);
                         $url=asset('images/profile_pic');  
                         if($getUser->user_detail) {
                             $data=array('status'=>1,'name'=>$getUser->user_detail->full_name,'mobile_no'=>$getUser->mobile_no,'auth_key'=> $getUser->auth_key,'user_id'=>$getUser->id,'profile_pic'=>$getUser->user_detail->profile_pic,'img_url'=>$url."/".$getUser->user_detail->profile_pic,'form_submit_level'=>$getUser->form_submit_level,
                             'overall_status'=>$getUser->status,'message'=>'Login Successfully');
                         } else {
                            $data=array('status'=>1,'name'=>$getUser->name,'email'=>$getUser->email,'auth_key'=>$getUser->auth_key, 'user_id'=>$getUser->id,'message'=>'Login Successfully');
                         } 


                     /* }     */                
                }  
            } else {
                $data=array('status'=>0,'message'=>"You don't have an account.Please signup.",'data'=> array());
            }                       
            return response(json_encode($data),200);
        }        
    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'new_password' => 'required|string',
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $getUser=User::select('id','name','email','password','auth_key')->where(['email'=>$request->email])->first();
            if($getUser) {
                if(!Hash::check(trim($request->password),$getUser->password)) {
                    $data=array('status'=>0,'message'=>'Password not matched','data'=> array());
                } else {
                    $update_user=User::find($getUser->id);
                    $update_user->password = Hash::make(trim($request->new_password));
                    $update_user->save();

                    $data=array('status'=>1,'name'=>$getUser->name,'email'=>$getUser->email,'auth_key'=>$getUser->auth_key, 'user_id'=>$getUser->id,'message'=>'Password updated Successfully');
                }
            } else {
                $data=array('status'=>0,'message'=>"You don't have an account.Please signup.",'data'=> array());
            }                       
            return response(json_encode($data),200);
        }
    }
    
}
