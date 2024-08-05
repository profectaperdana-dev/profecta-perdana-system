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
            </div>
            <div class="modal-body">
                Are you sure you want to delete this invoice ?
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal" data-original-title="test"
                    data-bs-target="#detailData{{ $invoice->id }}" data-bs-dismiss="modal">Back
                </a>
                <a type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</a>
                <a type="button" href="{{ url('material-promotion/transaction/' . $invoice->id . '/delete') }}"
                    class="btn btn-delete btn-primary">Yes, delete</a>
            </div>
        </div>
    </div>
</div>

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
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Print
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" target="popup"
                                    onclick="window.open('{{ url('material-promotion/purchase/print/' . $invoice->id) }}','name','width=600,height=400')
                                ">Print
                                    Struk</a>
                            </li>
                            {{-- <li><a class="dropdown-item" target="popup"
                                    onclick="window.open('{{ url('retail/print_invoice/' . $direct->id) }}','name','width=600,height=400')">Invoice</a>
                            </li>
                            <li><a class="dropdown-item" target="popup"
                                    onclick="window.open('{{ url('retail/print_do/' . $direct->id) }}','name','width=600,height=400')">Delivery
                                    Order</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li> --}}
                        </ul>
                    </div>
                    {{-- <div class="btn-group">
                        <button type="button" class="btn btn-info" target="popup"
                            onclick="window.open('{{ url('material-promotion/purchase/' . $invoice->id . '/print') }}','name','width=600,height=400')
                            ">Print
                            Purchase
                            Order
                        </button>

                    </div> --}}
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
                                        Purchase
                                    </a>
                                </li>
                            @endcanany
                            @canany(['level1'])
                                <li><a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#delete{{ $invoice->id }}"
                                        data-bs-dismiss="modal">Delete Purchase</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endcanany

                            <li><a class="dropdown-item"
                                    href="{{ url('material-promotion/purchase/return/' . $invoice->id . '/create') }}">Return
                                    Purchase</a></li>
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
{{-- <div class="modal" id="manageData{{ $invoice->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Edit
                    {{ $invoice->order_number }}</h6>
            </div>
            <div class="modal-body">
                <form action="{{ url('material-promotion/transaction/' . $invoice->id . '/update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <input type="hidden" class="warehouse" value="{{ $invoice->id_warehouse }}">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>
                                    Customer</label>
                                <select name="customer_id" id="" required
                                    class="form-control customer-select customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}"
                                    multiple>
                                    @if (is_numeric($invoice->id_customer))
                                        <option value="{{ $invoice->customerBy->id }}" selected>
                                            {{ $invoice->customerBy->name_cust }}</option>
                                    @else
                                        <option value="-1" selected>
                                            {{ $invoice->id_customer }}</option>
                                    @endif

                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 form-group">
                                <label>
                                    Warehouse</label>
                                <select name="warehouse_id" id="" required
                                    class="form-control warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}"
                                    multiple>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            @if ($warehouse->id == $invoice->id_warehouse) selected @endif>
                                            {{ $warehouse->warehouses }}</option>
                                    @endforeach

                                </select>
                                @error('warehouse_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 form-group cust-name" hidden>
                                <label>
                                    Customer Name</label>
                                <input type="text" name="customer_name" class="form-control"
                                    placeholder="Enter customer name">
                                @error('customer_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 form-group">
                                <label>
                                    Address</label>
                                <input type="text" name="address" value="{{ $invoice->address }}"
                                    class="form-control">
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="col-6 form-group">
                                <label>Remark</label>
                                <textarea class="form-control" name="remark" id="" cols="30" rows="1">{{ $invoice->remark }}</textarea>
                            </div>
                        </div>
                        <div class="form-group formSo-edit">
                            @foreach ($invoice->transactionDetailBy as $detail)
                                <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                                    <input type="hidden" class="loop" value="{{ $loop->index }}">
                                    <div class="form-group col-12 col-lg-6">
                                        <label>Product</label>
                                        <select name="editProduct[{{ $loop->index }}][products_id]" required
                                            class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}"
                                            multiple>
                                            @if ($detail->id_item != null)
                                                <option value="{{ $detail->id_item }}" selected>
                                                    {{ $detail->itemBy->name }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('editProduct[' . $loop->index . '][products_id]')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4 col-lg-4 form-group">
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
</div> --}}
</div>
{{-- !end modal edit invoice --}}
