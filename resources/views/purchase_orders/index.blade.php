@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')

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
                    <h3 class="font-weight-bold">{{ $title }}</h3>

                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basics"
                                class="display table table-striped table-borderless table-sm expandable-table "
                                style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th>#</th>
                                        <th>Purchase Order Number</th>
                                        <th>Warehouse</th>
                                        <th>Vendor</th>
                                        <th>Order Date</th>
                                        <th>Due Date</th>
                                        <th>Approve</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $key => $value)
                                        <tr>
                                            <td class="text-center">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>

                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
                                            <td class="text-center text-nowrap">{{ $value->order_number }}</td>
                                            <td class="text-center text-nowrap">{{ $value->warehouseBy->warehouses }}</td>
                                            <td class="text-center text-nowrap">{{ $value->supplierBy->nama_supplier }}</td>
                                            <td class="text-center">{{ date('d F Y', strtotime($value->order_date)) }}</td>
                                            <td class="text-center">{{ date('d F Y', strtotime($value->due_date)) }}</td>
                                            <td class="text-center"> <a type="button" class="btn btn-primary modal-btn2"
                                                    href="#" data-bs-toggle="modal"
                                                    data-bs-target="#manageData{{ $value->id }}">Approve</a></td>
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
                            {{ $item->supplierBy->nama_supplier }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('purchase_orders/' . $item->id . '/manage') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="container-fluid">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">
                                        <div class="form-group row">
                                            <div class="col-md-4 form-group">
                                                <label>
                                                    Vendor</label>
                                                <select name="supplier_id" id="" required multiple
                                                    class="form-control supplier-select {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}">
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            @if ($item->supplier_id == $supplier->id) selected @endif>
                                                            {{ $supplier->nama_supplier }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('supplier_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 form-group mr-5">
                                                <label>Warehouse</label>
                                                <select name="warehouse_id" required id="warehouse" multiple
                                                    class="form-control warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}">
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->warehouse_id }}"
                                                            @if ($item->warehouse_id == $warehouse->warehouse_id) selected @endif>
                                                            {{ $warehouse->warehouseBy->warehouses }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('warehouse_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 form-group mr-5">
                                                <label>Payment Method</label>
                                                <select name="payment_method" required multiple
                                                    class="form-control warehouse-select {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                                                    <option value="cash"
                                                        @if ($item->payment_method == 'cash') selected @endif>
                                                        Cash</option>
                                                    <option value="credit"
                                                        @if ($item->payment_method == 'credit') selected @endif>
                                                        Credit</option>
                                                </select>
                                                @error('payment_method')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 form-group mr-5">
                                                <label>Order Date</label>

                                                <input class="datepicker-here form-control digits"
                                                    data-position="bottom left" type="text" data-language="en"
                                                    id="from_date" name="order_date"
                                                    data-value="{{ date('d-m-Y', strtotime($item->order_date)) }}"
                                                    autocomplete="off" required>

                                                {{-- <input class="form-control" type="date" name="order_date"
                                                    value="{{ $item->order_date }}" required>
                                                @error('due_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror --}}
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>TOP</label>
                                                <input type="number" class="form-control" required name="top"
                                                    id="" value="{{ $item->top }}">
                                                @error('top')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 form-group mr-5">
                                                <label>Remarks</label>
                                                <textarea class="form-control" name="remark" id="" cols="30" rows="1" required>{{ $item->remark }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group formPo">
                                            @foreach ($item->purchaseOrderDetailsBy as $detail)
                                                <div class="form-group rounded row pt-2 mb-3 mx-auto"
                                                    style="background-color: #f0e194">
                                                    <input type="hidden" class="loop" value="{{ $loop->index }}">
                                                    <div class="form-group col-12 col-lg-5">
                                                        <label>Product</label>
                                                        <select name="poFields[{{ $loop->index }}][product_id]" multiple
                                                            class="form-control productPo" required>
                                                            @if ($detail->product_id != null)
                                                                <option value="{{ $detail->product_id }}" selected>
                                                                    {{ $detail->productBy->sub_materials->nama_sub_material . ' ' . $detail->productBy->sub_types->type_name . ' ' . $detail->productBy->nama_barang }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        @error('poFields[{{ $loop->index }}][product_id]')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-6 col-lg-3 form-group">
                                                        <label>Qty</label>
                                                        <input type="number" class="form-control qtyPo" required
                                                            name="poFields[{{ $loop->index }}][qty]" id=""
                                                            value="{{ $detail->qty }}">
                                                        @error('poFields[{{ $loop->index }}][qty]')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    @if ($loop->index == 0)
                                                        <div class="col-6 col-lg-4 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a href="javascript:void(0)"
                                                                class="form-control text-white text-center addPo"
                                                                style="border:none; background-color:#276e61">+</a>
                                                        </div>
                                                    @else
                                                        <div class="col-3 col-lg-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a href="javascript:void(0)"
                                                                class="form-control text-white text-center addPo"
                                                                style="border:none; background-color:#276e61">+</a>
                                                        </div>
                                                        <div class="col-3 col-lg-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a href="javascript:void(0)"
                                                                class="form-control text-white text-center remPo"
                                                                style="border:none; background-color:#d94f5c">-</a>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="form-group row">
                                            <div class="form-group col-12">
                                                <button type="button"
                                                    class="col-12 btn btn-outline-success btn-reload">--
                                                    Click this to
                                                    view total
                                                    --</button>
                                                <input type="hidden" value="{{$item->id}}" class="purchase-id">    
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="form-group col-lg-12 col-12">
                                                <label>Total</label>
                                                <input class="form-control total"
                                                    value="{{ 'Rp. ' . number_format(round($item->total)) }}"
                                                    id="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-info" type="button" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                    data-original-title="test" data-bs-target="#reject{{ $item->id }}"
                                    data-bs-dismiss="modal">Reject</button>

                                <button type="submit" class="btn btn-primary">Yes, Approve</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{-- PO Manage End --}}

        {{-- PO Delete --}}
        <div class="modal" id="reject{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('purchase_orders/' . $item->id . '/reject') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Reject order from
                                {{ $item->supplierBy->nama_supplier }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <h5>Are you sure reject this data ?</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Yes, reject
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- PO Delete End --}}
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
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

                    $('form').submit(function(e) {
                        var form = $(this);
                        var button = form.find('button[type="submit"]');

                        if (form[0].checkValidity()) { // check if form has input values
                            button.prop('disabled', true);
                            // e.preventDefault(); // prevent form submission
                        }
                    });

                    $(modal_id).find('.datepicker-here').datepicker({
                        dropdownParent: $(modal_id),
                        onSelect: function(formattedDate, date, inst) {
                            inst.hide();
                        },
                    });
                    $(modal_id).find('.datepicker-here').val(
                        $(modal_id).find('.datepicker-here').attr('data-value'));

                    $(modal_id).find(".supplier-select, .warehouse-select").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                    });

                    // $(modal_id).find('.addPo').unbind('click');

                    let selected_warehouse = $(modal_id).find('#warehouse option:selected').val();
                    $(modal_id).find('.warehouse-select').change(function() {
                        selected_warehouse = $('.warehouse-select').val();
                        // $(modal_id).find(".productPo").empty().trigger('change');
                    });
                    //Get Customer ID
                    $(modal_id).find(".productPo").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
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
                            '<div class="form-group rounded row pt-2 mb-3 mx-auto" style="background-color: #f0e194">' +
                            '<div class="form-group col-12 col-lg-5">' +
                            "<label>Product</label>" +
                            '<select multiple name="poFields[' +
                            x +
                            '][product_id]" class="form-control productPo" required>' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-6 col-lg-3 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control qtyPo" required name="poFields[' +
                            x +
                            '][qty]">' +
                            '</div>' +
                            '<div class="col-3 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a href="javascript:void(0)" class="form-control text-white addPo text-center" style="border:none; background-color:#276e61">' +
                            '+ </a> ' +
                            '</div>' +
                            '<div class="col-3 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a href="javascript:void(0)" class="form-control text-white remPo text-center" style="border:none; background-color:#d94f5c">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find(".formPo").append(form);

                        $(modal_id).find(".productPo").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
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
                        let purchase_id = $(this).parent().find('.purchase-id').val();

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
                                    data: {
                                        purchase_id: purchase_id
                                    },  
                                    success: function(data) {
                                        temp = data;
                                    },
                                });
                                return temp;
                            }();
                            // console.log(cost);
                            let qty = $(this).parent().siblings().find('.qtyPo').val();
                            total = total + (cost * qty);
                            // console.log(total);
                            //   alert($(this).parent().siblings().find('.cekQty-edit').val());
                        });
                        let ppn = total * $('#ppn').val();
                        let total_incl = total + ppn;
                        // console.log(total_incl);
                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(
                                total_incl)
                            .toLocaleString());

                    });
                    //   $(modal_id).on("hidden.bs.modal", function(event) {
                    //     $(modal_id).off(event);
                    //   });
                });
            });
        </script>
    @endpush
@endsection
