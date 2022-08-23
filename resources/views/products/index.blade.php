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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">
                        <a class="btn btn-primary" href="{{ url('products/create') }}">
                            + Create Products
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Products</th>
                                        <th>S/N</th>
                                        <th>Weight <span><small class="badge badge-danger">gram</small></span></th>
                                        <th>Materials</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#detailData{{ $value->id }}">Detail</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url('/products/' . $value->id . '/edit') }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
                                            {{-- Modul Detail UOM --}}
                                            <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detail Data
                                                                {{ $value->nama_barang }}</h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="mb-5 col-md-4">
                                                                        <img width="100%" class="img-fluid shadow-lg"
                                                                            src="{{ asset('foto_produk/' . $value->foto_barang) }}"
                                                                            alt="">
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-4">
                                                                                <label>Product Code</label>
                                                                                <input type="text"
                                                                                    class="form-control text-uppercase"
                                                                                    placeholder="Product Code" readonly
                                                                                    value="{{ $value->kode_barang }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Product Name</label>
                                                                                <input type="text" class="form-control "
                                                                                    placeholder="Product Name" readonly
                                                                                    value="{{ $value->nama_barang }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Serial Number</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->no_seri }}">

                                                                            </div>

                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Unit of Measurement</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->satuan }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Material</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->nama_material }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Sub Material</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->nama_sub_material }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Sub Material</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->type_name }}">
                                                                            </div>


                                                                            <div class="form-group col-md-4">
                                                                                <label>Purchase Price
                                                                                </label>
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    value="@currency($value->harga_beli)">

                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Retail Selling Price </label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    placeholder="Retail Selling Price"
                                                                                    value="@currency($value->harga_jual)">

                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Non Retail Selling
                                                                                    Price</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    placeholder="Non Retail Selling Price"
                                                                                    value="@currency($value->harga_jual_nonretail)">
                                                                            </div>

                                                                            <div class="form-group col-md-3">
                                                                                <label>Product Weight (gr)</label>
                                                                                <input type="number" class="form-control"
                                                                                    readonly value="{{ $value->berat }}">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Min Stock</label>
                                                                                <input type="number" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->minstok }}">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Status</label>
                                                                                @if ($value->status == 0)
                                                                                    <div>
                                                                                        <span
                                                                                            class="badge badge-danger">Non
                                                                                            Active</span>
                                                                                    </div>
                                                                                @else
                                                                                    <div><span class="badge badge-success">
                                                                                            Active</span></div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-danger" type="button"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- End Modal Detail UOM --}}
                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('products/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->nama_barang }}</h5>
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
                                            <td class="text-uppercase">{{ $value->kode_barang }}</td>
                                            <td>{{ $value->nama_barang }}</td>
                                            <td>{{ $value->no_seri }}</td>
                                            <td>{{ $value->berat }}</td>
                                            <td>{{ $value->nama_material }}/{{ $value->nama_sub_material }}/{{ $value->type_name }}
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
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    @endpush
@endsection
