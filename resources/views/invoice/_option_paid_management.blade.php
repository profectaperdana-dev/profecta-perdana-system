<a class="btn btn-sm btn-primary update-btn" href="javascript:void(0)" data-bs-toggle="modal"
  data-bs-target="#markData{{ $invoice->id }}">
  UPDATE
</a>

<!-- Verify Product Modal Start -->
<div class="modal fade" id="markData{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Update Paid Data:
          {{ $invoice->order_number }}</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <input type="hidden" class="id" value="{{ $invoice->id }}">
          <input type="hidden" class="totalraw" value="{{ $invoice->total_after_ppn }}">
          <form action="{{ url('invoice/' . $invoice->id . '/update_payment') }}" method="POST">
            @csrf
            <div class="form-group row">
              <div class="col-lg-6 form-group">
                <label>Pay Amount</label>
                <input type="number" name="amount" class="form-control" required>
              </div>
              <div class="col-lg-3 form-group">
                <label>&nbsp;</label>
                <button class="form-control btn btn-primary" type="submit">Update</button>
              </div>
            </div>
          </form>

          <hr>

          <div class="form-group row">
            <div class="col-lg-4 form-group">
              <label>Total (Include PPN)</label>
              <input class="form-control total-after-ppn"
                value="{{ 'Rp. ' . number_format($invoice->total_after_ppn, 0, ',', '.') }}" readonly>
            </div>

            <div class="col-lg-4 form-group">
              <label>Total Instalment</label>
              <input class="form-control total-instalment" readonly>
            </div>

            <div class="col-lg-4 form-group">
              <label>Remaining Instalment</label>
              <input class="form-control remaining-instalment" value="{{ 'Rp. ' . number_format($invoice->total) }}"
                readonly>
            </div>
          </div>

          <hr>

          <div class="form-group row">
            <div class="form-group row">
              <div class="col-md-6 form-group">
                <label>
                  Customers</label>
                <input type="text" class="form-control" value="{{ $invoice->customerBy->name_cust }}" readonly>
              </div>
              <div class="col-md-6 form-group mr-5">
                <label>Payment Method</label>
                <input type="text" class="form-control"
                  @if ($invoice->payment_method == 1) value="Cash On Delivery"
              @elseif($invoice->payment_method == 2)
                value="Cash Before Delivery"
              @else
                value="Credit" @endif
                  readonly>
              </div>
            </div>
          </div>
          <hr>
          <div class="form-group row formSo-edit">
            @foreach ($invoice->salesOrderDetailsBy as $detail)
              <div class="form-group row">
                <div class="form-group col-6">
                  <label>Product</label>
                  <input type="text" class="form-control"
                    value="{{ $detail->productSales->nama_barang .
                        ' (' .
                        $detail->productSales->sub_types->type_name .
                        ', ' .
                        $detail->productSales->sub_materials->nama_sub_material .
                        ')' }}"
                    readonly>
                </div>

                <div class="col-3 col-md-3 form-group">
                  <label>Qty</label>
                  <input type="text" class="form-control" value="{{ $detail->qty }}" readonly />
                </div>

                <div class="col-3 col-md-3 form-group">
                  <label>Disc(%)</label>
                  <input type="text" class="form-control" value="{{ $detail->discount }}" readonly />
                </div>
              </div>
            @endforeach
          </div>
          <hr>
          <div class="form-group row">
            <div class="col-12 form-group">
              <label>Remarks</label>
              <textarea class="form-control" name="remark" id="" cols="30" rows="5" readonly>{{ $invoice->remark }}</textarea>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-target="#historyData{{ $invoice->id }}"
          data-bs-toggle="modal" data-bs-dismiss="modal">History</button>
        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- Verify Product Modal End -->

<!-- History Payment -->
<div class="modal fade" id="historyData{{ $invoice->id }}" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          History Payment Data:
          {{ $invoice->order_number }}</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">


          <div class="form-group row">
            <div class="table-responsive">
              <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Payment Date</th>
                    <th>Amount</th>

                  </tr>
                </thead>
                <tbody>
                  @foreach ($invoice->salesOrderCreditsBy as $detail)
                    <tr>

                      <td>{{ $loop->iteration }}</td>
                      <td>{{ date('d-M-Y', strtotime($detail->payment_date)) }}</td>
                      <td>{{ 'Rp. ' . number_format($detail->amount, 0, ',', '.') }}</td>

                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-target="#markData{{ $invoice->id }}"
          data-bs-toggle="modal" data-bs-dismiss="modal">Payment</button>
        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- End History Payment -->
