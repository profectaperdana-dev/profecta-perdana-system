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

                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
                //reload instance after dynamic element is added
                validator.reload();
            })
        </script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                y = 0;

                // get warehouses
                $(".all_product_TradeIn").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/get_warehouse/",
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
                //* format harga_beli
                $('#berat').on('keyup', function() {
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
                    $('#berat_').val(input);
                });
                // format harga beli
                $('#harga_beli').on('keyup', function() {
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
                    $('#harga_beli_').val(input);
                });
                // format harga beli
                $('.harga_jual').on('keyup', function() {
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
                    $('.harga_jual_').val(input);
                });
                $('.harga_jual_nonretail').on('keyup', function() {
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
                    $('.harga_jual_nonretail_').val(input);
                });
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

                // dinamic retail price
                //* FUNCTION JS DINAMIC *//
                $("#addTradeIn").on("click", function() {
                    ++y;
                    let form = ` <div class="mx-auto py-2 form-group row bg-primary">
                <div class="form-group col-5">
                    <label>Baterry</label>
                    <select name="tradeFields[${y}][id_warehouse]" class="form-control all_product_TradeIn" required>
                        <option value="">-Choose Warehouse-</option>
                    </select>
                </div>
                
                <div class="col-5 form-group">
                    <label>Retail Price</label>
                    <input class="form-control total" value="{{ old('harga_jual', number_format($data->harga_jual, 0, ',', '.')) }}"  id="">
                    <input type="hidden" name="tradeFields[${y}][harga_jual]" >
                </div>
                <div class="col-2 col-md-2 form-group">
                    <label for="">&nbsp;</label>
                    <a href="javascript:void(0)"class="form-control text-white remTradeIn text-center"
                     style="border:none; background-color:red">-</a>
                </div>

            </div>`;
                    $("#formTradeIn").append(form);


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
                            url: "/get_warehouse/",
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
                $(document).on("click", ".remTradeIn", function() {
                    $(this).closest(".row").remove();
                });
            });
        </script>
    @endpush
@endsection
