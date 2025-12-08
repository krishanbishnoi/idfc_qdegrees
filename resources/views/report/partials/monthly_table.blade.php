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
                        <strong class="card-title">Sheet List</strong>
                    </div>
                    <div class="card-body">
                        <table class="table report-table mb-0">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Agency Name</th>
                                    <th>Agent ID</th>

                                    @foreach ($months as $i => $m)
                                        @php
                                            $colorClass = 'month-head-' . (($i % 3) + 1);
                                        @endphp
                                        <th colspan="2" class="{{ $colorClass }}">{{ $m }}</th>
                                    @endforeach
                                </tr>

                                <tr class="sub-head">
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    @foreach ($months as $m)
                                        <th>Count</th>
                                        <th>Receipt</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($results as $row)
                                    <tr>
                                        <td class="fw-bold">{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $row->AgencyName }}</td>
                                        <td>{{ $row->AgentId }}</td>

                                        @foreach ($months as $m)
                                            @php $safe = preg_replace('/[^A-Za-z0-9_]/', '_', $m); @endphp

                                            <td class="text-count">
                                                {{ $row->{'count_' . $safe} ?? 0 }}
                                            </td>

                                            <td class="text-money">
                                                ₹ {{ number_format($row->{'receipt_' . $safe} ?? 0, 2) }}
                                            </td>
                                        @endforeach
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

@endif
