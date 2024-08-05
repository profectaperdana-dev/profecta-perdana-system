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
                        <div class="table-responsive">
                            <table class="dataTable display table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>#</th>
                                        <th>Trip Number</th>
                                        <th>Name</th>
                                        <th>Departure - Return Date</th>
                                        <th>Departure - Destination</th>
                                        <th>Cost / Down Payment</th>
                                        <th>Needs / Goal</th>
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
        <script>
            $(document).ready(function() {
                $(document).on('submit', '.approved', function(event) {
                    event.preventDefault();
                    let formElement = $(this);
                    let url = formElement.attr('action');
                    let button = formElement.find('button[type="submit"]');
                    button.prop('disabled', true);
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "JSON",
                        processData: false, // prevent jQuery from processing the data
                        contentType: false, // prevent jQuery from setting the content type
                        beforeSend: function() {
                            $('.btnSubmit').attr('disabled', true);
                            $('.btnSubmit').html(
                                `<i class="fa fa-spinner fa-spin"></i> Processing...`
                            );
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                swal("Success !", "data has been saved", "success", {
                                    button: "Close",
                                });
                                // formElement[0].reset();
                            } else {
                                swal("Failed !", "data failed to save", "error", {
                                    button: "Close",
                                });
                            }
                            $('.dataTable').DataTable().ajax.reload();
                            $('.hideModal').click();
                        },
                        error: function(data) {
                            swal("failed !", "fail to saved data", "error", {
                                button: "Close",
                            });
                            button.prop('disabled', false);
                        },
                        complete: function() { // menambahkan fungsi complete untuk mengubah tampilan tombol kembali ke tampilan semula
                            $('.btnSubmit').attr('disabled', false);
                            $('.btnSubmit').html('Save');
                        }
                    });
                });

                var table = $('.dataTable').DataTable({
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
                    ajax: "{{ url('trip/list') }}",
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
                            data: 'departure_date',
                            name: 'departure_date',

                        },
                        {
                            data: 'departure',
                            name: 'departure',

                        },
                        {
                            className: '',
                            data: 'down_payment',
                            name: 'down_payment',
                        },
                        {
                            data: 'purpose',
                            name: 'purpose',
                        },


                    ],
                    responsive: {
                        details: {
                            type: 'column'
                        }
                    },
                });

            });
        </script>
    @endpush
@endsection
