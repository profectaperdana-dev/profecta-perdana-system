<div class="btn-group">
    <a href="javascript:void(0)" data-bs-toggle="modal" data-original-title="test"
        data-bs-target="#detailData{{ $data->id }}" class=" text-nowrap code fw-bold text-success"
        type="text">{{ $data->order_number }}</a> <span>&nbsp;</span>

</div>

<!-- Modal -->
<div class="modal" id="detailData{{ $data->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title no-print" id="exampleModalLabel">Detail
                    {{ $data->order_number }}</h6>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>
                                Customer</label>
                            <input type="text" readonly value="{{ $data->customerBy->name_cust }}"
                                class="form-control">
                        </div>
                        <div class="col-lg-6 form-group mr-5">
                            <label>Payment Method</label>
                            <select disabled class="form-control">
                                <option value="" selected>-Choose Payment-</option>
                                <option value="1" @if ($data->payment_method == 1) selected @endif>
                                    Cash On Delivery
                                </option>
                                <option value="2" @if ($data->payment_method == 2) selected @endif>
                                    Cash Before Delivery
                                </option>
                                <option value="3" @if ($data->payment_method == 3) selected @endif>
                                    Credit
                                </option>
                            </select>

                        </div>
                        <div class="col-12 form-group">
                            <label>Remark</label>
                            <textarea class="form-control" name="remark" id="" cols="30" rows="1" readonly>{{ $data->remark }}</textarea>
                        </div>
                    </div>
                    <div class="form-group formSo-edit">
                        @foreach ($data->salesOrderDetailsBy as $detail)
                            <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                <div class="form-group col-12 col-lg-4">
                                    <label>Product</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}">
                                </div>

                                <div class="col-4 col-lg-1 form-group">
                                    <label>Qty</label>
                                    <input type="text" class="form-control cekQty-edit" readonly
                                        value="{{ $detail->qty }}" />
                                    <small class="text-danger qty-warning" hidden>The number of items exceeds
                                        the
                                        stock</small>
                                </div>
                                @php
                                    
                                    $price = str_replace(',', '.', $detail->productSales->harga_jual_nonretail);
                                    $sub_total = (float) $price * (float) $ppn;
                                    (float) ($harga = (float) $price + (float) $sub_total);
                                @endphp
                                <div class="col-4 col-lg-2 form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ number_format(round($harga)) }}" />
                                </div>
                                <div class="col-4 col-lg-1 form-group">
                                    <label>Disc (%)</label>
                                    <input type="text" readonly min="0"
                                        class="form-control discount-append-edit" placeholder="Disc"
                                        value="{{ $detail->discount }}" />

                                </div>

                                <div class="col-6 col-lg-2 form-group">
                                    <label>Disc (Rp)</label>
                                    <input type="text" readonly class="form-control discount_rp" placeholder="Disc"
                                        value="{{ $detail->discount_rp }}" />
                                </div>
                                @php
                                    $disc = (float) $detail->discount / 100.0;
                                    $ppn_cost = (float) $price * (float) $ppn;
                                    $ppn_total = (float) $price + $ppn_cost;
                                    $disc_cost = (float) $ppn_total * $disc;
                                    $price_disc = (float) ($ppn_total - $disc_cost - $detail->discount_rp);
                                @endphp
                                <div class="col-6 col-lg-2 form-group">
                                    <label>Disc Price</label>
                                    <input type="text" class="form-control price" readonly
                                        value="{{ number_format(round($price_disc)) }}" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4 form-group">
                            <label>Total (Excl. PPN)</label>
                            <input class="form-control total" value="{{ 'Rp. ' . number_format($data->total) }}"
                                readonly>
                        </div>

                        <div class="form-group col-lg-4">
                            <label>PPN</label>
                            <input class="form-control ppn" value="{{ 'Rp. ' . number_format($data->ppn) }}"
                                id="" readonly>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label>Total (Incl. PPN)</label>
                            <input class="form-control total-after-ppn"
                                value="{{ 'Rp. ' . number_format($data->total_after_ppn) }}" readonly>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button class="btn  btn-danger " type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
