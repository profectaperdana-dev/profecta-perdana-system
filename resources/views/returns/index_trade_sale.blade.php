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
        @include('report.style')
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
                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group row col-12">
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
                                    <button class="btn btn-primary text-white form-control" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning text-white form-control  " name="refresh"
                                        id="refresh">Refresh</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th>No</th>
                                        <th>Return Date</th>
                                        <th>Return Number</th>
                                        <th>Ref. Purchase Number</th>
                                        <th>Total (Rp)</th>
                                        <th>Return Reason</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot class="table-info">
                                    <th colspan="3" class="text-right"></th>
                                    <th class="text-right">Total</th>
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
                            url: "{{ url('/retail_second_products') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        ajax: {
                            url: "{{ url('/return_trade_in_sale') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [{
                                width: '5%',
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center",
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'return_date',
                                name: 'return_date'

                            },
                            {
                                width: '5%',
                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            },
                            {
                                className: 'fw-bold text-center',
                                data: 'purchase_order_id',
                                name: 'purchase_order_id'

                            },

                            {
                                className: 'text-end',
                                data: 'total',
                                name: 'total'

                            },
                            {
                                data: 'return_reason',
                                name: 'return_reason',
                            },
                            {
                                data: 'created_by',
                                name: 'created_by',
                            },

                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            // PPN
                            var visibleData = api.column(4).nodes().to$().map(function() {
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


                            $(api.column(4).footer()).html(totalPPN.toLocaleString('en', {}));
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


            });
        </script>
        <script>
            $(document).ready(function() {
                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");

                    // $(document).on("click", ".modal-btn2", function() {

                    let modal_id = $(this).attr('data-bs-target');
                    let po_id = $('#po_id').val();

                    //Get Customer ID
                    $(modal_id).find(".multi").select2({
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        dropdownParent: modal_id,

                    });

                    $(modal_id).find('#addReturn').unbind('click');

                    $(modal_id).find('.return_reason1').change(function() {
                        let return_reason1 = $(this).val();
                        if (return_reason1 == "Wrong Quantity" || return_reason1 ==
                            "Wrong Product Type") {
                            $(modal_id).find('.return_reason2').attr('hidden', false);
                            $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                                .attr('required',
                                    true);
                            $(modal_id).find('.other').attr('hidden', true);
                            $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                                'required', false);
                        } else if (return_reason1 == "Other") {
                            $(modal_id).find('.return_reason2').attr('hidden', true);
                            $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                                .attr('required',
                                    false);
                            $(modal_id).find('.other').attr('hidden', false);
                            $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                                'required', true);
                        } else {
                            $(modal_id).find('.return_reason2').attr('hidden', true);
                            $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                                .attr('required',
                                    false);
                            $(modal_id).find('.other').attr('hidden', true);
                            $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                                'required', false);
                        }
                    });
                    console.log(po_id);
                    $(".productReturn").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            type: "GET",
                            url: "/return_trade_in_sale/selectReturn",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    p: po_id
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

                    //Get Customer ID
                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('#formReturn')
                        .last()
                        .find('.loop')
                        .val();

                    $(modal_id).find("#addReturn").on("click", function() {
                        ++x;
                        let form =
                            '<div class="row rounded mx-auto  pt-2 mb-3" style="background-color: #f0e194">' +
                            '<div class="form-group col-12 col-lg-7">' +
                            "<label>Product</label>" +
                            '<select name="returnFields[' +
                            x +
                            '][product_id]" multiple class="form-control productReturn" required>' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-8 col-lg-3 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control" required name="returnFields[' +
                            x +
                            '][qty]">' +
                            '<small class="text-xs box-order-amount" hidden>Order Amount: <span class="order-amount">0</span></small>' +
                            '<small class="text-xs box-return-amount" hidden> | Returned: <span class="return-amount">0</span></small>' +
                            '</div>' +
                            '<div class="col-4 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white remReturn text-center" style="border:none; background-color:red">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find("#formReturn").append(form);

                        $(".productReturn").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                type: "GET",
                                url: "/return_trade_in_sale/selectReturn",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        p: po_id
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

                    $(modal_id).on("click", ".remReturn", function() {
                        $(this).closest(".row").remove();
                    });


                    // $(modal_id).on('hidden.bs.modal', function() {
                    //     $(modal_id).unbind();
                    // });
                });
            });
        </script>
    @endpush
@endsection
