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
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="font-weight-bold">{{ $title }}</h4>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">Read data
                        {{ $title }} --}}
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
                        {{-- <h5>Purchase Order Preview</h5> --}}

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basics"
                                class="display table table-striped table-borderless table-sm expandable-table "
                                style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        {{-- <th></th> --}}
                                        <th>#</th>
                                        <th>Purchase Order Number</th>
                                        <th>Warehouse</th>
                                        <th>Vendor</th>
                                        <th>Order Date</th>
                                        <th>Due Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $key => $value)
                                        <tr>

                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-center text-nowrap">{{ $value->order_number }}</td>
                                            <td class="text-center text-nowrap">{{ $value->warehouseBy->warehouses }}</td>
                                            <td class="text-center text-nowrap">{{ $value->supplierBy->nama_supplier }}</td>
                                            <td class="text-center">{{ date('d F Y', strtotime($value->order_date)) }}</td>
                                            <td class="text-center">{{ date('d F Y', strtotime($value->due_date)) }}</td>
                                            <td class="text-center"> <a type="button" class="btn btn-primary modal-btn2"
                                                    href="#" data-bs-toggle="modal"
                                                    data-bs-target="#manageData{{ $value->id }}">View Detail</a></td>
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
    <div>
        <input type="hidden" id="ppn" value="{{ $ppn }}">
    </div>

    {{-- Modal PO --}}
    @foreach ($purchases as $item)
        {{-- PO Manage --}}
        <div class="modal fade" id="manageData{{ $item->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
            data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel"> Purchase Order:
                            {{ $item->order_number }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="font-weight-bold">
                                    <div class="form-group row">
                                        <div class="col-md-4 form-group">
                                            <label>
                                                Vendor</label>
                                            <select disabled name="supplier_id" id="" required
                                                class="form-control supplier-select {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}">
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        @if ($item->supplier_id == $supplier->id) selected @endif>
                                                        {{ $supplier->nama_supplier }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-md-4 form-group mr-5">
                                            <label>Warehouse</label>
                                            {{-- <input type="text" value="{{ $item }}"> --}}
                                            <select disabled name="warehouse_id" required id="warehouse"
                                                class="form-control warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}">
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->warehouse_id }}"
                                                        @if ($item->warehouse_id == $warehouse->warehouse_id) selected @endif>
                                                        {{ $warehouse->warehouseBy->warehouses }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-md-4 form-group mr-5">
                                            <label>Payment Method</label>
                                            <select disabled name="payment_method" required
                                                class="form-control warehouse-select {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                                                <option value="cash" @if ($item->payment_method == 'cash') selected @endif>
                                                    Cash</option>
                                                <option value="credit" @if ($item->payment_method == 'credit') selected @endif>
                                                    Credit</option>
                                            </select>

                                        </div>

                                        <div class="col-md-6 form-group mr-5">
                                            <label>Order Date</label>
                                            <input class="form-control" type="date" name="order_date"
                                                value="{{ $item->order_date }}" readonly required>

                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>TOP</label>
                                            <input type="number" class="form-control" readonly required name="top"
                                                id="" value="{{ $item->top }}">

                                        </div>

                                        <div class="col-md-12 form-group mr-5">
                                            <label>Remarks</label>
                                            <textarea class="form-control" readonly name="remark" id="" cols="30" rows="1" required>{{ $item->remark }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group formPo">
                                        @foreach ($item->purchaseOrderDetailsBy as $detail)
                                            <div class="form-group rounded row pt-2 mb-3 mx-auto"
                                                style="background-color: #f0e194">
                                                <input type="hidden" class="loop" value="{{ $loop->index }}">
                                                <div class="form-group col-8 col-lg-7">
                                                    <label>Product</label>
                                                    <select disabled name="poFields[{{ $loop->index }}][product_id]"
                                                        class="form-control productPo" required>
                                                        @if ($detail->product_id != null)
                                                            <option value="{{ $detail->product_id }}" selected>
                                                                {{ $detail->productBy->sub_materials->nama_sub_material . ' ' . $detail->productBy->sub_types->type_name . ' ' . $detail->productBy->nama_barang }}
                                                            </option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-4 col-lg-5 form-group">
                                                    <label>Qty</label>
                                                    <input type="number" readonly class="form-control qtyPo" required
                                                        name="poFields[{{ $loop->index }}][qty]" id=""
                                                        value="{{ $detail->qty }}">

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- PO Manage End --}}
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                var t = $('#basics').DataTable({
                    "language": {
                        "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                    },
                    "lengthChange": false,
                    "paging": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0,
                    }, {
                        searchable: false,
                        orderable: false,
                        targets: 1,
                    }, ],
                });

                t.on('order.dt search.dt', function() {
                    let i = 1;

                    t.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();
                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");

                    let modal_id = $(this).attr('data-bs-target');

                    $(modal_id).find(".supplier-select, .warehouse-select").select2({
                        width: "100%",
                        dropdownParent: modal_id
                    });

                    // $(modal_id).find('.addPo').unbind('click');

                    let selected_warehouse = $(modal_id).find('#warehouse option:selected').val();
                    $(modal_id).find('.warehouse-select').change(function() {
                        selected_warehouse = $('.warehouse-select').val();
                        $(modal_id).find(".productPo").empty().trigger('change');
                    });
                    //Get Customer ID
                    $(modal_id).find(".productPo").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: 'Choose Product',
                        ajax: {
                            type: "GET",
                            url: "/products/selectByWarehouse",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    c: selected_warehouse,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_sub_material + " " +
                                                item.type_name + " " + item
                                                .nama_barang,
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
                    $(document).off("click", ".addPo");
                    $(document).on("click", ".addPo", function() {
                        ++x;
                        let form =
                            '<div class="form-group rounded row bg-primary pt-2 mb-3 mx-auto">' +
                            '<div class="form-group col-12 col-lg-5">' +
                            "<label>Product</label>" +
                            '<select name="poFields[' +
                            x +
                            '][product_id]" class="form-control productPo" required>' +
                            '<option value=""> Choose Product </option> ' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-9 col-lg-3 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control qtyPo" required name="poFields[' +
                            x +
                            '][qty]">' +
                            '</div>' +
                            '<div class="col-3 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white addPo text-center" style="border:none; background-color:green">' +
                            '+ </a> ' +
                            '</div>' +
                            '<div class="col-3 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white remPo text-center" style="border:none; background-color:red">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find(".formPo").append(form);

                        $(modal_id).find(".productPo").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            ajax: {
                                type: "GET",
                                url: "/products/selectByWarehouse",
                                placeholder: 'Choose Product',
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        c: selected_warehouse,
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item
                                                    .nama_sub_material +
                                                    " " + item.type_name +
                                                    " " + item.nama_barang,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                        $(modal_id).find('.productPo').last().select2('open');
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
                                    url: "/products/selectCostDecrypted/" +
                                        product_id,
                                    dataType: "json",
                                    success: function(data) {
                                        temp = data;
                                    },
                                });
                                return temp;
                            }();
                            console.log(cost);
                            let qty = $(this).parent().siblings().find('.qtyPo').val();
                            total = total + (cost * qty);
                            console.log(total);
                            //   alert($(this).parent().siblings().find('.cekQty-edit').val());
                        });
                        let ppn = total * $('#ppn').val();
                        let total_incl = total + ppn;
                        console.log(total_incl);
                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(
                                total_incl)
                            .toLocaleString(
                                'id', {
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
