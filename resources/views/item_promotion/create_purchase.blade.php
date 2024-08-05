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
                        <form method="post" action="{{ url('material-promotion/purchase/store') }}"
                            enctype="multipart/form-data" id="">
                            @csrf
                            @include('item_promotion._form_purchase')
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
                $('.price').on('input', function(event) {
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
                    input = input.replace(/[\D\s\._\-]+/g, "");
                    input = input ? parseInt(input, 10) : 0;
                    $this.val(function() {
                        return input.toLocaleString("EN-en");
                    });
                    $this.next().val(input);
                });

                $(".multi-so").select2({
                    placeholder: 'Select an product',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/material-promotion/selectByItem",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term
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
                });
                let x = 0;
                let product_id = 0;
                //Get discount depent on product

                $(document).on("click", ".addSo", function() {
                    ++x;
                    let form = `<div class="mx-auto py-2 form-group rounded row"  style="background-color: #f0e194">
                                    <div class="mb-2 col-12 col-lg-5">
                                        <label>Product</label>
                                        <select name="promFields[${x}][product_id]" class="form-control multi-so" required multiple>
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <label>Price</label>
                                        <input type="text" class="form-control price" required id="">
                                        <input type="hidden" name="promFields[${x}][price]">
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <label>Qty</label>
                                        <input type="number" class="form-control qty cekQty" required name="promFields[${x}][qty]" id="">
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

                    $('.price').on('input', function(event) {
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
                        input = input.replace(/[\D\s\._\-]+/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return input.toLocaleString("EN-en");
                        });
                        $this.next().val(input);
                    });

                    $(".multi-so").select2({
                        placeholder: 'Select an product',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            type: "GET",
                            url: "/material-promotion/selectByItem",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term,
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
                        $(".price").focus();
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
