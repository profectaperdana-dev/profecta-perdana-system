@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Add {{ $title }}</h3>
                        <h6 class="font-weight-normal mb-0">Create, Read, Update and Delete {{ $title }}
                        </h6>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{-- Modul Tambah Produk --}}
                        <a class="btn btn-success" href="{{ url('products/create') }}">
                            Back
                        </a>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row font-weight-bold">
                                    <div class="col-md-4">
                                        <label>Product Code</label>
                                        <input type="text" class="form-control" placeholder="Product Code">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Product Name</label>
                                        <input type="text" class="form-control" placeholder="Product Name">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" placeholder="Serial Number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label>Single select box using select 2</label>
                                        <select class="js-example-basic-single form-control">
                                            <option value="AL">Alabama</option>
                                            <option value="WY">Wyoming</option>
                                            <option value="AM">America</option>
                                            <option value="CA">Canada</option>
                                            <option value="RU">Russia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row font-weight-bold">
                                    <div class="col-md-4">
                                        <label>Name
                                            Unit of Measurement</label>
                                        <select name="" id="uoms" class="form-control">
                                            <option value="">dfsdf</option>
                                            <option value="">dfsdf</option>
                                            <option value="">dfsdf</option>
                                            <option value="">dfsdf</option>
                                            <option value="">dfsdf</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Product Name</label>
                                        <input type="text" class="form-control" placeholder="Product Name">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" placeholder="Serial Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
