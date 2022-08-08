@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">{{ $title }}</h3>
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
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong class="text-capitalize">{{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session()->has('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong class="text-capitalize">{{ session('info') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong class="text-capitalize">{{ session('error') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        {{-- Modul Tambah Produk --}}
                        <a class="btn btn-success" href="{{ url('products/create') }}">
                            + Add Products
                        </a>

                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="myTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>Products</th>
                                                <th>S/N</th>
                                                <th>Uom</th>
                                                <th>Group</th>
                                                <th>Sub Group</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key => $value)
                                                <tr>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn"
                                                                id="dropdownMenuIconButton7" data-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                <i class="ti-more-alt"></i>
                                                            </button>
                                                            <div class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuIconButton7">
                                                                <h6 class="dropdown-header">Settings</h6>
                                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                                    data-target="#detailData{{ $value->id }}">
                                                                    Detail
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="{{ url('/products/' . $value->id . '/edit') }}">
                                                                    Edit
                                                                </a>
                                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                                    data-target="#deleteData{{ $value->id }}">
                                                                    Delete
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    {{-- Modul Detail Product --}}
                                                    <div class="modal fade" id="detailData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <form method="post"
                                                                action="{{ url('product_sub_materials/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="PATCH">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Detail Product {{ $title }} :
                                                                            {{ $value->nama_barang }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <img width="100%"
                                                                                    class="img-fluid shadow-lg"
                                                                                    src="{{ asset('foto_produk/' . $value->foto_barang) }}"
                                                                                    alt="">
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-4">
                                                                                        <label>Product Code</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Product Code"
                                                                                            readonly
                                                                                            value="{{ $value->kode_barang }}">

                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Product Name</label>
                                                                                        <input type="text"
                                                                                            class="form-control "
                                                                                            placeholder="Product Name"
                                                                                            readonly
                                                                                            value="{{ $value->nama_barang }}">

                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Serial Number</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Serial Number"
                                                                                            readonly
                                                                                            value="{{ $value->no_seri }}">

                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-4">

                                                                                        <label>
                                                                                            Unit of Measurement</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Serial Number"
                                                                                            readonly
                                                                                            value="{{ $value->satuan }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            Material</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Serial Number"
                                                                                            readonly
                                                                                            value="{{ $value->nama_material }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            Sub Material</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Serial Number"
                                                                                            readonly
                                                                                            value="{{ $value->nama_sub_material }}">
                                                                                    </div>

                                                                                </div>

                                                                                <div
                                                                                    class="form-group row font-weight-bold">

                                                                                    <div class="col-md-4">
                                                                                        <label>Purchase Price
                                                                                        </label>
                                                                                        <input type="text" readonly
                                                                                            class="form-control"
                                                                                            value="@currency($value->harga_beli)">

                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Retail Selling Price </label>
                                                                                        <input type="text"
                                                                                            class="form-control" readonly
                                                                                            placeholder="Retail Selling Price"
                                                                                            value="@currency($value->harga_jual)">

                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Non Retail Selling
                                                                                            Price</label>
                                                                                        <input type="text"
                                                                                            class="form-control" readonly
                                                                                            placeholder="Non Retail Selling Price"
                                                                                            value="@currency($value->harga_jual_nonretail)">

                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-3">
                                                                                        <label>Product Weight <span><small
                                                                                                    class="text-info">Gram</small></span></label>
                                                                                        <input type="number"
                                                                                            class="form-control" readonly
                                                                                            value="{{ $value->berat }}">

                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label>Qty Stock </label>
                                                                                        <input type="number"
                                                                                            class="form-control" readonly
                                                                                            value="{{ $value->qty }}">

                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label>Min Stock</label>
                                                                                        <input type="number"
                                                                                            class="form-control" readonly
                                                                                            value="{{ $value->minstok }}">

                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label>Status</label>
                                                                                        <input type="number"
                                                                                            class="form-control" readonly
                                                                                            value="{{ $value->status }}">

                                                                                    </div>



                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close</button>

                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Detail Product --}}

                                                    {{-- Modul Delete Product --}}
                                                    <div class="modal fade" id="deleteData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post"
                                                                action="{{ url('products/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="delete">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Delete {{ $title }} :
                                                                            {{ $value->nama_barang }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-12">
                                                                                        <h4>Are you sure delete this data ?
                                                                                        </h4>

                                                                                    </div>

                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close</button>

                                                                        <button type="submit"
                                                                            class="btn btn-primary">Yes, Delete</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Delete Product --}}

                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->kode_barang }}</td>
                                                    <td>{{ $value->nama_barang }}</td>
                                                    <td>{{ $value->no_seri }}</td>
                                                    <td>{{ $value->satuan }}</td>
                                                    <td>{{ $value->nama_material }}</td>
                                                    <td>{{ $value->nama_sub_material }}</td>
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
    </div>
@endsection
