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

                        <div class="form-group row col-12">
                            <div class="col-4">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="from_date" id="from_date">
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="form-control digits" type="date" data-language="en" placeholder="Start"
                                        name="to_date" id="to_date">
                                </div>
                            </div>
                            <div class="col-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary" name="filter" id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning" name="refresh" id="refresh">Refresh</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="ppn" id="ppn" value="{{ $ppn }}">
                        <div class="table-responsive">
                            <table id="example1" class="table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="2%">Action</th>
                                        <th>No</th>
                                        <th>Order Number</th>
                                        <th>Order Date</th>
                                        <th>Name</th>
                                        <th>Phone Number</th>
                                        <th>ID Card Number</th>
                                        <th>Email</th>
                                        <th>District</th>
                                        <th>Address</th>
                                        <th>Plate Number</th>
                                        <th>Car Brand</th>
                                        <th>Car Type</th>
                                        <th>Motocycle Brand</th>
                                        <th>Motocycle Type</th>
                                        <th>Remark</th>
                                        <th>Total (Exclude PPN)</th>
                                        <th>Total PPN</th>
                                        <th>Total (Include PPN)</th>
                                        <th>Paid Status</th>
                                        <th>Created By</th>

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
                            url: "{{ url('/retail/credit') }}",
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
                            },
                            {
                                width: '5%',
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center",
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'order_number',
                                name: 'order_number'

                            },
                            {
                                data: 'order_date',
                                name: 'order_date'

                            },
                            {
                                data: 'cust_name',
                                name: 'cust_name'

                            },
                            {
                                data: 'cust_phone',
                                name: 'cust_phone'
                            },
                            {
                                data: 'cust_ktp',
                                name: 'cust_ktp'
                            },
                            {
                                data: 'cust_email',
                                name: 'cust_email'
                            },
                            {
                                data: 'district',
                                name: 'district'
                            },
                            {
                                data: 'address',
                                name: 'address'
                            },
                            {
                                data: 'plate_number',
                                name: 'plate_number'
                            },
                            {
                                data: 'car_brand_id',
                                name: 'car_brand_id'
                            },
                            {
                                data: 'car_type_id',
                                name: 'car_type_id'
                            },
                            {
                                data: 'motor_brand_id',
                                name: 'motor_brand_id'
                            },
                            {
                                data: 'motor_type_id',
                                name: 'motor_type_id'
                            },
                            {
                                data: 'remark',
                                name: 'remark'
                            },
                            {
                                data: 'total_excl',
                                name: 'total_excl'
                            },
                            {
                                data: 'total_ppn',
                                name: 'total_ppn'
                            },
                            {
                                data: 'total_incl',
                                name: 'total_incl'
                            },
                            {
                                data: 'isPaid',
                                name: 'isPaid'
                            },
                            {
                                data: 'created_by',
                                name: 'created_by'
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
                                title: 'Data Sales Retail',
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
                // $(document).on("click", ".modal-btn2", function(event) {
                //     let csrf = $('meta[name="csrf-token"]').attr("content");

                //     // $(document).on("click", ".modal-btn2", function() {

                //     let modal_id = $(this).attr('data-bs-target');
                //     //Get Customer ID
                //     $(modal_id).find(".customer-select, .warehouse-select").select2({
                //         width: "100%",
                //     });
                //     let customer_id = $(modal_id).find('.customer-append').val();
                //     $(modal_id).find(".productSo-edit").select2({
                //         width: "100%",
                //         dropdownParent: modal_id,
                //         ajax: {
                //             context: this,
                //             type: "GET",
                //             url: "/products/select",
                //             data: function(params) {
                //                 return {
                //                     _token: csrf,
                //                     q: params.term, // search term
                //                     c: customer_id
                //                 };
                //             },
                //             dataType: "json",
                //             delay: 250,
                //             processResults: function(data) {
                //                 return {
                //                     results: $.map(data, function(item) {
                //                         return [{
                //                             text: item.nama_barang +
                //                                 " (" +
                //                                 item.type_name +
                //                                 ", " +
                //                                 item.nama_sub_material +
                //                                 ")",
                //                             id: item.id,
                //                         }, ];
                //                     }),
                //                 };
                //             },
                //         },
                //     });

                //     //Get Customer ID
                //     $(modal_id).find(".customer-append").change(function() {
                //         customer_id = $(modal_id).find(".customer-append").val();
                //     });
                //     let x = $(modal_id)
                //         .find('.modal-body')
                //         .find('.formSo-edit')
                //         .children('.form-group')
                //         .last()
                //         .find('.loop')
                //         .val();
                //     //Get discount depent on product
                //     $(modal_id).on("change", ".productSo-edit", function() {
                //         let product_id = $(this).val();
                //         let parent_product = $(this).parent('.form-group').siblings(
                //             '.form-group').find(
                //             ".discount-append-edit");
                //         $.ajax({
                //             context: this,
                //             type: "GET",
                //             url: "/discounts/select" + "/" + customer_id + "/" +
                //                 product_id,
                //             dataType: "json",
                //             success: function(data) {
                //                 if (data.discount != null) {
                //                     parent_product.val(data.discount);
                //                 } else {
                //                     parent_product.val(0);
                //                 }
                //             },
                //         });
                //     });
                //     var stokNow = $('.cekQty-edit').val();
                //     $(modal_id).on("input", ".cekQty-edit", function() {
                //         const qtyValue = $(this).val();
                //         let product_id = $(this).parent('.form-group').siblings(
                //             '.form-group').find(
                //             '.productSo-edit').val();
                //         let id = customer_id;
                //         $.ajax({
                //             context: this,
                //             type: "GET",
                //             url: "/stocks/cekQty/" + product_id,
                //             data: {
                //                 _token: csrf,
                //                 c: id,
                //             },
                //             dataType: "json",
                //             delay: 250,
                //             success: function(data) {
                //                 if (parseInt(qtyValue) > (parseInt(data.stock) +
                //                         parseInt(stokNow))) {
                //                     $(this).parent().find(".qty-warning")
                //                         .removeAttr(
                //                             "hidden");
                //                     $(this).addClass("is-invalid");
                //                 } else {
                //                     $(this)
                //                         .parent()
                //                         .find(".qty-warning")
                //                         .attr("hidden", "true");
                //                     $(this).removeClass("is-invalid");
                //                 }
                //             },
                //             error: function(XMLHttpRequest, textStatus,
                //                 errorThrown) {
                //                 alert("Status: " + textStatus);
                //                 alert("Error: " + errorThrown);
                //             },
                //         });
                //     });
                //     $(modal_id).on("click", ".addSo-edit", function() {
                //         ++x;
                //         var form =
                //             '<div class="mx-auto py-2 form-group row bg-primary">' +
                //             '<input type="hidden" class="loop" value="' + x + '">' +
                //             '<div class="form-group col-12 col-lg-6">' +
                //             "<label>Product</label>" +
                //             '<select name="editProduct[' +
                //             x +
                //             '][products_id]" class="form-control productSo-edit" required>' +
                //             '<option value="">Choose Product</option> ' +
                //             "</select>" +
                //             "</div>" +
                //             '<div class="col-4 col-lg-2 form-group">' +
                //             "<label> Qty </label> " +
                //             '<input type="number" class="form-control cekQty-edit" required name="editProduct[' +
                //             x +
                //             '][qty]">' +
                //             '<small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>' +
                //             "</div> " +
                //             '<div class="col-4 col-lg-2 form-group">' +
                //             "<label>Disc (%)</label>" +
                //             '<input type="number" class="form-control discount-append-edit" name="editProduct[' +
                //             x +
                //             '][discount]" id="">' +
                //             "</div>" +
                //             '<div class="col-1 col-md-2 form-group">' +
                //             '<label for=""> &nbsp; </label>' +
                //             '<a class="btn btn-danger form-control text-white remSo-edit text-center">' +
                //             "- </a> " +
                //             "</div>" +
                //             " </div>";
                //         $(modal_id).find(".formSo-edit").append(form);

                //         $(modal_id).find(".productSo-edit").select2({
                //             width: "100%",
                //             dropdownParent: modal_id,
                //             ajax: {
                //                 type: "GET",
                //                 url: "/products/select",
                //                 data: function(params) {
                //                     return {
                //                         _token: csrf,
                //                         q: params.term, // search term
                //                         c: customer_id
                //                     };
                //                 },
                //                 dataType: "json",
                //                 delay: 250,
                //                 processResults: function(data) {
                //                     return {
                //                         results: $.map(data, function(item) {
                //                             return [{
                //                                 text: item
                //                                     .nama_barang +
                //                                     " (" +
                //                                     item
                //                                     .type_name +
                //                                     ", " +
                //                                     item
                //                                     .nama_sub_material +
                //                                     ")",
                //                                 id: item.id,
                //                             }, ];
                //                         }),
                //                     };
                //                 },
                //             },
                //         });
                //     });

                //     //remove Sales Order fields
                //     $(modal_id).on("click", ".remSo-edit", function() {
                //         $(this).closest(".row").remove();
                //     });

                //     //reload total
                //     $(modal_id).on('click', '.btn-reload', function() {
                //         let ppn = 0;
                //         let total = 0;
                //         let total_after_ppn = 0;
                //         $(modal_id).find('.productSo-edit').each(function() {
                //             let product_id = $(this).val();
                //             let cost = function() {
                //                 let temp = 0;
                //                 $.ajax({
                //                     async: false,
                //                     context: this,
                //                     type: "GET",
                //                     url: "/products/selectCost/" +
                //                         product_id,
                //                     dataType: "json",
                //                     success: function(data) {
                //                         temp = data
                //                             .harga_jual_nonretail
                //                     },
                //                 });
                //                 return temp;
                //             }();

                //             let qty = $(this).parent().siblings().find(
                //                 '.cekQty-edit').val();
                //             let disc = $(this).parent().siblings().find(
                //                     '.discount-append-edit')
                //                 .val() / 100;
                //             let disc_cost = cost * disc;
                //             let cost_after_disc = cost - disc_cost;
                //             total = total + (cost_after_disc * qty);
                //             //   alert($(this).parent().siblings().find('.cekQty-edit').val());
                //         });

                //         ppn = total * $('#ppn').val();
                //         total_after_ppn = total + ppn;
                //         $(this).closest('.row').siblings().find('.ppn').val('Rp. ' + Math
                //             .round(ppn)
                //             .toLocaleString('id', {
                //                 minimumFractionDigits: 0,
                //                 maximumFractionDigits: 0
                //             }));
                //         $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math
                //             .round(total)
                //             .toLocaleString(
                //                 'id', {
                //                     minimumFractionDigits: 0,
                //                     maximumFractionDigits: 0
                //                 }));
                //         $(this).closest('.row').siblings().find('.total-after-ppn').val(
                //             'Rp. ' + Math
                //             .round(
                //                 total_after_ppn).toLocaleString('id', {
                //                 minimumFractionDigits: 0,
                //                 maximumFractionDigits: 0
                //             }));
                //     });
                //     $(modal_id).on('hidden.bs.modal', function() {
                //         $(modal_id).unbind();
                //     });
                // });
                // });
            });
        </script>
    @endpush
@endsection
