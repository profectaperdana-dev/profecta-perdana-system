<div class="row">
    <div class="col-md-12">
        <div class="" id="formReturn">
            <input type="hidden" name="so_id" value="{{ $item_promotion->id }}" id="so_id">
            <input type="hidden" name="warehouse" value="{{ $item_promotion->id_warehouse }}" id="warehouse_id">
            @foreach ($item_promotion->transactionDetailBy as $item)
                <div class="row pt-2 rounded mb-3" style="background-color: #f0e194">
                    <div class="form-group col-12 col-lg-5">
                        <label>Product</label>
                        <select multiple name="returnFields[{{ $loop->index }}][product_id]"
                            class="form-control productReturn" required>
                            <option value="{{ $item->id_item }}" selected>
                                {{ $item->itemBy->name }}
                            </option>
                        </select>
                        @error('returnFields[{{ $loop->index }}][product_id]')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-9 col-lg-3 form-group">
                        <label>Price</label>
                        <select multiple name="returnFields[{{ $loop->index }}][price]" id=""
                            class="price form-control multi-price">
                            <option value="{{ $item->price }}" selected>{{ number_format($item->price) }}</option>
                        </select>
                        @error('returnFields[{{ $loop->index }}][price]')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-9 col-lg-3 form-group">
                        <label>Qty</label>
                        <input type="number" class="form-control" required
                            name="returnFields[{{ $loop->index }}][qty]" id="">
                        <small class="text-xs box-order-amount">Order Amount: <span
                                class="order-amount">{{ $item->qty }}</span></small>
                        <small class="text-xs box-return-amount "> | Returned: <span
                                class="return-amount">{{ $return_amount[$loop->index] }}</span></small>
                        @error('returnFields[{{ $loop->index }}][qty]')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-3 col-lg-1 form-group">
                        <label for="">&nbsp;</label>
                        <a id="" href="javascript:void(0)"
                            class="form-control remReturn text-white text-center"
                            style="border:none; background-color:red">-</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mt-3">
            <div class="form-group col-12 col-lg-6">
                <label for="">Return Reason</label>
                <select name="return_reason1" class="form-control multi-select return_reason1" multiple required>
                    <option value="Wrong Quantity">Wrong Quantity</option>
                    <option value="Wrong Product Type">Wrong Product Type</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group col-1 return_reason2" hidden>
                <label for="">&nbsp;</label>
                <p class="form-group text-center pt-2"><strong>By:</strong></p>
            </div>
            <div class="form-group col-11 col-lg-5 return_reason2" hidden>
                <label for="">&nbsp;</label>
                <select name="return_reason2" class="form-control multi-select" multiple>
                    <option value="Admin">Admin</option>
                    <option value="Warehouse Keeper">Warehouse Keeper</option>
                    <option value="Retail">Retail</option>
                </select>
            </div>
            <div class="form-group col-12 col-lg-6 other" hidden>
                <label for="">&nbsp;</label>
                <textarea name="return_reason" class="form-control" rows="3" placeholder="Write Your Reasons Here..."></textarea>
            </div>
        </div>
        <div class="form-group">
            <a class="btn btn-danger" href="{{ url('/material-promotion/transaction') }}"> <i class="ti ti-arrow-left">
                </i> Back
            </a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary" id="saveBtn">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <span class="sr-only">Loading...</span>
                Save
            </button>
        </div>
    </div>
</div>
