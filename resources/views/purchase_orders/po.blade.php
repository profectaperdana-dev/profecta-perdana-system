@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }}
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data Purchase Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>#</th>
                                        <th>Order Number</th>
                                        <th>Warehouse</th>
                                        <th>Supplier</th>
                                        <th>Order Date</th>
                                        <th>TOP (days)</th>
                                        <th>Due Date</th>
                                        <th>Remark</th>
                                        <th>total</th>
                                        <th>Created By</th>
                                        <th>Receiving Status</th>
                                        {{-- <th>Total</th> --}}
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
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#example').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/all_purchase_orders') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [{
                                width: '5%',
                                data: 'action',
                                name: 'action',
                                orderable: false,
                            }, {
                                width: '5%',
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center",
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'order_number',
                                name: 'order_number'

                            },
                            {
                                data: 'warehouse_id',
                                name: 'warehouse_id'

                            },
                            {
                                data: 'supplier_id',
                                name: 'supplier_id'

                            },
                            {
                                data: 'order_date',
                                name: 'order_date',
                            },
                            {
                                data: 'top',
                                name: 'top',

                            },
                            {
                                data: 'due_date',
                                name: 'due_date',
                            },
                            {
                                data: 'remark',
                                name: 'remark',
                            },
                            {
                                data: 'total',
                                name: 'total',
                            },
                            {
                                data: 'created_by',
                                name: 'created_by',
                            },
                            {
                                data: 'isvalidated',
                                name: 'isvalidated',
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
                            {
                                title: 'Data Purchase Order',
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
                        $('#example').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        alert('Both Date is required');
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#example').DataTable().destroy();
                    load_data();
                });


                let date = new Date();
                let date_now = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
                // $('#example').DataTable({
                //   dom: 'Bfrtip',
                //   buttons: [{
                //       title: 'All Purchase Orders (' + date_now + ')',
                //       extend: 'pdf',
                //       pageSize: 'A4',
                //       exportOptions: {
                //         columns: ':visible'
                //       },
                //       orientation: 'landscape',
                //       customize: function(doc) {
                //         doc.styles.tableHeader.alignment = 'left';
                //         doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split(
                //           '');
                //       },
                //     },
                //     {
                //       title: 'All Purchase Orders (' + date_now + ')',
                //       extend: 'print',
                //       orientation: 'landscape',
                //       exportOptions: {
                //         columns: ':visible'
                //       },
                //     },
                //     {
                //       title: 'All Purchase Orders (' + date_now + ')',
                //       extend: 'excel',
                //       exportOptions: {
                //         columns: ':visible'
                //       }
                //     },
                //     'colvis'
                //   ]
                // });

                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");

                    let modal_id = $(this).attr('data-bs-target');

                    $(modal_id).find(".supplier-select, .warehouse-select").select2({
                        width: "100%",
                    });
                    //Get Customer ID
                    $(modal_id).find(".productPo").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/products/selectAll",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_barang +
                                                " (" +
                                                item.type_name +
                                                ", " +
                                                item.nama_sub_material +
                                                ")",
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('.formPo')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();
                    $(modal_id).find(".addPo").on("click", function() {
                        ++x;
                        let form =
                            '<div class="form-group row">' +
                            '<div class="form-group col-7">' +
                            "<label>Product</label>" +
                            '<select name="poFields[' +
                            x +
                            '][product_id]" class="form-control productPo" required>' +
                            '<option value=""> Choose Product </option> ' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-3 col-md-3 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control qtyPo" required name="poFields[' +
                            x +
                            '][qty]">' +
                            '</div>' +
                            '<div class="col-2 col-md-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white remPo text-center" style="border:none; background-color:red">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find(".formPo").append(form);

                        $(modal_id).find(".productPo").select2({
                            width: "100%",
                            ajax: {
                                type: "GET",
                                url: "/products/selectAll",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.nama_barang +
                                                    " (" +
                                                    item.type_name +
                                                    ", " +
                                                    item.nama_sub_material +
                                                    ")",
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    });

                    //remove Purchase Order fields
                    $(modal_id).on("click", ".remPo", function() {
                        $(this).closest(".row").remove();
                    });

                    //reload total
                    $(modal_id).on('click', '.btn-reload', function() {
                        let total = 0;
                        $(modal_id).find('.productPo').each(function() {
                            let product_id = $(this).val();
                            let cost = function() {
                                let temp = 0;
                                $.ajax({
                                    async: false,
                                    context: this,
                                    type: "GET",
                                    url: "/products/selectCost/" + product_id,
                                    dataType: "json",
                                    success: function(data) {
                                        temp = data.harga_beli
                                    },
                                });
                                return temp;
                            }();

                            let qty = $(this).parent().siblings().find('.qtyPo').val();
                            total = total + (cost * qty);
                            //   alert($(this).parent().siblings().find('.cekQty-edit').val());
                        });

                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(total)
                            .toLocaleString(
                                'us', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }));

                    });
                    //   $(modal_id).on("hidden.bs.modal", function(event) {
                    //     $(modal_id).off(event);
                    //   });
                });

            });
        </script>
    @endpush
@endsection
