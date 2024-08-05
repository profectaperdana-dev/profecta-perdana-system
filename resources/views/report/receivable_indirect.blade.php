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
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="" id="warehouse" multiple class="form-control selectMulti">
                                        @foreach ($warehouse as $row)
                                            <option value="{{ $row->id }}">{{ $row->warehouses }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
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
                                    <tr class="table-info">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Total AR</th>
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
    
    @foreach($invoices as $data)
        <div class="modal" id="detailData{{ $data->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
            data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title no-print" id="exampleModalLabel">Detail
                            {{ $data->order_number }}</h6>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label>
                                        Customer</label>
                                    <input type="text" readonly value="{{ $data->customerBy->name_cust }}"
                                        class="form-control">
                                </div>
                                <div class="col-lg-6 form-group mr-5">
                                    <label>Payment Method</label>
                                    <select disabled class="form-control">
                                        <option value="" selected>-Choose Payment-</option>
                                        <option value="1" @if ($data->payment_method == 1) selected @endif>
                                            Cash On Delivery
                                        </option>
                                        <option value="2" @if ($data->payment_method == 2) selected @endif>
                                            Cash Before Delivery
                                        </option>
                                        <option value="3" @if ($data->payment_method == 3) selected @endif>
                                            Credit
                                        </option>
                                    </select>
        
                                </div>
                                <div class="col-12 form-group">
                                    <label>Remark</label>
                                    <textarea class="form-control" name="remark" id="" cols="30" rows="1" readonly>{{ $data->remark }}</textarea>
                                </div>
                            </div>
                            <div class="form-group formSo-edit">
                                @foreach ($data->salesOrderDetailsBy as $detail)
                                    <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                        <div class="form-group col-12 col-lg-4">
                                            <label>Product</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}">
                                        </div>
        
                                        <div class="col-4 col-lg-1 form-group">
                                            <label>Qty</label>
                                            <input type="text" class="form-control cekQty-edit" readonly
                                                value="{{ $detail->qty }}" />
                                            <small class="text-danger qty-warning" hidden>The number of items exceeds
                                                the
                                                stock</small>
                                        </div>
                                        @php
                                            
                                            $price = str_replace(',', '.', $detail->productSales->harga_jual_nonretail);
                                            $sub_total = (float) $price * (float) $ppn;
                                            (float) ($harga = (float) $price + (float) $sub_total);
                                        @endphp
                                        <div class="col-4 col-lg-2 form-group">
                                            <label>Price</label>
                                            <input type="text" class="form-control" disabled
                                                value="{{ number_format(round($harga)) }}" />
                                        </div>
                                        <div class="col-4 col-lg-1 form-group">
                                            <label>Disc (%)</label>
                                            <input type="text" readonly min="0"
                                                class="form-control discount-append-edit" placeholder="Disc"
                                                value="{{ $detail->discount }}" />
        
                                        </div>
        
                                        <div class="col-6 col-lg-2 form-group">
                                            <label>Disc (Rp)</label>
                                            <input type="text" readonly class="form-control discount_rp" placeholder="Disc"
                                                value="{{ $detail->discount_rp }}" />
                                        </div>
                                        @php
                                            $disc = (float) $detail->discount / 100.0;
                                            $ppn_cost = (float) $price * (float) $ppn;
                                            $ppn_total = (float) $price + $ppn_cost;
                                            $disc_cost = (float) $ppn_total * $disc;
                                            $price_disc = (float) ($ppn_total - $disc_cost - $detail->discount_rp);
                                        @endphp
                                        <div class="col-6 col-lg-2 form-group">
                                            <label>Disc Price</label>
                                            <input type="text" class="form-control price" readonly
                                                value="{{ number_format(round($price_disc)) }}" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
        
                            <div class="form-group row">
                                <div class="col-lg-4 form-group">
                                    <label>Total (Excl. PPN)</label>
                                    <input class="form-control total" value="{{ 'Rp. ' . number_format($data->total) }}"
                                        readonly>
                                </div>
        
                                <div class="form-group col-lg-4">
                                    <label>PPN</label>
                                    <input class="form-control ppn" value="{{ 'Rp. ' . number_format($data->ppn) }}"
                                        id="" readonly>
                                </div>
        
                                <div class="col-lg-4 form-group">
                                    <label>Total (Incl. PPN)</label>
                                    <input class="form-control total-after-ppn"
                                        value="{{ 'Rp. ' . number_format($data->total_after_ppn) }}" readonly>
                                </div>
                            </div>
                        </div>
        
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button class="btn  btn-danger " type="button" data-bs-dismiss="modal">Close</button>
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
                    placeholder: 'Select Customer',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(warehouse = '', customer = '') {

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
                            url: "{{ url('/report_receivable_indirect') }}",
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
                                className: '',
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

                        },
                        order: [

                        ],
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
                                title: 'Receivable Report Data',
                                text: '<i class="fa fa-print"></i>',

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
                                text: '<i class="fa fa-download"></i>',

                                exportOptions: {
                                   columns: ':visible',
                                    orthogonal: 'not-visible',
                                    rows: function ( idx, data, node ) {
                                        return !$(node).hasClass('modal');
                                    }
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
                    var warehouse = $('#warehouse').val();
                    var customer = $('#customer').val();
                    load_data(warehouse, customer);

                });
                $('#refresh').click(function() {
                    $('#warehouse').val(null).trigger('change');
                    $('#customer').val(null).trigger('change');
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });


            });
        </script>
    @endpush
@endsection
