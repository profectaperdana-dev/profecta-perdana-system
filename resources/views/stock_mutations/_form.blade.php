<div class="row">
    <div class="col-md-12">
        <div class="row font-weight-bold " id="formPo">
            <div class="form-group row">
                <div class="col-md-6 form-group mr-5">
                    <label>From Warehouse</label>
                    <select name="from" required
                        class="form-control uoms {{ $errors->first('from') ? ' is-invalid' : '' }}" id="from_warehouse">
                        @can('isSuperAdmin')
                            <option value="" selected>-Choose Warehouse From-</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->warehouses }}
                                </option>
                            @endforeach
                        @elsecan('isWarehouseKeeper')
                            <option value="{{ Auth::user()->warehouse_id }}" selected>
                                {{ Auth::user()->warehouseBy->warehouses }}
                            </option>
                        @endcan

                    </select>
                    @error('from')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>
                        To Warehouse</label>
                    <select name="to" id="" required
                        class="form-control uoms {{ $errors->first('to') ? ' is-invalid' : '' }}">
                        <option value="" selected>-Choose Warehouse To -</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->warehouses }}
                            </option>
                        @endforeach
                    </select>
                    @error('to')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
            <div class="form-group row">
                <div class="col-md-12 form-group mr-5">
                    <label>Remarks</label>
                    <textarea class="form-control" name="remark" id="" cols="30" rows="5" required></textarea>
                </div>
            </div>

            <div class="form-group row" id="formMutation">
                <div class="form-group col-7">
                    <label>Product</label>
                    <select name="mutationFields[0][product_id]" class="form-control productM" required>
                        <option value="">Choose Product</option>
                    </select>
                    @error('mutationFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-3 col-md-3 form-group">
                    <label>Qty</label>
                    <input type="number" class="form-control" required name="mutationFields[0][qty]" id="">
                    <small class="from-stock" hidden>Stock : 0</small>
                    @error('mutationFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-2 col-md-2 form-group">
                    <label for="">&nbsp;</label>
                    <a id="addM" href="javascript:void(0)" class="form-control text-white  text-center"
                        style="border:none; background-color:green">+</a>
                </div>

            </div>


        </div>

    </div>

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('mutation_stockss/') }}"> <i class="ti ti-arrow-left"> </i> Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
