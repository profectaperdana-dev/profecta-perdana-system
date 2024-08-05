@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">Master Employee</h3>
                   
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

                            <table id="basics" class="table table-sm table-striped" style="width:100%">

                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>Name</th>
                                        <th>Employee ID</th>
                                        <th>Status</th>
                                        <th>Job</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $key => $value)
                                        <tr>



                                            <td class="text-end">{{ $key + 1 }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->employee_id }}</td>
                                            <td>
                                                @if ($value->status == 1)
                                                    <span class="badge rounded-pill bg-success">Active</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger">Nonactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $value->job }}</td>
                                            <td>{{ $value->phone }}</td>
                                            <td>{{ $value->email }}</td>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#detailData{{ $value->id }}">Detail</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url('/employee/' . $value->id . '/edit') }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
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
    @foreach ($employees as $key => $value)
        {{-- Modul Detail --}}
        <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Data
                            {{ $value->name }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4 font-weight-bold mb-5">
                                    <label>Employee's Photo</label>
                                    @if ($value->photo == 'blank')
                                        <img width="100%" class="img-fluid shadow-lg rounded"
                                            src="{{ url('/public/images/employees/default-placeholder.png') }}"
                                            alt="Preview Image">
                                    @else
                                        <img width="100%" class="img-fluid shadow-lg rounded"
                                            src="{{ url('/public/images/employees/' . $value->photo) }}"
                                            alt="Preview Image">
                                    @endif

                                </div>
                                <input class="id" type="hidden" value="{{ $value->id }}" readonly>
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header pb-0">
                                            <h5>Personal Data</h5>
                                            <hr class="bg-primary">
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row font-weight-bold">
                                                <div class="form-group col-lg-4">
                                                    <label>Employee ID</label>
                                                    <input type="text" class="form-control" placeholder="Customer Code"
                                                        readonly value="{{ $value->employee_id }}">
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control " placeholder="Employee Name"
                                                        readonly value="{{ $value->name }}">
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label>Gender</label>
                                                    <input type="text" class="form-control "
                                                        placeholder="Customer ID Card" readonly
                                                        @if ($value->gender == 0) value="Female"
                                                        @else
                                                        value="Male" @endif>
                                                </div>
                                            </div>

                                            <div class="form-group row font-weight-bold">
                                                <div class="form-group col-lg-12">
                                                    <label>Phone Number</label>
                                                    <input type="text" class="form-control" name=""
                                                        id="" value="{{ $value->phone }}" readonly>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Identity Card Number</label>
                                                    <input type="text" class="form-control" name=""
                                                        id="" value="{{ $value->nik }}" readonly>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Emergency Contact 1</label>
                                                    <div class="input-group">
                                                        <input type="text" aria-label="First name"
                                                            class="form-control" value="{{ $value->emergency_phone }}"
                                                            readonly>
                                                        <span class="input-group-text">|</span>
                                                        <input type="text" aria-label="Last name" class="form-control"
                                                            value="{{ $value->emergency_relation }}" readonly>
                                                    </div>

                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>Emergency Contact 2</label>
                                                    <div class="input-group">
                                                        <input type="text" aria-label="First name"
                                                            class="form-control" value="{{ $value->emergency_phone_ }}"
                                                            readonly>
                                                        <span class="input-group-text">|</span>
                                                        <input type="text" aria-label="Last name" class="form-control"
                                                            value="{{ $value->emergency_relation_ }}" readonly>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="form-group row font-weight-bold">
                                                <div class="form-group col-lg-4">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" name=""
                                                        id="" value="{{ $value->email }}" readonly>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label>Birth Place</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $value->birth_place }}" readonly>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label>Birth Date</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ date('d-M-Y', strtotime($value->birth_date)) }}"
                                                        readonly>
                                                </div>

                                            </div>

                                            <div class="form-group row font-weight-bold">
                                                <div class="form-group col-lg-12">
                                                    <label>Address</label>
                                                    <textarea class="form-control" rows="3" readonly>{{ $value->address . ', ' . $value->sub_district . ', ' . $value->district . ', ' . $value->province }}</textarea>
                                                </div>
                                            </div>
                                            @if ($value->address_identity != null)
                                                <div class="form-group row font-weight-bold">
                                                    <div class="form-group col-lg-12">
                                                        <label>Current Address</label>
                                                        <textarea class="form-control" rows="3" readonly>{{ $value->address_identity . ', ' . $value->sub_district . ', ' . $value->district . ', ' . $value->province }}</textarea>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header pb-0">
                                            <h5>Education Data</h5>
                                            <hr class="bg-primary">
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row font-weight-bold">
                                                <div class="form-group col-lg-3">
                                                    <label>Study Degree
                                                    </label>
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $value->last_edu_first }}">
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label>
                                                        Institution Name</label>
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $value->school_name_first }}">
                                                </div>
                                                <div class="form-group col-lg-5">
                                                    <label>
                                                        Study Period</label>
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $value->from_first . ' Until ' . $value->to_first }}">
                                                </div>
                                            </div>
                                            <div class="form-group row font-weight-bold">
                                                <div class="form-group col-lg-3">
                                                    <label>Study Degree
                                                    </label>
                                                    <input type="text" class="form-control" readonly
                                                        @if ($value->last_edu_sec == null) value="-"> @else value="{{ $value->last_edu_sec }}"> @endif
                                                        </div>
                                                    <div class="form-group col-lg-4">
                                                        <label>
                                                            Institution Name</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $value->school_name_sec }}">
                                                    </div>
                                                    <div class="form-group col-lg-5">
                                                        <label>
                                                            Study Period</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $value->from_sec . ' Until ' . $value->to_sec }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header pb-0">
                                                <h5>Family Data</h5>
                                                <hr class="bg-primary">
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group row font-weight-bold">
                                                    <div class="form-group col-lg-6">
                                                        <label>Father's Name
                                                        </label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $value->father_name }}">
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label>Father's Phone Number
                                                        </label>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $value->father_phone }}">
                                                    </div>
                                                </div>

                                                <div class="form-group row font-weight-bold">
                                                    <div class="form-group col-lg-6">
                                                        <label>
                                                            Mother's Name</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Serial Number" readonly
                                                            value="{{ $value->mom_name }}">
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label>
                                                            Mother's Phone
                                                            Number</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Serial Number" readonly
                                                            value="{{ $value->mom_phone }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header pb-0">
                                                <h5>Work Data</h5>
                                                <hr class="bg-primary">
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group row font-weight-bold">
                                                    <div class="form-group col-lg-4">
                                                        <label>Start Work Date</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ date('d-M-Y', strtotime($value->work_date)) }}">
                                                    </div>
                                                    <div class="form-group col-lg-4">
                                                        <label>Job</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $value->job }}">
                                                    </div>
                                                    <div class="form-group col-lg-4">
                                                        <label>Salary</label>
                                                        <input type="text" class="form-control credit-limit" readonly
                                                            value="{{ number_format($value->salary, 0, ',', '.') }}">
                                                    </div>
                                                     <div class="form-group col-lg-4">
                                                        <label>Leave Total</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $value->vacation }}">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Modal Detail --}}
        </div>

        @can('level1')
            {{-- Modul Delete UOM --}}
            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ url('employee/' . $value->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('delete')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete
                                    Data
                                    {{ $value->name }}</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <h5>Are you sure delete this data ?</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Yes,
                                    delete
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Modal Delete UOM --}}
        @endcan
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
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
                $('#basics').dataTable();

                let csrf = $('meta[name="csrf-token"]').attr("content");
                // $('form').submit(function() {
                //     $(this).find('button[type="submit"]').prop('disabled', true);
                // });
            });
        </script>
    @endpush
@endsection
