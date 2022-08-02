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
            <div class="col-md-4 grid-margin stretch-card">
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
                        <h5>Add Data Unit of Measurement</h5>
                        <hr>
                        <div class="container-fluid">
                            <form class="form-label-left input_mask" method="post" action="{{ url('/products_uoms') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Name Unit of Measurement</label>
                                                <input type="text" class="form-control text-capitalize " name="satuan"
                                                    placeholder="Name Unit of Measurement">
                                                @error('satuan')
                                                    <small class="text-danger">The Unit of Measurement field is
                                                        required.</small>
                                                @enderror
                                            </div>


                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <button type="reset" class="btn btn-warning"
                                                    data-dismiss="modal">Reset</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>


                    </div>
                </div>

            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
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
                        <h5>All Data Unit of Measurement</h5>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="myTable" class="display expandable-table text-capitalize"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>Uom</th>

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
                                                    {{-- Modul Edit UOM --}}
                                                    <div class="modal fade" id="changeData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog " role="document">
                                                            <form method="post"
                                                                action="{{ url('products_uoms/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="PATCH">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Change Data Products Unit of Measurement :
                                                                            {{ $value->satuan }}</h5>
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
                                                                                            Unit of Measurement</label>
                                                                                        <input type="text"
                                                                                            class="form-control text-capitalize "
                                                                                            name="satuan"
                                                                                            value="{{ $value->satuan }}"
                                                                                            placeholder="Name Unit of Measurement">
                                                                                        @error('satuan')
                                                                                            <small class="text-danger">The
                                                                                                Unit of Measurement field is
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
                                                    {{-- End Modal Edit UOM --}}
                                                    {{-- Modul Delete UOM --}}
                                                    <div class="modal fade" id="deleteData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post"
                                                                action="{{ url('products_uoms/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="delete">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Delete Data Products Unit of Measurement :
                                                                            {{ $value->satuan }}</h5>
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
                                                    {{-- End Modal Delete UOM --}}
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->satuan }}</td>


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
