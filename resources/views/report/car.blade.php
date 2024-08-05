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

                border-bottom: 1px solid black !important;
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
                    <div class="card-header pb-0">
                        <h5></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="from_date" id="from_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="to_date" id="to_date">
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
                                    <tr>
                                        {{-- <th>No</th> --}}
                                        <th>Car Brand</th>
                                        <th>Car Type</th>
                                        <th>Battery Type 1</th>
                                        <th>Battery Type 2</th>
                                        <th>Tyre Type</th>

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
        <script>
            $(document).ready(function() {
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
                            url: "{{ url('/report_car') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [

                            // {
                            //     width: '5%',
                            //     data: 'DT_RowIndex',
                            //     name: 'DT_Row_Index',
                            //     "className": "text-center",
                            //     orderable: false,
                            //     searchable: false
                            // },
                            {
                                data: 'id_car_brand',
                                name: 'id_car_brand'

                            },
                            {
                                data: 'car_type',
                                name: 'car_type'

                            },
                            {
                                data: 'accu_type',
                                name: 'accu_type'

                            },
                            {
                                data: 'second_accu_type',
                                name: 'second_accu_type'

                            },
                            {
                                data: 'tire_type',
                                name: 'tire_type'

                            },
                        ],

                        order: [
                            [0, 'desc']
                        ],
                        dom: 'Bfrtip',
                        pageLength: -1,
                        lengthMenu: [
                            [-1],
                            ['Show All']
                        ],
                        buttons: ['pageLength',
                            {
                                title: 'Car Report Data',
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

                }
                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if (from_date != '' && to_date != '') {
                        load_data(from_date, to_date);
                    } else {
                        alert('Both Date is required');
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
