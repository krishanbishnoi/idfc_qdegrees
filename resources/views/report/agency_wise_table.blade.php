<style>
    .table-container {
        width: 100%;
        overflow-x: auto;
        /* Enables horizontal scrolling */
        overflow-y: hidden;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    /* Optional: Sticky left column */
    .agency-report td.agency-col,
    .agency-report th.agency-col {
        position: sticky;
        left: 0;
        z-index: 5;
        background: #0c2f4e !important;
    }

    /* Sticky header row */
    .agency-report th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>

<style>
    /* ----------------------------------------------------
       MAIN REPORT TABLE (AGENCY REPORT)
    ---------------------------------------------------- */

    table.agency-report {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin-bottom: 40px;
    }

    table.agency-report th,
    table.agency-report td {
        border: 1px solid #dcdcdc;
        padding: 6px 8px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    .header-dark {
        background: #123c66;
        color: white;
        font-weight: bold;
        font-size: 13px;
    }

    .header-green {
        background: #2d4d3a;
        color: white;
        font-weight: bold;
        font-size: 13px;
    }

    .count-header {
        background: #ca8f54 !important;
        color: #fff !important;
        font-weight: bold;
    }

    .amount-header {
        background: #4d6b4d !important;
        color: #fff !important;
        font-weight: bold;
    }

    .agency-col {
        background: #0c2f4e;
        color: #fff;
        font-weight: bold;
        width: 200px;
    }

    .agency-report tr:nth-child(even) {
        background: #f8f8f8;
    }


    /* ----------------------------------------------------
       SUMMARY TABLE
    ---------------------------------------------------- */

    table.summary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    table.summary-table th {
        background: #0a6d73;
        color: white;
        text-align: center;
        padding: 8px;
        font-size: 14px;
    }

    table.summary-table td {
        border: 1px solid #dcdcdc;
        padding: 7px;
        text-align: center;
    }

    tr.count-row {
        background: #faf0d0;
        font-weight: 600;
    }

    tr.amount-row {
        background: #f7f7e8;
        font-weight: 600;
    }

    .green-cell {
        background: #2ecc71;
        color: white;
        font-weight: bold;
    }

    .red-cell {
        background: #e74c3c;
        color: white;
        font-weight: bold;
    }

    .yellow-cell {
        background: #f1c40f;
        color: black;
        font-weight: bold;
    }
</style>






{{-- =======================================================
     SUMMARY TABLE (COUNT + AMOUNT)
======================================================= --}}

@php
    // Count rows
    $countRows = ['Overall Count' => null];
    foreach ($months as $m) {
        $countRows[$m . ' Count'] = $m;
    }

    // Amount rows
    $amountRows = ['Overall Amount' => null];
    foreach ($months as $m) {
        $amountRows[$m . ' Amount'] = $m;
    }

    $summary = [];

    // ---------------- COUNT SUMMARY ----------------
    foreach ($countRows as $label => $monthFilter) {
        $same = $delay = $na = 0;

        foreach ($result as $agency => $monthData) {
            $monthLoop = $monthFilter ? [$monthFilter] : $months;

            foreach ($monthLoop as $m) {
                $same += $monthData[$m]['Same Day']['count'] ?? 0;
                $delay +=
                    ($monthData[$m]['3 To 5 Day']['count'] ?? 0) +
                    ($monthData[$m]['6 To 10 Day']['count'] ?? 0) +
                    ($monthData[$m]['Above 10 Day']['count'] ?? 0);
                $na += $monthData[$m]['Deposit Date Not Available']['count'] ?? 0;
            }
        }

        $total = $same + $delay + $na;

        $summary[$label] = [
            'isAmount' => false,
            'total' => $total,
            'same' => $same,
            'delay' => $delay,
            'na' => $na,
            'c' => $total ? round(($same / $total) * 100) : 0,
            'nc' => $total ? round(($delay / $total) * 100) : 0,
            'dna' => $total ? round(($na / $total) * 100) : 0,
        ];
    }

    // ---------------- AMOUNT SUMMARY ----------------
    foreach ($amountRows as $label => $monthFilter) {
        $same = $delay = $na = 0;

        foreach ($result as $agency => $monthData) {
            $monthLoop = $monthFilter ? [$monthFilter] : $months;

            foreach ($monthLoop as $m) {
                $same += $monthData[$m]['Same Day']['amount'] ?? 0;
                $delay +=
                    ($monthData[$m]['3 To 5 Day']['amount'] ?? 0) +
                    ($monthData[$m]['6 To 10 Day']['amount'] ?? 0) +
                    ($monthData[$m]['Above 10 Day']['amount'] ?? 0);
                $na += $monthData[$m]['Deposit Date Not Available']['amount'] ?? 0;
            }
        }

        $total = $same + $delay + $na;

        $summary[$label] = [
            'isAmount' => true,
            'total' => $total,
            'same' => $same,
            'delay' => $delay,
            'na' => $na,
        ];
    }
@endphp
<div class="row">
    <div class="col-lg-12" style="margin-top:10x">
    </div>
</div>
<div class="animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Summary List</strong>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="summary-table">

                            <tr>
                                <th>Summary</th>
                                <th>Total Receipt</th>
                                <th>Same Day</th>
                                <th>Delay Payment</th>
                                <th>Deposit Date Not Available</th>
                                <th>Compliance %</th>
                                <th>Non Compliance %</th>
                                <th>Deposit NA %</th>
                            </tr>

                            @foreach ($summary as $label => $row)
                                <tr class="{{ $row['isAmount'] ? 'amount-row' : 'count-row' }}">

                                    <td><b>{{ $label }}</b></td>

                                    <td>{{ number_format($row['total']) }}</td>
                                    <td>{{ number_format($row['same']) }}</td>
                                    <td>{{ number_format($row['delay']) }}</td>
                                    <td>{{ number_format($row['na']) }}</td>

                                    @if (!$row['isAmount'])
                                        <td class="green-cell">{{ $row['c'] }}%</td>
                                        <td class="red-cell">{{ $row['nc'] }}%</td>
                                        <td class="yellow-cell">{{ $row['dna'] }}%</td>
                                    @else
                                        <td colspan="3" style="background:#fff3cd; font-weight:bold;">—</td>
                                    @endif

                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12" style="margin-top:10x">
    </div>
</div>
<div class="animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Agency Report</strong>
                </div>
                <div class="card-body">
                    {{-- =======================================================
     MAIN AGENCY REPORT TABLE
======================================================= --}}
                    <div class="table-container">
                        <table class="agency-report">

                            {{-- MAIN HEADER --}}
                            <tr>
                                <th rowspan="3" class="agency-col">Agency Name</th>

                                {{-- Overall --}}
                                <th colspan="{{ 2 * (1 + count($buckets)) }}" class="header-dark">Overall</th>

                                {{-- Months --}}
                                @foreach ($months as $m)
                                    <th colspan="{{ 2 * (1 + count($buckets)) }}" class="header-green">
                                        {{ $m }}</th>
                                @endforeach
                            </tr>


                            {{-- Count / Amount Header --}}
                            <tr>
                                {{-- Overall --}}
                                <th colspan="{{ 1 + count($buckets) }}" class="count-header">Overall Count</th>
                                <th colspan="{{ 1 + count($buckets) }}" class="amount-header">Overall Amount</th>

                                {{-- Months --}}
                                @foreach ($months as $m)
                                    <th colspan="{{ 1 + count($buckets) }}" class="count-header">Count</th>
                                    <th colspan="{{ 1 + count($buckets) }}" class="amount-header">Amount</th>
                                @endforeach
                            </tr>


                            {{-- Bucket Names --}}
                            <tr>
                                {{-- Overall --}}
                                <th>Total Receipt</th>
                                @foreach ($buckets as $b)
                                    <th>{{ $b }}</th>
                                @endforeach

                                <th>Total Amount</th>
                                @foreach ($buckets as $b)
                                    <th>{{ $b }}</th>
                                @endforeach

                                {{-- Months --}}
                                @foreach ($months as $m)
                                    <th>Total Receipt</th>
                                    @foreach ($buckets as $b)
                                        <th>{{ $b }}</th>
                                    @endforeach

                                    <th>Total Amount</th>
                                    @foreach ($buckets as $b)
                                        <th>{{ $b }}</th>
                                    @endforeach
                                @endforeach
                            </tr>


                            {{-- DATA ROWS --}}
                            @foreach ($result as $agency => $monthData)
                                <tr>
                                    <td class="agency-col">{{ $agency }}</td>

                                    {{-- OVERALL SECTION --}}
                                    @php
                                        $overallCount = 0;
                                        $overallAmount = 0;
                                        foreach ($months as $m) {
                                            foreach ($buckets as $b) {
                                                $overallCount += $monthData[$m][$b]['count'] ?? 0;
                                                $overallAmount += $monthData[$m][$b]['amount'] ?? 0;
                                            }
                                        }
                                    @endphp

                                    <td>{{ $overallCount }}</td>

                                    @foreach ($buckets as $b)
                                        @php
                                            $sumCnt = 0;
                                            foreach ($months as $m) {
                                                $sumCnt += $monthData[$m][$b]['count'] ?? 0;
                                            }
                                        @endphp
                                        <td>{{ $sumCnt }}</td>
                                    @endforeach

                                    <td>{{ number_format($overallAmount, 2) }}</td>

                                    @foreach ($buckets as $b)
                                        @php
                                            $sumAmt = 0;
                                            foreach ($months as $m) {
                                                $sumAmt += $monthData[$m][$b]['amount'] ?? 0;
                                            }
                                        @endphp
                                        <td>{{ number_format($sumAmt, 2) }}</td>
                                    @endforeach


                                    {{-- PER MONTH SECTION --}}
                                    @foreach ($months as $m)
                                        @php
                                            $monthlyCount = 0;
                                            $monthlyAmount = 0;
                                            foreach ($buckets as $b) {
                                                $monthlyCount += $monthData[$m][$b]['count'] ?? 0;
                                                $monthlyAmount += $monthData[$m][$b]['amount'] ?? 0;
                                            }
                                        @endphp

                                        <td>{{ $monthlyCount }}</td>

                                        @foreach ($buckets as $b)
                                            <td>{{ $monthData[$m][$b]['count'] ?? 0 }}</td>
                                        @endforeach

                                        <td>{{ number_format($monthlyAmount, 2) }}</td>

                                        @foreach ($buckets as $b)
                                            <td>{{ number_format($monthData[$m][$b]['amount'] ?? 0, 2) }}</td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>