<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    ACTION</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    <a class="dropdown-item" href="{{ url('send_email_po/' . $purchase->id) }}">Send
        Purchase Order by Email</a>
    <a class="dropdown-item" href="{{ url('po/' . $purchase->id . '/print') }}">Print
        Purchase Order</a>

    @can('isSuperAdmin')
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#manageData{{ $purchase->id }}">Edit</a>
        <a class="dropdown-item" href="{{ url('return_purchase/' . $purchase->id) }}">Return</a>
    @endcan
</div>

<div class="modal fade" id="manageData{{ $purchase->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Purchase Order:
                    {{ $purchase->order_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('purchase_orders/' . $purchase->id . '/update_po') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="row font-weight-bold">
                                <div class="form-group row">
                                    <div class="col-md-6 form-group">
                                        <label>
                                            Supplier</label>
                                        <select name="supplier_id" id="" required
                                            class="form-control supplier-select {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}">
                                            <option value="" selected>-Choose Supplier-</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    @if ($purchase->supplier_id == $supplier->id) selected @endif>
                                                    {{ $supplier->nama_supplier }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group mr-5">
                                        <label>Warehouse</label>
                                        <select name="warehouse_id" required
                                            class="form-control warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}">
                                            <option value="" selected>-Choose Payment-</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    @if ($purchase->warehouse_id == $warehouse->id) selected @endif>
                                                    {{ $warehouse->warehouses }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('warehouse_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 form-group mr-5">
                                        <label>Order Date <strong>(mm/dd/yyyy)</strong></label>
                                        <input class="form-control" type="date" name="order_date"
                                            value="{{ $purchase->order_date }}" required>
                                        @error('due_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>TOP</label>
                                        <input type="number" class="form-control" required name="top"
                                            id="" value="{{ $purchase->top }}">
                                        @error('top')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 form-group mr-5">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remark" id="" cols="30" rows="5" required>{{ $purchase->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row formPo">
                                    @foreach ($purchase->purchaseOrderDetailsBy as $detail)
                                        <div class="form-group row">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-7">
                                                <label>Product</label>
                                                <select name="poFields[{{ $loop->index }}][product_id]"
                                                    class="form-control productPo" required>
                                                    <option value="">Choose Product</option>
                                                    @if ($detail->product_id != null)
                                                        <option value="{{ $detail->product_id }}" selected>
                                                            {{ $detail->productBy->nama_barang .
                                                                ' (' .
                                                                $detail->productBy->sub_types->type_name .
                                                                ', ' .
                                                                $detail->productBy->sub_materials->nama_sub_material .
                                                                ')' }}
                                                        </option>
                                                    @endif
                                                </select>
                                                @error('poFields[{{ $loop->index }}][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-3 col-md-3 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control qtyPo" required
                                                    name="poFields[{{ $loop->index }}][qty]" id=""
                                                    value="{{ $detail->qty }}">
                                                @error('poFields[{{ $loop->index }}][qty]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            @if ($loop->index == 0)
                                                <div class="col-2 col-md-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="form-control text-white text-center addPo"
                                                        style="border:none; background-color:green">+</a>
                                                </div>
                                            @else
                                                <div class="col-2 col-md-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="form-control text-white text-center remPo"
                                                        style="border:none; background-color:red">-</a>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
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
                                        <label>Total</label>
                                        <input class="form-control total"
                                            value="{{ 'Rp. ' . number_format($purchase->total, 0, ',', '.') }}"
                                            id="" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
