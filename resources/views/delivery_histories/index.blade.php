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
                <div class="card shadow">

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
                            <table id="example1" class="table table-sm table-borderless table-striped text-capitalize"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th>No</th>
                                         <th>Order&nbsp;Date</th>
                                        <th>Customer</th>
                                       
                                        <th>Remark</th>
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
                document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                // load data from server
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
                            url: "{{ url('/delivery_history') }}",
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
                                className: "text-center text-nowrap",

                                data: 'order_date',
                                name: 'order_date'

                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            },
                          
                            {
                                className: "text-nowrap",

                                data: 'remark',
                                name: 'remark',
                            },


                        ],


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
                    // Get all select elements with the 'dot-select' class


                    let csrf = $('meta[name="csrf-token"]').attr("content");

                    // $(document).on("click", ".modal-btn2", function() {

                    let modal_id = $(this).attr('data-bs-target');
                    $(modal_id).find(".productDot").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                    }).on("select2:select", function() {
                        $(this).attr('readonly', true);
                    });

                    $(document).on("input", ".qty_dot", function() {

                        let sum = parseInt($(this).val());
                        $(this).parent().parent().siblings().find('.qty_dot').each(function() {
                            sum += parseInt($(this).val());
                        });
                        let qty_product = $(this).closest('.form-addRow').siblings('.qty_product')
                            .val();

                        if (sum > qty_product) {
                            $(this).val(null);
                            $(modal_id).find('.saveButton').attr('disabled', 'disabled')
                            $.notify({
                                title: 'Warning !',
                                message: 'Qty Out is greater than Qty Product'
                            }, {
                                type: 'warning',
                                allow_dismiss: true,
                                newest_on_top: true,
                                mouse_over: true,
                                showProgressbar: false,
                                spacing: 10,
                                timer: 1000,
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
                        } else {
                            $(modal_id).find('.saveButton').removeAttr('disabled')
                        }
                    });
                    var y = $(modal_id)
                        .find('.modal-body')
                        .find('.formSo-edit')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();
                    // console.log(y);
                    // add row
                    $(document).off("click", ".addRow");

                    $(document).on("click", ".addRow", function() {
                        y++;
                        var id_product = $(this).closest('.form-addRow').siblings('.id_product')
                            .val();
                        var so_detail_id = $(this).closest('.form-addRow').siblings('.so_detail_id')
                            .val();
                        var form = ` <div class="row mx-auto rounded py-2 mb-2" style="background-color: #369c89">
                                            <div class="col-lg-4 col-12">
                                                <label for="">DOT - Qty
                                                </label>
                                                <select required multiple name="dotForm[${y}][dot]" class="form-control dotProduct" id="">

                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-12">
                                                <label for="">Qty Out
                                                </label>
                                                <input placeholder="0" name="dotForm[${y}][qty_dot]" required type="number" class="form-control qty_dot" value="">
                                                <input type="hidden" name="dotForm[${y}][so_detail_id]"
                                                        value="${so_detail_id}">
                                            </div>
                                            <div class="col-lg-2 col-6">
                                                <label for="">&nbsp;</label>
                                                <button  type="button" class="btn btn-primary form-control btn-sm addRow">+</button>
                                            </div>


                                            <div class="col-lg-2 col-6">
                                                <label for="">&nbsp;</label>
                                                <button  type="button" class="btn btn-danger form-control btn-sm remRow">-</button>
                                            </div>
                                        </div>`;
                        $(this).closest(".form-addRow").append(form);
                        var dotWarehouse = $(modal_id).find(".id_warehouse").val();
                        var productId = $(this).closest('.form-addRow').siblings('.id_product').val();


                        $(document).on("input", ".qty_dot", function() {

                            let sum = parseInt($(this).val());
                            $(this).parent().parent().siblings().find('.qty_dot').each(
                                function() {
                                    sum += parseInt($(this).val());
                                });

                            let qty_product = $(this).closest('.form-addRow').siblings(
                                    '.qty_product')
                                .val();
                            if (sum > qty_product) {
                                $(this).val(null);
                                $(modal_id).find('.saveButton').attr('disabled', 'disabled')
                                $.notify({
                                    title: 'Warning !',
                                    message: 'Qty Out is greater than Qty Product'
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
                                    z_index: 1000,
                                    animate: {
                                        enter: 'animated swing',
                                        exit: 'animated swing'
                                    }
                                });
                            } else {
                                $(modal_id).find('.saveButton').removeAttr('disabled')
                            }
                        });

                        // Lakukan sesuatu dengan nilai productId di sini
                        $(modal_id).find(".dotProduct").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: false,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                context: this,
                                type: "GET",
                                url: "/tyre_dot/selectDot",
                                data: {
                                    _token: csrf,
                                    p: productId,
                                    w: dotWarehouse
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.dot +
                                                    ' - [ ' +
                                                    item.qty +
                                                    ' pcs]',
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        }).on("select2:select", function() {
                            $(".dotProduct").attr('readonly', true);
                        });

                    });


                    $(modal_id).on("click", ".remRow", function() {
                        $(this).closest(".row").remove();
                    });



                    $(modal_id).find('.datepicker-here').datepicker({
                        onSelect: function(formattedDate, date, inst) {
                            inst.hide();
                        }
                    });
                    let warehouse_id = $(modal_id).find('.warehouse').val();
                    //Get Customer ID
                    $(modal_id).find(".status-select,.dot-select").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: "100%",
                    });
                    $(modal_id).find('.datepicker-here').datepicker({
                        dropdownParent: modal_id,
                        onSelect: function(date) {
                            $(this).datepicker("hide");
                        },
                    });
                });
            });
        </script>
    @endpush
@endsection
