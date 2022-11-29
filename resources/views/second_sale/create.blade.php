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
                        {{ Auth::user()->warehouseBy->warehouses }} : Retail {{ $title }} </h6>
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
                        <h5>Create Data Order</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="post"
                            action="{{ url('retail_second_products/') }}" enctype="multipart/form-data" id="">
                            @csrf
                            @include('second_sale._form')
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

                //* FUNCTION JS DEFAULT *//

                let id_product = 0;
                $(document).on('change', '.id_product', function() {
                    id_product = $(this).val();
                });
                $(document).on("input", ".cek_stock", function() {
                    let qtyValue = $(this).val();

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/retail_second_products/cekQty/" + id_product,
                        data: {
                            _token: csrf,
                        },
                        dataType: "json",
                        success: function(data) {
                            if (parseInt(qtyValue) > parseInt(data.qty)) {
                                $(this).parent().find(".qty-warning").removeAttr("hidden");
                                $(this).addClass("is-invalid");
                            } else {
                                $(this)
                                    .parent()
                                    .find(".qty-warning")
                                    .attr("hidden", "true");
                                $(this).removeClass("is-invalid");
                            }
                        },
                    });
                });
                $('.total').on('keyup', function() {
                    var selection = window.getSelection().toString();
                    if (selection !== '') {
                        return;
                    }
                    // When the arrow keys are pressed, abort.
                    if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                        return;
                    }
                    var $this = $(this);
                    // Get the value.
                    var input = $this.val();
                    var input = input.replace(/[\D\s\._\-]+/g, "");
                    input = input ? parseInt(input, 10) : 0;
                    $this.val(function() {
                        return (input === 0) ? "" : input.toLocaleString("id-ID");
                    });
                    $this.next().val(input);
                });
                $(".all_product_TradeIn").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/retail_second_products/select",
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


                //* FUNCTION JS DINAMIC *//
                $("#addTradeIn").on("click", function() {
                    ++y;
                    let form = ` <div class="mx-auto py-2 form-group row bg-primary">
                <div class="form-group col-6 col-md-4">
                    <label>Baterry</label>
                    <select name="tradeFields[${y}][product_trade_in]" class="form-control all_product_TradeIn" required>
                        <option value="">--Choose Battery--</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 form-group">
                    <label>Qty</label>
                    <input class="form-control cek_stock" required name="tradeFields[${y}][qty]" id="">
                    <small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>

                </div>
                <div class="col-5 col-md-2 form-group">
                    <label>Disc (%)</label>
                    <input type="number" value="0" class="form-control"  name="tradeFields[${y}][disc_percent]"
                        id="">
                </div>
                <div class="col-5 col-md-2 form-group">
                    <label>Disc (Rp)</label>
                    <input class="form-control total" id="" value="0">
                    <input type="hidden" value="0" name="tradeFields[${y}][disc_rp]" >
                </div>
                <div class="col-2 col-md-2 form-group">
                    <label for="">&nbsp;</label>
                    <a href="javascript:void(0)"class="form-control text-white remTradeIn text-center"
                     style="border:none; background-color:red">-</a>
                </div>

            </div>`;
                    $("#formTradeIn").append(form);
                    let id_product = 0;
                    $(document).on('change', '.id_product', function() {
                        id_product = $(this).val();
                    });
                    $(document).on("input", ".cek_stock", function() {
                        let qtyValue = $(this).val();

                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/retail_second_products/cekQty/" + id_product,
                            data: {
                                _token: csrf,
                            },
                            dataType: "json",
                            success: function(data) {
                                if (parseInt(qtyValue) > parseInt(data.qty)) {
                                    $(this).parent().find(".qty-warning").removeAttr(
                                        "hidden");
                                    // $(this).addClass("is-invalid");

                                } else {
                                    $(this)
                                        .parent()
                                        .find(".qty-warning")
                                        .attr("hidden", "true");
                                    // $(this).removeClass("is-invalid");

                                }
                            },
                        });
                    });
                    $('.total').on('keyup', function() {
                        var selection = window.getSelection().toString();
                        if (selection !== '') {
                            return;
                        }
                        // When the arrow keys are pressed, abort.
                        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                            return;
                        }
                        var $this = $(this);
                        // Get the value.
                        var input = $this.val();
                        var input = input.replace(/[\D\s\._\-]+/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return (input === 0) ? "" : input.toLocaleString("id-ID");
                        });
                        $this.next().val(input);
                    });
                    $(".all_product_TradeIn").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/retail_second_products/select",
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
