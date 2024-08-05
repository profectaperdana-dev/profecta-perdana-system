{{-- ! button action --}}
<a class="text-success fw-bold text-nowrap" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailData{{ $invoice->id }}">
    {{ $invoice->order_number }}</a>

<!-- Button trigger modal -->


<div class="currentModal">
{{-- <div class="modal" id="receipt{{ $invoice->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Cash Receipt {{ $invoice->order_number }}</h6>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped" style="width:100%">
                    <caption class="text-info">*Settlement History</caption>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Payment Date</th>
                            <th class="text-center">Payment Method</th>
                            <th class="text-center">Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->salesOrderCreditsBy as $detail)
                            <tr>
                                <td class="text-center"> {{ $loop->iteration }}</td>
                                <td class="text-center">
                                    {{ date('d F Y', strtotime($detail->payment_date)) }}
                                </td>
                                <td class="text-center">{{ $detail->payment_method }}</td>
                                <td class="text-end">

                                    {{ number_format($detail->amount, 0, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td class="text-end" colspan="3">Total Instalment</td>
                            <td class="text-end">
                                {{ number_format($invoice->salesOrderCreditsBy->sum('amount'), 0, '.', ',') }}
                            </td>
                        </tr>
                        <tr class="fw-bold">
                            <td class="text-end" colspan="3">Total Invoice</td>
                            <td class="text-end">
                                {{ number_format($invoice->total_after_ppn, 0, '.', ',') }}
                            </td>
                        </tr>
                        <tr class="fw-bold">
                            <td class="text-end" colspan="3">Remaining Instalment</td>
                            <td class="text-end text-danger">
                                {{ number_format($invoice->total_after_ppn - $invoice->salesOrderCreditsBy->sum('amount'), 0, '.', ',') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                        data-original-title="test" data-bs-target="#detailData{{ $invoice->id }}"
                        data-bs-dismiss="modal">Back
                    </button>
                    <button type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-primary" target="popup"
                        onclick="window.open('{{ url('invoice/' . $invoice->id . '/cash_receipt') }}','name','width=600,height=400')">Print</a>
                </div>
            </div>
        </div>
    </div>
</div> --}}


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
                        <div class="col-lg-6 form-group">
                            <label>
                                Vendor</label>
                            <input type="text" readonly value="{{ $invoice->supplierBy->name }}"
                                class="form-control">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>
                                Warehouse</label>
                            <input type="text" readonly value="{{ $invoice->warehouseBy->warehouses }}"
                                class="form-control">
                        </div>
                        <div class="col-12 form-group">
                            <label>Remark</label>
                            <textarea class="form-control" name="remark" id="" cols="30" rows="1" readonly>{{ $invoice->remark }}</textarea>
                        </div>
                    </div>
                    <div class="form-group formSo-edit">
                        @foreach ($invoice->purchaseDetailBy as $detail)
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
                                        value="{{ number_format(round($detail->price)) }}" />
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
