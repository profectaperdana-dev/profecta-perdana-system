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

                            <div class=" col-md-12 col-lg-4 form-group">
                                <label class="text-end">Product</label>
                                <div class="input-group">
                                    <select name="" id="product" class="form-control selectMulti" multiple>
                                        @foreach ($product as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 form-group">
                                <label class=" text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="" id="warehouse" class="form-control selectMulti" multiple>
                                        {{-- <option value="" selected>--ALL--</option> --}}
                                        @foreach ($all_warehouse as $row)
                                            <option value="{{ $row->id }}">{{ $row->warehouses }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-12 form-group">
                                <label class="text-end">Choose Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-6 col-lg-2 form-group">
                                <label class=" text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary form-control text-white" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2 form-group">
                                <label class=" text-end">&nbsp;</label>
                                <div class="input-group">
                                    <a class="btn btn-warning form-control text-white" href="{{ url()->current() }}"
                                        name="refresh" id="refresh">Reset</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table style="font-size: 10pt" id="dataTable" class="stripe row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Warehouse</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align:right">Total</th>
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
    @include('report.promotion_stock_modal')
    {{-- <input type="text" hidden value="{{ $ }}"> --}}
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
        <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js">
        </script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        @include('layouts.partials.multi-select')
        @include('layouts.partials.multi-select')

        <script>
            //on focus
            $(document).ready(function() {
                $('#material').focus();

            });
        </script>
        <script>
            $(document).ready(function() {
                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
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
            });
        </script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                //Datatable stock
                function load_data(from_date = '', warehouse = '', product = '') {

                    var table = $('#dataTable').DataTable({
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
                        destroy: true,
                        ajax: {
                            url: "{{ url('/material-promotion/stock/report') }}",
                            data: {
                                from_date: from_date,
                                product: product,
                                warehouse: warehouse,

                            }
                        },
                        columns: [{
                                width: '5%',
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center",
                                orderable: false,
                                searchable: false
                            },
                            {
                                "className": "fw-bold text-center",
                                data: 'nama_barang',
                                name: 'nama_barang'

                            },
                            {
                                "className": "text-center",
                                data: 'warehouse',
                                name: 'warehouse'

                            },
                            {
                                "className": "text-center",
                                data: 'qty',
                                name: 'qty'

                            }
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
                                .column(3)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Update footer
                            $(api.column(3).footer()).html(
                                total
                            );
                        },

                        dom: 'Bfrtip',
                        order: [],
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

                                title: 'Promotion Item Stock Report',
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
                        console.log('test');
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

                $(document).on('click', '.modal-trace', function() {
                    let stock_id = $(this).attr('data-stock');
                    let id_product = $(this).attr('data-product');
                    let id_warehouse = $(this).attr('data-warehouse');
                    let from_date_trace = $('#from_date').val();

                    // console.log(stock_id);
                    // console.log(id_warehouse);
                    // console.log(from_date_trace);

                    load_data_trace(from_date_trace, id_product, id_warehouse);
                    //Datatable stock trace
                    function load_data_trace(from_date = '', product = '', warehouse = '') {
                        $('#example' + stock_id).DataTable().destroy();

                        $('#example' + stock_id).DataTable({
                            destroy: true,
                            "lengthChange": false,
                            "bPaginate": false, // disable pagination
                            "bLengthChange": false, // disable show entries dropdown
                            processing: true,
                            serverSide: true,
                            "searching": false,
                            "ordering": false,
                            "info": false,
                            ajax: {
                                url: "{{ url('/report_stock_trace') }}",
                                data: {
                                    from_date: from_date,
                                    product_id: product,
                                    warehouse_id: warehouse
                                }
                            },
                            columns: [{
                                    width: '5%',
                                    data: 'DT_RowIndex',
                                    name: 'DT_Row_Index',
                                    "className": "text-center",
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    "className": "fw-bold text-center",
                                    data: 'date',
                                    name: 'date'

                                },
                                {
                                    "className": "fw-bold ",
                                    data: 'transaction',
                                    name: 'transaction'

                                },
                                {
                                    "className": "text-center",
                                    data: 'qty',
                                    name: 'qty',
                                    render: function(data, type, row) {
                                        if (row.transaction.includes("Indirect Return:") || row
                                            .transaction.includes("Direct Return:") || row
                                            .transaction.includes("Purchase:") || row
                                            .transaction.includes("to")) {
                                            return '<span class="text-success">' + data +
                                                '</span>';
                                        } else if (row.transaction.includes(
                                                "Indirect Sales:") ||
                                            row.transaction.includes("Direct Sales:") || row
                                            .transaction.includes("Purchase Return:") || row
                                            .transaction.includes("from")) {
                                            return '<span class="text-danger">' + data +
                                                '</span>';
                                        } else {
                                            return data;
                                        }
                                    }
                                },
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var api = this.api(),
                                    data;

                                let total = api
                                    .column(3)
                                    .data()
                                    .reduce(function(sum, value, index) {
                                        if (api.column(2).data()[index].includes(
                                                'Indirect Sales:')) {
                                            return sum - parseFloat(value);
                                        } else if (api.column(2).data()[index].includes(
                                                'Indirect Return:')) {
                                            return sum + parseFloat(value);
                                        } else if (api.column(2).data()[index].includes(
                                                'Purchase:')) {
                                            return sum + parseFloat(value);
                                        } else if (api.column(2).data()[index].includes(
                                                'Purchase Return:')) {
                                            return sum - parseFloat(value);
                                        } else if (api.column(2).data()[index].includes(
                                                'Direct Sales:')) {
                                            return sum - parseFloat(value);
                                        } else if (api.column(2).data()[index].includes(
                                                'Direct Return:')) {
                                            return sum + parseFloat(value);
                                        } else if (api.column(2).data()[index].includes(
                                                'from')) {
                                            return sum - parseFloat(value);
                                        } else if (api.column(2).data()[index].includes(
                                                'to')) {
                                            return sum + parseFloat(value);
                                        } else {
                                            return sum;
                                        }
                                    }, 0);

                                $(this.api().column(3).footer()).html(total);
                            },
                            order: [],
                            pageLength: -1,

                        });



                    }
                });

                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var warehouse = $('#warehouse').val();
                    var product = $('#product').val();
                    if (from_date != '') {
                        load_data(from_date, warehouse, product);
                        // load_data_trace(from_date);
                    } else {
                        $.notify({
                            title: 'Warning !',
                            message: 'Please Select Start Date & End Date'
                        }, {
                            type: 'warning',
                            allow_dismiss: true,
                            newest_on_top: true,
                            mouse_over: true,
                            showProgressbar: false,
                            spacing: 10,
                            timer: 3000,
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            offset: {
                                x: 30,
                                y: 30
                            },
                            delay: 1000,
                            z_index: 3000,
                            animate: {
                                enter: 'animated swing',
                                exit: 'animated swing'
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
