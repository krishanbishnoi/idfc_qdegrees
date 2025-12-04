@if(count($results) == 0)
    <p class="text-danger text-center">No data found.</p>
@else
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Agency Name</th>
            <th>Agent ID</th>
            <th>Count</th>
            <th>Total Receipt Amount</th>
        </tr>
    </thead>

    <tbody>
        @foreach($results as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->AgencyName }}</td>
            <td>{{ $row->AgentId }}</td>
            <td>{{ $row->total_count }}</td>
            <td>{{ number_format($row->total_receipt, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
