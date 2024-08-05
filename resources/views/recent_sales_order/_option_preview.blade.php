{{-- ! button action --}}
<a class="fw-bold text-nowrap text-success" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailData{{ $invoice->id }}">
    {{ $invoice->order_number }}</a>

<div class="currentModal">
    <div class="modal fade" data-bs-backdrop="static" id="detailData{{ $invoice->id }}" tabindex="-1" role="dialog"
    data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Sales
                    Order
                    :
                    {{ $invoice->order_number }}</h6>
                {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>
                                Customer</label>
                            <input type="text" readonly
                                value="{{ $invoice->customerBy->code_cust . ' - ' . $invoice->customerBy->name_cust }}"
                                class="form-control">
                        </div>
                        <div class="col-lg-6 form-group mr-5">
                            <label>Payment Method</label>
                            <select readonly class="form-control" disabled>
                                <option value="" selected>-Choose Payment-</option>
                                <option value="1" @if ($invoice->payment_method == 1) selected @endif>
                                    Cash On Delivery
                                </option>
                                <option value="2" @if ($invoice->payment_method == 2) selected @endif>
                                    Cash Before Delivery
                                </option>
                                <option value="3" @if ($invoice->payment_method == 3) selected @endif>
                                    Credit
                                </option>
                            </select>

                        </div>
                    </div>
                    <div class=" row">
                        <div class="col-12 form-group">
                            <label>Remark</label>
                            <textarea class="form-control" name="remark" id="" cols="30" rows="1" readonly>{{ $invoice->remark }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        @foreach ($invoice->salesOrderDetailsBy as $detail)
                            <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                <div class="form-group col-12 col-lg-8">
                                    <label>Product</label>
                                    <input readonly class="form-control" type="text"
                                        value="{{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}">

                                </div>

                                <div class="col-6 col-lg-2 form-group">
                                    <label>Qty</label>
                                    <input type="text" class="form-control cekQty-edit" readonly
                                        value="{{ $detail->qty }}" />
                                    <small class="text-danger qty-warning" hidden>The number of items exceeds
                                        the
                                        stock</small>
                                </div>

                                <div class="col-6 col-lg-2 form-group">
                                    <label>Disc (%)</label>
                                    <input type="text" readonly min="0"
                                        class="form-control discount-append-edit" placeholder="Disc"
                                        value="{{ $detail->discount }}" />

                                </div>

                                {{-- <div class="col-4 col-lg-3 form-group">
                                    <label>Disc (Rp)</label>
                                    <input type="text" readonly class="form-control discount_rp" placeholder="Disc"
                                        value="{{ $detail->discount_rp }}" />
                                </div> --}}
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4 form-group">
                            <label>Total (Excl. PPN)</label>
                            <input class="form-control total"
                                value="{{ 'Rp. ' . number_format($invoice->total, 0, '.', ',') }}" readonly>
                        </div>

                        <div class="form-group col-lg-4">
                            <label>PPN</label>
                            <input class="form-control ppn"
                                value="{{ 'Rp. ' . number_format($invoice->ppn, 0, '.', ',') }}" id=""
                                readonly>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label>Total (Incl. PPN)</label>
                            <input class="form-control total-after-ppn"
                                value="{{ 'Rp. ' . number_format($invoice->total_after_ppn, 0, '.', ',') }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group dropup">

                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- ! end button action --}}

