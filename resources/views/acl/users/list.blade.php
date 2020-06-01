@extends('layouts.master')

@section('title', '| Users')

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
					<strong class="card-title">User List</strong>
					<a class="btn btn-primary btn-sm float-right" href="{{route('userUpload')}}">Import Users (Create bulk user)</a>
				</div>
				<div class="card-body">
					<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
						<thead>
							<tr>
									<th scope="col">#</th>
									<th scope="col">
										Name
									</th>
									<th scope="col">
										Role
									</th>
									<th scope="col">
										Email
									</th>
									<th scope="col">
										Phone
									{{-- </th><th scope="col">
										Role
									</th> --}}

									<th scope="col">
										Actions
									</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data as $row)
							<tr scope="row">
								<td>{{$loop->iteration}}</td>
								<td>
									{{$row->name}}
								</td>
								<td>
									@foreach($row->roles as $rrs)
									{{$rrs->name.","}}
									@endforeach
								</td>
								<td>{{$row->email}}</td>
								<td>{{$row->mobile}}</td>
								{{-- <td>{{  $row->roles()->pluck('name')->implode(' ') }}</td>Retrieve array of roles associated to a user and convert to string --}}

								<td nowrap>
									<!-- <div style="display: flex;">
										{{Form::open([ 'method'  => 'delete', 'route' => [ 'user.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
										<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
											<i class="la la-trash"></i>
										</button>
									</form> -->
									<a href="{{url('user/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-xs btn-info" title="View">
										<i class="fa fa-edit"></i>
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