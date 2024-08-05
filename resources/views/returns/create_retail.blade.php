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
                        <div class="mb-3 row box-select-all justify-content-end">
                            <button class="col-1 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                        </div>
                        <form method="post" action="{{ url('return_retail/store_retail') }}" enctype="multipart/form-data">
                            @csrf
                            @include('returns._form_retail')
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
                    width: '100%'
                });

                $('.dotReturn').select2({
                    placeholder: 'DOT',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%'
                });
                $('.return_reason1').change(function() {
                    let return_reason1 = $(this).val();
                    if (return_reason1 == "Other") {
                        $('.return_reason2').attr('hidden', true);
                        $('.return_reason2').find('select[name="return_reason2"]').attr('required', false);
                        $('.other').attr('hidden', false);
                        $('.other').find('textarea[name="return_reason"]').attr('required', true);
                    } else {
                        $('.return_reason2').attr('hidden', false);
                        $('.return_reason2').find('select[name="return_reason2"]').attr('required', true);
                        $('.other').attr('hidden', true);
                        $('.other').find('textarea[name="return_reason"]').attr('required', false);
                    }
                });

                let retail_id = $('#retail_id').val();

                $(".productReturn").select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/retail/selectReturn",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                r: retail_id
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_sub_material + " " + item
                                            .type_name + " " + item.nama_barang,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                $(document).on('change', '.productReturn', function() {
                    let product_id = $(this).val();
                    let this_index = $(this).parent().parent().siblings('.loop').val();

                    // console.log(this_index);
                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/retail/getQtyDetail",
                        data: {
                            _token: csrf,
                            r: retail_id,
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

                    // console.log(product_id);
                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/retail/getDot",
                        data: {
                            _token: csrf,
                            r: retail_id,
                            p: product_id
                        },
                        dataType: "json",
                        success: function(data) {
                            $(this).parent().siblings('.parentDot').html('');
                            let fullDotelement = ``;
                            let dotElement = `<div class="row parentRow" data-dotIndex="0">
                                <label for="DOT">DOT</label>
                                <div class="col-4 col-lg-3 form-group">
                                    <select multiple name="returnFields[${this_index}][0][dot]"
                                        class="form-control dotReturn" required>
                                        
                                            `;
                            let dot_list = ``;
                            data.dots.forEach(element => {
                                dot_list += `<option value="${element.id}">${element.dot}
                                            </option>`;
                            });

                            fullDotelement += dotElement + dot_list + `</select></div>`


                            fullDotelement += `<div class="col-4 col-lg-3 form-group">
                                <input type="text" class="form-control"
                                    name="returnFields[${this_index}][0][qtyDot]" id=""
                                    placeholder="Qty" required>
                                </div>
                                <div class="col-4 col-lg-2 form-group">
                                    <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                                        style="border:none; background-color:#276e61">+</a>
                                </div>
                            </div>
                            `;
                            // console.log(data);
                            $(this).parent().siblings('.parentDot').append(fullDotelement);

                            $('.dotReturn').select2({
                                placeholder: 'DOT',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%'
                            });
                            // console.log($(this).parent().siblings('.parentDot').html());
                        },
                    });
                });

                let dot_index = 0;
                $(document).on('click', '.addDot', function() {
                    let dotIndex = $(this).closest('.parentDot').find('.parentRow').length;
                    let loopIndex = parseInt($(this).closest('.parentDot').parent().prev().val());
                    let dotList = $(this).closest('.parentRow').find('.dotReturn').html()
                    // console.log(loopIndex);
                    let newElementDot = `<div class="row parentRow" data-dotIndex="${dotIndex}">
                                <div class="col-4 col-lg-3 form-group mt-0">
                                    <select multiple name="returnFields[${loopIndex}][${dotIndex}][dot]"
                                        class="form-control dotReturn" required>
                                       
                                       ` + dotList + `</select>
                                </div>
                                <div class="col-4 col-lg-3 form-group">
                                    <input type="text" class="form-control"
                                        name="returnFields[${loopIndex}][${dotIndex}][qtyDot]" id=""
                                        placeholder="Qty" required>
                                </div>
                                <div class="col-4 col-lg-2 form-group">
                                    <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                                        style="border:none; background-color:#276e61">+</a>
                                </div>
                                <div class="col-4 col-lg-2 form-group">
                                    <a href="javascript:void(0)" class="form-control text-white text-center remDot"
                                        style="border:none; background-color:crimson">-</a>
                                </div>
                                `;
                    $(this).closest('.parentDot').append(newElementDot);

                    $('.dotReturn').select2({
                        placeholder: 'DOT',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%'
                    });
                });

                let x = $('.loop').last().val();
                $("#addReturn").on("click", function() {
                    ++x;
                    let form =
                        '<input type="hidden" name="loop" value="' + x + '" class="loop">' +
                        '<div class="form-group row rounded pt-2 mb-3 mx-auto" style="background-color: #f0e194">' +
                        '<div class="form-group col-12 col-lg-7">' +
                        "<label>Product</label>" +
                        '<select multiple name="returnFields[' +
                        x +
                        '][product_id]" class="form-control productReturn" required>' +

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
                        '<div class="row parentDot">' +
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
                            url: "/retail/selectReturn",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    r: retail_id
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
                    });

                    $(document).on('change', '.productReturn', function() {
                        let product_id = $(this).val();

                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/retail/getQtyDetail",
                            data: {
                                _token: csrf,
                                r: retail_id,
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

                $(document).on("click", ".remDot", function() {
                    $(this).closest(".row").remove();
                });

            });
        </script>
    @endpush
@endsection
