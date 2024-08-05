<div class="row">
    <div class="col-md-12">
        <div class=" font-weight-bold " id="formTradeIn">
            @if ($user_warehouse->count() == 1)
                @foreach ($user_warehouse as $item)
                    <input type="hidden" name="warehouse_id" id="warehouse" class="form-control"
                        value="{{ $item->id }}">
                @endforeach
            @else
                <div class="form-group row">
                    <div class="col-12 col-lg-4 form-group">
                        <label>Warehouse</label>
                        <select name="warehouse_id" class="form-control multiSelect" id="warehouse" required multiple>
                            @foreach ($user_warehouse as $item)
                                <option value="{{ $item->id }}">{{ $item->warehouses }}</option>
                            @endforeach
                        </select>
                    </div>
            @endif

            <div class="col-12 col-lg-4 form-group">
                <label>
                    Customer</label>
                <input type="text" name="customer" class="form-control text-capitalize" placeholder="Name Customer"
                    required>
            </div>
            {{-- <div class="col-lg-4 col-12 form-group">
                <label>
                    Customer NIK</label>
                <input type="number" name="customer_nik" data-v-min-length="16" data-v-max-length="16" number
                    class="form-control" placeholder="Customer NIK">
            </div> --}}
            <div class="col-lg-4 col-12 form-group">
                <label>
                    Customer Phone</label>
                <input type="text" data-v-min-length="9" data-v-max-length="13" number name="customer_phone"
                    class="form-control" placeholder="Customer Phone" required>
            </div>
            {{-- <div class="col-lg-4 col-12 form-group">
                <label>
                    Customer Email</label>
                <input type="email" name="customer_email" class="form-control" placeholder="Customer Email">
            </div> --}}
        </div>
        <div class="mx-auto py-2 form-group row rounded" style="background-color: #f0e194">
            <div class="form-group col-8 col-lg-4">
                <label>Product</label>
                <select name="tradeFields[0][product_trade_in]" class="form-control all_product_TradeIn id_product"
                    required multiple>
                </select>
            </div>
            <div class="col-4 col-lg-2 form-group">
                <label>Qty</label>
                <small class="text-danger qty-warning" hidden>Out of Stock</small>

                <input placeholder="0" class="form-control cek_stock" required name="tradeFields[0][qty]"
                    id="">

            </div>
            <div class="col-4 col-lg-2 form-group">
                <label>Disc (%)</label>
                <input value="0" type="number" class="form-control " name="tradeFields[0][disc_percent]"
                    id="">
            </div>
            <div class="col-8 col-lg-2 form-group">
                <label>Disc (Rp)</label>
                <input value="0" class="form-control total" id="">
                <input value="0" type="hidden" name="tradeFields[0][disc_rp]">
            </div>
            <div class="col-12 col-lg-2 form-group">
                <label for="">&nbsp;</label>
                <a id="" href="javascript:void(0)" class="form-control text-white addTradeIn text-center"
                    style="border:none; background-color:#276e61">+</a>
            </div>

        </div>
    </div>

</div>

<div class="form-group">
    <a class="btn btn-danger" href="{{ url('sales_order/') }}"> <i class="ti ti-arrow-left"> </i> Back
    </a>
    <button type="reset" class="btn btn-warning">Reset</button>
    <button type="submit" class="btn btn-primary save-button">Save</button>
</div>
</div>
