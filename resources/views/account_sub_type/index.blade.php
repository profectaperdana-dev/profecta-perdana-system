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
                        <div class="row justify-content-end">
                            <button class="col-2 btn btn-primary btn-sm" id="addTypes">+</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/account_sub_type') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12" id="formdynamic">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold">Account</label>
                                            <select class="form-control uoms" name="account_sub_id" required>
                                                <option value="">-- Select Account --</option>
                                                @foreach ($accountSub as $item)
                                                    <option value="{{ $item->id }}">
                                                        ({{ $item->code }})
                                                        {{ $item->account->name }} -
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="form-group col-md-4">
                                            <label>Code Type</label>
                                            <input type="text" name="subFields[0][code]" id="code" min="0"
                                                class="form-control @error('subFields[0][code]') is-invalid @enderror"
                                                placeholder="Enter Account Code" required>
                                            @error('subFields[0][code]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Name Type</label>
                                            <input type="text" name="subFields[0][name]" id="name"
                                                class="form-control text-capitalize @error('subFields[0][name]') is-invalid @enderror"
                                                placeholder="Enter Account Name" required>
                                            @error('subFields[0][name]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Cost</label>
                                            <input type="number" class="form-control text-capitalize" min="0"
                                                name="subFields[0][biaya]" placeholder="Enter Cost">
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
                                    <tr>
                                        <th style="width: 10%"></th>
                                        {{-- <th>#</th> --}}
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
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
                                            {{-- Modul Edit UOM --}}
                                            <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post"
                                                        action="{{ url('account_sub_type/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @method('patch')
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $value->name }}</h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label class="font-weight-bold ">Account
                                                                            </label>
                                                                            <select class="form-control uoms"
                                                                                name="account_ids">
                                                                                <option value="">-- Select Account --
                                                                                </option>
                                                                                @foreach ($accountSub as $val)
                                                                                    <option value="{{ $val->id }}"
                                                                                        @if ($val->id == $value->account_sub_id) selected @endif>
                                                                                        ({{ $val->code }})
                                                                                        {{ $val->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label>Code Sub</label>
                                                                        <input type="text" name="codes"
                                                                            min="0" value="{{ $value->code }}"
                                                                            class="form-control @error('codes') is-invalid @enderror"
                                                                            placeholder="Enter Account Code" required>
                                                                        @error('codes')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label>Name Sub</label>
                                                                        <input type="text" name="names"
                                                                            value="{{ $value->name }}"
                                                                            class="form-control text-capitalize @error('names') is-invalid @enderror"
                                                                            placeholder="Enter Account Name" required>
                                                                        @error('names')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label>Cost</label>
                                                                        <input type="number" value="{{ $value->biaya }}"
                                                                            class="form-control text-capitalize"
                                                                            min="0" name="biayas"
                                                                            placeholder="Enter Cost">
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
                                            {{-- End Modal Edit UOM --}}
                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post"
                                                        action="{{ url('account_sub_type/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
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
                                            {{-- <td>{{ $key + 1 }}</td> --}}
                                            <td>{{ $value->code }}
                                            </td>

                                            <td>
                                                {{ $value->accountSub->account->name }} - {{ $value->accountSub->name }} -
                                                {{ $value->name }}
                                            </td>
                                            <td>@currency($value->cost) </td>


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
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
                let y = 0;

                $("#addTypes").on("click", function() {
                    ++y;
                    let form =
                        '<div class="form-group row"> <div class="form-group col-4">' +
                        '<label> Code Sub  </label> <input type="text"  min="0" name="subFields[' +
                        y +
                        '][code]" id="type"' +
                        'class="form-control" placeholder="Enter Account Code" required>' +
                        '</div>' +
                        '<div class="form-group col-6">' +
                        '<label> Name Sub </label> <input type="text" name="subFields[' +
                        y +
                        '][name]" id="type"' +
                        'class="form-control text-capitalize" placeholder="Enter Account Name" required>' +
                        '</div>' +
                        '<div class="form-group col-2">' +
                        '<label for="">&nbsp;</label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remType text-center" style="border:none; background-color:red">X</a></div>' +
                        '<div class="form-group col-md-12">' +
                        '<label>Cost</label>' +
                        '<input type="number"  min="0" class="form-control text-capitalize" name="subFields[' +
                        y +
                        '][biaya]" placeholder="Enter Cost">' +
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
