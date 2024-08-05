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
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                        <!--<div class="row justify-content-end">-->
                        <!--    <button class="col-2 btn btn-primary btn-sm" id="addTypes">+</button>-->
                        <!--</div>-->
                    </div>
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/cars_type/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12" id="formdynamic">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Car Brand</label>
                                            <select class="form-control uoms" name="id_car_brand" id="id_car_brand"
                                                required>
                                                <option value="">-- Select Brand --</option>
                                                @foreach ($brand as $item)
                                                    <option value="{{ $item->id }}">{{ $item->car_brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="form-group col-md-12">
                                            <label>Cars Type</label>
                                            <input type="text" name="typeFields[0][car_type]" id="type"
                                                class="form-control @error('typeFields[0][car_type]') is-invalid @enderror"
                                                placeholder="Enter type" required>
                                            @error('typeFields[0][car_type]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="form-group col-md-12">
                                            <label>Battery Type 1</label>
                                            <input type="text" name="typeFields[0][accu_type]" id="accu_type"
                                                class="form-control @error('typeFields[0][accu_type]') is-invalid @enderror"
                                                placeholder="Enter battery type 1" required>
                                            @error('typeFields[0][accu_type]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                     <div class="form-group row">

                                        <div class="form-group col-md-12">
                                            <label>Battery Type 2</label>
                                            <input type="text" name="typeFields[0][second_accu_type]" id="second_accu_type"
                                                class="form-control @error('typeFields[0][second_accu_type]') is-invalid @enderror"
                                                placeholder="Enter battery type 2" required>
                                            @error('typeFields[0][second_accu_type]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="form-group col-md-12">
                                            <label>Tyre Type</label>
                                            <input type="text" name="typeFields[0][tire_type]" id="tire_type"
                                                class="form-control @error('typeFields[0][tire_type]') is-invalid @enderror"
                                                placeholder="Enter tyre type" required>
                                            @error('typeFields[0][tire_type]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>



                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="reset" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabel" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr class="text-center">

                                        <th>#</th>
                                        <th>Brand Name</th>
                                        <th>Type</th>
                                        <th>Battery Type 1</th>
                                        <th>Battery Type 2</th>
                                        <th>Tyre Type</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>


                                            <td class="text-end">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $value->brandBy->car_brand }}</td>
                                            <td class="text-center">{{ $value->car_type }}</td>
                                            <td class="text-center">{{ $value->accu_type }}</td>
                                            <td class="text-center">{{ $value->second_accu_type ? $value->second_accu_type : '-' }}</td>
                                            <td class="text-center">{{ $value->tire_type ? $value->tire_type : '-' }}</td>
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
    @foreach ($data as $key => $value)
        {{-- Modul Edit UOM --}}
        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('cars_type/update_type/' . $value->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                {{ $value->car_type }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold ">Car
                                            Brand</label>
                                        <select class="form-control uoms" name="edit_brands_id">
                                            <option value="">-- Select Brand --
                                            </option>
                                            @foreach ($brand as $val)
                                                <option value="{{ $val->id }}"
                                                    @if ($val->id == $value->id_car_brand) selected @endif>
                                                    {{ $val->car_brand }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Cars Type</label>
                                    <input type="text" name="edit_car_type" value="{{ $value->car_type }}"
                                        class="form-control @error('edit_car_type') is-invalid @enderror"
                                        placeholder="Enter car type" required>
                                    @error('edit_car_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Battery Type 1</label>
                                    <input type="text" name="edit_accu_type" value="{{ $value->accu_type }}"
                                        class="form-control @error('edit_accu_type') is-invalid @enderror"
                                        placeholder="Enter Battery type 1" required>
                                    @error('edit_accu_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Battery Type 2</label>
                                    <input type="text" name="edit_second_accu_type" value="{{ $value->second_accu_type }}"
                                        class="form-control @error('edit_second_accu_type') is-invalid @enderror"
                                        placeholder="Enter Battery type 2" required>
                                    @error('edit_accu_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Tyre Type</label>
                                    <input type="text" name="edit_tire_type" value="{{ $value->tire_type }}"
                                        class="form-control @error('edit_tire_type') is-invalid @enderror"
                                        placeholder="Enter tyre type" required>
                                    @error('edit_tire_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                <form method="post" action="{{ url('cars_type/delete/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                {{ $value->car_type }}</h5>
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

                $(document).on("click", ".modal-btn", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    $(modal_id).find(".uoms").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });

                });

                let y = 0;

                $("#addTypes").on("click", function() {
                    ++y;
                    let form =
                        '<div class="form-group row"> <div class="form-group col-md-12">' +
                        '<label>Cars Type</label> <input type="text" name="typeFields[' +
                        y +
                        '][car_type]" id="type"' +
                        'class="form-control" placeholder="Enter Type" required>' +
                        '</div>' +
                        '<div class="form-group col-10">' +
                        '<label>Battery Type</label> <input type="text" name="typeFields[' +
                        y +
                        '][accu_type]" id="accu_type"' +
                        'class="form-control" placeholder="Enter Type" required>' +
                        '</div>  <div class="form-group col-2">' +
                        '<label for="">&nbsp;</label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remType text-center" style="border:none; background-color:red">X</a></div></div>';

                    $("#formdynamic").append(form);
                });
                $(document).on("click", ".remType", function() {
                    $(this).closest(".row").remove();
                });

                $('#tabel').dataTable({
                    rowsGroup: [0, 1],

                });

                //select2
                $('#brand_id').select2({
                    width: '100 %'
                });
            });
        </script>
    @endpush
@endsection
