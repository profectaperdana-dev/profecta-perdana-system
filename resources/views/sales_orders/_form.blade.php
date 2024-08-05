<div class="row">
    <div class="col-md-12 col">
        <div class="font-weight-bold " id="formSo">
            <div class="form-group row">
                <div class="col-12 col-lg-4 form-group">
                    <label>
                        Customer</label>
                    <select name="customer_id" id="" required
                        class="form-control multiSelect customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}"
                        multiple>
                        {{-- <option value="" selected>-Choose Customer-</option> --}}
                        @foreach ($customer as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->code_cust }} - {{ $customer->name_cust }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-4 form-group mr-5">
                    <label>Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        class="form-control multiSelect {{ $errors->first('payment_method') ? ' is-invalid' : '' }}"
                        multiple>
                        {{-- <option value="" selected>-Choose Payment-</option> --}}
                        <option value="1">Cash On Delivery
                        </option>
                        <option value="2">Cash Before Delivery
                        </option>
                        <option value="3">Credit
                        </option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if ($user_warehouse->count() == 1)
                    @foreach ($user_warehouse as $item)
                        <input type="hidden" name="warehouse_id" id="warehouse" class="form-control"
                            value="{{ $item->id }}">
                    @endforeach
                @else
                    <div class="col-12 col-md-4 form-group">
                        <label>Warehouse</label>
                        <select name="warehouse_id" class="form-control multiSelect" id="warehouse" required multiple>
                            {{-- <option value="">Choose Warehouse</option> --}}
                            @foreach ($user_warehouse as $item)
                                <option value="{{ $item->id }}">{{ $item->warehouses }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="form-group">
                    <div class="col-md-12 form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remark" id="" cols="30" rows="3" required></textarea>
                    </div>
                </div>
            </div>
            <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                <div class="mb-2 col-12 col-lg-6">
                    <label class="text-dark">Product</label>
                    <select name="soFields[0][product_id]" class="form-control multi-so" required multiple>
                        {{-- <option value="">Choose Product</option> --}}
                    </select>
                    @error('soFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6 col-lg-2">
                    <label>Qty</label>
                    <input type="number" class="form-control qty cekQty" required name="soFields[0][qty]"
                        id="">
                    <small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>
                    @error('soFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6 col-lg-2">
                    <label>Disc (%)</label>
                    <input class="form-control discount-append" name="soFields[0][discount]" id="" readonly>
                </div>
                <div class="col-12 col-md-2">
                    <label for="">&nbsp;</label>
                    <a href="javascript:void(0)" class="form-control btn btn-sm text-white addSo text-center"
                        style="border:none; background-color:#276e61">+</a>
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('sales_order/') }}"> <i class="ti ti-arrow-left"> </i> Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <span class="sr-only">Loading...</span>
            Save
        </button>
    </div>
</div>
