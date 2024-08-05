@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    
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
                    </div>
                    <div class="card-body">

                        {{-- ! create data --}}
                        <form class="form-label-left input_mask" method="post" action="{{ url('/product_sub_types') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label>
                                              Product  Sub Material</label>
                                            <select name="sub_material_id" required
                                                class="form-control materials {{ $errors->first('sub_material_id') ? ' is-invalid' : '' }}">
                                                <option value="" selected>-Choose Material Source-</option>
                                                @foreach ($sub_materials as $sub_material)
                                                    <option value="{{ $sub_material->id }}">
                                                        {{ $sub_material->nama_sub_material }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('sub_material_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold"> Product Sub Material Type</label>
                                            <input type="text"
                                                class="form-control {{ $errors->first('type_name') ? ' is-invalid' : '' }}"
                                                name="type_name" placeholder="Name Product Sub Type">
                                            @error('type_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="form-group col-md-12">-->
                                    <!--    <label class="font-weight-bold ">Code Product Sub-->
                                    <!--        Material</label>-->
                                    <!--    <input type="text"-->
                                    {{-- <!--        class="form-control text-uppercase {{ $errors->first('code_sub_type') ? ' is-invalid' : '' }}"--> --}}
                                    <!--        name="code_sub_type" placeholder="code of product sub material" max="4"-->
                                    {{-- <!--        required value="{{ old('code_sub_type') }}">--> --}}
                                    {{-- <!--    @error('code_sub_type') --}}

                                    {{-- <!--        <small class="text-danger">{{ $message }}.</small>--> --}}
                                    <!--
                                                            {{-- @enderror--> --}}
                                                                   </div>-->
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
                        {{-- ! end create data --}}

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
                            <table id="basic2" class="display expandable-table table table-striped table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">

                                        <th>#</th>
                                        <th>Product Sub Type</th>
                                        <th>Product Sub Material</th>
                                        <th></th>
                                        <!--<th>Code Sub Type</th>-->

                                    </tr>
                                </thead>
                                <tbody>

                                    {{-- ! read data --}}
                                    @foreach ($sub_types as $key => $value)
                                        <tr>

                                            <td class="text-end">{{ $loop->iteration }}</td>
                                            <td>{{ $value->type_name }}</td>
                                            <td>{{ $value->nama_sub_material }}</td>
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
                                            <!--<td class="text-uppercase">{{ $value->code_sub_type }}</td>-->
                                        </tr>
                                    @endforeach
                                    {{-- ! end read data --}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($sub_types as $key => $value)
        {{-- ! Modul Edit  --}}
        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('product_sub_types/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                {{ $value->type_name }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label>
                                            Sub Material Source</label>
                                        <select name="sub_material_id_edit" required
                                            class="form-control materials {{ $errors->first('sub_material_id_edit') ? ' is-invalid' : '' }}">
                                            <option value="" selected>-Choose
                                                Material
                                                Source-</option>
                                            @foreach ($sub_materials as $sub_material)
                                                <option value="{{ $sub_material->id }}"
                                                    @if ($sub_material->id == $value->sub_material_id) selected @endif>
                                                    {{ $sub_material->nama_sub_material }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sub_material_id_edit')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold">Name
                                            Sub Material Type</label>
                                        <input type="text"
                                            class="form-control {{ $errors->first('type_name_edit') ? ' is-invalid' : '' }}"
                                            name="type_name_edit" value="{{ $value->type_name }}"
                                            placeholder="Name Sub Material Type">
                                        @error('type_name_edit')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label class="font-weight-bold ">Code Product Sub-->
                                <!--        Material</label>-->
                                <!--    <input type="text"-->
                                {{-- <!--        class="form-control text-uppercase {{ $errors->first('code_sub_type_edit') ? ' is-invalid' : '' }}"--> --}}
                                <!--        name="code_sub_type_edit"-->
                                <!--        placeholder="code of product sub type"-->
                                <!--        max="3" required-->
                                {{-- <!--        value="{{ $value->code_sub_type }}">--> --}}
                                {{-- <!--    @error('code_sub_type_edit') --}}

                                <!--        <small-->
                                {{-- <!--            class="text-danger">{{ $message }}.</small>--> --}}
                                <!--
                                                                        {{-- @enderror--> --}}
                                                        </div>-->
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
        {{-- ! End Modal Edit  --}}
        {{-- ! Modul Delete  --}}
        <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('product_sub_types/' . $value->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                {{ $value->type_name }}</h5>
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
        {{-- ! End Modal Delete  --}}
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).on("click", ".modal-btn", function(event) {
                let modal_id = $(this).attr('data-bs-target');
                console.log(modal_id);

                $(modal_id).find(".materials").select2({
                    width: "100%",
                    dropdownParent: modal_id,
                });

            });
        </script>
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
                var t = $('#basic2').DataTable({
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0,
                    }, {
                        searchable: false,
                        orderable: false,
                        targets: 1,
                    }, ],
                    order: [
                        [1, 'asc']
                    ],
                });

                t.on('order.dt search.dt', function() {
                    let i = 1;

                    t.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();
            });
        </script>
    @endpush
@endsection
