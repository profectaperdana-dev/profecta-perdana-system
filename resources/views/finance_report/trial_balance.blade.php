@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
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
                            <div class="col-lg-4 col-12 mb-2">
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
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-borderless table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Post</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="text-center table-info">
                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right text-dark">Debit</th>
                                        <th class="text-right text-dark">Credit</th>
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

                function load_data(from_date = '', to_date = '', warehouse = '', adjustment = 'after') {
                    $('#example1').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('/finance/report/trial_balance') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                warehouse: warehouse,
                                adjustment: adjustment
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
                                data: 'acc_code',
                                name: 'acc_code',
                            },
                            {
                                className: 'fw-bold',
                                data: 'acc_name',
                                name: 'acc_name',

                            },
                            {
                                className: 'fw-bold text-center',
                                data: 'post',
                                name: 'post',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'sub_debit',
                                name: 'sub_debit',

                            },
                            {
                                className: 'fw-bold text-end',
                                data: 'sub_credit',
                                name: 'sub_credit',

                            },

                        ],
                        order: [

                        ],
                        dom: 'Bfrtip',
                        buttons: [
                            // {
                            //     text: '<i class="fa fa-print"></i>',
                            //     title: 'Data Invoice',
                            //     messageTop: '<h5>{{ $title }} ({{ date('l H:i A, d F Y ') }})</h5><br>',
                            //     messageBottom: '<strong style="color:red;">*Please select only the type of column needed when printing so that the print is neater</strong>',
                            //     extend: 'print',
                            //     customize: function(win) {
                            //         $(win.document.body)
                            //             .css('font-size', '10pt')
                            //             .prepend(
                            //                 '<img src="{{ asset('images/logo.png') }}" style="position:absolute; top:300; left:150; bottom:; opacity: 0.2;"/>'
                            //             );
                            //         $(win.document.body)
                            //             .find('thead')
                            //             .css('background-color', 'rgba(211,225,222,255)')
                            //             .css('font-size', '8pt')
                            //         $(win.document.body)
                            //             .find('tbody')
                            //             .css('background-color', 'rgba(211,225,222,255)')
                            //             .css('font-size', '8pt')
                            //         $(win.document.body)
                            //             .find('table')
                            //             .css('width', '100%')
                            //     },
                            //     orientation: 'landscape',
                            //     pageSize: 'legal',
                            //     rowsGroup: [0],
                            //     exportOptions: {
                            //         columns: ':visible'
                            //     },
                            // },
                            {
                                extend: 'excel',
                                text: '<i class="fa fa-download"></i>',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                filename: function() {
                                    function formatDate(date) {
                                        // Split the date string into day, month, and year components
                                        let dateParts = date.split('-');

                                        // Create a new Date object using the year, month, and day components
                                        let dateObject = new Date(dateParts[2], dateParts[1] - 1,
                                            dateParts[0]);

                                        // Format the date as "yyyy-mm-dd"
                                        let year = dateObject.getFullYear();
                                        let month = (dateObject.getMonth() + 1).toString().padStart(2,
                                            '0');
                                        let day = dateObject.getDate().toString().padStart(2, '0');
                                        let formattedDate = `${day}-${month}-${year}`;

                                        return formattedDate;
                                    }

                                    var from_date = formatDate($('#from_date').val());
                                    var to_date = formatDate($('#to_date').val());
                                    let warehouse = $('#warehouse option:selected').text().trim();
                                    if (warehouse == '') {
                                        warehouse = 'AllWarehouse'
                                    }
                                    // var currentDate = new Date();
                                    // var day = currentDate.getDate();
                                    // var month = currentDate.getMonth() + 1;
                                    // var year = currentDate.getFullYear();
                                    return 'TrialBalance_(' + from_date + ' to ' + to_date + ')_' +
                                        warehouse;
                                },
                                customize: function(xlsx) {
                                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                                    // Get the table footer values
                                    var footerValues = [];
                                    $('#example1 tfoot th').each(function() {
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
                            }
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            // DEBIT
                            var visibleData = api.column(3).nodes().to$().map(function() {
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
                            $(api.column(3).footer()).html(totalPPN.toLocaleString('en', {}));

                            // CREDIT
                            var visibleData = api.column(4).nodes().to$().map(function() {
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
                            $(api.column(4).footer()).html(totalPPN.toLocaleString('en', {}));

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
                    let adjustment = $('#adjustment').val();
                    // console.log(adjustment[0]);
                    if (from_date != '' && to_date != '' && warehouse != '') {
                        $('#example1').DataTable().destroy();
                        load_data(from_date, to_date, warehouse, adjustment[0]);
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

            });
        </script>
    @endpush
@endsection
