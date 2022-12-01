<div class="row">
    <div class="col-md-12">
        <div class="row font-weight-bold " id="formTradeIn">
            <div class="form-group row">
                <div class="col-md-12 form-group">
                    <label>
                        Customer Data Sources
                    </label>
                    <select name="id_retail" class="form-select uoms valCust" id="">
                        <option value="" selected>--Select Refrence--</option>
                        @foreach ($retail as $value)
                            <option value="{{ $value->id }}">{{ $value->order_number }} /
                                @if (is_numeric($value->cust_name))
                                    {{ $value->customerBy->name_cust }}
                                @else
                                    {{ $value->cust_name }}
                                @endif
                            </option>
                        @endforeach
                        <option value="other">Other Refrence</option>

                    </select>
                    <input type="text" name="customer" class="form-control nameCustomer text-capitalize"
                        placeholder="Name Customer" required>

                </div>
                <div class="row otherCustomer" hidden>
                    <div class="col-md-4 form-group">
                        <label>
                            Customers NIK</label>
                        <input type="number" name="customer_nik" data-v-min-length="16" data-v-max-length="16" number
                            class="form-control" placeholder="Customer NIK">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>
                            Customers Phone</label>
                        <input type="text" data-v-min-length="9" data-v-max-length="13" number name="customer_phone"
                            class="form-control phone" placeholder="Customer Phone" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>
                            Customers Email</label>
                        <input type="email" name="customer_email" class="form-control" placeholder="Customer Email">
                    </div>
                </div>
            </div>

            <div class="mx-auto py-2 form-group row bg-primary">
                <div class="form-group col-7">
                    <label>Baterry</label>
                    <select name="tradeFields[0][product_trade_in]" class="form-control all_product_TradeIn" required>
                        <option value="">-Choose Battery-</option>
                    </select>
                    @error('tradeFields[0][product_trade_in]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-3 col-md-3 form-group">
                    <label>Qty</label>
                    <input class="form-control cekQty" required name="tradeFields[0][qty]" id="">
                    @error('tradeFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                <div class="col-2 col-md-2 form-group">
                    <label for="">&nbsp;</label>
                    <a id="addTradeIn" href="javascript:void(0)" class="form-control text-white  text-center"
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
