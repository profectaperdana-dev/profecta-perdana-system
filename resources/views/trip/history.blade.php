@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            @media (min-width: 768px) {

                /* Sesuaikan nilai 768px sesuai kebutuhan Anda */
                .mobile {
                    display: none;
                }
            }

            .vertical-text {
                writing-mode: vertical-rl;
                text-orientation: upright;
                /* Optional, for vertical orientation */
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
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Choose Start Date" data-position="bottom left" name="from_date"
                                        id="from_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Choose End Date" data-position="bottom left" name="to_date"
                                        id="to_date">
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
                            <table id="dataTable" class="display table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>#</th>
                                        <th>Trip Number</th>
                                        <th>Name</th>
                                        <th>Departure Date</th>
                                        <th>Return Date</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="form-group">
                                <small> <span class="text-danger">*Note : </span><br>
                                    - BTRPP is Business Trip Profecta Perdana <br>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="{{ asset('js/date_convert.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#start_date, #end_date').datepicker({
                    language: 'en',
                    dateFormat: 'dd-mm-yyyy',
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

                function load_data(from_date = '', to_date = '') {

                    $('#dataTable').DataTable({
                        "responsive": true,
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false, // disable automatic column width
                        destroy: true,
                        // rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 25, 26, 27],
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('trip/create/history') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                            }
                        },
                        columns: [{
                                className: 'dtr-control',
                                orderable: false,
                                data: null,
                                defaultContent: ''
                            }, {
                                className: 'text-end fw-bold',
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                            },
                            {
                                data: 'trip_number',
                                name: 'trip_number',
                            },
                            {
                                data: 'id_employee',
                                name: 'id_employee',
                            },
                            {
                                className: 'text-center',
                                data: 'departure_date',
                                name: 'departure_date',

                            },
                            {
                                className: 'text-center',
                                data: 'return_date',
                                name: 'return_date',

                            },



                        ],

                    });
                }
                $('#filter').click(function() {
                    // gunakan fungsi convertDate untuk mengubah format tanggal
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
                    console.log(from_date + ' ' + to_date);
                    if (from_date > to_date) {
                        $.notify({
                            title: 'Warning',
                            message: 'Start Date must be less than End Date'
                        }, {
                            type: 'danger',
                            allow_dismiss: true,
                            newest_on_top: true,
                            mouse_over: true,
                            showProgressbar: false,
                            spacing: 10,
                            timer: 1000,
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            offset: {
                                x: 30,
                                y: 30
                            },
                            delay: 1000,
                            z_index: 10000,
                            animate: {
                                enter: 'animated bounceInDown',
                                exit: 'animated bounceInUp'
                            }
                        });
                        $('#from_date').val('');
                        $('#to_date').val('');
                    } else {
                        if (from_date != '' && to_date != '') {
                            load_data(from_date, to_date);
                        } else {
                            $.notify({
                                title: 'Warning',
                                message: 'Please Select Date'
                            }, {
                                type: 'warning',
                                allow_dismiss: true,
                                newest_on_top: true,
                                mouse_over: true,
                                showProgressbar: false,
                                spacing: 10,
                                timer: 1000,
                                placement: {
                                    from: 'top',
                                    align: 'right'
                                },
                                offset: {
                                    x: 30,
                                    y: 30
                                },
                                delay: 1000,
                                z_index: 10000,
                                animate: {
                                    enter: 'animated bounceInDown',
                                    exit: 'animated bounceInUp'
                                }
                            });
                        }
                    }
                });
                $('#refresh').click(function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });
            });
        </script>
    @endpush
@endsection
