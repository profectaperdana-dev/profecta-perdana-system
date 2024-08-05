@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }
        </style>
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
                                    <tr class="text-center text-nowrap">
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
                                            <td class="text-center">{{ $item->asset_name }}</td>
                                            <td class="text-center">{{ $item->amount }}</td>
                                            <td class="text-center">{{ $item->lifetime }}</td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($item->acquisition_year)) }}
                                            </td>

                                            @php
                                                // Inisialisasi nilai sementara untuk biaya akuisisi
                                                $temp_cost = $item->acquisition_cost;
                                            @endphp

                                            @for ($i = 0; $i < $current_year - $smallest_year + 1; $i++)
                                                @if (date('Y', strtotime($item->acquisition_year)) == $smallest_year + $i)
                                                    {{-- Menampilkan biaya akuisisi pada tahun pertama --}}
                                                    <td class="text-end">{{ number_format($item->acquisition_cost) }}</td>
                                                @else
                                                    {{-- Menampilkan tanda strip (-) untuk tahun-tahun sebelumnya --}}
                                                    <td class="text-end">-</td>
                                                @endif

                                                @if (date('Y', strtotime($item->acquisition_year)) <= $smallest_year + $i)
                                                    @if (date('Y', strtotime($item->acquisition_year)) == $smallest_year + $i)
                                                        @php
                                                            // Perhitungan biaya untuk tahun akuisisi
                                                            $month = date('n', strtotime($item->acquisition_year));
                                                            $countmonth = 13 - intval($month);
                                                            $cost_per_month = $item->acquisition_cost / $item->lifetime;
                                                            $cost_current = $cost_per_month * $countmonth;
                                                            $check_cost = $temp_cost - $cost_current;
                                                        @endphp

                                                        {{-- Menampilkan biaya pada tahun akuisisi setelah menghitung --}}
                                                        @if ($check_cost <= 0 && $temp_cost != 0)
                                                            <td class="text-end">{{ number_format($temp_cost) }} </td>
                                                        @elseif ($check_cost <= 0 && $temp_cost <= 0)
                                                            {{-- Menampilkan tanda strip (-) jika biaya habis --}}
                                                            <td class="text-end">-</td>
                                                        @else
                                                            {{-- Menampilkan biaya depresiasi untuk tahun akuisisi --}}
                                                            <td class="text-end">{{ number_format($cost_current) }} </td>
                                                        @endif
                                                    @else
                                                        @php
                                                            // Perhitungan biaya depresiasi untuk tahun-tahun setelah akuisisi
                                                            $cost_per_month = $item->acquisition_cost / $item->lifetime;
                                                            $cost_current = $cost_per_month * 12;
                                                            $check_cost = $temp_cost - $cost_current;
                                                        @endphp

                                                        {{-- Menampilkan biaya depresiasi untuk tahun-tahun setelah akuisisi --}}
                                                        @if ($check_cost <= 0 && $temp_cost > 0)
                                                            <td class="text-end">{{ number_format($temp_cost) }} </td>
                                                        @elseif ($check_cost <= 0 && $temp_cost <= 0)
                                                            {{-- Menampilkan tanda strip (-) jika biaya habis --}}
                                                            <td class="text-end">-</td>
                                                        @else
                                                            {{-- Menampilkan biaya depresiasi untuk tahun-tahun setelah akuisisi --}}
                                                            <td class="text-end">{{ number_format($cost_current) }} </td>
                                                        @endif
                                                    @endif

                                                    @php
                                                        // Mengupdate nilai biaya sementara
                                                        if ($temp_cost > 0) {
                                                            $temp_cost = $temp_cost - $cost_current;
                                                        }
                                                    @endphp

                                                    {{-- Menampilkan tanda strip (-) jika biaya habis --}}
                                                    @if ($temp_cost <= 0)
                                                        <td class="text-end">-</td>
                                                    @else
                                                        {{-- Menampilkan biaya sisa setelah depresiasi --}}
                                                        <td class="text-end">{{ number_format($temp_cost) }} </td>
                                                    @endif
                                                @else
                                                    {{-- Menampilkan tanda strip (-) untuk tahun-tahun sebelum akuisisi --}}
                                                    <td class="text-end">-</td>
                                                    <td class="text-end">-</td>
                                                @endif
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th class=""></th>
                                    <th class=""></th>
                                    <th class=""></th>
                                    <th class="text-center">Total</th>

                                    @for ($i = 0; $i < $current_year - $smallest_year + 1; $i++)
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                        <th class="text-end"></th>
                                    @endfor
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
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

        <script
            src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js">
        </script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });

                var table_datatable = $('#depreciation').DataTable({
                    "language": {
                        "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                    },
                    "lengthChange": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true,
                    fixedColumns: {
                        leftColumns: 0,
                        rightColumns: 0
                    },
                    scrollCollapse: true,
                    paging: false,
                    //
                    fixedHeader: true,
                    pageLength: -1,
                    dom: 'Bfrtip', // Menambahkan tombol ekspor
                    buttons: [{
                            text: '<i class="icofont icofont-download-alt"></i>',

                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible'
                            },
                            charset: 'UTF-8',
                            customize: function(xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                                // Get the table footer values
                                var footerValues = [];
                                $('#depreciation tfoot th').each(function() {
                                    footerValues.push($(this).text());
                                });

                                // Add the footer row to the sheet data
                                var footerRow = sheet.getElementsByTagName('sheetData')[0]
                                    .appendChild(sheet.createElement('row'));
                                footerRow.setAttribute('r', sheet.getElementsByTagName('row')
                                    .length + 1);

                                // Add cells to the footer row
                                for (var i = 0; i < footerValues.length; i++) {
                                    var cell = footerRow.appendChild(sheet.createElement('c'));
                                    cell.setAttribute('r', String.fromCharCode(65 + i) + footerRow
                                        .getAttribute('r'));
                                    cell.setAttribute('t', 'inlineStr');
                                    var inlineStr = cell.appendChild(sheet.createElement('is'));
                                    var textNode = inlineStr.appendChild(sheet.createElement('t'));
                                    textNode.appendChild(sheet.createTextNode(footerValues[i]));
                                }
                            }

                        },
                        {
                            text: '<i class="icofont icofont-eye"></i> Columns',
                            extend: 'colvis',
                            postfixButtons: [
                                'colvisRestore'
                            ], // Tambahkan tombol untuk merestore ke default
                            collectionLayout: 'fixed one-column',
                            // collection: [{
                            //         text: '<i class="icofont icofont-eye"></i> Show All',
                            //         action: function() {
                            //             table_datatable.columns().visible(true);
                            //         }
                            //     },
                            //     {
                            //         text: '<i class="icofont icofont-eye-blocked"></i> Hide All',
                            //         action: function() {
                            //             table_datatable.columns().visible(false);
                            //         }
                            //     }
                            // ]
                        }
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        let rowLength = api.column(1).nodes().to$().map(function() {
                            return $(this).text();
                        }).toArray();
                        for (let id = 4; id < rowLength.length; id++) {
                            // PPN
                            var visibleData = api.column(id).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            // console.log(visibleData);
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '-';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(id).footer()).html(totalPPN.toLocaleString('en', {}));
                        }

                    },
                });


                // function getTabletoArray() {
                //     var table = $('#depreciation');
                //     var data = [];

                //     table.find('tr').each(function(i, el) {
                //         // no thead
                //         if (i != 0) {
                //             var $tds = $(this).find('td');
                //             var row = [];
                //             $tds.each(function(i, el) {
                //                 row.push($(this).text());
                //             });
                //             data.push(row);
                //         }

                //     });
                //     //no tfoot
                //     data.pop();

                //     return data;
                // }

                // let all_values = getTabletoArray();
                // // console.log(all_values[1].length);
                // let total_arr = [];
                // for (let i = 4; i < all_values[1].length; i++) {
                //     let temp_total = 0;
                //     for (let j = 1; j < all_values.length; j++) {
                //         let raw_string = all_values[j][i];
                //         if (raw_string == '-') {
                //             temp_total = temp_total + 0;
                //         } else {
                //             let no_dots_string = raw_string.replaceAll(',', '');
                //             let sub_total = parseInt(no_dots_string);
                //             temp_total = temp_total + sub_total;
                //         }
                //     }
                //     total_arr.push(temp_total);
                // }
                // // console.log(total_arr);
                // let foot = $('#depreciation').find('tfoot tr');
                // for (let index = 0; index < total_arr.length; index++) {
                //     let sub_foot =
                //         '<th class="text-center" rowspan="1" colspan="1" style="padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 159.688px;"><div class="dataTables_sizing" style="height:0;overflow:hidden;">' +
                //         total_arr[index].toLocaleString('en', {}) + '</div></th>';
                //     foot.append(sub_foot);
                // }
                // console.log(foot.html());
            });
        </script>
    @endpush
@endsection
