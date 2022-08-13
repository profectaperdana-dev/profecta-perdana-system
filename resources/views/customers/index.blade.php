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
                        <a class="btn btn-primary" href="{{ url('/customers/create') }}">
                            + Create Customers
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        @if (Gate::check('isSuperAdmin') || Gate::check('isAdmin'))
                                            <th></th>
                                        @endif
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
                                    @foreach ($customers as $key => $value)
                                        <tr>
                                            @if (Gate::check('isSuperAdmin') || Gate::check('isAdmin'))
                                                <td style="width: 10%">
                                                    <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"><i data-feather="settings"></i></a>
                                                    <div class="dropdown-menu" aria-labelledby="">
                                                        <h5 class="dropdown-header">Actions</h5>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#detailData{{ $value->id }}">Detail</a>
                                                        <a class="dropdown-item"
                                                            href="{{ url('/customers/' . $value->code_cust . '/edit') }}">Edit</a>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                    </div>
                                            @endif
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
                                                                    <div class="col-md-4 font-weight-bold mb-5">
                                                                        <label>Customer Reference Image</label>
                                                                        <img width="100%" class="img-fluid shadow-lg"
                                                                            src="{{ asset('images/customers/' . $value->reference_image) }}"
                                                                            alt="Preview Image">
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-4">
                                                                                <label>Customer Code</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Customer Code" readonly
                                                                                    value="{{ $value->code_cust }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Customer Name</label>
                                                                                <input type="text" class="form-control "
                                                                                    placeholder="Customer Name" readonly
                                                                                    value="{{ $value->name_cust }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Customer ID Card
                                                                                    Number</label>
                                                                                <input type="text" class="form-control "
                                                                                    placeholder="Customer ID Card" readonly
                                                                                    value="{{ $value->id_card_number }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-6">
                                                                                <label>Customer Address</label>
                                                                                <textarea class="form-control" rows="3" readonly>{{ $value->address_cust }}</textarea>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Customer NPWP</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Customer NPWP"
                                                                                    value="{{ $value->npwp }}" readonly>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-4">
                                                                                <label>
                                                                                    Phone Customer</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->phone_cust }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>
                                                                                    Customer Email</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->email_cust }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>
                                                                                    Customer Category</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->category_name }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">

                                                                            <div class="form-group col-md-4">
                                                                                <label>Customer Area
                                                                                </label>
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    value="{{ $value->area_name }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Coordinate</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->coordinate }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Credit Limit</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->credit_limit }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-3">
                                                                                <label>Last Transaction</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="@if ($value->last_transaction == null) No Transaction @else {{ $value->last_transaction }} @endif">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Due Date</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->due_date }}">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Label</label>
                                                                                <br>
                                                                                @if ($value->label == 'Prospect')
                                                                                    <span
                                                                                        class="badge badge-pill badge-secondary text-white">
                                                                                        Prospect</span>
                                                                                @elseif($value->label == 'Customer')
                                                                                    <span
                                                                                        class="badge badge-pill badge-primary">
                                                                                        Customer</span>
                                                                                @else
                                                                                    <span
                                                                                        class="badge badge-pill badge-danger">Bad
                                                                                        Customer</span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Status</label>
                                                                                <br>
                                                                                @if ($value->status == 1)
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
                                                    <form method="post"
                                                        action="{{ url('customers/' . $value->code_cust) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('delete')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->code_cust }}</h5>
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
                                            <td>{{ $value->code_cust }}</td>
                                            <td>{{ $value->name_cust }}</td>
                                            <td>{{ $value->phone_cust }}</td>
                                            <td>{{ $value->category_name }}</td>
                                            <td>{{ $value->area_name }}</td>
                                            <td class="text-center">
                                                <h3><a href="https://maps.google.com/?q={{ $value->coordinate }}"
                                                        target="_blank"><i class="icon-map-alt"></i></a></h3>
                                            </td>
                                            <td>
                                                @if ($value->status == 1)
                                                    <div class="badge badge-success">Active</div>
                                                @else
                                                    <div class="badge badge-danger">Nonactive</div>
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
    @endpush
@endsection
