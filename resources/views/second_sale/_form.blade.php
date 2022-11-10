<div class="row">
    <div class="col-md-12">
        <div class="row font-weight-bold " id="formTradeIn">
            <div class="form-group row">
                <div class="col-md-6 form-group">
                    <label>
                        Customers</label>
                    <input type="text" name="customer" class="form-control text-capitalize" placeholder="Name Customer"
                        required>
                </div>
                <div class="col-md-6 form-group">
                    <label>
                        Customers NIK</label>
                    <input type="number" name="customer_nik" data-v-min-length="16" data-v-max-length="16" number
                        class="form-control" placeholder="Customer NIK">
                </div>
                <div class="col-md-6 form-group">
                    <label>
                        Customers Phone</label>
                    <input type="text" data-v-min-length="9" data-v-max-length="13" number name="customer_phone"
                        class="form-control" placeholder="Customer Phone" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>
                        Customers Email</label>
                    <input type="email" name="customer_email" class="form-control" placeholder="Customer Email">
                </div>
            </div>
            <div class="mx-auto py-2 form-group row bg-primary">
                <div class="form-group col-6 col-md-4">
                    <label>Baterry</label>
                    <select name="tradeFields[0][product_trade_in]" class="form-control all_product_TradeIn id_product"
                        required>
                        <option value="">--Choose Battery--</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 form-group">
                    <label>Qty</label>
                    <small class="text-danger qty-warning" hidden>Out of Stock</small>

                    <input class="form-control cek_stock" required name="tradeFields[0][qty]" id="">

                </div>
                <div class="col-5 col-md-2 form-group">
                    <label>Disc (%)</label>
                    <input type="number" class="form-control " name="tradeFields[0][disc_percent]" id="">
                </div>
                <div class="col-5 col-md-2 form-group">
                    <label>Disc (Rp)</label>
                    <input class="form-control total" id="">
                    <input type="hidden" name="tradeFields[0][disc_rp]">
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
        <button type="submit" class="btn btn-primary save-button">Save</button>
    </div>
</div>
