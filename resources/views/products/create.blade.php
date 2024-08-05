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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create {{ $title }} </h6>
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
                        <form class="needs-validation" novalidate method="post" action="{{ url('products/') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @include('products._form')
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
                //initialize validator
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

                // get csrf token
                let csrf = $('meta[name="csrf-token"]').attr("content");
                y = 0;

                // image preview
                const imgInput = document.getElementById('inputreference');
                const imgEl = document.getElementById('previewimg');
                const previewLabel = document.getElementById('previewLabel');
                imgInput.addEventListener('change', () => {
                    if (imgInput.files && imgInput.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            imgEl.src = e.target.result;
                            imgEl.removeAttribute('hidden');
                            previewLabel.removeAttribute('hidden');
                        }
                        reader.readAsDataURL(imgInput.files[0]);
                    }
                });

                // get warehouse
                $(".getWarehouse").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/get_warehouses/",
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
                                        text: item.warehouses,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                // format weight
                $('.berat').on('input', function() {
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
                        return (input === 0) ? "" : input.toLocaleString("EN-en");
                    });
                    $this.next().val(input);
                });


                // format price purchase
                $('.harga_beli').on('change', function() {
                    let hargaInput = $(this).val();
                    hargaInput = hargaInput.replace(/,/g, '');
                    let hargaPisah = hargaInput.split('.');
                    if (hargaPisah.length > 1) {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString(
                            'en', {

                            });
                        hargaAkhir = hargaFloat + '.' + parseFloat(hargaPisah[1]);
                    } else {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah).toLocaleString(
                            'en', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        hargaAkhir = hargaFloat;
                    }
                    $(this).val(hargaAkhir);
                    $('.harga_beli_').val(hargaInput);
                });

                // format price non retail
                $('.harga_jual_nonretail').on('change', function() {
                    let hargaInput = $(this).val();
                    hargaInput = hargaInput.replace(/,/g, '');
                    let hargaPisah = hargaInput.split('.');
                    if (hargaPisah.length > 1) {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString(
                            'en', {

                            });
                        hargaAkhir = hargaFloat + '.' + parseFloat(hargaPisah[1]);
                    } else {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah).toLocaleString(
                            'en', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        hargaAkhir = hargaFloat;
                    }
                    $(this).val(hargaAkhir);
                    $('.harga_jual_nonretail_').val(hargaInput);
                });


                // format retail price default
                $('.harga_jual').on('change', function() {
                    let hargaInput = $(this).val();
                    hargaInput = hargaInput.replace(/,/g, '');
                    let hargaPisah = hargaInput.split('.');
                    if (hargaPisah.length > 1) {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString(
                            'en', {

                            });
                        hargaAkhir = hargaFloat + '.' + parseFloat(hargaPisah[1]);
                    } else {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah).toLocaleString(
                            'en', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        hargaAkhir = hargaFloat;
                    }
                    $(this).val(hargaAkhir);
                    $('.harga_jual_').val(hargaInput);
                });

                // form dinamyc add retail price
                $("#addTradeIn").on("click", function() {
                    ++y;
                    let form = `<div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                    <div class="col-12 col-lg-5 form-group">
                                        <label>Warehouse</label>
                                        <select name="tradeFields[${y}][id_warehouse]" class="form-control getWarehouse" required>
                                            <option value="">-Choose Warehouse-</option>
                                        </select>
                                    </div>
                                    <div class="col-10 col-lg-5 form-group">
                                        <label>Retail Price <small class="badge badge-primary">(ex.PPN)</small></label>
                                        <input class="form-control dynamic_price_retail" required  placeholder="0">
                                        <input type="hidden" class="dynamic_price_retail_" name="tradeFields[${y}][harga_jual]" >
                                    </div>
                                    <div class="col-2 col-md-2 form-group">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)"class="form-control text-white remTradeIn text-center"
                                        style="border:none; background-color:#dd5f6c">-</a>
                                    </div>
                                </div>`;

                    // append form
                    $("#formTradeIn").append(form);

                    // get warehouse
                    $(".getWarehouse").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/get_warehouses/",
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
                                            text: item.warehouses,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    // format retail price dynamic
                    $('.dynamic_price_retail').on('change', function() {
                        let hargaInput = $(this).val();
                        hargaInput = hargaInput.replace(/,/g, '');
                        let hargaPisah = hargaInput.split('.');
                        if (hargaPisah.length > 1) {
                            hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                            let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString(
                                'en', {

                                });
                            hargaAkhir = hargaFloat + '.' + parseFloat(hargaPisah[1]);
                        } else {
                            hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                            let hargaFloat = parseFloat(hargaPisah).toLocaleString(
                                'en', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            hargaAkhir = hargaFloat;
                        }
                        $(this).val(hargaAkhir);
                        $('.dynamic_price_retail_').val(hargaInput);
                    });
                });

                // remove form
                $(document).on("click", ".remTradeIn", function() {
                    $(this).closest(".row").remove();
                });
            });
        </script>
    @endpush
@endsection
