@if (count($results) == 0)

    <div class="alert alert-danger text-center fw-bold">
        <i class="bi bi-exclamation-circle"></i> No data found.
    </div>
@else
    <div class="row">
        <div class="col-lg-12" style="margin-top:10x">
        </div>
    </div>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Agent Wise Delay Deposition List</strong>
                    </div>
                    <div class="card-body">
                        <div class="card shadow-lg border-0 mt-3">
                            <div class="card-body p-0">

                                {{-- <table class="table table-hover table-striped mb-0"> --}}
                                <table
                                    class="summary-table table table-striped- table-bordered table-hover table-checkable"
                                    id="kt_table_1">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Agency Name</th>
                                            <th>Agent Name</th>
                                            <th>Agent ID</th>
                                            <th>Receipt Date</th>
                                            <th>Count</th>
                                            <th>Total Receipt Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($results as $row)
                                            <tr class="text-center">
                                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                                <td>{{ $row->AgencyName }}</td>
                                                <td>{{ $row->AgentName }}</td>
                                                <td>{{ $row->AgentId }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->receipt_date)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-primary fw-bold">{{ $row->total_count }}</td>
                                                <td class="fw-bold text-success">
                                                    ₹ {{ number_format($row->total_receipt, 2) }}
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
        </div>
    </div>
    </div>

@endif

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        jQuery(document).on('ready', function() {

            jQuery('#kt_table_1').DataTable();

        })
    </script>
@endsection
