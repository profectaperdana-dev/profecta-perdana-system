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
                        @php
                            $now = date('Y-m-d');
                        @endphp
                        <div class="form-group row">
                            {{-- <div class="col-lg-6 col-12">
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
                            </div> --}}
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Customer</label>
                                <div class="input-group">
                                    <select name="customer" id="customer" multiple class="form-control selectMulti">
                                        {{-- <option value="" selected>--ALL--</option> --}}
                                        @foreach ($customers as $cust)
                                            <option value="{{ $cust->id }}">
                                                {{ $cust->code_cust }} - {{ $cust->name_cust }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Area</label>
                                <div class="input-group">
                                    <select name="warehouse" id="warehouse" multiple class="form-control selectMulti">
                                        {{-- <option value="" selected>--ALL--</option> --}}
                                        @foreach ($warehouses as $wr)
                                            <option value="{{ $wr->id }}">
                                                {{ $wr->warehouses }}
                                            </option>
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
                            <table style="font-size: 10pt" id="dataTable" class="stripe row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>Invoice Number</th>
                                        <th>Order Date</th>
                                        <th>Due Date</th>
                                        <th>Aging (Day)</th>
                                        <th>Customer</th>
                                        <th class="text-center">AR</th>
                                        <th>Remark</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:right">Total AR</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @foreach($retails as $data)
    <div class="modal" id="detailDirect{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        <div>
                            Order Number
                            {{ $data->order_number }}
                        </div>
                    </h6>
                    {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row justify-content-between">
                                    <div class="form-group fw-bold col-7 col-lg-5">
                                        Customer:
                                        @if (is_numeric($data->cust_name))
                                            @if ($data->customerBy == null)
                                                {{ $data->cust_name }}
                                            @else
                                                {{ $data->customerBy->name_cust }}
                                            @endif
                                        @else
                                            {{ $data->cust_name }}
                                        @endif
                                    </div>
                                    <div class="form-group fw-bold col-7 col-lg-3">
                                        Order Date: {{ date('d F Y', strtotime($data->order_date)) }}
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="form-group col-7 col-lg-5">
                                        Address:
                                        <address class="fw-bold"><i>{{ $data->address }},
                                                {{ $data->district }}</i>
                                        </address>
    
                                    </div>
                                    <div class="form-group col-7 col-lg-3">
                                        Email: {{ $data->cust_email }}
                                    </div>
    
                                    <div class="form-group col-12 col-lg-12">
                                        <label for="">Remark</label>
                                        <input class="form-control" value="{{ $data->remark }}" readonly>
                                    </div>
                                </div>
                                <div class="" id="formReturn">
    
                                    @foreach ($data->directSalesDetailBy as $item)
                                        <div class="row mx-auto py-2 rounded form-group mb-3"
                                            style="background-color: #f0e194">
                                            <div class="form-group col-12 col-lg-4">
                                                <label>Product
                                                </label>
                                                <input readonly class="form-control"
                                                    value="{{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}">
                                            </div>
                                            <div class="col-6 col-lg-2 form-group">
                                                <label>Qty</label>
                                                <input type="" class="form-control" readonly
                                                    value="{{ $item->qty }}" id="">
                                            </div>
                                            <div class="col-6 col-lg-1 form-group">
                                                <label>Disc (%)</label>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $item->discount }}" id="">
                                            </div>
                                            <div class="col-6 col-lg-2 form-group">
                                                <label>Disc (Rp)</label>
                                                <input type="" class="form-control" readonly
                                                    value="{{ $item->discount_rp }}" id="">
                                            </div>
    
                                            @php
                                                $retail_price = $item->price;
                                                if ($item->price == null) {
                                                    foreach ($item->retailPriceBy as $value) {
                                                        if ($value->id_warehouse == $data->warehouse_id) {
                                                            $retail_price = $value->harga_jual;
                                                            $ppn_cost = (float) $retail_price * 0.11;
                                                            $retail_price = (float) $retail_price + $ppn_cost;
                                                        }
                                                    }
                                                }
                                                
                                                $disc = (float) $item->discount / 100;
                                                $hargadisc = (float) $retail_price * $disc;
                                                $harga = (float) $retail_price - $hargadisc - $item->discount_rp;
                                                $total = (float) $harga * $item->qty;
                                            @endphp
                                            <div class="col-6 col-lg-3 form-group">
                                                <label>Amount (Rp)</label>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ number_format(round($total)) }}" id="">
                                            </div>
                                            <div>
                                                <ul class="list-group">
    
                                                    <li class="list-group-item fw-bold">
                                                        @foreach ($item->directSalesCodeBy as $code)
                                                            @if ($loop->iteration == $item->directSalesCodeBy->count())
                                                                @if ($loop->iteration == 1)
                                                                    Series Code:
                                                                    {{ '[ ' . $code->product_code . ' ]' }}
                                                                @else
                                                                    {{ '[ ' . $code->product_code . ' ]' }}
                                                                @endif
                                                            @else
                                                                Series Code:
                                                                {{ '[ ' . $code->product_code . ' ]' . ', ' }}
                                                            @endif
                                                        @endforeach
                                                    </li>
                                                    @if ($item->productBy->materials->nama_material == 'Tyre')
                                                        <li class="list-group-item fw-bold">
                                                            @foreach ($item->directSalesCodeBy as $code)
                                                                @if ($code->dotBy != null)
                                                                    @if ($loop->iteration == $item->directSalesCodeBy->count())
                                                                        @if ($loop->iteration == 1)
                                                                            DOT:
                                                                            {{ '[ ' . $code->dotBy->dot . ' ]' }}
                                                                        @else
                                                                            {{ '[ ' . $code->dotBy->dot . ' ]' }}
                                                                        @endif
                                                                    @else
                                                                        DOT:
                                                                        {{ '[ ' . $code->dotBy->dot . ' ]' . ', ' }}
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </li>
                                                    @endif
    
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                                <div class="row justify-content-between">
                                    <div class="form-group col-3">
                                        <strong>Total (Excl. PPN):</strong>
    
                                    </div>
                                    <div class="form-group col-4 col-lg-2 text-end ">
                                        <strong>{{ number_format(round($data->total_excl)) }}</strong>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="form-group col-3">
                                        <strong>PPN {{ $ppn * 100 }}%:</strong>
    
                                    </div>
                                    <div class="form-group col-4 col-lg-2 text-end ">
                                        <strong class="">{{ number_format(round($data->total_ppn)) }}</strong>
                                    </div>
                                </div>
                                <hr>
                                <div class="row justify-content-between">
                                    <div class="form-group col-3">
                                        <h5><strong>Total (Include PPN):</strong></h5>
    
                                    </div>
                                    <div class="form-group col-4 text-success col-lg-2 text-end ">
                                        <h5>
                                            <strong>
                                                {{ number_format(round($data->total_incl)) }}</strong>
                                        </h5>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="form-group col-3">
                                        <h5><strong>Return Total:</strong></h5>
    
                                    </div>
                                    <div class="form-group col-4 text-danger col-lg-2 text-end ">
                                        <h5>
                                            <strong>
                                                {{ number_format($data->directSalesReturnBy->sum('total')) }}</strong>
                                        </h5>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="form-group col-3">
                                        <h5><strong>Settlement Total:</strong></h5>
    
                                    </div>
                                    <div class="form-group col-4 text-danger col-lg-2 text-end ">
                                        <h5>
                                            <strong>
                                                {{ number_format($data->directSalesCreditBy->sum('amount')) }}</strong>
                                        </h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="row justify-content-between">
                                    <div class="form-group col-3">
                                        <h5><strong>AR Total:</strong></h5>
    
                                    </div>
                                    <div class="form-group col-4 fw-bold col-lg-2 text-end ">
                                        <h5>
                                            <strong>
    
                                                {{ number_format($data->total_incl - $data->directSalesReturnBy->sum('total') - $data->directSalesCreditBy->sum('amount')) }}
                                            </strong>
                                        </h5>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>

    @endforeach
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
                $('.selectMulti').select2({
                    placeholder: 'Select Option',
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
                // document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                // document.querySelector('input[name="to_date"]').value = parseDate(new Date());

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(customer = '', warehouse = '') {

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
                        // ordering: false,
                        ajax: {
                            url: "{{ url('/report_receivable_direct') }}",
                            data: {
                                warehouse: warehouse,
                                customer: customer,
                            }
                        },
                        columns: [{
                                className: 'fw-bold',
                                data: 'order_number',
                                name: 'order_number'

                            },
                            {
                                className: 'text-center',
                                data: 'order_date',
                                name: 'order_date'

                            },
                            {
                                className: 'text-center',
                                data: 'due_date',
                                name: 'due_date'

                            },
                            {
                                className: 'text-center',
                                data: 'day_passed',
                                name: 'day_passed'

                            },
                            {
                                className: 'text-center',
                                data: 'customer',
                                name: 'customer'

                            },
                            {
                                className: 'text-end',
                                data: 'receivable',
                                name: 'receivable'
                            },
                            {
                                data: 'remark',
                                name: 'remark'
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
                                .column(5)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b.replace(/\./g, ''));
                                }, 0);


                            // Update footer
                            $(api.column(5).footer()).html(
                                total.toLocaleString()
                            );

                            // $(api.column(4).footer()).addClass('text-end');
                        },
                        order: [

                        ],
                        dom: 'Bfrtip',
                        // lengthMenu: [
                        //     [-1],
                        //     ['Show All']
                        // ],
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
                                title: 'Receivable Report Data',
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

                    var customer = $('#customer').val();
                    var warehouse = $('#warehouse').val();

                    // console.log(customer);
                    if (customer != '' || warehouse != '') {
                        $('#dataTable').DataTable().destroy();
                        load_data(customer, warehouse);
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
                $('#refresh').click(function() {
                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();
                    today = yyyy + '-' + mm + '-' + dd;
                    // $('#from_date').val(parseDate(new Date()));
                    // $('#to_date').val(parseDate(new Date()));
                    $('#customer').val(null).trigger('change');
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });


            });
        </script>
    @endpush
@endsection
