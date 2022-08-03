@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">{{ $title }}</h3>
                        <h6 class="font-weight-normal mb-0">Let's {{ $title }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customers Add Form</p>
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Name</label>
                                    <input type="text" name="nama_cust" class="form-control" placeholder="Customer Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone Number</label>
                                    <input type="text" name="no_telepon_cust" class="form-control"
                                        placeholder="Customer Phone Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="alamat_cust" class="form-control form-control-lg"
                                    placeholder="Customer Address">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input type="email" name="email_cust" class="form-control"
                                        placeholder="Email Customer">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Category</label>
                                    <select name="category_cust_id" class="form-control category-cust">
                                        <option>Choose Category Customer</option>
                                        @foreach ($customer_categories as $customer_category)
                                            <option value="{{ $customer_category->id }}">
                                                {{ $customer_category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Area</label>
                                    <select name="area_cust_id" class="form-control area-cust">
                                        <option selected>Choose Customer Area</option>
                                        @foreach ($customer_areas as $customer_area)
                                            <option value="{{ $customer_area->id }}">
                                                {{ $customer_area->area_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Coordinate</label>
                                    <input type="text" class="form-control" placeholder="Enter Customer Coordinate">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Credit Limit</label>
                                    <input type="number" class="form-control" placeholder="Credit Limit Customer">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Status</label>
                                    <select class="form-control">
                                        <option selected>Choose Customer Status</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Reference Image Customer</label>
                                    <input type="file" id="inputreference" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <img src="#" id="previewimg" class="img-fluid" hidden />
                                </div>
                            </div>

                    </div>
                    <div class="card-footer bg-white">
                        <a href=""><button class="btn btn-md btn-danger">Back</button></a>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="button" class="btn btn-primary">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
