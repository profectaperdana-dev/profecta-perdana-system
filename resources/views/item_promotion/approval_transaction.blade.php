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
                                        <th>Order Number</th>
                                        <th>Warehouse</th>
                                        <th>Customer</th>
                                        <th>Order Date</th>
                                        <th>Approve</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $value)
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
                                            <td class="text-center text-nowrap">
                                                @if (is_numeric($value->id_customer))
                                                    {{ $value->customerBy->name_cust }}
                                                @else
                                                    {{ $value->id_customer }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ date('d F Y', strtotime($value->order_date)) }}</td>
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


    {{-- Modal PO --}}
    @foreach ($transactions as $item)
        {{-- PO Manage --}}
        <div class="modal fade" id="manageData{{ $item->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
            data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel"> Transaction:
                            @if (is_numeric($value->id_customer))
                                {{ $value->customerBy->name_cust }}
                            @else
                                {{ $value->id_customer }}
                            @endif
                        </h6>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('material-promotion/transaction/' . $item->id . '/approve') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="container-fluid">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">
                                        <div class="form-group row">
                                            <div class="col-md-4 form-group">
                                                <label>
                                                    Customer</label>
                                                <select name="id_customer" id="" required multiple
                                                    class="form-control supplier-select {{ $errors->first('id_customer') ? ' is-invalid' : '' }}">
                                                    @if (!is_numeric($item->id_customer))
                                                        <option value="{{ $item->id_customer }}" selected>
                                                            {{ $item->id_customer }}
                                                        </option>
                                                    @endif
                                                    <option value="-1">Other</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            @if ($item->id_customer == $customer->id) selected @endif>
                                                            {{ $customer->name_cust }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_customer')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 form-group mr-5">
                                                <label>Warehouse</label>
                                                <select name="id_warehouse" required id="warehouse" multiple
                                                    class="form-control warehouse-select {{ $errors->first('id_warehouse') ? ' is-invalid' : '' }}">
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->warehouse_id }}"
                                                            @if ($item->id_warehouse == $warehouse->warehouse_id) selected @endif>
                                                            {{ $warehouse->warehouseBy->warehouses }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_warehouse')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 form-group mr-5">
                                                <label>Order Date</label>

                                                <input class="datepicker-here form-control digits"
                                                    data-position="bottom left" type="text" data-language="en"
                                                    id="from_date" name="order_date"
                                                    data-value="{{ date('d-m-Y', strtotime($item->order_date)) }}"
                                                    autocomplete="off" required>
                                            </div>

                                            <div class="other-customer row" hidden>
                                                <div class="col-12 col-lg-6 form-group">
                                                    <label>Name</label>
                                                    <input class="form-control" name="name_cust" type="text"
                                                        placeholder="Enter customer name" />
                                                </div>
                                                <div class="col-12 col-lg-6 form-group">
                                                    <label>Address</label>
                                                    <input class="form-control" name="address_cust" type="text"
                                                        placeholder="Enter customer address" />
                                                </div>
                                            </div>

                                            <div class="col-md-12 form-group mr-5">
                                                <label>Remarks</label>
                                                <textarea class="form-control" name="remark" id="" cols="30" rows="1" required>{{ $item->remark }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group formPo">
                                            @foreach ($item->transactionDetailBy as $detail)
                                                <div class="form-group rounded row pt-2 mb-3 mx-auto"
                                                    style="background-color: #f0e194">
                                                    <input type="hidden" class="loop" value="{{ $loop->index }}">
                                                    <div class="form-group col-12 col-lg-5">
                                                        <label>Product</label>
                                                        <select name="poFields[{{ $loop->index }}][product_id]" multiple
                                                            class="form-control productPo" required>
                                                            @if ($detail->id_item != null)
                                                                <option value="{{ $detail->id_item }}" selected>
                                                                    {{ $detail->itemBy->name }}
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
                                                        <label>Price by Purchase</label>
                                                        <select name="poFields[{{ $loop->index }}][price]"
                                                            class="form-control price" required multiple>
                                                            <option value="{{ $detail->price }}" selected>
                                                                {{ number_format($detail->price) }}</option>
                                                        </select>
                                                        @error('poFields[{{ $loop->index }}][price]')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-6 col-lg-2 form-group">
                                                        <label>Qty</label>
                                                        <input type="number" class="form-control qtyPo" required
                                                            name="poFields[{{ $loop->index }}][qty]" id=""
                                                            value="{{ $detail->qty }}">
                                                        <small class="text-danger qty-warning" hidden>The number of items
                                                            exceeds the stock</small>
                                                        <small class="text-black qty-stock" hidden></small>
                                                        @error('poFields[{{ $loop->index }}][qty]')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    @if ($loop->index == 0)
                                                        <div class="col-12 col-lg-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a href="javascript:void(0)"
                                                                class="form-control text-white text-center addPo"
                                                                style="border:none; background-color:#276e61">+</a>
                                                        </div>
                                                    @else
                                                        <div class="col-6 col-lg-1 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a href="javascript:void(0)"
                                                                class="form-control text-white text-center addPo"
                                                                style="border:none; background-color:#276e61">+</a>
                                                        </div>
                                                        <div class="col-6 col-lg-1 form-group">
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
                <form method="post" action="{{ url('material-promotion/transaction/' . $item->id . '/reject') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Reject order from
                                @if (is_numeric($value->id_customer))
                                    {{ $value->customerBy->name_cust }}
                                @else
                                    {{ $value->id_customer }}
                                @endif
                            </h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <h5>Are you sure reject this transaction?</h5>
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

                    $(modal_id).find(".price").select2({
                        placeholder: 'Select the product first',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
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
                    let customer_id = "";

                    $(modal_id).find(".supplier-select").change(function() {
                        customer_id = $(this).val();

                        if (customer_id == '-1') {
                            $('.other-customer').attr('hidden', false);
                        } else {
                            $('.other-customer').attr('hidden', true);
                        }
                    });

                    let selected_warehouse = $(modal_id).find('#warehouse option:selected').val();
                    $(modal_id).find('.warehouse-select').change(function() {
                        selected_warehouse = $('.warehouse-select').val();
                        $(modal_id).find(".productPo").empty().trigger('change');
                    });


                    //Get Customer ID
                    $(modal_id).find(".productPo").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select a product',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            type: "GET",
                            url: "/material-promotion/select",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: selected_warehouse,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_barang,
                                            id: item.id_item,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $(document).on("change", ".productPo", function() {
                        product_id = $(this).val();
                        $(this).closest('.row').find(".price").empty().trigger('change');

                        $(this).closest('.row').find(".price").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select the price',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                type: "GET",
                                url: "/material-promotion/selectPrice",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        w: selected_warehouse,
                                        p: product_id,
                                        isreturn: false
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: parseInt(item.cost)
                                                    .toLocaleString() +
                                                    ' (' + item.qty +
                                                    ') ',
                                                id: item.cost,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });

                        setTimeout(() => {
                            $.ajax({
                                context: this,
                                type: "GET",
                                url: "/material-promotion/cekQty/" + product_id,
                                data: {
                                    _token: csrf,
                                    w: selected_warehouse,
                                },
                                dataType: "json",
                                success: function(data) {
                                    // console.log($(this).closest('.row').find(".qty-warning").html());
                                    $(this).closest('.row').find(".qty-stock")
                                        .removeAttr("hidden");
                                    $(this).closest('.row').find(".qty-stock").html(
                                        `Stock Total: ${data.qty}`);

                                },
                            });
                        }, 2000);

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
                            ' <div class="col-6 col-lg-3 form-group">' +
                            '<label>Price by Purchase</label>' +
                            '<select name="poFields[' + x + '][price]"' +
                            'class="form-control price" required multiple>' +
                            '</select>' +
                            '</div>' +
                            '<div class="col-6 col-lg-2 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control qtyPo" required name="poFields[' +
                            x +
                            '][qty]">' +
                            '<small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>' +
                            '<small class="text-black qty-stock" hidden></small>' +
                            '</div>' +
                            '<div class="col-3 col-lg-1 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a href="javascript:void(0)" class="form-control text-white addPo text-center" style="border:none; background-color:#276e61">' +
                            '+ </a> ' +
                            '</div>' +
                            '<div class="col-3 col-lg-1 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a href="javascript:void(0)" class="form-control text-white remPo text-center" style="border:none; background-color:#d94f5c">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find(".formPo").append(form);

                        $(modal_id).find(".price").select2({
                            placeholder: 'Select the product first',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                        });

                        $(modal_id).find(".productPo").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select a product',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                type: "GET",
                                url: "/material-promotion/select",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        w: selected_warehouse,
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.nama_barang,
                                                id: item.id_item,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                        $(modal_id).find('.productPo').last().select2('open');

                        $(document).on("change", ".productPo", function() {
                            product_id = $(this).val();
                            $(this).closest('.row').find(".price").empty().trigger('change');

                            $(this).closest('.row').find(".price").select2({
                                dropdownParent: modal_id,
                                placeholder: 'Select the price',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%',
                                ajax: {
                                    type: "GET",
                                    url: "/material-promotion/selectPrice",
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                            w: selected_warehouse,
                                            p: product_id,
                                            isreturn: false
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return [{
                                                    text: parseInt(
                                                            item
                                                            .cost)
                                                        .toLocaleString() +
                                                        ' (' + item
                                                        .qty +
                                                        ') ',
                                                    id: item.cost,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            });

                            setTimeout(() => {
                                $.ajax({
                                    context: this,
                                    type: "GET",
                                    url: "/material-promotion/cekQty/" +
                                        product_id,
                                    data: {
                                        _token: csrf,
                                        w: selected_warehouse,
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        // console.log($(this).closest('.row').find(".qty-warning").html());
                                        $(this).closest('.row').find(
                                                ".qty-stock")
                                            .removeAttr("hidden");
                                        $(this).closest('.row').find(
                                            ".qty-stock").html(
                                            `Stock Total: ${data.qty}`);

                                    },
                                });
                            }, 2000);

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
                            let cost = $(this).parent().siblings().find('.price').val();
                            let qty = $(this).parent().siblings().find('.qtyPo').val();
                            total = total + (parseInt(cost) * qty);
                            // console.log(total);
                            //   alert($(this).parent().siblings().find('.cekQty-edit').val());
                        });
                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(
                                total)
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
