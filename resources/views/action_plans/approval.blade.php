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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                        You can approve leave addition.
                    </h6>
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
                                                            @if ($result->time_photo)
                                                            <p>Time {{ date('H:i:s', strtotime($result->time_photo)) }}</p>
                                                        @else
                                                            <p>No Time Available</p>
                                                        @endif
                                                            <img src="{{ url('https://tracking.profectaperdana.com/public/images/plans/' . $result->photo) }}"
                                                                 class="card-img-top" alt="Result Photo"
                                                                 style="max-width: 65%; height: auto;">
                                                        @else
                                                            <p>No Photo Available</p>
                                                        @endif
                                                        <br><br>
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
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Approve</button>
                            <a class="btn btn-warning reject-button"
                                href="{{ url('action_plans/reject/' . $item->id) }}">Reject</a>
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
                $('.from_date').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });

                function convertHTMLtoDelta(html) {
                    const container = document.createElement('div');
                    container.innerHTML = html;
                    const quill = new Quill(container);
                    return quill.getContents();
                }

                $(document).ready(function() {
                    $('.additional-desc').each(function() {
                        var quill = new Quill(this, {
                            // theme: 'snow',
                            modules: {
                                toolbar: false // Menonaktifkan toolbar
                            },
                            readOnly: true // Membuat editor read-only
                        });

                        // Ambil konten dari input tersembunyi sebelumnya
                        var jsonString = $(this).prev('.result-value').val();
                        var deltaContent = JSON.parse(jsonString);

                        if (jsonString !== "undefined" && jsonString !== "") {
                            quill.setContents(deltaContent);
                        }
                    });
                });

                var table = $('#dataTable').DataTable({
                    "responsive": true,
                    "language": {
                        "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                    },
                    "lengthChange": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false, // disable automatic column width
                    fixedColumns: {
                        leftColumns: 0,
                        rightColumns: 0
                    },
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    "fixedHeader": true,
                    serverSide: true,
                    processing: true,
                    pageLength: -1,
                    ajax: "{{ url('action_plans/approve') }}",
                    columns: [{
                            className: 'text-center fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                        },
                        {
                            data: 'created_by',
                            name: 'created_by',
                        },
                        {
                            className: 'text-center',
                            data: 'date',
                            name: 'date',

                        },
                        {
                            className: 'text-center',
                            data: 'status',
                            name: 'status',

                        },

                    ],
                    responsive: {
                        details: {
                            type: 'column'
                        }
                    },
                });


                $(document).on("click", ".modal-btn2", function() {
                    let modal_id = $(this).attr('data-bs-target');
                    let csrf = $('meta[name="csrf-token"]').attr('content');

                    $(modal_id).find('.from_date').datepicker({
                        onSelect: function(formattedDate, date, inst) {
                            inst.hide();
                        },
                    });
                    $(modal_id).find('input[name="from_date"]').val($(modal_id).find('.from_date')
                        .attr('value'));


                    $(modal_id).find(".formEmployee").find('.select-employee').select2({
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        dropdownParent: modal_id,
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/trip/get-employee/",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            id: item.id,
                                            text: item.name
                                        };
                                    })
                                };
                            },
                        },
                    })

                    $(modal_id).on("click", ".addEmployee", function() {
                        let form = `<div class="row formEmployee">
                        <div class="col-lg-4 mb-3">
                            <label for="">Name</label>
                            <div class="input-group">
                                <select multiple class="form-select select-employee" name="employee_id[]"></select>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <label for="">&nbsp;</label>
                            <a href="javascript:void(0)"
                            class="form-control btn btn-sm text-white addEmployee text-center"
                            style="border:none; background-color:#276e61">+</a>
                        </div>
                        <div class="col-4 col-md-2">
                            <label for="">&nbsp;</label>
                            <a href="#" class="form-control text-white rem text-center" style="border:none; background-color:#d94f5c">-</a>
                        </div>


                    </div>`;

                        $(modal_id).find(".formEmployee:last").after(form);

                        // Inisialisasi Select2 pada elemen select-employee yang baru ditambahkan
                        $(modal_id).find(".formEmployee:last").find('.select-employee').select2({
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                            dropdownParent: modal_id,
                            ajax: {
                                context: this,
                                type: "GET",
                                url: "/trip/get-employee/",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                id: item.id,
                                                text: item.name
                                            };
                                        })
                                    };
                                },
                            },
                        })
                    });

                    $(modal_id).on('click', '.rem', function() {
                        $(this).closest('.formEmployee').remove()
                    });
                });



            });
        </script>
    @endpush
@endsection
