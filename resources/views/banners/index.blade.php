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
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        {{-- ! create data --}}
                        <form class="form-label-left input_mask" method="post" action="{{ url('banners/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Tag Line</label>
                                            <input type="text"
                                                class="form-control text-capitalize {{ $errors->first('tag_line') ? ' is-invalid' : '' }}"
                                                name="tag_line" placeholder="Enter Tag Line ">
                                            @error('tag_line')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Sub Tag Line</label>
                                            <input type="text"
                                                class="form-control text-capitalize {{ $errors->first('tag_line_2') ? ' is-invalid' : '' }}"
                                                name="tag_line_2" placeholder="Enter Sub Tag Line ">
                                            @error('tag_line_2')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Image Banner</label>
                                            <input type="file"
                                                class="form-control text-capitalize {{ $errors->first('image') ? ' is-invalid' : '' }}"
                                                name="image">
                                            @error('image')
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
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%"></th>
                                        <th>#</th>
                                        <th>Tag Line</th>
                                        <th>Sub Tag Line</th>
                                        <th>Image</th>

                                    </tr>
                                </thead>
                                {{-- ! read data --}}
                                <tbody>
                                    @foreach ($banners as $key => $value)
                                        <tr>
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
                                            {{-- !Modul Edit UOM --}}
                                            <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('product_uoms/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="PATCH">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $value->satuan }}</h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label class="font-weight-bold ">Name
                                                                                Unit of Measurement</label>
                                                                            <input type="text"
                                                                                class="form-control text-capitalize {{ $errors->first('editSatuan') ? ' is-invalid' : '' }}"
                                                                                name="editSatuan"
                                                                                value="{{ $value->satuan }}"
                                                                                placeholder="Name Unit of Measurement">
                                                                            @error('editSatuan')
                                                                                <small
                                                                                    class="text-danger">{{ $message }}.</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-danger" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="reset"
                                                                    class="btn btn-warning">Reset</button>
                                                                <button class="btn btn-primary" type="submit">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- !End Modal Edit UOM --}}
                                            {{-- ! Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('product_uoms/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->satuan }}</h5>
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
                                            {{-- ! End Modal Delete UOM --}}
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->tag_line }}</td>
                                            <td>{{ $value->tag_line_2 }}</td>
                                            <td>
                                                <img src="{{ asset('/storage/banners/' . $value->image) }}"
                                                    alt="image" class="img-fluid" width="100px">
                                            </td>


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
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('form').submit(function(e) {
                    e.stopPropagation();
                });
            });
        </script>
    @endpush
@endsection
