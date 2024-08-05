<a href="#" class="fw-bold text-success text-center text-nowrap" href="#" data-bs-toggle="modal"
    data-original-title="test" data-bs-target="#detailData{{ $purchase->id }}">
    {{ $purchase->order_number }}</a>

<div class="currentModal">
    {{-- <a class="dropdown-item" href="{{ url('send_email_po/' . $purchase->id) }}">Send
Purchase Order by Email</a> --}}
<div class="modal" id="delete{{ $purchase->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Delete {{ $purchase->order_number }}</h6>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                Are you sure you want to delete this invoice ?
            </div>
            <div class="modal-footer">

                <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal" data-original-title="test"
                    data-bs-target="#detailData{{ $purchase->id }}" data-bs-dismiss="modal">Back
                </a>
                <a type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                <a type="button" href="{{ url('delete/purchase/' . $purchase->id) }}"
                    class="btn btn-primary btn-delete">Yes, delete</a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="detailData{{ $purchase->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"> Purchase Order
                    {{ $purchase->order_number }}</h6>
                {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="col-md-12">
                        <div class="font-weight-bold">
                            <div class="form-group row">
                                <div class="col-md-4 form-group">
                                    <label>
                                        Supplier</label>
                                    <input type="text" class="form-control" readonly
                                        value=" {{ $purchase->supplierBy->nama_supplier }}">

                                </div>
                                <div class="col-md-4 form-group mr-5">
                                    <label>Warehouse</label>
                                    <input type="text" class="form-control" readonly
                                        value=" {{ $purchase->warehouseBy->warehouses }}">
                                </div>
                                <div class="col-md-4 form-group mr-5">
                                    <label>Payment Method</label>
                                    <input type="text" class="form-control" readonly
                                        value=" {{ $purchase->payment_method }}">
                                </div>

                                <div class="col-md-6 form-group mr-5">
                                    <label>Order Date</label>
                                    <input class="form-control" type="text" readonly
                                        value="{{ date('d F Y', strtotime($purchase->order_date)) }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>TOP</label>
                                    <input type="number" class="form-control" readonly value="{{ $purchase->top }}">
                                </div>

                                <div class="col-md-12 form-group mr-5">
                                    <label>Remarks</label>
                                    <textarea class="form-control" readonly cols="30" rows="1">{{ $purchase->remark }}</textarea>
                                </div>
                            </div>
                            <div class="form-group  formPo">
                                {{-- @if (!empty($purchase->purchaseOrderDetailsBy)) --}}
                                @if (is_array($purchase->purchaseOrderDetailsBy) || is_object($purchase->purchaseOrderDetailsBy))
                                    @foreach ($purchase->purchaseOrderDetailsBy as $detail)
                                        <div class="form-group rounded row pt-2 mb-3 mx-auto"
                                            style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-12 col-lg-6">
                                                <label>Product</label>

                                                <input type="text" class="form-control" readonly
                                                    value=" {{ $detail->productBy->sub_materials->nama_sub_material . ' ' . $detail->productBy->sub_types->type_name . ' ' . $detail->productBy->nama_barang }}">
                                            </div>
                                            <div class="col-6 col-lg-3 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control qtyPo" readonly
                                                    value="{{ $detail->qty }}">
                                            </div>
                                            <div class="col-6 col-lg-3 form-group">
                                                <label>Disc(%)</label>
                                                <input type="text" class="form-control disc" readonly
                                                    value="{{ $detail->discount }}">
                                            </div>

                                            @if ($detail->productBy->materials->nama_material == 'Tyre')
                                                <div class="mb-3">
                                                    <ul class="list-group">

                                                        <li class="list-group-item fw-bold">
                                                            @foreach ($detail->purchaseOrderCodeBy as $code)
                                                                @if ($loop->iteration == $detail->purchaseOrderCodeBy->count())
                                                                    @if ($loop->iteration == 1)
                                                                        DOT:
                                                                        {{ '[ ' . $code->dot . ' ]' }}
                                                                    @else
                                                                        {{ '[ ' . $code->dot . ' ]' }}
                                                                    @endif
                                                                @else
                                                                    DOT:
                                                                    {{ '[ ' . $code->dot . ' ]' . ', ' }}
                                                                @endif
                                                            @endforeach
                                                        </li>

                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                            <div class="form-group row">
                                <div class="form-group col-lg-12 col-12">
                                    <label>Total</label>
                                    <input class="form-control total"
                                        value="{{ 'Rp. ' . number_format($purchase->total) }}" id="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" target="popup"
                            onclick="window.open('{{ url('po/' . $purchase->id . '/print') }}','name','width=600,height=400')">Print</button>
                    <div class="btn-group">
                        

                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            @canany(['level1', 'level2'])
                                <li>
                                    <a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#manageData{{ $purchase->id }}"
                                        data-bs-dismiss="modal">Edit
                                        Purchase
                                    </a>
                                </li>
                            @endcanany
                            @canany(['level1'])
                                <li><a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#delete{{ $purchase->id }}"
                                        data-bs-dismiss="modal">Delete Purchase</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endcanany



                            <li><a class="dropdown-item" href="{{ url('return_purchase/' . $purchase->id) }}">Return
                                    Purchase</a></li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        </ul>
                        
                    </div>
                    <button href="#" class="btn btn-danger" type="button"
                            data-bs-dismiss="modal">Close</button>


                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal" id="manageData{{ $purchase->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Edit Data Purchase Order
                    {{ $purchase->order_number }}</h6>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="ppn" value="{{ $ppn }}">
                <form action="{{ url('purchase_orders/' . $purchase->id . '/update_po') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="font-weight-bold">
                                <div class="form-group row">
                                    <div class="col-md-4 form-group">
                                        <label>
                                            Supplier</label>
                                        <select name="supplier_id" id="" required multiple
                                            class="form-control supplier-select {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}">
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
                                    <div class="col-md-4 form-group mr-5">
                                        <label>Warehouse</label>
                                        <select name="warehouse_id" required id="warehouse" multiple
                                            class="form-control warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}">
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
                                    <div class="col-md-4 form-group mr-5">
                                        <label>Payment Method</label>
                                        <select name="payment_method" required multiple
                                            class="form-control supplier-select {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                                            <option value="cash" @if ($purchase->payment_method == 'cash') selected @endif>
                                                Cash</option>
                                            <option value="credit" @if ($purchase->payment_method == 'credit') selected @endif>
                                                Credit</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 form-group mr-5">
                                        <label>Order Date <strong>(dd/mm/yyyy)</strong></label>
                                        <input class="datepicker-here form-control digits" data-position="bottom left"
                                            type="text" data-language="en"
                                            data-value="{{ date('d-m-Y', strtotime($purchase->order_date)) }}"
                                            name="order_date" autocomplete="off">
                                        <!--<input class="form-control" type="date" name="order_date"-->
                                        <!--    value="{{ $purchase->order_date }}" required>-->
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

                                    <div class="col-md-12 form-group mr-5">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remark" id="" cols="30" rows="1" required>{{ $purchase->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group formPo">
                                    @foreach ($purchase->purchaseOrderDetailsBy as $detail)
                                        <div class="form-group rounded row pt-2 mb-3 mx-auto"
                                            style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-12 col-lg-4">
                                                <label>Product</label>
                                                <select name="poFields[{{ $loop->index }}][product_id]" multiple
                                                    class="form-control productPo" required>
                                                    @if ($detail->product_id != null)
                                                        <option value="{{ $detail->product_id }}" selected>
                                                            {{ $detail->productBy->sub_materials->nama_sub_material . ' ' . $detail->productBy->sub_types->type_name . ' ' . $detail->productBy->nama_barang }}
                                                        </option>
                                                    @endif
                                                </select>

                                            </div>
                                            <div class="col-2 col-lg-1 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control qtyPo jumlahQty" required
                                                    name="poFields[{{ $loop->index }}][qty]" id=""
                                                    value="{{ $detail->qty }}">
                                            </div>
                                            @php
                                                $price = $detail->price;
                                                if ($price == 0 || $price == null) {
                                                    if(isset($detail->productSales->harga_jual_nonretail)){
                                                      $price = $detail->productSales->harga_jual_nonretail;
                                                    $price = $price + ($price * $ppn) / 100;
                                                    }
                                                  
                                                  
                                                }
                                            @endphp
                                            <div class="col-6 col-lg-2 form-group">
                                                <label>Price</label>
                                                <input type="text" readonly class="form-control" required
                                                    value="{{ number_format(round($price)) }}">
                                                <input type="hidden" readonly class="form-control jumlahPrice"
                                                    required value="{{ $price }}">

                                            </div>
                                            <div class="col-6 col-lg-2 form-group">
                                                <label>Disc(%)</label>
                                                <input type="text" class="form-control disc jumlahDisc" required
                                                    name="poFields[{{ $loop->index }}][discount]" id=""
                                                    value="{{ $detail->discount }}">


                                            </div>
                                            @php
                                                $disc = (float) $detail->discount / 100.0;
                                                $disc_cost = (float) $price * $disc;
                                                $price_disc = (float) ($price - $disc_cost) * $detail->qty;
                                            @endphp
                                            <div class="col-6 col-lg-3 form-group">
                                                <label>Total</label>
                                                <input type="text" readonly class="form-control jumlahTotal"
                                                    required value="{{ number_format(round($price_disc)) }}">

                                            </div>

                                            @if ($loop->index == 0)
                                                <div class="col-12 col-lg-4 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="form-control text-white text-center addPo"
                                                        style="border:none; background-color:#276e61">+</a>
                                                </div>
                                            @else
                                                <div class="col-6 col-lg-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="form-control text-white text-center addPo"
                                                        style="border:none; background-color:#276e61">+</a>
                                                </div>
                                                <div class="col-6 col-lg-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="form-control text-white text-center remPo"
                                                        style="border:none; background-color:#d94f5c">-</a>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-12">
                                        <button type="button" class="col-12 btn btn-outline-success btn-reload">--
                                            Click this to
                                            view total
                                            --</button>
                                        <input type="hidden" value="{{$purchase->id}}" class="purchase-id"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-lg-12 col-12">
                                        <label>Total</label>
                                        <input class="form-control total"
                                            value="{{ 'Rp. ' . number_format($purchase->total) }}" id=""
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                                data-original-title="test" data-bs-target="#detailData{{ $purchase->id }}"
                                data-bs-dismiss="modal">Back
                            </button>
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</div>

