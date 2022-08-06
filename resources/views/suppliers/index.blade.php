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
                        <h5>Add {{ $title }}</h5>
                        <hr>
                        <div class="container-fluid">
                            <form class="form-label-left input_mask" method="post" action="{{ url('/suppliers') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Supplier Name</label>
                                                <input type="text" value="{{ old('nama_supplier') }}"
                                                    class="form-control text-capitalize {{ $errors->first('nama_supplier') ? ' is-invalid' : '' }}"
                                                    name="nama_supplier" placeholder="Supplier Name" required>
                                                @error('nama_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Address</label>
                                                <textarea placeholder="Supplier Address" name="alamat_supplier" id="" cols="30" rows="5"
                                                    class="form-control text-capitalize {{ $errors->first('alamat_supplier') ? ' is-invalid' : '' }}" required>{{ old('alamat_supplier') }}</textarea>
                                                @error('alamat_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Phone Number</label>
                                                <input type="text" value="{{ old('no_telepon_supplier') }}"
                                                    class="form-control text-capitalize {{ $errors->first('no_telepon_supplier') ? ' is-invalid' : '' }}"
                                                    name="no_telepon_supplier" placeholder="Phone Number" required>
                                                @error('no_telepon_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">NPWP Supplier</label>
                                                <input type="text" value="{{ old('npwp_supplier') }}"
                                                    class="form-control text-capitalize {{ $errors->first('npwp_supplier') ? ' is-invalid' : '' }}"
                                                    name="npwp_supplier" placeholder="NPWP Supplier" required>
                                                @error('npwp_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">PIC Supplier</label>
                                                <input type="text" value="{{ old('pic_supplier') }}"
                                                    class="form-control text-capitalize {{ $errors->first('pic_supplier') ? ' is-invalid' : '' }}"
                                                    name="pic_supplier" placeholder="PIC Supplier" required>
                                                @error('pic_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
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
                        <h5>All {{ $title }}</h5>
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
                                                <th>Name Supplier</th>
                                                <th>Addres</th>
                                                <th>Phone Number</th>
                                                <th>NPWP</th>
                                                <th>PIC</th>
                                                <th>Status</th>

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
                                                                <a class="dropdown-item" href="#"
                                                                    data-toggle="modal"
                                                                    data-target="#changeData{{ $value->id }}">
                                                                    Edit
                                                                </a>
                                                                <a class="dropdown-item" href="#"
                                                                    data-toggle="modal"
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
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <form method="post"
                                                                action="{{ url('suppliers/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="PATCH">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Change {{ $title }} :
                                                                            {{ $value->nama_supplier }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">

                                                                            <div class="col-md-12">
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-6">
                                                                                        <label
                                                                                            class="font-weight-bold">Supplier
                                                                                            Name</label>
                                                                                        <input type="text"
                                                                                            value="{{ old('nama_supplier_', $value->nama_supplier) }}"
                                                                                            class="form-control text-capitalize {{ $errors->first('nama_supplier_') ? ' is-invalid' : '' }}"
                                                                                            name="nama_supplier_"
                                                                                            placeholder="Supplier Name"
                                                                                            required>
                                                                                        @error('nama_supplier_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <label
                                                                                            class="font-weight-bold">Address</label>
                                                                                        <textarea placeholder="Supplier Address" name="alamat_supplier_" id="" cols="30" rows="5"
                                                                                            class="form-control text-capitalize {{ $errors->first('alamat_supplier_') ? ' is-invalid' : '' }}" required>{{ old('alamat_supplier_', $value->alamat_supplier) }}</textarea>
                                                                                        @error('alamat_supplier_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-6">
                                                                                        <label
                                                                                            class="font-weight-bold">Phone
                                                                                            Number</label>
                                                                                        <input type="text"
                                                                                            value="{{ old('no_telepon_supplier_', $value->no_telepon_supplier) }}"
                                                                                            class="form-control text-capitalize {{ $errors->first('no_telepon_supplier_') ? ' is-invalid' : '' }}"
                                                                                            name="no_telepon_supplier_"
                                                                                            placeholder="Phone Number"
                                                                                            required>
                                                                                        @error('no_telepon_supplier_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <label
                                                                                            class="font-weight-bold">NPWP
                                                                                            Supplier</label>
                                                                                        <input type="text"
                                                                                            value="{{ old('npwp_supplier_', $value->npwp_supplier) }}"
                                                                                            class="form-control text-capitalize {{ $errors->first('npwp_supplier_') ? ' is-invalid' : '' }}"
                                                                                            name="npwp_supplier_"
                                                                                            placeholder="NPWP Supplier"
                                                                                            required>
                                                                                        @error('npwp_supplier_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-6">
                                                                                        <label class="font-weight-bold">PIC
                                                                                            Supplier</label>
                                                                                        <input type="text"
                                                                                            value="{{ old('pic_supplier_', $value->pic_supplier) }}"
                                                                                            class="form-control text-capitalize {{ $errors->first('pic_supplier_') ? ' is-invalid' : '' }}"
                                                                                            name="pic_supplier_"
                                                                                            placeholder="PIC Supplier"
                                                                                            required>
                                                                                        @error('pic_supplier_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label>
                                                                                            Status</label>
                                                                                        <select id="" required
                                                                                            name="status_supplier"
                                                                                            class="form-control uoms {{ $errors->first('status_supplier') ? ' is-invalid' : '' }}">
                                                                                            <option
                                                                                                value="{{ $value->status_supplier }}"
                                                                                                selected>
                                                                                                @if ($value->status_supplier == 0)
                                                                                                    Non Active
                                                                                                @else
                                                                                                    Active
                                                                                                @endif
                                                                                            </option>
                                                                                            <option value="1">Active
                                                                                            </option>
                                                                                            <option value="0">Non
                                                                                                Active</option>
                                                                                        </select>
                                                                                        @error('status_supplier')
                                                                                            <div class="invalid-feedback">
                                                                                                {{ $message }}
                                                                                            </div>
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
                                                                action="{{ url('suppliers/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="delete">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Delete {{ $title }} :
                                                                            {{ $value->nama_supplier }}</h5>
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
                                                    <td>{{ $value->nama_supplier }}</td>
                                                    <td>
                                                        <address>{{ $value->alamat_supplier }}</address>
                                                    </td>
                                                    <td>{{ $value->no_telepon_supplier }}</td>
                                                    <td>{{ $value->npwp_supplier }}</td>
                                                    <td>{{ $value->pic_supplier }}</td>
                                                    <td>
                                                        @if ($value->status_supplier == 0)
                                                            <div><span class="badge badge-danger">Non Active</span></div>
                                                        @else
                                                            <div><span class="badge badge-success"> Active</span></div>
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
    </div>
@endsection
