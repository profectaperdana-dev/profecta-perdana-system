@extends('layouts.master')
@section('content')
    @push('css')
        @include('report.style')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <style>
            .multi-so {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create
                        {{ $title }} </h6> --}}
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
                        <form method="post" action="{{ url('material-promotion/transaction/store') }}"
                            enctype="multipart/form-data" id="">
                            @csrf
                            @include('item_promotion._form')
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
        @include('layouts.partials.multi-select')
        <script>
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

                $(window).on("resize", function() {
                    if ($("select:focus").length) {
                        $("html, body").animate({
                            scrollTop: $("select:focus").offset().top - 20
                        }, 200);
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");

                $(".price").select2({
                    placeholder: 'Select the product first',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                let customer_id = "";
                $(".customer-append").change(function() {
                    customer_id = $(this).val();

                    if (customer_id == '-1') {
                        $('.other-customer').attr('hidden', false);
                    } else {
                        $('.other-customer').attr('hidden', true);
                    }
                });

                let warehouse_id = $('#warehouse').val();
                $("#warehouse").change(function() {
                    warehouse_id = $(this).val();
                });

                $(".multi-so").select2({
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
                                w: warehouse_id,
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

                $(document).on("change", ".multi-so", function() {
                    product_id = $(this).val();

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
                                    w: warehouse_id,
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
                            w: warehouse_id,
                        },
                        dataType: "json",
                        success: function(data) {
                            // console.log($(this).closest('.row').find(".qty-warning").html());
                            $(this).closest('.row').find(".qty-stock")
                                .removeAttr("hidden");
                            $(this).closest('.row').find(".qty-stock").html(
                                `Stock Total: ${data.qty}`);

                        },
                    });
                });
                let x = 0;
                let product_id = 0;
                //Get discount depent on product

                $(document).on("input", ".cekQty", function() {
                    let qtyValue = $(this).val();

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/material-promotion/cekQty/" + product_id,
                        data: {
                            _token: csrf,
                            w: warehouse_id,
                        },
                        dataType: "json",
                        success: function(data) {
                            // console.log(data);
                            if (parseInt(qtyValue) > parseInt(data.qty)) {
                                $(this).parent().find(".qty-warning").removeAttr("hidden");
                                $(this)
                                    .parent()
                                    .find(".qty-stock")
                                    .attr("hidden", "true");
                                $(this).addClass("is-invalid");

                                $.notify({
                                    title: 'Warning !',
                                    message: 'available stock is insufficient'

                                }, {
                                    type: 'warning',
                                    allow_dismiss: true,
                                    newest_on_top: true,
                                    mouse_over: true,
                                    showProgressbar: true,
                                    spacing: 10,
                                    timer: 1000,
                                    placement: {
                                        from: 'top',
                                        align: 'right'
                                    },
                                    offset: {
                                        x: 30,
                                        y: 30
                                    },
                                    delay: 1000,
                                    z_index: 3000,
                                    animate: {
                                        enter: 'animated swing',
                                        exit: 'animated swing'
                                    }
                                });
                            } else {
                                $(this)
                                    .parent()
                                    .find(".qty-warning")
                                    .attr("hidden", "true");
                                $(this).parent().find(".qty-stock").removeAttr("hidden");

                                $(this)
                                    .parent()
                                    .find(".qty-stock")
                                    .html(`Stock Total: ${data.qty}`);
                                $(this).removeClass("is-invalid");
                            }
                        },
                    });
                });

                $(document).on("click", ".addSo", function() {
                    ++x;
                    let form = `<div class="mx-auto py-2 form-group rounded row"  style="background-color: #f0e194">
                                    <div class="mb-2 col-12 col-lg-5">
                                        <label>Product</label>
                                        <select name="promFields[${x}][product_id]" class="form-control multi-so" required multiple>
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <label>Price by Purchase</label>
                                        <select name="promFields[${x}][price]" class="form-control price" required multiple>

                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <label>Qty</label>
                                        <input type="number" class="form-control qty cekQty" required name="promFields[${x}][qty]" id="">
                                        <small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>
                                        <small class="text-black qty-stock" hidden></small>
                                    </div>
                                    <div class="col-6 col-md-1 hideAdd">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)" class="form-control addSo text-white  text-center"
                                            style="border:none;background-color:#276e61">+</a>
                                    </div>
                                    <div class="col-6 col-md-1 form-group">
                                    <label for=""> &nbsp; </label>
                                        <a href="#" class="form-control text-white remSo text-center" style="border:none; background-color:#d94f5c">
                                        - </a>
                                    </div>
                                </div>`;
                    $("#formSo").append(form);
                    // $("#formSo").find('.hideAdd').not(':last').attr('hidden', 'true');

                    $(".price").select2({
                        placeholder: 'Select the product first',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                    });

                    $(".multi-so").select2({
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
                                    w: warehouse_id,
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
                    }).on("select2:select", function() {
                        $(".qty").focus();
                    });
                    $(".multi-so").last().select2("open");

                });

                //remove Sales Order fields
                $(document).on("click", ".remSo", function() {
                    $(this).parents(".form-group").remove();
                });

            });
        </script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
            });
        </script>
    @endpush
@endsection
