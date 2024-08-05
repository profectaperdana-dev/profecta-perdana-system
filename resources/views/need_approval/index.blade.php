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
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="example"
                                class="display table-sm text-nowrap table table-borderless  expandable-table text-capitalize table-striped"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">

                                        <th>#</th>
                                        <th>Order Number</th>
                                        <th>Order Date</th>
                                        <th>Due Date</th>
                                        <th>Customer</th>
                                        <th>Overdue </th>
                                        <th>Overplafond </th>
                                        {{-- <th>Approve</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataInvoice as $value)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center"><a class="fw-bold text-nowrap text-success"
                                                    href="#" data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#approveData{{ $value->id }}">
                                                    {{ $value->order_number }}
                                            </td>
                                            
                                            <td class="text-center">{{ date('d F Y', strtotime($value->order_date)) }}
                                            </td>
                                            <td class="text-center">{{ date('d F Y', strtotime($value->duedate)) }}</td>
                                            <td>{{ $value->customerBy->code_cust . ' - ' . $value->customerBy->name_cust }}
                                            </td>
                                            @if ($value->customerBy->isOverDue == 1)
                                                <td class="text-center"><span
                                                        class="badge badge-pill badge-danger text-white">Yes</span>
                                                </td>
                                            @else
                                                <td class="text-center"><span
                                                        class="badge badge-pill badge-primary text-white">No</span>
                                                </td>
                                            @endif
                                            @if ($value->customerBy->isOverPlafoned == 1)
                                                <td class="text-center"> <span
                                                        class="badge badge-pill badge-danger text-white">Yes</span>
                                                </td>
                                            @else
                                                <td class="text-center"><span
                                                        class="badge badge-pill badge-primary text-white">No</span>
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     @foreach ($dataInvoice as $value)
    <div data-bs-backdrop="static" class="modal fade"
                                                id="approveData{{ $value->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title" id="exampleModalLabel">
                                                                Sales Order {{ $value->order_number }} | Revision :
                                                                {{ $value->revision }}</h6>
                                                            {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container-fluid">

                                                                <div class="form-group row">
                                                                    <div class="col-6 col-md-6 form-group">
                                                                        <label>Over Due</label>
                                                                        <input type="text" readonly
                                                                            @if ($value->customerBy->isOverDue == 1) class="form-control text-white" style="border:none; background-color:#d94f5c"
                                                                value="YES"
                                                                @else
                                                                class="form-control bg-primary text-white"
                                                                value="NO" @endif>
                                                                    </div>
                                                                    <div class="col-6 col-md-6 form-group">
                                                                        <label>Over Plafond</label>
                                                                        <input type="text" readonly
                                                                            @if ($value->customerBy->isOverPlafoned == 1) class="form-control text-white" style="border:none; background-color:#d94f5c"
                                                                value="YES"
                                                                @else
                                                                class="form-control bg-primary text-white"
                                                                value="NO" @endif>
                                                                    </div>

                                                                    <div class="col-6 col-md-6 form-group">
                                                                        <label>Order Date</label>
                                                                        <input type="text" readonly class="form-control "
                                                                            value="{{ date('d F Y', strtotime($value->order_date)) }}">
                                                                    </div>
                                                                    <div class="col-6 col-md-6 form-group">
                                                                        <label>Due Date</label>
                                                                        <input type="text" readonly class="form-control"
                                                                            @if ($value->duedate == null) value="-"
                                                                @else
                                                                value="{{ date('d F Y', strtotime($value->duedate)) }}" @endif>
                                                                    </div>

                                                                    <div class="col-6 col-md-6 form-group">
                                                                        <label>Customer</label>
                                                                        <input type="text" readonly class="form-control "
                                                                            value="{{ $value->customerBy->name_cust }}">
                                                                    </div>
                                                                    <div class="col-6 col-md-6 form-group">
                                                                        <label>Payment Method</label>
                                                                        <input type="text" readonly class="form-control"
                                                                            @if ($value->payment_method == 1) value="Cash On Delivery"
                                                                @elseif($value->payment_method == 2)
                                                                value="Cash Before Delivery"
                                                                @else
                                                                value="Credit" @endif>
                                                                    </div>

                                                                    <div class="col-12 form-group">
                                                                        <label>Remarks</label>
                                                                        <textarea readonly class="form-control" name="remark" id="" cols="30" rows="1">{{ $value->remark }}</textarea>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    @foreach ($value->salesOrderDetailsBy as $detail)
                                                                        <div class="mx-auto rounded py-2 form-group row "
                                                                            style="background-color: #f0e194">
                                                                            <div class="form-group col-9 col-lg-4">
                                                                                <label>Product</label>
                                                                                <input type="text" readonly
                                                                                    class="form-control "
                                                                                    value="{{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->nama_barang . ' ' . $detail->productSales->sub_types->type_name }}" />

                                                                            </div>

                                                                            <div class="col-3 col-lg-3 form-group">
                                                                                <label>Qty</label>
                                                                                <input type="text" readonly
                                                                                    class="form-control cekQty-edit"
                                                                                    value="{{ $detail->qty }}">

                                                                            </div>

                                                                            <div class="col-6 col-lg-2 form-group">
                                                                                <label>Disc (%)</label>
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    placeholder="Product Name"
                                                                                    value="{{ $detail->discount }}">

                                                                            </div>

                                                                            <div class="col-6 col-lg-3 form-group">
                                                                                <label>Disc (Rp)</label>
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    placeholder="Product Name"
                                                                                    value="{{ $detail->discount_rp }}">

                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="form-group col-lg-4">
                                                                        <label>PPN</label>
                                                                        <input class="form-control"
                                                                            value="{{ 'Rp. ' . number_format($value->ppn, 0, ',', '.') }}"
                                                                            id="" readonly>
                                                                    </div>
                                                                    <div class="col-lg-4 form-group">
                                                                        <label>Total (Before PPN)</label>
                                                                        <input class="form-control"
                                                                            value="{{ 'Rp. ' . number_format($value->total, 0, ',', '.') }}"
                                                                            readonly>
                                                                    </div>
                                                                    <div class="col-lg-4 form-group">
                                                                        <label>Total (After PPN)</label>
                                                                        <input class="form-control"
                                                                            value="{{ 'Rp. ' . number_format($value->total_after_ppn, 0, ',', '.') }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-danger" type="button"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <a class="btn btn-delete btn-warning"
                                                                href="{{ url('/sales_orders/reject/' . $value->id) }}">No,
                                                                Reject

                                                            </a>
                                                            <a class="btn btn-delete btn-primary"
                                                                href="{{ url('/sales_orders/approve/' . $value->id) }}">Yes,
                                                                approve
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                             @endforeach
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
        <script>
            $(document).ready(function() {
                $(document).on('click', '.btn-delete', function() {
                    $(this).addClass('disabled');
                });

                let date = new Date();
                let date_now = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
                var t = $('#example').DataTable({
                    "lengthChange": false,
                    "paging": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    dom: 'lpftrip',
                    pageLength: -1,
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0
                    }, {
                        searchable: false,
                        orderable: false,
                        targets: 1,
                    }, ],
                });

                t.on('order.dt search.dt', function() {
                    let i = 1;

                    t.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();



            });
        </script>
    @endpush
@endsection
