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
                    </div>
                    <div class="card-body">
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
                                        <label class="font-weight-bold">Type Warehouse</label>
                                        <select name="type" class="form-control uoms text-uppercase" id="">
                                            <option value="" selected>--Choose Type--
                                            </option>
                                            @foreach ($warehouse_types as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }} -
                                                    ({{ $value->detail }})
                                                </option>
                                            @endforeach
                                        </select>
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
                                {{-- <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Latitude</label>
                                            <input type="text"
                                                class="form-control text-capitalize {{ $errors->first('latitude') ? ' is-invalid' : '' }}"
                                                name="latitude" placeholder="Latitude Warehouses" required>
                                            @error('latitude')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div> --}}
                                {{-- <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Longitude</label>
                                            <input type="text"
                                                class="form-control text-capitalize {{ $errors->first('longitude') ? ' is-invalid' : '' }}"
                                                name="longitude" placeholder="Longitude Warehouses" required>
                                            @error('longitude')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div> --}}
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="reset" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>

                    </div>
                    </form>
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
                                        <th></th>
                                        <th>#</th>
                                        <th>Warehouese</th>
                                        <th>Type</th>
                                        <th>Address</th>
                                        <th>Sales Area</th>
                                        {{-- <th>Coordinate Point</th> --}}
                                        <th>Status</th>

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
                                                    <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#changeData{{ $value->id }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
                                            {{-- Modul Edit UOM --}}
                                            <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <form method="post" action="{{ url('warehouses/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="PATCH">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $value->warehouses }}</h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 font-weight-bold">
                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="col-md-12 form-group">
                                                                                <label class="font-weight-bold">Name
                                                                                    Warehouses</label>
                                                                                <input type="text"
                                                                                    class="form-control text-capitalize {{ $errors->first('warehouses_') ? ' is-invalid' : '' }}"
                                                                                    name="warehouses_"
                                                                                    value="{{ $value->warehouses }}"
                                                                                    placeholder="Name Warehouses" required>
                                                                                @error('warehouses_')
                                                                                    <small
                                                                                        class="text-danger">{{ $message }}.</small>
                                                                                @enderror
                                                                            </div>

                                                                            <div class="col-md-12 form-group">
                                                                                <label
                                                                                    class="font-weight-bold">Address</label>
                                                                                <textarea placeholder="Address Warehouses" name="alamat_" id="" cols="30" rows="5"
                                                                                    class="form-control text-capitalize {{ $errors->first('alamat_') ? ' is-invalid' : '' }}" required>{{ $value->alamat }}</textarea>

                                                                                @error('alamat_')
                                                                                    <small
                                                                                        class="text-danger">{{ $message }}.</small>
                                                                                @enderror
                                                                            </div>

                                                                            <div class="col-md-12 form-group">
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
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label class="font-weight-bold">Type
                                                                                        Warehouse</label>
                                                                                    <select name="type_"
                                                                                        class="form-control uoms">
                                                                                        @foreach ($warehouse_types as $val)
                                                                                            <option
                                                                                                value="{{ $val->id }}"
                                                                                                @if ($value->type == $val->id) selected @endif>
                                                                                                {{ $val->name }}
                                                                                            </option>
                                                                                        @endforeach



                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 form-group">
                                                                                <label>
                                                                                    Status</label>
                                                                                <select id="" required
                                                                                    name="status"
                                                                                    class="form-control uoms {{ $errors->first('status_supplier') ? ' is-invalid' : '' }}">
                                                                                    <option value="{{ $value->status }}"
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
                                            {{-- End Modal Edit UOM --}}
                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('warehouses/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->warehouses }}</h5>
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
                                            <td>{{ $value->warehouses }}</td>
                                            <td>{{ $value->typeBy->name }}</td>
                                            <td>
                                                <address>{{ $value->alamat }}</address>
                                            </td>
                                            <td>{{ $value->area_name }}</td>
                                            {{-- <td>{{ $value->latitude }},{{ $value->longitude }} </td> --}}
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
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).on("click", ".modal-btn", function(event) {
                let modal_id = $(this).attr('data-bs-target');

                $(modal_id).find(".uoms").select2({
                    width: "100%",
                    dropdownParent: modal_id,
                });

            });
        </script>
    @endpush
@endsection
