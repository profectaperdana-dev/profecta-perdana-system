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
                        <form class="form-label-left input_mask" method="post" action="{{ url('/users') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Employee</label>
                                        <select name="employee_id"
                                            class="form-control role-acc @error('employee_id') is-invalid @enderror"
                                            required>
                                            <option value="">Choose Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select name="role_id"
                                            class="form-control role-acc @error('role_id') is-invalid @enderror" required>
                                            <option value="">Choose Role for Account</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">
                                                    {{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Job</label>
                                        <select name="job_id"
                                            class="form-control job-acc @error('job_id') is-invalid @enderror" required>
                                            <option value="">Choose Job for Account</option>
                                            @foreach ($jobs as $job)
                                                <option value="{{ $job->id }}">
                                                    {{ $job->job_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('job_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Warehouse Area</label>
                                        <select name="warehouse_id"
                                            class="form-control warehouse-acc @error('warehouse_id') is-invalid @enderror"
                                            required>
                                            <option value="">Choose where this account place</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">
                                                    {{ $warehouse->warehouses }}</option>
                                            @endforeach
                                        </select>
                                        @error('warehouse_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
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
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%"></th>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Job</th>
                                        <th>Role</th>
                                        <th>Warehouse</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $value)
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
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('users/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="PATCH">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $value->satuan }}</h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label>Employee</label>
                                                                            <select name="employee_id_edit"
                                                                                class="form-control role-acc @error('employee_id_edit') is-invalid @enderror"
                                                                                required>
                                                                                <option value="">Choose Employee
                                                                                </option>
                                                                                @foreach ($employees as $employee)
                                                                                    <option value="{{ $employee->id }}"
                                                                                        @if ($value->employee_id == $employee->id) selected @endif>
                                                                                        {{ $employee->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('employe_id_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-12">
                                                                                <label>Role</label>
                                                                                <select name="role_id_edit"
                                                                                    class="form-control role-acc @error('role_id_edit') is-invalid @enderror"
                                                                                    required>
                                                                                    <option value="">
                                                                                        Choose
                                                                                        Role for Account
                                                                                    </option>
                                                                                    @foreach ($roles as $role)
                                                                                        <option
                                                                                            value="{{ $role->id }}"
                                                                                            @if ($value->role_id == $role->id) selected @endif>
                                                                                            {{ $role->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('role_id_edit')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-12">
                                                                                <label>Job</label>
                                                                                <select name="job_id_edit"
                                                                                    class="form-control job-acc @error('job_id_edit') is-invalid @enderror"
                                                                                    required>
                                                                                    <option value="">
                                                                                        Choose
                                                                                        Job for Account
                                                                                    </option>
                                                                                    @foreach ($jobs as $job)
                                                                                        <option
                                                                                            value="{{ $job->id }}"
                                                                                            @if ($value->job_id == $job->id) selected @endif>
                                                                                            {{ $job->job_name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('job_id_edit')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-12">
                                                                                <label>Warehouse</label>
                                                                                <select name="warehouse_id_edit"
                                                                                    class="form-control warehouse-acc @error('warehouse_id_edit') is-invalid @enderror"
                                                                                    required>
                                                                                    <option value="">
                                                                                        Choose
                                                                                        where this account place
                                                                                    </option>
                                                                                    @foreach ($warehouses as $warehouse)
                                                                                        <option
                                                                                            value="{{ $warehouse->id }}"
                                                                                            @if ($value->warehouse_id == $warehouse->id) selected @endif>
                                                                                            {{ $warehouse->warehouses }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('warehouse_id_edit')
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
                                                    <form method="post" action="{{ url('users/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->satuan }}</h5>
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
                                            <td class="text-capitalize">{{ $value->name }}</td>
                                            <td>{{ $value->username }}</td>
                                            <td>{{ $value->job_name }}</td>
                                            <td>{{ $value->role_name }}</td>
                                            <td>{{ $value->warehouse_name }}</td>


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
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });

                $(document).on("click", ".modal-btn", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    $(modal_id).find(".role-acc, .job-acc").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });

                });
            })
        </script>
    @endpush
@endsection
