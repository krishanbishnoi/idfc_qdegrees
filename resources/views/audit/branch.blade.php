
@if($type=='yard')

    <div class="col-md-3 form-group">

        <label>Yard name</label>

        <input type="text" name="agency_name" class="form-control" value="{{$yard->name ?? ''}}" disabled>

    </div>

    <div class="col-md-3 form-group">

        <label>Yard Manager</label>

        <input list="yard_manager" type="text" name="yard_manager" class="form-control" value="{{$yard->agency_manager ?? ''}}">

        <!-- <input type="hidden" name="yard_manager_email" value="">

        <span id="yard_error" style="display:none">Not found</span>

        <datalist id="yard_manager">

        </datalist> -->

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

        <input list="agency_manager" type="text" name="agency_manager" class="form-control" value="{{$agency->user->name ?? ''}}" onkeyup="changeAgencyManager(this.value)" onchange="selectAgencyManager(this.value)">

        <input type="hidden" name="agency_manager_email" value="">

        <span id="agency_error" style="display:none">Not found</span>

        <datalist id="agency_manager">

        </datalist>

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
        elseif($item->type=='Area_Collection_Manager'){

            $myData[$item->type][]=$item;

        }elseif($item->type=='Regional_Collection_Manager'){



            $myData[$item->type][]=$item;



        }
        elseif($item->type=='Zonal_Collection_Manager'){



            $myData[$item->type][]=$item;



        }
         elseif($item->type=='National_Collection_Manager'){



            $myData[$item->type][]=$item;



        }
        else{

            $myData[$item->type]=$item;

        }  

    }

    $myType=array_keys($myData);

@endphp


<?php //echo "<pre>"; print_r($myType); ?>

@foreach($myType as $item)

    @if($item=='Collection_Manager')
    <div class="col-md-3 form-group">

        <label>{{str_replace('_',' ',$item)}}</label>

        <select class="form-control" name="{{$item}}" id="collection_manager-select">

            @foreach($myData[$item] as $value)

            {{-- <option data-code="{{$value->user->employee_id}}" data-bucket="{{$value->bucket ?? ''}}" data-acm = "{{$value->acm_id ?? ''}}" value="{{$value->user->id }}">{{$value->user->name ?? ''}}</option> --}}
                <?php 
                    $ncmname=($value->ncm_id) ? $value->ncm->name : '';
                    $rcmname=($value->rcm_id) ? $value->rcm->name : '';
                    $acmname=($value->acm_id) ? $value->acm->name : '';
                    $zcmname=($value->zcm_id) ? $value->zcm->name : '';
                    $ghname=($value->gph_id) ? $value->ghead->name : '';
                
                ?>
                @isset($value->user->employee_id)
                <option data-code="{{$value->user->employee_id}}" data-ncmname = "{{$ncmname}}" data-rcmname = "{{$rcmname}}" data-zcmname = "{{$zcmname}}" data-ghname = "{{$ghname}}" data-bucket="{{$value->bucket ?? ''}}" data-acm = "{{$value->acm_id ?? ''}}" data-zcm = "{{$value->zcm_id ?? ''}}" data-rcm = "{{$value->rcm_id ?? ''}}" data-ncm = "{{$value->ncm_id ?? ''}}"  data-nph = "{{$value->nph_id ?? ''}}" value="{{$value->user->id }}">{{$value->user->name ?? ''}}</option>

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

    @elseif($item=='Area_Collection_Manager')
    <div class="col-md-3 form-group">

        <label>{{str_replace('_',' ',$item)}}</label>
        <select class="form-control" name="{{$item}}" id="area_collection_manager-select" disabled="true">

            @foreach($myData[$item] as $value)

            <option  value="{{$value->user->id }}">{{$value->user->name ?? ''}}</option>

                

            @endforeach

        </select>
        

    </div>
     @elseif($item=='Regional_Collection_Manager')
    <div class="col-md-3 form-group" id="forsingle_rcm">

        <label>{{str_replace('_',' ',$item)}}</label>

        <input list="adventure" type="text" name="{{$item}}" id="regional_collection_manager-select" class="form-control" value="{{$myData[$item][0]->user->name ?? ''}}" disabled>

    </div>

  @elseif($item=='Zonal_Collection_Manager')
    <div class="col-md-3 form-group" id="forsingle_rcm">

        <label>{{str_replace('_',' ',$item)}}</label>

        <input list="adventure" type="text" name="{{$item}}" id="zonal_collection_manager-select" class="form-control" value="{{$myData[$item][0]->user->name ?? ''}}" disabled>

    </div>
     @elseif($item=='National_Collection_Manager')
    <div class="col-md-3 form-group" id="forsingle_rcm">

        <label>{{str_replace('_',' ',$item)}}</label>

        <input list="adventure" type="text" name="{{$item}}" id="national_collection_manager-select" class="form-control" value="{{$myData[$item][0]->user->name ?? ''}}" disabled>

    </div>

    @else
    <div class="col-md-3 form-group">

        <label>{{str_replace('_',' ',$item)}}</label>

        <input list="adventure" type="text" name="{{$item}}" class="form-control" value="{{$myData[$item]->user->name ?? ''}}" disabled>

    </div>

    @endif

