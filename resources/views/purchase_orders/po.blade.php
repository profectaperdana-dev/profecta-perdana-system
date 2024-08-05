@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')

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
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }} --}}
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
                        {{-- <h5>All Data Purchase Order</h5> --}}
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
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
                            <table id="example"
                                class="display table table-sm table-borderless table-striped expandable-table"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center table-success text-nowrap">
                                        <th>#</th>
                                        <th>PO Number</th>
                                        <th>Date</th>
                                        <th>Vendor</th>
                                        <th>Warehouse</th>
                                        <th>Total (Incl. PPN)</th>
                                        <th>Receiving</th>
                                        <th>Settlement</th>
                                        {{-- <th>Total</th> --}}
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
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
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
                $('.datepicker-here').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });


                // Set the value of the input element
                document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#example').DataTable({
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
                        destroy: true,
                        ajax: {
                            url: "{{ url('/all_purchase_orders') }}",
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
                                orderable: false,
                                searchable: false
                            }, {
                                className: '',
                                width: '5%',
                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            }, {
                                className: 'text-nowrap',
                                width: '5%',
                                data: 'order_date',
                                name: 'order_date',
                                orderable: true,
                                searchable: true
                            }, {
                                className: 'text-nowrap',

                                data: 'supplier_id',
                                name: 'supplier_id',
                                search: function(searchTerm, cellData) {
                                    // console.log(searchTerm);
                                    return cellData.toLowerCase().includes(searchTerm.toLowerCase());
                                }

                            }, {
                                className: 'text-nowrap',

                                data: 'warehouse_id',
                                name: 'warehouse_id',
                                search: function(searchTerm, cellData) {
                                    // console.log(searchTerm);
                                    return cellData.toLowerCase().includes(searchTerm.toLowerCase());
                                }
                            }

                            , {
                                className: 'text-center',
                                data: 'total',
                                name: 'total',
                            }, {
                                className: 'text-center',
                                data: 'isvalidated',
                                name: 'isvalidated',
                            }, {

                                className: 'text-center',
                                data: 'isPaid',
                                name: 'isPaid',
                            },

                        ],
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
                        $('#example').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        alert('Both Date is required');
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#example').DataTable().destroy();
                    load_data();
                });


                let date = new Date();
                let date_now = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
                // $('#example').DataTable({
                //   dom: 'Bfrtip',
                //   buttons: [{
                //       title: 'All Purchase Orders (' + date_now + ')',
                //       extend: 'pdf',
                //       pageSize: 'A4',
                //       exportOptions: {
                //         columns: ':visible'
                //       },
                //       orientation: 'landscape',
                //       customize: function(doc) {
                //         doc.styles.tableHeader.alignment = 'left';
                //         doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split(
                //           '');
                //       },
                //     },
                //     {
                //       title: 'All Purchase Orders (' + date_now + ')',
                //       extend: 'print',
                //       orientation: 'landscape',
                //       exportOptions: {
                //         columns: ':visible'
                //       },
                //     },
                //     {
                //       title: 'All Purchase Orders (' + date_now + ')',
                //       extend: 'excel',
                //       exportOptions: {
                //         columns: ':visible'
                //       }
                //     },
                //     'colvis'
                //   ]
                // });
                $(document).on('click', '.btn-delete', function() {
                    $(this).addClass('disabled');
                });

                $(document).on('submit', 'form', function(e) {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);
                        // e.preventDefault(); // prevent form submission
                    }
                });

                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");

                    let modal_id = $(this).attr('data-bs-target');
                    
                    $(modal_id).find('.datepicker-here').datepicker({
                        dropdownParent: $(modal_id),
                        onSelect: function(formattedDate, date, inst) {
                            inst.hide();
                        },
                    });
                    
                    $(modal_id).find('.datepicker-here').val(
                        $(modal_id).find('.datepicker-here').attr('data-value'));
                    
                    $(modal_id).find('.jumlahTotal').on('click', function() {

                        let jumlahQty = $(this).parent('.form-group').siblings('.form-group').find(
                            ".jumlahQty").val();
                        let jumlahPrice = $(this).parent('.form-group').siblings('.form-group').find(
                            ".jumlahPrice").val();
                        let jumlahDisc = $(this).parent('.form-group').siblings('.form-group').find(
                            ".jumlahDisc").val() / 100;
                        console.log(jumlahDisc);
                        let jumlahHargaDiskon = jumlahPrice * jumlahDisc;
                        let jumlahHargaAfterDisc = jumlahPrice - jumlahHargaDiskon;
                        let totalHarga = parseInt(jumlahHargaAfterDisc * jumlahQty);
                        console.log(totalHarga);
                        $(this).val(totalHarga.toLocaleString('en'));
                    });
                    $(modal_id).find(".supplier-select, .warehouse-select").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                    });

                    let selected_warehouse = $('#warehouse option:selected').val();
                    $(modal_id).find('.warehouse-select').change(function() {
                        selected_warehouse = $('.warehouse-select').val();
                        $(modal_id).find(".productPo").empty().trigger('change');
                    });
                    //Get Customer ID
                    $(modal_id).find(".productPo").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
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
                                            text: item
                                                .nama_sub_material +
                                                " " + item.type_name +
                                                " " + item.nama_barang,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('.formPo')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();
                    $(document).off("click", ".addPo");
                    $(document).on("click", ".addPo", function() {
                        ++x;
                        let form =
                            '<div class="form-group rounded row pt-2 mb-3 mx-auto" style="background-color: #f0e194">' +
                            '<div class="form-group col-12 col-lg-4">' +
                            "<label>Product</label>" +
                            '<select name="poFields[' +
                            x +
                            '][product_id]" multiple class="form-control productPo" required>' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-6 col-lg-2 form-group">' +
                            '<label> Disc(%) </label> ' +
                            '<input type="text" class="form-control disc" value="0" required name="poFields[' +
                            x +
                            '][discount]">' +
                            '</div>' +
                            '<div class="col-6 col-lg-2 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control qtyPo" required name="poFields[' +
                            x +
                            '][qty]">' +
                            '</div>' +
                            '<div class="col-6 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white addPo text-center" style="border:none; background-color:#276e61">' +
                            '+ </a> ' +
                            '</div>' +
                            '<div class="col-6 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white remPo text-center" style="border:none; background-color:#d94f5c">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find(".formPo").append(form);

                        $(modal_id).find(".productPo").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
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
                                                text: item
                                                    .nama_sub_material +
                                                    " " + item.type_name +
                                                    " " + item.nama_barang,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                        $(modal_id).find('.productPo').last().select2('open');

                    });

                    //remove Purchase Order fields
                    $(modal_id).on("click", ".remPo", function() {
                        $(this).closest(".row").remove();
                    });

                    //reload total
                    $(modal_id).on('click', '.btn-reload', function() {
                        let total = 0;
                        let purchase_id = $(this).parent().find('.purchase-id').val();
                        $(modal_id).find('.productPo').each(function() {
                            let product_id = $(this).val();
                            
                            let cost = function() {
                                let temp = 0;
                                $.ajax({
                                    async: false,
                                    context: this,
                                    type: "GET",
                                    url: "/products/selectCostDecrypted/" +
                                        product_id,
                                    data: {
                                        purchase_id: purchase_id
                                    },    
                                    dataType: "json",
                                    success: function(data) {
                                        temp = data
                                    },
                                });
                                return temp;
                            }();

                            let qty = $(this).parent().siblings().find('.qtyPo').val();
                            let disc = $(this).parent().siblings().find('.disc').val();
                            console.log(cost);
                            let subTotal = disc == 0 ? qty * cost : qty * cost * (1 - disc /
                                100);
                            // total = total + (cost * qty);
                            total = total + subTotal;
                            //   alert($(this).parent().siblings().find('.cekQty-edit').val());
                        });
                        let ppn = total * $('#ppn').val();
                        let total_incl = total + ppn;

                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(
                                total_incl)
                            .toLocaleString());

                    });
                    //   $(modal_id).on("hidden.bs.modal", function(event) {
                    //     $(modal_id).off(event);
                    //   });
                });

            });
        </script>
    @endpush
@endsection
