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
                        <form class="needs-validation" novalidate method="post" action="{{ url('trade_in/store') }}"
                            enctype="multipart/form-data" id="">
                            @csrf
                            @include('product_trade_in._form')
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
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(function() {

                $('.nameCustomer').attr('hidden', 'hidden');
                $('.valCust').on('change', function() {
                    var val = $(this).val();
                    if (val == 'other') {
                        $('.otherCustomer').removeAttr('hidden');
                        $('.nameCustomer').removeAttr('hidden');
                        $('.nameCustomer').attr('required', 'required');
                        $('.phone').attr('required', 'required');
                    } else {
                        $('.otherCustomer').attr('hidden', 'hidden');
                        $('.nameCustomer').attr('hidden', 'hidden');
                        $('.nameCustomer').removeAttr('required');
                        $('.phone').removeAttr('required');
                    }
                });

                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
                //reload instance after dynamic element is added
                // validator.reload();
                $('form').submit(function(e) {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                    if (validator.checkAll() != 0) {
                        $(this).find('button[type="submit"]').prop('disabled', false);
                    }
                });
            })
        </script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                y = 0;



                $(".all_product_TradeIn").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/all_product_trade_in",
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
                                    return [{
                                        text: item.name_product_trade_in,


                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });


                $("#addTradeIn").on("click", function() {
                    ++y;
                    let form =
                        '<div class="mx-auto py-2 form-group row bg-primary"> <div class="form-group col-7">' +
                        '<label>Baterry</label> <select name="tradeFields[' +
                        y +
                        '][product_trade_in]" class="form-control all_product_TradeIn" required>' +
                        '<option value="">-Choose Battery-</option> ' +
                        "</select>" +
                        '</div>' +
                        '<div class="col-3 col-md-3 form-group">' +
                        "<label> Qty </label> " +
                        '<input class="form-control cekQty" required name="tradeFields[' +
                        y +
                        '][qty]">' +
                        "</div>" +
                        '<div class="col-2 col-md-2 form-group">' +
                        '<label for="">&nbsp;</label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remTradeIn text-center" style="border:none; background-color:red">X</a></div></div>';

                    $("#formTradeIn").append(form);
                    $(".all_product_TradeIn").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/all_product_trade_in",
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
                                        return [{
                                            text: item.name_product_trade_in,


                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                });
                $(document).on("click", ".remTradeIn", function() {
                    $(this).parents(".form-group").remove();
                });
            });
        </script>
    @endpush
@endsection
