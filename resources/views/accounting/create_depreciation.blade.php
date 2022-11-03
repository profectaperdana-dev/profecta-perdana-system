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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">
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
                        <form class="form-label-left input_mask" method="post" action="{{ url('/depreciation/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-12">

                                    <div class=" row">
                                        <div class="form-group col-md-4">
                                            <label class="font-weight-bold">Asset</label>
                                            <input type="text"
                                                class="form-control {{ $errors->first('asset') ? ' is-invalid' : '' }}"
                                                name="asset" placeholder="Enter Type of Asset">
                                            @error('asset')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="font-weight-bold">Amount</label>
                                            <input type="number"
                                                class="form-control {{ $errors->first('amount') ? ' is-invalid' : '' }}"
                                                name="amount" placeholder="Enter Amount of Asset">
                                            @error('amount')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="font-weight-bold">Lifetime (In Month)</label>
                                            <input type="number"
                                                class="form-control {{ $errors->first('lifetime') ? ' is-invalid' : '' }}"
                                                name="lifetime" placeholder="Enter Lifetime of Asset">
                                            @error('lifetime')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class=" row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold">Year of Acquisition</label>
                                            <input type="date"
                                                class="form-control {{ $errors->first('acquisition_year') ? ' is-invalid' : '' }}"
                                                name="acquisition_year">
                                            @error('acquisition_year')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold">Cost of Acquisition</label>
                                            <input type="number"
                                                class="form-control {{ $errors->first('acquisition_cost') ? ' is-invalid' : '' }}"
                                                name="acquisition_cost" placeholder="Enter Cost of Acquisition">
                                            @error('acquisition_cost')
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
