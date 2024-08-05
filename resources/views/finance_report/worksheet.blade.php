@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            th {
                vertical-align: middle;
            }
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
                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date" data-value="{{ date('d-m-Y') }}"
                                        name="to_date" autocomplete="on">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="warehouse" id="warehouse" multiple class="form-control selectMulti">
                                        @foreach ($all_warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                                @if ($warehouse->id == 1) selected @endif>
                                                {{ $warehouse->warehouses }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Adjustment</label>
                                <div class="input-group">
                                    <select name="adjustment" id="adjustment" multiple class="form-control selectMulti">
                                        <option value="after" selected>
                                            After
                                        </option>
                                        <option value="before">
                                            Before
                                        </option>
                                    </select>
                                </div>
                            </div> --}}
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

                            <div class="col-1">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button id="exportToExcel" class="btn text-white form-control btn-primary"><i
                                            class="fa fa-download"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="example1" class="display table table-sm table-borderless table-striped"
                                style="width: 100%">
                                <thead>
                                    <tr class="text-center">
                                        <th style="text-align: center;" rowspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Code</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" rowspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 25px; margin-right: 25px;">Name</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" rowspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 15px; margin-right: 15px;">SN</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" rowspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">POS</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" colspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold" style="margin-left: 25px; margin-right: 25px;">Trial
                                                    Balance</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" colspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Adjustment
                                                    Journal</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" colspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Adjusted Trial
                                                    Balance</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" colspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Profit & Loss</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;" colspan="2">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Balance Sheet</span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Debit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Credit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Debit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Credit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Debit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Credit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Debit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Credit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Debit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Credit</span>
                                            </div>
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="text-center table-info">
                                        <th></th>
                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
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

                function getStartOfYear() {
                    let now = new Date();
                    let year = now.getFullYear();
                    return new Date(year, 0, 1); // Month is zero-based, so 0 represents January
                }

                function getEndOfYear() {
                    let now = new Date();
                    let year = now.getFullYear();
                    return new Date(year, 11, 31); // Month is zero-based, so 11 represents December
                }

                function parseDate(date) {
                    let now = date;
                    // Format the date as "dd-mm-yyyy"
                    let day = now.getDate().toString().padStart(2, '0');
                    let month = (now.getMonth() + 1).toString().padStart(2, '0');
                    let year = now.getFullYear();
                    let formattedDate = `${day}-${month}-${year}`;
                    return formattedDate;
                }
                // Get the current date


                // Set the value of the input element
                document.querySelector('input[name="from_date"]').value = parseDate(getStartOfYear());
                document.querySelector('input[name="to_date"]').value = parseDate(getEndOfYear());
                load_data();

                function load_data(from_date = '', to_date = '', warehouse = '') {
                    $('#example1').DataTable({
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
                        scrollY: 400,
                        scrollX: true,
                        scrollCollapse: true,
                        paging: false,
                        "fixedHeader": true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('/finance/report/worksheet') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                warehouse: warehouse,
                            }
                        },
                        // error: function(xhr, error, thrown) {
                        //     if (xhr.status == 401) {
                        //         // handle error ketika session habis atau expired
                        //         alert("Session Anda telah habis. Silakan login kembali.");
                        //     } else {
                        //         // handle error lainnya
                        //         alert("Terjadi kesalahan saat memuat data.");
                        //     }
                        // },
                        columns: [{
                                className: 'fw-bold text-center',
                                data: 'code',
                                name: 'code',
                            },
                            {
                                className: 'fw-bold',
                                data: 'name',
                                name: 'name',

                            },
                            {
                                className: 'fw-bold text-center',
                                data: 'sn',
                                name: 'sn',

                            },
                            {
                                className: 'fw-bold text-center',
                                data: 'pos',
                                name: 'pos',

                            },

                            {
                                className: 'fw-bold text-end',
                                data: 'tb_debit',
                                name: 'tb_debit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'tb_credit',
                                name: 'tb_credit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'aj_debit',
                                name: 'aj_debit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'aj_credit',
                                name: 'aj_credit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'atb_debit',
                                name: 'atb_debit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'atb_credit',
                                name: 'atb_credit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'pl_debit',
                                name: 'pl_debit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'pl_credit',
                                name: 'pl_credit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'bs_debit',
                                name: 'bs_debit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'bs_credit',
                                name: 'bs_credit',

                            },
                        ],
                        order: [

                        ],

                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            // Trial Balance DEBIT
                            var visibleData = api.column(4).nodes().to$().map(function() {
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
                            $(api.column(4).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Trial Balance CREDIT
                            var visibleData = api.column(5).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(5).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Adjustment Journal DEBIT
                            var visibleData = api.column(6).nodes().to$().map(function() {
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
                            $(api.column(6).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Adjustment Journal CREDIT
                            var visibleData = api.column(7).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(7).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Adjusted Trial Balance DEBIT
                            var visibleData = api.column(8).nodes().to$().map(function() {
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
                            $(api.column(8).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Adjusted Trial Balance CREDIT
                            var visibleData = api.column(9).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(9).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Profit Loss DEBIT
                            var visibleData = api.column(10).nodes().to$().map(function() {
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
                            $(api.column(10).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Profit Loss CREDIT
                            var visibleData = api.column(11).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(11).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Balance Sheet DEBIT
                            var visibleData = api.column(12).nodes().to$().map(function() {
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
                            $(api.column(12).footer()).html(totalPPN.toLocaleString('en', {}));

                            // Balance Sheet CREDIT
                            var visibleData = api.column(13).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '-') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(13).footer()).html(totalPPN.toLocaleString('en', {}));
                        },
                    });
                }
                $('#filter').click(function() {
                    function formatDate(date) {
                        // Split the date string into day, month, and year components
                        let dateParts = date.split('-');

                        // Create a new Date object using the year, month, and day components
                        let dateObject = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

                        // Format the date as "yyyy-mm-dd"
                        let year = dateObject.getFullYear();
                        let month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
                        let day = dateObject.getDate().toString().padStart(2, '0');
                        let formattedDate = `${year}-${month}-${day}`;

                        return formattedDate;
                    }

                    var from_date = formatDate($('#from_date').val());
                    var to_date = formatDate($('#to_date').val());
                    let warehouse = $('#warehouse').val();
                    // let adjustment = $('#adjustment').val();
                    // console.log(adjustment[0]);
                    if (from_date != '' && to_date != '' && warehouse != '') {
                        $('#example1').DataTable().destroy();
                        load_data(from_date, to_date, warehouse);
                    } else {
                        alert('Date and Warehouse are required');
                    }
                });

                $('#refresh').click(function() {
                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();
                    today = yyyy + '-' + mm + '-' + dd;
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#example1').DataTable().destroy();
                    load_data();
                });

                function exportTablesToExcel() {
                    var allData = [];
                    let AllNameAccount = [];
                    // console.log($('#jurnal-parent').html());
                    // $('.account-name').each(function() {
                    //     AllNameAccount.push($(this).text());
                    // });

                    let k = 0;
                    var headerAdded = false;

                    var dataTable = $('#example1').DataTable();
                    var data = dataTable.rows().data()
                        .toArray(); // Get all rows data (including hidden ones)
                    data = data.map(row => [row.code, row.name, row.sn, row.pos, row.tb_debit, row
                        .tb_credit, row.aj_debit, row.aj_credit, row.atb_debit, row.atb_credit, row.pl_debit,
                        row.pl_credit, row.bs_debit, row.bs_credit
                    ]); // Filter out only the desired columns
                    let total_tb_debit = calculateTotal(data, 4);
                    let total_tb_credit = calculateTotal(data, 5);
                    let total_aj_debit = calculateTotal(data, 6);
                    let total_aj_credit = calculateTotal(data, 7);
                    let total_atb_debit = calculateTotal(data, 8);
                    let total_atb_credit = calculateTotal(data, 9);
                    let total_pl_debit = calculateTotal(data, 10);
                    let total_pl_credit = calculateTotal(data, 11);
                    let total_bs_debit = calculateTotal(data, 12);
                    let total_bs_credit = calculateTotal(data, 4);
                    if (data.length > 0) {

                        // console.log(`total_tb:${t/otal_tb}`);
                        if (!headerAdded) {

                            allData.push([
                                "Code",
                                "Name",
                                "SN",
                                "POS",
                                "Trial Balance",
                                "",
                                "Adjustment Journal",
                                "",
                                "Adjusted Trial Balance",
                                "",
                                "Profit & Loss",
                                "",
                                "Balance Sheet",

                            ]); // Add the header row for the columns
                            allData.push(["", "", "", "", "Debit", "Credit", "Debit", "Credit", "Debit", "Credit",
                                "Debit", "Credit", "Debit", "Credit"
                            ]);

                            headerAdded = true;
                        } else {

                            allData.push([
                                "Code",
                                "Name",
                                "SN",
                                "POS",
                                "Trial Balance",
                                "",
                                "Adjustment Journal",
                                "",
                                "Adjusted Trial Balance",
                                "",
                                "Profit & Loss",
                                "",
                                "Balance Sheet",

                            ]); // Add the header row for the columns
                            allData.push(["", "", "", "", "Debit", "Credit", "Debit", "Credit", "Debit", "Credit",
                                "Debit", "Credit", "Debit", "Credit"
                            ]);
                        }

                        allData = allData.concat(data); // Concatenate the data for the current group
                    }

                    allData.push(["", "TOTAL", "", "", total_tb_debit.toLocaleString('en', {}), total_tb_credit
                        .toLocaleString('en', {}), total_aj_debit.toLocaleString('en', {}),
                        total_aj_credit.toLocaleString('en', {}), total_atb_debit.toLocaleString('en', {}),
                        total_atb_credit.toLocaleString('en', {}), total_pl_debit.toLocaleString('en', {}),
                        total_pl_credit.toLocaleString('en', {}), total_bs_debit.toLocaleString('en', {}),
                        total_bs_credit.toLocaleString('en', {}),
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
                    var fileName = "Worksheet.xlsx";
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

                function calculateTotal(data, coloumn) {
                    var total = 0;
                    for (var i = 0; i < data.length; i++) {
                        // console.log(data[i][1] + ": " + data[i][2])
                        total += parseFloat((data[i][coloumn]).toString().replace(/,/g, '')) ||
                            0; // Assuming the total is in the third column (index 2)
                    }
                    return total.toLocaleString('en', {});
                }

                document.getElementById("exportToExcel").addEventListener("click", exportTablesToExcel);


            });
        </script>
    @endpush
@endsection
