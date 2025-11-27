@if($type=='yard')
    <div class="col-md-3 form-group">
        <label>Yard name</label>
        <input type="text" name="agency_name" class="form-control" value="{{$yard->name ?? ''}}" disabled>
    </div>
    <div class="col-md-3 form-group">
        <label>Yard Manager</label>
        <input type="text" name="yard_manager" class="form-control" value="{{$yard->user->name ?? ''}}" data-id="{{$yard->user->id ?? ''}}" disabled>
        <input type="hidden" name="yard_manager_email" value="">
    </div>
    <div class="col-md-3 form-group yard_error" style="display:none">
        <label>Other</label>
        <span id="yard_error" class="form-controll" style="display:none"></span>
    </div>
    <div class="col-md-3 form-group">
        <label>Yard Address</label>
        <input type="text" name="agency_address" class="form-control" value="{{$yard->addresss ?? ''}}" disabled>
    </div>
@endif
@if($type=='agency')
    <div class="col-md-3 form-group">
        <label>Agency name</label>
        <input type="text" name="agency_name" class="form-control" value="{{$agency->name ?? ''}}" disabled>
    </div>
    {{-- <div class="col-md-3 form-group">
        <label>Agency Manager</label>
        <input  type="text" name="agency_manager" class="form-control" value="{{$agency->user->name ?? ''}}" data-id="{{$agency->user->id ?? ''}}" disabled>
        <input type="hidden" name="agency_manager_email" value="">
        <span id="agency_error" style="display:none"></span>
    </div> --}}
    {{-- <div class="col-md-3 form-group agency_error" style="display:none">
        <label>Other</label>
        <span id="agency_error" class="" style="display:none"></span>
    </div> --}}
    <div class="col-md-3 form-group">
        <label>Agency Manager</label>
        <input type="text" name="agency_manager" class="form-control" value="{{$agency->agency_manager ?? ''}}">
    </div>
    <div class="col-md-3 form-group">
        <label>Agency Phone</label>
        <input type="text" name="agency_phone" class="form-control" value="{{$agency->agency_phone ?? ''}}">
    </div>
    <div class="col-md-3 form-group">
        <label>Agency Address</label>
        <input type="text" name="agency_address" class="form-control" value="{{$agency->addresss ?? ''}}" disabled>
    </div>
@endif
@if($type=='agency_repo')
    <div class="col-md-3 form-group">
        <label>Agency repo name</label>
        <input type="text" name="agency_repo_name" class="form-control" value="{{$AgencyRepo->name ?? ''}}" disabled>
    </div>
    <div class="col-md-3 form-group">
        <label>Agency repo Address</label>
        <input type="text" name="agency_repo_address" class="form-control" value="{{$AgencyRepo->location ?? ''}}" disabled>
    </div>
@endif
@if($type=='branch_repo')
    <div class="col-md-3 form-group">
        <label>Branch repo name</label>
        <input type="text" name="branch_repo_name" class="form-control" value="{{$BranchRepo->name ?? ''}}" disabled>
    </div>
    <div class="col-md-3 form-group">
        <label>Branch repo Address</label>
        <input type="text" name="branch_repo_address" class="form-control" value="{{$BranchRepo->location ?? ''}}" disabled>
    </div>
@endif
<div class="col-md-3 form-group">
    <label>Branch name</label>
    <input type="text" name="branch" class="form-control" value="{{$branchable->name ?? ''}}" disabled>
</div>
<div class="col-md-3 form-group">
    <label>City</label>
    <input type="text" name="city" class="form-control" value="{{$branchable->city->name ?? ''}}" disabled>
</div>
<div class="col-md-3 form-group">
    <label>location</label>
    <input type="text" name="location" class="form-control" value="{{$branchable->location ?? ''}}" disabled>
</div>
@php
    $myData=[];
    $myType=[];
    $code='';
    $bucket='';
    $i=0;
    foreach($branchable->branchable as $item){ 
        if($item->type=='Collection_Manager'){
            $myData[$item->type][]=$item;
        }
        else{
            $myData[$item->type]=$item;
        }  
    }
    $myType=array_keys($myData);
function getSortOrder($c) {
    $sortOrder = ['Collection_Manager','Area_Collection_Manager','Regional_Collection_Manager','Zonal_Collection_Manager','National_Collection_Manager','Group_Product_Head'];
    $pos = array_search($c, $sortOrder);
    return $pos !== false ? $pos : 99999;
}

function mysort($a, $b) {
    if( getSortOrder($a) < getSortOrder($b) ) {
        return -1;
    }elseif( getSortOrder($a) == getSortOrder($b) ) {
        return 0;
    }else {
        return 1;
    }
}
usort($myType, "mysort");
@endphp

@foreach($myType as $item)
    @if($item=='Collection_Manager')
    <div class="col-md-3 form-group">
        <label>{{str_replace('_',' ',$item)}}</label>

        <select class="form-control" name="{{$item}}" id="collection_manager-select" disabled>
            @foreach($myData[$item] as $value)
                @isset($value->user->employee_id)
                    
                
             <?php
                 if(!empty($userData)) {
                    
                    if($userData['id'] == $value->user->id){?>
                        <option data-code="{{$value->user->employee_id}}" data-bucket="{{$value->buckrt ?? ''}}" value="{{$value->user->id }}" selected>{{$value->user->name ?? ''}}</option>
                   <?php }
                   else{
                    ?>
                         <option data-code="{{$value->user->employee_id}}" data-bucket="{{$value->buckrt ?? ''}}" value="{{$value->user->id }}" >{{$value->user->name ?? ''}}</option>
                    <?php 
                   }
                 }else{?>
                       <option data-code="{{$value->user->employee_id}}" data-bucket="{{$value->buckrt ?? ''}}" value="{{$value->user->id }}">{{$value->user->name ?? ''}}</option>
                    <?php
                 }?>

                
               
                @php
                    if(count($myData[$item])==1){
                        $code=$value->user->employee_id ?? '';
                        $bucket=$value->bucket ?? '';
                    }
                @endphp

                @endisset
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group">
        <label>{{str_replace('_',' ',$item)}} Emp Code</label>
        <input type="text" name="{{$item}}code" class="form-control" value="{{$code ?? ''}}" disabled>
    </div>
    <div class="col-md-3 form-group">
        <label>{{str_replace('_',' ',$item)}} bucket</label>
        <input type="text" name="{{$item}}_bucket" class="form-control" value="{{$bucket ?? ''}}" disabled>
    </div>
    @else
    <div class="col-md-3 form-group">
        <label>{{str_replace('_',' ',$item)}}</label>
        <input list="adventure" type="text" name="{{$item}}" class="form-control" value="{{$myData[$item]->user->name ?? ''}}" disabled>
    </div>
    @endif
@endforeach



