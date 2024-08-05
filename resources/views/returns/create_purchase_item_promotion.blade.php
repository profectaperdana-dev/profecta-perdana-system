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
                <div class="card shadow">

                    <div class="card-body">
                        <div class="mb-3 row box-select-all justify-content-end">
                            <button class="col-1 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                        </div>
                        <form method="post" action="{{ url('material-promotion/purchase/return/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @include('returns._form_purchase_item_promotion')
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
                $('.multi-select').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%'
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
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/material-promotion/selectPurchaseReturn",
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
                                        text: item.nama_barang,
                                        id: item.id_item,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                $(document).on('change', '.productReturn', function() {
                    let product_id = $(this).val();

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/material-promotion/selectPurchaseQtyReturn",
                        data: {
                            _token: csrf,
                            s: so_id,
                            p: product_id
                        },
                        dataType: "json",
                        success: function(data) {
                            if (product_id == "") {
                                $(this).parent().siblings().find('.box-order-amount')
                                    .attr('hidden', true);
                            } else {
                                $(this).parent().siblings().find('.box-order-amount')
                                    .attr('hidden', false);
                                $(this).parent().siblings().find('.box-return-amount')
                                    .attr('hidden', false);
                                $(this).parent().siblings().find('.order-amount').html(
                                    data.qty);
                                $(this).parent().siblings().find('.return-amount').html(
                                    data.return);
                            }

                        },
                    });
                });

                let x = 0;
                $("#addReturn").on("click", function() {
                    ++x;
                    let form =
                        '<div class="form-group row rounded pt-2 mb-3" style="background-color: #f0e194">' +
                        '<div class="form-group col-12 col-lg-7">' +
                        "<label>Product</label>" +
                        '<select multiple name="returnFields[' +
                        x +
                        '][product_id]" class="form-control productReturn" required>' +

                        '</select>' +
                        '</div>' +
                        '<div class="col-9 col-lg-3 form-group">' +
                        '<label> Qty </label> ' +
                        '<input class="form-control" required name="returnFields[' +
                        x +
                        '][qty]">' +
                        '<small class="text-xs box-order-amount" hidden>Order Amount: <span class="order-amount">0</span></small>' +
                        '<small class="text-xs box-return-amount" hidden> | Returned: <span class="return-amount">0</span></small>' +
                        '</div>' +
                        '<div class="col-3 col-lg-2 form-group">' +
                        '<label for=""> &nbsp; </label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remReturn text-center" style="border:none; background-color:red">' +
                        '- </a> ' +
                        '</div>' +
                        ' </div>';
                    $("#formReturn").append(form);

                    $(".productReturn").select2({
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            type: "GET",
                            url: "/material-promotion/selectPurchaseReturn",
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
                                            text: item.nama_barang,
                                            id: item.id_item,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $(document).on('change', '.productReturn', function() {
                        let product_id = $(this).val();

                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/material-promotion/selectPurchaseQtyReturn",
                            data: {
                                _token: csrf,
                                s: so_id,
                                p: product_id
                            },
                            dataType: "json",
                            success: function(data) {
                                if (product_id == "") {
                                    $(this).parent().siblings().find('.box-order-amount')
                                        .attr('hidden', true);
                                } else {
                                    $(this).parent().siblings().find('.box-order-amount')
                                        .attr('hidden', false);
                                    $(this).parent().siblings().find('.box-return-amount')
                                        .attr('hidden', false);
                                    $(this).parent().siblings().find('.order-amount').html(
                                        data.qty);
                                    $(this).parent().siblings().find('.return-amount').html(
                                        data.return);
                                }

                            },
                        });
                    });
                });

                //remove Purchase Order fields
                $(document).on("click", ".remReturn", function() {
                    $(this).closest(".row").remove();
                });

            });
        </script>
    @endpush
@endsection
