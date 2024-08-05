<a class="link-success update-btn" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#markData{{ $id_vendor }}">
    {{ $name_vendor }}
</a>

<div class="currentModal">
    <!-- Verify Product Modal Start -->
<div class="modal" id="markData{{ $id_vendor }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Update Paid Data:
                    {{ $name_vendor }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-md table-light display expandable-table text-capitalize" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                                                        <th>Purchase Number</th>

                                                                        <th>Order Date</th>
                                    <th>Due Date</th>

                                    <th>Remark</th>
                                    <th class="text-center">Sub Credit Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->sortBy('order_date') as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                          <td><a href="javascript:void(0)" class="link-success openSettlement"
                                                data-id="{{ $item->id }}">{{ $item->order_number }}</a></td>
                                                                                <td>{{ date('d F Y', strtotime($item->order_date)) }}</td>
                                        <td>{{ date('d F Y', strtotime($item->due_date)) }}</td>

                                      
                                                <td>{{$item->remark}}</td>
                                        <td class="text-end">
                                            {{ number_format($item->total - $item->purchaseOrderCreditsBy->sum('amount') - $item->purchaseOrderReturnBy->sum('total')) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="5" class="fw-bold">Total</td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($total_purchase - $total_credit - $total_return) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <br>

                    <div class="settlement-parent">
                        @foreach ($purchase as $item)
                            <div class="card settlement-section{{ $item->id }}" hidden>
                                <div class="card-header text-start fw-bold fs-6">
                                    <div>
                                        Settlement of {{ $item->order_number }}
                                    </div>
                                    <button class="btn-close bg-white close-settlement" type="button"
                                        aria-label="Close"></button>
                                </div>

                                <div class="card-body">
                                    <div>
                                        <form action="{{ url('purchase_orders/' . $item->id . '/update_payment') }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-group row bg-primary payParent">
                                                <div class="form-group row pt-2 pay">
                                                    <div class="col-lg-12 form-group">
                                                        <label>Cash & Bank</label>
                                                        <select name="coa_code" id=""
                                                            class="form-control cash-bank" required>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 form-group">
                                                        <label>Pay Amount</label>
                                                        <select name="pay[0][amount_method]" id=""
                                                            class="form-control payment amount-method" required>
                                                            <option value="full">Full Payment</option>
                                                            <option value="part">Partial Payment</option>
                                                        </select>
                                                        <input type="text" class="form-control total"
                                                            placeholder="Enter amount..." hidden>
                                                        <input type="hidden" name="pay[0][amount]"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-lg-3 form-group">
                                                        <label>Pay Date</label>
                                                        <input class="datepicker-here form-control digits"
                                                            data-position="bottom left" type="text"
                                                            data-language="en" name="pay[0][payment_date]"
                                                            autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-3 form-group">
                                                        <label>Payment Method</label>
                                                        <select name="pay[0][payment_method]" id=""
                                                            class="form-control payment">
                                                            <option value="Transfer">Transfer</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Trade In">Trade In</option>
                                                            <option value="Rebate">Rebate</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4 col-lg-2 form-group">
                                                        <label>&nbsp;</label>

                                                        <a href="javascript:void(0)"
                                                            class="form-control text-white text-center addPay"
                                                            style="border:none; background-color:#38a34c">+
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <div class="col-lg-2 form-group">
                                                    <button class="form-control btn btn-success text-white"
                                                        type="submit">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="form-group row">
                                        <form action="{{ url('purchase_orders/' . $item->id . '/cancel_payment') }}"
                                            method="POST">
                                            <div class="table-responsive">
                                                <table class="table table-md table-light display expandable-table text-capitalize" style="width:100%">
                                                    <caption>Settlement History</caption>
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Payment Date</th>
                                                            <th>Payment Method</th>
                                                            <th>Amount</th>
                                                            {{-- <th>Cancel</th> --}}

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item->purchaseOrderCreditsBy as $detail)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ date('d F Y', strtotime($detail->payment_date)) }}
                                                                </td>
                                                                <td>{{ $detail->payment_method }}</td>
                                                                <td class="text-end credit-amount">
                                                                    {{ number_format($detail->amount) }}</td>
                                                                {{-- <td
                                                                    class="d-flex justify-content-evenly align-items-center">
                                                                    <div>
                                                                        <input type="text"
                                                                            class="form-control total cancel-amount"
                                                                            placeholder="Enter amount..."> --}}
                                                                <input type="hidden"
                                                                    name="cancel[{{ $loop->index }}][amount]"
                                                                    class="form-control" value="{{ $detail->amount }}">
                                                                <input type="hidden"
                                                                    name="cancel[{{ $loop->index }}][credit_id]"
                                                                    value="{{ $detail->id }}">
                                                                {{-- </div>
                                                                    <div>
                                                                        <a href="javascript:void(0)"
                                                                            class="link-success align-self-center cancel-full">Full</a>
                                                                    </div> --}}
                                                                {{-- </td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        @if ($item->purchaseOrderCreditsBy->count() > 0)
                                                            <tr style="border: 0 !important">
                                                                <td colspan="3" style="border: 0 !important"></td>
                                                                <td class="d-flex justify-content-end"
                                                                    style="border: 0 !important">
                                                                    <div><button type="submit"
                                                                            class="btn btn-danger btn-sm text-white">Cancel
                                                                            Payment</button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr style="border: 0 !important">
                                                                <td colspan="4" class="text-center"
                                                                    style="border: 0 !important">There
                                                                    is no payment</td>

                                                            </tr>
                                                        @endif

                                                        <tr class="fw-bold">
                                                            <td colspan="3">Total Instalment</td>
                                                            <input type="hidden"
                                                                value="{{ $item->purchaseOrderCreditsBy->sum('amount') }}"
                                                                class="total-instalment">
                                                            <td class="text-end total-instalment-text">
                                                                {{ number_format($item->purchaseOrderCreditsBy->sum('amount')) }}
                                                            </td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td colspan="3">Total Purchase</td>
                                                            <td class="text-end">
                                                                {{ number_format($item->total) }}
                                                            </td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td colspan="3" class="text-danger">Total Return</td>
                                                            <td class="text-end text-danger">
                                                                {{ number_format($item->purchaseOrderReturnBy->sum('total')) }}
                                                            </td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td colspan="3">Remaining Instalment</td>
                                                            <td class="text-end text-danger">
                                                                {{ number_format($item->total - $item->purchaseOrderCreditsBy->sum('amount') - $item->purchaseOrderReturnBy->sum('total')) }}
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- <input type="hidden" class="id" value="{{ $purchase->id }}">
                    <input type="hidden" class="totalraw" value="{{ $purchase->total - $total_return }}">
                    <form action="{{ url('purchase_orders/' . $purchase->id . '/update_payment') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-5 form-group">
                                <label>Pay Amount</label>
                                <input type="text" class="form-control total" required>
                                <input type="hidden" name="amount" class="form-control" required>
                            </div>
                            <div class="col-lg-5 form-group">
                                <label>Pay Date</label>
                                <input class="datepicker-here form-control digits" data-position="bottom left"
                                    type="text" data-language="en" name="payment_date" autocomplete="off">
                            </div>
                            <div class="col-lg-2 form-group">
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
                                value="{{ 'Rp. ' . number_format($purchase->total - $total_return, 0, ',', '.') }}"
                                readonly>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label>Total Instalment</label>
                            <input class="form-control total-instalment" readonly>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label>Remaining Instalment</label>
                            <input class="form-control remaining-instalment"
                                value="{{ 'Rp. ' . number_format($purchase->total, 0, ',', '.') }}" readonly>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <div class="form-group row">
                            <div class="col-md-6 form-group">
                                <label>
                                    Vendor</label>
                                <input type="text" class="form-control"
                                    value="{{ $purchase->supplierBy->nama_supplier }}" readonly>
                            </div>
                            <div class="col-md-6 form-group mr-5">
                                <label>Payment Method</label>
                                <input type="text" class="form-control"
                                    value="{{ ucfirst($purchase->payment_method) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remark" id="" cols="30" rows="5" readonly>{{ $purchase->remark }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row formSo-edit">
                        @foreach ($purchase->purchaseOrderDetailsBy as $detail)
                            <div class="form-group rounded row bg-primary pt-2 mb-3">
                                <div class="form-group col-12 col-lg-6">
                                    <label>Product</label>
                                    <input type="text" class="form-control"
                                        value="{{ $detail->productBy->sub_materials->nama_sub_material . ' ' . $detail->productBy->sub_types->type_name . ' ' . $detail->productBy->nama_barang }}"
                                        readonly>
                                </div>
                                <div class="col-12 col-lg-6 form-group">
                                    <label>Qty</label>
                                    <input type="text" class="form-control" value="{{ $detail->qty }}" readonly />
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr> --}}


                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- Verify Product Modal End -->

<!-- History Payment -->
{{-- <div class="modal" id="historyData{{ $purchase->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    History Payment Data:
                    {{ $purchase->order_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">


                    <div class="form-group row">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Payment Date</th>
                                        <th>Amount</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase->purchaseOrderCreditsBy as $detail)
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
                <button class="btn btn-secondary" type="button" data-bs-target="#markData{{ $purchase->id }}"
                    data-bs-toggle="modal" data-bs-dismiss="modal">Payment</button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div> --}}
<!-- End History Payment -->
</div>

