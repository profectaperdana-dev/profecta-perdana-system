@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }

            .table.dataTable table,
            th,
            td {
                border-bottom: 1px solid black !important;
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
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row col-12">
                            <div class="col-lg-3 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-lg-3 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date" data-value="{{ date('d-m-Y') }}"
                                        name="to_date" autocomplete="on">
                                </div>
                            </div>
                            <div class="col-lg-3 col-12">

                                <label class="col-form-label text-end">Employee</label>
                                <div class="input-group">
                                    <select class="form-select select-employee" name="formEmployee[employee]"></select>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary text-white form-control" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table style="font-size: 10pt" id="dataTable" class="stripe row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th><span>&nbsp;&nbsp;</span>Name<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Clock In<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Clock Out<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Date<span>&nbsp;&nbsp;</span></th>
                                        {{-- <th><span>&nbsp;&nbsp;</span>Location<span>&nbsp;&nbsp;</span></th> --}}
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

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var today = new Date();
                var formattedToday = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' +
                    today.getDate()).slice(-2);
                var csrf = $('meta[name="csrf-token"]').attr('content');

                $('.from_date, .to_date').datepicker({
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(dateText, inst) {
                        $(this).val(dateText);
                    }
                });

                $('.select-employee').select2({
                    placeholder: 'Select an employee',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/attendances/get_user/",
                        data: function(params) {
                            return {
                                _token: csrf,
                                search: params.term
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        text: item.name
                                    };
                                })
                            };
                        },
                    },
                });

                // Function to format date to YYYY-MM-DD
                function formatDate(date) {
                    let dateParts = date.split('-');
                    return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`; // Format: YYYY-MM-DD
                }

                let queryString = window.location.search;
                let queryParams = new URLSearchParams(queryString);
                let filterValue = queryParams.get("filter");

                function parseDate(date) {
                    let day = date.getDate().toString().padStart(2, '0');
                    let month = (date.getMonth() + 1).toString().padStart(2, '0');
                    let year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                }

                if (filterValue == 'this_month') {
                    var currentDate = new Date();
                    var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                    var lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
                    $('#from_date').val(parseDate(firstDayOfMonth));
                    $('#to_date').val(parseDate(lastDayOfMonth));
                } else {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                }

                function load_data(from_date = '', to_date = '', user_id = '') {
                    if ($.fn.DataTable.isDataTable('#dataTable')) {
                        $('#dataTable').DataTable().destroy();
                        // $('#dataTable').DataTable().destroy();
                    }

                    var today = new Date().toISOString().split('T')[0]; // get today's date in YYYY-MM-DD format

                    from_date = from_date || today; // use today's date if from_date is not provided
                    to_date = to_date || today; // use today's date if to_date is not provided

                    $('#dataTable').DataTable({
                        "responsive": true,
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "bPaginate": false,
                        "bLengthChange": false,
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
                        serverSide: true,
                        processing: true,
                        pageLength: -1,
                        dom: 'Bfrtip',
                        ajax: {
                            url: "{{ url('report_attendance') }}",
                            data: function(d) {
                                d.from_date = from_date;
                                d.to_date = to_date;
                                d.user_id = user_id;
                                d.filter = filterValue;
                            }
                        },
                        columns: [{
                                data: 'user_id',
                                name: 'user_id',

                                searchable: true,
                                orderable: true
                            },
                            {
                                data: 'clock_in',
                                name: 'clock_in',
                                className: 'text-center'
                            },
                            {
                                data: 'clock_out',
                                name: 'clock_out',
                                className: 'text-center'
                            },
                            {
                                data: 'date',
                                name: 'date',
                                orderable: true,
                                searchable: true
                            },
                        ],
                        initComplete: function() {
                            $('#dataTable tbody').on('click', 'td.details-control', function() {
                                var tr = $(this).closest('tr');
                                var row = table.row(tr);
                                if (row.child.isShown()) {
                                    row.child.hide();
                                    tr.removeClass('shown');
                                } else {
                                    row.child(format(row.data())).show();
                                    tr.addClass('shown');
                                }
                            });
                        },
                        drawCallback: function(settings) {
                            $('#thisModal').html('');
                            $('.currentModal').each(function() {
                                $('#thisModal').append($(this).html());
                            });
                        },
                        responsive: {
                            details: {
                                type: 'column'
                            }
                        },
                        buttons: [{
                                text: '<i class="fa fa-print"></i>',
                                title: 'Data Vendor',
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

                    function wordWrap(str, width, brk) {
                        brk = brk || '<br>';
                        width = width || 75;
                        if (!str) {
                            return str;
                        }
                        var regex = new RegExp('.{1,' + width + '}(\\s|$)|\\S+?(\\s|$)', 'g');
                        return str.match(regex).join(brk);
                    }
                }

                // // Initial call to load data based on today's date
                // $(document).ready(function() {
                //     load_data();
                // });

                $('#filter').click(function() {
                    function formatDate(date) {
                        let dateParts = date.split('-');
                        return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                    }

                    var from_date = formatDate($('#from_date').val());
                    var to_date = formatDate($('#to_date').val());
                    var user_id = $('.select-employee').val();

                    console.log(user_id);
                    // console.log(`Filter data from ${from_date} to ${to_date} for user ${user_id}`);
                    if (from_date && to_date) {
                        load_data(from_date, to_date, user_id);
                    } else {
                        $.notify({
                            title: 'Warning!',
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
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    load_data();
                });

                load_data();

            });
        </script>
    @endpush
@endsection
