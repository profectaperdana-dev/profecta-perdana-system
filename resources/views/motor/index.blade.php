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
                        <form class="form-label-left input_mask" method="post" action="{{ url('/motorcycle') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Brand Motorcycle</label>
                                            <input type="text"
                                                class="form-control text-capitalize {{ $errors->first('name_brands') ? ' is-invalid' : '' }}"
                                                name="name_brands" placeholder="Brand Motorcycle">
                                            @error('name_brands')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
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
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr class="text-center">

                                        <th>#</th>
                                        <th> Brand Name</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>

                                            <td class="text-end">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $value->name_brand }}</td>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
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
                <form method="post" action="{{ url('motorcycle/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                {{ $value->name_brand }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold ">Name
                                            Brand</label>
                                        <input type="text"
                                            class="form-control text-capitalize {{ $errors->first('edit_brands') ? ' is-invalid' : '' }}"
                                            name="edit_brands" value="{{ $value->name_brand }}"
                                            placeholder="Name Unit of Measurement">
                                        @error('edit_brands')
                                            <small class="text-danger">{{ $message }}.</small>
                                        @enderror
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
        {{-- End Modal Edit UOM --}}
        {{-- Modul Delete UOM --}}
        <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('motorcycle/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                {{ $value->name_brand }}</h5>
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
            });
        </script>
    @endpush
@endsection
