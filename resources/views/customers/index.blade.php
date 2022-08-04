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

                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <p class="card-title">Customers Table</p>
                        <div>
                            <a href="{{ url('/customers/create') }}">
                                <button class="btn btn-md btn-success">+ Add
                                    Customer</button>
                            </a>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="myTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Category</th>
                                                <th>Area</th>
                                                <th>Coordinate</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $customer)
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
                                                                    data-target="#detailModal{{ $customer->code_cust }}">Detail</a>
                                                                <a class="dropdown-item"
                                                                    href="{{ url('/customers/' . $customer->code_cust . '/edit') }}">Edit</a>
                                                                <a class="dropdown-item" href="#" data-toggle="modal"
                                                                    data-target="#delModal{{ $customer->code_cust }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $customer->code_cust }}</td>
                                                    <td>{{ $customer->name_cust }}</td>
                                                    <td>{{ $customer->phone_cust }}</td>
                                                    <td>{{ $customer->category_name }}</td>
                                                    <td>{{ $customer->area_name }}</td>
                                                    <td>{{ $customer->coordinate }}</td>
                                                    <td>
                                                        @if ($customer->status == 1)
                                                            <div class="badge badge-success">Active</div>
                                                        @else
                                                            <div class="badge badge-danger">Nonactive</div>
                                                        @endif
                                                    </td>

                                                    {{-- Modal Detail Product --}}
                                                    <div class="modal fade" id="detailModal{{ $customer->code_cust }}"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog-scrollable"
                                                            role="document">
                                                            <form>
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-capitalize">
                                                                            Customer Detail :
                                                                            {{ $customer->name_cust }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-4 font-weight-bold">
                                                                                <label>Preview Image</label>
                                                                                <img width="100%"
                                                                                    style="border: 2px solid rgb(0, 0, 0);"
                                                                                    src="{{ asset('images/customers/' . $customer->reference_image) }}"
                                                                                    alt="Preview Image">
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-6">
                                                                                        <label>Code Customer </label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Product Code"
                                                                                            readonly
                                                                                            value="{{ $customer->code_cust }}">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label>Name Customer</label>
                                                                                        <input type="text"
                                                                                            class="form-control "
                                                                                            placeholder="Product Name"
                                                                                            readonly
                                                                                            value="{{ $customer->name_cust }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-12">
                                                                                        <label>Address Customer</label>
                                                                                        <textarea class="form-control" rows="3" readonly>{{ $customer->address_cust }}</textarea>
                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            Phone Customer</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Serial Number"
                                                                                            readonly
                                                                                            value="{{ $customer->phone_cust }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            Customer Email</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Serial Number"
                                                                                            readonly
                                                                                            value="{{ $customer->email_cust }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            Customer Category</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            placeholder="Serial Number"
                                                                                            readonly
                                                                                            value="{{ $customer->category_name }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="form-group row font-weight-bold">

                                                                                    <div class="col-md-4">
                                                                                        <label>Customer Area
                                                                                        </label>
                                                                                        <input type="text" readonly
                                                                                            class="form-control"
                                                                                            value="{{ $customer->area_name }}">

                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Coordinate</label>
                                                                                        <input type="text"
                                                                                            class="form-control" readonly
                                                                                            value="{{ $customer->coordinate }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>Credit Limit</label>
                                                                                        <input type="text"
                                                                                            class="form-control" readonly
                                                                                            value="{{ $customer->credit_limit }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="form-group row font-weight-bold">
                                                                                    <div class="col-md-3">
                                                                                        <label>Status</label>
                                                                                        <br>
                                                                                        @if ($customer->status == 1)
                                                                                            <h1
                                                                                                class="badge badge-pill badge-success">
                                                                                                Active</h1>
                                                                                        @else
                                                                                            <span
                                                                                                class="badge badge-pill badge-danger">Nonactive</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Detail Product --}}

                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="delModal{{ $customer->code_cust }}"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Delete
                                                                        Costumer :
                                                                        {{ $customer->code_cust }}</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure delete this costumer?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Close</button>
                                                                    <form
                                                                        action="{{ url('/customers/' . $customer->code_cust) }}"
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
