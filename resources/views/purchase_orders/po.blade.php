@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }}
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data Purchase Order</h5>
                        {{-- <hr class="bg-primary">
                        <a class="btn btn-primary" href="{{ url('/purchase_orders/create') }}">
                            + Create Purchase Order
                        </a> --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>#</th>
                                        <th>Order Number</th>
                                        <th>Warehouse</th>
                                        <th>Supplier</th>
                                        <th>Order Date</th>
                                        <th>Due Date</th>
                                        {{-- <th>Total</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $value)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item"
                                                        href="{{ url('send_email_po/' . $value->id) }}">Send
                                                        Purcahse Order by Email</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url('po/' . $value->id . '/print') }}">Print
                                                        Purchase Order</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#manageData{{ $value->id }}">Detail</a>
                                                </div>
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->order_number }}</td>
                                            <td>{{ $value->warehouseBy->warehouses }}</td>
                                            <td>{{ $value->supplierBy->nama_supplier }}</td>
                                            <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                                            <td>{{ date('d-M-Y', strtotime($value->due_date)) }}</td>
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

    {{-- Modal PO --}}
    @foreach ($purchases as $item)
        {{-- PO Manage --}}
        <div class="modal fade" id="manageData{{ $item->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Data Purchase Order:
                            {{ $item->order_number }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('purchase_orders/' . $item->id . '/manage') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="container-fluid">
                                <div class="col-md-12">
                                    <div class="row font-weight-bold">
                                        <div class="form-group row">
                                            <div class="col-md-4 form-group">
                                                <label>
                                                    Supplier</label>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $item->supplierBy->nama_supplier }}">
                                            </div>
                                            <div class="col-md-4 form-group mr-5">
                                                <label>Warehouse</label>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $item->warehouseBy->warehouses }}">
                                            </div>
                                            <div class="col-md-4 form-group mr-5">
                                                <label>Due Date <strong>(mm/dd/yyyy)</strong></label>
                                                <input readonly class="form-control" type="date" data-language="en"
                                                    name="due_date" value="{{ $item->due_date }}" required>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12 form-group mr-5">
                                                <label>Remarks</label>
                                                <textarea readonly class="form-control" name="remark" id="" cols="30" rows="5" required>{{ $item->remark }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row formPo">
                                            @foreach ($item->purchaseOrderDetailsBy as $detail)
                                                <div class="form-group row">
                                                    <input type="hidden" class="loop" value="{{ $loop->index }}">
                                                    <div class="form-group col-7">
                                                        <label>Product</label>
                                                        <input readonly class="form-control" type="text"
                                                            data-language="en"
                                                            value="{{ $detail->productBy->nama_barang }} ({{ $detail->productBy->sub_types->type_name }})"
                                                            required>
                                                    </div>
                                                    <div class="col-3 col-md-3 form-group">
                                                        <label>Qty</label>
                                                        <input type="number" class="form-control qtyPo" readonly
                                                            name="poFields[{{ $loop->index }}][qty]" id=""
                                                            value="{{ $detail->qty }}">

                                                    </div>


                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- PO Manage End --}}
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
                $('#example').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            title: 'RAB',
                            extend: 'pdf',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            title: 'Data Stock Profecta ',
                            extend: 'print',
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
                    ]
                });
            });
        </script>
    @endpush
@endsection
