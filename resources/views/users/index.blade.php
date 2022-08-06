@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">{{ $title }}</h3>

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
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <p class="card-title">Add Account</p>
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ url('/users') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Enter Account Name" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Enter Account Email" required>
                                        @error('email')
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
                                        <label>Warehouse</label>
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
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Customer Areas Table</p>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="myTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Warehouse</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
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
                                                                    data-target="#editModal{{ $user->id }}">Edit</a>
                                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                                    data-target="#delModal{{ $user->id }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->role_name }}</td>
                                                    <td>{{ $user->warehouse_name }}</td>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editModal{{ $user->id }}"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form action="{{ url('/users/' . $user->id) }}" method="POST">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Edit User Account :
                                                                            {{ $user->name }}</h5>
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
                                                                                        <label>Name</label>
                                                                                        <input type="text"
                                                                                            class="form-control @error('name_edit') is-invalid @enderror"
                                                                                            name="name_edit"
                                                                                            value="{{ $user->name }}"
                                                                                            placeholder="Enter Account Name"
                                                                                            required>
                                                                                        @error('name_edit')
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
                                                                                                        @if ($user->role_id == $role->id) selected @endif>
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
                                                                                            <label>Warehouse</label>
                                                                                            <select
                                                                                                name="warehouse_id_edit"
                                                                                                class="form-control warehouse-acc @error('warehouse_id_edit') is-invalid @enderror"
                                                                                                required>
                                                                                                <option value="">
                                                                                                    Choose
                                                                                                    where this account place
                                                                                                </option>
                                                                                                @foreach ($warehouses as $warehouse)
                                                                                                    <option
                                                                                                        value="{{ $warehouse->id }}"
                                                                                                        @if ($user->warehouse_id == $warehouse->id) selected @endif>
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
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="reset"
                                                                            class="btn btn-warning">Reset</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save
                                                                            Change</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!--End Edit Modal -->

                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="delModal{{ $user->id }}"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        Delete User Account :
                                                                        {{ $user->name }}</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure delete this data?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Close</button>
                                                                    <form action="{{ url('/users/' . $user->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Yes,
                                                                            delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--End Delete Modal -->
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
