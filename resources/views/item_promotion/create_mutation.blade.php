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

                    <div class="card-body">
                        <form method="post" action="{{ url('material-promotion/mutation/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @include('item_promotion._form_mutation')
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

                $(".price").select2({
                    placeholder: 'Select the product first',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });
                var warehouse_from = $('#from_warehouse').val();
                $(document).on('change', '#from_warehouse', function() {
                    warehouse_from = $(this).val();
                });

                $(".productM").select2({
                    placeholder: 'Select a product',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/material-promotion/select",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                w: warehouse_from,
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_barang,
                                        id: item.id_item,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                $(document).on('change', '.productM', function() {
                    let product_id = $(this).val();

                    $(this).closest('.row').find(".price").select2({
                        placeholder: 'Select the price',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            type: "GET",
                            url: "/material-promotion/selectPrice",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouse_from,
                                    p: product_id,
                                    isreturn: false
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: parseInt(item.cost)
                                                .toLocaleString() +
                                                ' (' + item.qty +
                                                ') ',
                                            id: item.cost,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/material-promotion/cekQty/" + product_id,
                        data: {
                            _token: csrf,
                            w: warehouse_from,
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
                                    'Stock Total: ' + data.qty);
                            }

                        },
                    });

                });

                let x = 0;
                $(document).off("click", ".addM");
                $(document).on("click", ".addM", function() {
                    ++x;
                    let form =
                        '<div class="form-group rounded bg-primary row pt-2 mb-3">' +
                        '<div class="form-group col-12 col-lg-5">' +
                        "<label>Product</label>" +
                        '<select multiple name="mutationFields[' +
                        x +
                        '][product_id]" class="form-control productM" required>' +

                        '</select>' +
                        '</div>' +
                        '<div class="form-group col-12 col-lg-3">' +
                        '<label>Price by Purchase</label>' +
                        '<select name="mutationFields[' + x +
                        '][price]" class="form-control price" multiple required>' +
                        '</select>' +
                        '</div>' +
                        '<div class="col-9 col-lg-2 form-group">' +
                        '<label> Qty </label> ' +
                        '<input class="form-control" required name="mutationFields[' +
                        x +
                        '][qty]">' +
                        '<small class="from-stock" hidden>Stock Total: 0</small>' +
                        '</div>' +
                        '<div class="col-3 col-lg-1 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remMutation text-center" style="border:none; background-color:#d94f5c">' +
                        '- </a> ' +
                        '</div>' +
                        '<div class="col-3 col-lg-1 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a href="javascript:void(0)" class="form-control text-white addM  text-center" style="border:none;background-color:green">' +
                        '+ </a>' +
                        '</div>' +
                        ' </div>';
                    $("#formMutation").append(form);

                    $(".price").select2({
                        placeholder: 'Select the product first',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                    });

                    $(".productM").select2({
                        placeholder: 'Select a product',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            type: "GET",
                            url: "/material-promotion/select",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouse_from,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_barang,
                                            id: item.id_item,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $(document).on('change', '.productM', function() {
                        let product_id = $(this).val();

                        $(this).closest('.row').find(".price").select2({
                            placeholder: 'Select the price',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                type: "GET",
                                url: "/material-promotion/selectPrice",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        w: warehouse_from,
                                        p: product_id,
                                        isreturn: false
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: parseInt(item.cost)
                                                    .toLocaleString() +
                                                    ' (' + item.qty +
                                                    ') ',
                                                id: item.cost,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });

                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/material-promotion/cekQty/" + product_id,
                            data: {
                                _token: csrf,
                                w: warehouse_from,
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
                                        'Stock Total: ' + data.qty);
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
