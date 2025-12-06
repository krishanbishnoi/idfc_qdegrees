@extends('layouts.master')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@endsection
@section('content')
 <!-- Content -->
 <input type="hidden" name="url" id="url" value={{url('/')}}>
 <input type="hidden" name="token" id="token" value={{@csrf_token()}}>
 
 @role('Admin')
 <div>
  

<button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#myModal">
 Dump Download
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: max-content;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Dump Download</h4>
      </div>
            <form method="POST" action="{{route('dump-excel')}}" autocomplete="off">
            <div class="modal-body">
        
        

                        <div class="row">

                            @csrf

                            <div class="col-md-3 form-group">

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

                            <div class="col-md-3 form-group">

                                <label>Select Branch</label>

                                <select class="form-control" name="branch_name" id="branch_name"  value="{{old('branch_name')}}">

                                    <option value="">All</option>
                                    
                                    @foreach ($branch as $item)

                                        <option value="{{$item->id}}" {{($item->id == old('branch_name'))?'selected':''}} >{{$item->name}}</option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3 form-group">

                                <label>Start Date*</label>

                                <input name="start_date" type="text" data-date-format="yyyy-mm-dd" class="form-control datepicker" required />

                            </div>

                            <div class="col-md-3 form-group">

                                <label>End Date*</label>

                                <input name="end_date" type="text" data-date-format="yyyy-mm-dd" class="form-control datepicker"/>

                            </div>

                            
                        </div>

                    

            </div>
                <div class="modal-footer">
                 <button type="submit" class="btn btn-primary">Download</button>
                </div>
        </form>
    </div>
  </div>
