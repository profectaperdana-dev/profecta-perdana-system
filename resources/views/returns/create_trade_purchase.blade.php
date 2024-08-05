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
                        <h6>Create Return</h6>
                        <hr class="bg-primary">
                        <div class="mb-3 row box-select-all justify-content-end">
                            <button class="col-2 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                        </div>
                        <form method="post" action="{{ url('return_trade_in/store') }}" enctype="multipart/form-data">
                            @csrf
                            @include('returns._form_trade_purchase')
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
                    var form = $(this);
                    var button = form.find('button[type="submit"]');

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);
                        // e.preventDefault(); // prevent form submission
                    }
                });
                $('.multi-select').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });
                $('.return_reason1').change(function() {
                    let return_reason1 = $(this).val();
                    if (return_reason1 != "Other") {
                        $('.return_reason2').attr('hidden', false);
                        $('.return_reason2').find('select[name="return_reason2"]').attr('required', true);
                        $('.other').attr('hidden', true);
                        $('.other').find('textarea[name="return_reason"]').attr('required', false);
                    } else {
                        $('.return_reason2').attr('hidden', true);
                        $('.return_reason2').find('select[name="return_reason2"]').attr('required', false);
                        $('.other').attr('hidden', false);
                        $('.other').find('textarea[name="return_reason"]').attr('required', true);
                    }
                });

                var po_id = $('#po_id').val();
                $(".productReturn").select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/trade_in/selectReturn",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                p: po_id
                            };
                        },
                        dataType: "json",
                        delay: 250, 
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.name_product_trade_in,
                                        id: item.id,
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
                        url: "/trade_in/getQtyDetail",
                        data: {
                            _token: csrf,
                            s: po_id,
                            p: product_id
                        },
                        dataType: "json",
                        success: function(data) {
                            if (product_id == "") {
                                $(this).parent().siblings().find('.box-order-amount')
                                    .attr('hidden',
                                        true);
                            } else {
                                $(this).parent().siblings().find('.box-order-amount')
                                    .attr('hidden',
                                        false);
                                $(this).parent().siblings().find('.box-return-amount')
                                    .attr('hidden',
                                        false);
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
                        '<div class="form-group rounded row bg-primary pt-2 mb-3">' +
                        '<div class="form-group col-12 col-lg-7">' +
                        "<label>Product</label>" +
                        '<select name="returnFields[' +
                        x +
                        '][product_id]" class="form-control productReturn" required multiple>' +

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
                        '<a class="form-control text-white remReturn text-center" style="border:none; background-color:red">' +
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
                            url: "/trade_in/selectReturn",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    p: po_id
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name_product_trade_in,
                                            id: item.product_trade_in,
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
                            url: "/trade_in/getQtyDetail",
                            data: {
                                _token: csrf,
                                s: po_id,
                                p: product_id
                            },
                            dataType: "json",
                            success: function(data) {
                                if (product_id == "") {
                                    $(this).parent().siblings().find('.box-order-amount')
                                        .attr('hidden',
                                            true);
                                } else {
                                    $(this).parent().siblings().find('.box-order-amount')
                                        .attr('hidden',
                                            false);
                                    $(this).parent().siblings().find('.box-return-amount')
                                        .attr('hidden',
                                            false);
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
