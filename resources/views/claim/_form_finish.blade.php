    <div class="row">
        <div class="col-sm-14 col-md-12 col-lg-12">
            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="ribbon ribbon-clip ribbon-primary">Information Claim</div>
                    <div class="row form-group col-md-12">
                        {{-- customer --}}
                        <div class="col-lg-12 col-md-12 form-group">
                            <label>Customer</label>
                            <input type="text" class="form-control" placeholder="Serial Number" readonly
                                value="{{ $value->customer_id }}">
                        </div>
                        <div class="col-lg-4 col-md-12 form-group">
                            <label for="">Sub Customer Name/Phone/Email</label>
                            {{-- Sub Name Customer --}}
                            <input name="sub_name" type="text" required class="form-control text-capitalize"
                                placeholder="Enter Name" aria-label="Username" value="{{ $value->sub_name }}" readonly
                                disabled>
                            {{-- End Sub Name Customer --}}
                        </div>
                        <div class="col-lg-4 col-md-12 form-group">
                            <label for="">&nbsp;</label>
                            {{-- SUb Phone Customer --}}
                            <input name="sub_phone" type="number" required class="form-control "
                                placeholder="Enter Phone" aria-label="Server" value="{{ $value->sub_phone }}" readonly
                                disabled>
                            {{-- End Sub Phone Customer --}}
                        </div>
                        <div class="col-lg-4 col-md-12 form-group">
                            <label for="">&nbsp;</label>
                            {{-- SUb email Customer --}}
                            <input name="sub_email" type="email" class="form-control " placeholder="Email is Optional"
                                aria-label="Server" value="{{ $value->email }}" readonly disabled>

                            {{-- End Sub email Customer --}}
                        </div>
                        {{-- End Customer --}}
                        {{-- Product --}}
                        <div class="col-lg-6 col-md-12 form-group">
                            <label>Accu type </label>
                            <input type="text" class="form-control text-uppercase" placeholder="Product Code"
                                readonly
                                value="{{ $value->material }}/{{ $value->type_material }}/{{ $value->productSales->nama_barang }}">
                        </div>
                        <div class="col-lg-6 col-md-12 form-group">
                            <label>Loaned Battery</label>
                            <input type="text" class="form-control text-uppercase" placeholder="Product Code"
                                readonly
                                value="{{ $value->loanBy->sub_materials->nama_sub_material }}/{{ $value->loanBy->sub_types->type_name }}/{{ $value->loanBy->nama_barang }}">
                        </div>
                        {{-- End Product --}}

                        {{-- Information Car --}}
                        <div class="col-12 col-md-4  form-group">
                            <label>Plat Number</label>
                            <input type="text" class="form-control text-uppercase" placeholder="Serial Number"
                                readonly value="{{ $value->plate_number }}">
                        </div>
                        <div class="col-12 col-md-4  form-group">
                            <label>Car Brands</label>
                            <input type="text" class="form-control text-capitalize" placeholder="Serial Number"
                                readonly value="{{ $value->carBrandBy->car_brand }}">
                        </div>
                        <div class="col-12 col-md-4  form-group">
                            <label>Car Type</label>
                            <input type="text" class="form-control text-capitalize" placeholder="Serial Number"
                                readonly value="{{ $value->carTypeBy->car_type }}">
                        </div>
                    </div>
                    {{-- End Information Car --}}

                </div>

            </div>


            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="col-lg-12 col-md-12 form-group">
                        <h5 class="text-center"><span class="bg-primary text-white form-control"><strong>ACCU
                                    COMPLAINT FORM</strong>
                            </span> </h5>
                    </div>
                    <div class="ribbon ribbon-clip ribbon-warning">Final Checking</div>
                    <div class="col-md-12">
                        <div class="form-group row font-weight-bold">
                            <div class="col-lg-6 col-md-12 form-group">
                                <label>Voltage</label>
                                <input type="text" required placeholder="Enter Voltage"
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
                                <input type="text" placeholder="Enter CCA" required
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
                                <input type="text" required placeholder="Enter Starting"
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
                                <input type="text" required placeholder="Enter Charging"
                                    class="form-control {{ $errors->first('f_charging') ? ' is-invalid' : '' }}"
                                    name="f_charging">
                                @error('f_charging')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-12 form-group" data-checkbox-group data-v-min-select="1"
                                data-v-required>
                                @foreach ($value->accuClaimDetailsBy as $key => $row)
                                    <label class="d-block" for="chk-ani2">
                                        <input class="checkbox_animated" type="checkbox" checked name="diagnosa[]"
                                            value="{{ $row->diagnosa }}">
                                        {{ $row->diagnosa }}
                                    </label>
                                @endforeach
                                <label class="d-block" for="chk-ani2">
                                    <input class="checkbox_animated" id="cekDiagnosa" type="checkbox" check
                                        value="">
                                    Other Diagnosa
                                </label>
                            </div>

                            <div hidden class="col-lg-12 col-md-12 form-group" id="otherDiagnosa">
                                <input type="text" class="form-control reqdiag" placeholder="Enter other diagnosa"
                                    name="other_diagnosa" required>
                            </div>
                            <div class="col-12 col-md-12 form-group">
                                <label>Result</label>
                                <select name="result" class="form-select uoms" id="result" required>
                                    <option value="" selected>-Choose Result-</option>
                                    <option value="CP01 - Good Condition">CP01 - Good Condition</option>
                                    <option value="CP02 - Waranty Rejected">CP02 - Waranty Rejected</option>
                                    <option value="CP03 - Waranty Accepted">CP03 - Waranty Accepted</option>
                                    <option value="CP04 - Good Will">CP04 - Good Will</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-12 form-group" id="warrantyTo">
                                <label>Warranty To</label>
                                <select name="to" id="warrantyAccepted" class="form-select uoms" required>
                                    <option value="" selected>-Choose Supplier-</option>
                                    @foreach ($suppliers as $row)
                                        <option value="{{ $row->id_warehouse }}">{{ $row->nama_supplier }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-12 col-md-12 form-group" id="warehouseTo">
                                <label>Warehouse To</label>
                                <select name="to_warehouse" id="goodWill" class="form-select uoms" required>
                                    <option value="" selected>-Choose Warehouse-</option>
                                    @foreach ($warehouse as $row)
                                        <option value="{{ $row->id }}">{{ $row->warehouses }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div id="file_received" class="col-lg-12 col-md-12 form-group">
                                <label>Evidence of Delivery</label>
                                <input type="file" class="form-control" name="file" id="inputreference"
                                    required>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4 offset-md-4 text-center">
                                    <label id="previewLabel" hidden>Preview Image</label>
                                    <img src="#" id="previewimg" class="img-fluid shadow-lg"
                                        style="width:350px;" hidden />
                                </div>
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
