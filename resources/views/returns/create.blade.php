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

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Return</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ url('return/store') }}" enctype="multipart/form-data">
                            @csrf
                            @include('returns._form')
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
        <script src="{{ asset('js/custom.js') }}"></script>
        <script>
            let csrf = $('meta[name="csrf-token"]').attr("content");

            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });

                $('.return_reason1').change(function() {
                    let return_reason1 = $(this).val();
                    if (return_reason1 == "Wrong Quantity" || return_reason1 == "Wrong Product Type") {
                        $('.return_reason2').attr('hidden', false);
                        $('.return_reason2').find('select[name="return_reason2"]').attr('required', true);
                        $('.other').attr('hidden', true);
                        $('.other').find('textarea[name="return_reason"]').attr('required', false);
                    } else if (return_reason1 == "Other") {
                        $('.return_reason2').attr('hidden', true);
                        $('.return_reason2').find('select[name="return_reason2"]').attr('required', false);
                        $('.other').attr('hidden', false);
                        $('.other').find('textarea[name="return_reason"]').attr('required', true);
                    } else {
                        $('.return_reason2').attr('hidden', true);
                        $('.return_reason2').find('select[name="return_reason2"]').attr('required', false);
                        $('.other').attr('hidden', true);
                        $('.other').find('textarea[name="return_reason"]').attr('required', false);
                    }
                });

                let so_id = $('#so_id').val();

                $(".productReturn").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/sales_order/selectReturn",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                s: so_id
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_barang +
                                            " (" +
                                            item.type_name +
                                            ", " +
                                            item.nama_sub_material +
                                            ")",
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                let x = 0;
                $("#addReturn").on("click", function() {
                    ++x;
                    let form =
                        '<div class="form-group row">' +
                        '<div class="form-group col-7">' +
                        "<label>Product</label>" +
                        '<select name="returnFields[' +
                        x +
                        '][product_id]" class="form-control productReturn" required>' +
                        '<option value=""> Choose Product </option> ' +

                        '</select>' +
                        '</div>' +
                        '<div class="col-3 col-md-3 form-group">' +
                        '<label> Qty </label> ' +
                        '<input class="form-control" required name="returnFields[' +
                        x +
                        '][qty]">' +
                        '</div>' +
                        '<div class="col-2 col-md-2 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a class="form-control text-white remReturn text-center" style="border:none; background-color:red">' +
                        '- </a> ' +
                        '</div>' +
                        ' </div>';
                    $("#formReturn").append(form);

                    $(".productReturn").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/sales_order/selectReturn",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    s: so_id
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_barang +
                                                " (" +
                                                item.type_name +
                                                ", " +
                                                item.nama_sub_material +
                                                ")",
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });

                //remove Purchase Order fields
                $(document).on("click", ".remReturn", function() {
                    $(this).parents(".form-group").remove();
                });

            });
        </script>
    @endpush
@endsection
