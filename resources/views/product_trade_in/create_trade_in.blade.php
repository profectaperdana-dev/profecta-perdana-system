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
                $('.multiSelect').select2({
                    placeholder: 'Select an product',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                $('form').submit(function() {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);
                        // e.preventDefault(); // prevent form submission
                    }
                });
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
            });
        </script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                y = 0;



                $(".all_product_TradeIn").select2({
                    placeholder: 'Select an warehouse',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
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
                }).on("select2:select", function() {
                    $(".qty").focus();
                });


                $(document).on("click", ".addTradeIn", function() {
                    ++y;
                    let form = `<div class="mx-auto py-2 form-group row rounded" style="background-color: #f0e194">
                                    <div class="form-group col-12 col-lg-7">
                                        <label>Baterry</label>
                                        <select name="tradeFields[${y}][product_trade_in]" class="form-control all_product_TradeIn" required
                                            multiple>
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-3 form-group">
                                        <label>Qty</label>
                                        <input class="form-control qty" required name="tradeFields[${y}][qty]" id="">
                                    </div>
                                    <div class="col-3 col-lg-1 form-group">
                                        <label for="">&nbsp;</label>
                                        <a id="" href="javascript:void(0)" class="form-control addTradeIn text-white  text-center"
                                            style="border:none; background-color:#276e61">+</a>
                                    </div>
                                    <div class="col-3 col-lg-1 form-group">
                                        <label for="">&nbsp;</label>
                                        <a id="" href="javascript:void(0)" class="form-control remTradeIn text-white  text-center"
                                        style="border:none; background-color:#d94f5c">-</a>
                                    </div>
                                </div>`;


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
                    }).on("select2:select", function() {
                        $(".qty").focus();
                    });
                    $(".all_product_TradeIn").last().select2("open");

                });
                $(document).on("click", ".remTradeIn", function() {
                    $(this).parents(".form-group").remove();
                });
            });
        </script>
    @endpush
@endsection
