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
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/document_renewal') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>Document Name</label>
                                    <input type="text" name="name" id="" class="form-control"
                                        placeholder="Enter Document Name" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Remark</label>
                                    <input type="text" name="remark" id="" class="form-control"
                                        placeholder="Enter Remark" required>
                                    @error('remark')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Last Renewal Date</label>
                                    <input type="date" name="last_updated"
                                        class="form-control @error('last_updated') is-invalid @enderror" required>
                                    @error('last_updated')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Renewal Period (Month)</label>
                                    <input type="number" name="update_period"
                                        class="form-control @error('update_period') is-invalid @enderror"
                                        placeholder="Enter Renewal Period" required>
                                    @error('update_period')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                            <table id="basic-2" class="table table-striped display expandable-table text-capitalize"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 10%"></th>
                                        <th>#</th>
                                        <th>Document Name</th>
                                        <th>Remark</th>
                                        <th>Last Renewal Date</th>
                                        <th>Renewal Period (Month)</th>
                                        <th>Renewal Deadline</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $document)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#renewData{{ $document->id }}">Renew</a>
                                                    @canany(['level1', 'level2'])
                                                        <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#changeData{{ $document->id }}">Edit</a>
                                                    @endcanany
                                                    @can('level1')
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#deleteData{{ $document->id }}">Delete</a>
                                                    @endcan
                                                </div>
                                            </td>
                                            {{-- Modul Edit Discount --}}
                                            <div class="modal fade" id="changeData{{ $document->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post"
                                                        action="{{ url('document_renewal/' . $document->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $document->name }}
                                                                </h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-12">
                                                                            <label>Document Name</label>
                                                                            <input class="form-control" type="text"
                                                                                name="name_edit" id=""
                                                                                value="{{ $document->name }}" required>
                                                                            @error('name_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-12">
                                                                            <label>Remark</label>
                                                                            <input class="form-control" type="text"
                                                                                name="remark_edit" id=""
                                                                                value="{{ $document->remark }}" required>
                                                                            @error('remark_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-12">
                                                                            <label>Last Renewal Date</label>
                                                                            <input class="form-control" type="date"
                                                                                name="last_updated_edit" id=""
                                                                                value="{{ $document->last_updated }}"
                                                                                required>
                                                                            @error('last_updated_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-12">
                                                                            <label>Renewal Period (Month)</label>
                                                                            <input class="form-control" type="number"
                                                                                name="update_period_edit" id=""
                                                                                value="{{ $document->update_period }}"
                                                                                required>
                                                                            @error('update_period_edit')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
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
                                            {{-- End Modal Edit discount --}}

                                            {{-- Modul Delete discount --}}
                                            <div class="modal fade" id="deleteData{{ $document->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post"
                                                        action="{{ url('document_renewal/' . $document->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('delete')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $document->name }}
                                                                </h5>
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

                                            {{-- Modul renew --}}
                                            <div class="modal fade" id="renewData{{ $document->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post"
                                                        action="{{ url('document_renewal/' . $document->id . '/renew') }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Renew
                                                                    {{ $document->name }}
                                                                </h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <h5>Are you sure to renew this document?</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-danger" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button class="btn btn-primary" type="submit">Yes, renew
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- End Modal renew --}}

                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $document->name }}</td>
                                            <td>{{ $document->remark }}</td>
                                            <td class="text-center">
                                                {{ date('d-M-Y', strtotime($document->last_updated)) }}</td>
                                            <td class="text-end">{{ $document->update_period }}</td>
                                            <td class="text-center">{{ $document->renewalDeadline() }}</td>
                                            <td>
                                                @if ($document->status == 'Renewed')
                                                    <span class="badge rounded-pill bg-primary">Renewed</span>
                                                @elseif($document->status == 'Need Renewing')
                                                    <span class="badge rounded-pill bg-warning">Need Renewing</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger">Unrenewed</span>
                                                @endif
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
    <!-- Container-fluid Ends-->

    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
                let csrf = $('meta[name="csrf-token"]').attr("content");

                $(".product-append-discount").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/product_sub_types/selectAll",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_sub_material + " " + item.type_name,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                $(document).on("click", ".modal-btn", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    $(".product-append-discount").select2({
                        dropdownParent: modal_id,
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/product_sub_types/selectAll",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_sub_material + " " +
                                                item.type_name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                });

                let i = 0;

                $("#addfields").on("click", function() {
                    ++i;
                    let form =
                        '<div class="form-group row"> <div class="form-group col-7" > <label> Product </label> <select name="discountFields[' +
                        i +
                        '][product_id]"' +
                        'class="form-control product-append-discount" required> <option value=""> Choose Product </option> </select>' +
                        '</div> <div class="form-group col-3">' +
                        '<label>Disc (%)</label> <input type="text" name="discountFields[' +
                        i +
                        '][discount]" id="discount"' +
                        'class="form-control" placeholder="Disc" required>' +
                        '</div>  <div class="form-group col-2">' +
                        '<label for="">&nbsp;</label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remfields" style="border:none; background-color:red">&#9747;</a> </div> </div>';

                    $("#formdynamic").append(form);
                    $(".product-append-discount").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/product_sub_types/selectAll",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_sub_material + " " +
                                                item.type_name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });
                $(document).on("click", ".remfields", function() {
                    $(this).parents(".form-group").remove();
                });

                var table =

                    $('#basic-2').DataTable();

                // //Order by the grouping
                $('#discount-table tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
            });
        </script>
    @endpush
@endsection
