@if(count($results) == 0)

    <div class="alert alert-danger text-center fw-bold">
        <i class="bi bi-exclamation-circle"></i> No data found.
    </div>

@else

<div class="card shadow-lg border-0 mt-3">
    <div class="card-body p-0">

        <table class="table table-hover table-striped mb-0">
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
                @foreach($results as $row)
                <tr class="text-center">
                    <td class="fw-bold">{{ $loop->iteration }}</td>
                    <td>{{ $row->AgencyName }}</td>
                    <td>test</td>
                    <td>{{ $row->AgentId }}</td>
                    <td>123</td>
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

@endif
