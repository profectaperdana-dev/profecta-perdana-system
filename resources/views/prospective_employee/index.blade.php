@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="font-weight-bold">{{ $title }} </h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                        {{ $title }}
                    </h6> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class="card">
                    <div class="card-header pb-0">

                        <a class="btn btn-primary click_this" href="{{ url('prospective_employees/create_code') }}">+ Create
                            Link
                            Form</a>

                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="2%">action</th>
                                        <th>No</th>
                                        <th>Link Form</th>
                                        <th>Name Candidate</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td style="width: 3%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    @if ($value->status == 1)
                                                        <a class="dropdown-item "
                                                            href="{{ url('prospective_employees/print_data/' . $value->code) }}">Print</a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>

                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <form method="post"
                                                        action="{{ url('prospective_employees/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                </h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <h5>Are you sure delete this link form ?</h5>
                                                                            <span class="form-control code text-info"
                                                                                type="text">
                                                                                {{ url()->current() . '/fill_form/' . $value->code }}</span>

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
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>
                                                <div class="input-group pill-input-group">
                                                    <span class="form-control code text-info" type="text">
                                                        {{ url()->current() . '/fill_form/' . $value->code }}</span>

                                                    <span class="input-group-text"><a href="#" class="copy_code">
                                                            <i class="icofont icofont-ui-copy"></i></a>
                                                    </span>
                                                </div>
                                            </td>

                                            <td>
                                                @if ($value->status == 1)
                                                    <span>{{ $value->name }}</span>
                                                @else
                                                    <span class="badge badge-danger">Form Not Filled</span>
                                                @endif

                                            </td>
                                            <td>
                                                @if ($value->status == 1)
                                                    <span class="badge badge-success">Form Filled</span>
                                                @else
                                                    <span class="badge badge-danger">Form Not Filled</span>
                                                @endif

                                            </td>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <input type="text" hidden value="{{ $ }}"> --}}
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.copy_code').click(function() {
                    var code = $(this).closest('td').find('.code').text();
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(code).select();
                    document.execCommand("copy");
                    $temp.remove();
                    $.notify({
                        title: 'Success !',
                        message: 'Code Copied'
                    }, {
                        type: 'success',
                        allow_dismiss: true,
                        newest_on_top: true,
                        mouse_over: true,
                        showProgressbar: false,
                        spacing: 10,
                        timer: 3000,
                        placement: {
                            from: 'top',
                            align: 'right'
                        },
                        offset: {
                            x: 30,
                            y: 30
                        },
                        delay: 1000,
                        z_index: 10000,
                        animate: {
                            enter: 'animated swing',
                            exit: 'animated swing'
                        }
                    });
                    // alert('Code copied : ' + code);
                });
            });
        </script>
    @endpush
@endsection
