<div class="row">
    <div class="col-md-12 col">
        <div class="font-weight-bold " id="formSo">
            <div class="form-group row">
                @if ($direct_sales != null)
                    <div class="row">
                        <div class="col-12 col-lg-4 form-group">
                            <label>Ref. Invoice Number</label>
                            <input class="form-control" name="ds_number" type="text" placeholder="Enter customer name"
                                value="{{ $direct_sales->order_number }}" readonly />
                        </div>
                        <div class="col-12 col-lg-4 form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" placeholder="Enter customer name"
                                @if (is_numeric($direct_sales->cust_name)) value="{{ $direct_sales->customerBy->name_cust }}"
                            @else
                            value="{{ $direct_sales->cust_name }}" @endif
                                readonly />
                            @if (is_numeric($direct_sales->cust_name))
                                <input type="hidden" name="customer_id" value="{{ $direct_sales->cust_name }}"
                                    id="">
                            @else
                                <input type="hidden" name="customer_id" value="-1" id="">
                                <input type="hidden" name="name_cust" value="{{ $direct_sales->cust_name }}"
                                    id="">
                            @endif

                        </div>
                        <div class="col-12 col-lg-4 form-group">
                            <label>Address</label>
                            <input class="form-control" name="address_cust" type="text"
                                placeholder="Enter customer address" value="{{ $direct_sales->address }}" readonly />
                        </div>
                        <input type="hidden" name="warehouse_id" id="warehouse" class="form-control"
                            value="{{ $direct_sales->warehouse_id }}">
                    </div>
                @else
                    <input name="ds_number" type="hidden" value="-" readonly />
                    <div class="col-12 col-lg-6 form-group">
                        <label>
                            Customer</label>
                        <select name="customer_id" id="customer" required
                            class="form-control multiSelect customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}"
                            multiple>
                            <option value="-1">Other</option>
                            @foreach ($customer as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->code_cust }} -
                                    {{ $customer->name_cust }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
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
                        <div class="col-12 col-lg-6 form-group">
                            <label>Warehouse</label>
                            <select name="warehouse_id" class="form-control multiSelect" id="warehouse" required
                                multiple>
                                @foreach ($user_warehouse as $item)
                                    <option value="{{ $item->id }}">{{ $item->warehouses }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="other-customer row" hidden>
                        <div class="col-12 col-lg-6 form-group">
                            <label>Name</label>
                            <input class="form-control" name="name_cust" type="text"
                                placeholder="Enter customer name" />
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <label>Address</label>
                            <input class="form-control" name="address_cust" type="text"
                                placeholder="Enter customer address" />
                        </div>
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
                <div class="mb-2 col-12 col-lg-5">
                    <label class="text-dark">Product</label>
                    <select name="promFields[0][product_id]" class="form-control multi-so" required multiple>
                        {{-- <option value="">Choose Product</option> --}}
                    </select>
                    @error('promFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6 col-lg-3">
                    <label>Price by Purchase</label>
                    <select name="promFields[0][price]" class="form-control price" required multiple>

                    </select>
                    @error('promFields[0][price]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6 col-lg-2">
                    <label>Qty</label>
                    <input type="number" class="form-control qty cekQty" required name="promFields[0][qty]"
                        id="">
                    <small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>
                    <small class="text-black qty-stock" hidden></small>
                    @error('promFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
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
        <a class="btn btn-danger" href="{{ url('material-promotion/transaction') }}"> <i class="ti ti-arrow-left">
            </i>
            Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <span class="sr-only">Loading...</span>
            Save
        </button>
    </div>
</div>
