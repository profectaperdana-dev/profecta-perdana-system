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
                        @php
                            $now = date('Y-m-d');
                        @endphp
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
                                        name="to_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 col-6">
                                <label class="col-form-label text-end">Car Brand</label>
                                <select id="car_brand" name="province" class="form-control selectMulti bg-success" multiple>
                                    @foreach ($car_brand as $row_car_brand)
                                        <option value="{{ $row_car_brand->id }}">{{ $row_car_brand->car_brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-6">
                                <label class="col-form-label text-end">Car Type</label>
                                <select id="car_type" name="province" class="form-control selectMulti" multiple>
                                    @foreach ($car_type as $row_car_type)
                                        <option value="{{ $row_car_type->id }}">{{ $row_car_type->car_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Material Group</label>
                                <div class="input-group">
                                    <select name="material" id="material" class="form-control selectMulti" multiple>
                                        @foreach ($material_group as $row_material_group)
                                            <option value="{{ $row_material_group->id }}">
                                                {{ $row_material_group->nama_sub_material }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Type</label>
                                <div class="input-group">
                                    <select id="type" name="type" id="type" class="form-control selectMulti"
                                        multiple>
                                        @foreach ($type as $row_type)
                                            <option value="{{ $row_type->id }}">
                                                {{ $row_type->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Product</label>
                                <div class="input-group">
                                    <select name="product" id="product" class="form-control selectMulti" multiple>
                                        @foreach ($product as $row_product)
                                            <option value="{{ $row_product->id }}">
                                                {{ $row_product->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Battery Loan</label>
                                <div class="input-group">
                                    <select name="product" id="product_loan" class="form-control selectMulti" multiple>
                                        @foreach ($product as $row_product)
                                            <option value="{{ $row_product->id }}">
                                                {{ $row_product->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Customer</label>
                                <div class="input-group">
                                    <select name="customer" id="customer" class="form-control selectMulti" multiple>
                                        @foreach ($customer as $row_customer)
                                            <option value="{{ $row_customer->id }}">
                                                {{ $row_customer->code_cust . ' - ' . $row_customer->name_cust }}
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
                                        <th><span>&nbsp;</span>Claim<span>&nbsp;</span>Number<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Mutation<span>&nbsp;</span>Number<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Claim<span>&nbsp;</span>Date<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Finish<span>&nbsp;</span>Date<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Motor<span>&nbsp;</span>Brand<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Motor<span>&nbsp;</span>Type<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Car<span>&nbsp;</span>Brand<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Car<span>&nbsp;</span>Type<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Other<span>&nbsp;</span>Machine<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Plat<span>&nbsp;</span>Number<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Customer<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Phone<span>&nbsp;</span>Number<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Email<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Product<span>&nbsp;</span>Code<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Mat.<span>&nbsp;</span>Group<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Type<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Battery<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Battery<span>&nbsp;</span>Lend<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Result<span>&nbsp;</span>Claim<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Diagnose<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Cost<span>&nbsp;</span></th>
                                        <th><span>&nbsp;</span>Technical<span>&nbsp;</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(from_date = '', to_date = '', material = '', type = '', product = '', customer =
                    '', car_brand = '', car_type = '', product_loan = '') {

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
                            url: "{{ url('/report_claim') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                material: material,
                                type: type,
                                product: product,
                                customer: customer,
                                car_brand: car_brand,
                                car_type: car_type,
                                product_loan: product_loan
                            }
                        },
                        columns: [{
                                className: 'fw-bold',
                                data: 'claim_number',
                                name: 'claim_number'

                            },
                            {
                                className: 'fw-bold',
                                data: 'mutation_number',
                                name: 'mutation_number'

                            },
                            {
                                data: 'start_date',
                                name: 'start_date'

                            },
                            {
                                data: 'finish_date',
                                name: 'finish_date'

                            },
                            {
                                data: 'motor_brand_id',
                                name: 'motor_brand_id'

                            },
                            {
                                data: 'motor_type_id',
                                name: 'motor_type_id'

                            },
                            {
                                data: 'car_brand_id',
                                name: 'car_brand_id'

                            },
                            {
                                data: 'car_type_id',
                                name: 'car_type_id'

                            },
                            {
                                data: 'other_machine',
                                name: 'other_machine'

                            },
                            {
                                data: 'plate_number',
                                name: 'plate_number'

                            },
                            {
                                data: 'customer_id',
                                name: 'customer_id'

                            },
                            {
                                data: 'sub_phone',
                                name: 'sub_phone'

                            },
                            {
                                data: 'email',
                                name: 'email'

                            },
                            {
                                data: 'product_code',
                                name: 'product_code'
                            },
                            {
                                data: 'material',
                                name: 'material'

                            },
                            {
                                data: 'type',
                                name: 'type'

                            },

                            {
                                data: 'product_id',
                                name: 'product_id'

                            },
                            {
                                data: 'loan_id',
                                name: 'loan_id'

                            },


                            {
                                data: 'result',
                                name: 'result'

                            },
                            {
                                data: 'diagnosa',
                                name: 'diagnosa'

                            },

                            {
                                className: 'text-end',
                                data: 'cost',
                                name: 'cost'

                            }, {
                                className: 'fw-bold',
                                data: 'e_submittedBy',
                                name: 'e_submittedBy'

                            },


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

                            }, {
                                text: '<i class="fa fa-print"></i>',

                                title: 'Data Claim',
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

                    $(document).find('#button#decreaseLeft').on('click', function() {
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
                    var type = $('#type').val();
                    var product = $('#product').val();
                    var customer = $('#customer').val();
                    var car_brand = $('#car_brand').val();
                    var car_type = $('#car_type').val();
                    var product_loan = $('#product_loan').val();
                    if (from_date != '' && to_date != '') {
                        $('#dataTable').DataTable().destroy();
                        load_data(from_date, to_date, material, type, product, customer, car_brand, car_type,
                            product_loan);
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
                    // console.log(today);
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#car_brand').val(null).trigger('change');
                    $('#car_type').val(null).trigger('change');
                    $('#material').val(null).trigger('change');
                    $('#type').val(null).trigger('change');
                    $('#product').val(null).trigger('change');
                    $('#customer').val(null).trigger('change');
                    $('#product_loan').val(null).trigger('change');
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });
            });
        </script>
    @endpush
@endsection
