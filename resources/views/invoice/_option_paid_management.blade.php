<a class="text-success fw-bold text-nowrap update-btn" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#markData{{ $id_cust }}">
    {{ $code_cust }} - {{ $name_cust }}
</a>

<div class="currentModal">
    <!-- Verify Product Modal Start -->
<div class="modal" id="markData{{ $id_cust }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    Update Paid
                    {{ $code_cust }} - {{ $name_cust }}</h6>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-sm table-light display expandable-table table-striped" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Order Date</th>
                                    <th class="text-center">Sub Credit Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->sortBy('order_date') as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td><a href="javascript:void(0)" class="link-success openSettlement"
                                                data-id="{{ $item->id }}">{{ $item->order_number }}</a></td>
                                        <td>{{ date('d F Y', strtotime($item->order_date)) }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->total_after_ppn - $item->salesOrderCreditsBy->sum('amount') - $item->returnBy->sum('total'), 0, '.', ',') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="3" class="fw-bold text-center">Total</td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($total_sale - $total_credit - $total_return, 0, '.', ',') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <br>

                    <div class="settlement-parent">
                        @foreach ($invoice as $item)
                            <div class="card settlement-section{{ $item->id }}" hidden>
                                <div class="card-header text-start fw-bold fs-6">
                                    <div>
                                        Settlement of {{ $item->order_number }}
                                    </div>
                                    <button class="btn-close bg-white close-settlement" type="button"
                                        aria-label="Close"></button>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-md table-light display expandable-table text-capitalize table-striped"
                                            style="width:100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->salesOrderDetailsBy as $detail)
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $detail->qty }}
                                                        </td>
                                                        @php
                                                            $disc = floatval($detail->discount) / 100;
                                                            $hargaDiskon = (float) $detail->price * $disc;
                                                            $hargaAfterDiskon = (float) ($detail->price - $hargaDiskon) - $detail->discount_rp;
                                                            $total = $hargaAfterDiskon * $detail->qty;
                                                        @endphp
                                                        <td class="text-center">
                                                            {{ number_format($total) }}
                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        <form action="{{ url('invoice/' . $item->id . '/update_payment') }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-group row bg-primary payParent">
                                                <div class="form-group justify-content-center row pt-2">
                                                    <div class="col-lg-5 form-group">
                                                        <label>Cash & Bank</label>
                                                        <select name="acc_coa" id=""
                                                            class="form-control cash-bank" required>
                                                        </select>
                                                    </div>
                                                    {{-- <div class="col-lg-5 form-group">
                                                    <label>Warehouse</label>
                                                    <select name="warehouse_id" id=""
                                                        class="form-control warehouse-id" required>
                                                    </select>
                                                </div> --}}
                                                </div>
                                                <div class="form-group row pt-2 pay">
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
                                                            <option value="Voucher">Voucher</option>
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
                                        <form action="{{ url('invoice/' . $item->id . '/cancel_payment') }}"
                                            method="POST">
                                            @csrf
                                            <div class="table-responsive">
                                                <table class="table table-sm table-light display expandable-table table-striped" style="width:100%">
                                                    <caption class="text-info">*Settlement History</caption>
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">Payment Date</th>
                                                            <th class="text-center">Payment Method</th>
                                                            <th class="text-center">Amount</th>
                                                            <th class="text-center">Settlement By</th>
                                                            {{-- <th class="text-center">Cancel</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item->salesOrderCreditsBy as $detail)
                                                            <tr>
                                                                <td class="text-center"> {{ $loop->iteration }}</td>
                                                                <td class="text-center">
                                                                    {{ date('d F Y', strtotime($detail->payment_date)) }}
                                                                </td>
                                                                <td class="text-center">{{ $detail->payment_method }}
                                                                </td>
                                                                <td class="text-end credit-amount">
                                                                    {{ number_format($detail->amount, 0, '.', ',') }}
                                                                </td>
                                                                <td class="text-center">
                                                                        {{ $detail->createdBy->name }}</td>
                                                                {{-- <td
                                                                    class="d-flex justify-content-evenly align-items-center"> --}}
                                                                {{-- <div>
                                                                        <input type="text"
                                                                            class="form-control total cancel-amount"
                                                                            placeholder="Enter amount..."> --}}
                                                                <input type="hidden"
                                                                    name="cancel[{{ $loop->index }}][amount]"
                                                                    class="form-control"
                                                                    value="{{ $detail->amount }}">
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
                                                        @if ($item->salesOrderCreditsBy->count() > 0)
                                                            <tr style="border: 0 !important">
                                                                <td colspan="3" style="border: 0 !important"></td>
                                                                <td class="d-flex justify-content-end"
                                                                    style="border: 0 !important">
                                                                    <div><button type="submit"
                                                                            class="btn btn-danger btn-sm text-white">Cancel
                                                                            Payment</button>
                                                                    </div>
                                                                </td>
                                                                <td style="border: 0 !important">
                                                                    </td>
                                                            </tr>
                                                        @else
                                                            <tr style="border: 0 !important">
                                                                <td colspan="5" class="text-center"
                                                                    style="border: 0 !important">There
                                                                    is no payment</td>

                                                            </tr>
                                                        @endif

                                                        <tr class="fw-bold">
                                                            <td class="text-end" colspan="3">Total Instalment</td>
                                                            <input type="hidden"
                                                                value="{{ $item->salesOrderCreditsBy->sum('amount') }}"
                                                                class="total-instalment">
                                                            <td class="text-end total-instalment-text">
                                                                {{ number_format($item->salesOrderCreditsBy->sum('amount')) }}
                                                            </td>
                                                            <td style="border: 0 !important">
                                                                    </td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td class="text-end" colspan="3">Total Invoice</td>
                                                            <td class="text-end">
                                                                {{ number_format($item->total_after_ppn) }}
                                                            </td>
                                                            <td style="border: 0 !important">
                                                                    </td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td colspan="3" class="text-danger text-end">Total
                                                                Return</td>
                                                            <td class="text-end text-danger">
                                                                {{ number_format($item->returnBy->sum('total')) }}
                                                            </td>
                                                            <td style="border: 0 !important">
                                                                    </td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td class="text-end" colspan="3">Remaining Instalment
                                                            </td>
                                                            <td class="text-end text-danger">
                                                                {{ number_format($item->total_after_ppn - $item->returnBy->sum('total') - $item->salesOrderCreditsBy->sum('amount')) }}
                                                            </td>
                                                            <td style="border: 0 !important">
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

                    {{-- <input type="hidden" class="id" value="{{ $invoice->id }}">
                    <input type="hidden" class="totalraw" value="{{ $invoice->total_after_ppn - $total_return }}">
                    <form action="{{ url('invoice/' . $invoice->id . '/update_payment') }}" method="POST">
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
                    <div class="form-group row">
                        <div class="col-lg-4 form-group">
                            <label>Total (Include PPN)</label>
                            <input class="form-control total-after-ppn"
                                value="{{ 'Rp. ' . number_format($invoice->total_after_ppn - $total_return, 0, ',', '.') }}"
                                readonly>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label>Total Instalment</label>
                            <input class="form-control total-instalment" readonly>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label>Remaining Instalment</label>
                            <input class="form-control remaining-instalment"
                                value="{{ 'Rp. ' . number_format($invoice->total, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>
                                Customers</label>
                            <input type="text" class="form-control"
                                value="{{ $invoice->customerBy->code_cust . ' - ' . $invoice->customerBy->name_cust }}"
                                readonly>
                        </div>
                        <div class="col-md-6 form-group mr-5">
                            <label>Payment Method</label>
                            <input type="text" class="form-control"
                                @if ($invoice->payment_method == 1) value="Cash On Delivery" @elseif($invoice->payment_method == 2) value="Cash Before Delivery" @else value="Credit" @endif
                                readonly>
                        </div>

                        <div class="col-12 form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remark" id="" cols="30" rows="1" readonly>{{ $invoice->remark }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group formSo-edit">
                        @foreach ($invoice->salesOrderDetailsBy as $detail)
                            <div class="form-group row rounded bg-primary pt-2 mx-auto mb-3">
                                <div class="form-group col-12 col-lg-6">
                                    <label>Product</label>
                                    <input type="text" class="form-control"
                                        value="{{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}"
                                        readonly>
                                </div>

                                <div class="col-6 col-lg-3 form-group">
                                    <label>Qty</label>
                                    <input type="text" class="form-control" value="{{ $detail->qty }}" readonly />
                                </div>

                                <div class="col-6 col-lg-3 form-group">
                                    <label>Disc(%)</label>
                                    <input type="text" class="form-control" value="{{ $detail->discount }}"
                                        readonly />
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
{{-- <div class="modal" id="historyData{{ $invoice->id }}" tabindex="-1" data-bs-backdrop="static"
    data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    History Payment :
                    {{ $invoice->order_number }}</h6>
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
                                    @foreach ($invoice->salesOrderCreditsBy as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d F Y', strtotime($detail->payment_date)) }}</td>
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
                {{-- <a class="btn btn-primary" type="button"
                    href="{{ url('print_history_payment/' . $invoice->id) }}">Print
                    History
                    Payment</a> --}}
            </div>
<!-- End History Payment -->

</div>
