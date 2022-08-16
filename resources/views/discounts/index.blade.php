@extends('layouts.master')
@section('content')
    @push('css')
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
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                        <div class="row justify-content-end">
                            <button class="col-2 btn btn-primary btn-sm" id="addfields">+</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/discounts') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12" id="formdynamic">
                                    <div class="form-group row">
                                        <div class="form-group col-md-12">
                                            <label>Customer</label>
                                            <select name="customer_id" id="customer"
                                                class="form-control discount @error('customer_id') is-invalid @enderror"
                                                required>
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        @if ($customer->id == old('customer_id')) selected @endif>
                                                        {{ $customer->name_cust }}</option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="form-group col-md-5">
                                            <label>Product</label>
                                            <select name="discountFields[0][product_id]" id="product"
                                                class="form-control @error('discountFields[0][product_id]') is-invalid @enderror product-append"
                                                required>
                                                <option value="">Choose Product</option>
                                            </select>
                                            @error('discountFields[0][product_id]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label>Discount</label>
                                            <input type="number" name="discountFields[0][discount]" id="discount"
                                                class="form-control @error('discountFields[0][discount]') is-invalid @enderror"
                                                placeholder="Enter Discount" required>
                                            @error('discountFields[0][discount]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="reset" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%"></th>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Product Name</th>
                                        <th>Discount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($discounts as $key => $discount)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#changeData{{ $discount->id }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $discount->id }}">Delete</a>
                                                </div>
                                            </td>
                                            {{-- Modul Edit Discount --}}
                                            <div class="modal fade" id="changeData{{ $discount->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('discounts/' . $discount->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $discount->customerBy->name_cust . '| Product: ' . $discount->productBy->nama_barang }}
                                                                </h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-12">
                                                                            <label>Customer</label>
                                                                            <select name="customer_id_edit"
                                                                                class="form-control role-acc @error('customer_id_edit') is-invalid @enderror"
                                                                                required>
                                                                                <option value="">Choose Customer
                                                                                </option>
                                                                                @foreach ($customers as $customer)
                                                                                    <option value="{{ $customer->id }}"
                                                                                        @if ($discount->customer_id == $customer->id) selected @endif>
                                                                                        {{ $customer->name_cust }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('customer_id_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-6">
                                                                            <label>Product</label>
                                                                            <select name="product_id_edit"
                                                                                class="form-control @error('product_id_edit') is-invalid @enderror product-append"
                                                                                required>
                                                                                <option selected
                                                                                    value="{{ $discount->product_id }}">
                                                                                    {{ $discount->productBy->nama_barang }}
                                                                                </option>
                                                                            </select>
                                                                            @error('product_id_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label>Discount</label>
                                                                            <input type="number" name="discount_edit"
                                                                                id="discount"
                                                                                class="form-control @error('discount_edit') is-invalid @enderror"
                                                                                placeholder="Enter Discount"
                                                                                value="{{ $discount->discount }}"
                                                                                required>
                                                                            @error('discount_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-danger" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="reset"
                                                                    class="btn btn-warning">Reset</button>
                                                                <button class="btn btn-primary" type="submit">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- End Modal Edit discount --}}
                                            {{-- Modul Delete discount --}}
                                            <div class="modal fade" id="deleteData{{ $discount->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('discounts/' . $discount->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('delete') <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $discount->customerBy->name_cust . '| Product: ' . $discount->productBy->nama_barang }}
                                                                </h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                <button class="btn btn-danger" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button class="btn btn-primary" type="submit">Yes, delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- End Modal Delete UOM --}}
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $discount->customerBy->name_cust }}</td>
                                            <td>{{ $discount->productBy->nama_barang }}</td>
                                            <td>{{ $discount->discount }}</td>


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
    <!-- Container-fluid Ends-->

    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    @endpush
@endsection