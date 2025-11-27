@extends('layouts.master')



@section('title', '| Audit Cycle')



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

					<strong class="card-title">Audit Cycle List</strong>

					<a class="btn btn-primary btn-sm float-right" href="{{url('create-audit-cycle')}}">Create Audit Cycle</a>

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

										Created At

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

								{{$row->created_at->format("Y-m-d h:i:s")}}

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

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

@endsection

@section('js')

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>

	jQuery(document).on('ready',function(){

		jQuery('#kt_table_1').DataTable();

	})

</script>

@endsection