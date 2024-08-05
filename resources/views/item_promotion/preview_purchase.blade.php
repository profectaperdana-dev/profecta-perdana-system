@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            table .table-non-border {
                border: none !important;
            }

            @media print {
                h6 .no-print {
                    display: none !important;
                }
            }

            .example1 .dataTables_filter {
                float: left !important;
                text-align: left !important;
            }

            .example1 .dataTables_filter input[type="search"] {
                width: 100% !important;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="font-weight-bold">{{ $title }}</h3>

                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class=" rounded shadow card">
                    <div class="card-body">
                        <div class="form-group row ">
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date" data-value="{{ date('d-m-Y') }}"
                                        name="to_date" autocomplete="on">
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
                            <table id="" class="example1 table table-sm table-borderless table-striped"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th>No</th>
                                        <th><span hidden>Detail</span></th>
                                        <th>Order&nbsp;Number </th>
                                        <th>Date</th>
                                        <th>Vendor</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center">Total</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script>
            $(document).ready(function() {
                //set date
                $('.datepicker-here').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });

                function parseDate(date) {
                    let now = date;
                    // Format the date as "dd-mm-yyyy"
                    let day = now.getDate().toString().padStart(2, '0');
                    let month = (now.getMonth() + 1).toString().padStart(2, '0');
                    let year = now.getFullYear();
                    let formattedDate = `${day}-${month}-${year}`;
                    return formattedDate;
                }
                // Get the current date


                // Set the value of the input element
                document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const format = (d) => {
                    return `
                            <div style="margin-left:2px;" class="row col-lg-3 card shadow">
                                    <table class="table fw-bold" style="border:0;" border="0">
                                        <tr>
                                            <td>Remark</td>
                                            <td>:</td>
                                            <td>${d.remark}</td>
                                        </tr>
                                        <tr>
                                            <td>Created by</td>
                                            <td>:</td>
                                            <td>${d.created_by}</td>
                                        </tr>
                                    </table>
                            </div>
                        `;
                };

                // load data from server
                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('.example1').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,

                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('/material-promotion/purchase/preview') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [{
                                    width: '5%',
                                    data: 'DT_RowIndex',
                                    name: 'DT_Row_Index',
                                    "className": "text-center fw-bold",
                                }, {
                                    data: null,
                                    orderable: false,
                                    searchable: false,
                                    className: 'details-control',
                                    defaultContent: '<i data-feather="plus"></i>'
                                },

                                {
                                    width: '5%',
                                    data: 'action',
                                    name: 'action',
                                    orderable: true,
                                    searchable: true,

                                }, {
                                    className: "text-nowrap text-center",
                                    data: 'order_date',
                                    name: 'order_date'

                                }, {
                                    className: "text-nowrap text-center",
                                    data: 'supplier_id',
                                    name: 'supplier_id',
                                    search: function(searchTerm, cellData) {
                                        // console.log(searchTerm);
                                        return cellData.toLowerCase().includes(searchTerm.toLowerCase());
                                    }
                                },

                                {
                                    className: "text-end",
                                    data: 'total',
                                    name: 'total',
                                }
                            ]

                            ,
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            // PPN
                            var visibleData = api.column(5).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    // let raw2 = raw1[0].split(".");
                                    raw2 = raw1.join('');
                                    // raw2 = raw2 + '.' + raw1[1];
                                    totalPPN += parseInt(raw2);
                                }
                            });


                            $(api.column(5).footer()).html(totalPPN.toLocaleString('en', {}));
                        },


                        initComplete: function() {
                            var table = $('.example1').DataTable();
                            $(document).find('.example1 tbody').off().on('click', 'td.details-control',
                                function() {
                                    var tr = $(this).closest('tr');
                                    var row = table.row(tr);

                                    if (row.child.isShown()) {
                                        // This row is already open - close it
                                        row.child.hide();
                                        tr.removeClass('shown');
                                    } else {
                                        // Open this row
                                        row.child(format(row.data())).show();
                                        tr.addClass('shown');
                                    }
                                });
                        },
                        drawCallback: function(settings) {
                            // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                            $('#thisModal').html('');
                            $('.currentModal').each(function(){
                                let currentModal = $(this).html();
                                $(this).html('');
                                $('#thisModal').append(currentModal);
                            });
                            
                            // console.log($('#currentModal').html());
                            // Lakukan tindakan lain yang Anda inginkan di sini
                        },


                    });

                }

                // filter data
                $('#filter').click(function() {
                    function formatDate(date) {
                        // Split the date string into day, month, and year components
                        let dateParts = date.split('-');

                        // Create a new Date object using the year, month, and day components
                        let dateObject = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

                        // Format the date as "yyyy-mm-dd"
                        let year = dateObject.getFullYear();
                        let month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
                        let day = dateObject.getDate().toString().padStart(2, '0');
                        let formattedDate = `${year}-${month}-${day}`;

                        return formattedDate;
                    }

                    var from_date = formatDate($('#from_date').val());
                    var to_date = formatDate($('#to_date').val());
                    if (from_date != '' && to_date != '') {
                        $('.example1').DataTable().destroy();

                        load_data(from_date, to_date);
                    } else {
                        $.notify({
                            title: 'Warning !',
                            message: 'Please Select Start Date & End Date'
                        }, {
                            type: 'warning',
                            allow_dismiss: true,
                            newest_on_top: true,
                            mouse_over: true,
                            showProgressbar: false,
                            spacing: 10,
                            timer: 3000,
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
                    }
                });

                // refresh data
                $('#refresh').click(function() {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('.example1').DataTable().destroy();
                    load_data();
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    $(document).on('click', '.btn-delete', function() {
                        $(this).addClass('disabled');
                    });
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
                    // $(document).on("click", ".modal-btn2", function() {

                    let modal_id = $(this).attr('data-bs-target');
                    let warehouse_id = $(modal_id).find('.warehouse').val();
                    //Get Customer ID
                    $(modal_id).find(" .warehouse-select").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                    });
                    let customer_id = $(modal_id).find('.customer-append').val();

                    // get customer data
                    $(modal_id).find('.customer-select').select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/customer/select/",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: [{
                                        text: 'Other',
                                        id: 'other'
                                    }].concat($.map(data, function(item) {
                                        return {
                                            text: item
                                                .code_cust +
                                                ' - ' +
                                                item.name_cust,
                                            id: item.id,
                                        };
                                    })),
                                };
                            },
                        },
                    });

                    $(modal_id).find(".productSo-edit").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/material-promotion/select",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouse_id
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

                    //Get Customer ID
                    $(modal_id).find(".customer-append").change(function() {
                        customer_id = $(modal_id).find(".customer-append").val();
                        if (customer_id == 'other') {
                            $(modal_id).find('.cust-name').attr('hidden', false);
                        } else {
                            $(modal_id).find('.cust-name').attr('hidden', true);
                        }
                        // console.log(customer_id);
                    });
                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('.formSo-edit')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();
                    //Get discount depent on product
                    $(modal_id).on("change", ".productSo-edit", function() {
                        let product_id = $(this).val();

                    });
                    var stokNow = $('.cekQty-edit').val();
                    $(modal_id).on("input", ".cekQty-edit", function() {
                        const qtyValue = $(this).val();
                        let product_id = $(this).parent('.form-group').siblings(
                            '.form-group').find(
                            '.productSo-edit').val();
                        let id = customer_id;
                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "/material-promotion/cekQty/" + product_id,
                            data: {
                                _token: csrf,
                                w: warehouse_id,
                            },
                            dataType: "json",
                            delay: 250,
                            success: function(data) {
                                if (parseInt(qtyValue) > (parseInt(data.qty) +
                                        parseInt(stokNow))) {
                                    $(this).parent().find(".qty-warning")
                                        .removeAttr(
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
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("Status: " + textStatus);
                                alert("Error: " + errorThrown);
                            },
                        });
                    });
                    $(document).off("click", ".addSo-edit");
                    $(document).on("click", ".addSo-edit", function() {
                        ++x;
                        var form =
                            '<div class="mx-auto py-2 form-group rounded row " style="background-color: #f0e194">' +
                            '<input type="hidden" class="loop" value="' + x + '">' +
                            '<div class="form-group col-12 col-lg-6">' +
                            "<label>Product</label>" +
                            '<select multiple name="editProduct[' +
                            x +
                            '][products_id]" class="form-control productSo-edit" required>' +

                            "</select>" +
                            "</div>" +
                            '<div class="col-4 col-lg-4 form-group">' +
                            "<label> Qty </label> " +
                            '<input type="number" class="form-control cekQty-edit" value="0" placeholder="Qty" required name="editProduct[' +
                            x +
                            '][qty]">' +
                            '<small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>' +
                            "</div> " +
                            '<div class="col-6 col-lg-1 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="btn form-control text-white addSo-edit text-center" style="border:none; background-color:#276e61">' +
                            "+ </a> " +
                            "</div>" +
                            '<div class="col-6 col-lg-1 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="btn form-control text-white remSo-edit text-center" style="border:none; background-color:#d94f5c">' +
                            "- </a> " +
                            "</div>" +
                            " </div>";
                        $(modal_id).find(".formSo-edit").append(form);

                        $(modal_id).find(".productSo-edit").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            ajax: {
                                type: "GET",
                                url: "/material-promotion/select",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        w: warehouse_id
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
                        $(modal_id).find(".productSo-edit").last().select2("open");

                    });

                    //remove Sales Order fields
                    $(modal_id).on("click", ".remSo-edit", function() {
                        $(this).closest(".row").remove();
                    });

                    //reload total
                    // $(modal_id).on('click', '.btn-reload', function() {
                    //     console.log('test')
                    //     let ppn = 0;
                    //     let total = 0;
                    //     let total_after_ppn = 0;
                    //     $(modal_id).find('.productSo-edit').each(function() {
                    //         let product_id = $(this).val();
                    //         let cost = function() {
                    //             let temp = 0;
                    //             $.ajax({
                    //                 async: false,
                    //                 context: this,
                    //                 type: "GET",
                    //                 url: "/products/selectCost/" +
                    //                     product_id,
                    //                 dataType: "json",
                    //                 success: function(data) {
                    //                     temp = parseInt(data
                    //                         .harga_jual_nonretail)
                    //                 },
                    //             });
                    //             return temp;
                    //         }();

                    //         let qty = $(this).parent().siblings().find(
                    //             '.cekQty-edit').val();
                    //         let disc = parseFloat($(this).parent().siblings().find(
                    //                 '.discount-append-edit')
                    //             .val().replace(",", ".")) / 100;
                    //         let disc_rp = $(this).parent().siblings().find('.discount_rp')
                    //             .val();
                    //         ppn = cost * $('#ppn').val();
                    //         let ppn_cost = cost + ppn;
                    //         let disc_cost = ppn_cost * disc;
                    //         let cost_after_disc = ppn_cost - disc_cost - disc_rp;
                    //         total = total + (cost_after_disc * qty);

                    //     });

                    //     total_after_ppn = total;
                    //     $(this).closest('.row').siblings().find('.ppn').val('Rp. ' + Math
                    //         .round(total_after_ppn / 1.11 * $('#ppn').val())
                    //         .toLocaleString('en', {
                    //             minimumFractionDigits: 0,
                    //             maximumFractionDigits: 0
                    //         }));
                    //     $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math
                    //         .round(total_after_ppn / 1.11)
                    //         .toLocaleString(
                    //             'en', {
                    //                 minimumFractionDigits: 0,
                    //                 maximumFractionDigits: 0
                    //             }));
                    //     $(this).closest('.row').siblings().find('.total-after-ppn').val(
                    //         'Rp. ' + Math
                    //         .round(
                    //             total_after_ppn).toLocaleString('en', {
                    //             minimumFractionDigits: 0,
                    //             maximumFractionDigits: 0
                    //         }));
                    // });
                    $(modal_id).on('hidden.bs.modal', function() {
                        $(modal_id).unbind();
                    });
                });
            });
        </script>
    @endpush
@endsection
