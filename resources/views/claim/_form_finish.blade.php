    <div class="row">
        <div class="col-sm-14 col-md-12 col-lg-12">
            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="ribbon ribbon-clip ribbon-primary">Information Claim</div>
                    <div class="col-md-12">
                        <div class="form-group row font-weight-bold">

                            <div class="form-group col-md-6">
                                <label>Claim number</label>
                                <input type="text" class="form-control " placeholder="Product Name" readonly
                                    value="{{ $value->claim_number }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Claim date</label>
                                <input type="date" class="form-control" placeholder="Serial Number" readonly
                                    value="{{ $value->claim_date }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Car Type</label>
                                <input type="text" class="form-control text-capitalize" placeholder="Serial Number"
                                    readonly
                                    value="{{ $value->carBrandBy->car_brand }} / {{ $value->carTypeBy->car_type }}">

                            </div>
                            <div class="form-group col-md-6">
                                <label>Accu type</label>
                                <input type="text" class="form-control text-uppercase" placeholder="Product Code"
                                    readonly
                                    value="@if ($value->material == null) {{ $value->product_id }}@else{{ $value->material }}/{{ $value->type_material }}/{{ $value->product_id }} @endif">
                            </div>

                            <div class="form-group col-md-6">
                                <label>
                                    Plat Number</label>
                                <input type="text" class="form-control text-uppercase" placeholder="Serial Number"
                                    readonly value="{{ $value->plate_number }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>
                                    Customer/Phone Number</label>
                                <input type="text" class="form-control" placeholder="Serial Number" readonly
                                    value="{{ $value->customer_id }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="ribbon ribbon-clip ribbon-warning">Finally Checking</div>
                    <div class="col-md-12">
                        <div class="form-group row font-weight-bold">
                            <div class="col-lg-6 col-md-12 form-group">
                                <label>Voltage</label>
                                <input type="number" required placeholder="Enter Voltage"
                                    class="form-control {{ $errors->first('f_voltage') ? ' is-invalid' : '' }}"
                                    name="f_voltage">
                                @error('f_voltage')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label>CCA</label>
                                <input type="number" placeholder="Enter CCA" required
                                    class="form-control  {{ $errors->first('f_cca') ? ' is-invalid' : '' }}"
                                    name="f_cca">
                                @error('f_cca')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6  col-md-12 form-group">
                                <label>Starting</label>
                                <input type="number" required placeholder="Enter Starting"
                                    class="form-control {{ $errors->first('f_starting') ? ' is-invalid' : '' }}"
                                    name="f_starting">
                                @error('f_starting')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label>Charging</label>
                                <input type="number" required placeholder="Enter Charging"
                                    class="form-control {{ $errors->first('f_charging') ? ' is-invalid' : '' }}"
                                    name="f_charging">
                                @error('f_charging')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-12 form-group">
                                <label>Cost (Rp)</label>
                                <input type="number" placeholder="Enter Claim Cost" class="form-control"
                                    name="cost">
                            </div>
                            <div class="col-12 col-md-12 form-group">
                                <label>Result</label>
                                <select name="result" id="" class="form-control uoms">
                                    <option value="" selected>-Choose Result-</option>
                                    <option value="CP01 - Good Condition">CP01 - Good Condition</option>
                                    <option value="CP02 - Waranty Rejected">CP02 - Waranty Rejected</option>
                                    <option value="CP03 - Waranty Accepted">CP03 - Waranty Accepted</option>
                                    <option value="CP04 - Good Will">CP04 - Good Will</option>
                                </select>
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
                                <a class="btn btn-danger" href="{{ url('claim/') }}"> <i class="ti ti-arrow-left">
                                    </i> Back
                                </a>
                                <button type="reset" class="btn btn-warning">Reset</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
