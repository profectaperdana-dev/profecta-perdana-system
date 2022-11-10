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
                        <a class="btn btn-primary" href="{{ url('/employee/create') }}">
                            + Create Employee
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $key => $value)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#detailData{{ $value->id }}">Detail</a>
                                                    @canany(['level1', 'level2'])
                                                        <a class="dropdown-item"
                                                            href="{{ url('/employee/' . $value->id . '/edit') }}">Edit</a>
                                                    @endcanany
                                                    @can('level1')
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                    @endcan
                                                </div>
                                            </td>
                                            {{-- Modul Detail --}}
                                            <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detail Data
                                                                {{ $value->name }}</h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="col-md-4 font-weight-bold mb-5">
                                                                        <label>Employee's Photo</label>
                                                                        <img width="100%" class="img-fluid shadow-lg"
                                                                            src="{{ asset('images/employees/' . $value->photo) }}"
                                                                            alt="Preview Image">
                                                                    </div>
                                                                    <input class="id" type="hidden"
                                                                        value="{{ $value->id }}" readonly>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-4">
                                                                                <label>Employee ID</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Customer Code" readonly
                                                                                    value="{{ $value->employee_id }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Name</label>
                                                                                <input type="text" class="form-control "
                                                                                    placeholder="Employee Name" readonly
                                                                                    value="{{ $value->name }}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Gender</label>
                                                                                <input type="text" class="form-control "
                                                                                    placeholder="Customer ID Card" readonly
                                                                                    @if ($value->gender == 0) value="Woman"
                                                                                    @else
                                                                                    value="Man" @endif>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-4">
                                                                                <label>Phone Number</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="" id=""
                                                                                    value="{{ $value->phone }}" readonly>
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Emergency Number</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="" id=""
                                                                                    value="{{ $value->emergency_phone }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>Email</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="" id=""
                                                                                    value="{{ $value->email }}" readonly>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-6">
                                                                                <label>Birth Place</label>
                                                                                <input type="text" class="form-control"
                                                                                    value="{{ $value->birth_place }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Birth Date</label>
                                                                                <input type="text" class="form-control"
                                                                                    value="{{ date('d-M-Y', strtotime($value->birth_date)) }}"
                                                                                    readonly>
                                                                            </div>

                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-12">
                                                                                <label>Address</label>
                                                                                <textarea class="form-control" rows="3" readonly>{{ $value->address . ', ' . $value->sub_district . ', ' . $value->district . ', ' . $value->province }}</textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-6">
                                                                                <label>Study Degree
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->last_education_first }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Institution Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->school_name_first }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Study Period</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->from_first . ' - ' . $value->to_first }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-6">
                                                                                <label>Study Degree
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->last_education_sec }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Institution Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->school_name_sec }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Study Period</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->from_sec . ' - ' . $value->to_sec }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-3">
                                                                                <label>
                                                                                    Mother's Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->mom_name }}">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>
                                                                                    Mother's Phone Number</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->mom_phone }}">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Father's Name
                                                                                </label>
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    value="{{ $value->father_name }}">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Father's Phone Number
                                                                                </label>
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    value="{{ $value->father_phone }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-6">
                                                                                <label>Start Work Date</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ date('d-M-Y', strtotime($value->work_date)) }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Salary</label>
                                                                                <input type="text"
                                                                                    class="form-control credit-limit"
                                                                                    readonly
                                                                                    value="{{ number_format($value->salary) }}">
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
                                            {{-- End Modal Detail --}}

                                            @can('level1')
                                                {{-- Modul Delete UOM --}}
                                                <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" action="{{ url('employee/' . $value->id) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('delete')
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                        {{ $value->name }}</h5>
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
                                            @endcan

                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->employee_id }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>
                                                @if ($value->gender == 0)
                                                    Woman
                                                @else
                                                    Man
                                                @endif
                                            </td>
                                            <td>{{ $value->phone }}</td>
                                            <td>{{ $value->email }}</td>

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
                let csrf = $('meta[name="csrf-token"]').attr("content");

            });
        </script>
    @endpush
@endsection
