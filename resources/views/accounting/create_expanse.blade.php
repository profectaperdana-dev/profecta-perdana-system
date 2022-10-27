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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/product_uoms') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label class="font-weight-bold">Account</label>
                                            <select name="" class="form-control text-capitalize account"
                                                id="">
                                                <option value="">-- Select Account --</option>
                                                @foreach ($account as $item)
                                                    <option value="{{ $item->id }}">({{ $item->code }})
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="font-weight-bold">Sub Account</label>
                                            <select name="" class="form-control sub_account text-capitalize">
                                                <option value="">-- Select Sub Account --</option>

                                            </select>
                                        </div>

                                        <div class="col-md-4" hidden id="type_account">
                                            <label class="font-weight-bold">Sub Type Account</label>
                                            <select name="" class="form-control sub_type_account text-capitalize">
                                                <option value="">-- Select Sub Type Account --</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label class="font-weight-bold">Date</label>
                                            <input type="date"
                                                class="form-control text-capitalize {{ $errors->first('uom') ? ' is-invalid' : '' }}"
                                                name="uom" placeholder="Name Unit of Measurement">
                                            @error('uom')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="font-weight-bold">Memo</label>
                                            <input type="text"
                                                class="form-control text-capitalize {{ $errors->first('uom') ? ' is-invalid' : '' }}"
                                                name="uom" placeholder="Name Unit of Measurement">
                                            @error('uom')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="font-weight-bold">Total</label>
                                            <input type="number"
                                                class="form-control text-capitalize {{ $errors->first('uom') ? ' is-invalid' : '' }}"
                                                name="uom" placeholder="Name Unit of Measurement">
                                            @error('uom')
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

                $('.account').select2({
                    width: '100%',

                });

                $('.account').on('change', function() {
                    var account = $(this).val();
                    if (account == '1') {
                        $('#type_account').attr('hidden', false);
                    } else if (account == '2') {
                        $('#type_account').attr('hidden', true);
                    } else {
                        $('#type_account').attr('hidden', false);
                    }
                });
                $(".account").change(function() {
                    //clear select
                    $(".sub_account").empty();
                    //set id
                    let host = window.location.host;
                    let brand_id = $(".account").val();
                    // console.log(brand_id);
                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    if (brand_id) {
                        $(".sub_account").select2({
                            width: "100%",
                            ajax: {
                                type: "GET",
                                url: "/sub_account/select/" + brand_id,
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
                                            return {
                                                text: '(' + item.code + ') ' + item.name,
                                                id: item.id,
                                            };
                                        }),
                                    };
                                },
                            },
                        });
                    } else {
                        $(".sub_account").empty();
                    }
                });
                $(".sub_account").change(function() {
                    //clear select
                    $(".sub_type_account").empty();
                    //set id
                    let host = window.location.host;
                    let brand_id = $(".sub_account").val();
                    console.log(brand_id);
                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    if (brand_id) {
                        $(".sub_type_account").select2({
                            width: "100%",
                            ajax: {
                                type: "GET",
                                url: "/sub_type_account/select/" + brand_id,
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
                                            return {
                                                text: '(' + item.code + ') ' + item.name,
                                                id: item.id,
                                            };
                                        }),
                                    };
                                },
                            },
                        });
                    } else {
                        $(".sub_type_account").empty();
                    }
                });

            });
        </script>
    @endpush
@endsection
