@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        {{-- <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }

            .table.dataTable table,
            th,
            td {

                vertical-align: middle !important;
            }

            .select2-selection__clear {
                margin-top: -5px !important;
                font-size: 18pt !important;
            }

            .select2-selection__choice__remove {
                display: none !important;
            }

            .select2-search__field {
                padding-bottom: 22px !important;
            }

            .select2-selection--multiple {
                padding: 5px 5px 5px 5px !important;
            }

            .select2-selection__choice {
                padding: 2px 6px !important;
                margin-top: 0 !important;
                background-color: #e2c636 !important;
                border-color: #e2c636 !important;
                color: #fff;
                margin-right: 8px !important;
            }
        </style> --}}
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
                            <div class="col-md-12 col-lg-4 form-group">
                                <label class="col-form-label text-end">
                                    Sub Material</label>
                                <select name="" id="material" required class="form-control multiSelect" multiple>
                                    @foreach ($material_group as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_sub_material }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-4 form-group">
                                <label class="col-form-label text-end">
                                    Material Type</label>

                                <select name="" id="type" required class="form-control" multiple>
                                </select>
                            </div>
                            <div class=" col-md-12 col-lg-4 form-group">
                                <label class="col-form-label text-end">Product</label>
                                <select name="" id="product" class="form-control" multiple>

                                </select>
                            </div>
                            @if ($cek_warehouse > 1)
                                <div class="col-lg-8 col-12">
                                    <label class="col-form-label text-end">Warehouse</label>
                                    <div class="input-group">
                                        <select name="" id="warehouse" class="form-control multiSelect" multiple>
                                            @foreach ($warehouse as $row)
                                                <option value="{{ $row->id }}">{{ $row->warehouses }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-8 col-12" hidden>
                                    <label class="col-form-label text-end">Warehouse</label>
                                    <div class="input-group">
                                        <input type="text" id="warehouse" class="form-control multiSelect" readonly
                                            value="{{ $cek_user->warehouse_id }}">
                                    </div>
                                </div>
                            @endif



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
                                    <a class="btn btn-warning form-control text-white" href="{{ url()->current() }}"
                                        name="refresh" id="refresh">Reset</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-striped table-borderless text-nowrap table-sm ">
                                <thead>
                                    <tr class="text-center">
                                       
                                        <th class="text-center">#</th> 
                                        <th class="text-center"></th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Warehouse</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">IS Price List (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        
                                        <th style="text-align:right">Total</th><th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-here">

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
        @include('layouts.partials.multi-select')

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                const format_debt = (d) => {
                                    return `
                                            <div style="margin-left:2px;" class="row col-lg-3 card shadow">
                                                    <table class="table fw-bold" style="border:0;" border="0">
                                                        <tr>
                                                            <td>Retail Price (Rp) : ${d.price_retail}</td>

                                                        </tr>
                                                       
                                                    </table>
                                            </div>
                                        `;
                                };
                load_data();

                function load_data(from_date = '', to_date = '', warehouse = '', type = '', product = '', material =
                    '') {

                    $('#dataTable').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        'pageLength': -1,
                        ajax: {
                            url: "{{ url('/check_stock') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                product: product,
                                warehouse: warehouse,
                                type: type,
                                material: material

                            }
                        },
                        columns: [
                            {
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                className: "text-center",
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'details-control',
                                defaultContent: '<i data-feather="plus"></i>'
                            },
                            // {
                            //     className: 'd-none d-sm-table-cell',
                            //     data: 'material',
                            //     name: 'material',

                            // },
                            // {
                            //     className: 'sub-material',
                            //     data: 'sub_material',
                            //     name: 'sub_material',

                            // },
                            // {
                            //     // className: 'd-none d-sm-table-cell sub-type',
                            //     data: 'type',
                            //     name: 'type',

                            // },
                            {
                                className: "fw-bold nama-barang",
                                data: 'nama_barang',
                                name: 'nama_barang'

                            },
                            {
                                className: ' warehouse',
                                data: 'warehouse',
                                name: 'warehouse'

                            },

                          
                            {
                                className: "text-end stock",
                                data: 'stock',
                                name: 'stock'

                            },
                            {
                                className: "text-end",
                                data: 'price_list',
                                name: 'price_list'

                            },
                        ],
                        
                        createdRow: function(row, data, dataIndex) {
                            // console.log(data);
                                        let cekText = data.nama_barang;
                                        let arrayText = cekText.split(" ");
                                        // console.log(arrayText[0]);

                            if (arrayText[0] == 'Continental') {
                                let csrf = $('meta[name="csrf-token"]').attr("content");

                                // console.log(data);
                                let id_for_modal = $(row).find('td:first-child').text();
                                // let sub_material_modal = $(row).find('.sub-material').text();
                                let sub_type_modal = $(row).find('.sub-type').text();
                                let nama_barang_modal = $(row).find('.nama-barang').text();
                                let warehouse_modal = $(row).find('.warehouse').text();
                                let stock_modal = $(row).find('.stock').text();

                                $(row).find('.nama-barang').wrapInner('<a href="#"></a>');
                                $(row).find('a').attr('data-bs-toggle', 'modal');
                                $(row).find('a').attr('href', '#');
                                $(row).find('a').attr('data-bs-target', '#dotdata' + id_for_modal);

                                $(row).find('a').on('click', function(e) {
                                    e.preventDefault();
                                    let target = e.target;
                                    target.classList.add('show');
                                    setTimeout(function() {
                                        target.classList.remove('show');
                                    }, 1000);
                                });

                                let dotRow = ``;

                                $.ajax({
                                    context: this,
                                    type: "GET",
                                    url: "/tyre_dot/selectDot",
                                    cache:true,
                                    data: {
                                        _token: csrf,
                                        p: data.product_by.id,
                                        w: data.warehouses_id
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        let totalDOT = parseInt(0);
                                        data.forEach(function(value) {
                                            // console.log(value);

                                            dotRow += ` 
                                            <tr>
                                                <td class="text-center">${value.dot}</td>
                                                <td class="text-center">${value.qty}</td>
                                            </tr>`;
                                            totalDOT += parseInt(value.qty);
                                        });


                                        let modal_dot = `
                                        <div class="modal fade" id="dotdata${id_for_modal}"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="staticBackdropLabel">
                                                            DOT List of
                                                            ${nama_barang_modal} at ${warehouse_modal}
                                                        </h6>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <b>
                                                            Stock in warehouse : <span
                                                                class="stockInWarehouse">${stock_modal}</span>
                                                            <span class="status"></span>
                                                        </b>
                                                        <hr>
                                                        <div class="table-responsive">
                                                            <table id="datatable2" class="table table-sm table-striped table-borderless"
                                                                style="width
                                                                100%">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <th style="width: 50%">DOT</th>
                                                                        <th style="width: 50%">Qty</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    ${dotRow}
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="1" class="text-center">
                                                                            Total
                                                                            DOT
                                                                            Stock
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <input type="text" readonly
                                                                                class="totalDot form-control text-center"
                                                                                value="${totalDOT}">
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>    
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;

                                        $(row).append(modal_dot);
                                    },
                                });

                                // console.log(dotRow);


                            }
                        },
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api(),
                                data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function(i) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column(4)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Update footer
                            $(api.column(4).footer()).html(
                                total
                            );
                        },

                        // order: [
                        //     [3, 'desc']
                        // ],
                        dom: 'Bfrtip',
                        lengthMenu: [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show All']
                        ],
                        buttons: ['pageLength',
                            {
                                title: 'RFS Report Data',
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
                         initComplete: function() {
                            var table = $('#dataTable').DataTable();
                            $(document).find('#dataTable tbody').off().on('click', 'td.details-control',
                                function() {
                                    var tr = $(this).closest('tr');
                                    var row = table.row(tr);

                                    if (row.child.isShown()) {
                                        // This row is already open - close it
                                        row.child.hide();
                                        tr.removeClass('shown');
                                    } else {
                                        // Open this row
                                        row.child(format_debt(row.data())).show();
                                        tr.addClass('shown');
                                    }
                                });
                        },

                        order: [],
                        dom: 'Bfrtip',

                    });

                    // $('#datatable2').DataTable();

                }
                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    var warehouse = $('#warehouse').val();
                    var material = $('#material').val();
                    var type = $('#type').val();
                    var product = $('#product').val();
                    // $(this).attr('disabled', 'disabled');
                    if (from_date != '' && to_date != '') {
                        // $(document).ready(function() {
                        load_data(from_date, to_date, warehouse, type, product, material);
                        // });
                        // $(this).removeAttr('disabled');
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
            });
        </script>
    @endpush
@endsection
