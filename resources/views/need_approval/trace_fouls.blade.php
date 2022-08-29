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
          <a class="col-2 btn btn-sm m-3 btn-danger" href="{{ url('need_approval/') }}"> <i class="ti ti-arrow-left">
            </i>
            Back
          </a>
          <div class="card-header pb-0">
            <h5>{{ $selected_customer->name_cust }}'s Customer Data</h5>
          </div>
          <div class="card-body">
            <div class="tab-content" id="pills-warningtabContent">
              <div class="tab-pane fade show active" id="pills-warninghome" role="tabpanel"
                aria-labelledby="pills-warninghome-tab">
                <p class="mb-0 m-t-30">
                <div class="col-12">
                  <div class="card">
                    <div class="blog-box blog-list row">
                      <div class="col-xl-6 col-12">
                        <div class="blog-wrraper"><img class="img-fluid sm-100-wp p-0"
                            src="{{ asset('images/customers/' . $selected_customer->reference_image) }}" alt="">
                        </div>
                      </div>
                      <div class="col-xl-6 col-12">
                        <div class="blog-details">
                          <div class="blog-date">Code: <strong>
                              {{ $selected_customer->code_cust }}</strong>
                          </div>
                          <div class="blog-date">Last Transaction:
                            {{ date('d-M-Y', strtotime($selected_customer->last_transaction)) }}
                          </div>
                          <h6>Fouls Status</h6>
                          <div class="blog-bottom-content">
                            <ul class="blog-social">
                              <li>Overdue: @if ($selected_customer->isOverDue == 1)
                                  Yes
                                @else
                                  No
                                @endif
                              </li>
                              <li>Overplafond: @if ($selected_customer->isOverPlafoned == 1)
                                  Yes
                                @else
                                  No
                                @endif
                              </li>

                            </ul>
                            <hr>
                            <p>
                              Credit Limit:
                            <p class="fw-bold">Rp. {{ $selected_customer->credit_limit }}</p>
                            </p>
                            <p class="mt-0">Total Credit: @if ($selected_customer->credit_limit < $total_credit)
                                <p class="text-danger fw-bold">Rp. {{ $total_credit }}</p>
                              @else
                                <p class="text-success fw-bold">Rp. {{ $total_credit }}</p>
                              @endif
                            </p>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  </p>
                </div>

              </div>
            </div>


          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-sm-12 col-xl-12 xl-100">
          <div class="card">
            <div class="card-header pb-0">
              <h5>All Orders Data of {{ $selected_customer->name_cust }} That Cause Fouls</h5>
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
                          <th>Overdue Status</th>
                          <th>Total</th>
                          <th>Paid Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($all_inv as $value)
                          <tr>

                            <td style="width: 5%">
                              <a href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                  data-feather="settings"></i></a>
                              <div class="dropdown-menu" aria-labelledby="">
                                <h5 class="dropdown-header">Actions</h5>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-original-title="test"
                                  data-bs-target="#detailData{{ $value->id }}">Products
                                  Detail</a>
                              </div>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->order_number }}</td>
                            <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                            <td>{{ date('d-M-Y', strtotime($value->duedate)) }}</td>
                            @if (date('Y-m-d') >= $value->duedate)
                              <td><span class="badge badge-pill badge-danger text-white">Yes</span></td>
                            @else
                              <td><span class="badge badge-pill badge-success text-white">No</span></td>
                            @endif
                            <td>Rp. {{ $value->total_after_ppn }}</td>
                            @if ($value->isPaid == 0)
                              <td>Unpaid</td>
                            @else
                              <td>Paid</td>
                            @endif

                            <!-- Detail Product Modal Start -->
                            <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1" role="dialog"
                              aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                <form>
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">
                                        Product Detail:
                                        {{ $value->order_number }}</h5>
                                      <button class="btn-close" type="button" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="container-fluid">
                                        <div class="form-group row">
                                          @foreach ($value->salesOrderDetailsBy as $detail)
                                            <div class="form-group col-4">
                                              <label>Product</label>
                                              <input class="form-control"
                                                value="{{ $detail->productSales->nama_barang .
                                                    ' (' .
                                                    $detail->productSales->sub_types->type_name .
                                                    ', ' .
                                                    $detail->productSales->sub_materials->nama_sub_material .
                                                    ')' }}"
                                                id="" readonly>
                                            </div>

                                            <div class="col-3 col-md-3 form-group">
                                              <label>Qty</label>
                                              <input class="form-control" value="{{ $detail->qty }}" readonly>
                                            </div>

                                            <div class="col-3 col-md-3 form-group">
                                              <label>Discount%</label>
                                              <input class="form-control" value="{{ $detail->discount }}" readonly>
                                            </div>
                                          @endforeach
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                          <div class="col-12 form-group">
                                            <label>Remarks</label>
                                            <textarea class="form-control" cols="30" rows="5" readonly>{{ $value->remark }}</textarea>
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="form-group col-lg-4">
                                            <label>PPN</label>
                                            <input class="form-control" value="{{ 'Rp. ' . $value->ppn }}"
                                              id="" readonly>
                                          </div>

                                          <div class="col-lg-4 form-group">
                                            <label>Total (Before PPN)</label>
                                            <input class="form-control" value="{{ 'Rp. ' . $value->total }}" readonly>
                                          </div>

                                          <div class="col-lg-4 form-group">
                                            <label>Total (After PPN)</label>
                                            <input class="form-control" value="{{ 'Rp. ' . $value->total_after_ppn }}"
                                              readonly>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button class="btn btn-danger" type="button"
                                          data-bs-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                </form>
                              </div>
                            </div>
                            <!-- Detail Product Modal End -->


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
          $('#example1').DataTable({
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
