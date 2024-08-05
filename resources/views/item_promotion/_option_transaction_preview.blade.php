{{-- ! button action --}}
<a class="text-success fw-bold text-nowrap" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailData{{ $invoice->id }}">
    {{ $invoice->order_number }}</a>


<div class="currentModal">
    {{-- ! end button action --}}
<div class="modal" id="detailData{{ $invoice->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title no-print" id="exampleModalLabel">Detail
                    {{ $invoice->order_number }}</h6>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <label>
                                Customer</label>
                            @if (is_numeric($invoice->id_customer))
                                <input type="text" readonly value="{{ $invoice->customerBy->name_cust }}"
                                    class="form-control">
                            @else
                                <input type="text" readonly value="{{ $invoice->id_customer }}" class="form-control">
                            @endif

                        </div>
                        <div class="col-lg-4 form-group">
                            <label>
                                Warehouse</label>
                            <input type="text" readonly value="{{ $invoice->warehouseBy->warehouses }}"
                                class="form-control">
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>
                                Address</label>
                            <input type="text" readonly value="{{ $invoice->address }}" class="form-control">
                        </div>

                        <div class="col-12 form-group">
                            <label>Remark</label>
                            <textarea class="form-control" name="remark" id="" cols="30" rows="1" readonly>{{ $invoice->remark }}</textarea>
                        </div>
                    </div>
                    <div class="form-group formSo-edit">
                        @foreach ($invoice->transactionDetailBy as $detail)
                            <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                <div class="form-group col-12 col-lg-6">
                                    <label>Product</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $detail->itemBy->name }}">
                                </div>

                                <div class="col-4 col-lg-2 form-group">
                                    <label>Qty</label>
                                    <input type="text" class="form-control cekQty-edit" readonly
                                        value="{{ $detail->qty }}" />

                                </div>
                                <div class="col-4 col-lg-4 form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ number_format(round($detail->price), 0, '.', ',') }}" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group row justify-content-end">

                        <div class="col-lg-4 form-group ">
                            <label>Total</label>
                            <input class="form-control total-after-ppn"
                                value="{{ 'Rp. ' . number_format($invoice->total) }}" readonly>
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
</div>

</div>
