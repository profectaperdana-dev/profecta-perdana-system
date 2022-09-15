@extends('layouts.master')
@section('content')
  @push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <style>
      tr.group,
      tr.group:hover {
        background-color: #ddd !important;
      }
    </style>
  @endpush

  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="font-weight-bold">{{ $title }}</h3>
          <h6 class="font-weight-normal mb-0 breadcrumb-item active">Check
            {{ $title }}
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
            <h5>All Data Sales Order Need Approval</h5>
          </div>
          <div class="card-body">

            <div class="tab-content" id="pills-warningtabContent">
              <div class="tab-pane fade show active" id="pills-warninghome" role="tabpanel"
                aria-labelledby="pills-warninghome-tab">
                <p class="mb-0 m-t-30">
                <div class="table-responsive">
                  <table id="example" class="display expandable-table text-capitalize table-hover" style="width:100%">
                    <thead>
                      <tr>
                        <th style="width: 5%">Action</th>
                        <th>#</th>
                        <th>SO Number</th>
                        <th>Order Date</th>
                        <th>Due Date</th>
                        <th>Customer</th>
                        <th>Overdue Status</th>
                        <th>Overplafond Status</th>
                        {{-- <th>Approve</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dataInvoice as $value)
                        <tr>
                          <td class="text-center"><a class="btn btn-primary btn-sm" href="#" data-bs-toggle="modal"
                              data-original-title="test" data-bs-target="#approveData{{ $value->id }}">Approval
                          </td>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->order_number }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->duedate)) }}</td>
                          <td>{{ $value->customerBy->name_cust }}</td>
                          @if ($value->customerBy->isOverDue == 1)
                            <td><span class="badge badge-pill badge-danger text-white">Yes</span>
                            </td>
                          @else
                            <td><span class="badge badge-pill badge-success text-white">No</span>
                            </td>
                          @endif
                          @if ($value->customerBy->isOverPlafoned == 1)
                            <td><span class="badge badge-pill badge-danger text-white">Yes</span>
                            </td>
                          @else
                            <td><span class="badge badge-pill badge-success text-white">No</span>
                            </td>
                          @endif

                          <div class="modal fade" id="approveData{{ $value->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">
                                    Order Number
                                    #{{ $value->order_number }}</h5>
                                  <button class="btn-close" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="container-fluid">
                                    <div class="row justify-content-center">
                                      <div class="col-4 col-lg-3">
                                        <h5>Revision: {{ $value->revision }}</h5>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <div class="col-6 col-md-6 form-group">
                                        <label>Over Due</label>
                                        <input type="text" readonly
                                          @if ($value->customerBy->isOverDue == 1) class="form-control bg-warning text-white"
                                                                                value="YES"
                                                                                @else
                                                                                class="form-control bg-primary text-white"
                                                                                value="NO" @endif>
                                      </div>
                                      <div class="col-6 col-md-6 form-group">
                                        <label>Over Plafond</label>
                                        <input type="text" readonly
                                          @if ($value->customerBy->isOverPlafoned == 1) class="form-control bg-warning text-white"
                                                                                value="YES"
                                                                                @else
                                                                                class="form-control bg-primary text-white"
                                                                                value="NO" @endif>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <div class="col-6 col-md-6 form-group">
                                        <label>Order Date</label>
                                        <input type="text" readonly class="form-control "
                                          value="{{ date('d-M-Y', strtotime($value->order_date)) }}">
                                      </div>
                                      <div class="col-6 col-md-6 form-group">
                                        <label>Due Date</label>
                                        <input type="text" readonly class="form-control"
                                          @if ($value->duedate == null) value="-"
                                        @else
                                        value="{{ date('d-M-Y', strtotime($value->duedate)) }}" @endif>
                                      </div>
                                    </div>
                                    <div class="form-group row">
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
                                                                                    value="Cash On Delivery"
                                                                                    @else
                                                                                    value="Cash On Delivery" @endif>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <div class="col-12 form-group">
                                        <label>Remarks</label>
                                        <textarea readonly class="form-control" name="remark" id="" cols="30" rows="5">{{ $value->remark }}</textarea>
                                      </div>
                                    </div>

                                    <div class="form-group row">
                                      @foreach ($value->salesOrderDetailsBy as $detail)
                                        <div class="form-group col-6">
                                          <label>Product</label>
                                          <input type="text" readonly class="form-control "
                                            value="{{ $detail->productSales->nama_barang .
                                                ' (' .
                                                $detail->productSales->sub_types->type_name .
                                                ', ' .
                                                $detail->productSales->sub_materials->nama_sub_material .
                                                ')' }} ">

                                        </div>

                                        <div class="col-3 col-md-3 form-group">
                                          <label>Qty</label>
                                          <input type="text" readonly class="form-control cekQty-edit"
                                            value="{{ $detail->qty }}">

                                        </div>

                                        <div class="col-3 col-md-3 form-group">
                                          <label>Disc (%)</label>
                                          <input type="text" readonly class="form-control" placeholder="Product Name"
                                            value="{{ $detail->discount }}">

                                        </div>
                                      @endforeach
                                    </div>
                                    <hr>

                                    <div class="form-group row">
                                      <div class="form-group col-lg-4">
                                        <label>PPN</label>
                                        <input class="form-control"
                                          value="{{ 'Rp. ' . number_format($value->ppn, 0, ',', '.') }}" id=""
                                          readonly>
                                      </div>
                                      <div class="col-lg-4 form-group">
                                        <label>Total (Before PPN)</label>
                                        <input class="form-control"
                                          value="{{ 'Rp. ' . number_format($value->total, 0, ',', '.') }}" readonly>
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
                                  <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                  <a class="btn btn-warning" href="{{ url('/sales_orders/reject/' . $value->id) }}">No,
                                    Reject
                                  </a>
                                  <a class="btn btn-primary"
                                    href="{{ url('/sales_orders/approve/' . $value->id) }}">Yes,
                                    approve
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                </p>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
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
        let date = new Date();
        let date_now = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
        $('#example').DataTable({
          dom: 'Bfrtip',
          buttons: [{
              title: 'Unapprove Sales Orders (' + date_now + ')',
              extend: 'pdf',
              pageSize: 'A4',
              alignment: 'left',
              exportOptions: {
                columns: ':visible'
              },
              customize: function(doc) {
                doc.styles.tableHeader.alignment = 'left';
                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split(
                  '');
              }

            },
            {
              title: 'Unapprove Sales Orders (' + date_now + ')',
              extend: 'print',
              exportOptions: {
                columns: ':visible'
              },
            },
            {
              title: 'Unapprove Sales Orders (' + date_now + ')',
              extend: 'excel',
              exportOptions: {
                columns: ':visible'
              }
            },
            'colvis'
          ]

        });


        $('#example tbody').on('click', 'tr.group', function() {
          var currentOrder = table.order()[0];
          if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            table.order([2, 'desc']).draw();
          } else {
            table.order([2, 'asc']).draw();
          }
        });

      });
    </script>
  @endpush
@endsection
