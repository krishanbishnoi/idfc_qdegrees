@extends('layouts.master')

@section('title', '| Yard')

@section('sh-detail')
    Edit New
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <strong>Edit Yard</strong> form
        </div>
        <div class="card-body card-block">
            
        {!! Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'yard/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Name</label>
                        <input type="text" id="text-input" name="name" placeholder="Yard Name" class="form-control" value="{{$data->name}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Branch Name</label>
                        <select name="branch_name" id="branchName" data-placeholder="Choose a Branch..." class="standardSelect form-control" tabindex="1">
                            <option value="" label="Branch Name"></option>
                            @foreach($branch as $k=>$item)
                            <option value="{{$item->id}}" {{($data->branch_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class=" form-group">
                        <label for="text-input" class=" form-control-label">Agency Name</label>
                        <select name="agency_name"  id="agency_name" data-placeholder="Choose a Agency..." class="standardSelect form-control" tabindex="1">
                            {{-- <option value="" label="Agency Name"></option>
                            @foreach($agency as $k=>$item)
                            <option value="{{$item->id}}" {{($data->agency_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                            @endforeach --}}
                            <!-- <option value="1" {{($data->agency_id==1)?'selected':''}}>Agency</option> -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class=" form-group">
                        <label for="yard_id" class=" form-control-label">Yard id</label>
                        <input type="text" id="yard_id" name="yard_id" placeholder="Yard id" class="form-control" value="{{$data->yard_id}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class=" form-group">
                        <label for="agency_manager" class=" form-control-label">Agency Manger</label>
                        <!-- <input type="text" id="agency_manager" name="agency_manager" placeholder="Agency Manger" class="form-control" value="{{$data->agency_manager}}"> -->
                        <select name="agency_manager" id="agency_manager" data-placeholder="Choose a Agency..." class="standardSelect form-control" tabindex="3">
                            <!-- <option value="" label="Agency Manger"></option>
                            @foreach($user as $k=>$item)
                                <option value="{{$item->id}}" {{($data->agency_id==$item->id)?'selected':''}}>{$item->name}}</option>
                            @endforeach -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class=" form-group">
                        <label for="location" class=" form-control-label">Location</label>
                        <input type="text" id="location" name="location" placeholder="Location" class="form-control" value="{{$data->location}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class=" form-group">
                        <label for="address" class=" form-control-label">Address</label>
                        <input type="text" id="address" name="address" placeholder="Address" class="form-control" value="{{$data->addresss}}">
                    </div>
                </div>
               
        </div>
            
<div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Submit
                </button>
                <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> Reset
                </button>
            </div>
            </form>
        </div>

    </div>
</div>
</div>

@endsection
@section('js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>

jQuery('#branchName').on('change',function(e){
            var branchId=e.target.value
            var selectAgency= "{{ $data->agency_id }}"
            var agency='<option value="" label="Agency Name"></option>'
            var agencymanager='<option value="" label="Agency Manger"></option>'
            var condition=false;
            if (branchId) {
                jQuery.ajax({
                    type: "get",
                    url: " {{url('/getAgency')}}/" + branchId,
                    success: function (res) {
                        if (res) {
                            console.log(res)
                           jQuery.each(res, function (key, value) {
                              if(selectAgency==value.id){
                                  agency=agency+'<option selected value="'+value.id+'">'+value.name+'</option>'
                                  condition=true         
                              }
                              else{
                                agency=agency+'<option value="'+value.id+'">'+value.name+'</option>'

                              }
                            });
                            jQuery('#agency_name').html(agency)
                            jQuery('#agency_manager').html(agencymanager)
                            if(condition){
                                jQuery('#agency_name').trigger('change');
                            }
                        }
                    }

                });
            }
        })
        jQuery('#agency_name').on('change',function(e){
            var branchId=e.target.value
            var selectAgency= "{{$data->agency_manager}}"
            var agency='<option value="" label="Agency Manger"></option>'
            if (branchId) {
                jQuery.ajax({
                    type: "get",
                    url: " {{url('/getAgencyManager')}}/" + branchId,
                    success: function (res) {
                        if (res) {
                            console.log(res)
                           jQuery.each(res, function (key, value) {
                               if(selectAgency==value.id){
                                agency=agency+'<option selected value="'+value.id+'">'+value.name+'</option>'
                               }
                               else{
                                   agency=agency+'<option value="'+value.id+'">'+value.name+'</option>'
                               }
                            });
                            jQuery('#agency_manager').html(agency)
                        }
                    }

                });
            }
        })
        jQuery(document).ready(function() {
            jQuery('#agency_name').html('<option value="" label="Agency Name"></option>')
            jQuery('#agency_manager').html('<option value="" label="Agency Manger"></option>')
            jQuery('#branchName').trigger('change');
            
            // jQuery(".sizes").select2();
        // jQuery(".standardSelect").chosen({
        //     disable_search_threshold: 10,
        //     no_results_text: "Oops, nothing found!",
        //     width: "100%"
        // });
    })

    </script>
@endsection