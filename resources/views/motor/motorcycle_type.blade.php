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
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                        <div class="row justify-content-end">
                            <button class="col-2 btn btn-primary btn-sm" id="addTypes">+</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/motorcycle_type/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12" id="formdynamic">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Brand Motorcycle</label>
                                            <select class="form-control uoms" name="brand_id" id="brand_id" required>
                                                <option value="">-- Select Brand --</option>
                                                @foreach ($brand as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name_brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group bg-primary p-2 row">

                                        <div class="form-group col-md-12">
                                            <label>Motorcycle Type</label>
                                            <input type="text" name="typeFields[0][type]" id="type"
                                                class="form-control @error('typeFields[0][type]') is-invalid @enderror"
                                                placeholder="Enter type" required>
                                            @error('typeFields[0][type]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Accu Type</label>
                                            <input type="text" name="typeFields[0][accu_type]" id="accu_type"
                                                class="form-control @error('typeFields[0][accu_type]') is-invalid @enderror"
                                                placeholder="Enter Battery Type" required>
                                            @error('typeFields[0][accu_type]')
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
            <div class="col-sm-7">
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
                                        <th> Brand Name</th>
                                        <th>Type</th>
                                        <th>Battery Type</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>


                                            <td class="text-end">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $value->brandBy->name_brand }}</td>
                                            <td class="text-center">{{ $value->name_type }}</td>
                                            <td class="text-center">{{ $value->accu_type }}</td>
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
                <form method="post" action="{{ url('motorcycle_type/update/' . $value->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                {{ $value->name_type }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold ">Name
                                            Brand</label>
                                        <select class="form-control uoms" name="brands_id">
                                            <option value="">-- Select Brand --
                                            </option>
                                            @foreach ($brand as $val)
                                                <option value="{{ $val->id }}"
                                                    @if ($val->id == $value->id_motor_brand) selected @endif>
                                                    {{ $val->name_brand }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Motorcycle Type</label>
                                    <input type="text" name="types" value="{{ $value->name_type }}"
                                        class="form-control @error('type') is-invalid @enderror" placeholder="Enter type"
                                        required>
                                    @error('type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Battery Type</label>
                                    <input type="text" name="accu_types" value="{{ $value->accu_type }}"
                                        class="form-control @error('accu_types') is-invalid @enderror"
                                        placeholder="Enter Battery Type" required>
                                    @error('accu_types')
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
                <form method="post" action="{{ url('motorcycle_type/delete/' . $value->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                {{ $value->name_type }}</h5>
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
                        '<div class="form-group bg-primary p-2 row"> <div class="form-group col-10">' +
                        '<label> Motorcycle Type  </label> <input type="text" name="typeFields[' +
                        y +
                        '][type]" id="type"' +
                        'class="form-control" placeholder="Enter Type" required>' +
                        '</div>' +
                        '<div class="form-group col-2">' +
                        '<label for="">&nbsp;</label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remType text-center" style="border:none; background-color:red">X</a></div>' +
                        '<div class="form-group col-md-12">' +
                        '<label>Accu Type</label>' +
                        '<input type="text" name="typeFields[' + y + '][accu_type]" id="accu_type"' +
                        'class="form-control"' +
                        'placeholder="Enter Battery Type" required>' +
                        '</div>' +
                        '</div>';

                    $("#formdynamic").append(form);
                });
                $(document).on("click", ".remType", function() {
                    $(this).parents(".form-group").remove();
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
