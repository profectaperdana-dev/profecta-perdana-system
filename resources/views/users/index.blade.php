@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.5.1/css/autoFill.dataTables.min.css">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                   
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
                                        <label>Warehouse Area</label>
                                        @foreach ($warehouses as $warehouse)
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    name="userFields[{{ $loop->iteration }}][warehouse_id]" type="checkbox"
                                                    value="{{ $warehouse->id }}">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ $warehouse->warehouses }}
                                                </label>
                                            </div>
                                        @endforeach

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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data User Profecta Perdana System</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basics" class="table-striped expandable-table table table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Username</th>
                                        <!--<th>Job</th>-->
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Warehouse</th>
                                        <th style="width: 10%"></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $value)
                                        <tr>


                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                @if ($value->status == 1)
                                                    <span class="badge rounded-pill bg-primary">Active</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger">Nonactive</span>
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{ $value->name }}</td>
                                            <td>{{ $value->username }}</td>
                                            <!--<td>{{ $value->job_name }}</td>-->
                                            <td>{{ $value->role_name }}</td>
                                            <td>
                                                @foreach ($value->userWarehouseBy as $item)
                                                    <span class="badge bg-primary">{{ $item->warehouseBy->warehouses }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td style="width: 10%" class="text-center">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#authData{{ $value->id }}">Authorization</a>
                                                    <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#changeData{{ $value->id }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#resetData{{ $value->id }}">Reset Password</a>
                                                </div>
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

    @foreach ($users as $key => $value)
        {{-- Modul Authorization --}}
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="authData{{ $value->id }}"
            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Authorization:
                            {{ $value->name }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ url('/change_authorization/' . $value->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @foreach ($master_section as $ms)
                                <div class="card card-absolute pt-4">
                                    <div class="card-header bg-primary mb-5 mt-3">
                                        <h5 class="text-white">{{ $ms->master_section }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($section as $sc)
                                                @if ($ms->master_section == $sc->master_section)
                                                    <div class="card col-12 col-lg-3 pb-2">
                                                        <div class="text-dark p-3">
                                                            <div class="media">
                                                                <label
                                                                    class="col-form-label m-r-10">{{ $sc->section }}</label>
                                                                <div
                                                                    class="media-body text-end switch-sm icon-state switch-outline">
                                                                    <label class="switch">
                                                                        <input type="checkbox" class="section-check"><span
                                                                            class="switch-state"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <ul class="list-group list-group-flush">
                                                            @foreach ($auth as $item)
                                                                @if ($sc->section == $item->section)
                                                                    <li class="list-group-item">
                                                                        <div class="media">
                                                                            <label
                                                                                class="col-form-label m-r-10 fw-normal">{{ $item->menu_name }}</label>
                                                                            <div class="media-body text-end switch-sm">
                                                                                <label class="switch">
                                                                                    <input type="checkbox"
                                                                                        @if ($value->checkAuth($item->id)) checked="checked" @endif
                                                                                        name="authFields[{{ $item->id }}][auth_id]"
                                                                                        value="{{ $item->id }}"><span
                                                                                        class="switch-state"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                            <div class="modal-footer">
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Authorization --}}
        {{-- Modul Edit UOM --}}
        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('users/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                {{ $value->satuan }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                                    <option value="{{ $role->id }}"
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
                                            <label>Status</label>
                                            <select name="status"
                                                class="form-control role-acc @error('status') is-invalid @enderror"
                                                required>
                                                <option value="1" @if ($value->status == 1) selected @endif>
                                                    Active
                                                </option>
                                                <option value="0" @if ($value->status == 0) selected @endif>
                                                    Nonactive
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label>Warehouse</label>
                                            @foreach ($warehouses as $warehouse)
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        name="userEditFields[{{ $loop->iteration }}][edit_warehouse_id]"
                                                        type="checkbox" value="{{ $warehouse->id }}"
                                                        @if ($value->userWarehouseBy->contains('warehouse_id', $warehouse->id)) checked="checked" @endif>
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{ $warehouse->warehouses }}
                                                    </label>
                                                </div>
                                            @endforeach
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
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button class="btn btn-primary" type="submit">Save
                                changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Modal Edit UOM --}}
        {{-- Modul Delete UOM --}}
        <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('users/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                {{ $value->name }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Yes, delete
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Modal Delete UOM --}}
        {{-- Modul Reset UOM --}}
        <div class="modal fade" id="resetData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Reset Password
                            {{ $value->name }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h5>Are you sure to reset password this user ?</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <a href="{{ url('/reset_password/' . $value->id) }}" class="btn btn-primary">Yes, reset
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Modal Reset UOM --}}
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/autofill/2.5.1/js/dataTables.autoFill.min.js"></script>
        <script>
            $(document).ready(function() {
                $(document).on('submit', 'form', function() {
                    // console.log('click');
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    // console.log(form.html());

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);

                    }
                });
                $('#basics').dataTable({
                    "pageLength": 100,
                    dom: 'lpftrip',
                });

                $(document).on('click', '.section-check', function() {
                    let sec_check = $(this).prop('checked');
                    $(this).closest('.card').find('ul').find('input[type="checkbox"]').each(function(index,
                        value) {
                        if (sec_check) {
                            $(this).prop('checked', true);
                        } else $(this).prop('checked', false);

                    });
                });

                $(document).on("click", ".modal-btn", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    $(modal_id).find(".role-acc, .job-acc, .warehouse-acc").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });

                });

                var t = $('#example').DataTable({
                    autoFill: false,
                    columnDefs: [{
                            searchable: false,
                            orderable: false,
                            targets: 1,
                        },
                        {
                            searchable: false,
                            orderable: false,
                            targets: 0,
                        }
                    ],
                    order: [
                        [2, 'asc']
                    ],
                });

                t.on('order.dt search.dt', function() {
                    let i = 1;

                    t.cells(null, 1, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();

            })
        </script>
    @endpush
@endsection
