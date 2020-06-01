@extends('layouts.master')
@section('css')
{{-- <link rel="stylesheet" href="{{URL::asset('public/base/style.bundle.css')}}"> --}}
<style>
.sp-row .row {
    margin-bottom: 15px;
}


.sp-row .row {
    margin-bottom: 15px;
}

.flex-container {
    display: flex;
    align-items: center;
}

.flex-container {
    display: flex;
    align-items: center;
}
.kt-font-bolder {
    font-weight: 600 !important;
}
#seprator {
    margin: 2.5rem 0 0 0;
}
#seprator {
    margin: 2.5rem 0 0 0;
}
.kt-separator.kt-separator--space-lg {
    margin: 2.5rem 0;
}
.kt-separator.kt-separator--border-dashed {
    border-bottom: 1px dashed #ebedf2;
}
.kt-separator {
    height: 0;
    margin: 20px 0;
    border-bottom: 1px solid #ebedf2;
}
.kt-font-primary {
    color: #5867dd !important;
}

.kt-font-bolder {
    font-weight: 600 !important;
}
.kt-font-bold {
    font-weight: 500 !important;
}
.centerparameter{
	display: flex;
    justify-content: center;
    align-items: center
}
</style>
@endsection
@section('title')
Audit 
@endsection

@section('sh-detail')
Messages
@endsection

@section('content')

<div class="row">
	<div class="col-lg-12" style="margin-top:10x">
	</div>
