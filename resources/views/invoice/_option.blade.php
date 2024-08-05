{{-- ! button action --}}
<a class="text-success fw-bold text-nowrap" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailData{{ $invoice->id }}">
    {{ $invoice->order_number }}</a>

<!-- Button trigger modal -->
<div class="currentModal">
    <!-- Modal -->
<div class="modal" id="delete{{ $invoice->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Delete {{ $invoice->order_number }}</h6>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                Are you sure you want to delete this invoice ?
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal" data-original-title="test"
                    data-bs-target="#detailData{{ $invoice->id }}" data-bs-dismiss="modal">Back
                </a>
                <a type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</a>
                <a type="button" href="{{ url('delete/indirect_invoice/' . $invoice->id) }}"
                    class="btn btn-delete btn-primary">Save changes</a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="receipt{{ $invoice->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Cash Receipt {{ $invoice->order_number }}</h6>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <table class="table table-light table-sm table-striped" style="width:100%">
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
                                Customer</label>
                            <input type="text" readonly value="{{ $invoice->customerBy->name_cust }}"
                                class="form-control">
                        </div>
                        <div class="col-lg-6 form-group mr-5">
                            <label>Payment Method</label>
                            <select disabled class="form-control">
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
                        <div class="col-12 form-group">
                            <label>Remark</label>
                            <textarea class="form-control" name="remark" id="" cols="30" rows="1" readonly>{{ $invoice->remark }}</textarea>
                        </div>
                    </div>
                    <div class="form-group formSo-edit">
                        @foreach ($invoice->salesOrderDetailsBy as $detail)
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
                                    $price = $detail->price;
                                    if ($detail->price == null || $detail->price == 0) {
                                        $price = str_replace(',', '.', $detail->productSales->harga_jual_nonretail);
                                        $sub_total = (float) $price * (float) $ppn;
                                        (float) ($price = (float) $price + (float) $sub_total);
                                    }
                                @endphp
                                <div class="col-4 col-lg-2 form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ number_format(round($price), 0, '.', ',') }}" />
                                </div>
                                <div class="col-4 col-lg-1 form-group">
                                    <label>Disc (%)</label>
                                    <input type="text" readonly min="0"
                                        class="form-control discount-append-edit" placeholder="Disc"
                                        value="{{ $detail->discount }}" />

                                </div>

                                <div class="col-6 col-lg-2 form-group">
                                    <label>Disc (Rp)</label>
                                    <input type="text" readonly class="form-control discount_rp"
                                        placeholder="Disc" value="{{ $detail->discount_rp }}" />
                                </div>
                                @php
                                    $disc = (float) $detail->discount / 100.0;
                                    $disc_cost = (float) $price * $disc;
                                    $price_disc = (float) ($price - $disc_cost - $detail->discount_rp);
                                @endphp
                                <div class="col-6 col-lg-2 form-group">
                                    <label>Disc Price</label>
                                    <input type="text" class="form-control price" readonly
                                        value="{{ number_format(round($price_disc), 0, '.', ',') }}" />
                                </div>
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
                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Print
                        </button>
                        <ul class="dropdown-menu">

                            <li><a class="dropdown-item" target="popup"
                                    onclick="window.open('{{ url('invoice/' . $invoice->id . '/invoice_with_ppn') }}','name','width=600,height=400')">Print
                                    Invoice</a></li>
                            <li><a class="dropdown-item" target="popup"
                                    onclick="window.open('{{ url('invoice/' . $invoice->id . '/delivery_order') }}','name','width=600,height=400')
                                    ">Delivery
                                    Order</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        </ul>
                    </div>
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            @canany(['level1', 'level2'])
                                <li>
                                    <a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#manageData{{ $invoice->id }}"
                                        data-bs-dismiss="modal">Edit
                                        Invoice
                                    </a>
                                </li>
                            @endcanany
                            @canany(['level1'])
                                <li><a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#delete{{ $invoice->id }}"
                                        data-bs-dismiss="modal">Delete Invoice</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endcanany


                            <li><a class="dropdown-item" href="{{ url('send_email/' . $invoice->id) }}">Send Invoice
                                    by Email</a></li>
                            <li><a class="dropdown-item" href="{{ url('return/' . $invoice->id) }}">Return
                                    Invoice</a></li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-original-title="test"
                                    data-bs-target="#traceData{{ $invoice->id }}" href="#"
                                    data-bs-dismiss="modal">SO Process
                                </a></li>
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-original-title="test"
                                    data-bs-target="#receipt{{ $invoice->id }}" href="#"
                                    data-bs-dismiss="modal">Cash Receipt
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button class="btn  btn-danger " type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ! modal edit invoice --}}
<div class="modal" id="manageData{{ $invoice->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Edit
                    {{ $invoice->order_number }}</h6>
                {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <form action="{{ url('invoice/' . $invoice->id . '/edit_superadmin') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <input type="hidden" name="warehouse_id" class="warehouse"
                            value="{{ $invoice->warehouse_id }}">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>
                                    Customer</label>
                                <select name="customer_id" id="" required
                                    class="form-control customer-select customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}"
                                    multiple>
                                    <option value="{{ $invoice->customerBy->id }}" selected>
                                        {{ $invoice->customerBy->name_cust }}</option>
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6 form-group mr-5">
                                <label>Payment Method</label>
                                <select name="payment_method" required
                                    class="form-control sub_type warehouse-select {{ $errors->first('payment_method') ? ' is-invalid' : '' }}"
                                    multiple>
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
                                @error('payment_method')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label>Remark</label>
                                <textarea class="form-control" name="remark" id="" cols="30" rows="1">{{ $invoice->remark }}</textarea>
                            </div>
                        </div>
                        <div class="form-group formSo-edit">
                            @foreach ($invoice->salesOrderDetailsBy as $detail)
                                <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                    <input type="hidden" class="loop" value="{{ $loop->index }}">
                                    <div class="form-group col-12 col-lg-4">
                                        <label>Product</label>
                                        <select name="editProduct[{{ $loop->index }}][products_id]" required
                                            class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}"
                                            multiple>
                                            @if ($detail->products_id != null)
                                                <option value="{{ $detail->products_id }}" selected>
                                                    {{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('editProduct[' . $loop->index . '][products_id]')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4 col-lg-2 form-group">
                                        <label>Qty</label>
                                        <input type="number" class="form-control cekQty-edit"
                                            name="editProduct[{{ $loop->index }}][qty]"
                                            value="{{ $detail->qty }}" />
                                        <small class="text-danger qty-warning" hidden>The number of items exceeds
                                            the
                                            stock</small>
                                        @error('top')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4 col-lg-2 form-group">
                                        <label>Disc (%)</label>
                                        <input type="text" min="0"
                                            class="form-control discount-append-edit" placeholder="Disc"
                                            name="editProduct[{{ $loop->index }}][discount]"
                                            value="{{ $detail->discount }}" />
                                        @error('editProduct[{{ $loop->index }}][discount]')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4 col-lg-2 form-group">
                                        <label>Disc (Rp)</label>
                                        <input type="number" min="0" class="form-control discount_rp"
                                            placeholder="Disc" name="editProduct[{{ $loop->index }}][discount_rp]"
                                            value="{{ old(0, $detail->discount_rp) }}" />
                                        @error('editProduct[{{ $loop->index }}][discount_rp]')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    @if ($loop->index == 0)
                                        <div class="col-12 col-lg-2 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="btn btn-primary form-control text-white addSo-edit">+</a>
                                        </div>
                                    @else
                                        <div class="col-6 col-lg-1 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="btn form-control text-white addSo-edit"
                                                style="border:none; background-color:#276e61">+</a>
                                        </div>
                                        <div class="col-6 col-lg-1 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="btn form-control text-white remSo-edit"
                                                style="border:none; background-color:#d94f5c">-</a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="row form-group">
                            <div class="form-group col-12">
                                <button type="button" class="col-12 btn btn-outline-warning btn-reload">- Click
                                    this to
                                    view total -</button>
                            </div>
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
                                    value="{{ 'Rp. ' . number_format($invoice->total_after_ppn, 0, '.', ',') }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#detailData{{ $invoice->id }}"
                            data-bs-dismiss="modal">Back
                        </button>

                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                            Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- !end modal edit invoice --}}

