@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
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

            @media (max-width: 767px) {
                tfoot {
                    display: none;
                }
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
                        <div class="table-responsive">
                            <table style="font-size: 10pt" class="tableClaim stripe row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th></th>
                                        <th>#</th>
                                        <th>Claim Number</th>
                                        <th>Customer</th>
                                        <th>Name</th>
                                        <th>Credit Cost</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="text-end fw-bold ">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Total</th>
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
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
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
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>

        <script>
            $(document).ready(function() {
                var table = $('.tableClaim').DataTable({
                    "language": {
                        "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                    },
                    "lengthChange": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true,
                    fixedColumns: {
                        leftColumns: 0,
                        rightColumns: 0
                    },
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    "fixedHeader": true,
                    processing: true,
                    serverSide: true,
                    pageLength: -1,
                    ajax: "{{ url('/claim/settlement') }}",
                    columns: [{
                            className: 'dtr-control',
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        }, {
                            className: 'text-center fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
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
                            data: 'sub_name',
                            name: 'sub_name'
                        },

                        {
                            data: 'cost',
                            render: function(data, type, row, meta) {
                                if (type === 'display' && $(window).width() > 768) {
                                    return '<div class="text-end fw-bold">' + data + '</div>';
                                }
                                return data;
                            }
                        },
                    ],

                    order: [
                        [1, 'desc']
                    ],
                    responsive: {
                        details: {
                            type: 'column'
                        }
                    },
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        total = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(5).footer()).html(total.toLocaleString('en', {}));
                    }

                });
            });

            $(document).ready(function() {
                $(document).on("click", ".modal-btn2", function(event) {
                    let modal_id = $(this).attr('data-bs-target');
                    let validator = $('form.needs-validation').jbvalidator({
                        errorMessage: true,
                        successClass: false,
                        language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                    });
                    $(modal_id).find('.datepicker-here').datepicker({
                        onSelect: function(formattedDate, date, inst) {
                            inst.hide();
                        }
                    });
                    $(modal_id).find(".selectMulti").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        allowClear: true,
                        maximumSelectionLength: 1,
                        placeholder: 'Select an option',
                    });

                });
                $(document).on('submit', '.settlementClaim', function(event) {
                    event.preventDefault();
                    console.log('masuk');
                    var form_data = new FormData($(this)[0]);
                    var formElement = $(this);
                    let action = $(this).attr('action');
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
                                `<i class="fa fa-spinner fa-spin"></i> Please wait...`
                            );
                        },
                        success: function(response) {
                            swal("Success !", response.message, "success", {
                                button: "Close",
                            });
                            $('.hideModalAdd').click();
                            $('.tableClaim').DataTable().ajax.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
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
