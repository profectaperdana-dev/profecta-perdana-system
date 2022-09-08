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
                              <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#deleteData{{ $value->id }}">Delete
                                Sales Order</a>
                            </div>
                          </td>

                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->order_number }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                          <td>{{ $value->customerBy->name_cust . ' (' . $value->customerBy->code_cust . ')' }}
                          </td>
                          @if ($value->payment_method == 1)
                            <td>COD</td>
                          @else
                            <td>CBD</td>
                          @endif
                          <td class="text-center"><a class="btn btn-primary btn-sm modal-btn" href="javascript:void(0)"
                              data-bs-toggle="modal" data-bs-target="#verifyData{{ $value->id }}">Verify</a>
                          </td>
                          <!-- Detail Product Modal Start -->
                          {{-- <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1" role="dialog"
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
                            </div> --}}
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
                  <table id="example1" class="display expandable-table text-capitalize table-hover" style="width:100%">
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
                              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-original-title="test"
                                data-bs-target="#deleteData{{ $value->id }}">Delete
                                Sales Order</a>
                            </div>

                          </td>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->order_number }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                          <td>{{ date('d-M-Y', strtotime($value->duedate)) }}</td>
                          <td>{{ $value->customerBy->name_cust . ' (' . $value->customerBy->code_cust . ')' }}
                          </td>
                          <td class="text-center"><a type="button" class="btn btn-primary btn-sm modal-btn"
                              href="javascript:void(0)" data-bs-toggle="modal"
                              data-bs-target="#verifyData{{ $value->id }}">Verify</a>
                          </td>

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

    <div class="row">
      <div class="col-sm-12 col-xl-12 xl-100">
        <div class="card">
          <div class="card-header pb-0">
            <h5>All Data Sales Order Rejected</h5>
          </div>
          <div class="card-body">

            <p class="mb-0 m-t-30">
            <div class="table-responsive">
              <table id="example2" class="display expandable-table text-capitalize table-hover" style="width:100%">
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
                  @foreach ($dataSalesOrderReject as $value)
                    <tr>
                      <td style="width: 5%">
                        <a href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            data-feather="settings"></i></a>
                        <div class="dropdown-menu" aria-labelledby="">
                          <h5 class="dropdown-header">Actions</h5>
                          <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                            data-bs-target="#deleteData{{ $value->id }}">Delete
                            Sales Order</a>
                        </div>
                      </td>

                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $value->order_number }}</td>
                      <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                      <td>{{ $value->customerBy->name_cust . ' (' . $value->customerBy->code_cust . ')' }}
                      </td>
                      @if ($value->payment_method == 1)
                        <td>COD</td>
                      @else
                        <td>CBD</td>
                      @endif
                      <td class="text-center"><a class="btn btn-primary btn-sm modal-btn" href="javascript:void(0)"
                          data-bs-toggle="modal" data-bs-target="#verifyData{{ $value->id }}">Verify</a>
                      </td>
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

  @foreach ($dataSalesOrder as $value)
    <!-- Verify Product Modal Start -->
    <div class="modal fade" id="verifyData{{ $value->id }}" tabindex="-1" data-bs-keyboard="false"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Sales
              Order
              :
              {{ $value->order_number }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ url('sales_orders/' . $value->id . '/verify') }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <div class="container-fluid">
                <div class="form-group row">
                  <div class="col-md-6 form-group">
                    <label>
                      Customers</label>
                    <select name="customer_id" id="" required
                      class="form-control sub_type customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                      <option value="" selected>-Choose Customers-</option>
                      @foreach ($customer as $cust)
                        <option value="{{ $cust->id }}" @if ($cust->id == $value->customers_id) selected @endif>
                          {{ $cust->code_cust }} |
                          {{ $cust->name_cust }}
                        </option>
                      @endforeach
                    </select>
                    @error('customer_id')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="col-md-6 form-group mr-5">
                    <label>Payment Method</label>
                    <select name="payment_method" required
                      class="form-control sub_type {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                      <option value="" selected>-Choose Payment-</option>
                      <option value="1" @if ($value->payment_method == 1) selected @endif>
                        Cash On Delivery
                      </option>
                      <option value="2" @if ($value->payment_method == 2) selected @endif>
                        Cash Before Delivery
                      </option>
                      <option value="3" @if ($value->payment_method == 3) selected @endif>
                        Credit
                      </option>
                    </select>
                    @error('payment_method')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <hr>
                <div class="form-group row formSo-edit">
                  @foreach ($value->salesOrderDetailsBy as $detail)
                    <div class="form-group row">
                      <input type="hidden" class="loop" value="{{ $loop->index }}">
                      <div class="form-group col-6">
                        <label>Product</label>
                        <select name="editProduct[{{ $loop->index }}][products_id]" required
                          class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}">
                          @if ($detail->products_id != null)
                            <option value="{{ $detail->products_id }}" selected>
                              {{ $detail->productSales->nama_barang .
                                  ' (' .
                                  $detail->productSales->sub_types->type_name .
                                  ', ' .
                                  $detail->productSales->sub_materials->nama_sub_material .
                                  ')' }}
                            </option>
                          @endif
                        </select>
                        @error('editProduct[' . $loop->index . '][products_id]')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      <div class="col-2 col-md-2 form-group">
                        <label>Qty</label>
                        <input type="number" class="form-control cekQty-edit"
                          name="editProduct[{{ $loop->index }}][qty]" value="{{ $detail->qty }}" />
                        <small class="text-danger qty-warning" hidden>The number of items exceeds
                          the
                          stock</small>
                        @error('top')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      <div class="col-2 col-md-2 form-group">
                        <label>Disc(%)</label>
                        <input type="number" class="form-control discount-append-edit" placeholder="Disc"
                          name="editProduct[{{ $loop->index }}][discount]" value="{{ $detail->discount }}" />
                        @error('editProduct[{{ $loop->index }}][discount]')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      @if ($loop->index == 0)
                        <div class="col-1 col-md-2 form-group">
                          <label for="">&nbsp;</label>
                          <a href="javascript:void(0)" class="btn btn-success form-control text-white addSo-edit">+</a>
                        </div>
                      @else
                        <div class="col-1 col-md-2 form-group">
                          <label for="">&nbsp;</label>
                          <a href="javascript:void(0)" class="btn btn-danger form-control text-white remSo-edit">-</a>
                        </div>
                      @endif
                    </div>
                  @endforeach
                </div>
                <hr>
                <div class="form-group row">
                  <div class="col-12 form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" name="remark" id="" cols="30" rows="5">{{ $value->remark }}</textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="form-group col-12">
                    <button type="button" class="col-12 btn btn-outline-success btn-reload">--
                      Click this to
                      reload total
                      --</button>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="form-group col-lg-4">
                    <label>PPN</label>
                    <input class="form-control ppn" value="{{ 'Rp. ' . number_format($value->ppn) }}" id=""
                      readonly>
                  </div>

                  <div class="col-lg-4 form-group">
                    <label>Total (Before PPN)</label>
                    <input class="form-control total" value="{{ 'Rp. ' . number_format($value->total) }}" readonly>
                  </div>

                  <div class="col-lg-4 form-group">
                    <label>Total (After PPN)</label>
                    <input class="form-control total-after-ppn"
                      value="{{ 'Rp. ' . number_format($value->total_after_ppn) }}" readonly>
                  </div>
                </div>

              </div>

          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Yes,
              verify
            </button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Verify Product Modal End -->

    <!-- Delete Product Modal Start -->
    <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form method="post" action="{{ url('sales_order/' . $value->id) }}" enctype="multipart/form-data">
          @csrf
          @method('delete')
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Delete Data:
                {{ $value->order_number }}</h5>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <div class="form-group row">
                  <div class="col-md-12">
                    <h5>Are you sure delete this data ?
                    </h5>
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
  @endforeach

  @foreach ($dataSalesOrderDebt as $value)
    <!-- Verify Product Modal Start -->
    <div class="modal fade" id="verifyData{{ $value->id }}" tabindex="-1" data-bs-keyboard="false"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Sales
              Order
              :
              {{ $value->order_number }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ url('sales_orders/' . $value->id . '/verify') }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <div class="container-fluid">
                <div class="form-group row">
                  <div class="col-md-6 form-group">
                    <label>
                      Customers</label>
                    <select name="customer_id" id="" required
                      class="form-control sub_type customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                      <option value="" selected>-Choose Customers-</option>
                      @foreach ($customer as $cust)
                        <option value="{{ $cust->id }}" @if ($cust->id == $value->customers_id) selected @endif>
                          {{ $cust->code_cust }} |
                          {{ $cust->name_cust }}
                        </option>
                      @endforeach
                    </select>
                    @error('customer_id')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="col-md-6 form-group mr-5">
                    <label>Payment Method</label>
                    <select name="payment_method" required
                      class="form-control sub_type {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                      <option value="" selected>-Choose Payment-</option>
                      <option value="1" @if ($value->payment_method == 1) selected @endif>
                        Cash On Delivery
                      </option>
                      <option value="2" @if ($value->payment_method == 2) selected @endif>
                        Cash Before Delivery
                      </option>
                      <option value="3" @if ($value->payment_method == 3) selected @endif>
                        Credit
                      </option>
                    </select>
                    @error('payment_method')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <hr>
                <div class="form-group row formSo-edit">
                  @foreach ($value->salesOrderDetailsBy as $detail)
                    <div class="form-group row">
                      <input type="hidden" class="loop" value="{{ $loop->index }}">
                      <div class="form-group col-6">
                        <label>Product</label>
                        <select name="editProduct[{{ $loop->index }}][products_id]" required
                          class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}">
                          @if ($detail->products_id != null)
                            <option value="{{ $detail->products_id }}" selected>
                              {{ $detail->productSales->nama_barang .
                                  ' (' .
                                  $detail->productSales->sub_types->type_name .
                                  ', ' .
                                  $detail->productSales->sub_materials->nama_sub_material .
                                  ')' }}
                            </option>
                          @endif
                        </select>
                        @error('editProduct[' . $loop->index . '][products_id]')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      <div class="col-2 col-md-2 form-group">
                        <label>Qty</label>
                        <input type="number" class="form-control cekQty-edit"
                          name="editProduct[{{ $loop->index }}][qty]" value="{{ $detail->qty }}" />
                        <small class="text-danger qty-warning" hidden>The number of items exceeds
                          the
                          stock</small>
                        @error('top')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      <div class="col-2 col-md-2 form-group">
                        <label>Disc(%)</label>
                        <input type="number" class="form-control discount-append-edit" placeholder="Disc"
                          name="editProduct[{{ $loop->index }}][discount]" value="{{ $detail->discount }}" />
                        @error('editProduct[{{ $loop->index }}][discount]')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      @if ($loop->index == 0)
                        <div class="col-1 col-md-2 form-group">
                          <label for="">&nbsp;</label>
                          <a href="javascript:void(0)" class="btn btn-success form-control text-white addSo-edit">+</a>
                        </div>
                      @else
                        <div class="col-1 col-md-2 form-group">
                          <label for="">&nbsp;</label>
                          <a href="javascript:void(0)" class="btn btn-danger form-control text-white remSo-edit">-</a>
                        </div>
                      @endif
                    </div>
                  @endforeach
                </div>
                <hr>
                <div class="form-group row">
                  <div class="col-12 form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" name="remark" id="" cols="30" rows="5">{{ $value->remark }}</textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="form-group col-12">
                    <button type="button" class="col-12 btn btn-outline-success btn-reload">--
                      Click this to
                      reload total
                      --</button>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="form-group col-lg-4">
                    <label>PPN</label>
                    <input class="form-control ppn" value="{{ 'Rp. ' . number_format($value->ppn) }}" id=""
                      readonly>
                  </div>

                  <div class="col-lg-4 form-group">
                    <label>Total (Before PPN)</label>
                    <input class="form-control total" value="{{ 'Rp. ' . number_format($value->total) }}" readonly>
                  </div>

                  <div class="col-lg-4 form-group">
                    <label>Total (After PPN)</label>
                    <input class="form-control total-after-ppn"
                      value="{{ 'Rp. ' . number_format($value->total_after_ppn) }}" readonly>
                  </div>
                </div>

              </div>

          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Yes,
              verify
            </button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Verify Product Modal End -->

    <!-- Delete Product Modal Start -->
    <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form method="post" action="{{ url('sales_order/' . $value->id) }}" enctype="multipart/form-data">
          @csrf
          @method('delete')
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Delete Data:
                {{ $value->order_number }}</h5>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
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
  @endforeach

  @foreach ($dataSalesOrderReject as $value)
    <!-- Verify Product Modal Start -->
    <div class="modal fade" id="verifyData{{ $value->id }}" tabindex="-1" data-bs-keyboard="false"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Sales
              Order
              :
              {{ $value->order_number }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ url('sales_orders/' . $value->id . '/verify') }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <div class="container-fluid">
                <div class="form-group row">
                  <div class="col-md-6 form-group">
                    <label>
                      Customers</label>
                    <select name="customer_id" id="" required
                      class="form-control sub_type customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                      <option value="" selected>-Choose Customers-</option>
                      @foreach ($customer as $cust)
                        <option value="{{ $cust->id }}" @if ($cust->id == $value->customers_id) selected @endif>
                          {{ $cust->code_cust }} |
                          {{ $cust->name_cust }}
                        </option>
                      @endforeach
                    </select>
                    @error('customer_id')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="col-md-6 form-group mr-5">
                    <label>Payment Method</label>
                    <select name="payment_method" required
                      class="form-control sub_type {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                      <option value="" selected>-Choose Payment-</option>
                      <option value="1" @if ($value->payment_method == 1) selected @endif>
                        Cash On Delivery
                      </option>
                      <option value="2" @if ($value->payment_method == 2) selected @endif>
                        Cash Before Delivery
                      </option>
                      <option value="3" @if ($value->payment_method == 3) selected @endif>
                        Credit
                      </option>
                    </select>
                    @error('payment_method')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <hr>
                <div class="form-group row formSo-edit">
                  @foreach ($value->salesOrderDetailsBy as $detail)
                    <div class="form-group row">
                      <input type="hidden" class="loop" value="{{ $loop->index }}">
                      <div class="form-group col-6">
                        <label>Product</label>
                        <select name="editProduct[{{ $loop->index }}][products_id]" required
                          class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}">
                          @if ($detail->products_id != null)
                            <option value="{{ $detail->products_id }}" selected>
                              {{ $detail->productSales->nama_barang .
                                  ' (' .
                                  $detail->productSales->sub_types->type_name .
                                  ', ' .
                                  $detail->productSales->sub_materials->nama_sub_material .
                                  ')' }}
                            </option>
                          @endif
                        </select>
                        @error('editProduct[' . $loop->index . '][products_id]')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      <div class="col-2 col-md-2 form-group">
                        <label>Qty</label>
                        <input type="number" class="form-control cekQty-edit"
                          name="editProduct[{{ $loop->index }}][qty]" value="{{ $detail->qty }}" />
                        <small class="text-danger qty-warning" hidden>The number of items exceeds
                          the
                          stock</small>
                        @error('top')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      <div class="col-2 col-md-2 form-group">
                        <label>Disc(%)</label>
                        <input type="number" class="form-control discount-append-edit" placeholder="Disc"
                          name="editProduct[{{ $loop->index }}][discount]" value="{{ $detail->discount }}" />
                        @error('editProduct[{{ $loop->index }}][discount]')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                      @if ($loop->index == 0)
                        <div class="col-1 col-md-2 form-group">
                          <label for="">&nbsp;</label>
                          <a href="javascript:void(0)" class="btn btn-success form-control text-white addSo-edit">+</a>
                        </div>
                      @else
                        <div class="col-1 col-md-2 form-group">
                          <label for="">&nbsp;</label>
                          <a href="javascript:void(0)" class="btn btn-danger form-control text-white remSo-edit">-</a>
                        </div>
                      @endif
                    </div>
                  @endforeach
                </div>
                <hr>
                <div class="form-group row">
                  <div class="col-12 form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" name="remark" id="" cols="30" rows="5">{{ $value->remark }}</textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="form-group col-12">
                    <button type="button" class="col-12 btn btn-outline-success btn-reload">--
                      Click this to
                      reload total
                      --</button>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="form-group col-lg-4">
                    <label>PPN</label>
                    <input class="form-control ppn" value="{{ 'Rp. ' . number_format($value->ppn) }}" id=""
                      readonly>
                  </div>

                  <div class="col-lg-4 form-group">
                    <label>Total (Before PPN)</label>
                    <input class="form-control total" value="{{ 'Rp. ' . number_format($value->total) }}" readonly>
                  </div>

                  <div class="col-lg-4 form-group">
                    <label>Total (After PPN)</label>
                    <input class="form-control total-after-ppn"
                      value="{{ 'Rp. ' . number_format($value->total_after_ppn) }}" readonly>
                  </div>
                </div>

              </div>

          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Yes,
              verify
            </button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Verify Product Modal End -->

    <!-- Delete Product Modal Start -->
    <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form method="post" action="{{ url('sales_order/' . $value->id) }}" enctype="multipart/form-data">
          @csrf
          @method('delete')
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Delete Data:
                {{ $value->order_number }}</h5>
              <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
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

        $('#example2').DataTable({
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
    <script>
      $(document).ready(function() {
        let csrf = $('meta[name="csrf-token"]').attr("content");

        $(document).on("click", ".modal-btn", function() {

          let modal_id = $(this).attr('data-bs-target');
          //Get Customer ID
          let customer_id = $(modal_id).find('.customer-append').val();
          $(modal_id).find(".productSo-edit").select2({
            width: "100%",
            ajax: {
              context: this,
              type: "GET",
              url: "/products/select",
              data: function(params) {
                return {
                  _token: csrf,
                  q: params.term, // search term
                  c: customer_id
                };
              },
              dataType: "json",
              delay: 250,
              processResults: function(data) {
                return {
                  results: $.map(data, function(item) {
                    return [{
                      text: item.nama_barang +
                        " (" +
                        item.type_name +
                        ", " +
                        item.nama_sub_material +
                        ")",
                      id: item.id,
                    }, ];
                  }),
                };
              },
            },
          });

          //Get Customer ID
          $(modal_id).find(".customer-append").change(function() {
            customer_id = $(modal_id).find(".customer-append").val();
          });
          let x = $(modal_id)
            .find('.modal-body')
            .find('.formSo-edit')
            .children('.form-group')
            .last()
            .find('.loop')
            .val();
          //Get discount depent on product
          $(modal_id).on("change", ".productSo-edit", function() {
            let product_id = $(this).val();
            let parent_product = $(this).parent('.form-group').siblings('.form-group').find(
              ".discount-append-edit");
            $.ajax({
              context: this,
              type: "GET",
              url: "/discounts/select" + "/" + customer_id + "/" + product_id,
              dataType: "json",
              success: function(data) {
                if (data.discount != null) {
                  parent_product.val(data.discount);
                } else {
                  parent_product.val(0);
                }
              },
            });
          });
          $(modal_id).on("input", ".cekQty-edit", function() {
            let qtyValue = $(this).val();
            let product_id = $(this).parent('.form-group').siblings('.form-group').find(
              '.productSo-edit').val();
            let id = customer_id;

            $.ajax({
              context: this,
              type: "GET",
              url: "/stocks/cekQty/" + product_id,
              data: {
                _token: csrf,
                c: id,
              },
              dataType: "json",
              delay: 250,
              success: function(data) {
                if (parseInt(qtyValue) > parseInt(data.stock)) {
                  $(this).parent().find(".qty-warning").removeAttr(
                    "hidden");
                  $(this).addClass("is-invalid");
                } else {
                  $(this)
                    .parent()
                    .find(".qty-warning")
                    .attr("hidden", "true");
                  $(this).removeClass("is-invalid");
                }
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus);
                alert("Error: " + errorThrown);
              },
            });
          });
          $(modal_id).on("click", ".addSo-edit", function() {
            ++x;
            var form =
              '<div class="form-group row">' +
              '<input type="hidden" class="loop" value="' + x + '">' +
              '<div class="form-group col-6">' +
              "<label>Product</label>" +
              '<select name="editProduct[' +
              x +
              '][products_id]" class="form-control productSo-edit" required>' +
              '<option value="">Choose Product</option> ' +
              "</select>" +
              "</div>" +
              '<div class="col-2 col-md-2 form-group">' +
              "<label> Qty </label> " +
              '<input type="number" class="form-control cekQty-edit" required name="editProduct[' +
              x +
              '][qty]">' +
              '<small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>' +
              "</div> " +
              '<div class="col-2 col-md-2 form-group">' +
              "<label>Disc(%)</label>" +
              '<input type="number" class="form-control discount-append-edit" name="editProduct[' +
              x +
              '][discount]" id="">' +
              "</div>" +
              '<div class="col-1 col-md-2 form-group">' +
              '<label for=""> &nbsp; </label>' +
              '<a class="btn btn-danger form-control text-white remSo-edit text-center">' +
              "- </a> " +
              "</div>" +
              " </div>";
            $(modal_id).find(".formSo-edit").append(form);

            $(modal_id).find(".productSo-edit").select2({
              width: "100%",
              ajax: {
                type: "GET",
                url: "/products/select",
                data: function(params) {
                  return {
                    _token: csrf,
                    q: params.term, // search term
                    c: customer_id
                  };
                },
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                  return {
                    results: $.map(data, function(item) {
                      return [{
                        text: item.nama_barang +
                          " (" +
                          item.type_name +
                          ", " +
                          item.nama_sub_material +
                          ")",
                        id: item.id,
                      }, ];
                    }),
                  };
                },
              },
            });
          });

          //remove Sales Order fields
          $(modal_id).on("click", ".remSo-edit", function() {
            $(this).closest(".row").remove();
          });

          //reload total
          $(modal_id).on('click', '.btn-reload', function() {
            let ppn = 0;
            let total = 0;
            let total_after_ppn = 0;
            $(modal_id).find('.productSo-edit').each(function() {
              let product_id = $(this).val();
              let cost = function() {
                let temp = 0;
                $.ajax({
                  async: false,
                  context: this,
                  type: "GET",
                  url: "/products/selectCost/" + product_id,
                  dataType: "json",
                  success: function(data) {
                    temp = data.harga_jual_nonretail
                  },
                });
                return temp;
              }();

              let qty = $(this).parent().siblings().find('.cekQty-edit').val();
              let disc = $(this).parent().siblings().find('.discount-append-edit')
                .val() / 100;
              let disc_cost = cost * disc;
              let cost_after_disc = cost - disc_cost;
              total = total + (cost_after_disc * qty);
              //   alert($(this).parent().siblings().find('.cekQty-edit').val());
            });

            ppn = total * 0.11;
            total_after_ppn = total + ppn;
            $(this).closest('.row').siblings().find('.ppn').val('Rp. ' + Math.round(ppn)
              .toLocaleString('us', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              }));
            $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(total)
              .toLocaleString(
                'us', {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 0
                }));
            $(this).closest('.row').siblings().find('.total-after-ppn').val('Rp. ' + Math
              .round(
                total_after_ppn).toLocaleString('us', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              }));
          });
          $(modal_id).on('hidden.bs.modal', function() {
            $(modal_id).unbind();
          });
        });
      });
    </script>
  @endpush
@endsection
