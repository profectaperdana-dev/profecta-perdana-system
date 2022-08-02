@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">{{ $title }}</h3>
                        <h6 class="font-weight-normal mb-0">Create, Read, Update and Delete Data
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
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                            + Add Products
                        </button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Change Data Products</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                            <label>Product Code</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Product Code">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Product Name</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Product Name">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Serial</label>
                                                            <input type="text" class="form-control" placeholder="Serial">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- End Modal Tambah Produk --}}
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
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->kode_barang }}</td>
                                                    <td>{{ $value->nama_barang }}</td>
                                                    <td>{{ $value->no_seri }}</td>
                                                    <td>{{ $value->uom }}</td>
                                                    <td>{{ $value->material_grup }}</td>
                                                    <td>{{ $value->sub_material }}</td>
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
