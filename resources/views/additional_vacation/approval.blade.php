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
                                        <th>Remark</th>
                                        <th>Date</th>
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
            <form action="{{ url('additional_leave/approve_additional_vacation/' . $item->id) }}" method="POST"
                class="approved" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>APPROVAL ADDITION {{ $item->remark }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach ($item->detailBy as $detail)
                                    <div class="row formEmployee">
                                        <div class="col-lg-4 mb-3">
                                            <label for="employee_id[]">Name</label>
                                            <div class="input-group">
                                                <select class="form-select select-employee" name="employee_id[]">
                                                    <option value="{{ $detail->employee_id }}" selected>
                                                        {{ $detail->teamBy->name }}
                                                    </option>
                                                </select>
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
                                            <a href="#" class="form-control text-white rem text-center"
                                                style="border:none; background-color:#d94f5c">-</a>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="row newEmployeeContainer"></div>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label for="addition">Addition</label>
                                        <input type="text" class="form-control" name="addition"
                                            value="{{ $item->addition }}">
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="from_date">Date</label>
                                        <div class="input-group">
                                            <input class="datepicker-here form-control digits from_date"
                                                data-position="bottom left" type="text" data-language="en"
                                                value="{{ date('d-m-Y', strtotime($item->date)) }}" name="from_date"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="remark">Remark</label>
                                        <input type="text" class="form-control" name="remark"
                                            value="{{ $item->remark }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Approve</button>
                            <a class="btn btn-warning reject-button"
                                href="{{ url('additional_leave/reject_additional_vacation/' . $item->id) }}">Reject</a>
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

        <script>
            $(document).ready(function() {
                $('.from_date').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
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
                    processing: true,
                    serverSide: true,
                    pageLength: -1,
                    ajax: "{{ url('additional_leave/approve_additional_vacation') }}",
                    columns: [{
                            className: 'text-center fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                        },
                        {
                            data: 'remark',
                            name: 'remark',
                        },
                        {
                            className: 'text-center',
                            data: 'date',
                            name: 'date',

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
