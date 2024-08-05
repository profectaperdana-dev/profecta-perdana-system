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
                        <form method="post" action="{{ url('purchase_orders/') }}" enctype="multipart/form-data"
                            id="">
                            @csrf
                            @include('purchase_orders._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        @include('layouts.partials.multi-select')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            let csrf = $('meta[name="csrf-token"]').attr("content");

            $(document).ready(function() {
                $('form').submit(function(e) {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);
                        // e.preventDefault(); // prevent form submission
                    }
                });

                $(".supplier-select, .warehouse-select").select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                let selected_warehouse = "";
                $('.warehouse-select').change(function() {
                    selected_warehouse = $('.warehouse-select').val();
                });
                $(".productPo").select2({
                    placeholder: 'Select an product',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/products/selectByWarehouse",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                c: selected_warehouse,
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_sub_material + " " +
                                            item
                                            .type_name + " " + item.nama_barang,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                }).on("select2:select", function() {
                    $(".qty").focus();
                });
                let x = 0;
                $(document).on("click", ".addPo", function() {
                    ++x;
                    let form = `<div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                    <div class="mb-2 col-12 col-lg-7">
                                        <label>Product</label>
                                        <select name="poFieldss[${x}][product_id]" class="form-control productPo" required multiple>
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-3 mb-2">
                                        <label>Qty</label>
                                        <input type="number" class="form-control qty" required name="poFieldss[${x}][qty]" id="">

                                    </div>
                                    <div class="col-3 col-lg-1 mb-2">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)" class="form-control addPo text-white  text-center"
                                            style="border:none; background-color:#276e61">+</a>
                                    </div>
                                    <div class="col-3 col-lg-1 mb-2">
                                        <label for="">&nbsp;</label>
                                        <a  href="javascript:void(0)" class="form-control remPo text-white text-center"
                                        style="border:none; background-color:#d94f5c">-</a>
                                    </div>
                                </div>`;
                    $("#formPo").append(form);

                    let selected_warehouse_add = $('.warehouse-select option:selected').val();

                    $(".productPo").select2({
                        placeholder: 'Select an product',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            type: "GET",
                            url: "/products/selectByWarehouse",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    c: selected_warehouse,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_sub_material + " " +
                                                item
                                                .type_name + " " + item.nama_barang,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    }).on("select2:select", function() {
                        $(".qty").focus();
                    });
                    $(".productPo").last().select2("open");


                });

                //remove Purchase Order fields
                $(document).on("click", ".remPo", function() {
                    $(this).parents(".form-group").remove();
                });
            });
        </script>
    @endpush
@endsection
