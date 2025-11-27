@extends('layouts.master')



@section('title', '| Branch')



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

                        <strong class="card-title">Branch List</strong>
                        <a class="btn btn-primary btn-sm float-right" style="margin-right: 5px" href="{{route('excelDownloadBranch')}}" target="_blank">Export Branch</a>

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

                                    City

                                </th>

                                <th scope="col">

                                    Manager

                                </th>

                                <th scope="col">

                                    Lob

                                </th>

                                <th scope="col">

                                    Location

                                </th>



                                <th scope="col">

                                    Actions

                                </th>

                            </tr>

                            </thead>

                            <tbody>

                                {{-- {{dd($data)}} --}}

                            @foreach($data as $row)

                                <tr scope="row">

                                    <td>{{$loop->iteration}}</td>

                                    <td>

                                        {{$row->name}}

                                    </td>

                                    <td>{{@$row->city->name}}</td>

                                    <td>{{@$row->user->name}}</td>

                                    <td>{{str_replace('_',' ',$row->lob)}}</td>

                                    <td>{{$row->location}}</td>





                                    <td nowrap>

                                    <!-- <div style="display: flex;">

										{{Form::open([ 'method'  => 'delete', 'route' => [ 'branch.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}

                                            <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">

                                                <i class="la la-trash"></i>

                                            </button>

                                        </form> -->

                                        <a href="{{url('branch/'.Crypt::encrypt($row->id).'/edit')}}"

                                           class="btn btn-xs btn-info" title="View">

                                            <i class="fa fa-edit"></i>

                                        </a>
                                        <a href="{{url('branch/'.Crypt::encrypt($row->id))}}" class="btn btn-xs btn-danger" title="Delete">

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