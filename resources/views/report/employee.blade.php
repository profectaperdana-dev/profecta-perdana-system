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
                        <div class="table-responsive">
                            <table style="font-size: 10pt" id="dataTable" class="stripe row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th><span>&nbsp;&nbsp;</span>Name<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Gender<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Phone Number<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Emergency Contact 1<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Emergency Contact 2<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span>Email<span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span>
                                        </th>
                                        <th><span>&nbsp;&nbsp;</span>Place and Date of Birth<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Address<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Current Address<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Education Data 1<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Education Data 2<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Family Data 1<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Family Data 2<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Total Leave<span>&nbsp;&nbsp;</span></th>
                                        <th><span>&nbsp;&nbsp;</span>Work<span>&nbsp;&nbsp;</span></th>
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
                function wordWrap(str, width, brk) {
                    brk = brk || '<br>';
                    width = width || 75;
                    if (!str) {
                        return str;
                    }
                    var regex = new RegExp('.{1,' + width + '}(\\s|$)|\\S+?(\\s|$)', 'g');
                    return str.match(regex).join(brk);
                }

                $('.from_date').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });

                var table = $('#dataTable').DataTable({
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
                    destroy: true,
                    dom: 'Bfrtip',
                    ajax: "{{ url('report_employee') }}",
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                         {
                            data: 'gender',
                            name: 'gender',
                            className: 'text-center'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.emergency_phone + '- ' + row.emergency_relation;
                            },
                            name: 'emergency_contact_1'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.emergency_phone_ + '- ' + row.emergency_relation_;
                            },
                            name: 'emergency_contact_2'
                        },
                        {
                            data: 'email',
                            name: 'email',
                            createdCell: function(td, cellData, rowData, row, col) {
                                $(td).attr('contenteditable', 'true').css({
                                    'word-break': 'break-word',
                                    'white-space': 'normal'
                                }).html(wordWrap(cellData, 10,
                                    '<br>')); // Gunakan wordWrap jika diperlukan
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.birth_place + ', ' + row.birth_date;
                            },
                            name: 'birth_details'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'address_identity',
                            name: 'address_identity'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.last_edu_first + ', ' + row.school_name_first + ' from ' +
                                    row.from_first + ' to ' + row.to_first;
                            },
                            name: 'education_1'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.last_edu_sec + ', ' + row.school_name_sec + ' from ' + row
                                    .from_sec + ' to ' + row.to_sec;
                            },
                            name: 'education_2'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.mom_name + '- ' + row.mom_phone;
                            },
                            name: 'mother_contact'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.father_name + '- ' + row.father_phone;
                            },
                            name: 'father_contact'
                        },
                        {
                            data: 'vacation',
                            name: 'vacation'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.work_date;
                            },
                            name: 'work_date'
                        }
                    ],
                    responsive: {
                        details: {
                            type: 'column'
                        }
                    },
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
