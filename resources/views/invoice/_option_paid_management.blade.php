<a class="btn btn-sm btn-primary" href="javascript:void(0)" data-bs-toggle="modal"
  data-bs-target="#markData{{ $invoice->id }}">
  MARK</a>

<!-- Verify Product Modal Start -->
<div class="modal fade" id="markData{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Mark as Paid Data:
          {{ $invoice->order_number }}</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
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
          <div class="form-group row">
            <div class="form-group col-lg-4">
              <label>PPN</label>
              <input class="form-control ppn" value="{{ 'Rp. ' . number_format($invoice->ppn) }}" readonly>
            </div>

            <div class="col-lg-4 form-group">
              <label>Total (Before PPN)</label>
              <input class="form-control total" value="{{ 'Rp. ' . number_format($invoice->total) }}" readonly>
            </div>

            <div class="col-lg-4 form-group">
              <label>Total (After PPN)</label>
              <input class="form-control total-after-ppn"
                value="{{ 'Rp. ' . number_format($invoice->total_after_ppn) }}" readonly>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
        <a href="{{ url('invoice/' . $invoice->id . '/mark_as_paid') }}"><button class="btn btn-primary"
            type="submit">Yes, Mark as Paid
          </button></a>
      </div>
    </div>
  </div>
</div>

<!-- Verify Product Modal End -->
