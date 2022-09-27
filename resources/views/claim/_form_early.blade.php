<div class="row">
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-6 form-group">
                <label>Customer</label>
                <select name="customer_id" id="cust"
                    class="form-control uoms {{ $errors->first('uom') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Customer-</option>
                    @foreach ($customer as $row)
                        <option value="{{ $row->id }}">{{ $row->name_cust }}</option>
                    @endforeach
                    <option value="other">Other Customer</option>
                    <input id="otheCustomer" type="text" class="form-control" placeholder="other customer">
                </select>
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 form-group">
                <label>Accu Type</label>
                <select name="product_id" id=""
                    class="form-control uoms {{ $errors->first('uom') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Accu Typer-</option>
                    @foreach ($product as $row)
                        <option value="{{ $row->id }}">{{ $row->nama_barang }}</option>
                    @endforeach
                </select>
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-4 form-group">
                <label>Car Type</label>
                <input type="text" class="form-control" name="car_type">
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-4 form-group">
                <label>Plat Number</label>
                <input type="text" class="form-control" name="plat_number">
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-4 form-group">
                <label>Voltage</label>
                <input type="text" class="form-control" name="e_voltage">
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-4 form-group">
                <label>CCA</label>
                <input type="text" class="form-control" name="e_cca">
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-4 form-group">
                <label>Starting</label>
                <input type="text" class="form-control" name="e_starting">
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-4 form-group">
                <label>Charging</label>
                <input type="text" class="form-control" name="e_starting">
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-12 form-group">
                <label>Diagnosa</label>
                <textarea name="diagnosa" id="" cols="30" rows="10" class="form-control"></textarea>
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-12 form-group">
                <label class="" for="">Signature:</label>
                <br />
                <div id="sig"></div>
                <br />
                <textarea id="signature64" name="signed" style="display: none"></textarea>
            </div>

            <div class="form-group">
                <a class="btn btn-danger" href="{{ url('claim/') }}"> <i class="ti ti-arrow-left"> </i> Back
                </a>
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
