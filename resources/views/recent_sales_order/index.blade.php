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

            .nav-new {
                display: block;
                padding: 0.5rem 1rem;
                color: #24695c !important;
                text-decoration: none;
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
            }

            .nav-pills .nav-new.active,
            .nav-pills .show>.nav-new {
                background-color: #d0efe9 !important;
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
        <div class="col-12 col-lg-12">
            <div class="card shadow">
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link nav-new active" id="pills-home-tab" data-bs-toggle="pill"
                                href="#pills-home" role="tab" aria-controls="pills-home"
                                aria-selected="true">Verification
                                <div class="media"></div>
                            </a></li>
                        <li class="nav-item"><a class="nav-link nav-new" id="pills-profile-tab" data-bs-toggle="pill"
                                href="#pills-profile" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Reject</a></li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div class="table-responsive">
                                <table id="example"
                                    class="display expandable-table table table-borderless text-capitalize table-striped table-sm text-nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Order Number</th>
                                            <th>Order Date</th>
                                            <th>Customer</th>
                                            <th>Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataSalesOrder as $value)
                                            <tr>
                                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                                <td class="text-center">
                                                    <a class="fw-bold text-nowrap modal-btn" href="javascript:void(0)"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#verifyData{{ $value->id }}">{{ $value->order_number }}</a>
                                                </td>
                                                <td class="text-center">{{ date('d F Y', strtotime($value->order_date)) }}
                                                </td>
                                                <td>
                                                    {{ $value->customerBy->code_cust . ' - ' . $value->customerBy->name_cust }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($value->payment_method == 1)
                                                        COD
                                                    @elseif ($value->payment_method == 2)
                                                        CBD
                                                    @else
                                                        Credit
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="ppn" id="ppn" value="{{ $ppn }}">
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="table-responsive">
                                <table id="example1"
                                    class="display expandable-table table table-borderless text-capitalize table-striped table-sm text-nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Order Number</th>
                                            <th>Order Date</th>
                                            <th>Customer</th>
                                            <th>Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataSalesOrderReject as $value)
                                            <tr>
                                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                                <td class="text-center">
                                                    <a class="fw-bold text-nowrap modal-btn" href="javascript:void(0)"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#verifyData{{ $value->id }}">{{ $value->order_number }}</a>
                                                </td>
                                                <td class="text-center">{{ date('d F Y', strtotime($value->order_date)) }}
                                                </td>
                                                <td>
                                                    {{ $value->customerBy->code_cust . ' - ' . $value->customerBy->name_cust }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($value->payment_method == 1)
                                                        COD
                                                    @elseif ($value->payment_method == 2)
                                                        CBD
                                                    @else
                                                        Credit
                                                    @endif
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

    </div>


    @foreach ($dataSalesOrder as $value)
        <!-- Verify Product Modal Start -->
        <div class="modal" id="verifyData{{ $value->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Sales
                            Order

                            {{ $value->order_number }}</h6>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('sales_orders/' . $value->id . '/verify') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <input type="hidden" name="warehouse_id" class="warehouse"
                                        value="{{ $value->warehouse_id }}">
                                    <div class=" col-md-4 mb-3">
                                        <label>
                                            Customers</label>
                                        <select name="customer_id" id="" required
                                            class="form-control customer-select customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}"
                                            multiple>
                                            <option value="{{ $value->customerBy->id }}" selected>
                                                {{ $value->customerBy->name_cust }}</option>
                                        </select>

                                    </div>
                                    <div class="col-md-4 mb-3 mr-5">
                                        <label>Payment Method</label>
                                        <select name="payment_method" required
                                            class="form-control multi-select {{ $errors->first('payment_method') ? ' is-invalid' : '' }}"
                                            multiple>
                                            <option value="1" @if ($value->payment_method == 1) selected @endif>
                                                Cash On Delivery
                                            </option>
                                            <option value="2" @if ($value->payment_method == 2) selected @endif>
                                                Cash Before Delivery
                                            </option>
                                            <option value="3" @if ($value->payment_method == 3) selected @endif>
                                                Credit
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3 mr-5">
                                        <label>Order Date</label>
                                        {{-- <input type="text" value="{{ $value->order_date }}"> --}}
                                        <input class="datepicker-here form-control digits" data-position="bottom left"
                                            type="text" data-language="en"
                                            data-value="{{ date('d-m-Y', strtotime($value->order_date)) }}"
                                            name="order_date" autocomplete="off">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remark" id="" cols="30" rows="1">{{ $value->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group formSo-edit">
                                    @foreach ($value->salesOrderDetailsBy as $detail)
                                        <div class="mx-auto py-2 rounded form-group row"
                                            style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-12 col-lg-3">
                                                <label>Product</label>
                                                <select multiple name="editProduct[{{ $loop->index }}][products_id]"
                                                    required
                                                    class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}">
                                                    @if ($detail->products_id != null)
                                                        <option value="{{ $detail->products_id }}" selected>
                                                            {{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}
                                                        </option>
                                                    @endif
                                                </select>

                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control cekQty-edit jumlahQty"
                                                    name="editProduct[{{ $loop->index }}][qty]"
                                                    value="{{ $detail->qty }}" />
                                                <small class="text-danger qty-warning" hidden>The number of items
                                                    exceeds
                                                    the
                                                    stock</small>
                                                @error('top')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            @php
                                                $price = $detail->price;
                                                if ($price == 0 || $price == null) {
                                                    $price = $detail->productSales->harga_jual_nonretail;
                                                    $price = $price + ($price * $ppn) / 100;
                                                }
                                                // $price = str_replace(',', '.', $detail->productSales->harga_jual_nonretail);
                                                // $sub_total = (float) $price * (float) $ppn;
                                                // (float) ($harga = (float) $price + (float) $sub_total);
                                            @endphp
                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Price</label>
                                                <input type="hidden" class="price" name="editProduct[{{ $loop->index }}][price]"
                                                    value="{{ number_format(round($price)) }}"
                                                    class="hargaNonRetail">
                                                <input type="text" class="form-control price hargaNonRetail" disabled
                                                    value="{{ number_format(round($price)) }}" />
                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Disc (%)</label>
                                                <input type="text"
                                                    class="form-control discount-append-edit discountPersen"
                                                    placeholder="Disc" name="editProduct[{{ $loop->index }}][discount]"
                                                    value="{{ $detail->discount }}" />
                                                @error('editProduct[{{ $loop->index }}][discount]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Disc (Rp)</label>
                                                <input type="number" class="form-control discount_rp discountRupiah"
                                                    placeholder="Disc"
                                                    name="editProduct[{{ $loop->index }}][discount_rp]"
                                                    value="{{ $detail->discount_rp }}" />
                                                @error('editProduct[{{ $loop->index }}][discount_rp]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            @php
                                                $disc = (float) $detail->discount / 100.0;
                                                // $ppn_cost = (float) $price * (float) $ppn;
                                                // $ppn_total = (float) $price + $ppn_cost;
                                                $disc_cost = (float) $price * $disc;
                                                $price_disc = (float) ($price - $disc_cost - $detail->discount_rp) * $detail->qty;
                                            @endphp
                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Disc Price</label>
                                                <input type="text" class="form-control priceDiscount" readonly
                                                    value="{{ number_format(round($price_disc)) }}" />
                                            </div>

                                            @if ($loop->index == 0)
                                                <div class="col-12 col-lg-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        style="border:none; background-color:#276e61"
                                                        class="form-control text-center fw-bold text-white addSo-edit">+</a>
                                                </div>
                                            @else
                                                <div class="col-4 col-lg-1 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        style="border:none; background-color:#276e61"
                                                        class="form-control text-center fw-bold text-white addSo-edit">+</a>
                                                </div>
                                                <div class="col-4 col-lg-1 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        style="border:none; background-color:#d94f5c"
                                                        class="btn form-control text-white remSo-edit">-</a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group row">
                                    <div class="form-group col-12">
                                        <button type="button" class="col-12 btn btn-outline-primary btn-reload">--
                                            Click this to
                                            view total
                                            --</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-lg-4">
                                        <label>PPN</label>
                                        <input class="form-control ppn"
                                            value="{{ 'Rp. ' . number_format($value->ppn) }}" id=""
                                            readonly>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label>Total (Before PPN)</label>
                                        <input class="form-control total"
                                            value="{{ 'Rp. ' . number_format($value->total) }}" readonly>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label>Total (After PPN)</label>
                                        <input class="form-control total-after-ppn"
                                            value="{{ 'Rp. ' . number_format($value->total_after_ppn) }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">

                                    <button type="button" class="btn btn-secondary" href="javascript:void(0)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteData{{ $value->id }}">Reject</button>

                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="sr-only">Loading...</span>
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Verify Product Modal End -->

        <!-- Delete Product Modal Start -->
        <div class="modal" id="deleteData{{ $value->id }}" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('sales_order/' . $value->id . '/reject_from_verification') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">
                                Reject
                                {{ $value->order_number }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div> Are you sure to reject this order?
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="">Reason</label>
                                    <input type="text" name="reason" placeholder="Write your reason..."
                                        id="" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            {{-- <button class="btn btn-secondary" type="button" data-bs-toggle="modal"/> data-bs-target="#verifyData{{ $value->id }}">Back</button> --}}
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary btn-delete" type="submit">Yes, reject
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Delete Product Modal End -->
    @endforeach



    @foreach ($dataSalesOrderReject as $value)
        <!-- Verify Product Modal Start -->
        <div class="modal" id="verifyData{{ $value->id }}" data-bs-keyboard="false"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Sales
                            Order {{ $value->order_number }}</h6>
                        {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('sales_orders/' . $value->id . '/verify') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="container-fluid">
                                <input type="hidden" name="warehouse_id" class="warehouse"
                                    value="{{ $value->warehouse_id }}">
                                <div class="form-group row">
                                    <input type="hidden" name="warehouse_id" class="warehouse"
                                        value="{{ $value->warehouse_id }}">
                                    <div class=" col-md-4 mb-3">
                                        <label>
                                            Customers</label>
                                        <select name="customer_id" id="" required
                                            class="form-control customer-select customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}"
                                            multiple>
                                            <option value="{{ $value->customerBy->id }}" selected>
                                                {{ $value->customerBy->name_cust }}</option>
                                        </select>

                                    </div>
                                    <div class="col-md-4 mb-3 mr-5">
                                        <label>Payment Method</label>
                                        <select name="payment_method" required
                                            class="form-control multi-select {{ $errors->first('payment_method') ? ' is-invalid' : '' }}"
                                            multiple>
                                            <option value="1" @if ($value->payment_method == 1) selected @endif>
                                                Cash On Delivery
                                            </option>
                                            <option value="2" @if ($value->payment_method == 2) selected @endif>
                                                Cash Before Delivery
                                            </option>
                                            <option value="3" @if ($value->payment_method == 3) selected @endif>
                                                Credit
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3 mr-5">
                                        <label>Order Date</label>
                                        {{-- <input type="text" value="{{ $value->order_date }}"> --}}
                                        <input class="datepicker-here form-control digits" data-position="bottom left"
                                            type="text" data-language="en"
                                            data-value="{{ date('d-m-Y', strtotime($value->order_date)) }}"
                                            name="order_date" autocomplete="off">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remark" id="" cols="30" rows="1">{{ $value->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group formSo-edit">
                                    @foreach ($value->salesOrderDetailsBy as $detail)
                                        <div class="mx-auto py-2 rounded form-group row"
                                            style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-12 col-lg-3">
                                                <label>Product</label>
                                                <select multiple name="editProduct[{{ $loop->index }}][products_id]"
                                                    required
                                                    class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}">
                                                    @if ($detail->products_id != null)
                                                        <option value="{{ $detail->products_id }}" selected>
                                                            {{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}
                                                        </option>
                                                    @endif
                                                </select>

                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control cekQty-edit jumlahQty"
                                                    name="editProduct[{{ $loop->index }}][qty]"
                                                    value="{{ $detail->qty }}" />
                                                <small class="text-danger qty-warning" hidden>The number of items
                                                    exceeds
                                                    the
                                                    stock</small>
                                                @error('top')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            @php

                                                $price = $detail->price;
                                                if ($price == 0 || $price == null) {
                                                    $price = $detail->productSales->harga_jual_nonretail;
                                                    $price = $price + ($price * $ppn) / 100;
                                                }
                                            @endphp
                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Price</label>
                                                <input type="hidden" name="editProduct[{{ $loop->index }}][price]"
                                                    value="{{ number_format(round($price)) }}"
                                                    class="hargaNonRetail">
                                                <input type="text" class="form-control price hargaNonRetail" disabled
                                                    value="{{ number_format(round($price)) }}" />
                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Disc (%)</label>
                                                <input type="text"
                                                    class="form-control discount-append-edit discountPersen"
                                                    placeholder="Disc" name="editProduct[{{ $loop->index }}][discount]"
                                                    value="{{ $detail->discount }}" />
                                                @error('editProduct[{{ $loop->index }}][discount]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Disc (Rp)</label>
                                                <input type="number" class="form-control discount_rp discountRupiah"
                                                    placeholder="Disc"
                                                    name="editProduct[{{ $loop->index }}][discount_rp]"
                                                    value="{{ $detail->discount_rp }}" />
                                                @error('editProduct[{{ $loop->index }}][discount_rp]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            @php
                                                $disc = (float) $detail->discount / 100.0;
                                                // $ppn_cost = (float) $price * (float) $ppn;
                                                // $ppn_total = (float) $price + $ppn_cost;
                                                $disc_cost = (float) $price * $disc;
                                                $price_disc = (float) ($price - $disc_cost - $detail->discount_rp) * $detail->qty;
                                            @endphp
                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Disc Price</label>
                                                <input type="text" class="form-control priceDiscount" readonly
                                                    value="{{ number_format(round($price_disc)) }}" />
                                                <small class="text-danger">*Click here after choose product</small>
                                            </div>

                                            @if ($loop->index == 0)
                                                <div class="col-12 col-lg-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        style="border:none; background-color:#276e61"
                                                        class="form-control text-center fw-bold text-white addSo-edit">+</a>
                                                </div>
                                            @else
                                                <div class="col-4 col-lg-1 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        style="border:none; background-color:#276e61"
                                                        class="form-control text-center fw-bold text-white addSo-edit">+</a>
                                                </div>
                                                <div class="col-4 col-lg-1 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        style="border:none; background-color:#d94f5c"
                                                        class="btn form-control text-white remSo-edit">-</a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group row">
                                    <div class="form-group col-12">
                                        <button type="button" class="col-12 btn btn-outline-success btn-reload">--
                                            Click this to
                                            view total
                                            --</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-lg-4">
                                        <label>PPN</label>
                                        <input class="form-control ppn"
                                            value="{{ 'Rp. ' . number_format($value->ppn) }}" id=""
                                            readonly>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label>Total (Before PPN)</label>
                                        <input class="form-control total"
                                            value="{{ 'Rp. ' . number_format($value->total) }}" readonly>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label>Total (After PPN)</label>
                                        <input class="form-control total-after-ppn"
                                            value="{{ 'Rp. ' . number_format($value->total_after_ppn) }}"
                                            readonly>
                                    </div>
                                </div>

                            </div>

                    </div>
                    <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" href="javascript:void(0)"
                                data-bs-toggle="modal" data-bs-target="#deleteData{{ $value->id }}">Reject</button>

                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                            Save
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Verify Product Modal End -->

        <!-- Delete Product Modal Start -->
        <div class="modal" id="deleteData{{ $value->id }}" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('sales_order/' . $value->id . '/reject_from_verification') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">
                                Reject
                                {{ $value->order_number }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div> Are you sure to reject this order?
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="">Reason</label>
                                    <input type="text" name="reason" placeholder="Write your reason..."
                                        id="" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            {{-- <button class="btn btn-secondary" type="button" data-bs-toggle="modal"/> data-bs-target="#verifyData{{ $value->id }}">Back</button> --}}
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary btn-delete" type="submit">Yes, reject
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Delete Product Modal End -->
    @endforeach

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
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                let date = new Date();
                let date_now = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
                var t = $('#example').DataTable({
                    "lengthChange": false,
                    "paging": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    dom: 'lpftrip',
                    pageLength: -1,
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0
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

                var t_ = $('#example1').DataTable({
                    "lengthChange": false,
                    "paging": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    pageLength: -1,
                    "autoWidth": false,
                    dom: 'lpftrip',
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
                t_.on('order.dt search.dt', function() {
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
                let csrf = $('meta[name="csrf-token"]').attr("content");

                $(document).on("click", ".modal-btn", function() {
                    let modal_id = $(this).attr('data-bs-target');
                    $(document).on('click', '.btn-delete', function() {
                        $(this).addClass('disabled');
                    });
                    $('form').submit(function(e) {
                        var form = $(this);
                        var button = form.find('button[type="submit"]');
                        if (form[0].checkValidity()) {
                            button.prop('disabled', true);
                            $(this).find('.spinner-border').removeClass('d-none');
                            $(this).find('span:not(.spinner-border)').addClass('d-none');
                            $(this).off('click');
                        }
                    });


                    let warehouse_id = $(modal_id).find('.warehouse').val();
                    $(modal_id).find('.multi-select').select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%'
                    });
                    $(modal_id).find('.customer-select').select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/customer/select/",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item
                                                .code_cust +
                                                ' - ' +
                                                item.name_cust,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                    $(modal_id).find('.datepicker-here').datepicker({
                        dropdownParent: $(modal_id),
                        onSelect: function(formattedDate, date, inst) {
                            inst.hide();
                        },
                    });
                    $(modal_id).find('.datepicker-here').val(
                        $(modal_id).find('.datepicker-here').attr('data-value'));

                    $(modal_id).find(".productSo-edit").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/products/select",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouse_id
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
                                                " " +
                                                item.type_name +
                                                " " + item.nama_barang,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });



                    $(modal_id).find('.productSo-edit').change(function() {
                        let product_id = $(this).val();
                        let parent_product = $(this).parent('.form-group').siblings(
                            '.form-group').find(
                            ".price");
                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/products/selectPrice" + "/" + product_id,
                            dataType: "json",
                            success: function(data) {
                                if (data != null) {
                                    parent_product.val(data.replace(/\./g,
                                            ','));
                                } else {
                                    parent_product.val(0);
                                }
                            },
                        });
                    });

                    $(modal_id).find('.priceDiscount').on('click', function() {
                        let price = $(this).parent('.form-group').siblings('.form-group').find(
                            ".price").val().replace(/\,/g, '');
                        console.log(price);
                        let qty = $(this).parent('.form-group').siblings('.form-group').find(
                            ".jumlahQty").val();
                        console.log(qty);
                        let discountPersen = $(this).parent('.form-group').siblings('.form-group').find(
                            ".discountPersen").val() / 100;
                        console.log(discountPersen);
                        let discountRupiah = $(this).parent('.form-group').siblings('.form-group').find(
                            ".discountRupiah").val();
                        console.log(discountRupiah);


                        let hDiskon = price * discountPersen;
                        let totalDiskon = parseInt(hDiskon) + parseInt(discountRupiah);
                        let total = (price - totalDiskon) * qty;

                        $(this).val(total.toLocaleString('en'));
                    });

                    //Get Customer ID
                    let customer_id = $(modal_id).find('.customer-append').val();
                    $(modal_id).find(".productSo-edit").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/products/select",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouse_id
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
                                                " " +
                                                item.type_name +
                                                " " + item.nama_barang,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    //Get Customer ID
                    $(modal_id).find(".customer-append").change(function() {
                        customer_id = $(modal_id).find(".customer-append").val();
                    });
                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('.formSo-edit')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();
                    //Get discount depent on product
                    $(modal_id).on("change", ".productSo-edit", function() {
                        let product_id = $(this).val();
                        let parent_product = $(this).parent('.form-group').siblings('.form-group').find(
                            ".discount-append-edit");
                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/discounts/select" + "/" + customer_id + "/" + product_id,
                            dataType: "json",
                            success: function(data) {
                                if (data.discount != null) {
                                    parent_product.val(data.discount);
                                } else {
                                    parent_product.val(0);
                                }
                            },
                        });
                    });
                    $(modal_id).on("input", ".cekQty-edit", function() {
                        let qtyValue = $(this).val();
                        let product_id = $(this).parent('.form-group').siblings('.form-group').find(
                            '.productSo-edit').val();
                        let id = customer_id;

                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/stocks/cekQty/" + product_id,
                            data: {
                                _token: csrf,
                                w: warehouse_id,
                            },
                            dataType: "json",
                            delay: 250,
                            success: function(data) {
                                if (parseInt(qtyValue) > parseInt(data.stock)) {
                                    $(this).parent().find(".qty-warning").removeAttr(
                                        "hidden");
                                    $(this).addClass("is-invalid");
                                } else {
                                    $(this)
                                        .parent()
                                        .find(".qty-warning")
                                        .attr("hidden", "true");
                                    $(this).removeClass("is-invalid");
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("Status: " + textStatus);
                                alert("Error: " + errorThrown);
                            },
                        });
                    });
                    $(document).off("click", ".addSo-edit");
                    $(document).on("click", ".addSo-edit", function() {
                        ++x;
                        var form = ` <div class="mx-auto py-2 rounded form-group row" style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="${x}">
                                            <div class="form-group col-12 col-lg-3">
                                                <label>Product</label>
                                                <select name="editProduct[${x}][products_id]" required multiple
                                                    class="form-control productSo-edit ">
                                                </select>

                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control cekQty-edit jumlahQty"
                                                    name="editProduct[${x}][qty]"
                                                    value="0" />
                                                <small class="text-danger qty-warning" hidden>The number of items exceeds
                                                    the
                                                    stock</small>

                                            </div>

                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Price</label>
                                                <input type="hidden" class="hargaNonRetail price" name="editProduct[${x}][price]">
                                                <input type="text" class="form-control price hargaNonRetail" disabled
                                                    value="0" />
                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Disc (%)</label>
                                                <input type="text"
                                                    class="form-control discount-append-edit discountPersen"
                                                    placeholder="Disc" name="editProduct[${x}][discount]"
                                                    value="0" />

                                            </div>

                                            <div class="col-4 col-lg-1 form-group">
                                                <label>Disc (Rp)</label>
                                                <input type="number" class="form-control discount_rp discountRupiah"
                                                    placeholder="Disc"
                                                    name="editProduct[${x}][discount_rp]"
                                                    value="0" />

                                            </div>

                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Disc Price</label>
                                                <input type="text" placeholder="click here" class="form-control priceDiscount" readonly
                                                    value="" />
                                                <small class="text-danger">*Click here after choose product</small>
                                            </div>


                                                <div class="col-4 col-lg-1 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="btn text-center fw-bold form-control text-white addSo-edit" style="border:none; background-color:#276e61">+</a>
                                                </div>
                                                <div class="col-4 col-lg-1 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)" style="border:none; background-color:#d94f5c"
                                                        class="btn text-center fw-bold form-control text-white remSo-edit">-</a>
                                                </div>
                                        </div>`;

                        $(modal_id).find(".formSo-edit").append(form);

                        $(modal_id).find(".productSo-edit").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                type: "GET",
                                url: "/products/select",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        w: warehouse_id
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
                                                    " " +
                                                    item.type_name +
                                                    " " + item.nama_barang,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                        $(modal_id).find('.priceDiscount').on('click', function() {
                            let price = $(this).parent('.form-group').siblings('.form-group')
                                .find(
                                    ".price").val().replace(/\,/g, '');
                            console.log(price);
                            let qty = $(this).parent('.form-group').siblings('.form-group')
                                .find(
                                    ".jumlahQty").val();
                            console.log(qty);
                            let discountPersen = $(this).parent('.form-group').siblings(
                                '.form-group').find(
                                ".discountPersen").val() / 100;
                            console.log(discountPersen);
                            let discountRupiah = $(this).parent('.form-group').siblings(
                                '.form-group').find(
                                ".discountRupiah").val();
                            console.log(discountRupiah);


                            let hDiskon = price * discountPersen;
                            let totalDiskon = parseInt(hDiskon) + parseInt(discountRupiah);
                            let total = (price - totalDiskon) * qty;

                            $(this).val(total.toLocaleString('en'));
                        });
                        $(modal_id).find('.productSo-edit').change(function() {
                            let product_id = $(this).val();
                            let parent_product = $(this).parent('.form-group').siblings(
                                '.form-group').find(
                                ".price");
                            $.ajax({
                                context: this,
                                type: "GET",
                                url: "/products/selectPrice" + "/" + product_id,
                                dataType: "json",
                                success: function(data) {
                                    if (data != null) {
                                        parent_product.val(data.replace(/\./g,
                                            ','));
                                    } else {
                                        parent_product.val(0);
                                    }
                                },
                            });
                        });

                        $(modal_id).find(".productSo-edit").last().select2("open");

                    });


                    //remove Sales Order fields
                    $(modal_id).on("click", ".remSo-edit", function() {
                        $(this).closest(".row").remove();
                    });

                    //reload total
                    $(modal_id).on('click', '.btn-reload', function() {
                        let ppn = 0;
                        let total = 0;
                        let total_after_ppn = 0;
                        $(this).closest('.row').siblings('.formSo-edit').find('.productSo-edit').each(function() {
                            let product_id = $(this).val();
                            let cost = $(this).parent().siblings().find('.priceDiscount').val()
                                .replace(
                                    /\,/g, '');
                           // console.log('cost: ' + cost);
                            let qty = $(this).parent().siblings().find('.cekQty-edit').val();
                            let disc = parseFloat($(this).parent().siblings().find(
                                    '.discount-append-edit')
                                .val()) / 100;
                            let disc_rp = $(this).parent().siblings().find('.discount_rp')
                                .val();
                            ppn = parseFloat(cost) * $('#ppn').val();
                            let ppn_cost = parseFloat(cost);
                            let disc_cost = ppn_cost * disc;
                            let cost_after_disc = (ppn_cost - disc_cost) - disc_rp;
                            total = parseFloat(total) + parseFloat(cost);
                            //console.log('Total: ' + total);

                        });
                        total_after_ppn = total;
                        $(this).closest('.row').siblings().find('.ppn').val('Rp. ' + Math.round(
                                total_after_ppn / 1.11 * $('#ppn').val())
                            .toLocaleString('id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }));
                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(
                                total_after_ppn / 1.11)
                            .toLocaleString(
                                'id', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }));
                        $(this).closest('.row').siblings().find('.total-after-ppn').val('Rp. ' + Math
                            .round(
                                total_after_ppn).toLocaleString('id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }));
                    });
                    $(modal_id).on('hidden.bs.modal', function() {
                        $(modal_id).unbind();
                    });
                });
            });
        </script>
    @endpush
@endsection
