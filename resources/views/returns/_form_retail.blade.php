<div class="row">
    <div class="col-md-12">
        <div class="row" id="formReturn">
            <input type="hidden" name="retail_id" value="{{ $retail->id }}" id="retail_id">
            @foreach ($retail->directSalesDetailBy as $item)
                <input type="hidden" name="loop" value="{{ $loop->index }}" class="loop">
                <div class="row rounded pt-2 mb-3 mx-auto" style="background-color: #f0e194">
                    <div class="form-group col-12 col-lg-7">
                        <label>Product</label>
                        <select multiple name="returnFields[{{ $loop->index }}][product_id]"
                            class="form-control productReturn" required>
                            <option value="{{ $item->product_id }}" selected>
                                {{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}
                            </option>
                        </select>
                        @error('returnFields[{{ $loop->index }}][product_id]')
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

                    <div class="col-3 col-lg-2 form-group">
                        <label for="">&nbsp;</label>
                        <a id="" href="javascript:void(0)"
                            class="form-control remReturn text-white text-center"
                            style="border:none; background-color:red">-</a>
                    </div>

                    <div class="parentDot">
                        @if ($item->productBy->materials->nama_material == 'Tyre')
                            <div class="row parentRow" data-dotIndex="0">
                                <label for="DOT">DOT</label>
                                <div class="col-4 col-lg-3 form-group mt-0">
                                    <select multiple name="returnFields[{{ $loop->index }}][0][dot]"
                                        class="form-control dotReturn" required>
                                        {{-- <option value="" selected>
                                    DOT
                                </option> --}}
                                        @php
                                            $options = collect($item->directSalesCodeBy)->groupBy('dot'); // group the items by their dot attribute
                                        @endphp
                                        @foreach ($options as $option)
                                            @if ($option->first()->dotBy != null)
                                                {{-- // check if the option has a dotBy attribute --}}
                                                <option value="{{ $option->first()->dot }}">
                                                    {{ $option->first()->dotBy->dot }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-4 col-lg-3 form-group">
                                    <input type="text" class="form-control"
                                        name="returnFields[{{ $loop->index }}][0][qtyDot]" id=""
                                        placeholder="Qty" required>
                                </div>
                                <div class="col-4 col-lg-2 form-group">
                                    <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                                        style="border:none; background-color:#276e61">+</a>
                                </div>

                            </div>
                        @endif
                    </div>


                </div>
            @endforeach
        </div>
        <div class="row mt-3">
            <div class="form-group col-12 col-lg-6">
                <label for="">Return Reason</label>
                <select multiple name="return_reason1" class="form-control multi-select return_reason1" required>
                     <option value="Bad Debt">Bad Debt</option>
                    <option value="Change Customer Data">Change Customer Data</option>
                    <option value="Change Invoice Data">Change Invoice Data</option>
                    <option value="Double Input">Double Input</option>
                    <option value="Wrong Discount">Wrong Discount</option>
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
                <select multiple name="return_reason2" class="form-control multi-select">
                    <option value="Admin">Admin</option>
                    <option value="Warehouse Keeper">Warehouse Keeper</option>
                    <option value="Customer">Customer</option>
                    <option value="Sales">Sales</option>
                    <option value="Retail">Retail</option>
                </select>
            </div>
            <div class="form-group col-12 col-lg-6 other" hidden>
                <label for="">&nbsp;</label>
                <textarea name="return_reason" class="form-control" rows="3" placeholder="Write Your Reasons Here..."></textarea>
            </div>
        </div>
        <div class="form-group">
            <a class="btn btn-danger" href="{{ url('/retail') }}"> <i class="ti ti-arrow-left"> </i> Back
            </a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </div>
</div>
