<div class="row">
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="form-group col-md-12">
                <input type="text" class="form-control bg-success text-white text-center" placeholder="Serial Number"
                    readonly value="Information Claim">
            </div>
            <div class="col-6 col-md-12 form-group">
                <label>Customer</label>
                <select name="customer_id" id="cust" required
                    class="form-control uoms {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Customer-</option>
                    @foreach ($customer as $row)
                        <option value="{{ $row->name_cust }}}">{{ $row->name_cust }}</option>
                    @endforeach
                    <option value="other">Other Customer</option>
                    <input id="otheCustomer" name="other" type="text" class="form-control"
                        placeholder="other customer">
                </select>
                @error('customer_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 col-md-12 form-group">
                <label>Accu Type</label>
                <select name="product_id" id="" required
                    class="form-control uoms {{ $errors->first('product_id') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Accu Type-</option>
                    @foreach ($product as $row)
                        <option value="{{ $row->id }}">
                            {{ $row->sub_materials->nama_sub_material }}/{{ $row->sub_types->type_name }}/{{ $row->nama_barang }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-12 form-group">
                <label>Car Type</label>
                <input type="text" required
                    class="form-control {{ $errors->first('car_type') ? ' is-invalid' : '' }}" name="car_type">
                @error('car_type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-12 form-group">
                <label>Plat Number</label>
                <input type="text" required
                    class="form-control {{ $errors->first('plate_number') ? ' is-invalid' : '' }}" name="plate_number">
                @error('plate_number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-12">
                <input type="text" class="form-control bg-warning text-white text-center" placeholder="Serial Number"
                    readonly value="Early Check">
            </div>
            <div class="col-lg-3 col-md-12 form-group">
                <label>Voltage</label>
                <input type="text" required
                    class="form-control {{ $errors->first('e_voltage') ? ' is-invalid' : '' }}" name="e_voltage">
                @error('e_voltage')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-lg-3 col-md-12 form-group">
                <label>CCA</label>
                <input type="text" required class="form-control  {{ $errors->first('e_cca') ? ' is-invalid' : '' }}"
                    name="e_cca">
                @error('e_cca')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-lg-3  col-md-12 form-group">
                <label>Starting</label>
                <input type="text" required
                    class="form-control {{ $errors->first('e_starting') ? ' is-invalid' : '' }}" name="e_starting">
                @error('e_starting')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-lg-3 col-md-12 form-group">
                <label>Charging</label>
                <input type="text" required
                    class="form-control {{ $errors->first('e_charging') ? ' is-invalid' : '' }}" name="e_charging">
                @error('e_charging')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-12 col-md-12 form-group">
                <label>Diagnosa</label>
                <textarea name="diagnosa" required id="" cols="30" rows="10"
                    class="form-control {{ $errors->first('diagnosa') ? ' is-invalid' : '' }}"></textarea>
                @error('diagnosa')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-12 col-md-12 form-group">
                <label>Receipt Method</label>
                <select name="receipt_method" id="choose_received" required
                    class="form-control uoms {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Method-</option>

                    <option value="signature">Signature</option>
                    <option value="file">Upload File</option>

                </select>
                @error('customer_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div id="file_received" class="col-lg-12 col-md-12 form-group">
                <label>Upload File</label>
                <input type="file" class="form-control" name="file">

            </div>
            <div id="ttd_received" class="col-12 form-group">
                <label class="" for="">Signature: <span class="text-danger">*Don't
                        leave
                        this page before save this signature</span></label>
                <br />
                <div id="sig"></div>
                <br />
                <textarea id="signature64" name="signed" style="display: none"></textarea>
                <br>
                <button id="clear" class="btn btn-warning">Clear Signature</button>
            </div>
            <div class="form-group">
                <a class="btn btn-danger" href="{{ url('claim/') }}"> <i class="ti ti-arrow-left"> </i> Back
                </a>
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </div>
    </div>
</div>
