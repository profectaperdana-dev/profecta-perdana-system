@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet" />
        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }

            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
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

            .flex-fill {
                flex: 1 1 45%;
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
            <div class="col-sm-12">
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
                            <table id="dataTable" class=" display table table-striped row-border order-column table-sm">

                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($data as $item)
        <div class="modal fade" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="{{ url('action_plans/approve/' . $item->id) }}" method="POST" class="approved"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>ACTION PLANS {{ $item->userBy->name ?? 'N/A' }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong> Date : {{ $item->created_at->format('d-m-Y') }}</strong></p>
                            @foreach ($item->PlanDetails as $detail)
                                <div class="row mb-3">
                                    <!-- Plan Details Column -->
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Plan Details</h6>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">Customer: {{ $detail->customer }}</li>
                                                    <li class="list-group-item">Address: {{ $detail->address }}</li>
                                                    <li class="list-group-item">Area: {{ $detail->area }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Plan Results Column -->
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Plan Results</h6>
                                                @foreach ($detail->PlanResults as $result)
                                                    <div class="row mb-3">
                                                        <!-- Photo -->
                                                        <div class="col-md-6">
                                                            @if ($result->photo)
                                                                <p>Photo:</p>
                                                                <img src="{{ url('https://tracking.profectaperdana.com/public/images/plans/' . $result->photo) }}"
                                                                    class="card-img-top" alt="Result Photo"
                                                                    style="max-width: 65%; height: auto;">
                                                            @else
                                                                <p>No Photo Available</p>
                                                            @endif
                                                        </div>
                                                        <!-- Issue and Result -->
                                                        <div class="col-md-6">
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item">
                                                                    <p>Issue:</p>
                                                                    <input type="hidden" class="result-value"
                                                                        value="{{ $result->issue }}">
                                                                    <div class="additional-desc"></div>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <p>Result:</p>
                                                                    <input type="hidden" class="result-value"
                                                                        value="{{ $result->result }}">
                                                                    <div class="additional-desc"></div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger me-5" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endforeach



    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>


        <script>
            $(document).ready(function() {
                $('.from_date, .to_date').datepicker({
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(dateText, inst) {
                        $(this).val(dateText);
                    }
                });

                let queryString = window.location.search;
                let queryParams = new URLSearchParams(queryString);
                let filterValue = queryParams.get("filter");

                function parseDate(date) {
                    let day = date.getDate().toString().padStart(2, '0');
                    let month = (date.getMonth() + 1).toString().padStart(2, '0');
                    let year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                }

                if (filterValue == 'this_month') {
                    var currentDate = new Date();
                    var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                    var lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
                    $('#from_date').val(parseDate(firstDayOfMonth));
                    $('#to_date').val(parseDate(lastDayOfMonth));
                } else {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                }

                function load_data(from_date = '', to_date = '') {
                    if ($.fn.DataTable.isDataTable('#dataTable')) {
                        $('#dataTable').DataTable().destroy();
                    }

                    $('#dataTable').DataTable({
                        language: {
                            processing: `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        lengthChange: false,
                        paging: false,
                        searching: true,
                        ordering: true,
                        info: false,
                        autoWidth: false,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('action_plans/history') }}",
                            data: function(d) {
                                d.from_date = from_date;
                                d.to_date = to_date;
                                d.filter = filterValue;
                            }
                        },
                        columns: [{
                                className: 'text-center fw-bold',
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'created_by',
                                name: 'created_by',
                                orderable: true,
                                searchable: true
                            },
                            {
                                className: 'text-center',
                                data: 'date',
                                name: 'date',
                                orderable: true,
                                searchable: true
                            },
                            {
                                className: 'text-center',
                                data: 'status',
                                name: 'status'
                            },
                        ],
                        initComplete: function() {
                            $('#dataTable tbody').on('click', 'td.details-control', function() {
                                var tr = $(this).closest('tr');
                                var row = table.row(tr);
                                if (row.child.isShown()) {
                                    row.child.hide();
                                    tr.removeClass('shown');
                                } else {
                                    row.child(format(row.data())).show();
                                    tr.addClass('shown');
                                }
                            });
                        },
                        drawCallback: function(settings) {
                            $('#thisModal').html('');
                            $('.currentModal').each(function() {
                                $('#thisModal').append($(this).html());
                            });
                        },
                    });
                }

                $('#filter').click(function() {
                    function formatDate(date) {
                        let dateParts = date.split('-');
                        return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                    }

                    var from_date = formatDate($('#from_date').val());
                    var to_date = formatDate($('#to_date').val());

                    if (from_date && to_date) {
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

                $('#refresh').click(function() {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    load_data();
                });

                load_data();
            });
        </script>
    @endpush
@endsection
