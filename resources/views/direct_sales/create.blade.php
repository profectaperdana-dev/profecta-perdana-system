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
                                                        <input class="form-control" type="text" name="cust_name"
                                                            required>
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Phone Number</label>
                                                        <input class="form-control" type="text" name="cust_phone"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-sm-6">
                                                        <label>ID Card Number</label>
                                                        <input class="form-control" type="text" name="cust_ktp" required>
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Email Address</label>
                                                        <input class="form-control" type="email" name="cust_email"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Plate Number</label>
                                                        <input class="form-control" type="text" name="plate_number"
                                                            required>
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Vehicle</label>
                                                        <select class="form-control select2" name="vehicle"
                                                            id="vehicle">
                                                            <option value="">Choose Vehicle</option>
                                                            <option value="Car">Car</option>
                                                            <option value="Motocycle">Motocycle</option>
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
                                                <div class="row">
                                                    <div class="mb-3 col-sm-6">
                                                        <label>District</label>
                                                        <select class="form-control district-retail" required
                                                            name="district">
                                                            <option value="">Choose District</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-sm-6">
                                                        <label>Address</label>
                                                        <input type="text" class="form-control" name="address"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label>Remark</label>
                                                        <input class="form-control" type="text" name="remark">
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

                                                        <div class="animate-chk">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <label class="d-block" for="edo-ani">
                                                                        <input class="radio_animated" type="radio"
                                                                            checked="" data-original-title=""
                                                                            title="" name="payment"
                                                                            value="cash">Cash
                                                                    </label>
                                                                    <label class="d-block" for="edo-ani1">
                                                                        <input class="radio_animated" type="radio"
                                                                            data-original-title="" title=""
                                                                            name="payment" value="credit">Credit
                                                                    </label>

                                                                </div>
                                                            </div>
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
                        '<td>' + material + ' - ' +
                        sub_material + ' ' + sub_type + ': ' +
                        '<strong>' + product_name + '</strong>' +
                        '</td>' +
                        '<td><strong><input type="number" value="0" class="form-control" name="" id="qty' +
                        product_id +
                        '"></strong></td>' +
                        '<input type="hidden" name="retails[0][product_id]" value="">' +
                        '<input type="hidden" name="retails[0][qty]" value="">' +
                        '<input type="hidden" name="retails[0][discount]" value="">' +
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

                    //Choose Vehicle
                    $(modal_id).find('#vehicle').change(function() {
                        if ($(this).val() == "Car") {
                            $(modal_id).find('#car').attr('hidden', false);
                            $(modal_id).find('#motor').attr('hidden', true);
                        } else {
                            $(modal_id).find('#car').attr('hidden', true);
                            $(modal_id).find('#motor').attr('hidden', false);
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

                    $(modal_id).find('.select2').select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });

                    $(modal_id).find('.district-retail').select2({
                        dropdownParent: modal_id,
                        width: "100%",
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
                                            id: item.id,
                                        };
                                    }),
                                };
                            },
                        },
                    })
                    let total_all = 0;
                    for (let index = 0; index < arr_product_id.length; index++) {
                        let qty = $(document).find('#table-cart').find('#qty' + arr_product_id[index]).val();
                        let format_harga = (parseInt(arr_harga_jual[index]) * qty).toLocaleString('id', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        let product_qty_total = `
                            <li><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> ${arr_material[index]} - ${arr_sub_material[index]} ${arr_sub_type[index]}: 
                                ${arr_product_name[index]} × ${qty} <span>Rp. ${format_harga}</span>
                            </li>
                        `;
                        $(modal_id).find('#products-qty-total').append(product_qty_total);

                        total_all = total_all + (parseInt(arr_harga_jual[index]) * qty);
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

                    $(modal_id).on('hidden.bs.modal', function() {
                        total_all = 0;
                        $(modal_id).find('#products-qty-total').html('');
                    });
                });

            });
        </script>
    @endpush
@endsection
