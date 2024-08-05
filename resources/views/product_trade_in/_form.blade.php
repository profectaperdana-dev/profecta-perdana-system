<div class="row">
    <div class="col-md-12">
        <div class=" font-weight-bold " id="formTradeIn">
            <div class="form-group row">
                <div class="col-12 col-md-6 form-group">
                    <label>
                        Ref. Retail Order Number
                    </label>
                    <input value="{{ $ref }}" type="text" placeholder="Enter Reference" class="form-control"
                        name="retail_order_number" @if ($ref) readonly @endif required>
                </div>

                @if ($user_warehouse->count() == 1)
                    @foreach ($user_warehouse as $item)
                        <input type="hidden" name="warehouse_id" id="warehouse" class="form-control"
                            value="{{ $item->id }}">
                    @endforeach
                @else
                    <div class="col-12 col-md-6 form-group">
                        <label>Warehouse</label>
                        <select name="warehouse_id" class="form-control multiSelect" id="warehouse" multiple>
                            {{-- <option value="">Choose Warehouse</option> --}}
                            @foreach ($user_warehouse as $item)
                                <option value="{{ $item->id }}">{{ $item->warehouses }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

            </div>

            <div class="mx-auto py-2 form-group row rounded" style="background-color: #f0e194">
                <div class="form-group col-8 col-lg-7">
                    <label>Baterry</label>
                    <select name="tradeFields[0][product_trade_in]" class="form-control all_product_TradeIn" required
                        multiple>
                        {{-- <option value="">-Choose Battery-</option> --}}
                    </select>
                    @error('tradeFields[0][product_trade_in]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-4 col-lg-3 form-group">
                    <label>Qty</label>
                    <input class="form-control qty" required name="tradeFields[0][qty]" id="">
                    @error('tradeFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12 col-lg-2 form-group">
                    <label for="">&nbsp;</label>
                    <a id="" href="javascript:void(0)" class="form-control addTradeIn text-white  text-center"
                        style="border:none; background-color:#276e61">+</a>
                </div>

            </div>
        </div>

    </div>

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url()->previous() }}"> <i class="ti ti-arrow-left"> </i> Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
