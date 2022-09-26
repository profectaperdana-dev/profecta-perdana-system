<div class="row">
    <div class="col-md-12">
        <div class="row" id="formReturn">
            <div class="row">
                <input type="hidden" name="so_id" value="{{ $sales_order->id }}" id="so_id">
                <div class="form-group col-7">
                    <label>Product</label>
                    <select name="returnFields[0][product_id]" class="form-control productReturn" required>
                        <option value="">Choose Product</option>
                    </select>
                    @error('returnFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-3 col-md-3 form-group">
                    <label>Qty</label>
                    <input type="number" class="form-control" required name="returnFields[0][qty]" id="">
                    @error('returnFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-2 col-md-2 form-group">
                    <label for="">&nbsp;</label>
                    <a id="addReturn" href="javascript:void(0)" class="form-control text-white text-center"
                        style="border:none; background-color:green">+</a>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="form-group col-6">
                <label for="">Return Reason</label>
                <select name="return_reason1" class="form-control uoms return_reason1" required>
                    <option value="">-- Choose Return Reason -- </option>
                    <option value="Wrong Discount">Wrong Discount</option>
                    <option value="Wrong Quantity">Wrong Quantity</option>
                    <option value="Wrong Product Type">Wrong Product Type</option>
                    <option value="Bad Debt">Bad Debt</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group col-1 return_reason2" hidden>
                <label for="">&nbsp;</label>
                <p class="form-group text-center pt-2"><strong>By:</strong></p>
            </div>
            <div class="form-group col-5 return_reason2" hidden>
                <label for="">&nbsp;</label>
                <select name="return_reason2" class="form-control uoms">
                    <option value="">-- Choose Who's Responsible -- </option>
                    <option value="Admin">Admin</option>
                    <option value="Warehouse Keeper">Warehouse Keeper</option>
                </select>
            </div>
            <div class="form-group col-6 other" hidden>
                <label for="">&nbsp;</label>
                <textarea name="return_reason" class="form-control" rows="3" placeholder="Write Your Reasons Here..."></textarea>
            </div>
        </div>
        <div class="form-group">
            <a class="btn btn-danger" href="{{ url('/invoice') }}"> <i class="ti ti-arrow-left"> </i> Back
            </a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </div>
</div>
