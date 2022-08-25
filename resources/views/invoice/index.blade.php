@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
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
                        <div class="form-group row col-12">
                            <div class="col-5">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Start">
                                </div>
                            </div>
                            <div class="col-5">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Start">
                                </div>
                            </div>
                            <div class="col-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary">Filter</button>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table id="example1"
                                class="display table-bordered expandable-table text-capitalize table-hover"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="2%">action</th>
                                        <th>No</th>
                                        <th>SO Number</th>
                                        <th>Order Date</th>
                                        <th>Due Date</th>
                                        <th>Customer</th>
                                        <th>Remark</th>
                                        <th>By</th>
                                        <th>TOP</th>
                                        <th>PPN</th>
                                        <th>Total</th>
                                        <th>Total After PPN</th>
                                        <th>Payment Method</th>

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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#example1').DataTable({
                    processing: true,
                    serverSide: true,

                    ajax: "{{ url('/invoice') }}",
                    columns: [{
                            width: '5%',
                            data: 'action',
                            name: 'action',
                            orderable: false,
                        }, {
                            width: '5%',
                            data: 'DT_RowIndex',
                            name: 'DT_Row_Index',
                            "className": "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'order_number',
                            name: 'order_number'

                        },
                        {
                            data: 'order_date',
                            name: 'order_date'

                        },
                        {
                            data: 'duedate',
                            name: 'duedate'

                        },
                        {
                            data: 'customerBy',
                            name: 'customerBy.name_cust',
                        },
                        {
                            data: 'remark',
                            name: 'remark',
                        },
                        {
                            data: 'createdSalesOrder',
                            name: 'createdSalesOrder.name',
                        },
                        {
                            data: 'top',
                            name: 'top',
                        },
                        {
                            data: 'ppn',
                            name: 'ppn',
                        },
                        {
                            data: 'total',
                            name: 'total',
                        },
                        {
                            data: 'total_after_ppn',
                            name: 'total_after_ppn',
                        },
                        {
                            data: 'payment_method',
                            name: 'payment_method',
                        },
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        ['10 rows', '25 rows', '50 rows', 'Show All']
                    ],
                    buttons: ['pageLength',
                        {
                            title: ' Data Invoice Profecta Perdana in {{ Auth::user()->warehouseBy->warehouses }}',
                            extend: 'print',
                            customize: function(win) {
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                            },
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        'colvis'
                    ],

                });
            });
        </script>
    @endpush
@endsection
