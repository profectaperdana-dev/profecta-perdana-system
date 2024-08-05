<div class="row">
    <div class="col-md-12 col">
        <div class="font-weight-bold " id="formSo">
            <div class="form-group row">
                <div class="col-12 col-lg-6 form-group">
                    <label>
                        Vendor</label>
                    <select name="supplier_id" id="supplier" required
                        class="form-control multiSelect customer-append {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}"
                        multiple>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if ($user_warehouse->count() == 1)
                    @foreach ($user_warehouse as $item)
                        <input type="hidden" name="warehouse_id" id="warehouse" class="form-control"
                            value="{{ $item->id }}">
                    @endforeach
                @else
                    <div class="col-12 col-lg-6 form-group">
                        <label>Warehouse</label>
                        <select name="warehouse_id" class="form-control multiSelect" id="warehouse" required multiple>
                            @foreach ($user_warehouse as $item)
                                <option value="{{ $item->id }}">{{ $item->warehouses }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="form-group">
                    <div class="col-md-12 form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remark" id="" cols="30" rows="3" required></textarea>
                    </div>
                </div>
            </div>
            <div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">
                <div class="mb-2 col-12 col-lg-4">
                    <label class="text-dark">Product</label>
                    <select name="promFields[0][product_id]" class="form-control multi-so" required multiple>
                        {{-- <option value="">Choose Product</option> --}}
                    </select>
                    @error('promFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6 col-lg-4">
                    <label>Price</label>
                    <input type="text" class="form-control price" required id="">
                    <input type="hidden" name="promFields[0][price]">
                    @error('promFields[0][price]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6 col-lg-3">
                    <label>Qty</label>
                    <input type="number" class="form-control qty cekQty" required name="promFields[0][qty]"
                        id="">
                    @error('promFields[0][qty]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12 col-md-1">
                    <label for="">&nbsp;</label>
                    <a href="javascript:void(0)" class="form-control btn btn-sm text-white addSo text-center"
                        style="border:none; background-color:#276e61">+</a>
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('material-promotion/purchase') }}"> <i class="ti ti-arrow-left"> </i>
            Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <span class="sr-only">Loading...</span>
            Save
        </button>
    </div>
</div>
