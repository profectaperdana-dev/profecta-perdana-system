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
                <div class="card shadow rounded">
                    <div class="card-body">
                        @php
                            $date = date('Y-m-d');
                        @endphp
                        <div class="form-group row">
                            <div class="col-lg-6 col-12 mb-2">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 mb-2">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date" data-value="{{ date('d-m-Y') }}"
                                        name="to_date" autocomplete="on">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4 mb-2">
                                <label class="col-form-label text-end">
                                    Sub Material</label>
                                <select name="" id="material" required class="form-control multiSelect" multiple>
                                    @foreach ($material_group as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_sub_material }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-4 mb-2">
                                <label class="col-form-label text-end">
                                    Material Type</label>
                                <select name="" id="type" required class="form-control" multiple>
                                </select>
                            </div>
                            <div class=" col-md-12 col-lg-4 mb-2">
                                <label class="col-form-label text-end">Product</label>
                                <select name="" id="product" class="form-control" multiple>
                                </select>
                            </div>
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
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Customer</label>
                                <div class="input-group">
                                    <select name="customer" id="customer" multiple class="form-control selectMulti">
                                        @foreach ($customer as $row_customer)
                                            <option value="{{ $row_customer->id }}">
                                                {{ $row_customer->code_cust . ' - ' . $row_customer->name_cust }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Area</label>
                                <div class="input-group">
                                    <select name="" id="area" multiple class="form-control selectMulti">
                                        @foreach ($area as $row)
                                            <option value="{{ $row->id }}">{{ $row->area_name }}</option>
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
                                        id="refresh">Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table style="font-size: 10pt" id="dataTable" class="stripe row-border order-column table-sm"
                                style="width:100%">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Invoice&nbsp;Number</th>
                                        <th><span>&nbsp;</span>Order&nbsp;Date<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Due&nbsp;Date<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Paid&nbsp;Date<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Customer&nbsp;Name<span>&nbsp;</span> </th>
                                                                                <th><span>&nbsp;</span>Area<span>&nbsp;</span> </th>

                                        <th><span>&nbsp;</span>District<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Payment&nbsp;Method<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Remark<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Term&nbsp;Of&nbsp;Payment<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Material<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Type<span>&nbsp;</span> </th>
                                        <th><span>&nbsp;</span>Product<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Price<span>&nbsp;</span></th>

                                        <th><span>&nbsp;</span>Qty<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Total Price<span>&nbsp;</span></th>
                                                                                                                        <th><span>&nbsp;</span>Total Price Excl<span>&nbsp;</span></th>

                                        <th><span>&nbsp;</span>Discount&nbsp;(%)<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Discount&nbsp;(Rp)<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Total&nbsp;(Excl.&nbsp;PPN)<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>PPN<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Total&nbsp;(Incl.&nbsp;PPN)<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Return&nbsp;Total<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Created&nbsp;By<span>&nbsp;</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align:right">Total</th>
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

    {{-- JS --}}
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
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());
            });
        </script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // default load data
                load_data();

                // filter data
                function load_data(from_date = '', to_date = '', material = '', type = '', product = '', customer =
                    '', warehouse = '', area = '') {
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
                        ajax: {
                            url: "{{ url('/report_sales') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                material: material,
                                type: type,
                                product: product,
                                customer: customer,
                                warehouse: warehouse,
                                area: area
                            }
                        },
                        columns: [{
                                className: "text-center  fw-bold",
                                data: 'order_number',
                                name: 'order_number',
                               
                            },
                            {
                                data: 'order_date',
                                name: 'order_date',
                                
                            },
                            {
                                className: "text-center",
                                data: 'duedate',
                                name: 'duedate',
                               
                            },
                            {
                                className: "text-center",
                                data: 'paid_date',
                                name: 'paid_date',
                                
                            },
                            {
                                data: 'name_cust',
                                name: 'name_cust',
                            },
                            {
                                data: 'area',
                                name: 'area',
                            },
                            {
                                data: 'district',
                                name: 'district',
                            },
                            {
                                data: 'payment_method',
                                name: 'payment_method',
                                
                            },
                            {
                                data: 'remark',
                                name: 'remark',
                                
                            },
                            {
                                className: "text-center",
                                data: 'top',
                                name: 'top',
                                
                            },

                            {
                                data: 'material',
                                name: 'material'
                            },
                            {
                                data: 'sub_type',
                                name: 'sub_type'
                            },
                            {
                                data: 'nama_barang',
                                name: 'nama_barang'
                            },
                            {
                                className: "text-end",
                                data: 'price',
                                name: 'price'
                            },
                            
                            {
                                className: "text-end",
                                data: 'qty',
                                name: 'qty'
                            },
                            {
                                className: "text-end",
                                data: 'total_price',
                                name: 'total_price'
                            },
                            {
                                className: "text-end",
                                data: 'total_price_excl',
                                name: 'total_price_excl'
                            },
                            {
                                className: "text-end",
                                data: 'discount',
                                name: 'discount'
                            },
                            {
                                className: "text-end",
                                data: 'discount_rp',
                                name: 'discount_rp'
                            },
                            {
                                className: "text-end",
                                data: 'total',
                                name: 'total',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }
                            },
                            {
                                className: "text-end",
                                data: 'ppn',
                                name: 'ppn',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }
                            },
                            {
                                className: "text-end",
                                data: 'total_ppn',
                                name: 'total_ppn',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }
                            },
                            {
                                className: "text-end",
                                data: 'return_total',
                                name: 'return_total',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'created_by',
                                name: 'created_by',
                                
                            },
                        ],
                        dom: 'Bfrtip',
                        buttons: [{
                                text: '<i class="fa fa-snowflake-o"></i>',
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
                                title: 'Data Invoice',
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
                                    columns: ':visible'
                                },
                                customize: function(xlsx) {
                                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                                    // Get the table footer values
                                    var footerValues = [];
                                    $('#dataTable tfoot th').each(function() {
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
                            'colvis'
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            var intVal = function(i) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                            };
                            total = api
                                .column(14)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                            $(api.column(14).footer()).html(total);
                            
                            // PPN
                            var visibleData = api.column(15).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalprice = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalprice += parseInt(raw2);
                                }
                            });
                            $(api.column(15).footer()).html(totalprice.toLocaleString('en', {}));


                            // PPN
                            var visibleData = api.column(19).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });
                            $(api.column(19).footer()).html(totalPPN.toLocaleString('en', {}));


                            // TOTAL INCL
                            var visibleData_ = api.column(20).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns_ = api.columns().visible();
                            var filteredData_ = visibleData_.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalIncl = 0;
                            filteredData_.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalIncl += parseInt(raw2);
                                }
                            });
                            $(api.column(20).footer()).html(totalIncl.toLocaleString('en', {}));


                            // TOTAL EXCL
                            var visibleData__ = api.column(16).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData__.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalExcl = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalExcl += parseInt(raw2);
                                }
                            });
                            $(api.column(16).footer()).html(totalExcl.toLocaleString('en', {}));

                            // TOTAL RETURN
                            var visibleData_ = api.column(21).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns_ = api.columns().visible();
                            var filteredData_ = visibleData_.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalReturn = 0;
                            filteredData_.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');
                                    totalReturn += parseInt(raw2);
                                }
                            });
                            $(api.column(21).footer()).html(totalReturn.toLocaleString('en', {}));
                        },



                    });

                    // Add click event listener to fix first column button
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
                    var material = $('#material').val();
                    var product = $('#product').val();
                    var customer = $('#customer').val();
                    var type = $('#type').val();
                    var warehouse = $('#warehouse').val();
                    var area = $('#area').val();
                    if (from_date != '' && to_date != '') {
                        $('#dataTable').DataTable().destroy();
                        load_data(from_date, to_date, material, type, product, customer, warehouse, area);
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
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#material').val(null).trigger('change');
                    $('#product').val(null).trigger('change');
                    $('#customer').val(null).trigger('change');
                    $('#type').val(null).trigger('change');
                    $('#warehouse').val(null).trigger('change');
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });
            });
        </script>
    @endpush
@endsection
