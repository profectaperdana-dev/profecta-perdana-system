@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')

        <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }

            .table.dataTable table,
            th,
            td {

                vertical-align: middle !important;
            }

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
                <div class="col-sm-12">
                    <h3 class="font-weight-bold">{{ $title }}</h3>

                </div>
            </div>
        </div>
    </div>
    <div></div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-6 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date" data-value="{{ date('d-m-Y') }}"
                                        name="to_date" autocomplete="on">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="warehouse_id" id="warehouse" multiple class="form-control selectMulti">
                                        {{-- <option value="" selected>--ALL--</option> --}}
                                        @foreach ($warehouse as $row)
                                            <option value="{{ $row->id }}">{{ $row->warehouses }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Vendor</label>
                                <div class="input-group">
                                    <select name="supplier_id" id="supplier" multiple class="form-control selectMulti">
                                        {{-- <option value="" selected>--ALL--</option> --}}
                                        @foreach ($suppliers as $row)
                                            <option value="{{ $row->id }}">{{ $row->nama_supplier }}</option>
                                        @endforeach
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
                            <table style="font-size: 10pt" id="dataTable" class="table text-nowrap table-striped table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>Purchase Number</th>
                                        <th>Order Date</th>
                                        <th>TOP</th>
                                        <th>Due Date</th>
                                        <th>Warehouse</th>
                                        <th>Vendor</th>
                                        <th>Remark</th>
                                        <th>Debt</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <th colspan="7">Total</th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <input type="text" hidden value="{{ $ }}"> --}}
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
        <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js">
        </script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
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
                document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());
                $('.selectMulti').select2({
                    placeholder: 'Select Option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                load_data();

                function load_data(from_date = '', to_date = '', warehouse = '', supplier = '') {

                    var table = $('#dataTable').DataTable({
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
                        destroy: true,
                        // rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 25, 26, 27],
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/report_debt') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                warehouse_id: warehouse,
                                supplier_id: supplier
                            }
                        },
                        columns: [

                            // {
                            //     width: '5%',
                            //     data: 'DT_RowIndex',
                            //     name: 'DT_Row_Index',
                            //     "className": "text-center",
                            //     orderable: false,
                            //     searchable: false
                            // },
                            {
                                className: 'fw-bold text-center',
                                data: 'order_number',
                                name: 'order_number'

                            },
                            {
                                data: 'order_date',
                                name: 'order_date'

                            },
                            {
                                className: 'text-center',
                                data: 'top',
                                name: 'top'

                            },
                            {
                                data: 'due_date',
                                name: 'due_date'

                            },
                            {
                                data: 'warehouse_id',
                                name: 'warehouse_id'

                            },
                            {
                                data: 'supplier_id',
                                name: 'supplier_id'

                            },
                            {
                                data: 'remark',
                                name: 'remark'
                            },
                            {
                                className: 'text-end',
                                data: 'debt',
                                name: 'debt'
                            },


                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api(),
                                data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function(i) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column(7)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b.replace(/\./g, ''));
                                }, 0);


                            // Update footer
                            $(api.column(7).footer()).html(
                                total.toLocaleString()
                            );
                        },
                        order: [],
                        dom: 'Bfrtip',

                        buttons: [{
                                text: '<i class="fa-solid fa-arrows-turn-right"></i>',
                                attr: {
                                    id: 'increaseLeft'
                                },

                            },
                            {
                                text: '<i class="fa-solid fa-clock-rotate-left"></i>',
                                attr: {
                                    id: 'decreaseLeft'
                                },

                            },
                            {
                                text: '<i class="fa fa-print"></i>',

                                title: 'Debt Report Data',
                                messageTop: '<h5>{{ $title }} ({{ date('l H:i A, d F Y ') }})</h5><br>',
                                messageBottom: '<strong style="color:red;">*Please select only the type of column needed when printing so that the print is neater</strong>',
                                extend: 'print',
                                customize: function(win) {
                                    $(win.document.body)
                                        .css('font-size', '10pt')
                                        .prepend(
                                            '<img src="{{ asset('images/logo.png') }}" style="position:absolute; top:300; left:150; bottom:; opacity: 0.2;"/>'
                                        );
                                    $(win.document.body)
                                        .find('thead')
                                        .css('background-color', 'rgba(211,225,222,255)')
                                        .css('font-size', '8pt')
                                    $(win.document.body)
                                        .find('tbody')
                                        .css('background-color', 'rgba(211,225,222,255)')
                                        .css('font-size', '8pt')
                                    $(win.document.body)
                                        .find('table')
                                        .css('width', '100%')
                                },
                                orientation: 'landscape',
                                pageSize: 'legal',
                                rowsGroup: [0],
                                exportOptions: {


                                    columns: ':visible'
                                },
                            },
                            {
                                text: '<i class="fa fa-download"></i>',

                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            'colvis'
                        ],

                    });

                    $(document).find('#increaseLeft').on('click', function() {
                        // console.log('test');
                        var currLeft = table.fixedColumns().left();
                        if (currLeft < 9) {
                            table.fixedColumns().left(currLeft + 1);
                            $('#click-output').prepend(
                                '<div>New Left: ' + (+currLeft + 1) + '</div>'
                            );
                        }
                    })

                    $('button#decreaseLeft').on('click', function() {
                        var currLeft = table.fixedColumns().left();
                        if (currLeft > 0) {
                            table.fixedColumns().left(currLeft - 1);
                            $('#click-output').prepend(
                                '<div>New Left: ' + (+currLeft - 1) + '</div>'
                            );
                        }
                    })


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
                    var warehouse = $('#warehouse').val()[0];
                    var supplier = $('#supplier').val()[0];
                    if (from_date != '' && to_date != '') {
                        load_data(from_date, to_date, warehouse, supplier);
                    } else {
                        alert('Both Date is required');
                    }
                });
                $('#refresh').click(function() {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });


            });
        </script>
    @endpush
@endsection
