<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    INV</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    @can('isSuperAdmin')
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#manageData{{ $invoice->id }}">Edit Invoice</a>
    @endcan
    <a class="dropdown-item" href="{{ url('send_email/' . $invoice->id) }}">Send Invoice by Email</a>
    <h5 class="dropdown-header">Prints</h5>
    <a class="dropdown-item" href="{{ url('invoice/' . $invoice->id . '/invoice_with_ppn') }}">Print Invoice</a>
    <a class="dropdown-item" href="{{ url('invoice/' . $invoice->id . '/delivery_order') }}">Print Delivary Order</a>
</div>
<div class="modal fade" id="manageData{{ $invoice->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sales
                    Order
                    :
                    {{ $invoice->order_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('invoice/' . $invoice->id . '/edit_superadmin') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="form-group row">
                            <div class="col-md-6 form-group">
                                <label>
                                    Customers</label>
                                <select name="customer_id" id="" required
                                    class="form-control customer-select customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                                    <option value="" selected>-Choose Customers-</option>
                                    @foreach ($customer as $cust)
                                        <option value="{{ $cust->id }}"
                                            @if ($cust->id == $invoice->customers_id) selected @endif>
                                            {{ $cust->code_cust }} |
                                            {{ $cust->name_cust }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mr-5">
                                <label>Payment Method</label>
                                <select name="payment_method" required
                                    class="form-control sub_type warehouse-select {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
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
                                @error('payment_method')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row formSo-edit">
                            @foreach ($invoice->salesOrderDetailsBy as $detail)
                                <div class="mx-auto py-2 form-group row bg-primary">
                                    <input type="hidden" class="loop" value="{{ $loop->index }}">
                                    <div class="form-group col-12 col-lg-6">
                                        <label>Product</label>
                                        <select name="editProduct[{{ $loop->index }}][products_id]" required
                                            class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}">
                                            @if ($detail->products_id != null)
                                                <option value="{{ $detail->products_id }}" selected>
                                                    {{ $detail->productSales->nama_barang .
                                                        ' (' .
                                                        $detail->productSales->sub_types->type_name .
                                                        ', ' .
                                                        $detail->productSales->sub_materials->nama_sub_material .
                                                        ')' }}
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
                                        <input type="number" class="form-control discount-append-edit"
                                            placeholder="Disc" name="editProduct[{{ $loop->index }}][discount]"
                                            value="{{ $detail->discount }}" />
                                        @error('editProduct[{{ $loop->index }}][discount]')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    @if ($loop->index == 0)
                                        <div class="col-1 col-md-2 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="btn btn-success form-control text-white addSo-edit">+</a>
                                        </div>
                                    @else
                                        <div class="col-1 col-md-2 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="btn btn-danger form-control text-white remSo-edit">-</a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-12 form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remark" id="" cols="30" rows="5">{{ $invoice->remark }}</textarea>
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
                        <div class="form-group row">
                            <div class="form-group col-lg-4">
                                <label>PPN</label>
                                <input class="form-control ppn"
                                    value="{{ 'Rp. ' . number_format($invoice->ppn, 0, ',', '.') }}" id=""
                                    readonly>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label>Total (Before PPN)</label>
                                <input class="form-control total"
                                    value="{{ 'Rp. ' . number_format($invoice->total, 0, ',', '.') }}" readonly>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label>Total (After PPN)</label>
                                <input class="form-control total-after-ppn"
                                    value="{{ 'Rp. ' . number_format($invoice->total_after_ppn, 0, ',', '.') }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save

                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
