<div class="row">
    <div class="col-md-12">
        <div class="font-weight-bold " id="formPo">
            <div class="form-group row">
                <div class="col-md-4 form-group">
                    <label>
                        Vendor</label>
                    <select name="supplier_id" id="" required
                        class="form-control multiSelect  {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}"
                        multiple>
                        {{-- <option value="" selected>-Choose Vendor-</option> --}}
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}
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
                    <select name="warehouse_id" required
                        class="form-control multiSelect warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}"
                        multiple>
                        {{-- <option value="" selected>-Choose Warehouse-</option> --}}
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->warehouseBy->warehouses }}
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
                    <select name="payment_method" required
                        class="form-control multiSelect {{ $errors->first('payment_method') ? ' is-invalid' : '' }}"
                        multiple>

                        <option value="cash">
                            Cash
                        </option>
                        <option value="credit">
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

            <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                <div class="mb-2 col-12 col-lg-7">
                    <label>Product</label>
                    <select name="poFieldss[0][product_id]" class="form-control productPo" required multiple>
                        {{-- <option value="">Choose the warehouse first</option> --}}
                    </select>
                    @error('poFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6 col-lg-3 mb-2">
                    <label>Qty</label>
                    <input type="number" class="form-control qty" required name="poFieldss[0][qty]">
                    @error('poFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-6 col-lg-2 mb-2">
                    <label for="">&nbsp;</label>
                    <a href="javascript:void(0)" class="form-control addPo text-white  text-center"
                        style="border:none; background-color:#276e61">+</a>
                </div>

            </div>
        </div>

    </div>

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('purchase_orders/') }}"> <i class="ti ti-arrow-left"> </i> Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
