@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }

            /* .table-bordered {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                border-width: 10px !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date" data-value="{{ date('d-m-Y') }}"
                                        name="to_date" autocomplete="off">
                                </div>
                            </div>
                            {{-- <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Account</label>
                                <div class="input-group">
                                    <select name="account" id="account" multiple class="form-control selectMulti">
                                        @foreach ($all_account as $account)
                                            <option value="{{ $account['coa_code'] }}">
                                                {{ $account['coa_code'] }} - {{ $account['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="warehouse" id="warehouse" multiple class="form-control selectMulti"
                                        required>
                                        @foreach ($all_warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                                @if ($warehouse->id == 1) selected @endif>
                                                {{ $warehouse->warehouses }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Show</label>
                                <div class="input-group">
                                    <select name="show" id="show" multiple class="form-control selectMulti">
                                        <option value="all" selected>
                                            All
                                        </option>
                                        <option value="non-zero">
                                            Non-zero
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary form-control text-white" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning form-control text-white" name="refresh"
                                        id="refresh">Refresh</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-4">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    {{-- <input type="hidden" value="{{ $grouped_jurnal->count() }}" id="count"> --}}
                                    <button id="exportToExcel" class="btn text-white form-control btn-primary">Export
                                        All</button>
                                </div>
                            </div>
                        </div>


                        <hr class="fw-bold text-success">
                        <div class="row">
                            <div class="mb-5 col-lg-12 ">

                                <div class="fw-bold">Modal Awal
                                </div>
                                <div class="">
                                    <table
                                        class=" table-bordered table-sm text-capitalize table-striped bg-light text-black"
                                        style="width:100%;border:1px solid black !important;" id="modal-awal">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Account</th>
                                                <th></th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody style="">

                                        </tbody>
                                        {{-- <tfoot>
                                            <tr>
                                                <th>TOTAL MODAL AWAL</th>
                                                <th></th>
                                                <th class="sub_total_modal_awal"></th>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </div>
                            </div>

                            <div class="mb-5 col-lg-12 ">

                                <div class="fw-bold">Penambahan
                                </div>
                                <div class="">
                                    <table
                                        class=" table-bordered table-sm text-capitalize table-striped bg-light text-black"
                                        style="width:100%;border:1px solid black !important;" id="penambahan">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Account</th>
                                                <th></th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody style="">

                                        </tbody>
                                        {{-- <tfoot>
                                            <tr>
                                                <th>TOTAL PENAMBAHAN</th>
                                                <th></th>
                                                <th class="sub_total_penamabahan"></th>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-12 ">

                                <div class="fw-bold">Pengurangan
                                </div>
                                <div class="">
                                    <table
                                        class=" table-bordered table-sm text-capitalize table-striped bg-light text-black"
                                        style="width:100%;border:1px solid black !important;" id="pengurangan">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Account</th>
                                                <th></th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody style="">

                                        </tbody>
                                        {{-- <tfoot>
                                            <tr>
                                                <th>TOTAL PENGURANGAN</th>
                                                <th></th>
                                                <th class="sub_total_pengurangann"></th>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </div>
                            </div>

                            <div class="row fw-bold border mb-5 mt-0">
                                <div class="col-lg-6">TOTAL PENAMBAHAN/PENGURANGAN</div>
                                <div class="col-lg-6 text-end total_penambahan"></div>

                            </div>
                            <div class="row fw-bold border mb-5 mt-0">
                                <div class="col-lg-6">MODAL AKHIR</div>
                                <div class="col-lg-6 text-end modal_akhir"></div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <!-- Add the Papaparse library for parsing CSV data -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
        <!-- Add the SheetJS library for exporting to Excel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(document).ready(function() {
                $('.datepicker-here').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });

                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                function parseDate(date) {
                    let now = date;
                    // Format the date as "dd-mm-yyyy"
                    let day = now.getDate().toString().padStart(2, '0');
                    let month = (now.getMonth() + 1).toString().padStart(2, '0');
                    let year = now.getFullYear();
                    let formattedDate = `${day}-${month}-${year}`;
                    return formattedDate;
                }
                document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();


                function load_data(from_date = '', to_date = '', warehouse = '', show = 'all') {
                    var tableData = [];

                    // console.log($(this).val());
                    let modal_awal = $('#modal-awal').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        searching: false,
                        ajax: {
                            url: "{{ url('/finance/report/capital_change/table') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                warehouse: warehouse,
                                type: "modal_awal"
                            }
                        },
                        columns: [{
                                data: 'acc',
                                name: 'acc',

                            },
                            {
                                data: 'total_left',
                                name: 'total_left',

                            },
                            {
                                className: 'total_sheet',
                                // className: 'text-end',
                                data: 'total_right',
                                name: 'total_right',

                            },


                        ],
                        order: [

                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            // DEBIT
                            var visibleData = api.column(2).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                // console.log(data);
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(2).footer()).html(totalPPN.toLocaleString('en', {}));


                        },
                        initComplete: function() {
                            // initComplete logic
                            // You can update the 'profit' element here
                            let current_profit = $('.modal_awal').text();
                            if (current_profit == '' || current_profit == undefined) {
                                current_profit = 0
                            } else {
                                current_profit = parseFloat(current_profit.replace(/,/g, ''))
                            }

                            new_profit = parseFloat($(this).find('.sub_modal_awal')
                                .text()
                                .replace(/,/g,
                                    ''));


                            $('.total_aktiva').text((current_profit + new_profit).toLocaleString(
                                'en', {}));

                            let showAll = show === 'all';

                            // Iterate through each row and hide rows with sub_total <= 0 if show is not 'all'
                            $(this).find('tbody tr').each(function() {
                                let subTotal = parseFloat($(this).find(
                                        'td.total_sheet').text()
                                    .replace(/,/g, ''));
                                if (!showAll && subTotal == 0) {
                                    $(this).hide();
                                }
                            });

                        },
                        // dom: 'Bfrtip',
                        // pageLength: -1,
                        // buttons: [{
                        //     extend: 'excel',
                        //     text: '<i class="fa fa-download"></i>',
                        //     exportOptions: {
                        //         columns: ':visible'
                        //     }
                        // }],

                    });

                    let penambahan = $('#penambahan').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": false,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('/finance/report/capital_change/table') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                warehouse: warehouse,
                                type: "penambahan"
                            }
                        },
                        columns: [{
                                data: 'acc',
                                name: 'acc',

                            },
                            {
                                data: 'total_left',
                                name: 'total_left',

                            },
                            {
                                className: 'total_sheet',
                                data: 'total_right',
                                name: 'total_right',

                            },


                        ],
                        order: [

                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            // DEBIT
                            var visibleData = api.column(2).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                // console.log(data);
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(1).footer()).html(totalPPN.toLocaleString('en', {}));


                        },
                        // initComplete: function() {
                        //     // initComplete logic
                        //     // You can update the 'profit' element here
                        //     let current_profit = $('.total_kewajiban').text();
                        //     if (current_profit == '' || current_profit == undefined) {
                        //         current_profit = 0
                        //     } else {
                        //         current_profit = parseFloat(current_profit.replace(/,/g, ''))
                        //     }

                        //     new_profit = parseFloat($(this).find('.sub_total_kewajiban')
                        //         .text()
                        //         .replace(/,/g,
                        //             ''));


                        //     $('.total_kewajiban').text((current_profit + new_profit).toLocaleString(
                        //         'en', {}));
                        //     let showAll = show === 'all';

                        //     // Iterate through each row and hide rows with sub_total <= 0 if show is not 'all'
                        //     $(this).find('tbody tr').each(function() {
                        //         let subTotal = parseFloat($(this).find(
                        //                 'td.total_sheet').text()
                        //             .replace(/,/g, ''));
                        //         if (!showAll && subTotal == 0) {
                        //             $(this).hide();
                        //         }
                        //     });

                        // },
                        // dom: 'Bfrtip',
                        // pageLength: -1,
                        // buttons: [{
                        //     extend: 'excel',
                        //     text: '<i class="fa fa-download"></i>',
                        //     exportOptions: {
                        //         columns: ':visible'
                        //     }
                        // }],

                    });

                    let pengurangan = $('#pengurangan').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": false,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('/finance/report/capital_change/table') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                warehouse: warehouse,
                                type: "pengurangan"
                            }
                        },
                        columns: [{
                                data: 'acc',
                                name: 'acc',

                            },
                            {
                                data: 'total_left',
                                name: 'total_left',

                            },
                            {
                                className: 'total_sheet',
                                data: 'total_right',
                                name: 'total_right',

                            },


                        ],
                        order: [

                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            // DEBIT
                            var visibleData = api.column(2).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                // console.log(data);
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(1).footer()).html(totalPPN.toLocaleString('en', {}));


                        },
                        // initComplete: function() {
                        //     // initComplete logic
                        //     // You can update the 'profit' element here
                        //     let current_profit = $('.total_aktiva').text();
                        //     if (current_profit == '' || current_profit == undefined) {
                        //         current_profit = 0
                        //     } else {
                        //         current_profit = parseFloat(current_profit.replace(/,/g, ''))
                        //     }

                        //     new_profit = parseFloat($(this).find('.sub_total_aktiva')
                        //         .text()
                        //         .replace(/,/g,
                        //             ''));


                        //     $('.total_aktiva').text((current_profit + new_profit).toLocaleString(
                        //         'en', {}));

                        //     let showAll = show === 'all';

                        //     // Iterate through each row and hide rows with sub_total <= 0 if show is not 'all'
                        //     $(this).find('tbody tr').each(function() {
                        //         let subTotal = parseFloat($(this).find(
                        //                 'td.total_sheet').text()
                        //             .replace(/,/g, ''));
                        //         if (!showAll && subTotal == 0) {
                        //             $(this).hide();
                        //         }
                        //     });

                        // },
                        // dom: 'Bfrtip',
                        // pageLength: -1,
                        // buttons: [{
                        //     extend: 'excel',
                        //     text: '<i class="fa fa-download"></i>',
                        //     exportOptions: {
                        //         columns: ':visible'
                        //     }
                        // }],

                    });

                }

                // Function to concatenate and export the DataTables to Excel
                function exportTablesToExcel() {
                    var allData = [];
                    var total_aktiva = 0;
                    var total_ekuitas = 0;
                    let AllNameAccount = ['Aktiva Lancar', 'Kewajiban', 'Aktiva Tetap', 'Modal'];
                    // console.log($('#jurnal-parent').html());
                    let k = 0;
                    var headerAdded = false;

                    var dataTable = $('#aktiva_lancar').DataTable();
                    var data = dataTable.rows().data()
                        .toArray(); // Get all rows data (including hidden ones)
                    data = data.map(row => [row.code, row.acc, row.total]); // Filter out only the desired columns

                    var dataTable_kewajiban = $('#kewajiban').DataTable();
                    var data_kewajiban = dataTable_kewajiban.rows().data()
                        .toArray(); // Get all rows data (including hidden ones)
                    data_kewajiban = data_kewajiban.map(row => [row.code, row.acc, row
                        .total
                    ]); // Filter out only the desired columns
                    // console.log(data);
                    if (data.length > 0 && data_kewajiban.length > 0) {
                        var total = calculateTotal(data);
                        var total_kewajiban = calculateTotal(data_kewajiban);
                        if (!headerAdded) {
                            allData.push([AllNameAccount[0], "", "", "", AllNameAccount[1]]);
                            allData.push(["Code", "Account", "#", "", "Code", "Account",
                                "#"
                            ]); // Add the header row for the columns
                            headerAdded = true;
                        } else {
                            allData.push([""]); // Add an empty row to separate groups

                            allData.push([AllNameAccount[0], "", "", "", AllNameAccount[1]]);
                            allData.push(["Code", "Account", "#", "", "Code", "Account",
                                "#"
                            ]); // Add the header row for the columns
                        }
                        let kewajiban_aktiva = data.map((itemA, index) => itemA.concat("", data_kewajiban[index]));
                        // console.log(kewajiban_aktiva);
                        // allData.push([...data, "", ...data_kewajiban])
                        allData = allData.concat(kewajiban_aktiva);
                        allData.push(["TOTAL AKTIVA LANCAR", "", total, "", "TOTAL KEWAJIBAN", "", total_kewajiban]);
                        total_aktiva += parseFloat(total.replace(/,/g, ''));
                        total_ekuitas += parseFloat(total_kewajiban.replace(/,/g, ''));

                    }


                    var dataTable_aktiva_tetap = $('#aktiva_tetap').DataTable();
                    var data_aktiva_tetap = dataTable_aktiva_tetap.rows().data()
                        .toArray(); // Get all rows data (including hidden ones)
                    data_aktiva_tetap = data_aktiva_tetap.map(row => [row.code, row.acc, row
                        .total
                    ]); // Filter out only the desired columns

                    var dataTable_modal = $('#modal').DataTable();
                    var data_modal = dataTable_modal.rows().data()
                        .toArray(); // Get all rows data (including hidden ones)
                    data_modal = data_modal.map(row => [row.code, row.acc, row
                        .total
                    ]); // Filter out only the desired columns

                    if (data_aktiva_tetap.length > 0 && data_modal.length > 0) {
                        var total_aktiva_tetap = calculateTotal(data_aktiva_tetap);
                        var total_modal = calculateTotal(data_modal);

                        if (!headerAdded) {
                            allData.push([AllNameAccount[2], "", "", "", AllNameAccount[3]]);
                            allData.push(["Code", "Account", "#", "", "Code", "Account",
                                "#"
                            ]); // Add the header row for the columns
                            headerAdded = true;
                        } else {
                            allData.push([""]); // Add an empty row to separate groups

                            allData.push([AllNameAccount[2], "", "", "", AllNameAccount[3]]);
                            allData.push(["Code", "Account", "#", "", "Code", "Account",
                                "#"
                            ]); // Add the header row for the columns
                        }
                        let aktiva_modal = data_aktiva_tetap.map((itemA, index) => itemA.concat("", data_modal[index]));

                        allData = allData.concat(aktiva_modal);
                        allData.push(["TOTAL AKTIVA TETAP", "", total_aktiva_tetap, "", "TOTAL EKUITAS", "",
                            total_modal
                        ]);
                        total_aktiva += parseFloat(total_aktiva_tetap.replace(/,/g, ''));
                        total_ekuitas += parseFloat(total_modal.replace(/,/g, ''));

                    }

                    allData.push(["TOTAL AKTIVA", "", total_aktiva.toLocaleString('en', {}), "",
                        "TOTAL KEWAJIBAN DAN EKUITAS", "", total_ekuitas.toLocaleString('en', {})
                    ]);

                    // Step 2: Create a new worksheet
                    var worksheetData = allData;
                    var worksheet = XLSX.utils.aoa_to_sheet(worksheetData);

                    // Step 3: Create a new workbook and add the worksheet to it
                    var workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, "Merged Data");

                    // Step 4: Export the workbook to Excel
                    var excelBuffer = XLSX.write(workbook, {
                        bookType: "xlsx",
                        type: "array"
                    });

                    // Step 5: Save the Excel file
                    var fileName = "Balance Sheet.xlsx";
                    saveExcelFile(excelBuffer, fileName);
                }

                // Function to save the Excel file
                function saveExcelFile(buffer, fileName) {
                    var blob = new Blob([buffer], {
                        type: "application/octet-stream"
                    });
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = url;
                    a.download = fileName;
                    a.click();

                    // Clean up the URL object
                    setTimeout(function() {
                        URL.revokeObjectURL(url);
                    }, 100);
                }

                function calculateTotal(data) {
                    var total = 0;
                    for (var i = 0; i < data.length; i++) {
                        // console.log(data[i][1] + ": " + data[i][2])
                        total += parseFloat((data[i][2]).toString().replace(/,/g, '')) ||
                            0; // Assuming the total is in the third column (index 2)
                    }
                    return total.toLocaleString('en', {});
                }

                // Attach the function to the button click event
                document.getElementById("exportToExcel").addEventListener("click", exportTablesToExcel);
                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    let warehouse = $('#warehouse').val();
                    let show = $('#show').val()[0];
                    if (from_date != '' && to_date != '' && warehouse != '') {
                        $('#aktiva_lancar').DataTable().destroy();
                        $('#kewajiban').DataTable().destroy();
                        $('#aktiva_tetap').DataTable().destroy();
                        $('#modal').DataTable().destroy();

                        load_data(from_date, to_date, warehouse, show);

                    } else {
                        alert('Date and Warehouse are required');
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#example1').DataTable().destroy();
                    load_data();
                });
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    let modal_id = $(this).attr('data-bs-target');
                    $(function() {

                        let validator = $('form.needs-validation').jbvalidator({
                            errorMessage: true,
                            successClass: true,
                            language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                        });

                        validator.reload();
                    })
                    $(modal_id).find(".role-acc, .job-acc, .warehouse-acc").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });
                    $('.total').on('keyup', function() {
                        var selection = window.getSelection().toString();
                        if (selection !== '') {
                            return;
                        }
                        // When the arrow keys are pressed, abort.
                        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                            return;
                        }
                        var $this = $(this);
                        // Get the value.
                        var input = $this.val();
                        var input = input.replace(/[\D\s\._\-]+/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return (input === 0) ? "" : input.toLocaleString("id-ID");
                        });
                        $this.next().val(input);

                    });


                    $(".account").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });
                });
            });
        </script>
    @endpush
@endsection