{{-- ! end button action --}}
<div class="modal" id="traceData{{ $invoice->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Trace Delivery History
                    {{ $invoice->order_number }}</h6>
            </div>
            <div class="modal-body">

                <div class="container-fluid">

                    @if (empty($invoice->deliveryHistoriesBy))
                        <h4>No History</h4>
                    @else
                        <section class="cd-container" id="cd-timeline">
                            @foreach ($invoice->deliveryHistoriesBy as $history)
                                <div class="cd-timeline-block">
                                    <div class="cd-timeline-img cd-movie bg-primary">
                                        @if ($history->status == 'Packing')
                                            <i class="icon-dropbox-alt"></i>
                                        @elseif ($history->status == 'Delivering')
                                            <i class="fa fa-car"></i>
                                        @else
                                            <i class="fa fa-check"></i>
                                        @endif
                                    </div>
                                    <div class="cd-timeline-content">
                                        <h4>{{ $history->status }}</h4>
                                        <p class="m-0">{{ $history->remark }}</p>
                                        <br>
                                        <small
                                            class="fw-light mt-4">{{ date('d-M-Y H:i:s', strtotime($history->history_date)) }}</small>
                                        <small>Updated by {{ $history->createdBy->name }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </section>

                    @endif
                    <!-- cd-timeline Ends-->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                        data-original-title="test" data-bs-target="#detailData{{ $invoice->id }}"
                        data-bs-dismiss="modal">Back
                    </button>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

