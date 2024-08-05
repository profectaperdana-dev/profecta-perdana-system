@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    

                </div>

            </div>
        </div>
    </div>




    <div class="position-relative">
        <div class="position-fixed bottom-0 end-0" style="z-index: 100">
            <button class="btn btn-primary btn-circle btn-xl me-3 mb-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#miniCart" data-bs-toggle="tooltip" data-bs-placement="top" title="Cart">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" id="count-cart">
                    0
                    <span class="visually-hidden">Products in Cart</span>
                </span>
            </button>
        </div>
    </div>

    <input type="hidden" name="" id="ppn" value="{{ $ppn }}">
    <!-- Container-fluid starts-->
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Product List</h5>
                        <hr class="bg-primary">
                    </div>


                    <div class="card-body">
                        @include('direct_sales._form')
                    </div>
                </div>
            </div>

        </div>
        <div class="offcanvas offcanvas-end" id="miniCart" data-bs-scroll="true" data-bs-backdrop="false">
            <div class="offcanvas-header" id="headerCart">
                <h4 class="offcanvas-title" id="offcanvasExampleLabel"><strong>Cart</strong></h4>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <hr class="bg-primary">
                <div class="row">
                    <div class="mb-3 col-12">
                        <label>Name</label>
                        <select class="form-control select-cust" name="cust_name" id="cust" required>
                            <option selected="" value="">Choose Customer
                            </option>
                            <option value="other_cust">Other
                            </option>
                            @foreach ($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name_cust }}
                                </option>
                            @endforeach
                        </select>
                        <input class="form-control manual-cust" placeholder="Enter Name" type="text"
                            name="cust_name_manual" hidden>
                    </div>
                </div>

                <div id="table-cart">
                    {{-- @foreach ($retail_products as $item)
                                <tr class="form-group">
                                    <td><button type="button" class="btn btn-sm p-3 text-center text-danger fs-5"><i
                                                class="fa fa-trash" aria-hidden="true"></i>
                                        </button></td>
                                    <td>{{ $item->materials->nama_material }} -
                                        {{ $item->sub_materials->nama_sub_material }} {{ $item->sub_types->type_name }}:
                                        <strong>{{ $item->nama_barang }}</strong>
                                    </td>
                                    <td><strong><input type="number" class="form-control" name=""></strong></td>
                                    <input type="hidden" name="retails[0]['product_id']" value="">
                                    <input type="hidden" name="retails[0]['qty']" value="">
                                    <input type="hidden" name="retails[0]['discount']" value="">
                                </tr>
                                @if ($loop->iteration == 5)
                                @break
                            @endif
                        @endforeach --}}
                </div>


                <br>
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-secondary col-12 modalButton" data-bs-toggle="modal"
                            data-bs-target="#checkoutModal">Checkout</button>
                    </div>
                </div>

            </div>


        </div>

        <!-- Modal -->
        <div class="modal fade" id="checkoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Checkout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Billing Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-danger fade show alert-code" role="alert" hidden>
                                        <strong>Warning!</strong> There are empty serial codes, or you have not selected a
                                        customer. Please check them!

                                    </div>
                                    <div class="alert alert-danger fade show alert-emptyDOT" role="alert" hidden>
                                        <strong>Warning!</strong> There are empty DOT. Please check them!

                                    </div>
                                    <div class="alert alert-danger fade show alert-exceedDOT" role="alert" hidden>
                                        <strong>Warning!</strong> The DOT you have selected exceeds the number of available
                                        DOTs. Please check again.
                                    </div>
                                    <form action="{{ url('retail/store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-6 col-sm-12">
                                                <div class="row">
                                                    <div class="mb-3 col-sm-6 cust-parent">
                                                        {{-- <label>Name</label>
                                                        <select class="form-control select2" name="cust_name"
                                                            id="cust" required>
                                                            <option selected="" value="">Choose Customer
                                                            </option>
                                                            <option value="other_cust">Other
                                                            </option>
                                                            @foreach ($customers as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name_cust }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input class="form-control manual-cust" placeholder="Enter Name"
                                                            type="text" name="cust_name_manual" hidden> --}}
                                                    </div>
                                                    <div class="mb-3 col-sm-6 phone" hidden>
                                                        <label>Phone Number</label>
                                                        <input class="form-control" placeholder="Enter Phone Number"
                                                            type="text" name="cust_phone">
                                                    </div>
                                                </div>
                                                <div class="row id_card" hidden>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>ID Card Number</label>
                                                        <input class="form-control" placeholder="Enter ID Card Number"
                                                            type="text" name="cust_ktp">
                                                        <div class="form-text">*Optional</div>
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Email Address</label>
                                                        <input class="form-control" placeholder="Enter Email"
                                                            type="text" name="cust_email">
                                                        <div class="form-text">*Optional</div>

                                                    </div>
                                                </div>
                                                <div class="row plate" hidden>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Plate Number</label>
                                                        <input class="form-control" placeholder="Enter Plate Number"
                                                            type="text" name="plate_number">
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Vehicle</label>
                                                        <select class="form-control select2" name="vehicle"
                                                            id="vehicle">
                                                            <option value="">Choose Vehicle</option>
                                                            <option value="Car">Car</option>
                                                            <option value="Motocycle">Motorcycle</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row" id="car" hidden>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Car Brand</label>
                                                        <select class="form-control select2" name="car_brand_id"
                                                            id="car-brand">
                                                            <option selected="" value="">Choose Car Brand
                                                            </option>
                                                            @foreach ($car_brands as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->car_brand }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Car Type</label>
                                                        <select class="form-control select2" id="car-type"
                                                            name="car_type_id">
                                                            <option selected="" value="">Choose...</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-12" id="other-car" hidden>
                                                        <label>Other Car Type</label>
                                                        <input type="text" placeholder="Enter Other Car Type"
                                                            name="other_car" id="other_car_input" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row" id="motor" hidden>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Motocycle Brand</label>
                                                        <select class="form-control select2" id="motor-brand"
                                                            name="motor_brand_id">
                                                            <option selected="" value="">Choose...</option>
                                                            @foreach ($motor_brands as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name_brand }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Motocycle Type</label>
                                                        <select class="form-control" id="motor-type"
                                                            name="motor_type_id">
                                                            <option selected="" value="">Choose...</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-12" id="other-motor" hidden>
                                                        <label>Other Motorcycle Type</label>
                                                        <input type="text" placeholder="Enter Other Motorcycle Type"
                                                            name="other_motor" id="other_motor_input"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row" id="other" hidden>
                                                    <div class="mb-3 col-12">
                                                        <label>Other</label>
                                                        <input class="form-control" placeholder="Enter Other Machine"
                                                            type="text" name="other">
                                                    </div>
                                                </div>
                                                <div class="row geo" hidden>
                                                    <div class="form-group col-md-4">
                                                        <label>Province</label>
                                                        <select name="province"
                                                            class="form-control province @error('province') is-invalid @enderror">
                                                            {{-- @if ($customer->province != null)
                                                                <option selected value="{{ $customer->province }}">
                                                                    {{ $customer->province }}
                                                                </option>
                                                            @endif --}}
                                                        </select>
                                                        @error('province')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>District</label>
                                                        <select name="district"
                                                            class="form-control city @error('district') is-invalid @enderror">
                                                            {{-- @if ($customer->city != null)
                                                                <option selected value="{{ $customer->city }}">
                                                                    {{ $customer->city }}
                                                                </option>
                                                            @endif --}}
                                                        </select>
                                                        @error('district')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Sub-district</label>
                                                        <select name="sub_district"
                                                            class="form-control district @error('sub_district') is-invalid @enderror">
                                                            {{-- @if ($customer->district != null)
                                                                <option selected value="{{ $customer->district }}">
                                                                    {{ $customer->district }}
                                                                </option>
                                                            @endif --}}
                                                        </select>
                                                        @error('sub_district')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-sm-6 address" hidden>
                                                        <label>Address</label>
                                                        <input type="text" placeholder="Enter Address"
                                                            class="form-control" name="address">
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Remark</label>
                                                        <input class="form-control" placeholder="Enter Remark"
                                                            type="text" name="remark" required>
                                                    </div>
                                                    <div class="mb-3 col-sm-6 payment_method">
                                                        <label>Payment Method</label>
                                                        <select class="form-control select2" name="payment_method">
                                                            <option value="1" selected>
                                                                Cash
                                                            </option>
                                                            <option value="0">
                                                                Credit
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-12">
                                                        <label>Delivery Point</label>
                                                        <select class="form-control select2" name="delivery_point_option"
                                                            id="delivery_point">
                                                            <option value="1" selected>
                                                                Same as Address
                                                            </option>
                                                            <option value="0">
                                                                Other Delivery Point
                                                            </option>
                                                        </select>
                                                        <input hidden id="delivery_point_input" class="form-control"
                                                            placeholder="Enter Delivery Point" type="text"
                                                            name="delivery_point_value">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12">
                                                <div class="checkout-details">
                                                    <div class="order-box">
                                                        <div class="title-box">
                                                            <div class="checkbox-title">
                                                                <h4 class="mb-0">Product </h4><span>Total</span>
                                                            </div>
                                                        </div>
                                                        <div id="products-detail">
                                                            <ul class="qty" id="products-qty-total">
                                                            </ul>
                                                            <ul class="sub-total" id="total-exl-wrap">
                                                                <li>Total (Excl. PPN) <span class="count"
                                                                        id="total-exl"></span>
                                                                </li>
                                                            </ul>
                                                            <ul class="sub-total" id="ppn-wrap">
                                                                <li>PPN ({{ $ppn * 100 }}%) <span class="count"
                                                                        id="ppn-total"></span>
                                                                </li>
                                                            </ul>
                                                            <hr>
                                                            <ul class="sub-total total" id="total-wrap">
                                                                <li>Total <span class="count" id="total"></span>
                                                                </li>
                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button id="btn-order" type="submit" class="btn btn-primary">Order</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="https://apps.profectaperdana.com/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
        <script src="https://apps.profectaperdana.com/assets/js/datatable/datatables/datatable.custom.js"></script>
        <script>
            $(document).ready(function() {

                $('form').submit(function() {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);
                        // e.preventDefault(); // prevent form submission
                    }
                });
                let csrf = $('meta[name="csrf-token"]').attr("content");
                let count_cart = 0;
                let arr_product_id = [];
                let arr_product_name = [];
                let arr_material = [];
                let arr_sub_material = [];
                let arr_sub_type = [];
                let arr_harga_jual = [];
                let modal_id = $('.modalButton').attr('data-bs-target');
                let warehouse = $('.warehouse.active').attr('data-id');

                $('.select2').select2({
                    width: "100%",
                    dropdownParent: modal_id,
                });
                $('.select-cust').select2({
                    width: "100%",
                });
                //Add Product to Cart
                $(document).on('click', '.addProduct', function() {
                    $(this).prop('disabled', true);
                    $(this).html('<i class="fa fa-check" aria-hidden="true"></i>');
                    $('#count-cart').text(++count_cart);

                    let product_id = $(this).parent().find('.product_id').val();
                    arr_product_id.push(product_id);

                    let product_name = $(this).parent().find('.product_name').val();
                    arr_product_name.push(product_name);

                    let material = $(this).parent().find('.material').val();
                    arr_material.push(material);

                    let sub_material = $(this).parent().find('.sub-material').val();
                    arr_sub_material.push(sub_material);

                    let sub_type = $(this).parent().find('.sub-type').val();
                    arr_sub_type.push(sub_type);

                    let harga_jual = $(this).parent().find('.harga').val();
                    arr_harga_jual.push(harga_jual);

                    let price_ppn = $(this).parent().siblings('.product-details').find('.product-price').text();

                    let addCart = `
                    
                    <div class="bg-light text-dark py-2 mb-2 parent-cart">
                        <div class="row">
                            <div class="col-4 align-self-end me-1 bg-primary fw-bold">${price_ppn}</div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-2">
                                <button type="button" class="btn btn-sm p-3 text-center text-danger fs-5 remProduct"><i
                                        class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                            <input type="hidden" class="product-id-cart" value="${product_id}">
                            <div class="col-10 dataProduct" data-material="${material}" data-productId="${product_id}" 
                            data-warehouse="${warehouse}">
                                ${ sub_material} -
                                ${sub_type}:
                                <strong>${product_name}</strong>
                            </div>
                        </div>
                        <div class="row mx-2">
                            <div class="col-4">
                                <label for="">Qty</label>
                                <input type="number" value="0" class="form-control qty-cart" name="" id="qty${product_id}">
                            </div>
                            <div class="col-4">
                                <label for="">Disc (%)</label>
                                <input type="text" placeholder="0" required class="form-control" name="" id="disc${product_id}">
                            </div>
                            <div class="col-4">
                                <label for="">Disc (Rp)</label>
                                <input type="text"  class="form-control diskon" name="">
                                <input type="hidden" value="0" id="discrp${product_id}">
                            </div>
                        </div>
                        <div class="row mx-2 mt-3 series-code" data-code="code${product_id}" data-dot="dot${product_id}">

                        </div>
                    </div>`;

                    let cust_id_in_add = $('#cust').val();

                    function makeAjaxCall(cust_id_in_add) {
                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/discounts/select/" + cust_id_in_add + "/" + product_id,
                            data: {
                                _token: csrf,
                            },
                            dataType: "json",
                            delay: 250,
                            success: function(data) {
                                if (data.discount == null || data.discount == "") {
                                    $('#disc' + product_id).val(0);
                                } else {
                                    $('#disc' + product_id).val(data.discount);
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("Status: " + textStatus);
                                alert("Error: " + errorThrown);
                            },
                        });
                    }

                    if (cust_id_in_add.trim() != '') {

                        makeAjaxCall(cust_id_in_add);
                    }

                    $(document).find('#table-cart').append(addCart);
                    $('.diskon').on('keyup', function() {
                        var selection = window.getSelection().toString();
                        if (selection !== '') {
                            return;
                        }
                        // When the arrow keys are pressed, abort.
                        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                            return;
                        }
                        var $this = $(this);
                        // Get the value.
                        var input = $this.val();
                        var input = input.replace(/[\D\s\._\-]+/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return (input === 0) ? "" : input.toLocaleString();
                        });
                        $this.next().val(input);

                    });
                });

                $('#table-cart').on('change', '.qty-cart', function() {
                    $(this).closest('.row').siblings('.series-code').html('');
                    let count = $(this).val();
                    let code = $(this).closest('.row').siblings('.series-code').attr('data-code');
                    let dataDot = $(this).closest('.row').siblings('.series-code').attr('data-dot');
                    let qtyNode = $(this);

                    for (let index = 0; index < count; index++) {
                        let productMaterial = $(this).closest('.row').siblings('.align-items-center').find(
                                '.dataProduct')
                            .attr('data-material');
                        let element_series_code;
                        if (productMaterial == 'Tyre') {
                            element_series_code = `<div class="col-6 mt-2">
                            <input type="text" placeholder="Series Code" class="form-control ${code} series-input" name="" required>
                                </div>
                                `;
                            let dot_element = `<div class="col-6 mt-2 clickDOT" readonly>
                                    <select class="form-control selectDOT ${dataDot}" data-dataDot="${dataDot}" name="">
                                    </select>
                                </div>`;
                            element_series_code = element_series_code + dot_element;
                        } else {
                            element_series_code = `<div class="col-12 mt-2">
                            <input type="text" placeholder="Series Code" class="form-control ${code} series-input" name="" required>
                        </div>`;
                        }
                        $(this).closest('.row').siblings('.series-code').append(element_series_code);
                    }

                    let productId = $(this).closest('.row').siblings('.align-items-center').find(
                            '.dataProduct')
                        .attr('data-productId');
                    let warehouseId = $(this).closest('.row').siblings('.align-items-center').find(
                            '.dataProduct')
                        .attr('data-warehouse');

                    $('.selectDOT').select2({
                        width: "100%",
                        placeholder: 'Select DOT',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/tyre_dot/selectDot",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouseId,
                                    p: productId
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item
                                                .dot +
                                                " (" +
                                                item.qty + ") ",
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                });

                $(document).on('change', '.selectDOT', function() {
                    let textDOT = $(this).find(':selected').text();
                    let valueDOT = $(this).val();
                    let parseDataDot = $(this).attr('data-dataDot');

                    $(this).attr('disabled', true);
                    $(this).parent().removeClass('col-6');
                    $(this).parent().addClass('col-5');
                    let newElementDot =
                        `
                <div class="col-1 mt-3 parentResetDot">
                    <a href="javascript:void()" class="link-danger resetDot"><i
                                class="fa fa-trash" aria-hidden="true"></i></a>
                </div>`;
                    $(this).parent().after(newElementDot);
                    // console.log($(this).closest('.clickDOT').html());

                });

                $(document).on('click', '.resetDot', function() {
                    $(this).parent().prev().find('.selectDOT').attr('disabled', false);
                    $(this).parent().prev('.clickDOT').removeClass('col-5');
                    $(this).parent().prev('.clickDOT').addClass('col-6');
                    let productId = $(this).closest('.row').siblings('.align-items-center').find(
                            '.dataProduct')
                        .attr('data-productId');
                    let warehouseId = $(this).closest('.row').siblings('.align-items-center').find(
                            '.dataProduct')
                        .attr('data-warehouse');

                    // console.log('product id: ' + productId);
                    // console.log('warehouse id: ' + warehouseId);

                    // $('.selectDOT').select2('destroy');

                    $('.selectDOT').select2({
                        width: "100%",
                        placeholder: 'Select DOT',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/tyre_dot/selectDot",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouseId,
                                    p: productId
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item
                                                .dot +
                                                " (" +
                                                item.qty + ") ",
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },

                        },
                    });
                    $(this).parent().remove();

                    // $(this).remove();
                });

                // $(document).on('click', '.clickDOT', function() {
                //     let productId = $(this).closest('.row').siblings('.align-items-center').find(
                //             '.dataProduct')
                //         .attr('data-productId');
                //     let warehouseId = $(this).closest('.row').siblings('.align-items-center').find(
                //             '.dataProduct')
                //         .attr('data-warehouse');

                //     console.log('product id: ' + productId);
                //     console.log('warehouse id: ' + warehouseId);

                //     $('.selectDOT').select2('destroy');

                //     $('.selectDOT').select2({
                //         width: "100%",
                //         ajax: {
                //             context: this,
                //             type: "GET",
                //             url: "/tyre_dot/selectDot",
                //             data: function(params) {
                //                 return {
                //                     _token: csrf,
                //                     q: params.term, // search term
                //                     w: warehouseId,
                //                     p: productId
                //                 };
                //             },

                //         },
                //     });

                //     // $(this).trigger('mousedown');
                // });


                //Remove Product to Cart
                $(document).on('click', '.remProduct', function() {
                    let product_id_cart = $(this).parents().parents().children('.product-id-cart').val();
                    let parent_node = $('#product-list').find('#detailProduct' + product_id_cart).next().next();

                    parent_node.find('.addProduct').prop('disabled', false).html('Add');
                    let selected_product_id = parent_node.find('.product_id').val();
                    let indexArray = arr_product_id.indexOf(selected_product_id);
                    arr_product_id.splice(indexArray, 1);
                    arr_product_name.splice(indexArray, 1);
                    arr_material.splice(indexArray, 1);
                    arr_sub_material.splice(indexArray, 1);
                    arr_sub_type.splice(indexArray, 1);
                    arr_harga_jual.splice(indexArray, 1);

                    $(this).closest('.parent-cart').remove();
                    $('#count-cart').text(--count_cart);
                });

                // let warehouse = $('.warehouse.active').attr('data-id');

                $(document).on('click', '.warehouse', function() {
                    warehouse = $(this).attr('data-id');
                    $('.warehouse').removeClass('active');
                    $(this).addClass('active');
                    $('.btn-sub').removeClass('active');
                    $('.btn-sub[data-id="all"]').addClass('active');

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/retail/selectWarehouse",
                        data: {
                            _token: csrf,
                            w: warehouse
                        },
                        dataType: "json",
                        delay: 250,
                        success: function(data) {
                            let old = $('#product-list').html();
                            let ppn_current = $('#ppn').val();

                            $('#product-list').html('');

                            $.each(data, function(index, value) {
                                let ppns = parseFloat(value.harga_jual) *
                                    ppn_current;
                                let harga_jual = parseFloat(value.harga_jual) + ppns;
                                let list = `
                            <div class="col-12 col-xl-3 col-sm-6 xl-3">
                                <div class="card">
                                    <div class="product-box">
                                        <div class="product-img"><img class="img-fluid" style="width: 100%;height:229px"
                                                src="../foto_produk/${(value.foto_barang == null) ? 'no-image.jpg' : value.foto_barang}" alt="">
                                            <div class="product-hover">
                                                <ul>
                                                    <li><a data-bs-toggle="modal"
                                                            data-bs-target="#detailProduct${value.id_product}"><i
                                                                class="icon-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="detailProduct${value.id_product}">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class="product-box row">
                                                            <div class="product-img col-lg-6"><img class="img-fluid"
                                                                    src="../foto_produk/${(value.foto_barang == null) ? 'no-image.jpg' : value.foto_barang}"
                                                                    alt="">
                                                            </div>
                                                            <div class="product-details col-lg-6 text-start">
                                                                <h4>${value.nama_barang}</h4>

                                                                <div class="product-price">Rp.
                                                                    ${harga_jual.toLocaleString('en', {
                                                                        minimumFractionDigits: 0,
                                                                        maximumFractionDigits: 0
                                                                    })}
                                                                </div>
                                                                <div class="product-view">
                                                                    <h6 class="f-w-600">Product Details</h6>
                                                                    <p class="mb-0">
                                                                    <ul>
                                                                        <li><strong>Material</strong>:
                                                                            ${value.nama_material}</li>
                                                                        <li><strong>Sub-Material</strong>:
                                                                            ${value.nama_sub_material}</li>
                                                                        <li><strong>Type</strong>:
                                                                            ${value.type_name}</li>
                                                                        <li><strong>Weight</strong>:
                                                                            ${parseInt(value.berat).toLocaleString('en', {
                                                                            minimumFractionDigits: 0,
                                                                            maximumFractionDigits: 0
                                                                            })} gr</li>
                                                                    </ul>
                                                                    </p>
                                                                </div>
                                                                <br>
                                                                <div class="product-qnty">
                                                                    <h6 class="f-w-600">Stock:
                                                                        ${(value.stock ==null)? 0 : value.stock} ${value.satuan}
                                                                    </h6>
                                                                </div>
                                                                <div class="product-qnty">

                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-details">
                                            <h4>${value.nama_barang} </h4>

                                            <p>${value.nama_material} -
                                                ${value.nama_sub_material}
                                                ${value.type_name}</p>
                                            <div class="product-price">Rp.
                                                ${harga_jual.toLocaleString('en', {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 0
                                                })}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row-reverse m-1 nodeButton justify-content-between">
                                            <button type="button" class="btn btn-primary me-3 addProduct">Add
                                            </button>
                                            <div class="text-start my-auto ms-1 fs-6"><i class="fa fa-cubes"></i>
                                                ${(value.stock ==null)? 0 : value.stock} ${value.satuan}
                                            </div>
                                            <!-- Start Parsing Data -->
                                            <input type="hidden" class="product_id" value="${value.id_product}">
                                            <input type="hidden" class="product_name" value="${value.nama_barang}">
                                            <input type="hidden" class="material"
                                                value="${value.nama_material}">
                                            <input type="hidden" class="sub-material"
                                                value="${value.nama_sub_material}">
                                            <input type="hidden" class="sub-type" value="${value.type_name}">
                                            <input type="hidden" class="harga" value="${value.harga_jual}">
                                            <!-- End Parsing Data -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                                $('#product-list').append(list);
                            });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("Status: " + textStatus);
                            alert("Error: " + errorThrown);
                        },
                    });

                });

                //Change Discount based on Customer
                $('#cust').change(function() {
                    let cust_id = $(this).val();
                    if ($(this).val() == 'other_cust') {
                        $('.manual-cust').attr('hidden', false);
                        if ($('.product-id-cart').length > 0) {
                            $('.product-id-cart').each(function() {
                                $('#disc' + $(this).val()).val(0);
                            });
                        }
                    } else {
                        $('.manual-cust').attr('hidden', true);
                        if ($('.product-id-cart').length > 0) {
                            $('.product-id-cart').each(function() {
                                $.ajax({
                                    context: this,
                                    type: "GET",
                                    url: "/discounts/select/" + cust_id + "/" + $(this).val(),
                                    data: {
                                        _token: csrf,
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    success: function(data) {
                                        if (data.discount == null || data.discount == "") {
                                            $('#disc' + $(this).val()).val(0);
                                        } else $('#disc' + $(this).val()).val(data
                                            .discount);

                                    },
                                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                                        alert("Status: " + textStatus);
                                        alert("Error: " + errorThrown);
                                    },
                                });
                            });
                        }
                    }
                });

                //Checkout Modal
                $(document).on('click', '.modalButton', function() {
                    let modal_id = $(this).attr('data-bs-target');
                    // let x = $(modal_id).find('.district-retail').parent().html();
                    // console.log(x);
                    let cust_checkout = ``;
                    if ($('#cust').val() == '' || $('#cust').val() == null) {
                        $('#btn-order').addClass('disabled');
                    } else {
                        $('#btn-order').removeClass('disabled');
                        if ($('#cust').val() != "other_cust") {
                            const cust_checkout_name = $("#cust option:selected").text();
                            cust_checkout = `
                                <div class="fw-bold">Customer: ${cust_checkout_name}</div>
                                <input type="hidden" name="cust_name" value="${$('#cust').val()}">
                            `;

                            $('.phone').attr('hidden', true);
                            $('.id_card').attr('hidden', true);
                            $('.plate').attr('hidden', true);
                            $('.geo').attr('hidden', true);
                            $('.address').attr('hidden', true);
                            $('.payment_method').attr('hidden', false);
                        } else {
                            const cust_checkout_manual_name = $("#cust").siblings('.manual-cust').val();
                            cust_checkout = `
                                <div class="fw-bold">Customer: ${cust_checkout_manual_name}</div>
                                <input type="hidden" name="cust_name_manual" value="${cust_checkout_manual_name}">
                                <input type="hidden" name="cust_name" value="other_cust">
                                `;
                            $('.phone').attr('hidden', false);
                            $('.id_card').attr('hidden', false);
                            $('.plate').attr('hidden', false);
                            $('.geo').attr('hidden', false);
                            $('.address').attr('hidden', false);
                            $('.payment_method').attr('hidden', true);
                        }
                    }

                    $(modal_id).find('.cust-parent').html('');
                    $(modal_id).find('.cust-parent').append(cust_checkout);

                    //Checking Empty Series Code
                    $('.series-input').each(function() {
                        if ($(this).val() == null || $(this).val() == '' || $('#cust').val() == '' || $(
                                '#cust').val() == null) {
                            $('#btn-order').addClass('disabled');
                            $('.alert-code').attr('hidden', false);
                        } else {
                            $('#btn-order').removeClass('disabled');
                            $('.alert-code').attr('hidden', true);
                        }
                    });

                    //Checking Empty and Exceed DOT
                    $('.selectDOT').each(function() {
                        if ($(this).val() == null || $(this).val() == '') {
                            $('#btn-order').addClass('disabled');
                            $('.alert-emptyDOT').attr('hidden', false);
                        } else {
                            $('#btn-order').removeClass('disabled');
                            $('.alert-emptyDOT').attr('hidden', true);
                        }
                    });

                    let dotMap = $('.selectDOT').map(function() {
                        return $(this).val(); // Replace with the value you want to add to the array
                    }).get();
                    let dotMap_unique = Array.from(new Set(dotMap));
                    // console.log(dotMap);
                    // console.log(dotMap_unique);
                    // create an array of promises for each element in dotMap_unique
                    const promises = dotMap_unique.map(element => {
                        let total_dot_picked = dotMap.filter(value => value == element).length;
                        return $.ajax({
                            type: "GET",
                            url: "/tyre_dot/checkExceed",
                            data: {
                                _token: csrf,
                                qt: total_dot_picked,
                                d: element
                            },
                            dataType: "json",
                            delay: 250
                        });
                    });

                    // wait for all promises to resolve
                    Promise.all(promises)
                        .then(results => {
                            // check if any of the results returned false
                            const hasExceedDot = results.some(data => !data);
                            if (hasExceedDot) {
                                $('#btn-order').addClass('disabled');
                                $('.alert-exceedDOT').attr('hidden', false);
                            } else {
                                $('#btn-order').removeClass('disabled');
                                $('.alert-exceedDOT').attr('hidden', true);
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });


                    //Choose Vehicle
                    $(modal_id).find('#vehicle').change(function() {
                        if ($(this).val() == "Car") {
                            $(modal_id).find('#car').attr('hidden', false);
                            $(modal_id).find('#motor').attr('hidden', true);
                            $(modal_id).find('#other').attr('hidden', true);
                        } else if ($(this).val() == "Motocycle") {
                            $(modal_id).find('#car').attr('hidden', true);
                            $(modal_id).find('#motor').attr('hidden', false);
                            $(modal_id).find('#other').attr('hidden', true);
                        } else {
                            $(modal_id).find('#car').attr('hidden', true);
                            $(modal_id).find('#motor').attr('hidden', true);
                            $(modal_id).find('#other').attr('hidden', false);
                        }
                    });

                    $(modal_id).find("#car-brand").change(function() {
                        //clear select
                        $("#car-type").empty();
                        // $("#car-type").append(`<option value="other">Other</option>`);
                        //set id
                        let car_brand = $(this).val();
                        if (car_brand) {
                            $(modal_id).find("#car-type").select2({
                                width: "100%",
                                dropdownParent: modal_id,
                                ajax: {
                                    type: "GET",
                                    url: "/car_brand/select/" + car_brand,
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
                                            results: [{
                                                text: 'Other',
                                                id: 'other_car'
                                            }].concat($.map(data, function(item) {
                                                return {
                                                    text: item.car_type,
                                                    id: item.car_type,
                                                };
                                            })),
                                        };
                                    },
                                },
                            });
                        } else {
                            $("#car-type").empty();
                        }
                    });

                    $(modal_id).find("#motor-brand").change(function() {
                        //clear select
                        $("#motor-type").empty();
                        //set id
                        let motor_brand = $(this).val();
                        if (motor_brand) {
                            $(modal_id).find("#motor-type").select2({
                                width: "100%",
                                dropdownParent: modal_id,
                                ajax: {
                                    type: "GET",
                                    url: "/motocycle_brand/select/" + motor_brand,
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
                                            results: [{
                                                text: 'Other',
                                                id: 'other_motor'
                                            }].concat($.map(data, function(item) {
                                                return {
                                                    text: item.name_type,
                                                    id: item.name_type,
                                                };
                                            })),
                                        };
                                    },
                                },
                            });
                        } else {
                            $("#motor-type").empty();
                        }
                    });

                    $(modal_id).find("#car-type").change(function() {
                        if ($(this).val() == 'other_car') {
                            $(modal_id).find("#other-car").attr('hidden', false);
                        } else {
                            $(modal_id).find("#other-car").attr('hidden', true);
                            $(modal_id).find("#other_car_input").val(null);
                        }
                    });

                    $(modal_id).find("#motor-type").change(function() {
                        if ($(this).val() == 'other_motor') {
                            $(modal_id).find("#other-motor").attr('hidden', false);
                        } else {
                            $(modal_id).find("#other-motor").attr('hidden', true);
                            $(modal_id).find("#other_motor_input").val(null);
                        }
                    });

                    $(modal_id).find(".province").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: "Select Customer Province",
                        minimumResultsForSearch: -1,
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getProvince",
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
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $(modal_id).find('.province').change(function() {
                        let province_value = $(modal_id).find('.province').val();

                        $(modal_id).find(".city").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            minimumResultsForSearch: -1,
                            placeholder: "Select Customer City",
                            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                            ajax: {
                                type: "GET",
                                url: "/customers/getCity/" + province_value,
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
                                                text: item.name,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    });

                    $(modal_id).find('.city').change(function() {
                        let city_value = $(modal_id).find('.city').val();

                        $(modal_id).find(".district").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            minimumResultsForSearch: -1,
                            placeholder: "Select Customer District",
                            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                            ajax: {
                                type: "GET",
                                url: "/customers/getDistrict/" + city_value,
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
                                                text: item.name,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    });
                    
                    $(modal_id).find('#delivery_point').change(function() {
                        let delivery_choice = $(this).val();
                        if (delivery_choice == 1) {
                            $(modal_id).find('#delivery_point_input').attr('hidden', true);
                        } else {
                            $(modal_id).find('#delivery_point_input').attr('hidden', false);
                        }
                    });


                    let total_all = 0;
                    for (let index = 0; index < arr_product_id.length; index++) {
                        let qty = $(document).find('#table-cart').find('#qty' + arr_product_id[index]).val();

                        //Series Code show and store
                        let codeList = '';
                        let code = $(document).find('#table-cart').find('.series-code').find('.code' +
                            arr_product_id[index]).each(function(index) {
                            if (index == 0) {
                                codeList = $(this).val();
                            } else codeList = codeList + ', ' + $(this).val();
                        });
                        let code_store_list = [];
                        let code_store = $(document).find('#table-cart').find('.series-code').find('.code' +
                            arr_product_id[index]).each(function(index) {
                            code_store_list.push($(this).val());
                        });

                        //DOT show and store
                        let dotList = '';
                        let dots = $(document).find('#table-cart').find('.series-code').find('.dot' +
                            arr_product_id[index]).each(function(index) {
                            if (index == 0) {
                                dotList = $(this).find(':selected').text();
                            } else dotList = dotList + ', ' + $(this).find(':selected').text();
                            console.log(dotList);

                        });

                        let dot_store_list = [];
                        let dot_store = $(document).find('#table-cart').find('.series-code').find('.dot' +
                            arr_product_id[index]).each(function(index) {
                            dot_store_list.push($(this).val());
                        });

                        let disc = $(document).find('#table-cart').find('#disc' + arr_product_id[index]).val();
                        let disc_float = parseFloat(disc.replace(',', '.'));
                        let disc_rp = $(document).find('#table-cart').find('#discrp' + arr_product_id[index])
                            .val();
                        let format_disc_rp = parseInt(disc_rp).toLocaleString();
                        let hargaFloat = arr_harga_jual[index].replace(',', '.');

                        let cost_ppn = $(document).find('#ppn').val() * parseFloat(hargaFloat);

                        let cost_after_ppn = parseFloat(hargaFloat) + cost_ppn;

                        let diskon = cost_after_ppn * (disc_float / 100);

                        let hargaDiskon = cost_after_ppn - diskon - disc_rp;

                        let format_harga = (hargaDiskon * qty).toLocaleString();

                        let code_series_list = ``;
                        for (let i = 0; i < code_store_list.length; i++) {
                            code_series_list = code_series_list +
                                `<input type="hidden" name="retails[${index}][${i}][product_code]" value="${code_store_list[i]}" required>`;
                        }

                        let dot_series_list = ``;
                        for (let i = 0; i < dot_store_list.length; i++) {
                            dot_series_list = dot_series_list +
                                `<input type="hidden" name="retails[${index}][${i}][dot]" value="${dot_store_list[i]}" required>`;
                        }

                        let product_qty_total = `
                        <li>Series Code: ${codeList}</li>
                        <li>DOT: ${dotList}</li>
                        <li><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> ${arr_sub_material[index]} ${arr_sub_type[index]}:
                            ${arr_product_name[index]} × ${qty} <br> Disc: ${disc}% + ${format_disc_rp} <span>Rp. ${format_harga}</span>
                        </li>
                        <input type="hidden" name="retails[${index}][product_id]" value="${arr_product_id[index]}" >
                        <input type="hidden" name="retails[${index}][qty]" value="${qty}" >
                        <input type="hidden" name="retails[${index}][discount]" value="${disc_float}" >
                        <input type="hidden" name="retails[${index}][discount_rp]" value="${disc_rp}" >
                        ` + code_series_list + dot_series_list;
                        $(modal_id).find('#products-qty-total').append(product_qty_total);

                        total_all = total_all + (hargaDiskon * qty);
                        // console.log(total_all);
                    }


                    $(modal_id).find('#products-detail').find('#total-exl').text('Rp. ' + (Math.round(
                            total_all / 1.11))
                        .toLocaleString());

                    $(modal_id).find('#products-detail').find('#ppn-total').text('Rp. ' + (Math.round(
                            total_all / 1.11 *
                            $(document).find('#ppn').val()))
                        .toLocaleString());

                    let total_incl = total_all;
                    $(modal_id).find('#products-detail').find('#total').text('Rp. ' + total_incl
                        .toLocaleString());

                    let selected_warehouse = warehouse;

                    let input_total = `
                <input type="hidden" name="total_excl" value="${parseFloat(total_all) / 1.11}">
                <input type="hidden" name="total_ppn" value="${total_all / 1.11 *
                    $(document).find('#ppn').val()}">
                <input type="hidden" name="total_incl" value="${total_incl}">
                <input type="hidden" name="warehouse_id" value="${selected_warehouse}">
                `;

                    $(modal_id).find('#products-detail').append(input_total);

                    $(modal_id).on('hidden.bs.modal', function() {
                        total_all = 0;
                        $(modal_id).find('#products-qty-total').html('');
                    });
                });

                $(document).find('.district-retail').select2({
                    width: "100%",
                    dropdownParent: modal_id,
                    ajax: {
                        type: "GET",
                        url: "/district/selectAll/",
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
                                    return {
                                        text: item.district_name,
                                        id: item.district_name,
                                    };
                                }),
                            };
                        },
                    },
                });

                $(document).on('input', '#searchRetail', function() {
                    let search = $(this).val();

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/retail/search",
                        data: {
                            _token: csrf,
                            q: search,
                            w: warehouse
                        },
                        dataType: "json",
                        delay: 250,
                        success: function(data) {
                            let old = $('#product-list').html();
                            let ppn_current = $('#ppn').val();
                            console.log(data);
                            console.log(warehouse)
                            $('#product-list').html('');
                            $.each(data, function(index, value) {
                                let ppns = parseFloat(value.harga_jual) *
                                    ppn_current;
                                let harga_jual = parseFloat(value.harga_jual) + ppns;
                                let list = `
                            <div class="col-12 col-xl-3 col-sm-6 xl-3">
                                <div class="card">
                                    <div class="product-box">
                                        <div class="product-img"><img class="img-fluid" style="width: 100%;height:229px"
                                                src="../foto_produk/${(value.foto_barang == null) ? 'no-image.jpg' : value.foto_barang}" alt="">
                                            <div class="product-hover">
                                                <ul>
                                                    <li><a data-bs-toggle="modal"
                                                            data-bs-target="#detailProduct${value.id_product}"><i
                                                                class="icon-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="detailProduct${value.id_product}">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class="product-box row">
                                                            <div class="product-img col-lg-6"><img class="img-fluid"
                                                                    src="../foto_produk/${(value.foto_barang == null) ? 'no-image.jpg' : value.foto_barang}"
                                                                    alt="">
                                                            </div>
                                                            <div class="product-details col-lg-6 text-start">
                                                                <h4>${value.nama_barang}</h4>

                                                                <div class="product-price">Rp.
                                                                    ${harga_jual.toLocaleString('en', {
                                                                        minimumFractionDigits: 0,
                                                                        maximumFractionDigits: 0
                                                                    })}
                                                                </div>
                                                                <div class="product-view">
                                                                    <h6 class="f-w-600">Product Details</h6>
                                                                    <p class="mb-0">
                                                                    <ul>
                                                                        <li><strong>Material</strong>:
                                                                            ${value.nama_material}</li>
                                                                        <li><strong>Sub-Material</strong>:
                                                                            ${value.nama_sub_material}</li>
                                                                        <li><strong>Type</strong>:
                                                                            ${value.type_name}</li>
                                                                        <li><strong>Weight</strong>:
                                                                            ${parseInt(value.berat).toLocaleString('en', {
                                                                            minimumFractionDigits: 0,
                                                                            maximumFractionDigits: 0
                                                                            })} gr
                                                                        </li>
                                                                    </ul>
                                                                    </p>
                                                                </div>
                                                                <br>
                                                                <div class="product-qnty">
                                                                    <h6 class="f-w-600">Stock:
                                                                        ${(value.stock ==null)? 0 : value.stock} ${value.satuan}
                                                                    </h6>
                                                                </div>
                                                                <div class="product-qnty">

                                                                    <div class="addcart-btn"><a type="button"
                                                                            class="btn btn-primary me-3">Add
                                                                            to Cart </a></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-details">
                                            <h4>${value.nama_barang} </h4>

                                            <p>${value.nama_material} -
                                                ${value.nama_sub_material}
                                                ${value.type_name}</p>
                                            <div class="product-price">Rp.
                                                ${harga_jual.toLocaleString('en', {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 0
                                                })}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row-reverse m-1 nodeButton justify-content-between">
                                            <button type="button" class="btn btn-primary me-3 addProduct">Add
                                            </button>
                                            <div class="text-start my-auto ms-1 fs-6"><i class="fa fa-cubes"></i>
                                                ${(value.stock ==null)? 0 : value.stock} ${value.satuan}
                                            </div>
                                            <!-- Start Parsing Data -->
                                            <input type="hidden" class="product_id" value="${value.id_product}">
                                            <input type="hidden" class="product_name" value="${value.nama_barang}">
                                            <input type="hidden" class="material"
                                                value="${value.nama_material}">
                                            <input type="hidden" class="sub-material"
                                                value="${value.nama_sub_material}">
                                            <input type="hidden" class="sub-type" value="${value.type_name}">
                                            <input type="hidden" class="harga" value="${value.harga_jual}">
                                            <!-- End Parsing Data -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                                $('#product-list').append(list);
                            });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("Status: " + textStatus);
                            alert("Error: " + errorThrown);
                        },
                    });
                });

                $(document).on('click', '.btn-sub', function() {
                    let id = $(this).attr('data-id');
                    $('.btn-sub').removeClass('active');
                    $(this).addClass('active');
                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/retail/selectById",
                        data: {
                            _token: csrf,
                            s: id,
                            w: warehouse
                        },
                        dataType: "json",
                        delay: 250,
                        success: function(data) {
                            let ppn_current = $(document).find('#ppn').val();
                            $('#product-list').html('');
                            $.each(data, function(index, value) {
                                let ppns = parseFloat(value.harga_jual) *
                                    ppn_current;
                                let harga_jual = parseFloat(value.harga_jual) + ppns;
                                let list = `
                            <div class="col-12 col-xl-3 col-sm-6 xl-3">
                                <div class="card">
                                    <div class="product-box">
                                        <div class="product-img"><img class="img-fluid" style="width: 100%;height:229px"
                                                src="../foto_produk/${(value.foto_barang == null) ? 'no-image.jpg' : value.foto_barang}" alt="">
                                            <div class="product-hover">
                                                <ul>
                                                    <li><a data-bs-toggle="modal"
                                                            data-bs-target="#detailProduct${value.id_product}"><i
                                                                class="icon-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="detailProduct${value.id_product}">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class="product-box row">
                                                            <div class="product-img col-lg-6"><img class="img-fluid"
                                                                    src="../foto_produk/${(value.foto_barang == null) ? 'no-image.jpg' : value.foto_barang}"
                                                                    alt="">
                                                            </div>
                                                            <div class="product-details col-lg-6 text-start">
                                                                <h4>${value.nama_barang}</h4>

                                                                <div class="product-price">Rp.
                                                                     ${harga_jual.toLocaleString('en', {
                                                                            minimumFractionDigits: 0,
                                                                            maximumFractionDigits: 0
                                                                        })}
                                                                </div>
                                                                <div class="product-view">
                                                                    <h6 class="f-w-600">Product Details</h6>
                                                                    <p class="mb-0">
                                                                    <ul>
                                                                        <li><strong>Material</strong>:
                                                                            ${value.nama_material}</li>
                                                                        <li><strong>Sub-Material</strong>:
                                                                            ${value.nama_sub_material}</li>
                                                                        <li><strong>Type</strong>:
                                                                            ${value.type_name}</li>
                                                                        <li><strong>Weight</strong>:
                                                                            ${parseInt(value.berat).toLocaleString()} gr</li>
                                                                    </ul>
                                                                    </p>
                                                                </div>
                                                                <br>
                                                                <div class="product-qnty">
                                                                    <h6 class="f-w-600">Stock:
                                                                        ${(value.stock ==null)? 0 : value.stock} ${value.satuan}
                                                                    </h6>
                                                                </div>
                                                                <div class="product-qnty">

                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-details">
                                            <h4>${value.nama_barang} </h4>

                                            <p>${value.nama_material} -
                                                ${value.nama_sub_material}
                                                ${value.type_name}</p>
                                            <div class="product-price">Rp.
                                                 ${harga_jual.toLocaleString('en', {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 0
                                                })}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row-reverse m-1 nodeButton justify-content-between">
                                            <button type="button" class="btn btn-primary me-3 addProduct">Add
                                            </button>
                                            <div class="text-start my-auto ms-1 fs-6"><i class="fa fa-cubes"></i>
                                                ${(value.stock ==null)? 0 : value.stock} ${value.satuan}
                                            </div>
                                            <!-- Start Parsing Data -->
                                            <input type="hidden" class="product_id" value="${value.id_product}">
                                            <input type="hidden" class="product_name" value="${value.nama_barang}">
                                            <input type="hidden" class="material"
                                                value="${value.nama_material}">
                                            <input type="hidden" class="sub-material"
                                                value="${value.nama_sub_material}">
                                            <input type="hidden" class="sub-type" value="${value.type_name}">
                                            <input type="hidden" class="harga" value="${value.harga_jual}">
                                            <!-- End Parsing Data -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                                $('#product-list').append(list);
                            });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("Status: " + textStatus);
                            alert("Error: " + errorThrown);
                        },
                    });
                });

            });
        </script>
    @endpush
@endsection
