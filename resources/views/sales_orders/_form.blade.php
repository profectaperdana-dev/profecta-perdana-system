<div class="row">
    <div class="col-md-12">
        <div class="row font-weight-bold " id="formSo">
            <div class="form-group row">
                <div class="col-md-6 form-group">
                    <label>
                        Customers</label>
                    <select name="customer_id" id="" required
                        class="form-control sub_type customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                        <option value="" selected>-Choose Customers-</option>
                        @foreach ($customer as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->code_cust }} | {{ $customer->name_cust }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                {{-- <div class="col-md-4 form-group">
                    <label for="">PPN 11%</label><br>
                    <select name="ppn" id="" class="sub_type form-control">
                        <option value="" selected>-Choose PPN-</option>
                        <option value="1">Include PPN</option>
                        <option value="2">Without PPN</option>
                    </select>
                </div> --}}
                <div class="col-md-6 form-group mr-5">
                    <label>Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        class="form-control sub_type {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                        <option value="" selected>-Choose Payment-</option>
                        <option value="1">Paid
                        </option>
                        <option value="2">Debt
                        </option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="form-group row">

                <div id="top" hidden class="col-md-12 form-group">
                    <label>Terms of Payment</label>
                    <input type="text" class="form-control" placeholder="Product Name" name="top" value="">
                    @error('top')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div id="payment" hidden class="col-md-6 form-group mr-6">
                    <label>Payment</label>
                    <select name="payment" id="" class="form-control sub_type ">
                        <option value="" selected>-Choose Payment-</option>
                        <option value="1">CBD
                        </option>
                        <option value="2">COD
                        </option>
                    </select>
                </div>
                <div id="payment_type" hidden class="col-md-6 form-group mr-6">
                    <label>Payment Type</label>
                    <select name="payment_type" id="sub-type" class="form-control sub_type ">
                        <option value="" selected>-Choose Payment-</option>
                        <option value="1">Cash
                        </option>
                        <option value="2">Transfer
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 form-group mr-5">
                    <label>Remarks</label>
                    <textarea class="form-control" name="remark" id="" cols="30" rows="5" required></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group col-4">
                    <label>Product</label>
                    <select name="soFields[0][product_id]" class="form-control productSo" required>
                        <option value="">Choose Product</option>
                    </select>
                    @error('soFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-3 col-md-3 form-group">
                    <label>Qty</label>
                    <input class="form-control cekQty" required name="soFields[0][qty]" id="">
                    @error('soFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-3 col-md-3 form-group">
                    <label>Discount%</label>
                    <input class="form-control discount-append" name="soFields[0][discount]" id="" readonly>
                </div>
                <div class="col-2 col-md-1 form-group">
                    <label for="">&nbsp;</label>
                    <a id="addSo" href="javascript:void(0)" class="form-control text-white  text-center"
                        style="border:none; background-color:green">+</a>
                </div>

            </div>
        </div>

    </div>

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('sales_order/') }}"> <i class="ti ti-arrow-left"> </i> Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
