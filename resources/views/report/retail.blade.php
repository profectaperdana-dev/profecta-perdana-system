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
                    {{-- <div class="card-header pb-0">
                        <h5></h5>
                    </div> --}}
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
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Province</label>
                                <select id="province" name="province" class="form-control province">
                                </select>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">District</label>
                                <select id="district" name="district" class="form-control city">
                                </select>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Sub District</label>
                                <select id="sub_district" name="sub_district" class="form-control district">

                                </select>
                            </div>
                            <div class="col-lg-3 col-6">
                                <label class="col-form-label text-end">Car Brand</label>
                                <select id="car_brand" class="form-control selectMulti" multiple>
                                    {{-- <option value="">--ALL--</option> --}}
                                    @foreach ($car_brand as $row_car_brand)
                                        <option value="{{ $row_car_brand->car_brand }}">{{ $row_car_brand->car_brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-6">
                                <label class="col-form-label text-end">Car Type</label>
                                <select id="car_type" class="form-control selectMulti" multiple>
                                    {{-- <option value="" selected>--ALL--</option> --}}
                                    @foreach ($car_type as $row_car_type)
                                        <option value="{{ $row_car_type->car_type }}">{{ $row_car_type->car_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-6">
                                <label class="col-form-label text-end">Motor Brand</label>
                                <select id="motor_brand" class="form-control selectMulti" multiple>
                                    {{-- <option value="" selected>--ALL--</option> --}}
                                    @foreach ($motor_brand as $row_motor_brand)
                                        <option value="{{ $row_motor_brand->name_brand }}">
                                            {{ $row_motor_brand->name_brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-6">
                                <label class="col-form-label text-end">Motor Type</label>
                                <select id="motor_type" class="form-control selectMulti" multiple>
                                    {{-- <option value="" selected>--ALL--</option> --}}
                                    @foreach ($motor_type as $row_motor_type)
                                        <option value="{{ $row_motor_type->name_type }}">{{ $row_motor_type->name_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-4 form-group">
                                <label class="col-form-label text-end">
                                    Sub Material</label>
                                <select name="" id="material" required class="form-control multiSelect" multiple>
                                    @foreach ($material_group as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_sub_material }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-4 form-group">
                                <label class="col-form-label text-end">
                                    Material Type</label>

                                <select name="" id="type" required class="form-control" multiple>
                                </select>
                            </div>
                            <div class=" col-md-12 col-lg-4 form-group">
                                <label class="col-form-label text-end">Product</label>
                                <select name="" id="product" class="form-control" multiple>

                                </select>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Customer</label>
                                <div class="input-group">
                                    <select name="customer" id="customer" class="form-control selectMulti" multiple>
                                        {{-- <option value="">--ALL--</option> --}}
                                        @foreach ($customer as $row_customer)
                                            <option value="{{ $row_customer->id }}">
                                                {{ $row_customer->code_cust . ' - ' . $row_customer->name_cust }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="" id="warehouse" multiple class="form-control selectMulti">
                                        {{-- <option value="" selected>--ALL--</option> --}}
                                        @foreach ($warehouse as $row)
                                            <option value="{{ $row->id }}">{{ $row->warehouses }}</option>
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
                                    {{-- <tr class="text-center">

                                        <th colspan="8" class="table-success text-center">Customer&nbsp;Information
                                        </th>
                                        <th colspan="5" class="table-warning text-center">Vehicle&nbsp;Information</th>
                                        <th colspan="12" class="table-info text-center">
                                            Order&nbsp;Information&nbsp;per&nbsp;Item</th>

                                    </tr> --}}
                                    <tr class="text-center">
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Invoice Number</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Order Date</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Name</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Phone</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Identity Number</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 100px; margin-right: 100px;">Email</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Area</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Province</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">District</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Sub District</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Address</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Plate Number</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Car Brand</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Car Type</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Motocycle Brand</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Motocycle Type</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Material</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Sub Material</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Type</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Product</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Price</span> <!--21-->
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Qty</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Total Price</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Total Price Excl. PPN</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 10px; margin-right: 10px;">Discount (%)</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Discount (Rp)</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">(Exclude PPN)</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">PPN</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Total (Include PPN)</span>
                                            </div>
                                        </th>
                                         <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Return Total</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 20px; margin-right: 20px;">Other</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Remark</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 30px; margin-right: 30px;">Created By</span>
                                            </div>
                                        </th>
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
                                        <th></th> <!--21-->
                                        <th class="border-2 border-black"></th>
                                        <th ></th>
                                        <th ></th>
                                        <th></th>
                                        <th></th>
                                        <th ></th>
                                        <th ></th>
                                        <th ></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <!--<th></th>-->
                                        <!--<th></th>-->
                                    </tr>
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
                load_data();

                function load_data(
                    from_date = '',
                    to_date = '',
                    province = '',
                    district = '',
                    sub_district = '',
                    car_brand = '',
                    car_type = '',
                    motor_brand = '',
                    motor_type = '',
                    material = '',
                    type = '',
                    product = '',
                    customer = '',
                    warehouse = ''
                ) {
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
                            url: "{{ url('/report_retail') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                province: province,
                                district: district,
                                sub_district: sub_district,
                                car_brand: car_brand,
                                car_type: car_type,
                                motor_brand: motor_brand,
                                motor_type: motor_type,
                                material: material,
                                type: type,
                                product: product,
                                customer: customer,
                                warehouse: warehouse
                            }
                        },
                        columns: [

                            {
                                className: 'fw-bold text-nowrap text-center',
                                data: 'order_number',
                                name: 'order_number',
                               
                            },
                            {
                                className: 'text-center',
                                data: 'order_date',
                                name: 'order_date',
                               
                            },
                            {
                                className: 'text-center',
                                data: 'cust_name',
                                name: 'cust_name',
                               
                            },
                            {
                                className: 'text-center',
                                data: 'cust_phone',
                                name: 'cust_phone',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'cust_ktp',
                                name: 'cust_ktp',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'cust_email',
                                name: 'cust_email',
                                 render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }


                            },
                            {
                                className: 'text-center',
                                data: 'area',
                                name: 'area',

                            },
                            {
                                className: 'text-center',
                                data: 'province',
                                name: 'province',

                            },
                            {
                                className: 'text-center',
                                data: 'district',
                                name: 'district',

                            },
                            {
                                className: 'text-center',
                                data: 'sub_district',
                                name: 'sub_district',

                            },
                            {
                                data: 'address',
                                name: 'address',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'plate_number',
                                name: 'plate_number',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'car_brand_id',
                                name: 'car_brand_id',

                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'car_type_id',
                                name: 'car_type_id',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }


                            },
                            {
                                className: 'text-center',
                                data: 'motor_brand_id',
                                name: 'motor_brand_id',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'motor_type_id',
                                name: 'motor_type_id',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'material',
                                name: 'material'
                            },
                            {
                                className: 'text-center',
                                data: 'sub_material',
                                name: 'sub_material'
                            },
                            {
                                className: 'text-center',
                                data: 'sub_type',
                                name: 'sub_type'
                            },
                            {
                                className: 'text-center',
                                data: 'nama_barang',
                                name: 'nama_barang'
                            },
                            {
                                className: 'text-end',
                                data: 'price',
                                name: 'price'
                            },
                            {
                                className: 'text-center',
                                data: 'qty',
                                name: 'qty'
                            },
                            {
                                className: 'text-end',
                                data: 'total_price',
                                name: 'total_price'
                            },
                            {
                                className: 'text-end',
                                data: 'total_price_excl',
                                name: 'total_price_excl'
                            },
                            {
                                className: 'text-end',
                                data: 'discount',
                                name: 'discount'
                            },
                            {
                                className: 'text-end',
                                data: 'discount_rp',
                                name: 'discount_rp'
                            },
                            {
                                className: 'text-end',
                                data: 'total_excl',
                                name: 'total_excl',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-end',
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
                                className: 'text-end',
                                data: 'total_incl',
                                name: 'total_incl',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-end',
                                data: 'total_return',
                                name: 'total_return',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: 'text-center',
                                data: 'other',
                                name: 'other',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'remark',
                                name: 'remark',
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
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['order_number'] === meta.settings.aoData[
                                            meta.row - 1]._aData['order_number']) {
                                        return '';
                                    }
                                    return data;
                                }
                            },

                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            // Remove the formatting to get integer data for summation
                            var intVal = function(i) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column(21)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Update footer
                            $(api.column(21).footer()).html(total.toLocaleString('en', {}));
                            
                            // PPN
                            var visibleData = api.column(22).nodes().to$().map(function() {
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
                            
                            $(api.column(22).footer()).html(totalprice.toLocaleString('en', {}));
// PPN
                            var visibleData = api.column(23).nodes().to$().map(function() {
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
                            
                            $(api.column(23).footer()).html(totalprice.toLocaleString('en', {}));
                            // PPN
                            // var visibleData = api.column(25).nodes().to$().map(function() {
                            //     return $(this).text();
                            // }).toArray();
                            // var visibleColumns = api.columns().visible();
                            // var filteredData = visibleData.filter(function(data) {
                            //     return data.trim() !== '';
                            // });
                            // var totalPPN = 0;
                            // filteredData.forEach(function(data) {
                            //     if (data != '') {
                            //         let raw1 = data.split(",");
                            //         let raw2 = raw1.join('');
                            //         totalPPN += parseInt(raw2);
                            //     }
                            // });


                            // $(api.column(25).footer()).html(totalPPN.toLocaleString());




                            // Total Incl
                            var visibleData_ = api.column(26).nodes().to$().map(function() {
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
                                    let raw2 = raw1.join('');
                                    totalIncl += parseInt(raw2);
                                }
                            });


                            $(api.column(26).footer()).html(totalIncl.toLocaleString());


                            // // Total Excl
                            // var visibleData__ = api.column(24).nodes().to$().map(function() {
                            //     return $(this).text();

                            // }).toArray();
                            // // console.log(visibleData__);

                            // var visibleColumns__ = api.columns().visible();
                            // // console.log(visibleColumns__);
                            // var filteredData__ = visibleData__.filter(function(data) {
                            //     return data.trim() !== '';
                            // });

                            // // console.log(filteredData__);
                            // var totalExcl = 0;
                            // filteredData__.forEach(function(data) {
                            //     if (data != '') {
                            //         let raw1 = data.split(",");
                            //         let raw2 = raw1.join('');
                            //         totalExcl += parseInt(raw2);
                            //     }
                            // });

                            // $(api.column(24).footer()).html(totalExcl.toLocaleString());

                            // Total Return
                            var visibleData__ = api.column(27).nodes().to$().map(function() {
                                return $(this).text();

                            }).toArray();
                            // console.log(visibleData__);

                            var visibleColumns__ = api.columns().visible();
                            // console.log(visibleColumns__);
                            var filteredData__ = visibleData__.filter(function(data) {
                                return data.trim() !== '';
                            });

                            // console.log(filteredData__);
                            var totalReturn = 0;
                            filteredData__.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    let raw2 = raw1.join('');
                                    totalReturn += parseInt(raw2);
                                }
                            });

                            $(api.column(27).footer()).html(totalReturn.toLocaleString());

// PPN
                            var visibleData = api.column(28).nodes().to$().map(function() {
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
                            
                            $(api.column(28).footer()).html(totalprice.toLocaleString('en', {}));
                            
                             var visibleData = api.column(29).nodes().to$().map(function() {
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
                            
                            $(api.column(29).footer()).html(totalprice.toLocaleString('en', {}));
                        },

                        dom: 'Bfrtip',
                        order: [

                        ],
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
                                text: '<i class="icofont icofont-printer"></i>',

                                title: 'Retail Invoice Data',
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
                    var province = $('#province').val();
                    var district = $('#district').val();
                    var sub_district = $('#sub_district').val();
                    var car_brand = $('#car_brand').val();
                    // console.log(car_brand);
                    var car_type = $('#car_type').val();
                    var motor_brand = $('#motor_brand').val();
                    var motor_type = $('#motor_type').val();
                    var material = $('#material').val();
                    var type = $('#type').val();
                    var product = $('#product').val();
                    var customer = $('#customer').val();
                    var warehouse = $('#warehouse').val();
                    if (from_date != '' && to_date != '') {
                        $('#dataTable').DataTable().destroy();
                        load_data
                            (
                                from_date,
                                to_date,
                                province,
                                district,
                                sub_district,
                                car_brand,
                                car_type,
                                motor_brand,
                                motor_type,
                                material,
                                type,
                                product,
                                customer,
                                warehouse
                            );
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
                    $('#province').val(null).trigger('change');
                    $('#district').val(null).trigger('change');
                    $('#sub_district').val(null).trigger('change');
                    $('#car_brand').val(null).trigger('change');
                    // console.log($('#car_brand').val());
                    $('#car_type').val(null).trigger('change');
                    $('#motor_brand').val(null).trigger('change');
                    $('#motor_type').val(null).trigger('change');
                    $('#material').val(null).trigger('change');
                    $('#type').val(null).trigger('change');
                    $('#product').val(null).trigger('change');
                    $('#customer').val(null).trigger('change');
                    $('#warehouse').val(null).trigger('change');
                    $('#dataTable').DataTable().destroy();

                    load_data();
                });

            });
        </script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                $(".province").select2({
                    width: "100%",
                    placeholder: "Select Province",
                    minimumResultsForSearch: -1,
                    sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                    ajax: {
                        type: "GET",
                        url: "/customers/getProvince",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.name,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                $('.province').change(function() {
                    let province_value = $('.province').val();
                    console.log(province_value);
                    $(".city").select2({
                        width: "100%",
                        minimumResultsForSearch: -1,
                        placeholder: "Select City",
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getCity/" + province_value,
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });

                $('.city').change(function() {
                    let city_value = $('.city').val();

                    $(".district").select2({
                        width: "100%",
                        minimumResultsForSearch: -1,
                        placeholder: "Select District",
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getDistrict/" + city_value,
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });

                $('.district').change(function() {
                    let district_value = $('.district').val();

                    $(".village").select2({
                        width: "100%",
                        minimumResultsForSearch: -1,
                        placeholder: "Select Village",
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getVillage/" + district_value,
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });
            })
        </script>
    @endpush
@endsection
