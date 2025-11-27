@extends('layouts.master')

@section('title', '| Users')

@section('sh-detail')
    Create New
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>Create Branch</strong> 
        </div>
        <div class="card-body card-block">
            {!! Form::open(
                     array(
                       'route' => 'branch.store',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'data-toggle'=>"validator")
                     ) !!}

            <div class="row">
            
            <div class="col col-md-4">
                <div class="form-group">
                    <label>Lob Name*</label>
                    <select name="lob" class="form-control">
                        <option value="">Choose Lob Name</option>
                        <option value="collection">Collection</option>
                        <option value="commercial_vehicle">Commercial Vehicle</option>
                        <option value="rural">Rural</option>
                        <option value="alliance">Alliance</option>
                        <option value="credit_card">Credit Card</option>
                    </select>
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">Regions
                            </label>
                    <select class="form-control" name="region_id" id="country">
                        <option value="">Choose Region</option>
                        @foreach ($regions as $country)
                            <option value="{{$country->id}}">
                                {{$country->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">State
                            </label>
                        <select class="form-control" name="state" id="state">
                        </select>

                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">City
                            </label>

                        <select class="form-control" name="city_id" id="city">
                        </select>

                </div>
            </div>

            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Branch Name</label>
                    <input type="text" id="text-input" name="name" placeholder="Branch Name"
                                                        class="form-control" value="{{old('name')}}">
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Location</label>
                    <input type="text" id="text-input" name="location" placeholder="Location"
                                                        class="form-control" value="{{old('name')}}">
                    
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Branch Id</label>
                    <input type="text" id="text-input" name="uuid" placeholder="Branch Id"
                                                        class="form-control" readonly value="{{getBranchUuid()}}">
                    
                </div>
            </div>

            
            <!-- branch manager working -->
            {{-- <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">Branch Manager
                            </label>
                    <select class="form-control" name="manager_id" id="users">
                            <option value="">Choose Branch Manager</option>
                            @foreach ($users as $user)
                            @if($user->hasRole(str_replace('_',' ','Collection_Manager')))
                                <option value="{{$user->id}}">
                                    {{$user->name}}
                                </option>
                            @endif
                        @endforeach
                        </select>

                </div>
            </div> --}}
                {{-- <div class="col col-md-4">
                    <div class="form-group">
                        <label for="multiple-select" class=" form-control-label">Branch Manager Type
                                </label>
                            <select class="form-control" name="owner_id" id="owner_id">
                                <option selected>--Choose Branch Manager Type--</option>
                                <option value="1">Collection Manager</option>
                                <option value="2">Asst. Collection Manager</option>
                                <option value="3">Regional Collection Manager</option>
                                <option value="4">Zonal Collection Manager</option>
                                <option value="5">NCM/GPM</option>
                            </select>
                    </div>
                </div> --}}
                {{-- <div class="col col-md-4">
                    <div class="form-group">
                        <label for="multiple-select" class=" form-control-label">Bucket Level
                                </label>
                                <input type="hidden" name="owner_id" value="1">
                                <input type="text" name="bucket_level" class="form-control">
                    </div>
                </div> --}}
                @php
                $roles=['Collection_Manager','Area_Collection_Manager','Regional_Collection_Manager','Zonal_Collection_Manager','National_Collection_Manager','Group_Product_Head'];
            @endphp
            @foreach($roles as $item)
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="multiple-select" class=" form-control-label">{{str_replace('_',' ',$item)}}
                            </label>
                    <select class="form-control" name="{{$item}}" id="users">
                            <option value="">Choose {{str_replace('_',' ',$item)}}</option>
                            @foreach ($users as $user)
                                @if($user->hasRole(str_replace('_',' ',$item)))
                                <option value="{{$user->id}}">
                                    {{$user->name}}
                                </option>
                                @endif
                            @endforeach
                    </select>
                </div>
            </div>
            @endforeach
                <div class="col col-md-3">
                    <div class="form-group">
                        <label for="multiple-select" class=" form-control-label">Product
                                </label>
                            <select class="form-control" name="product_id" id="owner_id">
                                <option selected>--Choose Product--</option>
                               @foreach($product as $data)
                               <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                <div class="col col-md-1">
                <label></label>
                <div class="fa fa-plus text-right" id="add"></div>
                </div>
                
            </div>
            <div  id="shower">
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


@endsection
@section('js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>

            
                
            
var users='';
                var idx=1
                @foreach ($users as $user)
                        @if($user->hasRole(str_replace('_',' ','Collection_Manager')))
                            users=users+`<option value="{{$user->id}}">
                                {{$user->name}}
                            </option>`
                        @endif
                    @endforeach
                var pro=''
                @foreach($product as $data)
                       pro=pro+ `<option value="{{$data->id}}">{{$data->name}}</option>`
                @endforeach

                
                var finalData=''
                @foreach($roles as $item)
                var conUser=''
                    @foreach ($users as $user)
                        @if($user->hasRole(str_replace('_',' ',$item)))
                            conUser=conUser+`<option value="{{$user->id}}">
                                {{$user->name}}
                            </option>`
                        @endif
                    @endforeach
                finalData=finalData+ `<div class="col col-md-4">
                        <div class="form-group">
                            <label for="multiple-select" class=" form-control-label">{{str_replace('_',' ',$item)}}
                                    </label>
                            <select class="form-control" name="{{$item}}${idx}" id="users">
                                    <option value="">Choose {{str_replace('_',' ',$item)}}</option>
                                 ${conUser}       
                            </select>
                        </div>
                    </div>`
             @endforeach
        jQuery('#add').on('click',function(e){
            // var manager=`<div class="col col-md-4">
            //     <div class="form-group">
            //         <label for="multiple-select" class=" form-control-label">Branch Manager
            //                 </label>
            //         <select class="form-control" name="manager_id${idx}" id="users">
            //                 <option value="">Choose Branch Manager</option>
            //                 ${users}          
            //             </select>

            //     </div>
            // </div>`;
            var manager='';
            manager=manager+finalData;
            manager=manager+`    <div class="col col-md-3">
                    <div class="form-group">
                        <label for="multiple-select" class=" form-control-label">Product
                                </label>
                            <select class="form-control" name="product_id${idx}" id="owner_id">
                                <option selected>--Choose Product--</option>
                                ${pro}
                            </select>
                    </div>
                </div>`
                manager=manager+`<div class="col col-md-1 text-center fa fa-trash" id="delete" data-id="${idx}"></div>`   
            jQuery('#shower').append(`<div class="row" id="custom${idx}">${manager}</div>`)
            idx=idx+1;
        })
        jQuery(document).on('click','#delete',function(e){
            var id=jQuery(this).data('id');
            // alert(id);
            jQuery('#custom'+id).remove();
        })
        jQuery('#country').change(function () {
            var cid = jQuery(this).val();
            if (cid) {
                jQuery.ajax({
                    type: "get",
                    url: " {{url('/getStates')}}/" + cid,
                    success: function (res) {
                        if (res) {
                            jQuery("#state").empty();
                            jQuery("#city").empty();
                            jQuery("#state").append('<option>Select State</option>');
                            jQuery.each(res, function (key, value) {
                                jQuery("#state").append('<option value="' + key + '">' + value + '</option>');
                            });
                        }
                    }

                });
            }
        });
        jQuery('#state').change(function () {
            var sid = jQuery(this).val();
            if (sid) {
                jQuery.ajax({
                    type: "get",
                    url: "{{url('/getCities')}}/" + sid,
                    success: function (res) {
                        if (res) {
                            jQuery("#city").empty();
                            jQuery("#city").append('<option>Select City</option>');
                            jQuery.each(res, function (key, value) {
                                jQuery("#city").append('<option value="' + key + '">' + value + '</option>');
                            });
                        }
                    }

                });
            }
        });
    </script>
@endsection