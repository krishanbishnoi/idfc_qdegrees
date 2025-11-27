@extends('layouts.master')



@section('title', '| Yard')



@section('sh-detail')

    Create New

@endsection



@section('content')

<div class="row">

    <div class="col-lg-12">

    <div class="card">

        <div class="card-header">

            <strong>Create Yard</strong> form

        </div>

        <div class="card-body card-block">

            {!! Form::open(

                     array(

                       'route' => 'yard.store',

                       'class' => 'form-horizontal',

                       'role'=>'form',

                       'data-toggle'=>"validator")

                     ) !!}

            <div class="row">

                <div class="col-md-4">

                    <div class=" form-group">

                        <label for="text-input" class=" form-control-label">Name</label>

                        <input type="text" id="text-input" name="name" placeholder="Yard Name" class="form-control" value="{{old('name')}}" tabindex="1">

                    </div>

                </div>

                <div class="col-md-4">

                    <div class=" form-group">

                        <label for="text-input" class=" form-control-label">Branch Name</label>

                        <select name="branch_name" id="branchName" data-placeholder="Choose a Branch..." class="standardSelect form-control" tabindex="2">

                            <option value="" label="Branch Name"></option>

                            @foreach($branch as $k=>$item)

                            <option value="{{$item->id}}">{{$item->name}}</option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class=" form-group">

                        <label for="text-input" class=" form-control-label">Agency Name</label>

                        <select name="agency_name" id="agency_name" data-placeholder="Choose a Agency..." class="standardSelect form-control" tabindex="3">

                            

                        </select>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class=" form-group">

                        <label for="agency_manager" class=" form-control-label">Agency Manger</label>

                        <!-- <input type="text" id="agency_manager" name="agency_manager" placeholder="Agency Manger" class="form-control" value="{{old('agency_manager')}}" tabindex="5"> -->

                        

                        <select name="agency_manager" id="agency_manager" data-placeholder="Choose a Agency..." class="standardSelect form-control" tabindex="3">

                            

                           {{-- @foreach($user as $k=>$item)

                            <option value="{{$item->id}}">{{$item->name}}</option>

                            @endforeach --}}

                        </select>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class=" form-group">

                        <label for="yard_id" class=" form-control-label">Yard id</label>

                        <input type="text" id="yard_id" name="yard_id" placeholder="Yard id" class="form-control" value="{{old('yard_id')}}" tabindex="4">

                    </div>

                </div>

                <div class="col-md-4">

                    <div class=" form-group">

                        <label for="location" class=" form-control-label">Location</label>

                        <input type="text" id="location" name="location" placeholder="Location" class="form-control" value="{{old('location')}}" tabindex="6">

                    </div>

                </div>

                <div class="col-md-4">

                    <div class=" form-group">

                        <label for="address" class=" form-control-label">Address</label>

                        <input type="text" id="address" name="address" placeholder="Address" class="form-control" value="{{old('address')}}" tabindex="7">

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

            var agency='<option value="" label="Agency Name"></option>'

            var agencymanager='<option value="" label="Agency Manger"></option>'

            if (branchId) {

                jQuery.ajax({

                    type: "get",

                    url: " {{url('/getAgency')}}/" + branchId,

                    success: function (res) {

                        if (res) {

                            console.log(res)

                           jQuery.each(res, function (key, value) {

                                // jQuery("#state").append('<option value="' + key + '">' + value + '</option>');

                                agency=agency+'<option value="'+value.id+'">'+value.name+'</option>'

                            });

                            jQuery('#agency_name').html(agency)

                            jQuery('#agency_manager').html(agencymanager)

                        }

                    }



                });

            }

        })

        jQuery('#agency_name').on('change',function(e){

            var branchId=e.target.value

            var agency='<option value="" label="Agency Manger"></option>'

            if (branchId) {

                jQuery.ajax({

                    type: "get",

                    url: " {{url('/getAgencyManager')}}/" + branchId,

                    success: function (res) {

                        if (res) {

                            console.log(res)

                           jQuery.each(res, function (key, value) {

                                // jQuery("#state").append('<option value="' + key + '">' + value + '</option>');

                                agency=agency+'<option value="'+value.id+'">'+value.name+'</option>'

                            });

                            jQuery('#agency_manager').html(agency)

                        }

                    }



                });

            }

        })

        jQuery(document).ready(function() {

            // jQuery(".sizes").select2();

            jQuery('#agency_name').html('<option value="" label="Agency Name"></option>')

            jQuery('#agency_manager').html('<option value="" label="Agency Manger"></option>')

        // jQuery(".standardSelect").chosen({

        //     disable_search_threshold: 10,

        //     no_results_text: "Oops, nothing found!",

        //     width: "100%"

        // });

    })

    </script>

@endsection