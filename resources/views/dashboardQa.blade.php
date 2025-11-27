@extends('layouts.master')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection

@section('content')

 <!-- Content -->

 <input type="hidden" name="url" id="url" value={{url('/')}}>

 <input type="hidden" name="token" id="token" value={{@csrf_token()}}>

    <div class="content" style="font-size: 13px !important;">

        <!-- Animated -->

        <div class="animated fadeIn">

            <!-- Widgets  -->

            <div class="card">

                <div class="card-header">

                <form action="{{route('dashboard')}}" method="post"> 

                        @csrf

                        <div class="row">

                            <div class="col-md-3"><h4>My Dashboard</h4></div>

                            <div class="col-md-9">

                                <label for="lob">Line of Business</label>

                                <select class="text-right" id="Productlob" name="productlob">

                                    <option value="all" {{(isset($old['productlob']) && $old['productlob']=='all')?'selected':''}}>All</option>

                                    <option value="collection" {{(isset($old['productlob']) && $old['productlob']=='collection')?'selected':''}}>IDFC First Collection</option>

                                    <option value="commercial_vehicle" {{(isset($old['productlob']) && $old['productlob']=='commercial_vehicle')?'selected':''}}>IDFC First Commercial Vehicle</option>

                                    <option value="rural" {{(isset($old['productlob']) && $old['productlob']=='rural')?'selected':''}}>IDFC First Rural</option>

                                    <option value="alliance" {{(isset($old['productlob']) && $old['productlob']=='alliance')?'selected':''}}>IDFC First Alliance</option>
                                    
                                     <option value="credit_card" {{(isset($old['productlob']) && $old['productlob']=='credit_card')?'selected':''}}>IDFC Credit Card</option>

                                </select>

                                <label for="audit_cycle">Audit Cycle</label>

                                <select class="text-right " name="lob_audit_cycle" id="lob_audit_cycle">

                                    <option value="current" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='current')?'selected':''}}>Current Audit Cycle</option>

                                <option value="last_2" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_2')?'selected':''}}>Last 2 Audit Cycle</option>

                                <option value="last_3" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_3')?'selected':''}}>Last 3 Audit Cycle</option>

                                <option value="last_4" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='last_4')?'selected':''}}>Last 4 Audit Cycle</option>

                                <!-- <option value="custom" {{(isset($old['lob_audit_cycle']) && $old['lob_audit_cycle']=='custom')?'selected':''}}>Custom Audit Cycle</option> -->

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

                                            <div class="stat-heading">Total Audits</div>

                                            <div class="stat-text"><span class="count2">{{ $totalAudit ?? 0 }}</span></div>

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

                                            <div class="stat-text"><span class="count2">{{ $totalpending ?? 0}}</span></div>

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

                                            <div class="stat-text"><span class="count2">{{ $totalpass ?? 0 }}</span></div>

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

                                            <div class="stat-text"><span class="count2">{{$totalfaild ?? 0}}</span></div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- .animated -->

    </div>

<!-- /.content -->

    <div class="clearfix"></div>

@endsection





@section('js')

<script>

	jQuery(document).on('ready',function(){

        jQuery(window).on('load', function(){

            jQuery('.perSign').remove()

        })

    })

</script>

@endsection

