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
            <h5>All Data Sales Order Not Verified</h5>
          </div>
          <div class="card-body">
            <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
              <li class="nav-item"><a class="nav-link active" id="pills-warninghome-tab" data-bs-toggle="pill"
                  href="#pills-warninghome" role="tab" aria-controls="pills-warninghome" aria-selected="true">No
                  Debt</a></li>
              <li class="nav-item"><a class="nav-link" id="pills-warningprofile-tab" data-bs-toggle="pill"
                  href="#pills-warningprofile" role="tab" aria-controls="pills-warningprofile"
                  aria-selected="false">Debt</a></li>

            </ul>
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
                        <th>Customer</th>
                        <th>Payment</th>
                        <th>Verified</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dataSalesOrder as $value)
                        <tr>
                          <td style="width: 5%">
                            <a href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                data-feather="settings"></i></a>
                            <div class="dropdown-menu" aria-labelledby="">
                              <h5 class="dropdown-header">Actions</h5>
                              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-original-title="test"
                                data-bs-target="#detailData{{ $value->id }}">Products
                                Detail</a>
                              <a class="dropdown-item editPayment_method"
                                href="{{ url('/edit_sales_order/' . $value->id) }}">Edit
                                Sales
                                Order</a>
                              <a class="dropdown-item editPayment_method"
                                href="{{ url('/edit_product/' . $value->id) }}">Edit
                                Product</a>
                              <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#deleteData{{ $value->id }}">Delete
                                Sales Order</a>
                            </div>
                          </td>

                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->order_number }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                          <td>{{ $value->customerBy->name_cust }}</td>
                          @if ($value->payment_method == 1)
                            <td>COD</td>
                          @else
                            <td>CBD</td>
                          @endif
                          <td class="text-center"><a class="btn btn-primary btn-sm"
                              href="{{ url('/sales_orders/verify/' . $value->id) }}">Verify
                          </td>
                          </a>

                          <!-- Delete Product Modal Start -->
                          <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form method="post" action="{{ url('sales_order/' . $value->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('delete')
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                      Delete Data:
                                      {{ $value->order_number }}</h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                      aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="container-fluid">
                                      <div class="form-group row">
                                        <div class="col-md-12">
                                          <h5>Are you sure delete this data ?</h5>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit">Yes, delete
                                    </button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          <!-- Delete Product Modal End -->

                          <!-- Edit Product Modal Start -->
                          <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form method="post" action="{{ url('sales_order/' . $value->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                      Edit Data:
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
                                            <select name="soFields[{{ $loop->index }}][product_id]"
                                              class="form-control productSo" required>
                                              <option value="">Choose
                                                Product</option>
                                            </select>
                                            @error('soFields[{{ $loop->index }}][product_id]')
                                              <div class="invalid-feedback">
                                                {{ $message }}
                                              </div>
                                            @enderror
                                          </div>

                                          <div class="col-3 col-md-3 form-group">
                                            <label>Qty</label>
                                            <input class="form-control cekQty" required
                                              name="soFields[{{ $loop->index }}][qty]" id="">
                                            @error('soFields[{{ $loop->index }}][qty]')
                                              <div class="invalid-feedback">
                                                {{ $message }}
                                              </div>
                                            @enderror
                                          </div>

                                          <div class="col-3 col-md-3 form-group">
                                            <label>Discount%</label>
                                            <input class="form-control discount-append"
                                              name="soFields[{{ $loop->index }}][discount]" id="">
                                          </div>
                                        @endforeach

                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button class="btn btn-danger" type="button"
                                      data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit">Yes, delete
                                    </button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          <!-- Edit Product Modal End -->

                          <!-- Detail Product Modal Start -->
                          <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
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
              <div class="tab-pane fade" id="pills-warningprofile" role="tabpanel"
                aria-labelledby="pills-warningprofile-tab">
                <p class="mb-0 m-t-30">
                <div class="table-responsive">
                  <table id="example1" class="display expandable-table text-capitalize table-hover"
                    style="width:100%">
                    <thead>
                      <tr>
                        <th style="width: 3%">Action</th>
                        <th>#</th>
                        <th>SO Number</th>
                        <th>Order Date</th>
                        <th>Due Date</th>
                        <th>Customer</th>
                        <th>Verified</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dataSalesOrderDebt as $value)
                        <tr>
                          <td style="width: 3%">
                            <a href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                data-feather="settings"></i></a>
                            <div class="dropdown-menu" aria-labelledby="">
                              <h5 class="dropdown-header">Actions</h5>
                              <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-original-title="test" data-bs-target="#detailDataDebt{{ $value->id }}">Products
                                Detail</a>
                              <a class="dropdown-item editPayment_method"
                                href="{{ url('/edit_sales_order/' . $value->id) }}">Edit
                                Sales
                                Order</a>
                              <a class="dropdown-item editPayment_method"
                                href="{{ url('/edit_product/' . $value->id) }}">Edit
                                Product</a>
                              <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-original-title="test" data-bs-target="#deleteData{{ $value->id }}">Delete
                                Sales Order</a>
                            </div>

                          </td>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->order_number }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->duedate)) }}</td>
                          <td>{{ $value->customerBy->name_cust }}</td>
                          <td class="text-center"><a class="btn btn-primary btn-sm"
                              href="{{ url('/sales_orders/verify/' . $value->id) }}">Verify
                          </td>
                          </a>

                          <!-- Delete Product Modal Start -->
                          <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form method="post" action="{{ url('sales_order/' . $value->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('delete')
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                      Delete Data:
                                      {{ $value->order_number }}</h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                                      aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="container-fluid">
                                      <div class="form-group row">
                                        <div class="col-md-12">
                                          <h5>Are you sure delete this data ?</h5>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button class="btn btn-danger" type="button"
                                      data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit">Yes, delete
                                    </button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          <!-- Delete Product Modal End -->

                          <!-- Edit Product Modal Start -->
                          <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form method="post" action="{{ url('sales_order/' . $value->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                      Edit Data:
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
                                            <select name="soFields[{{ $loop->index }}][product_id]"
                                              class="form-control productSo" required>
                                              <option value="">Choose
                                                Product</option>
                                            </select>
                                            @error('soFields[{{ $loop->index }}][product_id]')
                                              <div class="invalid-feedback">
                                                {{ $message }}
                                              </div>
                                            @enderror
                                          </div>

                                          <div class="col-3 col-md-3 form-group">
                                            <label>Qty</label>
                                            <input class="form-control cekQty" required
                                              name="soFields[{{ $loop->index }}][qty]" id="">
                                            @error('soFields[{{ $loop->index }}][qty]')
                                              <div class="invalid-feedback">
                                                {{ $message }}
                                              </div>
                                            @enderror
                                          </div>

                                          <div class="col-3 col-md-3 form-group">
                                            <label>Discount%</label>
                                            <input class="form-control discount-append"
                                              name="soFields[{{ $loop->index }}][discount]" id="">
                                          </div>
                                        @endforeach

                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button class="btn btn-danger" type="button"
                                      data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit">Yes, delete
                                    </button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          <!-- Edit Product Modal End -->

                          <!-- Detail Product Modal Start -->
                          <div class="modal fade" id="detailDataDebt{{ $value->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
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
                                        <div class="form-group col-4">
                                          <label>TOP</label>
                                          <input class="form-control" value="{{ $value->top . ' Days' }}"
                                            id="" readonly>
                                        </div>

                                        <div class="col-4 form-group">
                                          <label>Order Date</label>
                                          <input class="form-control"
                                            value="{{ date('d-M-Y', strtotime($value->order_date)) }}" readonly>
                                        </div>

                                        <div class="col-4 form-group">
                                          <label>Due Date</label>
                                          <input class="form-control"
                                            value="{{ date('d-M-Y', strtotime($value->duedate)) }}" readonly>
                                        </div>

                                      </div>
                                      <div class="form-group row">
                                        <div class="form-group col-lg-3">
                                          <label>PPN</label>
                                          <input class="form-control" value="{{ 'Rp. ' . $value->ppn }}"
                                            id="" readonly>
                                        </div>

                                        <div class="col-lg-3 form-group">
                                          <label>Total (Before PPN)</label>
                                          <input class="form-control" value="{{ 'Rp. ' . $value->total }}" readonly>
                                        </div>

                                        <div class="col-lg-3 form-group">
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
        //     var table = ,
        //         "columnDefs": [{
        //             "visible": false,
        //             "targets": 2
        //         }],
        //         "order": [
        //             [2, 'asc']
        //         ],
        //         "displayLength": 25,
        //         "drawCallback": function(settings) {
        //             var api = this.api();
        //             var rows = api.rows({
        //                 page: 'current'
        //             }).nodes();
        //             var last = null;

        //             api.column(2, {
        //                 page: 'current'
        //             }).data().each(function(group, i) {
        //                 if (last !== group) {
        //                     $(rows).eq(i).before(
        //                         '<tr class="group"><td colspan="4">' + group + '</td></tr>'
        //                     );

        //                     last = group;
        //                 }
        //             });
        //         }
        // });
        // Order by the grouping
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