</div>


 <!-- end -->

 <!-- <a class="btn btn-sm btn-primary float-right" href="{{route('dump-excel')}}">Dump Download</a> -->
 </div>
 @endrole
 <div class="content" style="font-size: 13px !important;">
            <!-- Animated -->
            <div class="animated fadeIn">
{{-- ===================================================================== --}}
            @role('Admin')
                <div class="card">
                        <div class="card-header">
                                <div class="row">
                                    <div class="col-md-7"><h4>Overall Details</h4></div>
                                </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Total Audits</div>
                                                    <div class="stat-text"><span class="count2">{{ $qa['totalAudit'] ?? 0 }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Total Pending</div>
                                                    <div class="stat-text"><span class="count2">{{ $qa['totalpending'] ?? 0}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Audit Submited - QC Pass</div>
                                                    <div class="stat-text"><span class="count2">{{ $qa['totalpass'] ?? 0 }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Audit Submited - QC Fail</div>
                                                    <div class="stat-text"><span class="count2">{{$qa['totalfaild'] ?? 0}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Total Pending To Approved</div>
                                                    <div class="stat-text"><span class="count2">{{ $qc['totalpending'] ?? 0}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Audit Approved</div>
                                                    <div class="stat-text"><span class="count2">{{ $qc['totalApproved'] ?? 0 }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Audit Approved Without Changes</div>
                                                    <div class="stat-text"><span class="count2">{{ $qc['totalpass'] ?? 0 }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Audit Approved With Changes</div>
                                                    <div class="stat-text"><span class="count2">{{$qc['totalpassChange'] ?? 0}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Audit Saved</div>
                                                    <div class="stat-text"><span class="count2">{{$qc['totalsaved'] ?? 0}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Audit Failed</div>
                                                    <div class="stat-text"><span class="count2">{{$qc['totalfaild'] ?? 0}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                    <div class="stat-heading">Alert Raised</div>
                                                    <div class="stat-text"><span class="count2">{{$totalalert ?? 0}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            @endrole
{{-- ================================================================================ --}}

{{-- ===================================================================== --}}
            @role('Client')

                <!-- Widgets  -->
                <div class="card">
                    <div class="card-header">
                        <form action="{{route('dashboard')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-7"><h4>Line of Business Score</h4></div>
                                <div class="col-md-5">
                                    <label for="audit_cycle">Audit Cycle</label>
                                    <select class="text-right " name="lob_audit_cycle" id="lob_audit_cycle">
                                        <option value="current" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='current')?'selected':''}}>Current Audit Cycle</option>
                                    <option value="last_2" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_2')?'selected':''}}>Last 2 Audit Cycle</option>
                                    <option value="last_3" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_3')?'selected':''}}>Last 3 Audit Cycle</option>
                                    <option value="last_4" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_4')?'selected':''}}>Last 4 Audit Cycle</option>
                                    <option value="custom" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='custom')?'selected':''}}>Custom Audit Cycle</option>
                                    </select>
                                <input type="text" style="{{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='custom')?'':'display:none;'}}" value="{{(isset($old['lob_audit_cycle_custom']))? $old['lob_audit_cycle_custom']:''}}" name="lob_audit_cycle_custom" id="lob_audit_cycle_custom"/>
                                    <input type="submit" class="text-right ml-2" value="Show Result">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Collection</div>
                                                <div class="stat-text"><span class="count">{{isset($lob['collection'])?(round(($lob['collection']['point']/$lob['collection']['total'])*100,2)) : 0}}%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Commercial Vehicle</div>
                                                <div class="stat-text"><span class="count">{{isset($lob['commercial_vehicle'])?(round(($lob['commercial_vehicle']['point']/$lob['commercial_vehicle']['total'])*100,2)) : 0}}%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Rural</div>
                                                <div class="stat-text"><span class="count">{{isset($lob['rural'])?(round(($lob['rural']['point']/$lob['rural']['total'])*100,2))  : 0}}%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC First Alliance</div>
                                                <div class="stat-text"><span class="count">{{isset($lob['alliance'])?(round(($lob['alliance']['point']/$lob['alliance']['total'])*100,2))  : 0}}%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="stat-widget-five text-center">
                                            <div class="text-center dib">
                                                <div class="stat-heading">IDFC Credit Card</div>
                                                <div class="stat-text"><span class="count">{{isset($lob['credit_card'])?(round(($lob['credit_card']['point']/$lob['credit_card']['total'])*100,2))  : 0}}%</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-md-3"><h4>Product Score</h4></div>
                        <div class="col-md-9 ">
                            <form action="{{route('dashboard')}}" method="post">
                                @csrf
                                <label for="lob">Line of Business</label>
                                <select class="text-right" id="Productlob" name="productlob">
                                    <option value="all" {{(isset($old['productlob']) && $old['productlob']=='all')?'selected':''}}>All</option>
                                    <option value="collection" {{(isset($old['productlob']) && $old['productlob']=='collection')?'selected':''}}>IDFC First Collection</option>
                                    <option value="commercial_vehicle" {{(isset($old['productlob']) && $old['productlob']=='commercial_vehicle')?'selected':''}}>IDFC First Commercial Vehicle</option>
                                    <option value="rural" {{(isset($old['productlob']) && $old['productlob']=='all')?'rural':''}}>IDFC First Rural</option>
                                    <option value="alliance" {{(isset($old['productlob']) && $old['productlob']=='all')?'alliance':''}}>IDFC First Alliance</option>
                                     <option value="credit_card" {{(isset($old['productlob']) && $old['productlob']=='all')?'credit_card':''}}>IDFC Credit Card</option>
                                </select>
                                <label for="audit_cycle">Audit Cycle</label>
                                <select class="text-right" id="product_audit_cycle" name="product_audit_cycle">
                                    <option value="current" {{(isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='current')?'selected':''}}>Current Audit Cycle</option>
                                    <option value="last_2" {{(isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='last_2')?'selected':''}}>Last 2 Audit Cycle</option>
                                    <option value="last_3" {{(isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='last_3')?'selected':''}}>Last 3 Audit Cycle</option>
                                    <option value="last_4" {{(isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='last_4')?'selected':''}}>Last 4 Audit Cycle</option>
                                    <option value="custom" {{(isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='custom')?'selected':''}}>Custom Audit Cycle</option>
                                </select>
                                <input type="text" style="{{(isset($old['product_audit_cycle']) && $old['product_audit_cycle']=='custom')?'':'display:none;'}}" value="{{(isset($old['product_audit_cycle_custom']))? $old['product_audit_cycle_custom']:''}}" name="product_audit_cycle_custom" id="product_audit_cycle_custom"/>
                                <input type="submit" class="text-right" value="Show Result">
                            </form>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                        <div class="row" style="padding-bottom:10px!important">
                            <div class="col-md-8">
                                <span><strong>Top 4 Products</strong></span>
                                <a style="padding-left:30px;" class="cursor" onClick="showProduct();">SHOW ALL PRODUCTS</a>
                            </div>
                        </div>
                        <div class="row">
                           @foreach ($product as $item)
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="stat-widget-five text-center">
                                                <div class="text-center dib">
                                                <div class="stat-heading">{{$item['lob'].'-'.$item['name']}}</div>
                                                    <div class="stat-text"><span class="count">{{(round(($item['point']/$item['total'])*100,2))}}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>National Score</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">Product</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="product" id="nationalProduct">
                                            <option value="all">All</option>
                                            @foreach ($productList as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Line of Business </div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="lob" id="nationalLob">
                                            <option value="all" {{(isset($old['lob']) && $old['lob']=='all')?'selected':''}}>All</option>
                                            <option value="collection" {{(isset($old['lob']) && $old['lob']=='collection')?'selected':''}}>IDFC First Collection</option>
                                            <option value="commercial_vehicle" {{(isset($old['lob']) && $old['lob']=='commercial_vehicle')?'selected':''}}>IDFC First Commercial Vehicle</option>
                                            <option value="rural" {{(isset($old['lob']) && $old['lob']=='all')?'rural':''}}>IDFC First Rural</option>
                                            <option value="alliance" {{(isset($old['lob']) && $old['lob']=='all')?'alliance':''}}>IDFC First Alliance</option>
                                            <option value="credit_card" {{(isset($old['lob']) && $old['lob']=='all')?'credit_card':''}}>IDFC Credit Card</option>
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Zone</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="zone" id="nationalZone">
                                            <option value="all">All</option>
                                            <option value="2">East</option>
                                            <option value="3">West</option>
                                            <option value="1">North</option>
                                            <option value="4">South</option>
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Audit Cycle</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="audit_cycle" id="nationalAudit_cycle">
                                            <option value="current">Current Audit Cycle</option>
                                            <option value="last_2">Last 2 Audit Cycle</option>
                                            <option value="last_3">Last 3 Audit Cycle</option>
                                            <option value="last_4">Last 4 Audit Cycle</option>
                                            <option value="custom">Custom Audit Cycle</option>
                                        </Select>
                                        <input type="text" class="form-control" style="display:none;" name="nationalAudit_cycle_custom" id="nationalAudit_cycle_custom"/>
                                    </div>
                                    <div class="col-md-6">State</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="state" id="nationalState">
                                            <option value="all">All</option>
                                        </Select>
                                    </div>
                                    <div class="col-md-6">Branch</div>
                                    <div class="col-md-6">
                                        <Select class="form-control" name="branch" id="nationalBranch">
                                            <option value="all">All</option>
                                        </Select>
                                    </div>

                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <input type="button" value="Show Result" id="nationalResult"/>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-control text-center">
                                            <strong>Total Score: <span id="NationalTotal">0</span></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div id="map" style="height: 450px;"></div>
                                {{-- <!-- <div>
                                    <a href="#" class="" style="float: right;">See more</a>
                                </div>   --> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('dashboard')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="lob">Product</label>
                                            <select class="form-control" name="filterProduct" id="filterProduct">
                                                <option value="all" {{(isset($old['filterProduct']) && $old['filterProduct']=='all')?'selected':''}}>All</option>
                                                @foreach ($productList as $item)
                                                    <option value="{{$item->id}}" {{(isset($old['filterProduct']) && $old['filterProduct']==$item->id)?'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="lob">Line of Business</label>
                                            <select class="form-control" name="filterlob" id="filterlob">
                                                <option value="all" {{(isset($old['filterlob']) && $old['filterlob']=='all')?'selected':''}}>All</option>
                                                <option value="collection" {{(isset($old['filterlob']) && $old['filterlob']=='collection')?'selected':''}}>IDFC First Collection</option>
                                                <option value="commercial_vehicle" {{(isset($old['filterlob']) && $old['filterlob']=='commercial_vehicle')?'selected':''}}>IDFC First Commercial Vehicle</option>
                                                <option value="rural" {{(isset($old['filterlob']) && $old['filterlob']=='all')?'rural':''}}>IDFC First Rural</option>
                                                <option value="alliance" {{(isset($old['filterlob']) && $old['filterlob']=='all')?'alliance':''}}>IDFC First Alliance</option>
                                                 <option value="credit_card" {{(isset($old['filterlob']) && $old['filterlob']=='all')?'credit_card':''}}>IDFC Credit Card</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="audot_cycle">Audit Cycle</label>
                                            <select class="form-control" name="filteraudit_cycle" id="filteraudit_cycle">
                                                <option {{(isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='All')?'selected':''}}>All</option>
                                                <option value="current" {{(isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='current')?'selected':''}}>Current Audit Cycle</option>
                                                <option value="last_2" {{(isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='last_2')?'selected':''}}>Last 2 Audit Cycle</option>
                                                <option value="last_3" {{(isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='last_3')?'selected':''}}>Last 3 Audit Cycle</option>
                                                <option value="last_4" {{(isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='last_4')?'selected':''}}>Last 4 Audit Cycle</option>
                                                <option value="custom" {{(isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='custom')?'selected':''}}>Custom Audit Cycle</option>
                                            </select>
                                            <input type="text" class="form-control" style="{{(isset($old['filteraudit_cycle']) && $old['filteraudit_cycle']=='custom')?'':'display:none;'}}" value="{{(isset($old['filteraudit_cycle_custom']))? $old['filteraudit_cycle_custom']:''}}" name="filteraudit_cycle_custom" id="filteraudit_cycle_custom"/>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="zone">Zone</label>
                                            <select class="form-control" id="zone" name="filterzone">
                                                <option value="all" {{(isset($old['filterzone']) && $old['filterzone']=='all')?'selected':''}}>All</option>
                                                <option value="2" {{(isset($old['filterzone']) && $old['filterzone']=='2')?'selected':''}}>East</option>
                                                <option value="3" {{(isset($old['filterzone']) && $old['filterzone']=='3')?'selected':''}}>West</option>
                                                <option value="1" {{(isset($old['filterzone']) && $old['filterzone']=='1')?'selected':''}}>North</option>
                                                <option value="4" {{(isset($old['filterzone']) && $old['filterzone']=='4')?'selected':''}}>South</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="State">State</label>
                                            <select class="form-control" id="State" name="filterstate">
                                                <option value="all">All</option>

                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="branch">Branch</label>
                                            <select class="form-control" id="branch" name="filterbranch">
                                                <option value="all">All</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" class="mt-3" value="Apply Filters">
                                    <br><a href="#">Reset Filters</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5>Collection Manager’s Scorecard</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped  table-hover table-checkable" id="collection_manager_table">
                            <thead style="background-color: rgba(0,0,0,.03);">
                                <tr>
                                    <th>Collection Manager Name</th>
                                    <th>Current Score</th>
                                    <th>Previous Score</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filterData as $k=>$item)
                                <tr>
                                <td>{{$item['name']}}</td>
                                <td>{{round((($item['point']/$item['total'])*100),2)}}%</td>
                                    <td>0</td>
                                <td><a style="cursor: pointer;" class="filterShow" data-id="{{$k}}" data-name="{{$item['name']}}">Show Details</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{-- <a href="#" class="" style="float: right;">See more</a> --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Bottom 10 Collection Managers</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topCollectionManager['bottom'] as $item)
                                            <tr>
                                                <td>{{$item['name']}}</td>
                                                <td>{{round((($item['point']/$item['total'])*100),2)}}%</td>
                                                <td>0</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>
                                    {{-- <a href="#" class="" style="float: right;">See more</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Top 10 Collection Managers</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topCollectionManager['top'] as $item)
                                        <tr>
                                            <td>{{$item['name']}}</td>
                                            <td>{{round((($item['point']/$item['total'])*100),2)}}%</td>
                                            <td>0</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- <div>
                                    <a href="#" class="" style="float: right;">See more</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Bottom 10 Parameters of Product</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bottomProductParameter['bottom'] as $item)
                                        <tr>
                                            <td>{{$item['name']}}</td>
                                            <td>{{$item['point']}}%</td>
                                            <td>0</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- <div>
                                    <a href="#" class="" style="float: right;">See more</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Top 10 Parameters of Product</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bottomProductParameter['top'] as $item)
                                        <tr>
                                            <td>{{$item['name']}}</td>
                                            <td>{{$item['point']}}%</td>
                                            <td>0</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- <div>
                                    <a href="#" class="" style="float: right;">See more</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Bottom 10 Agencies</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topAgency['bottom'] as $item)
                                        <tr>
                                            <td>{{$item['name']}}</td>
                                            <td>{{round((($item['point']/$item['total'])*100),2)}}%</td>
                                            <td>0</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>
                                    {{-- <a href="#" class="" style="float: right;">See more</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped  table-hover table-checkable">
                                    <thead style="background-color: rgba(0,0,0,.03);">
                                        <tr>
                                            <th>Top 10 Agencies</th>
                                            <th>Current Score</th>
                                            <th>Previous Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topAgency['top'] as $item)
                                        <tr>
                                            <td>{{$item['name']}}</td>
                                            <td>{{round((($item['point']/$item['total'])*100),2)}}%</td>
                                            <td>0</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- <div>
                                    <a href="#" class="" style="float: right;">See more</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Modal - Calendar - Add Category -->
                <div class="modal  bd-example-modal-lg fade none-border" id="add-category">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title text-center"><strong id="title"></strong></h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-striped  table-hover table-checkable">
                                            <thead style="background-color: rgba(0,0,0,.03);">
                                                <tr>
                                                    <th>Agency Name</th>
                                                    <th>Score</th>
                                                </tr>
                                            </thead>
                                            <tbody id="agencyData">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-striped  table-hover table-checkable tableParameter" style="display:none">
                                            <thead style="background-color: rgba(0,0,0,.03);">
                                                <tr>
                                                    <th>Parameter Name</th>
                                                    <th>Score</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="parameterdata">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="pareto"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                {{-- <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ====================================== --}}
                <div class="modal fade none-border" id="add-product">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title text-center"><strong>Parameters </strong></h4>
                            </div>
                            <div class="modal-body">
                                <h4 class="mb-3">pareto Chart</h4>
                                <div class="flot-container">
                                    <div id="pareto" style="width:100%;height:275px;"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                {{-- <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade none-border" id="show-product">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <div class="modal-title text-center">
                                    <span class="float-right">Total Product: <span id="totalPoint"></span></span>
                                    <strong><h4>ALL PRODUCT SCORE</h4></strong>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="productBack">

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                {{-- <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            <!-- /#add-category -->

            <div class="modal fade none-border" id="showStateBranch">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title text-center"><strong id="stateName"></strong><strong class="float-right">State Score:<span id="stateTotal"></span></strong></h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped  table-hover table-checkable">
                                <thead style="background-color: rgba(0,0,0,.03);">
                                    <tr>
                                        <th>Name</th>
                                        <th>Previous Score</th>
                                        <th>Current Score</th>
                                    </tr>
                                </thead>
                                <tbody id="showStateBranchBody">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                            {{-- <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button> --}}
                        </div>
                    </div>
                </div>
            </div>

            @endrole
{{-- ================================================================================ --}}

            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
@endsection
@section('js')
<script src="{{URL::asset('public/js/highmaps.js')}}"></script>
<script src="{{URL::asset('public/js/exporting.js')}}"></script>
<script src="{{URL::asset('public/js/in-all.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{URL::asset('public/js/dashboard.js')}}"></script>
<script src="https://code.highcharts.com/modules/pareto.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    jQuery(document).ready(function() {

        jQuery('.datepicker').datepicker({
            dateFormat: "yyyy-mm-dd"
        });
        
        indiaMap([]);
        // jQuery('#add-product').modal('show')
        // pareto();
        jQuery('#collection_manager_table').DataTable()
        jQuery('#nationalResult').trigger('click');
        })
</script>

@endsection
