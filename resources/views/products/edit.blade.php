@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
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
                        <h5>Edit Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="post" action="{{ url('products/' . $data->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
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
            // validator default
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
            });
        </script>
        <script>
            $(document).ready(function() {
                // get csrf token
                let csrf = $('meta[name="csrf-token"]').attr("content");
                let y = $('.getIndex').last().find('.index').val();

                $(function() {
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
                })

                // get warehouses
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

                // FORMAT BERAT BARANG
                $('.berat').on('change', function() {
                    var selection = window.getSelection().toString();
                    if (selection !== '') {
                        return;
                    }
                    if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                        return;
                    }
                    var $this = $(this);
                    var input = $this.val();
                    var input = input.replace(/[\D\s\._\-]+/g, "");
                    input = input ? parseInt(input, 10) : 0;
                    $this.val(function() {
                        return (input === 0) ? "" : input.toLocaleString("EN-en");
                    });
                    $this.next().val(input);
                });

                // FORMAT HARGA BELI
                $(function() {
                    let hargaInput = $('.harga_beli').val();
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
                    $('.harga_beli').val(hargaAkhir);

                })

                // FORMAT HARGA NON RETAIL
                $(function() {
                    let hargaInput = $('.harga_jual_nonretail').val();
                    let hargaPisah = hargaInput.split('.');
                    if (hargaPisah.length > 1) {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString(
                            "EN-en", {

                            });
                        hargaAkhir = hargaFloat + '.' + parseFloat(hargaPisah[1]);
                    } else {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah).toLocaleString(
                            "EN-en", {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        hargaAkhir = hargaFloat;
                    }
                    $('.harga_jual_nonretail').val(hargaAkhir);

                })

                // FORMAT HARGA RETAIL
                $('.hargaJual').find('.hargaJual_').each(function(index, value) {
                    let hargaInput = $(this).find('.harga_jual').val();
                    let hargaPisah = hargaInput.split('.');
                    if (hargaPisah.length > 1) {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString(
                            "EN-en", {

                            });
                        hargaAkhir = hargaFloat + '.' + parseFloat(hargaPisah[1]);
                    } else {
                        hargaPisah[0] = hargaPisah[0].replace(/,/g, '');
                        let hargaFloat = parseFloat(hargaPisah).toLocaleString(
                            "EN-en", {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        hargaAkhir = hargaFloat;
                    }
                    $(this).find('.harga_jual').val(hargaAkhir);
                });

                // FORMAT HARGA BELI SAAT DIUBAH
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

                // FORMAT HARGA NON RETAIL SAAT DIUBAH
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


                // FORMAT HARGA RETAIL SAAT DIUBAH
                $('.harga_jual').on('change', function() {
                    let hargaInput = $(this).val();
                    hargaInput = hargaInput.replace(/,/g, '');
                    // console.log('====================================');
                    // console.log(hargaInput);
                    // console.log('====================================');
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
                    $(this).next('.harga_jual_').val(hargaInput);
                });

                // add form dynamic
                $("#addTradeIn").on("click", function() {
                    ++y;

                    let form = `<div class="mx-auto py-2 form-group row " style="background-color: #f0e194">

                                    <div class="col-12 col-lg-5 form-group">
                                        <label>Baterry</label>
                                        <select name="tradeFields[${y}][id_warehouse]" class="form-control all_product_TradeIn" required>
                                            <option value="">-Choose Warehouse-</option>

                                         </select>

                                    </div>
                                    <div class="col-10 col-lg-5 form-group">
                                        <label>Retail Price <small class="badge badge-primary">(exclude PPN)</small></label>

                                        <input required type="text" class="form-control harga_jual"  placeholder="0">

                                        <input class="harga_jual_" type="hidden" name="tradeFields[${y}][harga_jual]">

                                    </div>
                                    <div class="col-2 col-md-2 form-group">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)"class="form-control text-white remTradeIn text-center"

                                        style="border:none; background-color:#dd5f6c">-</a>

                                    </div>
                                </div>`;
                    $("#formEdit").append(form);

                    let validator = $('form.needs-validation').jbvalidator({
                        errorMessage: true,
                        successClass: true,
                        language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                    });
                    //reload instance after dynamic element is added
                    validator.reload();
                    $('.harga_jual').on('change', function() {

                        let priceReal = $(this).val();

                        let price = priceReal.replace(/\./g, '');

                        let myPrice = price.split(',');

                        let finalPrice = '';

                        if (myPrice.length > 1) {

                            let myPrice_1 = parseInt(myPrice[0]).toLocaleString(

                                'id', {

                                    minimumFractionDigits: 0,

                                    maximumFractionDigits: 0

                                });

                            finalPrice = myPrice_1 + ',' + myPrice[1];

                        } else {

                            let myPrice_1 = parseInt(price).toLocaleString(

                                'id', {

                                    minimumFractionDigits: 0,

                                    maximumFractionDigits: 0

                                });

                            finalPrice = myPrice_1;

                        }

                        $(this).val(finalPrice);
                        $(this).siblings('.harga_jual_').val(price);

                    });
                    $(".all_product_TradeIn").select2({
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

                });

                // remove form dynamic
                $(document).on("click", ".remTradeIn", function() {
                    $(this).closest(".row").remove();
                });
            });
        </script>
    @endpush
@endsection
