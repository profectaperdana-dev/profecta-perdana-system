@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                        {{ $title }}
                    </h6> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="from_date" id="from_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="to_date" id="to_date">
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary form-control text-white" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning form-control text-white" name="refresh"
                                        id="refresh">Refresh</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="2%">action</th>
                                        <th>No</th>
                                        <th>Number</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>NIK</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Total (Rp)</th>
                                        <th>By</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <input type="text" hidden value="{{ $ }}"> --}}
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>

        <script>
            $(document).ready(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#example1').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/retail_second_products') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [{
                                width: '5%',
                                data: 'action',
                                name: 'action',
                                orderable: false,
                            }, {
                                width: '5%',
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center",
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'second_sale_number',
                                name: 'second_sale_number',

                            },
                            {
                                data: 'second_sale_date',
                                name: 'second_sale_date',

                            },
                            {
                                data: 'customer_name',
                                name: 'customer_name',

                            },
                            {
                                data: 'customer_nik',
                                name: 'customer_nik',
                            },
                            {
                                data: 'customer_phone',
                                name: 'customer_phone',
                            },
                            {
                                data: 'customer_email',
                                name: 'customer_email',
                            },
                            {
                                data: 'total',
                                name: 'total',

                            },
                            {
                                data: 'secondSaleBy',
                                name: 'secondSaleBy',
                            },

                        ],
                        order: [
                            [0, 'desc']
                        ],
                        dom: 'Bfrtip',
                        lengthMenu: [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show All']
                        ],
                        buttons: ['pageLength',
                            {
                                title: 'Data Invoice',
                                messageTop: '<h5>{{ $title }} ({{ date('l H:i A, d F Y ') }})</h5><br>',
                                messageBottom: '<strong style="color:red;">*Please select only the type of column needed when printing so that the print is neater</strong>',
                                extend: 'print',
                                customize: function(win) {
                                    $(win.document.body)
                                        .css('font-size', '10pt')
                                        .prepend(
                                            '<img src="{{ asset('images/logo.png') }}" style="position:absolute; top:300; left:150; bottom:; opacity: 0.2;"/>'
                                        );
                                    $(win.document.body)
                                        .find('thead')
                                        .css('background-color', 'rgba(211,225,222,255)')
                                        .css('font-size', '8pt')
                                    $(win.document.body)
                                        .find('tbody')
                                        .css('background-color', 'rgba(211,225,222,255)')
                                        .css('font-size', '8pt')
                                    $(win.document.body)
                                        .find('table')
                                        .css('width', '100%')
                                },
                                orientation: 'landscape',
                                pageSize: 'legal',
                                exportOptions: {
                                    columns: ':visible'
                                },
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            'colvis'
                        ],

                    });
                }
                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if (from_date != '' && to_date != '') {
                        $('#example1').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        alert('Both Date is required');
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#example1').DataTable().destroy();
                    load_data();
                });

                // Edit Invoice
                $(document).on("click", ".modal-btn2", function(event) {


                    $('.split_rp').on('keyup', function() {
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
                    let validator = $('form.needs-validation').jbvalidator({
                        errorMessage: true,
                        successClass: true,
                        language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                    });
                    validator.reload();



                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    // $(document).on("click", ".modal-btn2", function() {
                    let modal_id = $(this).attr('data-bs-target');
                    // let id_product = $(modal_id).find('.id_product').val();
                    // console.log(id_product);

                    // $(modal_id).on('change', '.id_product', function() {
                    //     id_product = $(this).val();
                    //     console.log(id_product);

                    // });
                    var stokNow = $('.cekQty-edit').val();
                    $(modal_id).on("input", ".cekQty-edit", function() {
                        const qtyValue = $(this).val();
                        let product_id = $(this).parent('.form-group').prev().find('.productSo-edit')
                            .val();
                        console.log(product_id);
                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/retail_second_products/cekQty/" + product_id,
                            data: {
                                _token: csrf,
                            },
                            dataType: "json",
                            success: function(data) {
                                if (parseInt(qtyValue) > parseInt(data.qty)) {
                                    $(this).parent().find(".qty-warning").removeAttr(
                                        "hidden");
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
                    // $(modal_id).on("input", ".cek_stock", function() {
                    //     let qtyValue = $(this).val();

                    //     $.ajax({
                    //         context: this,
                    //         type: "GET",
                    //         url: "/retail_second_products/cekQty/" + id_product,
                    //         data: {
                    //             _token: csrf,
                    //         },
                    //         dataType: "json",
                    //         success: function(data) {
                    //             if (parseInt(qtyValue) > parseInt(data.qty)) {
                    //                 $(this).parent().find(".qty-warning").removeAttr(
                    //                     "hidden");
                    //                 $(this).addClass("is-invalid");
                    //             } else {
                    //                 $(this)
                    //                     .parent()
                    //                     .find(".qty-warning")
                    //                     .attr("hidden", "true");
                    //                 $(this).removeClass("is-invalid");
                    //             }
                    //         },
                    //     });
                    // });



                    //Get Customer ID
                    console.log(modal_id);
                    $(modal_id).find(".productSo-edit").select2({
                        dropdownParent: modal_id,
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


                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('.formSo-edit')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();
                    //Get discount depent on product

                    $(modal_id).on("click", ".addSo-edit", function() {
                        console.log(x);

                        ++x;
                        var form = ` <div class="mx-auto py-2 form-group row bg-primary">
                                         <div class="form-group col-6 col-md-4">
                                            <label>Baterry</label>
                                            <select name="tradeFields[${x}][product_trade_in]" class="form-control productSo-edit id_product" required>
                                             <option value="">--Choose Battery--</option>
                                            </select>
                                        </div>
                                        <div class="col-6 col-md-2 form-group">
                                            <label>Qty</label>
                                            <input required class="form-control cekQty-edit cek_stock" required name="tradeFields[${x}][qty]" id="">
                                            <small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>
                                        </div>
                                        <div class="col-4 col-md-2 form-group">
                                            <label>Disc (%)</label>
                                            <input type="number" required value="0" class="form-control disc_persen"  name="tradeFields[${x}][disc_percent]">
                                        </div>
                                        <div class="col-5 col-md-2 form-group">
                                            <label>Disc (Rp)</label>
                                            <input  type="text" value="0" required class="form-control disc_rp split_rp" class="" >
                                            <input type="hidden" value="0" name="tradeFields[${x}][disc_rp]" class="discountRp">
                                        </div>
                                        <div class="col-3 col-md-2 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"class="form-control text-white remSo-edit text-center"
                                            style="border:none; background-color:red">-</a>
                                        </div>

                                    </div>`;
                        $(modal_id).find(".formSo-edit").append(form);
                        $('.split_rp').on('keyup', function() {
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
                                return (input === 0) ? "" : input.toLocaleString(
                                    "id-ID");
                            });
                            $this.next().val(input);
                        });
                        $(modal_id).find(".productSo-edit").select2({
                            width: "100%",
                            dropdownParent: modal_id,
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
                                                text: item
                                                    .name_product_trade_in,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    });

                    //remove Sales Order fields
                    $(modal_id).on("click", ".remSo-edit", function() {
                        $(this).closest(".row").remove();
                    });

                    //reload total
                    $(modal_id).on('click', '.btn-reload', function() {
                        let total = 0;
                        $(modal_id).find('.productSo-edit').each(function() {
                            let product_id = $(this).val();
                            let cost = function() {
                                let temp = 0;
                                $.ajax({
                                    async: false,
                                    context: this,
                                    type: "GET",
                                    url: "/tradein/selectCost/" +
                                        product_id,
                                    dataType: "json",
                                    success: function(data) {
                                        temp = data
                                            .price_product_trade_in
                                    },
                                });
                                // console.log(temp);
                                return temp;
                            }();

                            var disc_persen = $(this).closest('.row').find('.disc_persen')
                                .val();
                            var disc_rp = $(this).parent().siblings().find(
                                '.discountRp').val();
                            let qty = $(this).parent().siblings().find(
                                '.cekQty-edit').val();
                            // console.log(qty);


                            if (disc_persen == null) {
                                disc_persen = 0;
                            }

                            if (disc_rp == null) {
                                disc_rp = 0;
                            }
                            let disc_cost = cost * disc_persen / 100;
                            let cost_after_disc = cost - disc_cost;


                            let discount = parseInt(cost_after_disc) - parseInt(disc_rp);
                            total = total + (discount * qty);
                            console.log('diskon rp =' + disc_rp);
                            console.log('diskon% =' + disc_persen);

                            // console.log(total);
                        });

                        $(this).closest('.row').siblings().find('.total_save').val(total);
                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math
                            .round(total)
                            .toLocaleString(
                                'id', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }));
                    });
                    $(modal_id).on('hidden.bs.modal', function() {
                        $(modal_id).unbind();
                    });
                });
            });
        </script>
    @endpush
@endsection
