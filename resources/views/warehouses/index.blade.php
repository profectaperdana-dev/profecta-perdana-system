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
                        <h5>Add {{ $title }}</h5>
                        <hr>
                        <div class="container-fluid">
                            <form class="form-label-left input_mask" method="post" action="{{ url('/warehouses') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Name Warehouses</label>
                                                <input type="text"
                                                    class="form-control text-capitalize {{ $errors->first('warehouses') ? ' is-invalid' : '' }}"
                                                    name="warehouses" placeholder="Name Warehouses" required>
                                                @error('warehouses')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Address</label>
                                                <textarea placeholder="Address Warehouses" name="alamat" id="" cols="30" rows="5"
                                                    class="form-control text-capitalize {{ $errors->first('alamat') ? ' is-invalid' : '' }}" required></textarea>

                                                @error('alamat')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label>
                                                    Sales Area</label>
                                                <select id="" required name="id_area"
                                                    class="form-control uoms {{ $errors->first('id_area') ? ' is-invalid' : '' }}">
                                                    <option value="" selected>-Choose Area-</option>
                                                    @foreach ($areas as $list_area)
                                                        <option value="{{ $list_area->id }}">
                                                            {{ $list_area->area_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_area')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Latitude</label>
                                                <input type="text"
                                                    class="form-control text-capitalize {{ $errors->first('latitude') ? ' is-invalid' : '' }}"
                                                    name="latitude" placeholder="Latitude Warehouses" required>
                                                @error('latitude')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Longitude</label>
                                                <input type="text"
                                                    class="form-control text-capitalize {{ $errors->first('longitude') ? ' is-invalid' : '' }}"
                                                    name="longitude" placeholder="Longitude Warehouses" required>
                                                @error('longitude')
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
                                                <th>Warehouese</th>
                                                <th>Address</th>
                                                <th>Sales Area</th>
                                                <th>Coordinate Point</th>
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
                                                    {{-- Modul Edit Warehouses --}}
                                                    <div class="modal fade" id="changeData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <form method="post"
                                                                action="{{ url('warehouses/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="PATCH">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Change {{ $title }} :
                                                                            {{ $value->warehouses }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 font-weight-bold">
                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-4">
                                                                                        <label
                                                                                            class="font-weight-bold">Name
                                                                                            Warehouses</label>
                                                                                        <input type="text"
                                                                                            class="form-control text-capitalize {{ $errors->first('warehouses_') ? ' is-invalid' : '' }}"
                                                                                            name="warehouses_"
                                                                                            value="{{ $value->warehouses }}"
                                                                                            placeholder="Name Warehouses"
                                                                                            required>
                                                                                        @error('warehouses_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        <label
                                                                                            class="font-weight-bold">Address</label>
                                                                                        <textarea placeholder="Address Warehouses" name="alamat_" id="" cols="30" rows="5"
                                                                                            class="form-control text-capitalize {{ $errors->first('alamat_') ? ' is-invalid' : '' }}" required>{{ $value->alamat }}</textarea>

                                                                                        @error('alamat_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            Sales Area</label>
                                                                                        <select id="" required
                                                                                            name="id_area_"
                                                                                            class="form-control uoms {{ $errors->first('id_area_') ? ' is-invalid' : '' }}">
                                                                                            @foreach ($areas as $list_area)
                                                                                                <option
                                                                                                    value="{{ $list_area->id }}"
                                                                                                    @if ($value->id_area == $list_area->id) selected @endif>
                                                                                                    {{ $list_area->area_name }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        @error('id_area_')
                                                                                            <div class="invalid-feedback">
                                                                                                {{ $message }}
                                                                                            </div>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-4">
                                                                                        <label
                                                                                            class="font-weight-bold">Latitude</label>
                                                                                        <input type="text"
                                                                                            value="{{ $value->latitude }}"
                                                                                            class="form-control text-capitalize {{ $errors->first('latitude_') ? ' is-invalid' : '' }}"
                                                                                            name="latitude_"
                                                                                            placeholder="Latitude Warehouses"
                                                                                            required>
                                                                                        @error('latitude_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        <label
                                                                                            class="font-weight-bold">Longitude</label>
                                                                                        <input type="text"
                                                                                            value="{{ $value->longitude }}"
                                                                                            class="form-control text-capitalize {{ $errors->first('longitude_') ? ' is-invalid' : '' }}"
                                                                                            name="longitude_"
                                                                                            placeholder="Longitude Warehouses"
                                                                                            required>
                                                                                        @error('longitude_')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}.</small>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            Status</label>
                                                                                        <select id="" required
                                                                                            name="status"
                                                                                            class="form-control uoms {{ $errors->first('status') ? ' is-invalid' : '' }}">
                                                                                            <option
                                                                                                value="{{ $value->status }}"
                                                                                                selected>
                                                                                                @if ($value->status == 0)
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
                                                                                        @error('status')
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
                                                    {{-- End Modal Edit Warehouses --}}
                                                    {{-- Modul Delete Warehouses --}}
                                                    <div class="modal fade" id="deleteData{{ $value->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form method="post"
                                                                action="{{ url('warehouses/' . $value->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="delete">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize"
                                                                            id="exampleModalLabel">
                                                                            Delete {{ $title }} :
                                                                            {{ $value->warehouses }}</h5>
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
                                                    {{-- End Modal Delete Warehouses --}}
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->warehouses }}</td>
                                                    <td>{{ $value->alamat }}</td>
                                                    <td>{{ $value->area_name }}</td>
                                                    <td>{{ $value->latitude }},{{ $value->longitude }} </td>
                                                    <td>
                                                        @if ($value->status == 0)
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
