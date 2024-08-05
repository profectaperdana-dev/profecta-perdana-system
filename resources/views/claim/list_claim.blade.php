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
                    <div class="card-header pb-0">
                        <h5></h5>
                    </div>
                    <div class="card-body">
                        @php
                            $now = date('Y-m-d');
                        @endphp
                        <div class="form-group row">

                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" value="{{ $now }}" type="date"
                                        data-language="en" placeholder="Start" name="from_date" id="from_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" value="{{ $now }}" type="date"
                                        data-language="en" placeholder="Start" name="to_date" id="to_date">
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
                            <table style="font-size: 10pt" id="dataTable" class="table table-striped text-nowrap table-sm"
                                style="width:100%">
                                <thead>

                                    <tr class="text-center">

                                        <th class="table-success">No</th>
                                        <th class="table-success">Code - Customer </th>
                                        <th class="table-success">Frequency </th>

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

                function load_data(from_date = '', to_date = '') {

                    $('#dataTable').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/list_claim') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                // material: material,
                                // type: type,
                                // product: product,
                                // customer: customer,
                                // car_brand: car_brand,
                                // car_type: car_type,
                                // warehouse: warehouse
                            }
                        },
                        columns: [{

                                className: 'fw-bold text-center',
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'

                            },
                            {
                                className: 'fw-bold',
                                data: 'name',
                                name: 'name'

                            },
                            {
                                className: 'fw-bold text-center',
                                data: 'qty',
                                name: 'qty'

                            },



                        ],
                        drawCallback: function(settings) {
                            // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                            $('#thisModal').html('');
                            $('.currentModal').each(function(){
                                let currentModal = $(this).html();
                                $(this).html('');
                                $('#thisModal').append(currentModal);
                            });
                            
                            // console.log($('#currentModal').html());
                            // Lakukan tindakan lain yang Anda inginkan di sini
                        },
                        order: [
                            [0, 'desc']
                        ],
                        dom: 'Bfrtip',
                        lengthMenu: [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show All']
                        ],

                        buttons: ['pageLength',
                            {
                                title: 'Data Frequency Claim',
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
                    console.log(from_date);

                    var to_date = $('#to_date').val();
                    console.log(to_date);



                    if (from_date != '' && to_date != '') {
                        $('#dataTable').DataTable().destroy();
                        load_data(from_date, to_date);
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
                    $('#from_date').val(today);
                    $('#to_date').val(today);
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });

            });
        </script>
    @endpush
@endsection