@endforeach

<div class="col-md-3 form-group">

    <label>Geo tag</label>

    <textarea type="text" name="geotag" id="demogeo" class="form-control" value="" disabled></textarea>

</div>

<script>

/*function getSub(val) {
    $('input[name=Collection_Manager_bucket]').val('');
    $('input[name=Collection_Managercode]').val('');
    $('input[name=Area_Collection_Manager]').val('');
    $('#regional_collection_manager-select').val('');
    $('#national_collection_manager-select').val('');
    $('#zonal_collection_manager-select').val('');
    $('input[name=Group_Product_Head]').val('');
    
    $('#collection_manager-select option[value='+val+']').attr('selected','selected');
    var code = $('#collection_manager-select').find('option:selected').data('code');
     //alert(code);
     
    var bucket = $('#collection_manager-select').find('option:selected').data('bucket');
    var acmid = $('#collection_manager-select').find('option:selected').data('acm');
    $('#area_collection_manager-select option[value='+acmid+']').removeAttr("selected");
    //added by abhilasha
    var zcmid = $('#collection_manager-select').find('option:selected').data('zcm');
    var ncmid = $('#collection_manager-select').find('option:selected').data('ncm');
    
    var ncmname=$('#collection_manager-select').find('option:selected').data('ncmname');
    var zcmname=$('#collection_manager-select').find('option:selected').data('zcmname');
    var ghname=$('#collection_manager-select').find('option:selected').data('ghname');
    var rcmname=$('#collection_manager-select').find('option:selected').data('rcmname');
    
    var rcmid = $('#collection_manager-select').find('option:selected').data('rcm');
    if(bucket != '')
    $('input[name=Collection_Manager_bucket]').val(bucket);
    if(code != '')
    $('input[name=Collection_Managercode]').val(code);
    if(acmid != ''){
    $('input[name=Area_Collection_Manager]').val(acmid);
    $('#area_collection_manager-select option[value='+acmid+']').attr('selected','selected');
    }
    if(rcmid != ''){

    $('#regional_collection_manager-select').val(rcmname);

    }
    if(ncmid != ''){

    $('#national_collection_manager-select').val(ncmname);

    }
    if(zcmid != ''){

    $('#zonal_collection_manager-select').val(zcmname);

    }
    
    if(ghname != ''){

    $('input[name=Group_Product_Head]').val(ghname);

    }
}*/

//Commented due to version issue
jQuery('#collection_manager-select').on('change',function(e){
    //jQuery('#collection_manager-select').find("option").removeAttr("selected");
    //jQuery('#area_collection_manager-select').find("option").removeAttr("selected");
    jQuery('input[name=Collection_Manager_bucket]').val('');
    jQuery('input[name=Collection_Managercode]').val('');
    jQuery('input[name=Area_Collection_Manager]').val('');
    jQuery('#regional_collection_manager-select').val('');
    jQuery('#national_collection_manager-select').val('');
    jQuery('#zonal_collection_manager-select').val('');
    jQuery('input[name=Group_Product_Head]').val('');
    
    var optval = this.value;
    jQuery('#collection_manager-select option[value='+optval+']').attr('selected','selected');
    //console.log(code,bucket);
    var code = jQuery(this).find('option:selected').data('code');
    var bucket = jQuery(this).find('option:selected').data('bucket');
    var acmid = jQuery(this).find('option:selected').data('acm');

    //added by kratika jain
    var zcmid = jQuery(this).find('option:selected').data('zcm');
    var ncmid = jQuery(this).find('option:selected').data('ncm');
    var rcmid = jQuery(this).find('option:selected').data('rcm');
    
    var ncmname=jQuery(this).find('option:selected').data('ncmname');
    var zcmname=jQuery(this).find('option:selected').data('zcmname');
    var ghname=jQuery(this).find('option:selected').data('ghname');
    var rcmname=jQuery(this).find('option:selected').data('rcmname');
    if(bucket != '')
    jQuery('input[name=Collection_Manager_bucket]').val(bucket);
    if(code != '')
    jQuery('input[name=Collection_Managercode]').val(code);
    if(acmid != ''){
    jQuery('input[name=Area_Collection_Manager]').val(acmid);
    jQuery('#area_collection_manager-select option[value='+acmid+']').attr('selected','selected');
    }
    if(rcmid != ''){

    jQuery('#regional_collection_manager-select').val(rcmname);

    }
    if(ncmid != ''){

    jQuery('#national_collection_manager-select').val(ncmname);

    }
    if(zcmid != ''){

    jQuery('#zonal_collection_manager-select').val(zcmname);

    }
    
    if(ghname != ''){

    jQuery('input[name=Group_Product_Head]').val(ghname);

    }

});

</script>