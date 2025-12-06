<table class="table table-bordered table-striped">

    {{-- HEADER ROW --}}
    <thead>
        <tr>
            <th>Agency Name</th>

            @foreach ($months as $m)
                <th colspan="5" class="text-center bg-success text-white">{{ $m }} Count</th>
                <th colspan="5" class="text-center bg-success text-white">{{ $m }} Amount</th>
            @endforeach
        </tr>

        <tr>
            {{-- EMPTY FIRST COLUMN --}}
            <th></th>

            @foreach ($months as $m)
                @foreach ($buckets as $b)
                    <th class="text-center bg-secondary text-white">{{ $b }}</th>
                @endforeach

                @foreach ($buckets as $b)
                    <th class="text-center bg-secondary text-white">{{ $b }}</th>
                @endforeach
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($result as $agency => $monthData)
            <tr>
                <td class="fw-bold">{{ $agency }}</td>

                @foreach ($months as $m)
                    {{-- COUNT --}}
                    @foreach ($buckets as $b)
                        <td>{{ $monthData[$m][$b]['count'] ?? 0 }}</td>
                    @endforeach

                    {{-- AMOUNT --}}
                    @foreach ($buckets as $b)
                        <td>{{ number_format($monthData[$m][$b]['amount'] ?? 0, 2) }}</td>
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>

</table>
