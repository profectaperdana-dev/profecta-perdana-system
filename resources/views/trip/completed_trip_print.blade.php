<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Trip Report</title>
    <style>
        body {
font-family: monospace;
        }

        .no-border-table {
            border-collapse: collapse;
        }

        .no-border-table td {
            border: none;
            font-weight: normal;
            text-align: right;
        }


        h6 {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        input,
        textarea {
            border: none;
            background-color: transparent;
            resize: none;
            width: 100%;
        }

        input[readonly],
        textarea[readonly] {
            background-color: #f9f9f9;
        }

        .text-end {
            text-align: end;
        }

        .text-center {
            text-align: center;
        }

        .col-12 {
            width: 100%;
        }

        .col-lg-6 {
            width: 50%;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -0.5rem;
            margin-left: -0.5rem;
        }

        .row>[class^="col-"],
        .row>[class*=" col-"] {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }
    </style>
</head>

<body>
    <h2 class="text-center">BUSINESS TRIP REPORT</h5>
    <h3 class="text-center">Trip Number: {{ $item->trip_number }}</h6>
    <div class="container">
        <table>
            <tr>
                <td>
                    <label for="">Name :</label>
                    <input type="text" class="form-control" readonly value="{{ $item->getName() }}">
                </td>
                <td>
                    <label for="">NIK :</label>
                    <input type="text" class="form-control" readonly value="{{ $item->getNik() }}">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="">Departure Date :</label>
                    <input type="text" class="form-control" readonly
                        value="{{ date('d-m-Y, H:i', strtotime($item->departure_date)) }} WIB">
                </td>
                <td>
                    <label for="">Return Date :</label>
                    <input type="text" class="form-control" readonly
                        value="{{ date('d-m-Y, H:i', strtotime($item->return_date)) }} WIB">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="">Submission Date :</label>
                    <input type="text" class="form-control" readonly
                        value="{{ date('d-m-Y', strtotime($item->completedBy->propose_date)) }}">
                </td>
                <td><label for="">Transport :</label>
                    <input type="text" class="form-control" readonly value="{{ $item->transport }}">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="">Departure  :</label>
                    <input type="text" class="form-control" readonly
                        value="{{ $item->routeBy->first()->place }}">
                </td>
                <td><label for="">Destination :</label>
                @php
                $count = $item->routeBy->count();
                
                @endphp
                    <input type="text" class="form-control" readonly value="{{ $item->routeBy->slice(0,$count - 1)->last()->place }}">
                </td>
            </tr>
        </table>
    </div>

    <hr>

    <div>
        <h6 class="text-center">TRIP EXPENSE</h6>
        <div class="table-responsive">
            <table border="1" class="table table-sm table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>Date</th>
                        <th>Description</th>
                        <th>Transport</th>
                        <th>Accommodation</th>
                        <th>Per-Diem</th>
                        {{-- <th>Toll</th> --}}
                        <th>Other</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->completedBy->detailBy as $detail)
                        <tr>
                            <td><input readonly class="form-control"
                                    value="{{ date('d-m-Y', strtotime($detail->date)) }}"></td>
                            <td>
                                <span style="font-family:monospace" class="form-control" cols="30" rows="1">{{ $detail->description }}</span>
                            </td>
                            <td><input placeholder="0" class="transport form-control text-end" readonly type="text"
                                    value="{{ number_format($detail->transport) }}"></td>
                            <td><input placeholder="0" class="acomodation form-control text-end" type="text" readonly
                                    value="{{ number_format($detail->accommodation) }}"></td>
                            <td><input placeholder="0" class="perDiem form-control text-end" type="text" readonly
                                    value="{{ number_format($detail->perdiem) }}"></td>
                            {{-- <td><input placeholder="0" class="toll form-control text-end" type="text" readonly
                                    value="{{ number_format($detail->toll) }}"></td> --}}
                            <td><input placeholder="0" class="other form-control text-end" type="text" readonly
                                    value="{{ number_format($detail->other) }}"></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-center">Total</th>
                        <th><input value="{{ number_format($item->completedBy->detailBy->sum('transport')) }}"
                                class="form-control text-end totalTransport" type="text" readonly></th>
                        <th><input value="{{ number_format($item->completedBy->detailBy->sum('accommodation')) }}"
                                class="form-control text-end totalAcomodation" type="text" readonly></th>
                        <th><input value="{{ number_format($item->completedBy->detailBy->sum('perdiem')) }}"
                                class="form-control text-end totalPerDiem" type="text" readonly></th>
                        {{-- <th><input value="{{ number_format($item->completedBy->detailBy->sum('toll')) }}"
                                class="form-control text-end totalToll" type="text" readonly></th> --}}
                        <th><input value="{{ number_format($item->completedBy->detailBy->sum('other')) }}"
                                class="form-control text-end totalOther" type="text" readonly></th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-end">&nbsp;</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Total Expense</th>
                        <th><input value="{{ number_format($item->completedBy->total) }}"
                                class="form-control text-end subTotal" type="text" readonly></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Cash Advance</th>
                        <th>
                            <input readonly class="form-control text-end " type="text"
                                value="{{ number_format($item->transport_expense + $item->acomodation_expense + $item->other_expense + $item->toll_cost) }}">
                            <input type="hidden"
                                value="{{ $item->transport_expense + $item->acomodation_expense + $item->other_expense + $item->toll_cost }}"
                                class="cashAdvance_">
                        </th>
                    </tr>
                    @php
                        $cashRemain = $item->transport_expense + $item->acomodation_expense + $item->toll_cost + $item->other_expense - $item->completedBy->total;
                    @endphp
                    <tr>
                        <th colspan="5" class="text-end">Cash Remaining</th>
                        <th><input value="{{ number_format($cashRemain) }}"
                                class="form-control text-end cashRemain @if ($cashRemain < 0) text-danger @endif"
                                type="text" readonly></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br>
    <table class="no-border-table">
        <tr>
            <td>
                <h5>Acknowledged by,</h5>
                <br>
                <h5>&nbsp;</h5>
            </td>
            <td>
                <h5>Approved by Finance,</h5>
                <br>
                <h5>({{ $item->completedBy->financeBy->name }})</h5>
            </td>
            <td>
                <h5>Approved by GA,</h5>
                <br>
                <h5>({{ $item->completedBy->gaBy->name }})</h5>
            </td>
            <td>
                <h5>Submitted by,</h5>
                <br>
                <h5>&nbsp;</h5>
            </td>
        </tr>
    </table>
</body>

</html>
