@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }

            .nav-new {
                display: block;
                padding: 0.5rem 1rem;
                color: #24695c !important;
                text-decoration: none;
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
            }

            .nav-pills .nav-new.active,
            .nav-pills .show>.nav-new {
                background-color: #d0efe9 !important;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Choose Start Date" data-position="bottom left" name="from_date"
                                        id="from_date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Choose End Date" data-position="bottom left" name="to_date"
                                        id="to_date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-12">
                                <label class="col-form-label text-end">Status</label>
                                <div class="input-group">
                                    <select multiple class="form-select selectMulti" id="status">
                                        <option selected value="0">
                                            Uncompleted</option>
                                        <option value="1">
                                            Completed</option>
                                    </select>
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
                            <table style="font-size: 10pt" id="dataTable"
                                class="tableClaim stripe row-border order-column table-sm" style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th>#</th>
                                        <th></th>
                                        <th>Claim Number</th>
                                        <th>Customer</th>
                                        <th>Battery Type</th>
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
        <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="{{ asset('js/date_convert.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });
                $('#start_date, #end_date').datepicker({
                    language: 'en',
                    dateFormat: 'dd-mm-yyyy',
                });
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
                    <div style="margin-left:2px;" class="row col-lg-6 card shadow">
                                    <table class="table fw-bold" style="border:0;" border="0">
                                        <tr>
                                            <td>Claim Duration</td>
                                            <td>:</td>
                                            <td>${d.duration}</td>
                                        </tr>
                                    </table>
                            </div>
                        `;
                };

                load_data();

                function load_data(from_date = '', to_date = '', status = "") {

                    $('#dataTable').DataTable({
                        "scrollX": true,
                        scrollY: 350,
                        "scrollCollapse": true,
                        "paging": false,
                        "searching": true,
                        "ordering": false,
                        "info": false,
                        "autoWidth": true,
                        "responsive": true,
                        "pageLength": -1,
                        "destroy": true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/history_claim/') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                status: status,
                            }
                        },
                        columns: [{
                                className: 'text-center fw-bold',
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "data": null,
                                "defaultContent": ''
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                            {
                                data: 'customer',
                                name: 'customer'
                            },
                            {
                                data: 'product',
                                name: 'product'
                            },
                        ],
                        drawCallback: function(settings) {
                            // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                            $('#thisModal').html('');
                            $('.currentModal').each(function() {
                                let currentModal = $(this).html();
                                $(this).html('');
                                $('#thisModal').append(currentModal);
                            });

                            // console.log($('#currentModal').html());
                            // Lakukan tindakan lain yang Anda inginkan di sini
                        },

                        initComplete: function() {
                            var table = $('#dataTable').DataTable();
                            $(document).find('#dataTable tbody').off().on('click', 'td.details-control',
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

                    });

                }
                $('#filter').click(function() {
                    // gunakan fungsi convertDate untuk mengubah format tanggal
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
                    var status = $('#status').val();
                    if (from_date > to_date) {
                        $.notify({
                            title: 'Warning',
                            message: 'Start Date must be less than End Date'
                        }, {
                            type: 'danger',
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
                            z_index: 10000,
                            animate: {
                                enter: 'animated bounceInDown',
                                exit: 'animated bounceInUp'
                            }
                        });
                    } else {
                        if (from_date != '' && to_date != '') {
                            load_data(from_date, to_date, status[0]);
                        } else {
                            $.notify({
                                title: 'Warning',
                                message: 'Please Select Date'
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
                                z_index: 10000,
                                animate: {
                                    enter: 'animated bounceInDown',
                                    exit: 'animated bounceInUp'
                                }
                            });
                        }
                    }
                });
                $('#refresh').click(function() {
                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();
                    today = yyyy + '-' + mm + '-' + dd;
                    // console.log(today);
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });

            });
        </script>

        <script>
            $(document).ready(function() {

                $(document).on("click", ".modal-btn2", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    $('.warrantyAccepted').attr('required', false);
                    $('.goodWill').attr('required', false);
                    $('.warrantyTo').hide();
                    $('.warehouseTo').hide();
                    $(modal_id).find(".selectMulti").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        allowClear: true,
                        maximumSelectionLength: 1,
                        placeholder: 'Select an option',
                    });
                    $('.result').on('change', function() {
                        var result = $(this).val();
                        if (result == "CP03 - Waranty Accepted") {
                            $('.warrantyTo').show();
                            $('.warehouseTo').hide();
                            $('.warrantyAccepted').attr('required', true);
                            $('.goodWill').attr('required', false);
                        } else if (result == "CP04 - Good Will") {
                            $('.warrantyTo').hide();
                            $('.warehouseTo').show();
                            $('.warrantyAccepted').attr('required', false);
                            $('.goodWill').attr('required', true);

                        } else {
                            $('.warrantyTo').hide();
                            $('.warehouseTo').hide();
                            $('.warrantyAccepted').attr('required', false);
                            $('.goodWill').attr('required', false);
                        }
                    });

                });

                $(document).on('submit', '.resultClaim', function(event) {
                    event.preventDefault();
                    var form_data = new FormData($(this)[0]);
                    var formElement = $(this);
                    let action = $(this).attr('action');
                    console.log('action:' + action);
                    $.ajax({
                        url: action,
                        type: "POST",
                        dataType: "json",
                        data: form_data,
                        processData: false, // prevent jQuery from processing the data
                        contentType: false, // prevent jQuery from setting the content type
                        beforeSend: function() {
                            $('.btnSubmit').attr('disabled', true);
                            $('.btnSubmit').html(
                                `<i class="fa fa-spinner fa-spin"></i> Processing...`
                            );
                        },
                        success: function(response) {
                            console.log(response.message);
                            if(response.status == 'error'){
                                swal("Error !", response.message, "error", {
                                    button: "Close",
                                });
                            }else{
                                swal("Success !", response.message, "success", {
                                    button: "Close",
                                });
                            }
                            
                            $('#cust').val(null).trigger('change');
                            formElement[0].reset();
                            $('.hideModalAdd').click();
                            $('.tableClaim').DataTable().ajax.reload();


                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus);
                            swal("Error !", 'Error : Please call your Most Valuable IT Team. ',
                                "error", {
                                    button: "Close",
                                });
                        },
                        complete: function() { // menambahkan fungsi complete untuk mengubah tampilan tombol kembali ke tampilan semula
                            $('.btnSubmit').attr('disabled', false);
                            $('.btnSubmit').html('Save');
                        }
                    });
                })
            });
        </script>
    @endpush
@endsection
