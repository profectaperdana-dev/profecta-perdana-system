<div class="row">
    <div class="col-md-12">
        <div class="row font-weight-bold " id="formPo">
            <div class="form-group row">
                <div class="col-md-6 form-group mr-5">
                    <label>From Warehouse</label>
                    <select name="from" required multiple
                        class="form-control selectMulti {{ $errors->first('from') ? ' is-invalid' : '' }}"
                        id="from_warehouse">
                        @foreach ($from_warehouse as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->warehouses }}
                            </option>
                        @endforeach

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
                    <select name="to" id="" required multiple
                        class="form-control selectMulti {{ $errors->first('to') ? ' is-invalid' : '' }}">
                        @foreach ($to_warehouse as $warehouse)
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

            <div id="formMutation">
                <div class="form-group row bg-primary pt-2 mb-3">
                    <div class="form-group col-12 col-lg-5">
                        <label>Product</label>
                        <select name="mutationFields[0][product_id]" class="form-control productM" multiple required>
                            <option value="">Choose Product</option>
                        </select>
                        @error('mutationFields[0][product_id]')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-12 col-lg-3">
                        <label>Price by Purchase</label>
                        <select name="mutationFields[0][price]" class="form-control price" multiple required>
                        </select>
                        @error('mutationFields[0][price]')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-9 col-lg-2 form-group">
                        <label>Qty</label>
                        <input type="number" class="form-control" required name="mutationFields[0][qty]"
                            id="">
                        <small class="from-stock" hidden>Stock Total : 0</small>
                        @error('mutationFields[0][qty]')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-3 col-lg-2 form-group">
                        <label for="">&nbsp;</label>
                        <a id="" href="javascript:void(0)" class="form-control addM text-white  text-center"
                            style="border:none; background-color:green">+</a>
                    </div>

                </div>
            </div>


        </div>

    </div>

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('material-promotion/mutation') }}"> <i class="ti ti-arrow-left"> </i>
            Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
