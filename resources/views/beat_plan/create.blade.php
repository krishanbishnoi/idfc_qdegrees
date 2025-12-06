@extends('layouts.master')

@section('sh-title')
Audit Alert Box
@endsection



@section('sh-detail')
Create New
@endsection

@section('content')
<div class="card" id="kt_repeater_1">
    <div class="card-header">
        <strong>Beat Plan</strong>
    </div>
    <div class="card-body card-block">
      
        {!! Form::open(
                  array(
                    'route' => 'beat_plan.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
          <!--begin::Form-->
        
        <div class="row">
          <div class="col-md-3 form-group">
            <label>Name*</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter Beat plan name" required>
          </div>
           
          </div> 
          <div id="kt_repeater_1">
            <div class="form-group  row" id="kt_repeater_1">
              <div data-repeater-list="subs" class="col-lg-12">
                <div data-repeater-item class="form-group align-items-center">
                  <div class="row">
                  
                    <div class="col-md-3 form-group">
                      <label>From Date*</label>
                      <input type="text" id="date" name="date" class="form-control datepicker" placeholder="Choose From dates" autocomplete="off" required>
                    </div>

                    <div class="col-md-3 form-group">
                      <label>To Date*</label>
                      <input type="text" id="to_date" name="to_date" class="form-control datepicker" placeholder="Choose To dates" autocomplete="off" required>
                    </div>

                <div class="col-md-3 form-group">
                    <label>Branch*</label>
                    <select name="branch_id" id="branchName" data-placeholder="Choose a Branch..." class="standardSelect form-control branchName" tabindex="2" required>
                      <option value="" label="Branch Name"></option>
                      @foreach($branch as $k=>$item)
                      <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 form-group">
                  <label>Branch Repo</label>
                  <input type="number" id="branch_repo" name="branch_repo" class="form-control branch_repo" placeholder="Enter Total Branch Repo" >
                </div>

                <div class="col-md-3 form-group">
                  <label>Agencies</label>
                  <input type="number" id="agencies" name="agencies" class="form-control agencies" placeholder="Enter Total Agencies" >
                </div>

                <div class="col-md-3 form-group">
                  <label>Agency Repo</label>
                  <input type="number" id="agency_repo" name="agency_repo" class="form-control agency_repo" placeholder="Enter Total Agency Repo" >
                </div>

                <div class="col-md-3 form-group">
                  <label>Yard</label>
                  <input type="number" id="yard" name="yard" class="form-control yard" placeholder="Enter Total Yard" >
                </div>

                <div class="col-md-3 form-group">
                  <label>Yard Repo</label>
                  <input type="number" id="yard_repo" name="yard_repo" class="form-control yard_repo" placeholder="Enter Total Yard Repo" >
                </div>

                <div class="col-md-3 form-group">
                  <label>Products</label>
                  <select class="form-control product" name="product" id="product" multiple required>
                      <option value=''>--Choose Product--</option>
                      @foreach($product as $data)
                      <option value="{{$data->id}}">{{$data->name}}</option>
                      @endforeach
                  </select>
                </div>

                <div class="col-md-3 form-group">
                  <label>Collection Managers*</label>
                  <input type="number" id="collection_manager" name="collection_manager" class="form-control collection_manager" placeholder="Enter Total CM" required>
                  
                    
                </div>




                <div class="col-md-3 form-group">
                  <label>Description</label>
                    <textarea class="form-control"  id="" name="description"></textarea>
                </div>
                <div class="col-md-3">
                  <div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill">
                    <span>
                      <i class="la la-trash-o"></i>
                      <span>Delete</span>
                    </span>
                  </div>
                </div>
                  </div>

                  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-4">
                <div data-repeater-create="" class="btn btn-primary btn-sm">
                  <span>
                    <i class="fa fa-plus"></i>
                    <span>Add</span>
                  </span>
                </div>
              </div>
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

      <!--end::Form-->
    </div>

    <!--end::Portlet-->

    

  </div>
</div>



@endsection

@section('js')



<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script> --}}


<script>
  jQuery(document).ready(function() {
    jQuery('.datepicker').datepicker({
      format: "yyyy-mm-dd",
      minDate: new Date()
    });

    
  });
  
  jQuery('body').on('focus',".datepicker", function(){
    jQuery(this).datepicker({
      format: "yyyy-mm-dd",
      minDate: new Date()
    });
  })
      


  $('.branchName').on('click change', function(e) {
  if(e.type==='change' || this.id!=='branchName') {
      var sel = this.value;
      $("#branchName option[value='"+ sel + "']").attr("selected", "selected");
    //alert(this.value);
  }
  });

  </script>
    @include('shared.form_js')
@endsection