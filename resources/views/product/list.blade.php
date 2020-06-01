@extends('layouts.master')

@section('title', '| Yards')

<!-- @section('sh-detail')
Users
@endsection -->

@section('content')
<div class="row">
		<div class="col-lg-12" style="margin-top:10x">
		</div>
</div>
<div class="animated fadeIn">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<strong class="card-title">Product List</strong>
				</div>
				<div class="card-body">
					<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
									<th scope="col">#</th>
									<th scope="col">
										Product Name
									</th>
									<th scope="col">
										Product Bucket
									</th>
									
									<th scope="col">
										Actions
									</th>
							</tr>
						</thead>
						<tbody>
							@foreach($product as $k=>$row)
							<tr scope="row">
								<td>{{$k+1}}</td>
								<td>
									{{$row->name}}
								</td>
								{{-- <td>
									{{ (isset($row->type) && $row->type==0)?'First':'Second'}}
								</td> --}}
								<td>
									{{ $row->bucket ?? ''}}
                                </td>
                                <td nowrap>
									<!-- <div style="display: flex;">
										{{Form::open([ 'method'  => 'delete', 'route' => [ 'user.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
										<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
											<i class="la la-trash"></i>
										</button>
									</form> -->
									<a href="{{url('product/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-xs btn-info" title="View">
										<i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{url('product/'.Crypt::encrypt($row->id))}}" class="btn btn-xs btn-danger" title="View">
										<i class="fa fa-trash"></i>
									</a>

									<!-- </div> -->
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection