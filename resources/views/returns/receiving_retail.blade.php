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
                                    <button class="btn btn-primary text-white form-control" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning text-white form-control" name="refresh"
                                        id="refresh">Refresh</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-borderless" style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">

                                        <th>No</th>
                                        <th>Return Date</th>
                                        <th>Return Number</th>
                                        <th>From Retail Number</th>
                                        <th>Total (Rp)</th>
                                        <th>Return Reason</th>
                                        <th>Created By</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">Total</td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
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

                //set date
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
                        ajax: {
                            url: "{{ url('/return_retail/receiving') }}",
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
                                className: "text-center text-nowrap",
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
                                className: "text-center fw-bold text-nowrap",
                                data: 'retail_id',
                                name: 'retail_id'

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

                                    raw2 = raw1.join('');

                                    totalPPN += parseInt(raw2);
                                }
                            });


                            $(api.column(4).footer()).html(totalPPN.toLocaleString());
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
                    let retail_id = $('#retail_id').val();

                    //Get Customer ID
                    $(modal_id).find(".multi-select").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                    });

                    $(modal_id).find(".dotReturn").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                    });

                    $(modal_id).find('#addReturn').unbind('click');
                    let old_return_reason = $(modal_id).find('.return_reason1').val();
                    // console.log(old_return_reason);
                    $(modal_id).find('.return_reason1').change(function() {
                        let return_reason1 = $(this).val();
                        if (return_reason1 == "Other") {
                            $(modal_id).find('.return_reason2').attr('hidden', true);
                            $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                                .attr('required',
                                    false);
                            $(modal_id).find('.other').attr('hidden', false);
                            $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                                'required', true);
                        } else if (return_reason1 == old_return_reason[
                                0]) {
                            $(modal_id).find('.return_reason2').attr('hidden', true);
                            $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                                .attr('required',
                                    false);
                            $(modal_id).find('.other').attr('hidden', true);
                            $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                                'required', false);
                        } else {
                            $(modal_id).find('.return_reason2').attr('hidden', false);
                            $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                                .attr('required',
                                    true);
                            $(modal_id).find('.other').attr('hidden', true);
                            $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                                'required', false);
                        }
                    });

                    $(modal_id).find(".productReturn").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        // allowClear: true,
                        // maximumSelectionLength: 1,
                        width: '100%',
                        // readonly: true,
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
                                                item.type_name + " " + item
                                                .nama_barang,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    }).on("select2:opening", function (e) {
                        // Jangan izinkan membuka dropdown jika readonly
                        if ($(this).prop("readonly")) {
                            e.preventDefault();
                        }
                    });
                    
                    // Set elemen input dalam komponen Select2 menjadi readonly
                    $(modal_id).find(".productReturn").prop("readonly", true);

                    //Get Customer ID
                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('#formReturn')
                        .last()
                        .find('.loop')
                        .val();

                    // let x = $(modal_id)
                    //     .find('.modal-body')
                    //     .find('#formReturn').last().html();
                    // console.log(x);

                    $(modal_id).find("#addReturn").on("click", function() {
                        ++x;
                        let form =
                            '<div class="form-group rounded mx-auto row pt-2 mb-3" style="background-color: #f0e194">' +
                            '<input type="hidden" class="loop" value="' + x + '">' +
                            '<div class="form-group col-12 col-lg-7">' +
                            "<label>Product</label>" +
                            '<select multiple name="returnFields[' +
                            x +
                            '][product_id]" class="form-control productReturn" required>' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-8 col-lg-3 form-group">' +
                            '<label> Qty </label> ' +
                            '<input type="number" class="form-control" required name="returnFields[' +
                            x +
                            '][qty]">' +
                            '<small class="text-xs box-order-amount" hidden>Order Amount: <span class="order-amount">0</span></small>' +
                            '<small class="text-xs box-return-amount" hidden> | Returned: <span class="return-amount">0</span></small>' +
                            '</div>' +
                            '<div class="col-4 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a href="javasript:void(0)" class="form-control text-white remReturn text-center" style="border:none; background-color:red">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find("#formReturn").append(form);

                        $(modal_id).find(".productReturn").select2({
                            dropdownParent: modal_id,
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
