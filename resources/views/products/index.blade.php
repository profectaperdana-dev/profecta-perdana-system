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
                                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                                    data-target="#changeData{{ $value->id }}">
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
                                                                            Detail {{ $title }} :
                                                                            {{ $value->nama_sub_material }}</h5>
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
                                                                                        <label class="font-weight-bold">Name
                                                                                            Product Sub Material</label>
                                                                                        <input type="text"
                                                                                            class="form-control text-capitalize "
                                                                                            name="editnama_submaterial"
                                                                                            value="{{ $value->nama_sub_material }}"
                                                                                            placeholder="Name Unit of Measurement">
                                                                                        @error('editnama_submaterial')
                                                                                            <small class="text-danger">The
                                                                                                Product Sub Material field is
                                                                                                required.</small>
                                                                                        @enderror
                                                                                    </div>

                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="reset"
                                                                            class="btn btn-warning">Reset</button>
                                                                        <button type="submit" class="btn btn-primary">Save
                                                                            changes</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Detail Product --}}
                                                    {{-- Modul Edit Product --}}
                                                    <div class="modal fade" id="changeData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog " role="document">
                                                            <form method="post"
                                                                action="{{ url('product_sub_materials/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="PATCH">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Change {{ $title }} :
                                                                            {{ $value->nama_sub_material }}</h5>
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
                                                                                        <label
                                                                                            class="font-weight-bold">Name
                                                                                            Product Sub Material</label>
                                                                                        <input type="text"
                                                                                            class="form-control text-capitalize "
                                                                                            name="editnama_submaterial"
                                                                                            value="{{ $value->nama_sub_material }}"
                                                                                            placeholder="Name Unit of Measurement">
                                                                                        @error('editnama_submaterial')
                                                                                            <small class="text-danger">The
                                                                                                Product Sub Material field is
                                                                                                required.</small>
                                                                                        @enderror
                                                                                    </div>

                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="reset"
                                                                            class="btn btn-warning">Reset</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save
                                                                            changes</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Edit Product --}}
                                                    {{-- Modul Delete Product --}}
                                                    <div class="modal fade" id="deleteData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post"
                                                                action="{{ url('product_sub_materials/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="delete">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Delete {{ $title }} :
                                                                            {{ $value->nama_material }}</h5>
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
