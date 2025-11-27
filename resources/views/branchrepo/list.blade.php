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
                        <strong class="card-title">Branch Repo List</strong>
                        <a class="btn btn-primary btn-sm float-right" style="margin-right: 5px" href="{{route('excelDownloadBranchRepo')}}" target="_blank">Export Branch Repo</a>
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
                                    Branch Name
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
                            @foreach($data as $row)
                                <tr scope="row">
                                    <td>{{$loop->iteration}}</td>
                                    <td>
                                        {{$row->name}}
                                    </td>
                                    <td>{{@$row->branch->name}}</td>
                                    <td>{{$row->location}}</td>


                                    <td nowrap>
                                     <div style="display: flex;">
                                        <a href="{{url('branchrepo/'.Crypt::encrypt($row->id).'/edit')}}"
                                            class="btn btn-xs btn-info" title="View">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        {{Form::open([ 'method'  => 'delete', 'route' => [ 'branchrepo.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                                            <button class="btn btn-xs btn-danger" title="delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                         </div>
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