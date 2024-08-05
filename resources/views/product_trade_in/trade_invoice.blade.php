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
                                    <input class="datepicker-here form-control digits" data-position="top left"
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
                                        id="refresh">Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        
                                        <th class="text-nowrap">Trade-In Number</th>
                                        <th>Ref. Retail Number</th>
                                                                                <th>Customer</th>

                                        <th>Date</th>
                                        <th class="text-center">Total (Rp)</th>
                                        <th>Created By</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot class="table-info">
                                    <th colspan="4" class="text-right">Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tfoot>
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

                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#example1').DataTable({
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
                            url: "{{ url('/trade_invoice') }}",
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
                            },
                            {
                                width: '13%',
                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            },
                            {
                                className: 'fw-bold text-center',
                                data: 'retail_order_number',
                                name: 'retail_order_number',

                            },
                            {
                                className: 'fw-bold text-center',
                                data: 'customer',
                                name: 'customer',

                            },
                            {
                                className: 'text-center',
                                data: 'trade_in_date',
                                name: 'trade_in_date',

                            },
                            {
                                className: 'text-end',
                                data: 'total',
                                name: 'total',

                            },
                            {
                                className: 'text-center',
                                data: 'createdBy',
                                name: 'createdBy',
                            },


                        ],
                        order: [
                            [0, 'desc']
                        ],
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
                        $('#example1').DataTable().destroy();
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
                    $('#example1').DataTable().destroy();
                    load_data();
                });


                // Edit Invoice
                $(document).on("click", ".modal-btn2", function(event) {

                    $('form').submit(function(e) {
                        var form = $(this);
                        var button = form.find('button[type="submit"]');

                        if (form[0].checkValidity()) { // check if form has input values
                            button.prop('disabled', true);
                            // e.preventDefault(); // prevent form submission
                        }
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
                    //Get Customer ID
                    $(modal_id).find(".multi").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                    });

                    $(modal_id).find(".productSo-edit").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        ajax: {
                            type: "GET",
                            url: "/all_product_trade_in",
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
                    }).on("select2:select", function() {
                        $(modal_id).find(".qty").focus();
                    });


                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('.formSo-edit')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();
                    //Get discount depent on product


                    $(document).find(modal_id).on("click", ".addSo-edit", function() {
                        ++x;
                        var form =
                            `<div class="mx-auto py-1 form-group rounded row " style="background-color: #f0e194">
                                <div class="form-group col-12 col-lg-5">
                                     <label>Baterry</label>
                                     <select multiple name="tradeFields[${x}][product_trade_in]" class="form-control productSo-edit" required>
                                     </select>
                                </div>
                                <div class="col-6 col-lg-3 form-group">
                                    <label> Qty </label>
                                    <input class="form-control cekQty-edit" required name="tradeFields[${x}][qty]">
                                </div>
                                <div class="col-3 col-lg-2 form-group">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)"
                                            class="form-control addSo-edit text-white  text-center"
                                            style="border:none; background-color:#276e61">+</a>
                                    </div>
                                    <div class="col-3 col-lg-2 form-group">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)"
                                            class="btn form-control text-white remSo-edit"
                                            style="border:none; background-color:#d94f5c">-</a>
                                    </div>
                            </div>`;

                        $(modal_id).find(".formSo-edit").append(form);

                        $(modal_id).find(".productSo-edit").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            ajax: {
                                type: "GET",
                                url: "/all_product_trade_in",
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

                        $(modal_id).find(".productSo-edit").last().select2("open");

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
                                    url: "/tradein/selectCost/",
                                    data: {
                                        _token: csrf,
                                        id: product_id,
                                        warehouse: $(modal_id).find(
                                            '#warehouse').val()
                                    },
                                    dataType: "json",
                                    success: function(data) {
                                        temp = data
                                            .price_purchase;
                                    },
                                });
                                console.log(temp);
                                return temp;
                            }();

                            let qty = $(this).parent().siblings().find(
                                '.cekQty-edit').val();
                            console.log(qty);
                            total = total + (cost * qty);

                            console.log(total);
                        });


                        $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math
                            .round(total)
                            .toLocaleString(
                                'en', {
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
