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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create Order at Retail

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
                <table class="table table-borderless table-success table-striped">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Disc (%)</th>
                        </tr>
                    </thead>
                    <tbody id="table-cart">
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
                    </tbody>

                </table>

                <button type="button" class="btn btn-secondary col-12 modalButton" data-bs-toggle="modal"
                    data-bs-target="#checkoutModal">Checkout</button>
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
                                    <form action="{{ url('retail/store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-6 col-sm-12">
                                                <div class="row">
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Name</label>
                                                        <select class="form-control select2" name="cust_name" id="cust"
                                                            required>
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
                                                            type="text" name="cust_name_manual" hidden>
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
                                                            <option value="Motocycle">Motocycle</option>
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
                                                                <option value="{{ $item->id }}">{{ $item->car_brand }}
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
                                                </div>
                                                <div class="row" id="motor" hidden>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Motocycle Brand</label>
                                                        <select class="form-control select2" id="motor-brand"
                                                            name="motor_brand_id">
                                                            <option selected="" value="">Choose...</option>
                                                            @foreach ($motor_brands as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name_brand }}
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
                                                                <li>Total (Exclude PPN) <span class="count"
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
                                            <button type="submit" class="btn btn-primary">Order</button>
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
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
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


                $('.select2').select2({
                    width: "100%",
                    dropdownParent: modal_id,
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

                    let addCart = '<tr class="form-group"><td>' +
                        '<button type="button" class="btn btn-sm p-3 text-center text-danger fs-5 remProduct">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '<input type="hidden" class="product-id-cart" value="' + product_id + '">' +
                        '</td>' +
                        '<td><small>' +
                        sub_material + ' ' + sub_type + ': ' +
                        '<strong>' + product_name + '</strong>' +
                        '</small></td>' +
                        '<td><input type="number" value="0" class="form-control" name="" id="qty' +
                        product_id +
                        '"></td>' +
                        '<td><input type="number" value="0" class="form-control" name="" id="disc' +
                        product_id +
                        '"></td>' +
                        '</tr>';

                    $(document).find('#table-cart').append(addCart);
                });

                //Remove Product to Cart
                $(document).on('click', '.remProduct', function() {
                    let product_id_cart = $(this).parent().find('.product-id-cart').val();
                    let parent_node = $('#product-list').find('#detailProduct' + product_id_cart)
                        .next()
                        .next();

                    parent_node.find('.addProduct').prop('disabled', false).html('Add');
                    let selected_product_id = parent_node.find('.product_id').val();
                    let indexArray = arr_product_id.indexOf(selected_product_id);
                    arr_product_id.splice(indexArray, 1);
                    arr_product_name.splice(indexArray, 1);
                    arr_material.splice(indexArray, 1);
                    arr_sub_material.splice(indexArray, 1);
                    arr_sub_type.splice(indexArray, 1);
                    arr_harga_jual.splice(indexArray, 1);

                    $(this).closest('.form-group').remove();
                    $('#count-cart').text(--count_cart);
                });

                //Checkout Modal
                $(document).on('click', '.modalButton', function() {
                    let modal_id = $(this).attr('data-bs-target');
                    // let x = $(modal_id).find('.district-retail').parent().html();
                    // console.log(x);

                    $(modal_id).find('#cust').change(function() {
                        if ($(this).val() == 'other_cust') {
                            $('.manual-cust').attr('hidden', false);
                            $('.phone').attr('hidden', false);
                            $('.id_card').attr('hidden', false);
                            $('.plate').attr('hidden', false);
                            $('.geo').attr('hidden', false);
                            $('.address').attr('hidden', false);
                        } else {
                            $('.manual-cust').attr('hidden', true);
                            $('.phone').attr('hidden', true);
                            $('.id_card').attr('hidden', true);
                            $('.plate').attr('hidden', true);
                            $('.geo').attr('hidden', true);
                            $('.address').attr('hidden', true);
                        }
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
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.car_type,
                                                    id: item.id,
                                                };
                                            }),
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
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.name_type,
                                                    id: item.id,
                                                };
                                            }),
                                        };
                                    },
                                },
                            });
                        } else {
                            $("#motor-type").empty();
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


                    let total_all = 0;
                    for (let index = 0; index < arr_product_id.length; index++) {
                        let qty = $(document).find('#table-cart').find('#qty' + arr_product_id[index]).val();
                        let disc = $(document).find('#table-cart').find('#disc' + arr_product_id[index]).val();

                        let diskon = parseInt(arr_harga_jual[index]) * (disc / 100);
                        let hargaDiskon = parseInt(arr_harga_jual[index]) - diskon;

                        let format_harga = (hargaDiskon * qty).toLocaleString('id', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        let product_qty_total = `
                            <li><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> ${arr_sub_material[index]} ${arr_sub_type[index]}: 
                                ${arr_product_name[index]} × ${qty} Disc: ${disc}% <span>Rp. ${format_harga}</span>
                            </li>
                            <input type="hidden" name="retails[${index}][product_id]" value="${arr_product_id[index]}" >
                            <input type="hidden" name="retails[${index}][qty]" value="${qty}" >
                            <input type="hidden" name="retails[${index}][discount]" value="${disc}" >
                        `;
                        $(modal_id).find('#products-qty-total').append(product_qty_total);

                        total_all = total_all + (hargaDiskon * qty);
                    }


                    $(modal_id).find('#products-detail').find('#total-exl').text('Rp. ' + total_all
                        .toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }));

                    let total_ppn = $(document).find('#ppn').val() * total_all;
                    $(modal_id).find('#products-detail').find('#ppn-total').text('Rp. ' + total_ppn
                        .toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }));

                    let total_incl = total_all + total_ppn;
                    $(modal_id).find('#products-detail').find('#total').text('Rp. ' + total_incl.toLocaleString(
                        'id', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }));

                    let input_total = `
                    <input type="hidden" name="total_excl" value="${total_all}">
                    <input type="hidden" name="total_ppn" value="${total_ppn}">
                    <input type="hidden" name="total_incl" value="${total_incl}">
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
                        },
                        dataType: "json",
                        delay: 250,
                        success: function(data) {
                            let old = $('#product-list').html();

                            $('#product-list').html('');
                            $.each(data, function(index, value) {
                                let list = `
                                <div class="col-12 col-xl-4 col-sm-6 xl-3">
                                    <div class="card">
                                        <div class="product-box">
                                            <div class="product-img"><img class="img-fluid" style="width: 100%;height:229px"
                                                    src="../foto_produk/${value.foto_barang}" alt="">
                                                <div class="product-hover">
                                                    <ul>
                                                        <li><a data-bs-toggle="modal"
                                                                data-bs-target="#detailProduct${value.id}"><i
                                                                    class="icon-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="detailProduct${value.id}">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="product-box row">
                                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                                        src="../foto_produk/${value.foto_barang}"
                                                                        alt="">
                                                                </div>
                                                                <div class="product-details col-lg-6 text-start">
                                                                    <h4>${value.nama_barang}</h4>

                                                                    <div class="product-price">Rp.
                                                                        ${value.harga_jual}
                                                                    </div>
                                                                    <div class="product-view">
                                                                        <h6 class="f-w-600">Product Details</h6>
                                                                        <p class="mb-0">
                                                                        <ul>
                                                                            <li><strong>Material</strong>:
                                                                                ${value.materials.nama_material}</li>
                                                                            <li><strong>Sub-Material</strong>:
                                                                                ${value.sub_materials.nama_sub_material}</li>
                                                                            <li><strong>Type</strong>:
                                                                                ${value.sub_types.type_name}</li>
                                                                            <li><strong>Weight</strong>:
                                                                                ${value.berat} gr</li>
                                                                        </ul>
                                                                        </p>
                                                                    </div>
                                                                    <br>
                                                                    <div class="product-qnty">
                                                                        <h6 class="f-w-600">Stock:
                                                                            ${value.stock_by.stock} ${value.uoms.satuan}
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

                                                <p>${value.materials.nama_material} -
                                                    ${value.sub_materials.nama_sub_material}
                                                    ${value.sub_types.type_name}</p>
                                                <div class="product-price">Rp.
                                                    ${value.harga_jual}
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row-reverse m-1 nodeButton">
                                                <button type="button" class="btn btn-primary me-3 addProduct">Add
                                                </button>
                                                <!-- Start Parsing Data -->
                                                <input type="hidden" class="product_id" value="${value.id}">
                                                <input type="hidden" class="product_name" value="${value.nama_barang}">
                                                <input type="hidden" class="material"
                                                    value="${value.materials.nama_material}">
                                                <input type="hidden" class="sub-material"
                                                    value="${value.sub_materials.nama_sub_material}">
                                                <input type="hidden" class="sub-type" value="${value.sub_types.type_name}">
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
                        },
                        dataType: "json",
                        delay: 250,
                        success: function(data) {
                            $('#product-list').html('');
                            $.each(data, function(index, value) {
                                let list = `
                                <div class="col-12 col-xl-4 col-sm-6 xl-3">
                                    <div class="card">
                                        <div class="product-box">
                                            <div class="product-img"><img class="img-fluid" style="width: 100%;height:229px"
                                                    src="../foto_produk/${value.foto_barang}" alt="">
                                                <div class="product-hover">
                                                    <ul>
                                                        <li><a data-bs-toggle="modal"
                                                                data-bs-target="#detailProduct${value.id}"><i
                                                                    class="icon-eye"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="detailProduct${value.id}">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="product-box row">
                                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                                        src="../foto_produk/${value.foto_barang}"
                                                                        alt="">
                                                                </div>
                                                                <div class="product-details col-lg-6 text-start">
                                                                    <h4>${value.nama_barang}</h4>

                                                                    <div class="product-price">Rp.
                                                                        ${value.harga_jual}
                                                                    </div>
                                                                    <div class="product-view">
                                                                        <h6 class="f-w-600">Product Details</h6>
                                                                        <p class="mb-0">
                                                                        <ul>
                                                                            <li><strong>Material</strong>:
                                                                                ${value.materials.nama_material}</li>
                                                                            <li><strong>Sub-Material</strong>:
                                                                                ${value.sub_materials.nama_sub_material}</li>
                                                                            <li><strong>Type</strong>:
                                                                                ${value.sub_types.type_name}</li>
                                                                            <li><strong>Weight</strong>:
                                                                                ${value.berat} gr</li>
                                                                        </ul>
                                                                        </p>
                                                                    </div>
                                                                    <br>
                                                                    <div class="product-qnty">
                                                                        <h6 class="f-w-600">Stock:
                                                                            ${value.stock_by.stock} ${value.uoms.satuan}
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

                                                <p>${value.materials.nama_material} -
                                                    ${value.sub_materials.nama_sub_material}
                                                    ${value.sub_types.type_name}</p>
                                                <div class="product-price">Rp.
                                                    ${value.harga_jual}
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row-reverse m-1 nodeButton">
                                                <button type="button" class="btn btn-primary me-3 addProduct">Add
                                                </button>
                                                <!-- Start Parsing Data -->
                                                <input type="hidden" class="product_id" value="${value.id}">
                                                <input type="hidden" class="product_name" value="${value.nama_barang}">
                                                <input type="hidden" class="material"
                                                    value="${value.materials.nama_material}">
                                                <input type="hidden" class="sub-material"
                                                    value="${value.sub_materials.nama_sub_material}">
                                                <input type="hidden" class="sub-type" value="${value.sub_types.type_name}">
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
