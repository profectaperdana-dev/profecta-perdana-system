@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        @include('report.style')
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
                        <form method="post" action="{{ url('stock_mutation/second_store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @include('stock_mutations._form_second')
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
                $('form').submit(function(e) {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    if (form[0].checkValidity()) {
                        button.prop('disabled', true);
                        $(this).find('.spinner-border').removeClass('d-none');
                        $(this).find('span:not(.spinner-border)').addClass('d-none');
                        $(this).off('click');
                    }
                });
                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                var warehouse_from = $('#from_warehouse').val();
                $(document).on('change', '#from_warehouse', function() {
                    warehouse_from = $(this).val();
                });

                $(".productM").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/stock_mutation/selectSecond",
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
                                        text: item.nama_barang,
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
                        url: "/stock_mutation/getSecondProductQty",
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
                $(document).off("click", ".addM");
                $(document).on("click", ".addM", function() {
                    ++x;
                    let form =
                        '<div class="form-group row bg-primary pt-2 mb-3">' +
                        '<div class="form-group col-12 col-lg-5">' +
                        "<label>Product</label>" +
                        '<select name="mutationFieldss[' +
                        x +
                        '][product_id]" class="form-control productM" required>' +
                        '<option value=""> Choose Product </option> ' +

                        '</select>' +
                        '</div>' +
                        '<div class="col-9 col-lg-3 form-group">' +
                        '<label> Qty </label> ' +
                        '<input class="form-control" required name="mutationFieldss[' +
                        x +
                        '][qty]">' +
                        '<small class="from-stock" hidden>Stock : 0</small>' +
                        '</div>' +
                        '<div class="col-9 col-lg-3 form-group">' +
                        '<label>Note (Optional)</label>' +
                        '<input type="text" class="form-control" name="mutationFields[' + x +
                        '][note]" id="">' +
                        '</div>' +
                        '<div class="col-3 col-lg-2 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a class="form-control text-white remMutation text-center" style="border:none; background-color:red">' +
                        '- </a> ' +
                        '</div>' +
                        '<div class="col-3 col-lg-2 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a href="javascript:void(0)" class="form-control text-white addM  text-center" style="border:none; background-color:green">' +
                        '+ </a>' +
                        '</div>' +
                        ' </div>';
                    $("#formMutation").append(form);

                    $(".productM").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/stock_mutation/selectSecond",
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
                                            text: item.nama_barang,
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
                            url: "/stock_mutation/getSecondProductQty",
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

                    $(".productM").last().select2("open");
                });

                //remove Purchase Order fields
                $(document).on("click", ".remMutation", function() {
                    $(this).closest(".row").remove();
                });

            });
        </script>
    @endpush
@endsection
