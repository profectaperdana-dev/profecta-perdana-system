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
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
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
                                        text: item.nama_sub_material + " " + item
                                            .type_name + " " + item.nama_barang,
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

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/stock_mutation/checkMaterial",
                        data: {
                            _token: csrf,
                            p: product_id
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                $(this).parent().siblings('.parentDot').attr('hidden',
                                    false);
                            } else {
                                $(this).parent().siblings('.parentDot').attr('hidden',
                                    true);
                            }

                        },
                    });

                    let parentDot = $(this).parent().siblings('.parentDot');

                    parentDot.find(".dotProduct").select2({
                        placeholder: 'Select an option',
                        allowClear: false,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/tyre_dot/selectDot",
                            data: {
                                _token: csrf,
                                p: product_id,
                                w: warehouse_from
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.dot +
                                                ' - [ ' +
                                                item.qty +
                                                ' pcs]',
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    }).on("select2:select", function() {
                        $(".dotProduct").attr('readonly', true);
                    });

                    let xdot = 0;
                    let index_product = parentDot.attr('data-index');
                    parentDot.off("click", ".addDot");
                    parentDot.on("click", ".addDot", function() {
                        // console.log(index_product);
                        ++xdot;
                        let form_dot = `<div class="row">
                            <div class="col-3 form-group">
                                <label for="">DOT</label>
                                <select name="mutationFields[${index_product}][${xdot}][Dot]" id="" class="form-control dotProduct"
                                    multiple></select>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Qty</label>
                                <input type="text" name="mutationFields[${index_product}][${xdot}][qtyDot]"
                                    class="form-control text-center qtyDot" id="inputGroup-sizing-sm" value="0"
                                    placeholder="Qty" aria-label="Qty">

                            </div>
                            <div class="col-1 form-group">
                                <label for="">&nbsp;</label>
                                <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                                    style="border:none; background-color:green">+</a>
                            </div>
                            <div class="col-1 form-group">
                                <label for="">&nbsp;</label>
                                <a href="javascript:void(0)" class="form-control text-white text-center remDot"
                                    style="border:none; background-color:red">-</a>
                            </div>
                        </div>`;
                        // console.log(parentDot.html());
                        parentDot.append(form_dot);

                        parentDot.find(".dotProduct").select2({
                            placeholder: 'Select an option',
                            allowClear: false,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                context: this,
                                type: "GET",
                                url: "/tyre_dot/selectDot",
                                data: {
                                    _token: csrf,
                                    p: product_id,
                                    w: warehouse_from
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.dot +
                                                    ' - [ ' +
                                                    item.qty +
                                                    ' pcs]',
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        }).on("select2:select", function() {
                            $(".dotProduct").attr('readonly', true);
                        });

                    });

                    $(document).on("click", ".remDot", function() {
                        $(this).closest(".row").remove();
                    });


                });

                let x = 0;
                $(document).off("click", ".addM");
                $(document).on("click", ".addM", function() {
                    ++x;
                    let form =
                        '<div class="form-group rounded row pt-2 mb-3" style="background-color: #f0e194">' +
                        '<div class="form-group col-12 col-lg-5">' +
                        "<label>Product</label>" +
                        '<select multiple name="mutationFields[' +
                        x +
                        '][product_id]" class="form-control productM" required>' +

                        '</select>' +
                        '</div>' +
                        '<div class="col-9 col-lg-2 form-group">' +
                        '<label> Qty </label> ' +
                        '<input class="form-control" required name="mutationFields[' +
                        x +
                        '][qty]">' +
                        '<small class="from-stock" hidden>Stock : 0</small>' +
                        '</div>' +
                        '<div class="col-9 col-lg-3 form-group">' +
                        '<label>Note</label>' +
                        '<input type="text" required class="form-control" name="mutationFields[' + x +
                        '][note]" id="">' +
                        '</div>' +
                        '<div class="col-3 col-lg-1 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remMutation text-center" style="border:none; background-color:#d94f5c">' +
                        '- </a> ' +
                        '</div>' +
                        '<div class="col-3 col-lg-1 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a href="javascript:void(0)" class="form-control text-white addM  text-center" style="border:none; background-color:#276e61">' +
                        '+ </a>' +
                        '</div>' +
                        `<div class="parentDot" data-index="${x}" hidden>
                            <div class="row ">
                                <div class="col-3 form-group">
                                    <label for="">DOT</label>
                                    <select name="mutationFields[${x}][0][Dot]" id="" class="form-control dotProduct"
                                        multiple></select>
                                </div>
                                <div class="col-2 form-group">
                                    <label for="">Qty</label>
                                    <input type="text" name="mutationFields[${x}][0][qtyDot]"
                                        class="form-control text-center qtyDot" id="inputGroup-sizing-sm" value="0"
                                        placeholder="Qty" aria-label="Qty">

                                </div>
                                <div class="col-1 form-group">
                                    <label for="">&nbsp;</label>
                                    <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                                        style="border:none; background-color:green">+</a>
                                </div>
                            </div>
                        </div>` +
                        ' </div>';
                    $("#formMutation").append(form);

                    $(".productM").select2({
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
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
                                            text: item.nama_sub_material + " " +
                                                item.type_name + " " + item
                                                .nama_barang,
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
