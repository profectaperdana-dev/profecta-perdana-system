<div class="row">
  <div class="col-md-12">
    <div class="row font-weight-bold " id="formPo">
      <div class="form-group row">
        <div class="col-md-4 form-group">
          <label>
            Supplier</label>
          <select name="supplier_id" id="" required
            class="form-control supplier-select {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}">
            <option value="" selected>-Choose Supplier-</option>
            @foreach ($suppliers as $supplier)
              <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}
              </option>
            @endforeach
          </select>
          @error('supplier_id')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="col-md-4 form-group mr-5">
          <label>Warehouse</label>
          <select name="warehouse_id" required
            class="form-control warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}">
            <option value="" selected>-Choose Payment-</option>
            @foreach ($warehouses as $warehouse)
              <option value="{{ $warehouse->id }}">{{ $warehouse->warehouses }}
              </option>
            @endforeach
          </select>
          @error('warehouse_id')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="col-md-4 form-group mr-5">
          <label>Due Date</label>
          <input class="form-control" type="date" data-language="en" name="due_date" required>
          @error('due_date')
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
      <div class="form-group row">
        <div class="form-group col-7">
          <label>Product</label>
          <select name="poFields[0][product_id]" class="form-control productPo" required>
            <option value="">Choose Product</option>
          </select>
          @error('poFields[0][product_id]')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="col-3 col-md-3 form-group">
          <label>Qty</label>
          <input type="number" class="form-control" required name="poFields[0][qty]" id="">
          @error('poFields[0][qty]')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="col-2 col-md-2 form-group">
          <label for="">&nbsp;</label>
          <a id="addPo" href="javascript:void(0)" class="form-control text-white  text-center"
            style="border:none; background-color:green">+</a>
        </div>

      </div>
    </div>

  </div>

  <div class="form-group">
    <a class="btn btn-danger" href="{{ url('purchase_orders/') }}"> <i class="ti ti-arrow-left"> </i> Back
    </a>
    <button type="reset" class="btn btn-warning">Reset</button>
    <button type="submit" class="btn btn-primary">Save</button>
  </div>
</div>
