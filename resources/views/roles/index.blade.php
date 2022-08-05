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

                        <p class="card-title">Add User's Role</p>
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ url('/roles') }}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label>Role Name</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Enter Role Name" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label>Guard Name</label>
                                            <input type="text" name="guard_name"
                                                class="form-control @error('guard_name') is-invalid @enderror"
                                                placeholder="Enter Guard Name" required>
                                            @error('guard_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
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
                        <p class="card-title">User's Roles Table</p>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="myTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Guard Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $role)
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
                                                                    data-target="#editModal{{ $role->id }}">Edit</a>
                                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                                    data-target="#delModal{{ $role->id }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $role->name }}</td>
                                                    <td>{{ $role->guard_name }}</td>

                                                    <!-- Edit Mod -->
                                                    <div class="modal fade" id="editModal{{ $role->id }}" tabindex="-1"
                                                        role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form action="{{ url('/roles/' . $role->id) }}" method="POST">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                            User's Role :
                                                                            {{ $role->name }}</h5>
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
                                                                                            value="{{ $role->name }}"
                                                                                            placeholder="Customer Category Name"
                                                                                            required>
                                                                                        @error('name_edit')
                                                                                            {{ $message }}
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="col-md-12">
                                                                                        <label>Guard Name</label>
                                                                                        <input type="text"
                                                                                            name="guard_name_edit"
                                                                                            class="form-control @error('guard_name_edit') is-invalid @enderror"
                                                                                            placeholder="Enter Guard Name"
                                                                                            value="{{ $role->guard_name }}"
                                                                                            required>
                                                                                        @error('guard_name_edit')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}</small>
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
                                                                            Change</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!--End Edit Modal -->

                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="delModal{{ $role->id }}"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Delete
                                                                        User's Role :
                                                                        {{ $role->name }}</h5>
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
                                                                    <form action="{{ url('/roles/' . $role->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Yes, delete</button>
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
