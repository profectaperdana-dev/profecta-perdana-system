@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
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

                vertical-align: middle !important;
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
                            <table style="font-size: 10pt" id="dataTable" class="table table-sm" style="width:100%">
                                <thead>
                                    <tr class="text-nowrap text-center">
                                        <th>No</th>
                                        <th>Employee</th>
                                        <th>Date Submission</th>
                                        <th>Date Range</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
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
        <script
            src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js">
        </script>
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(from_date = '', to_date = '') {

                    $('#dataTable').DataTable({
                        destroy: true,
                        // rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 25, 26, 27],
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('leave/all_data/') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
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
                                data: 'user_id',
                                name: 'user_id'

                            },
                            {
                                data: 'submission',
                                name: 'submission'

                            },
                            {
                                className: 'text-nowrap',
                                data: 'date_range',
                                name: 'date_range'

                            },
                            {
                                className: 'text-center',
                                data: 'count_days',
                                name: 'count_days'

                            },
                            {
                                className: 'text-nowrap',
                                data: 'reason',
                                name: 'reason'

                            },
                            {
                                data: 'status',
                                name: 'status',
                            }
                        ],

                        order: [
                            [0, 'desc']
                        ],
                        dom: 'Bfrtip',
                        lengthMenu: [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show All']
                        ],
                        buttons: ['pageLength',
                            'colvis'
                        ],

                    });

                }
                $('#filter').click(function() {
                    // gunakan fungsi convertDate untuk mengubah format tanggal
                    let from_date = convertDate($('#from_date').val());
                    let to_date = convertDate($('#to_date').val());
                    console.log(from_date);
                    console.log(to_date);
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
