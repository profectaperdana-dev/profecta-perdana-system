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
                        <h5>Create Stock Mutation</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ url('stock_mutation/store') }}" enctype="multipart/form-data">
                            @csrf
                            @include('stock_mutations._form')
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

                var warehouse_from = $('#from_warehouse').val();
                $(document).on('change', '#from_warehouse', function() {
                    warehouse_from = $(this).val();
                });

                $(".productM").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/stock_mutation/select",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                fw: warehouse_from
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

                $(document).on('change', '.productM', function() {
                    let product_id = $(this).val();

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/stock_mutation/getQtyDetail",
                        data: {
                            _token: csrf,
                            fw: warehouse_from,
                            p: product_id
                        },
                        dataType: "json",
                        success: function(data) {
                            if (product_id == "") {
                                $(this).parent().siblings().find('.from-stock')
                                    .attr('hidden',
                                        true);
                            } else {
                                $(this).parent().siblings().find('.from-stock')
                                    .attr('hidden',
                                        false);
                                $(this).parent().siblings().find('.from-stock').html(
                                    'Stock : ' + data);
                            }

                        },
                    });
                });

                let x = 0;
                $("#addM").on("click", function() {
                    ++x;
                    let form =
                        '<div class="form-group row">' +
                        '<div class="form-group col-7">' +
                        "<label>Product</label>" +
                        '<select name="mutationFields[' +
                        x +
                        '][product_id]" class="form-control productM" required>' +
                        '<option value=""> Choose Product </option> ' +

                        '</select>' +
                        '</div>' +
                        '<div class="col-3 col-md-3 form-group">' +
                        '<label> Qty </label> ' +
                        '<input class="form-control" required name="mutationFields[' +
                        x +
                        '][qty]">' +
                        '<small class="from-stock" hidden>Stock : 0</small>' +
                        '</div>' +
                        '<div class="col-2 col-md-2 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a class="form-control text-white remMutation text-center" style="border:none; background-color:red">' +
                        '- </a> ' +
                        '</div>' +
                        ' </div>';
                    $("#formMutation").append(form);

                    $(".productM").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/stock_mutation/select",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    fw: warehouse_from
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

                    $(document).on('change', '.productM', function() {
                        let product_id = $(this).val();

                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/stock_mutation/getQtyDetail",
                            data: {
                                _token: csrf,
                                fw: warehouse_from,
                                p: product_id
                            },
                            dataType: "json",
                            success: function(data) {
                                if (product_id == "") {
                                    $(this).parent().siblings().find('.from-stock')
                                        .attr('hidden',
                                            true);
                                } else {
                                    $(this).parent().siblings().find('.from-stock')
                                        .attr('hidden',
                                            false);
                                    $(this).parent().siblings().find('.from-stock').html(
                                        'Stock : ' + data);
                                }

                            },
                        });
                    });
                });

                //remove Purchase Order fields
                $(document).on("click", ".remMutation", function() {
                    $(this).closest(".row").remove();
                });

            });
        </script>
    @endpush
@endsection
