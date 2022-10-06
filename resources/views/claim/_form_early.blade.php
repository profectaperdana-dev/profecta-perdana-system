<div class="row">
    <div class="col-sm-14 col-md-12 col-lg-12">
        <div class="ribbon-wrapper card">
            <div class="card-body">
                <div class="ribbon ribbon-clip ribbon-primary">Information Claim</div>
                <div class="row form-group col-md-12">

                    {{-- customer --}}
                    <div class="col-lg-12 col-md-12 form-group">
                        <label>Customer</label>
                        <select name="customer_id" id="cust" required
                            class="form-control uoms {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                            <option value="" selected>-Choose Customer-</option>
                            <option value="Other Customer">Other Customer</option>
                            @foreach ($customer as $row)
                                <option value="{{ $row->code_cust }} - {{ $row->name_cust }}">
                                    {{ $row->code_cust }} - {{ $row->name_cust }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="input-group" class="otherCustomer mt-3">
                            {{-- Sub Name Customer --}}
                            <input name="sub_name" type="text" id="other_name" required
                                class="form-control text-capitalize fw-bold {{ $errors->first('sub_name') ? ' is-invalid' : '' }}"
                                placeholder="Enter Name" aria-label="Username">
                            @error('sub_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            {{-- End Sub Name Customer --}}

                            {{-- SUb Phone Customer --}}
                            <input name="sub_phone" type="number" id="other_phone"
                                class="form-control fw-bold {{ $errors->first('sub_phone') ? ' is-invalid' : '' }}"
                                required placeholder="Enter Phone" aria-label="Server">
                            @error('sub_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            {{-- End Sub Phone Customer --}}
                        </div>
                    </div>
                    {{-- End Customer --}}

                    {{-- Product --}}
                    <div class="col-lg-12 col-md-12 form-group">
                        <label>Product </label>
                        <select name="product_id" id="product_id" required
                            class="form-control uoms {{ $errors->first('product_id') ? ' is-invalid' : '' }}">
                            <option value="" selected>-Choose Product-</option>
                            @foreach ($product as $row)
                                <option value="{{ $row->nama_barang }}"
                                    data-material="{{ $row->sub_materials->nama_sub_material }}"
                                    data-type_material="{{ $row->sub_types->type_name }}"
                                    data-parent_material={{ $row->materials->nama_material }}>
                                    {{ $row->sub_materials->nama_sub_material }}/{{ $row->sub_types->type_name }}/{{ $row->nama_barang }}
                                </option>
                            @endforeach
                            <input type="hidden" name="material" id="material">
                            <input type="hidden" name="type_material" id="type_material">
                            <input type="hidden" name="parent_material" id="parent_material">
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- End Product --}}

                    {{-- Information Car --}}
                    <div class="row">
                        <div class="col-12 col-md-4  form-group">
                            <label>Plat Number</label>
                            <input type="text" required placeholder="Enter Plat Number"
                                class="form-control text-uppercase {{ $errors->first('plate_number') ? ' is-invalid' : '' }}"
                                name="plate_number">
                            @error('plate_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4  form-group">
                            <label>Car Brands</label>
                            <select name="car_brand_id" id="brand"
                                class="form-control uoms {{ $errors->first('car_brand_id') ? ' is-invalid' : '' }}">
                                <option value="" selected>-Choose Car Brands-</option>
                                @foreach ($brand as $value)
                                    <option value="{{ $value->id }}">{{ $value->car_brand }}</option>
                                @endforeach
                            </select>
                            @error('car_brand_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4  form-group">
                            <label>Car Type</label>
                            <select name="car_type_id" id="carType"
                                class="form-control uoms {{ $errors->first('car_type_id') ? ' is-invalid' : '' }}">
                                <option value="" selected>-Choose Car Brands-</option>
                            </select>
                            @error('car_type_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    {{-- End Information Car --}}

                </div>
            </div>
        </div>
    </div>
    {{-- CLAIM FORM --}}
    <div class="col-sm-14 col-md-12 col-lg-12">
        <div class="ribbon-wrapper card">
            <div class="card-body">
                <div class="ribbon ribbon-clip ribbon-warning">Early Checking</div>
                <div class="col-md-12">
                    {{-- FORM CLAIM ACCU --}}
                    <div class="form-group row font-weight-bold" id="accu_claims">
                        <div class="col-lg-3 col-md-12 form-group">
                            <label>Voltage</label>
                            <input type="number" required placeholder="Enter Voltage"
                                class="form-control {{ $errors->first('e_voltage') ? ' is-invalid' : '' }}"
                                name="e_voltage">
                            @error('e_voltage')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-12 form-group">
                            <label>CCA</label>
                            <input type="number" required placeholder="Enter CCA"
                                class="form-control  {{ $errors->first('e_cca') ? ' is-invalid' : '' }}"
                                name="e_cca">
                            @error('e_cca')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-3  col-md-12 form-group">
                            <label>Starting</label>
                            <input type="number" required placeholder="Enter Starting"
                                class="form-control {{ $errors->first('e_starting') ? ' is-invalid' : '' }}"
                                name="e_starting">
                            @error('e_starting')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-12 form-group">
                            <label>Charging</label>
                            <input type="number" required placeholder="Enter Charging"
                                class="form-control {{ $errors->first('e_charging') ? ' is-invalid' : '' }}"
                                name="e_charging">
                            @error('e_charging')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 form-group">
                                <label>Diagnostic :<span class="text-danger">
                                        *Please checklist diagnosis
                                        suitably</span>
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label class="d-block" for="chk-ani">
                                    <input class="checkbox_animated" id="chk-ani" type="checkbox"
                                        value="Problematic dynamo" name="diagnosa[]">
                                    Problematic dynamo
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">

                                <label class="d-block" for="chk-ani1">
                                    <input class="checkbox_animated" id="chk-ani1" type="checkbox"
                                        name="diagnosa[]" value="There is a leak">
                                    There is a leak
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">

                                <label class="d-block" for="chk-ani2">
                                    <input class="checkbox_animated" id="chk-ani2" type="checkbox"
                                        name="diagnosa[]" value="Forgot to turn off the vehicle electricity">
                                    Forgot to turn off the vehicle electricity
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">

                                <label class="d-block" for="chk-ani3">
                                    <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                        name="diagnosa[]" value="Loose or dirty battery fastener">
                                    Loose or dirty
                                    battery fastener
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">

                                <label class="d-block" for="chk-ani3">
                                    <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                        name="diagnosa[]" value="Dynamo start problem">
                                    Dynamo start
                                    problem
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">

                                <label class="d-block" for="chk-ani3">
                                    <input class="checkbox_animated" id="chk-ani3" type="checkbox" Ignition problem
                                        name="diagnosa[]" value="Ignition problem">
                                    Ignition problem
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">

                                <label class="d-block" for="chk-ani3">
                                    <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                        name="diagnosa[]" value="The cable period is lacking or damaged or unstable">
                                    The cable period
                                    is lacking or damaged or unstable
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label class="d-block" for="chk-ani3">
                                    <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                        name="diagnosa[]" value="Broken battery">
                                    Broken battery
                                </label>
                                <input type="text" class="form-control" placeholder="Enter other diagnosa"
                                    name="other_diagnosa">
                            </div>
                        </div>
                    </div>
                    {{-- END CLAIM ACCU --}}

                    {{-- FORM RECEIVED --}}
                    <div class="col-lg-12 col-md-12 form-group">
                        <label>Claim Evidence</label>
                        <input type="file" class="form-control" name="file" id="inputreference" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 offset-md-4 text-center">
                            <label id="previewLabel" hidden>Preview Image</label>
                            <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;"
                                hidden />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="" for="">Signature: <span class="text-danger">*Don't
                                    leave
                                    this page before save this signature</span></label>
                            <br />
                            <div id="sig"></div>
                            <br />
                            <textarea id="signature64" name="signed" style="display: none" required></textarea>
                            <br>
                            <button id="clear" class="btn btn-warning">Clear Signature</button>
                        </div>
                    </div>
                    {{-- END FORM RECEIVED --}}

                    {{-- BUTTON --}}
                    <div class="form-group">
                        <a class="btn btn-danger" href="{{ url('claim/') }}"> <i class="ti ti-arrow-left"> </i>
                            Back
                        </a>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                    {{-- END BUTTON --}}

                </div>
            </div>
        </div>
    </div>
    {{-- END CLAIM FORM --}}
</div>
