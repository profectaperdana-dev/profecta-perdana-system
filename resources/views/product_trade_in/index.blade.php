@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        @include('report.style')
        <style>
            .loader-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.5);
                z-index: 9999;
            }

            .loader-box {
                text-align: center;
            }

            .loader-39 {
                width: 35px;
                height: 35px;
                margin: 0 auto;
                position: relative;
                border: 4px solid #f2f2f2;
                border-top: 4px solid #3498db;
                border-radius: 50%;
                animation: loader-39 1.5s linear infinite;
            }

            @keyframes loader-39 {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="font-weight-bold">{{ $title }}</h4>

                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Create Data</h6>
                        <hr class="bg-primary">
                        <form class="needs-validation" novalidate method="post" action="{{ url('/trade_in') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="font-weight-bold">Product Name</label>
                                        <input type="text" class="form-control text-capitalize" required
                                            name="name_product_trade_in" placeholder="Product Name" autocomplete="off">
                                    </div>
                                    <div class="form-price">
                                        <div class="mx-auto text-dark py-2 rounded form-group row"
                                            style="background-color: #f0e194">
                                            <div class="col-12 col-lg-4 form-group">
                                                <label for="">Warehouse</label>
                                                <select name="priceForm[0][warehouse]" multiple required
                                                    class="form-control warehouse">

                                                </select>
                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Purchase Price</label>
                                                <input type="text" class="form-control text-capitalize purchase_price"
                                                    required placeholder="0">
                                                <input type="hidden" name="priceForm[0][price_purchase]"
                                                    class="purchase_price_" value="">
                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Sale Price</label>
                                                <input type="text" class="form-control text-capitalize sale_price"
                                                    required placeholder="0">
                                                <input type="hidden" name="priceForm[0][price_sale]" class="sale_price_"
                                                    value="">
                                            </div>
                                            <div class="col-2 col-md-4 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)"
                                                    class="addPrice form-control text-white  text-center"
                                                    style="border:none; background-color:#2b786a">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="reset" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                        <button type="submit" class="btn btn-primary">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                                aria-hidden="true"></span>
                                            <span class="sr-only">Loading...</span>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>All Data</h6>
                        <hr class="bg-primary">
                        <div class="table-responsive">
                            <table id="basics" class="table table-sm table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center text-capitalize">
                                        <th style="width: 5%">#</th>
                                        <th style="width: 10%">Status</th>
                                        <th>Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td class="fw-bold text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">
                                                @if ($value->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            <td>
                                                <a href="#" class="fw-bold text-success modal-btn2" href="#"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#changeData{{ $value->id }}">{{ $value->name_product_trade_in }}</a>
                                            </td>
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
    {{-- Modul Edit UOM --}}
    @foreach ($data as $key => $value)
        <div data-bs-backdrop="static" class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <form class="needs-validation" novalidate method="post" action="{{ url('trade_in/' . $value->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">Change {{ $value->name_product_trade_in }}
                            </h6>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8 form-group">
                                    <label class="font-weight-bold">Product Name</label>
                                    <input type="text" class="form-control text-capitalize" required
                                        name="name_product" placeholder="Item Name"
                                        value="{{ $value->name_product_trade_in }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select multiple name="status" class="form-control status" required>
                                        <option value="1" {{ $value->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $value->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="form-price-edit">
                                    @foreach ($value->productCostSecond as $item)
                                        <div class="mx-auto Price text-dark py-2 rounded form-group row"
                                            style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">

                                            <div class="col-12 col-lg-4 form-group">
                                                <label for="">Warehouse</label>
                                                <select name="priceForm[{{ $loop->index }}][warehouse]" multiple required
                                                    class="form-control warehouse">
                                                    <option value="{{ $item->id_warehouse }}" selected>
                                                        {{ $item->warehouseBy->warehouses }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-10 col-lg-2 form-group pricePurchase">
                                                <label class="font-weight-bold">Purchase Price</label>
                                                <input type="text" class="form-control text-capitalize purchase_price"
                                                    required placeholder="0" value="{{ $item->price_purchase }}">
                                                <input type="hidden"
                                                    name="priceForm[{{ $loop->index }}][price_purchase]"
                                                    class="purchase_price_" value="{{ $item->price_purchase }}">
                                            </div>
                                            <div class="col-10 col-lg-2 form-group priceSale">
                                                <label class="font-weight-bold">Sale Price</label>
                                                <input type="text" class="form-control text-capitalize sale_price"
                                                    required placeholder="0" value="{{ $item->price_sale }}">
                                                <input type="hidden" name="priceForm[{{ $loop->index }}][price_sale]"
                                                    class="sale_price_" value="{{ $item->price_sale }}">
                                            </div>
                                            @if ($loop->index == 0)
                                                <div class="col-2 col-md-4 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="addPriceModal form-control text-white  text-center"
                                                        style="border:none; background-color:#2b786a">+</a>
                                                </div>
                                            @else
                                                <div class="col-2 col-md-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="removePriceModal form-control text-white  text-center"
                                                        style="border:none; background-color:#d94f5c">-</a>
                                                </div>
                                                <div class="col-2 col-md-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="addPriceModal form-control text-white  text-center"
                                                        style="border:none; background-color:#2b786a">+</a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                            </div>


                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-original-title="test"
                                data-bs-target="#deleteData{{ $value->id }}">Delete</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    {{-- End Modal Edit UOM --}}

    @foreach ($data as $key => $value)
        {{-- Modul Delete UOM --}}
        <div data-bs-backdrop="static" class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('trade_in/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">Delete product
                                {{ $value->name_product_trade_in }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <h5>Are you sure delete this data ?</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Yes, delete
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Modal Delete UOM --}}
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(function() {

                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
                $(document).on('submit', 'form', function() {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    if (validator.checkAll() == 0) {
                        button.prop('disabled', true);
                        $(this).find('.spinner-border').removeClass('d-none');
                        $(this).find('span:not(.spinner-border)').addClass('d-none');
                        $(this).off('click');
                    } else {
                        button.prop('disabled', false);
                    }
                });

                validator.reload();
            });
        </script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");

                $(".warehouse").select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/get_warehouse/",
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
                                        text: item.warehouses,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });
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
                    pageLength: -1,
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
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.purchase_price').on('change', function() {
                    let priceReal = $(this).val();
                    let price = priceReal.replace(/\./g, '');
                    let myPrice = price.split(',');
                    let finalPrice = '';
                    if (myPrice.length > 1) {
                        let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1 + ',' + myPrice[1];
                    } else {
                        let myPrice_1 = parseInt(price).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1;
                    }
                    $(this).val(finalPrice);
                    $(this).next().val(price);
                });
                $('.sale_price').on('change', function() {
                    let priceReal = $(this).val();
                    let price = priceReal.replace(/\./g, '');
                    let myPrice = price.split(',');
                    let finalPrice = '';
                    if (myPrice.length > 1) {
                        let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1 + ',' + myPrice[1];
                    } else {
                        let myPrice_1 = parseInt(price).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1;
                    }
                    $(this).val(finalPrice);
                    $(this).next().val(price);
                });



            });
        </script>

        <script>
            let y = 0;
            $(document).off('click', '.addPrice');
            $(document).on('click', '.addPrice', function() {
                y++;
                let csrf = $('meta[name="csrf-token"]').attr("content");

                let form = `
                                        <div class="mx-auto text-dark py-2 rounded form-group row"
                                            style="background-color: #f0e194">
                                            <div class="col-12 col-lg-4 form-group">
                                                <label for="">Warehouse</label>
                                                <select name="priceForm[${y}][warehouse]" multiple required
                                                    class="form-control warehouse">

                                                </select>
                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Purchase Price</label>
                                                <input type="text" class="form-control text-capitalize purchase_price"
                                                    required placeholder="0">
                                                <input type="hidden" name="priceForm[${y}][price_purchase]"
                                                    class="purchase_price_" value="">
                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Sale Price</label>
                                                <input type="text" class="form-control text-capitalize sale_price"
                                                    required placeholder="0">
                                                <input type="hidden" name="priceForm[${y}][price_sale]" class="sale_price_"
                                                    value="">
                                            </div>
                                            <div class="col-2 col-md-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)" class="remPrice form-control text-white text-center" style="border:none; background-color:#d94f5c">-</a>
                                            </div>
                                            <div class="col-2 col-md-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)"
                                                    class="addPrice form-control text-white  text-center"
                                                    style="border:none; background-color:#2b786a">+</a>
                                            </div>
                                        </div>
                `;
                $(".form-price").append(form);
                $(".warehouse").select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/get_warehouse/",
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
                                        text: item.warehouses,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });
                $('.purchase_price').on('change', function() {
                    let priceReal = $(this).val();
                    let price = priceReal.replace(/\./g, '');
                    let myPrice = price.split(',');
                    let finalPrice = '';
                    if (myPrice.length > 1) {
                        let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1 + ',' + myPrice[1];
                    } else {
                        let myPrice_1 = parseInt(price).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1;
                    }
                    $(this).val(finalPrice);
                    $(this).next().val(price);
                });
                $('.sale_price').on('change', function() {
                    let priceReal = $(this).val();
                    let price = priceReal.replace(/\./g, '');
                    let myPrice = price.split(',');
                    let finalPrice = '';
                    if (myPrice.length > 1) {
                        let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1 + ',' + myPrice[1];
                    } else {
                        let myPrice_1 = parseInt(price).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1;
                    }
                    $(this).val(finalPrice);
                    $(this).next().val(price);
                });
                $(document).on("click", ".remPrice", function() {
                    $(this).parents(".form-group").remove();
                });
            });
        </script>
        <script>
            $(document).on("click", ".modal-btn2", function(event) {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                let modal_id = $(this).attr('data-bs-target');
                $(modal_id).find('.status').select2({
                    dropdownParent: modal_id,
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: "100%",
                });
                $(modal_id).find(".warehouse").select2({
                    dropdownParent: modal_id,
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/get_warehouse/",
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
                                        text: item.warehouses,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });
                $('.Price').find('.pricePurchase').each(function(index, value) {
                    // console.log();
                    let priceReal = $(this).find('.purchase_price_').val();
                    let price = priceReal.replace(/\./g, '');
                    let myPrice = price.split(',');
                    let finalPrice = '';
                    if (myPrice.length > 1) {
                        let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1 + ',' + myPrice[1];
                    } else {
                        let myPrice_1 = parseInt(price).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1;
                    }
                    $(this).find('.purchase_price').val(finalPrice);
                });
                $('.Price').find('.priceSale').each(function(index, value) {
                    // console.log();
                    let priceReal = $(this).find('.sale_price_').val();
                    let price = priceReal.replace(/\./g, '');
                    let myPrice = price.split(',');
                    let finalPrice = '';
                    if (myPrice.length > 1) {
                        let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1 + ',' + myPrice[1];
                    } else {
                        let myPrice_1 = parseInt(price).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1;
                    }
                    $(this).find('.sale_price').val(finalPrice);
                });
                let z = $(modal_id)
                    .find('.modal-body')
                    .find('.row')
                    .find('.form-price-edit')
                    .find('.form-group')
                    .find('.loop')
                    .last()
                    .val();
                $(document).off('click', '.addPriceModal');
                $(document).on('click', '.addPriceModal', function() {
                    z++;
                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    let form = `  <div class="mx-auto text-dark py-2 rounded form-group row"
                                            style="background-color: #f0e194">
                                            <div class="col-12 col-lg-4 form-group">
                                                <label for="">Warehouse</label>
                                                <select name="priceForm[${z}][warehouse]" multiple required
                                                    class="form-control warehouse">

                                                </select>
                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Purchase Price</label>
                                                <input type="text" class="form-control text-capitalize purchase_price"
                                                    required placeholder="0">
                                                <input type="hidden" name="priceForm[${z}][price_purchase]"
                                                    class="purchase_price_" value="">
                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Sale Price</label>
                                                <input type="text" class="form-control text-capitalize sale_price"
                                                    required placeholder="0">
                                                <input type="hidden" name="priceForm[${z}][price_sale]" class="sale_price_"
                                                    value="">
                                            </div>
                                            <div class="col-2 col-md-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)" class="remPriceModal form-control text-white text-center" style="border:none; background-color:#d94f5c">-</a>
                                            </div>
                                            <div class="col-2 col-md-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)"
                                                    class="addPriceModal form-control text-white  text-center"
                                                    style="border:none; background-color:#2b786a">+</a>
                                            </div>
                                        </div>
                          `;
                    $(modal_id).find(".form-price-edit").append(form);
                    $(modal_id).find(".warehouse").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/get_warehouse/",
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
                                            text: item.warehouses,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                    $(modal_id).find('.purchase_price').on('change', function() {
                        let priceReal = $(this).val();
                        let price = priceReal.replace(/\./g, '');
                        let myPrice = price.split(',');
                        let finalPrice = '';
                        if (myPrice.length > 1) {
                            let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                                'id', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            finalPrice = myPrice_1 + ',' + myPrice[1];
                        } else {
                            let myPrice_1 = parseInt(price).toLocaleString(
                                'id', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            finalPrice = myPrice_1;
                        }
                        $(this).val(finalPrice);
                        $(this).next().val(price);
                    });
                    $(modal_id).find('.sale_price').on('change', function() {
                        let priceReal = $(this).val();
                        let price = priceReal.replace(/\./g, '');
                        let myPrice = price.split(',');
                        let finalPrice = '';
                        if (myPrice.length > 1) {
                            let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                                'id', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            finalPrice = myPrice_1 + ',' + myPrice[1];
                        } else {
                            let myPrice_1 = parseInt(price).toLocaleString(
                                'id', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            finalPrice = myPrice_1;
                        }
                        $(this).val(finalPrice);
                        $(this).next().val(price);
                    });

                });
                $(modal_id).on("click", ".removePriceModal", function() {
                    $(this).closest(".row").remove();
                });


            });
        </script>
    @endpush
@endsection
