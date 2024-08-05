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
                            <table class="dataTable display table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>#</th>
                                        <th>Trip Number</th>
                                        <th>Name</th>
                                        <th>Departure Date</th>
                                        <th>Return Date</th>
                                        <th>Approval Status</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="form-group">
                                <small> <span class="text-danger">*Note : </span><br>
                                    - BTRPP is Business Trip Profecta Perdana <br>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($data as $item)
        <div class="modal fade" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            {{-- <form action="{{ url('trip/approval/' . $item->id) }}" class="approved" enctype="multipart/form-data">
                @csrf --}}
            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <form class="approved" action="{{ url('trip/finance_approval') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h6>APPROVAL BUSINESS TRIP PROPOSAL {{ $item->trip_number }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control" value="{{ $item->getName() }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Departure Date</label>
                                    <input type="text" class="form-control"
                                        value="{{ date('d-m-Y H:i', strtotime($item->departure_date)) }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Departure Date</label>
                                    <input type="text" class="form-control"
                                        value="{{ date('d-m-Y H:i', strtotime($item->return_date)) }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Transport</label>
                                    <input type="text" class="form-control" value="{{ $item->transport }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Vehicle</label>
                                    <input type="text" class="form-control" value="{{ $item->vehicle }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Distance (Km)</label>
                                    <input type="text" class="form-control distance"
                                        value="{{ number_format($item->distance_route) }}" readonly>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="">Route Business Trip</label>
                                    <p>
                                        @foreach ($data_route as $key => $route)
                                            @if ($route->id_trip == $item->id)
                                                @if ($loop->first)
                                                    <span class="badge badge-success">{{ $route->place }}</span>
                                                @elseif($loop->last)
                                                    <span class="badge badge-success">{{ $route->place }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ $route->place }}</span>
                                                @endif
                                            @endif
                                        @endforeach

                                    </p>
                                </div>
                                <div class="col-lg-2 mb-3 {{ $item->transport == 'Public Transport' ? 'd-none' : '' }}">
                                    <label for="">Fuel Price (Rp)</label>
                                    <input type="text" class="form-control fuel_price"
                                        value="{{ number_format($item->fuel_price) }}">
                                    <input type="hidden" class="fuel_price_" name="fuel_price"
                                        value="{{ $item->fuel_price }}">
                                </div>
                                <div class="{{ $item->transport == 'Public Transport' ? 'col-lg-3' : 'col-lg-2' }} mb-3">
                                    <label for="">Transport Expense (Rp)</label>
                                    <input type="text" class="form-control transport"
                                        value="{{ number_format($item->transport_expense) }}">
                                    <input type="hidden" class="transport_" name="transport_expense"
                                        value="{{ $item->transport_expense }}">
                                </div>
                                <div class="col-lg-2 mb-3 {{ $item->transport == 'Public Transport' ? 'd-none' : '' }}">
                                    <label for="">Toll Expense (Rp)</label>
                                    <input type="text" class="form-control toll_cost"
                                        value="{{ number_format($item->toll_cost) }}">
                                    <input type="hidden" class="toll_cost_" name="toll_cost"
                                        value="{{ $item->toll_cost == '' ? '0' : $item->toll_cost }}">
                                </div>
                                <div class="{{ $item->transport == 'Public Transport' ? 'col-lg-3' : 'col-lg-2' }} mb-3">
                                    <label for="">Accomodation (Rp)</label>
                                    <input type="text" class="form-control acomodation_expense"
                                        value="{{ number_format($item->acomodation_expense) }}">
                                    <input type="hidden" class="acomodation_expense_" name="acomodation_expense"
                                        value="{{ $item->acomodation_expense }}">
                                </div>
                                <div class="{{ $item->transport == 'Public Transport' ? 'col-lg-3' : 'col-lg-2' }} mb-3">
                                    <label for="">Other Expense (Rp)</label>
                                    <input type="text" class="form-control other_expense"
                                        value="{{ number_format($item->other_expense) }}">
                                    <input type="hidden" class="other_expense_" name="other_expense"
                                        value="{{ $item->other_expense }}">
                                </div>
                                <div class="{{ $item->transport == 'Public Transport' ? 'col-lg-3' : 'col-lg-2' }} mb-3">
                                    <label for="">Cash Advance (Rp)</label>
                                    <input type="text" class="form-control total"
                                        value="{{ number_format($item->transport_expense + $item->toll_cost + $item->acomodation_expense + $item->other_expense) }}"
                                        readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Account Bank</label>
                                    <input type="text" class="form-control" value="{{ $item->account_bank }}"
                                        readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Account Number</label>
                                    <input type="text" class="form-control" value="{{ $item->account_number }}"
                                        readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Account Name</label>
                                    <input type="text" class="form-control" value="{{ $item->account_name }}"
                                        readonly>
                                </div>
                                <div class="{{ $item->transport == 'Public Transport' ? 'col-lg-12' : 'col-lg-6' }} mb-3">
                                    <label for="">Purpose</label>
                                    <textarea readonly class="form-control" name="" id="" cols="30" rows="2">{{ $item->purpose }}</textarea>
                                </div>
                                <div class="{{ $item->transport == 'Public Transport' ? 'd-none' : 'col-lg-6' }} mb-3">
                                    <label for="">Notes : GA</label>
                                    <textarea readonly class="form-control" name="" id="" cols="30" rows="2">{{ $item->notes }}</textarea>

                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            @if (!$item->finance_approval)
                                <a class="btn btn-warning" href="{{ url('trip/delete/' . $item->id) }}">Delete</a>
                            @endif

                            <button class="btn btn-danger hideModal" type="button"
                                data-bs-dismiss="modal">Close</button>
                            @if (!$item->finance_approval)
                                <button type="submit" class="btn btnSubmit btn-primary">
                                    Approve
                                </button>
                            @else
                                <button type="button" disabled class="btn btn-primary">
                                    Need GA Approval
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            {{-- </form> --}}
        </div>
    @endforeach


    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(document).ready(function() {
                $(document).on("click", ".modal-btn2", function(event) {
                    let modal_id = $(this).attr('data-bs-target');
                    $(modal_id).find(
                        '.transport,.fuel_price,.toll_cost,.acomodation_expense,.other_expense').on(
                        'input',
                        function(
                            event) {
                            var selection = window.getSelection().toString();
                            if (selection !== '') {
                                return;
                            }
                            // When the arrow keys are pressed, abort.
                            if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                                return;
                            }
                            var $this = $(this);
                            // Get the value.
                            var input = $this.val();
                            input = input.replace(/[\D\s\._\-]+/g, "");
                            input = input ? parseInt(input, 10) : 0;
                            $this.val(function() {
                                return input.toLocaleString("EN-en");
                            });
                            $this.next().val(input);
                        });
                    $(modal_id).find(
                            '.fuel_price,.transport,.acomodation_expense,.other_expense,.fuel_price,.toll_cost')
                        .on(
                            'focusout',
                            function() {
                                let distance = $(modal_id).find('.distance').val();
                                let fuel_price = $(modal_id).find('.fuel_price_').val();
                                let toll_cost = $(modal_id).find('.toll_cost_').val();
                                let acomodation_expense = $(modal_id).find('.acomodation_expense_').val();
                                let other_expense = $(modal_id).find('.other_expense_').val();
                                let transport = '';
                                
                                // console.log(distance);
                                // console.log(fuel_price);
                                // console.log(toll_cost);
                                // console.log(acomodation_expense);
                                // console.log(other_expense);

                                if (typeof distance == 'undefined' || distance == null || distance == '' ||
                                    distance ==
                                    0) {
                                    transport = $(modal_id).find('.transport_').val();

                                    let total = parseInt(transport) + parseInt(toll_cost) + parseInt(
                                            acomodation_expense) +
                                        parseInt(other_expense);
                                    $(modal_id).find('.total').val(total.toLocaleString("EN-en"));

                                } else {
                                    distance = distance.replace(/,/g, '');
                                    transport = (distance / 10) * fuel_price;

                                    let total = parseInt(transport) + parseInt(toll_cost) + parseInt(
                                            acomodation_expense) +
                                        parseInt(other_expense);
                                    $(modal_id).find('.total').val(total.toLocaleString("EN-en"));
                                }
                                $(modal_id).find('.transport_').val(transport);
                                $(modal_id).find('.transport').val(parseInt(transport).toLocaleString("EN-en"));

                            });
                })

            })
        </script>
        <script>
            $(document).ready(function() {
                $(document).on('submit', '.approved', function(event) {
                    event.preventDefault();
                    var form_data = $(this).serialize();
                    var url = $(this).attr('action');
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: form_data,
                        beforeSend: function() {
                            $('.btnSubmit').attr('disabled', true);
                            $('.btnSubmit').html(
                                `<i class="fa fa-spinner fa-spin"></i> Processing...`
                            );
                        },
                        success: function(response) {
                            console.log(response);
                            swal("Success !", "data has been saved", "success", {
                                button: "Close",
                            });
                            $('.dataTable').DataTable().ajax.reload();
                            $('.hideModal').click();


                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error:', textStatus, errorThrown);
                        },
                        complete: function() { // menambahkan fungsi complete untuk mengubah tampilan tombol kembali ke tampilan semula
                            $('.btnSubmit').attr('disabled', false);
                            $('.btnSubmit').html('Save');
                        }
                    });
                })

                var table = $('.dataTable').DataTable({
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
                    ajax: "{{ url('trip/finance_approval') }}",
                    columns: [{
                            className: 'dtr-control',
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        }, {
                            className: 'text-end fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                        },
                        {
                            data: 'trip_number',
                            name: 'trip_number',
                        },
                        {
                            data: 'id_employee',
                            name: 'id_employee',
                        },
                        {
                            className: 'text-center',
                            data: 'departure_date',
                            name: 'departure_date',

                        },
                        {
                            className: 'text-center',
                            data: 'return_date',
                            name: 'return_date',

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

            });
        </script>
    @endpush
@endsection
