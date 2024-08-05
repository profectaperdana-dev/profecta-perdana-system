<a href="#" class="fw-bold tex-nowrap text-success " data-bs-toggle="modal" data-bs-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false" data-bs-target="#detail{{ $invoice->id }}">
    {{ $invoice->second_sale_number }} </a>

<div class="currentModal">
    <div class="modal" id="delete{{ $invoice->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Delete {{ $invoice->second_sale_number }}</h6>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                Are you sure you want to delete this invoice ?
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal" data-original-title="test"
                    data-bs-target="#detail{{ $invoice->id }}" data-bs-dismiss="modal">Back
                </a>
                <a type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</a>
                <a type="button" href="{{ url('delete/trade_sale/' . $invoice->id) }}"
                    class="btn btn-delete btn-primary">Save changes</a>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="detail{{ $invoice->id }}" aria-hidden="true" data-bs-backdrop="static"
    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalToggleLabel">Detail
                    {{ $invoice->second_sale_number }}
                </h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <div class="col-12 col-lg-4 form-group">
                                <label>Warehouse</label>
                                <input type="text" class="form-control" value="{{ $invoice->warehouse->warehouses }}"
                                    readonly>
                            </div>
                            <div class="col-12 col-lg-4 form-group">
                                <label>
                                    Customers Name</label>
                                <input readonly type="text" name="customer_name" required class="form-control"
                                    value="{{ $invoice->customer_name }}">
                            </div>
                            {{-- <div class="col-12 col-lg-4 form-group">
                                <label>
                                    Customers NIK</label>
                                <input readonly type="text" name="customer_nik" class="form-control"
                                    value="{{ $invoice->customer_nik }}">
                            </div> --}}
                            <div class="col-12 col-lg-4 form-group">
                                <label>
                                    Customers Phone</label>
                                <input readonly type="text" data-v-min-length="9" data-v-max-length="13" number
                                    required name="customer_phone" class="form-control"
                                    value="{{ $invoice->customer_phone }}">
                            </div>
                            {{-- <div class="col-12 col-lg-4 form-group">
                                <label>
                                    Customers Email</label>
                                <input readonly type="text" name="customer_email" class="form-control"
                                    value="{{ $invoice->customer_email }}">
                            </div> --}}
                        </div>
                        @foreach ($invoice->second_sale_details as $detail)
                            <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                <div class="col-8 col-lg-4">
                                    <label>Baterry</label><br>
                                    <input type="text" class="form-control"
                                        value="{{ $detail->secondProduct->name_product_trade_in }}" readonly>

                                </div>
                                <div class="col-4 col-lg-3 mb-2">
                                    <label>Qty</label>
                                    <input class="form-control" required readonly value="{{ $detail->qty }}">
                                </div>
                                <div class="col-4 col-lg-2 mb-2">
                                    <label>Disc (%)</label>
                                    <input type="" class="form-control " required readonly
                                        value="{{ $detail->discount }}">
                                </div>
                                <div class="col-8 col-lg-3 mb-2">
                                    <label>Disc (Rp)</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ number_format($detail->discount_rp, 0, '.', ',') }}">

                                </div>
                            </div>
                        @endforeach
                        <div class="form-group row">
                            <div class="col-12 col-lg-12 form-group">
                                <label>Total</label>
                                <input type="text" class="form-control"
                                    value="Rp {{ number_format($invoice->total, 0, '.', ',') }}" readonly>
                            </div>

                        </div>
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
                                onclick="window.open('{{ url('second_sale/print_struk/' . $invoice->id . '/print') }}','name','width=600,height=400')">Print
                                Struk</a></li>

                    </ul>
                </div>
                <div class="btn-group dropup">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        @canany(['level1', 'level2'])
                            <li>
                                <a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                    data-original-title="test" data-bs-target="#edittradein{{ $invoice->id }}"
                                    data-bs-dismiss="modal">Edit
                                    Invoice
                                </a>
                            </li>
                        @endcanany
                        @canany(['level1'])
                            <li><a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                    data-original-title="test" data-bs-target="#delete{{ $invoice->id }}"
                                    data-bs-dismiss="modal">Delete Invoice</a></li>
                        @endcanany
                        {{-- <li><a class="dropdown-item" href="{{ url('send_email_trade_invoice/' . $invoice->id) }}">Send
                                Invoice
                                by Email</a></li> --}}
                        <li><a class="dropdown-item" href="{{ url('return_trade_in_sale/' . $invoice->id) }}">
                                Return Invoice</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    </ul>
                </div>

                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<div class="modal" id="edittradein{{ $invoice->id }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="needs-validation" novalidate method="post"
            action="{{ url('second_sale/' . $invoice->id . '/edit_superadmin') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalToggleLabel2">Edit
                        {{ $invoice->second_sale_number }}</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            @if ($user_warehouse->count() == 1)
                                @foreach ($user_warehouse as $item)
                                    <input type="hidden" name="warehouse_id" id="warehouse" class="form-control"
                                        value="{{ $item->id }}">
                                @endforeach
                            @else
                                <div class="form-group row">
                                    <div class="col-12 col-lg-5 form-group">
                                        <label>Warehouse</label>
                                        <select multiple name="warehouse_id" class="form-control multiSelect"
                                            id="warehouse" required>
                                            @foreach ($user_warehouse as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($item->id == $invoice->warehouse_id) selected @endif>
                                                    {{ $item->warehouses }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                            @endif
                            <div class="col-12 col-lg-4 form-group">
                                <label>
                                    Customers Name</label>
                                <input type="text" name="customer_name" required class="form-control"
                                    value="{{ $invoice->customer_name }}">
                            </div>
                            {{-- <div class="col-12 col-lg-4 form-group">
                                <label>
                                    Customers NIK</label>
                                <input type="text" name="customer_nik" class="form-control"
                                    value="{{ $invoice->customer_nik }}">
                            </div> --}}
                            <div class="col-12 col-lg-3 form-group">
                                <label>
                                    Customers Phone</label>
                                <input type="text" data-v-min-length="9" data-v-max-length="13" number required
                                    name="customer_phone" class="form-control"
                                    value="{{ $invoice->customer_phone }}">
                            </div>
                            {{-- <div class="col-12 col-lg-4 form-group">
                                <label>
                                    Customers Email</label>
                                <input type="text" name="customer_email" class="form-control"
                                    value="{{ $invoice->customer_email }}">
                            </div> --}}

                        </div>
                        <div class="form-group formSo-edit">
                            @foreach ($invoice->second_sale_details as $detail)
                                <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                    <div class="mb-2 col-8 col-lg-4">
                                        <label>Baterry</label>
                                        <select multiple name="tradeFields[{{ $loop->index }}][product_trade_in]"
                                            class="form-control productSo-edit id_product" required>
                                            @if ($detail->secondProduct != null)
                                                <option value="{{ $detail->product_second_id }}" selected>
                                                    {{ $detail->secondProduct->name_product_trade_in }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-4 col-lg-2 mb-2">
                                        <label>Qty</label>
                                        <small class="text-danger qty-warning" hidden>Out of Stock</small>
                                        <input type="number" class="form-control cek_stock cekQty-edit" required
                                            name="tradeFields[{{ $loop->index }}][qty]" id=""
                                            value="{{ $detail->qty }}">
                                    </div>
                                    <div class="col-4 col-lg-2 mb-2">
                                        <label>Disc (%)</label>
                                        <input type="number" class="form-control disc_persen" required
                                            name="tradeFields[{{ $loop->index }}][disc_percent]" id=""
                                            value="{{ $detail->discount }}">
                                    </div>
                                    <div class="col-8 col-lg-2 mb-2">
                                        <label>Disc (Rp)</label>
                                        <input type="text" class="form-control disc_rp split_rp" id=""
                                            placeholder="0"
                                            value="{{ number_format($detail->discount_rp, 0, '.', ',') }}">
                                        <input type="hidden" class="discountRp" value="{{ $detail->discount_rp }}"
                                            name="tradeFields[{{ $loop->index }}][disc_rp]">
                                    </div>
                                    @if ($loop->index == 0)
                                        <div class="col-12 col-lg-2 mb-2">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="form-control addSo-edit text-white  text-center"
                                                style="border:none; background-color:#276e61"> + </a>
                                        </div>
                                    @else
                                        <div class="col-6 col-lg-2 mb-2">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="form-control addSo-edit text-white  text-center"
                                                style="border:none; background-color:#276e61"> + </a>
                                        </div>
                                        <div class="col-6 col-lg-2 mb-2">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="btn form-control text-white remSo-edit"
                                                style="border:none; background-color:#d94f5c">-</a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-lg-12">
                                <label>TOTAL</label>
                                <input class="form-control total"
                                    value="{{ 'Rp. ' . number_format($invoice->total, 0, '.', ',') }}" id=""
                                    readonly>
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

                    </div>
                </div>
            </div>
            <div class="modal-footer">

                    <button class="btn btn-secondary" data-bs-target="#detail{{ $invoice->id }}"
                        data-bs-toggle="modal" data-bs-dismiss="modal">Back</button>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button> <button
                        class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

</div>
