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
                            <div class="col-4">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="from_date" id="from_date">
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="to_date" id="to_date">
                                </div>
                            </div>
                            <div class="col-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary" name="filter" id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning" name="refresh" id="refresh">Refresh</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table style="font-size: 10pt" id="dataTable" class="table text-capitalize table-sm"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        {{-- <th>No</th> --}}
                                        <th>Invoice</th>
                                        <th>Order Date</th>
                                        <th>Due Date</th>
                                        <th>Customer</th>
                                        <th>Remark</th>
                                        <th>Created By</th>
                                        <th>TOP</th>
                                        <th>Total</th>
                                        <th>Paid Date</th>
                                        <th>Material</th>
                                        <th>Type</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Discount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <button onclick="exportData()">
                            Download list</button>
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

                        rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8],

                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/report_purchase') }}",
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
                                data: 'order_number',
                                name: 'order_number'

                            },
                            {
                                data: 'order_date',
                                name: 'order_date'

                            },
                            {
                                data: 'due_date',
                                name: 'due_date'

                            },
                            {
                                data: 'name_cust',
                                name: 'name_cust'

                            },
                            {
                                data: 'remark',
                                name: 'remark'

                            },
                            {
                                data: 'name',
                                name: 'name'

                            },
                            {
                                data: 'top',
                                name: 'top'

                            },
                            {
                                data: 'total_after_ppn',
                                name: 'total_after_ppn'

                            },
                            {
                                data: 'paid_date',
                                name: 'paid_date'

                            },
                            {
                                data: 'material',
                                name: 'material'

                            },
                            {
                                data: 'sub_type',
                                name: 'sub_type'

                            },
                            {
                                data: 'due_date',
                                name: 'due_date'

                            },
                            {
                                data: 'qty',
                                name: 'qty'

                            },
                            {
                                data: 'discount',
                                name: 'discount'

                            },
                            {
                                data: 'discount',
                                name: 'discount'

                            },

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
                            {
                                title: 'Data Invoice',
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
                                        .css('font-size', '8pt');
                                    $(win.document.body)
                                        .find('tbody')
                                        .css('background-color', 'rgba(211,225,222,255)')
                                        .css('font-size', '8pt');
                                    $(win.document.body)
                                        .find('table')
                                        .css('width', '100%');
                                    var lastColX = null;
                                    var lastColY = null;
                                    var bod = [];
                                    win.content[1].table.body.forEach(function(line, i) {
                                        //Group based on first column (ignore empty cells)
                                        if (lastColX != line[0].text && line[0].text != '') {
                                            //Add line with group header
                                            bod.push([{
                                                text: line[0].text,
                                                style: 'tableHeader'
                                            }, '', '', '', '']);
                                            //Update last
                                            lastColX = line[0].text;
                                        }
                                        //Group based on second column (ignore empty cells) with different styling
                                        if (lastColY != line[1].text && line[1].text != '') {
                                            //Add line with group header
                                            bod.push(['', {
                                                text: line[1].text,
                                                style: 'subheader'
                                            }, '', '', '']);
                                            //Update last
                                            lastColY = line[1].text;
                                        }
                                        //Add line with data except grouped data
                                        if (i < win.content[1].table.body.length - 1) {
                                            bod.push(['', '', {
                                                    text: line[2].text,
                                                    style: 'defaultStyle'
                                                },
                                                {
                                                    text: line[3].text,
                                                    style: 'defaultStyle'
                                                },
                                                {
                                                    text: line[4].text,
                                                    style: 'defaultStyle'
                                                }
                                            ]);
                                        }
                                        //Make last line bold, blue and a bit larger
                                        else {
                                            bod.push(['', '', {
                                                    text: line[2].text,
                                                    style: 'lastLine'
                                                },
                                                {
                                                    text: line[3].text,
                                                    style: 'lastLine'
                                                },
                                                {
                                                    text: line[4].text,
                                                    style: 'lastLine'
                                                }
                                            ]);
                                        }

                                    });
                                    //Overwrite the old table body with the new one.
                                    win.content[1].table.headerRows = 3;
                                    win.content[1].table.widths = [50, 50, 150, 100, 100];
                                    win.content[1].table.body = bod;
                                    win.content[1].layout = 'lightHorizontalLines';

                                    win.styles = {
                                        subheader: {
                                            fontSize: 10,
                                            bold: true,
                                            color: 'black'
                                        },
                                        tableHeader: {
                                            bold: true,
                                            fontSize: 10.5,
                                            color: 'black'
                                        },
                                        lastLine: {
                                            bold: true,
                                            fontSize: 11,
                                            color: 'blue'
                                        },
                                        defaultStyle: {
                                            fontSize: 10,
                                            color: 'black'
                                        }
                                    }

                                },
                                orientation: 'landscape',
                                pageSize: 'legal',
                                rowsGroup: [0],
                                exportOptions: {
                                    rowsGroup: [0],
                                    columns: ':visible'
                                },
                            },
                            {
                                extend: 'excelHtml5',
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
                        $('#dataTable').DataTable().destroy();
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

                function exportData() {
                    /* Get the HTML data using Element by Id */
                    var table = document.getElementById("example1");

                    /* Declaring array variable */
                    var rows = [];

                    //iterate through rows of table
                    for (var i = 0, row; row = table.rows[i]; i++) {
                        //rows would be accessed using the "row" variable assigned in the for loop
                        //Get each cell value/column from the row
                        column1 = row.cells[0].innerText;
                        column2 = row.cells[1].innerText;
                        column3 = row.cells[2].innerText;
                        column4 = row.cells[3].innerText;
                        column5 = row.cells[4].innerText;

                        /* add a new records in the array */
                        rows.push(
                            [
                                column1,
                                column2,
                                column3,
                                column4,
                                column5
                            ]
                        );

                    }
                    csvContent = "data:text/csv;charset=utf-8,";
                    /* add the column delimiter as comma(,) and each row splitted by new line character (\n) */
                    rows.forEach(function(rowArray) {
                        row = rowArray.join(",");
                        csvContent += row + "\r\n";
                    });

                    /* create a hidden <a> DOM node and set its download attribute */
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "Stock_Price_Report.csv");
                    document.body.appendChild(link);
                    /* download the data file named "Stock_Price_Report.csv" */
                    link.click();
                }


            });
        </script>
    @endpush
@endsection
