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
                    </div>
                    <div class="card-body">
                        {{-- ! create data --}}
                        <form class="form-label-left input_mask" method="post" action="{{ url('/product_sub_materials') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label>
                                                Product Material</label>
                                            <select name="material_id" required
                                                class="form-control materials {{ $errors->first('material_id') ? ' is-invalid' : '' }}">
                                                <option value="" selected>-Choose Material Source-</option>
                                                @foreach ($materials as $material)
                                                    <option value="{{ $material->id }}">
                                                        {{ $material->nama_material }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('material_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}.
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold"> Product Sub Material</label>
                                            <input type="text"
                                                class="form-control {{ $errors->first('nama_sub_material') ? ' is-invalid' : '' }}"
                                                name="nama_sub_material" placeholder="Name Product Sub Material"
                                                value="{{ old('nama_sub_material') }}">
                                            @error('nama_sub_material')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div class="form-group col-md-12">-->
                                    <!--    <label class="font-weight-bold ">Code Product Sub-->
                                    <!--        Material</label>-->
                                    <!--    <input type="text"-->
                                    <!--        class="form-control text-uppercase {{ $errors->first('code_sub_material') ? ' is-invalid' : '' }}"-->
                                    <!--        name="code_sub_material" placeholder="code of product sub materials"-->
                                    <!--        max="3" required value="{{ old('code_sub_material') }}">-->
                                    <!--    @error('code_sub_material')
        -->
                                        <!--        <small class="text-danger">{{ $message }}.</small>-->
                                        <!--
    @enderror-->
                                    <!--</div>-->
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
                                        <th>Sub Material</th>
                                        <th>Product Material </th>
                                        <th></th>
                                        <!--<th>Code Sub Material</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ! read data --}}
                                    @foreach ($data as $key => $value)
                                        <tr>


                                            <td class="text-end">{{ $loop->iteration }}</td>
                                            <td>{{ $value->nama_sub_material }}</td>
                                            <td>{{ $value->nama_material }}</td>
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
                                            <!--<td class="text-uppercase">{{ $value->code_sub_material }}</td>-->
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
    @foreach ($data as $key => $value)
        {{-- ! Modul Edit --}}
        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('product_sub_materials/' . $value->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                {{ $value->nama_material }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label>
                                            Material Source</label>
                                        <select name="material_id_edit" required
                                            class="form-control materials {{ $errors->first('material_id_edit') ? ' is-invalid' : '' }}">
                                            <option value="" selected>-Choose
                                                Material
                                                Source-</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}"
                                                    @if ($material->id == $value->material_id) selected @endif>
                                                    {{ $material->nama_material }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('material_id_edit')
                                            <div class="invalid-feedback">
                                                {{ $message }}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold">Name
                                            Product Sub Material</label>
                                        <input type="text"
                                            class="form-control{{ $errors->first('editnama_submaterial') ? ' is-invalid' : '' }}"
                                            name="editnama_submaterial" value="{{ $value->nama_sub_material }}"
                                            placeholder="Name Unit of Measurement">
                                        @error('editnama_submaterial')
                                            <small class="text-danger">
                                                {{ $message }}.</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label class="font-weight-bold ">Code Product Sub-->
                                <!--        Material</label>-->
                                <!--    <input type="text"-->
                                {{-- <!--        class="form-control text-uppercase {{ $errors->first('editcode_sub_material') ? ' is-invalid' : '' }}"--> --}}
                                <!--        name="editcode_sub_material"-->
                                <!--        placeholder="code of product sub materials"-->
                                <!--        max="3" required-->
                                {{-- <!--        value="{{ $value->editcode_sub_material }}">--> --}}
                                {{-- <!--    @error('editcode_sub_material') --}}

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
                <form method="post" action="{{ url('product_sub_materials/' . $value->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                {{ $value->nama_material }}</h5>
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
        {{-- ! End Modal Delete --}}
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).on("click", ".modal-btn", function(event) {
                let modal_id = $(this).attr('data-bs-target');
                // console.log(modal_id);

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
