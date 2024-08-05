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


                        <div class="table-responsive">
                            <table style="font-size: 10pt" id="dataTable" class="stripe row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr>

                                        <th>Vendor</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>NPWP</th>
                                        <th>PIC</th>
                                        <th>Bank - Account Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($model as $vendor)
                                        <tr>

                                            <td>{{ $vendor->nama_supplier }}</td>
                                            <td>{{ $vendor->alamat_supplier }}</td>
                                            <td>{{ $vendor->no_telepon_supplier }}</td>
                                            <td>{{ $vendor->npwp_supplier }}</td>
                                            <td>{{ $vendor->pic_supplier }}</td>

                                            <td>{{ $vendor->bank }} - {{ $vendor->no_rek }}</td>
                                        </tr>
                                    @endforeach
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
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
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
                    pageLength: -1,
                    destroy: true,
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

                        },
                        {
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
            });
        </script>
    @endpush
@endsection
