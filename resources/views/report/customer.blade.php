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
                            <div class="col-lg-6 col-6">
                                <label class="col-form-label text-end">Category</label>
                                <select id="category" class="form-control selectMulti" multiple>
                                    {{-- <option value="" selected>--ALL--</option> --}}
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-6">
                                <label class="col-form-label text-end">Area</label>
                                <select id="area" class="form-control selectMulti" multiple>
                                    {{-- <option value="" selected>--ALL--</option> --}}
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">
                                            {{ $area->area_name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                <label class="col-form-label text-end">Label</label>
                                <select id="label" class="form-control selectMulti" multiple>
                                    {{-- <option value="">--ALL--</option> --}}
                                    <option value="Prospect">Prospect
                                    </option>
                                    <option value="Customer">Customer
                                    </option>
                                    <option value="Bad Customer">Bad Customer
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-6">
                                <label class="col-form-label text-end">Status</label>
                                <select id="status" class="form-control selectMulti" multiple>
                                    {{-- <option value="" selected>--ALL--</option> --}}
                                    <option value="1">Active
                                    </option>
                                    <option value="0">Non-active
                                    </option>
                                </select>
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
                                        <th class="text-center"><span>&nbsp;</span>Code<span>&nbsp;</span></th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Name</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Phone</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold" style="margin-left: 50px; margin-right: 50px;">Office
                                                    Phone</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold" style="margin-left: 50px; margin-right: 50px;">ID
                                                    Number</span>
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
                                                    style="margin-left: 50px; margin-right: 50px;">NPWP</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Category</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Area</span>
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
                                                    style="margin-left: 50px; margin-right: 50px;">City</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Sub-District</span>
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
                                                    style="margin-left: 50px; margin-right: 50px;">Province</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Credit Limit</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold" style="margin-left: 50px; margin-right: 50px;">Due
                                                    Date</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold" style="margin-left: 50px; margin-right: 50px;">Last
                                                    Transaction</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Label</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Status</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">OverDue</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">OverPlafoned</span>
                                            </div>
                                        </th>
                                        <th style="text-align: center;">
                                            <div style="display: flex; justify-content: center;">
                                                <span class="fw-bold"
                                                    style="margin-left: 50px; margin-right: 50px;">Created By</span>
                                            </div>
                                        </th>
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
                    category = '',
                    area = '',
                    province = '',
                    district = '',
                    sub_district = '',
                    label = '',
                    status = '',
                    isOverDue = '',
                    isOverPlafoned = '',
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
                            url: "{{ url('/report_customer') }}",
                            data: {
                                category: category,
                                area: area,
                                province: province,
                                district: district,
                                sub_district: sub_district,
                                label: label,
                                status: status,
                                isOverDue: isOverDue,
                                isOverPlafoned: isOverPlafoned
                            }
                        },
                        columns: [

                            {
                                className: 'fw-bold text-nowrap text-center',
                                data: 'code_cust',
                                name: 'code_cust',
                            },
                            {
                                className: 'text-center',
                                data: 'name_cust',
                                name: 'name_cust',
                            },
                            {
                                className: 'text-center',
                                data: 'phone_cust',
                                name: 'phone_cust',
                            },
                            {
                                className: 'text-center',
                                data: 'office_number',
                                name: 'office_number',

                            },
                            {
                                className: 'text-center',
                                data: 'id_card_number',
                                name: 'id_card_number',
                            },
                            {
                                className: 'text-center',
                                data: 'email_cust',
                                name: 'email_cust',
                            },
                            {
                                className: 'text-center',
                                data: 'npwp',
                                name: 'npwp',

                            },
                            {
                                className: 'text-center',
                                data: 'category_cust_id',
                                name: 'category_cust_id',
                            },
                            {
                                className: 'text-center',
                                data: 'area_cust_id',
                                name: 'area_cust_id',
                            },
                            {
                                className: 'text-center',
                                data: 'address_cust',
                                name: 'address_cust',
                            },
                            {
                                className: 'text-center',
                                data: 'village',
                                name: 'village',
                            },
                            {
                                className: 'text-center',
                                data: 'district',
                                name: 'district',

                            },
                            {
                                className: 'text-center',
                                data: 'city',
                                name: 'city',
                            },
                            {
                                className: 'text-center',
                                data: 'province',
                                name: 'province',

                            },
                            {
                                className: 'text-end',
                                data: 'credit_limit',
                                name: 'credit_limit',

                            },
                            {
                                className: 'text-center',
                                data: 'due_date',
                                name: 'due_date'
                            },
                            {
                                className: 'text-center',
                                data: 'last_transaction',
                                name: 'last_transaction'
                            },
                            {
                                className: 'text-center',
                                data: 'label',
                                name: 'label'
                            },
                            {
                                className: 'text-center',
                                data: 'status',
                                name: 'status'
                            },
                            {
                                className: 'text-center',
                                data: 'isOverDue',
                                name: 'isOverDue',
                                render: function(data, type, row) {
                                    return data == 1 ? 'Yes' : 'No';
                                }
                            },
                            {
                                className: 'text-center',
                                data: 'isOverPlafoned',
                                name: 'isOverPlafoned',
                                render: function(data, type, row) {
                                    return data == 1 ? 'Yes' : 'No';
                                }
                            },
                            {
                                className: 'text-center',
                                data: 'created_by',
                                name: 'created_by'
                            },


                        ],

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

                                title: 'Customer Report Data',
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

                    var category = $('#category').val();
                    var area = $('#area').val();
                    var province = $('#province').val();
                    var district = $('#district').val();
                    var sub_district = $('#sub_district').val();
                    var label = $('#label').val();
                    var status = $('#status').val();
                    var isOverDue = $('#isOverDue').val();
                    var isOverPlafoned = $('#isOverPlafoned');
                    if (category != '' || area != '' || province != '' || district != '' ||
                        sub_district != '' || label != '' || status != '' || isOverDue != '' ||
                        isOverPlafoned != '') {
                        $('#dataTable').DataTable().destroy();
                        load_data
                            (
                                category,
                                area,
                                province,
                                district,
                                sub_district,
                                label,
                                status,
                                isOverDue,
                                isOverPlafoned
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
                    $('#category').val(null).trigger('change');
                    $('#area').val(null).trigger('change');
                    $('#province').val(null).trigger('change');
                    $('#district').val(null).trigger('change');
                    $('#sub_district').val(null).trigger('change');
                    $('#label').val(null).trigger('change');
                    $('#status').val(null).trigger('change');
                    $('#isOverDue').val(null).trigger('change');
                    $('#isOverPlafoned').val(null).trigger('change');
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
