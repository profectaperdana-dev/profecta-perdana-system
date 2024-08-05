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
                        <div class="row justify-content-end">
                            <button class="col-1 btn btn-primary btn-sm" id="addfields">+</button>
                        </div>
                        <div class="container-fluid">
                            <form class="form-label-left input_mask" method="post"
                                action="{{ url('/national_day/store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="nationalField">
                                            <div class="form-group row bg-primary mt-2 p-2 mx-auto mb-3">
                                                <div class="col-12 col-lg-6">
                                                    <label class="font-weight-bold">Date</label>
                                                    <input type="date" value="{{ old('date') }}"
                                                        class="form-control {{ $errors->first('date') ? ' is-invalid' : '' }}"
                                                        name="nationalFields[0][date]" placeholder="Choose Date" required>
                                                    @error('date')
                                                        <small class="text-danger">{{ $message }}.</small>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <label class="font-weight-bold">Remark</label>
                                                    <input type="text" name="nationalFields[0][remark]" id=""
                                                        class="form-control">
                                                </div>
                                            </div>
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
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All National Day at {{ date('Y') }}</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($days as $key => $value)
                                        <tr>

                                            <td class="text-end">{{ $key + 1 }}</td>
                                            <td>{{ date('d F Y', strtotime($value->date)) }}</td>
                                            <td>{{ $value->remark }}</td>
                                            <td style="width: 5%">
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
    @foreach ($days as $key => $value)
        {{-- Modul Edit UOM --}}
        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form method="post" action="{{ url('national_day/' . $value->id . '/update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                of
                                {{ $value->remark }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="font-weight-bold">Day</label>
                                            <select name="day" id="" class="form-control uoms">
                                                @for ($k = 1; $k <= 31; $k++)
                                                    <option value="{{ sprintf('%02d', $k) }}"
                                                        @if (date('d', strtotime($value->date)) == sprintf('%02d', $k)) selected @endif>
                                                        {{ sprintf('%02d', $k) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="font-weight-bold">Month</label>
                                            <select name="month" id="" class="form-control uoms">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}"
                                                        @if (date('F', strtotime($value->date)) == date('F', strtotime($i . '/01/01'))) selected @endif>
                                                        {{ date('F', strtotime($i . '/01/01')) }}
                                                    </option>
                                                @endfor
                                            </select>

                                        </div>
                                        <div class="col-lg-4">
                                            <label class="font-weight-bold">Year</label>
                                            <input type="text" value="{{ date('Y', strtotime($value->date)) }}"
                                                class="form-control" name="year"
                                                @if (date('Y', strtotime($value->date)) == date('Y')) readonly @endif>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Remark</label>
                                            <input type="text" name="remark" value="{{ $value->remark }}"
                                                id="" class="form-control" required>
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
        {{-- End Modal Edit --}}
        {{-- Modul Delete --}}
        <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('national_day/' . $value->id . '/delete') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                of
                                {{ $value->remark }}</h5>
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
        {{-- End Modal Delete --}}
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

                $(".modal-btn").on("click", function() {
                    let modal_id = $(this).attr('data-bs-target');
                    $(modal_id).find(".uoms").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });
                });

                let i = 0;

                $("#addfields").on("click", function() {
                    console.log('clicker');
                    ++i;
                    let form =
                        `<div class="form-group row bg-primary mt-2 p-2 mx-auto mb-3">
                                <div class="col-12 col-lg-4">
                                    <label class="font-weight-bold">Date</label>
                                    <input type="date"
                                        class="form-control"
                                        name="nationalFields[${i}][date]" placeholder="Choose Date" required>
                                    
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label class="font-weight-bold">Remark</label>
                                    <input type="text" name="nationalFields[${i}][remark]" id=""
                                        class="form-control">
                                </div>
                                <div class="col-12 col-lg-2">
                                    <label class="font-weight-bold">&nbsp;</label>
                                    <button class="btn btn-danger text-white btn-sm remfields form-control">-</button>
                                </div>
                            </div>`;

                    $("#nationalField").append(form);

                });
                $(document).on("click", ".remfields", function() {
                    $(this).parents(".form-group").remove();
                });


            })
        </script>
    @endpush
@endsection
