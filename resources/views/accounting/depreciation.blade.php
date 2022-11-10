@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }} --}}
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Report Depreciation of Asset</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="depreciation"
                                class="table table-sm table-hover table-striped expandable-table text-capitalize"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Asset Name</th>
                                        <th>Amount</th>
                                        <th>Lifetime (In Month)</th>
                                        <th>Year of Acquisition</th>
                                        @for ($i = 0; $i < $current_year - $smallest_year + 1; $i++)
                                            <th>Acquisition Cost {{ $smallest_year + $i }}</th>
                                            <th>Depreciation {{ $smallest_year + $i }}</th>
                                            <th>End Book Value {{ $smallest_year + $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($depreciations as $item)
                                        <tr>
                                            <td>{{ $item->asset_name }}</td>
                                            <td>{{ $item->amount }}</td>
                                            <td>{{ $item->lifetime }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->acquisition_year)) }}</td>
                                            @php
                                                $temp_cost = $item->acquisition_cost;
                                            @endphp
                                            @for ($i = 0; $i < $current_year - $smallest_year + 1; $i++)
                                                @if (date('Y', strtotime($item->acquisition_year)) == $smallest_year + $i)
                                                    <td>{{ number_format($item->acquisition_cost, 0, ',', '.') }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                @if (date('Y', strtotime($item->acquisition_year)) <= $smallest_year + $i)
                                                    @if (date('Y', strtotime($item->acquisition_year)) == $smallest_year + $i)
                                                        @php
                                                            $month = date('n', strtotime($item->acquisition_year));
                                                            $countmonth = 13 - intval($month);
                                                            $cost_per_month = $item->acquisition_cost / $item->lifetime;
                                                            $cost_current = $cost_per_month * $countmonth;
                                                            $check_cost = $temp_cost - $cost_current;
                                                        @endphp
                                                        @if ($check_cost <= 0 && $temp_cost != 0)
                                                            <td>{{ number_format($temp_cost, 0, ',', '.') }}</td>
                                                        @elseif ($check_cost <= 0 && $temp_cost == 0)
                                                            <td>-</td>
                                                        @else
                                                            <td>{{ number_format($cost_current, 0, ',', '.') }}</td>
                                                        @endif
                                                    @else
                                                        @php
                                                            $cost_per_month = $item->acquisition_cost / $item->lifetime;
                                                            $cost_current = $cost_per_month * 12;
                                                            $check_cost = $temp_cost - $cost_current;
                                                        @endphp
                                                        @if ($check_cost <= 0 && $temp_cost < 0)
                                                            <td>{{ number_format($temp_cost, 0, ',', '.') }}</td>
                                                        @elseif ($check_cost <= 0 && $temp_cost == 0)
                                                            <td>-</td>
                                                        @else
                                                            <td>{{ number_format($cost_current, 0, ',', '.') }}</td>
                                                        @endif
                                                    @endif
                                                    @php
                                                        if ($temp_cost > 0) {
                                                            $temp_cost = $temp_cost - $cost_current;
                                                        }
                                                    @endphp
                                                    @if ($temp_cost <= 0)
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ number_format($temp_cost, 0, ',', '.') }}</td>
                                                    @endif
                                                @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                @endif
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th colspan="4" class="text-center">Total</th>
                                </tfoot>
                            </table>
                        </div>
                        <br>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });


                function getTabletoArray() {
                    var table = $('#depreciation');
                    var data = [];

                    table.find('tr').each(function(i, el) {
                        // no thead
                        if (i != 0) {
                            var $tds = $(this).find('td');
                            var row = [];
                            $tds.each(function(i, el) {
                                row.push($(this).text());
                            });
                            data.push(row);
                        }

                    });
                    //no tfoot
                    data.pop();

                    return data;
                }

                let all_values = getTabletoArray();
                let total_arr = [];
                for (let i = 4; i < all_values[0].length; i++) {
                    let temp_total = 0;
                    for (let j = 0; j < all_values.length; j++) {
                        let raw_string = all_values[j][i];
                        if (raw_string == '-') {
                            temp_total = temp_total + 0;
                        } else {
                            let no_dots_string = raw_string.replaceAll('.', '');
                            let sub_total = parseInt(no_dots_string);
                            temp_total = temp_total + sub_total;
                        }
                    }
                    total_arr.push(temp_total);
                }
                let foot = $('#depreciation').find('tfoot tr');
                for (let index = 0; index < total_arr.length; index++) {
                    let sub_foot = '<th>' + total_arr[index].toLocaleString('id', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }) + '</th>';
                    foot.append(sub_foot);
                }
            });
        </script>
    @endpush
@endsection
