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
                        <p class="card-title">Customers Table</p>
                        <div>
                            <button class="btn btn-md btn-success" data-toggle="modal" data-target="#addModal">+ Add
                                Customer</button>
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
                                                                <a class="dropdown-item" data-toggle="modal"
                                                                    data-target="#editModal{{ $customer->id }}">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal"
                                                                    data-target="#delModal{{ $customer->id }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $customer->kode_cust }}</td>
                                                    <td>{{ $customer->nama_cust }}</td>
                                                    <td>{{ $customer->no_telepon_cust }}</td>
                                                    <td>{{ $customer->category_name }}</td>
                                                    <td>{{ $customer->area_name }}</td>
                                                    <td>{{ $customer->coordinate }}</td>
                                                    <td>
                                                        @if ($customer->status == 1)
                                                            <div class="badge badge-success">Aktif</div>
                                                        @else
                                                            <div class="badge badge-danger">Tidak Aktif</div>
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