</div>
<div class="animated fadeIn">
	<div class="row">
		<div class="col-lg-12">
			<div class="card"> 
				<div class="card-header" style="background-image: linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));color:#fff">
					<strong class="card-title">{{($data->lob=='commercial_vehicle')?'Commercial Vehicle':ucfirst($data->lob)}} | {{ucfirst(str_replace('_',' ',$data->type))}}</strong>
				</div>
				<div class="card-body">
					<div class="row">
						@if($data->type=='branch')
							<div class="col-md-3 form-group">
								<label>Branch*</label>
								<select name="branch" class="form-control branch">
								<option value="">Choose Branch</option>
								@foreach ($branch as $item)  
									<option value="{{$item->id}}" {{($item->id==$result->branch_id)?'selected':''}}>{{$item->name}}</option>
								@endforeach
								</select>
							</div>
						@elseif($data->type=='agency')
							<div class="col-md-3 form-group">
								<label>Agency*</label>
								<select name="agency" class="form-control agency">
								<option value="">Choose Agency</option>
								@foreach ($agency as $item)  
									<option value="{{$item->id}}" {{($item->id==$result->agency_id)?'selected':''}}>{{$item->name}}</option>
								@endforeach
								</select>
							</div>
						@elseif($data->type=='repo_yard')
							<div class="col-md-3 form-group">
								<label>Yard*</label>
								<select name="yard" class="form-control yard">
								<option value="">Choose Yard</option>
								@foreach ($yard as $item)  
									<option value="{{$item->id}}" {{($item->id==$result->yard_id)?'selected':''}}>{{$item->name}}</option>
								@endforeach
								</select>
							</div>
						@endif
						<div class="col-md-3 form-group" id="product" style="display:none;">
							<label>Product*</label>
							<select name="product" class="form-control product" id="productSelect">
							<option value="">Choose Product</option>
							</select>
						</div>
					</div>
					<div class="row" id="data">
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<strong class="card-title">Audit</strong>
				</div>
				<div class="card-body">
					
					<div class="row">
						<div class="col-md-2 kt-font-bolder">
							Parameter
						</div>
						<div class="col-md-10 kt-font-bolder">
							<div class="row">
								<div class="col-md-2 kt-font-bolder">Sub Parameter</div> 
								<div class="col-md-2 kt-font-bolder">Observation</div> 
								<div class="col-md-2 kt-font-bolder">Scored</div> 
								<div class="col-md-2 kt-font-bolder">Remarks</div>
								<div class="col-md-2 kt-font-bolder">Action</div>
							</div>
						</div>
					</div>
					<div id="seprator" class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
					@php
						$total=0;
					@endphp
					@foreach ($data->parameter as $item)
					<div class="row flex-container" style="border-bottom: 1px solid rgb(204, 204, 204); padding: 20px 0px; height: 100%;">
						<div class="col-md-2 kt-font-bolder kt-font-primary flex-item centerparameter">
							{{$item->parameter}}
						</div>
						<div class="col-md-10 sp-row">
							@foreach ($item->qm_sheet_sub_parameter as $value)
							<div class="row flex-container mb-2">
								<div class="col-md-2 kt-font-bold">
									{{$value->sub_parameter}} <i title="sdfdf" class="la la-info-circle kt-font-warning sp-details-top"></i>
								</div>
								<div class="col-md-2">
								<select class="form-control 0bervation" id="obs{{$value->id}}" data-id="{{$value->id}}" data-parameterId="{{$item->id}}" data-point="{{$value->weight}}">
										<option value="">Choose type</option>
										@if($value->pass==1)<option value="{{$value->weight}}" {{($resultSubPar[$value->id]->selected_option==$value->weight)?'selected':''}}>Pass</option>@endif
										@if($value->fail==1)<option value="0"  {{($resultSubPar[$value->id]->is_critical==0 && $resultSubPar[$value->id]->selected_option==0)?'selected':''}}>Fail</option>@endif
										@if($value->critical==1)<option value="Critical"  {{($resultSubPar[$value->id]->is_critical==1)?'selected':''}}>Critical</option>@endif
										@if($value->na==1)<option value="N/A"  {{($resultSubPar[$value->id]->is_critical==0 && $resultSubPar[$value->id]->selected_option=='N/A')?'selected':''}}>N/A</option>@endif
										@if($value->pwd==1)<option value="{{round(($value->weight)/2,2)}}"  {{($resultSubPar[$value->id]->selected_option==round(($value->weight)/2,2))?'selected':''}}>PWD</option>@endif
									</select>
									<span style="display:none" id="org{{$value->id}}">{{$value->weight}}</span>
								</div>
								<div class="col-md-2">
								<input type="text" id="{{$value->id}}" readonly="readonly" class="form-control" value="{{ ($resultSubPar[$value->id]->is_critical==1)?'Critical':$resultSubPar[$value->id]->score}}">
								</div>
								<div class="col-md-2">
								<textarea class="form-control" id="remark{{$value->id}}" value="{{ $resultSubPar[$value->id]->remark}}">{{ $resultSubPar[$value->id]->remark}}</textarea>
								</div>
								<div class="col-md-2">
									<button class="btn btn-danger btn-sm alertModal" data-parameterid="{{$item->id}}" data-id="{{$value->id}}">Alert</button>
									<button class="btn btn-info btn-sm artifactModal mr-1" data-parameterid="{{$item->id}}" data-id="{{$value->id}}">Artifact</button>
								</div>
							</div>
							<div id="seprator" class="kt-separator kt-separator--border-dashed "></div>
							@php
								$total=$total+$value->weight;
							@endphp
							@endforeach
						<span style="display:none" id="total{{$item->id}}">{{$total}}</span>	
						</div>
					</div>
					@endforeach
					{{-- // result --}}
					<div>
						
				</div>
				{{-- <div class="card-footer">
					<button type="submit" class="btn btn-primary btn-sm">
						<i class="fa fa-dot-circle-o"></i> Submit
					</button>
					<button type="reset" class="btn btn-danger btn-sm">
						<i class="fa fa-ban"></i> Reset
					</button>
				</div> --}}
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<strong class="card-title">Result</strong>
			</div>
			<div class="card-body">
				
		<div class="row" style="border-bottom: 1px solid rgb(204, 204, 204);">
			<div class="col-lg-4 kt-font-bolder">&nbsp;</div>
			<div class="col-lg-4 kt-font-bolder">Scored</div>
			<div class="col-lg-4 kt-font-bolder">Scores%</div>
		</div>
		<div class="row" style="padding: 15px 0px;">
			<div class="col-lg-2 kt-font-bolder">Parameter</div>
			<div class="col-lg-2 kt-font-bolder">Scorable</div>
			<div class="col-lg-2 kt-font-bolder">With FATAL</div>
			<div class="col-lg-2 kt-font-bolder">Without FATAL</div>
			<div class="col-lg-2 kt-font-bolder">With FATAL</div>
			<div class="col-lg-2 kt-font-bolder">Without FATAL</div>
		</div>
		
		@foreach ($data->parameter as $item)
			<div class="row" style="border-bottom: 1px solid rgb(204, 204, 204); padding: 20px 0px; height: 100%;">
				<div class="col-lg-2 kt-font-bold kt-font-primary">{{$item->parameter}}</div>
			<div class="col-lg-2" id="scroable{{$item->id}}">0</div>
				<div class="col-lg-2 kt-font-danger" id="wfatal{{$item->id}}">0</div>
				<div class="col-lg-2" id="wnfatal{{$item->id}}">0</div>
				<div class="col-lg-2 kt-font-danger" id="wfatalper{{$item->id}}">0 %</div>
				<div class="col-lg-2" id="wnfatalper{{$item->id}}">0 %</div>
			</div>
		@endforeach

		<div class="row" style="padding: 20px 0px; height: 100%;">
			<div class="col-lg-2 kt-font-bold kt-font-success">Over All</div>
			<div class="col-lg-2 kt-font-bold" id="scroable">0</div>
			<div class="col-lg-2 kt-font-bold kt-font-danger" id="wfatal">0</div>
			<div class="col-lg-2 kt-font-bold" id="wnfatal">0</div>
			<div class="col-lg-2 kt-font-bold kt-font-danger" id="wfatalper">0%</div>
			<div class="col-lg-2 kt-font-bold"  id="wnfatalper">0%</div>
		</div>
	</div>
</div>
	</div>

	</div>
	<button type="submit" class="btn btn-primary btn-sm submit">
		<i class="fa fa-dot-circle-o"></i> Submit
	</button>
	<button type="reset" class="btn btn-danger btn-sm">
		<i class="fa fa-ban"></i> Reset
	</button>
</div>

{{-- modal code --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12 form-group">
					<label>files</label>
					<input type="file" id="file" name="file" class="form-control-file">
				</div>
				<div class="col-md-12 form-group">
					<label>Messages*</label>
					<input type="hidden" name="alertParameterId" id="alertParameterId" value=""/>
					<input type="hidden" name="alertSubParameterId" id="alertSubParameterId" value=""/>
					<textarea name="msg" id="msg" class="form-control" placeholder="Enter message" required></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  <button type="button" class="btn btn-primary" id="saveAlert">Save changes</button>
		</div>
	  </div>
	</div>
  </div>
  <div class="modal fade" id="artifactModal" tabindex="-1" role="dialog" aria-labelledby="artifactModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="artifactModalLabel">Artifact</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12 form-group">
					<label>files</label>
					<input type="hidden" name="artifactParameterId" id="artifactParameterId" value=""/>
					<input type="hidden" name="artifactSubParameterId" id="artifactSubParameterId" value=""/>
					<input type="file" id="file" name="artifactfile" class="form-control-file">
				</div>
			</div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  <button type="button" class="btn btn-primary" id="artifactAlert">Save changes</button>
		</div>
	  </div>
	</div>
  </div>
@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
<script>
	var result={};
	var par={};
	var subpar={};
	@php
	foreach($data->parameter as $item)
	{
		@endphp
		result[{{$item->id}}]={};
		@php
	}
	
	foreach($resultSubPar as $k=>$v){
		$subValue=($v->is_critical==1)?"Critical":$v->score;
		@endphp	
		subpar[{{$k}}]={{$v->id}}
		resultFun('{{$subValue}}', {{$k}},{{$v->parameter_id}})
	@php
	}
	foreach($resultPar as $k=>$v){
	@endphp
		par[{{$k}}]={{$v->id}}
	@php
	}
	@endphp
	function sum( obj ) {
		var sum = 0;
		for( var el in obj ) {
			if( obj.hasOwnProperty( el ) ) {
				sum += parseFloat( obj[el] );
			}
		}
		return sum;
	}
	function totalfun( obj ){
		var total=0;
		for( var el in obj ) {
			if( obj.hasOwnProperty( el ) ) {
				var subtotal=0
				for( var item in obj[el] ) {
				if( obj[el].hasOwnProperty( item ) ) {
					if(obj[el][item]!='Critical'){
						subtotal=parseInt(subtotal)+parseInt((obj[el][item]=='N/A'?0:obj[el][item]));
					}
					else{
						subtotal=0;
						break;
					}
				}
			}
			total=parseInt(subtotal)+parseInt(total);
				// total +=sum(obj[el])
			}
		}
		jQuery('#scroable').text(total)
		jQuery('#wfatal').text(total)
		jQuery('#wnfatal').text(total)
		var wfatalper=(total!=0)?(total/total)*100:0;
		var wnfatalper=(total!=0)?(total/total)*100:0;
		jQuery('#wfatalper').text(wfatalper+'%')
		jQuery('#wnfatalper').text(wnfatalper+'%')
	}
	var total=0;
	function resultFun(value, id,parameterId){
		console.log(value, id,parameterId)
		result[parameterId][id]=value;
		var total=0;
			for( var el in result[parameterId] ) {
				if( result[parameterId].hasOwnProperty( el ) ) {
					if(result[parameterId][el]!='Critical'){
						total=parseInt(total)+parseInt((result[parameterId][el]=='N/A'?0:result[parameterId][el]));
					}
					else{
						total=0;
						break;
					}
				}
			}
			console.log(total)
		jQuery('#scroable'+parameterId).text(total)
		jQuery('#wfatal'+parameterId).text(total)
		jQuery('#wnfatal'+parameterId).text(total)
		var wfatalper=(total!=0)?(total/total)*100:0;
		var wnfatalper=(total!=0)?(total/total)*100:0;
		jQuery('#wfatalper'+parameterId).text(wfatalper+'%')
		jQuery('#wnfatalper'+parameterId).text(wnfatalper+'%')
		totalfun(result)
		
	}
	jQuery('.0bervation').on('change',function(e){
		var id =jQuery(this).data('id');
		var parameterId =jQuery(this).data('parameterid');
		jQuery('#'+id).val(e.target.value)
		resultFun(e.target.value, id,parameterId)
	})
	jQuery(".submit").on("click",function(e){
		var submitData=[];
		var parameters={}
		var sub={}
		
		for( var el in result ) {
			if( result.hasOwnProperty( el ) ) {
				for( var row in result[el] ) {
					if( result[el].hasOwnProperty( row ) ) {
						sub[row]={
							'id':subpar[row],
							'remark':jQuery('#remark'+row).val(),
						'orignal_weight':jQuery('#org'+row).text(),
						'temp_weight':result[el][row],
						'score':jQuery('#'+row).val(),
						}
					}
				}
				parameters[el]={
				'id':par[el],
				'score':jQuery('#scroable'+el).text(),
				'score_with_fatal':jQuery('#wfatal'+el).text(),
				'score_without_fatal':jQuery('#wnfatal'+el).text(),
				'temp_total_weightage':jQuery('#scroable').text(),
				'parameter_weight':jQuery('#'+el).text(),
				'subs':sub
			}
			}
		}
		submitData.push({
			'id':{{$result->id}},
			'qm_sheet_id':{{$data->id}},
			'overall_score':jQuery('#scroable').text(),
			'with_fatal_score_per':jQuery('#wfatalper').text(),
			'branch_id':jQuery('.branch').val(),
			'agency_id':jQuery('.agency').val(),
			'yard_id':jQuery('.yard').val(),
			'product_id':jQuery('.product').val()
			
		})
		var saveData = jQuery.ajax({
			type: 'POST',
			url: "{{url('allocation/update_audit')}}",
			data: {'submission_data':submitData,'parameters':parameters,
			"_token":"{{ csrf_token() }}"
			},
			dataType: "text",
			success: function(resultData) { 

				// window.location='{{ url("audited_list")}}'
			}
		});
		saveData.error(function() { alert("Something went wrong"); });
		console.log(submitData)
		
	})
	jQuery(document).on('ready',function(e){
		var type="{{$data->type}}"
		if(type=='branch'){
			gerProduct('{{$result->branch_id}}','branch')
			editBranch('{{$result->branch_id}}','{{$result->product_id}}','branch')
		}
		else if(type=='agency'){
			gerProduct('{{$result->agency_id}}','agency')
			editBranch('{{$result->agency_id}}','{{$result->product_id}}','agency')
		}
		else{
			gerProduct('{{$result->yard_id}}','yard')
			editBranch('{{$result->yard_id}}','{{$result->product_id}}','yard')
		}
	})
	jQuery('.branch').on('change',function(e){
		gerProduct(e.target.value,'branch')
	})
	jQuery('.agency').on('change',function(e){
		gerProduct(e.target.value,'agency')
	})
	jQuery('.yard').on('change',function(e){
		gerProduct(e.target.value,'yard')
	})
	jQuery('.product').on('change',function(e){
		var type=jQuery('#productSelect').attr("data-type")
		var id=jQuery('#productSelect').attr("data-id")
		editBranch(id,e.target.value,type)
	})
	function gerProduct(id,type){
		var saveData = jQuery.ajax({
			type: 'get',
			url: "{{url('get_product')}}/"+id+'/'+type,
			dataType: "text",
			success: function(resultData) { 
				var data='';
				var obj=JSON.parse(resultData)
				obj.data.forEach(function(item, index){
					data=data+'<option value="'+item.product.id+'"'+(item.product.id=={{$result->product_id}}?'selected':'')+' >'+item.product.name+'</option>'
					// data=dadata=data+'<option value="'+item.product.id+'">'+item.product.name+'</option>'
				});
				jQuery('#product').show();
				jQuery('#productSelect').attr('data-type',type)
				jQuery('#productSelect').attr('data-id',id)
				jQuery('#productSelect').html(data)
				// window.location='{{ url("audited_list")}}'
			}
		});
		saveData.error(function() { alert("Something went wrong"); });
	}
	function editBranch(id,product_id,type){
		var saveData = jQuery.ajax({
			type: 'get',
			url: "{{url('get_branch_detail')}}/"+id+'/'+type+'/'+product_id,
			dataType: "text",
			success: function(resultData) { 
				console.log(resultData)
				jQuery('#data').html(resultData)
				// window.location='{{ url("audited_list")}}'
			}
		});
		saveData.error(function() { alert("Something went wrong"); });
	}
	jQuery('.alertModal').on('click',function(e){
		var subparameterId =jQuery(this).data('id')
		var parameterId =jQuery(this).data('parameterid')
		jQuery('#alertParameterId').val(parameterId)
		jQuery('#alertSubParameterId').val(subparameterId)
		console.log(parameterId)
		jQuery('#exampleModal').modal('show');
	})
	jQuery('.artifactModal').on('click',function(e){
		var subparameterId =jQuery(this).data('id')
		var parameterId =jQuery(this).data('parameterid')
		jQuery('#artifactParameterId').val(parameterId)
		jQuery('#artifactSubParameterId').val(subparameterId)
		jQuery('#artifactModal').modal('show');
	})
	jQuery('#saveAlert').on('click',function(e){
		var parid=jQuery('#alertParameterId').val()
		var subid=jQuery('#alertSubParameterId').val()
		var msg=jQuery('#msg').val()
		var sheetID="{{$data->id}}"
		jQuery('#alertParameterId').val('')
		jQuery('#alertSubParameterId').val('')
		jQuery('#msg').val('')
		jQuery('#exampleModal').modal('hide');
		var fileUpload = jQuery("#file").get(0);
		var files = fileUpload.files;
		var data = new FormData();
            data.append('id', subid);
            data.append('parameter_id', parid);
            data.append('sheet_id', sheetID);
            data.append('msg', msg);
            data.append('_token', "{{ csrf_token() }}");
            for (var i = 0; i < files.length; i++) {
                data.append('file', files[i]);
            }
			// jQuery('#file').val('')
		var saveData = jQuery.ajax({
			type: 'post',
			url: "{{url('red-alert')}}",
			data: data,
			processData: false,
			contentType: false,
			success: function(resultData) { 
				console.log(resultData)
				// window.location='{{ url("audited_list")}}'
			}
		});
		saveData.error(function() { alert("Something went wrong"); });
	})
	jQuery('#artifactAlert').on('click',function(e){
		var parid=jQuery('#artifactParameterId').val()
		var subid=jQuery('#artifactSubParameterId').val()
		var msg=jQuery('#msg').val()
		var sheetID="{{$data->id}}"
		jQuery('#artifactParameterId').val('')
		jQuery('#artifactSubParameterId').val('')
		jQuery('#msg').val('')
		jQuery('#artifactModal').modal('hide');
		var fileUpload = jQuery("input[name=artifactfile]").get(0);
		console.log(fileUpload)
		var files = fileUpload.files;
		var data = new FormData();
            data.append('id', subid);
            data.append('parameter_id', parid);
            data.append('sheet_id', sheetID);
            data.append('_token', "{{ csrf_token() }}");
            for (var i = 0; i < files.length; i++) {
                data.append('file', files[i]);
            }
			// jQuery('#file').val('')
		var saveData = jQuery.ajax({
			type: 'post',
			url: "{{url('artifact')}}",
			data: data,
			processData: false,
			contentType: false,
			success: function(resultData) { 
				console.log(resultData)
				// window.location='{{ url("audited_list")}}'
			}
		});
		saveData.error(function() { alert("Something went wrong"); });
	})
</script>
@endsection