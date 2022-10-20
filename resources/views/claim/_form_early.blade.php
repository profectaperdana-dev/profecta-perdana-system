<div class="row text-dark">
    <div class="col-sm-14 col-md-12 col-lg-12">
        <div class="ribbon-wrapper card">
            <div class="card-body">
                <div class="ribbon ribbon-clip ribbon-primary">Information Claim</div>
                <div class="row form-group col-md-12" style="color: black !important">
                    {{-- customer --}}
                    <div class="col-lg-12 col-md-12 form-group">
                        <label>Customer</label>
                        <select name="customer_id" id="cust" required class="form-select select2">
                            <option value="" selected>-Choose Customer-</option>
                            <option value="Other Customer">Other Customer</option>
                            @foreach ($customer as $row)
                                <option value="{{ $row->code_cust }} - {{ $row->name_cust }}">
                                    {{ $row->code_cust }} - {{ $row->name_cust }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div hidden id="other_name" class="col-lg-4 col-md-12 form-group">
                        <label for="">Sub Customer Name/Phone/Email</label>
                        {{-- Sub Name Customer --}}
                        <input name="sub_name" type="text" required class="form-control text-capitalize fw-bold"
                            placeholder="Enter Name" aria-label="Username">
                        {{-- End Sub Name Customer --}}
                    </div>
                    <div hidden id="other_phone" class="col-lg-4 col-md-12 form-group">
                        <label for="">&nbsp;</label>
                        {{-- SUb Phone Customer --}}
                        <input name="sub_phone" data-v-min-length="9" data-v-max-length="13" type="text" required
                            class="form-control fw-bold " placeholder="Enter Phone" aria-label="Server">
                        {{-- End Sub Phone Customer --}}
                    </div>
                    <div hidden id="other_email" class="col-lg-4 col-md-12 form-group">
                        <label for="">&nbsp;</label>
                        {{-- SUb email Customer --}}
                        <input name="sub_email" type="email" class="form-control fw-bold "
                            placeholder="Email is Optional" aria-label="Server">
                        <small class="text-primary">*e-mail is optional</small>
                        {{-- End Sub email Customer --}}
                    </div>
                    {{-- End Customer --}}
                    {{-- Product --}}
                    <div class="col-lg-12 col-md-12 form-group">
                        <label>Battery Type </label>
                        <select name="product_id" id="product_id" required class="form-select select2">
                            <option value="" selected>-Choose Battery-</option>
                            @foreach ($product as $row)
                                <option value="{{ $row->id }}"
                                    data-material="{{ $row->sub_materials->nama_sub_material }}"
                                    data-type_material="{{ $row->sub_types->type_name }}"
                                    data-parent_material="{{ $row->materials->nama_material }}">
                                    ({{ $row->sub_materials->nama_sub_material }}/{{ $row->sub_types->type_name }})
                                    - {{ $row->nama_barang }}
                                </option>
                            @endforeach
                            <input type="hidden" name="material" id="material">
                            <input type="hidden" name="type_material" id="type_material">
                            <input type="hidden" name="parent_material" id="parent_material">
                        </select>
                    </div>
                    {{-- End Product --}}

                    {{-- Information Car --}}
                    <div class="col-12 col-md-4  form-group">
                        <label>Plat Number</label>
                        <input type="text" placeholder="Enter Plat Number" required
                            class="form-control text-uppercase " name="plate_number">
                    </div>
                    <div class="col-12 col-md-4  form-group">
                        <label>Car Brands</label>
                        <select name="car_brand_id" id="brand" required class="form-control select2">
                            <option value="" selected>-Choose Car Brands-</option>
                            @foreach ($brand as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->car_brand }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4  form-group">
                        <label>Car Type</label>
                        <select name="car_type_id" id="carType" required class="form-select select2 ">
                            <option value="" selected>-Choose Car Brands-</option>
                        </select>
                    </div>
                    {{-- End Information Car --}}

                </div>
            </div>
        </div>
        {{-- CLAIM FORM --}}
        <div class="col-sm-14 col-md-12 col-lg-12" style="color: black !important">
            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="col-lg-12 col-md-12 form-group">
                        <h5 class="text-center"><span class="bg-primary text-white form-control"><strong>ACCU
                                    COMPLAINT FORM</strong>
                            </span> </h5>
                    </div>
                    <div class="ribbon ribbon-clip ribbon-warning">Early Checking</div>
                    <div class="col-md-12">
                        {{-- FORM CLAIM ACCU --}}
                        <div class="form-group row font-weight-bold">

                            <div class="col-lg-3 col-md-12 form-group">
                                <label>Voltage</label>
                                <input data-v-min="1" required type="text" placeholder="Enter Voltage"
                                    class="form-control" name="e_voltage">

                            </div>
                            <div class="col-lg-3 col-md-12 form-group">
                                <label>CCA</label>
                                <input data-v-min="1" required type="text" placeholder="Enter CCA"
                                    class="form-control" name="e_cca">

                            </div>

                            <div class="col-lg-3  col-md-12 form-group">
                                <label>Starting</label>
                                <input data-v-min="1" required type="text" placeholder="Enter Starting"
                                    class="form-control" name="e_starting">

                            </div>
                            <div class="col-lg-3 col-md-12 form-group">
                                <label>Charging</label>
                                <input data-v-min="1" required type="text" placeholder="Enter Charging"
                                    class="form-control" name="e_charging">

                            </div>
                            <div class="row" data-checkbox-group data-v-min-select="1" data-v-required>
                                <div class="col-lg-12 col-md-12 form-group">
                                    <label>Diagnostic :<span class="text-danger">
                                            *Please checklist diagnostic
                                            suitably</span>
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label class="d-block" for="chk-ani">
                                        <input class="checkbox_animated" id="chk-ani" type="checkbox"
                                            value="Starting problem" name="diagnosa[]">
                                        Starting problem
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">

                                    <label class="d-block" for="chk-ani3">
                                        <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                            name="diagnosa[]" value="Charging Problem">
                                        Charging Problem
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label class="d-block" for="chk-ani1">
                                        <input class="checkbox_animated" id="chk-ani1" type="checkbox"
                                            name="diagnosa[]" value="Current Leakage">
                                        Current Leakage
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
                                        <input class="checkbox_animated" id="chk-ani3" type="checkbox" Ignition
                                            problem name="diagnosa[]" value="Ignition problem">
                                        Ignition problem
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">

                                    <label class="d-block" for="chk-ani3">
                                        <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                            name="diagnosa[]" value="Negative Cable is non standard or damaged">
                                        Negative Cable is non standard or damaged
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label class="d-block" for="chk-ani3">
                                        <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                            name="diagnosa[]" value="Broken or damaged battery">
                                        Broken or damaged battery
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label class="d-block" for="chk-ani3">
                                        <input class="checkbox_animated" id="chk-ani3" type="checkbox"
                                            name="diagnosa[]" value="Product defects">
                                        Product defects </label>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label class="d-block" for="chk-ani2">
                                        <input class="checkbox_animated" id="cekDiagnosa" type="checkbox" check
                                            value="">
                                        Other Diagnosa
                                    </label>

                                </div>
                                <div hidden class="col-lg-12 col-md-12 form-group" id="otherDiagnosa">
                                    <input type="text" class="form-control reqdiag"
                                        placeholder="Enter other diagnosa" name="other_diagnosa" required>
                                </div>
                            </div>
                            {{-- loaned battery --}}
                            <div class="col-lg-12 col-md-12 form-group">
                                <label>Loaned Accu</label>
                                <select name="loan_product_id" id="" class="form-select select2" required>
                                    <option value="">-Select Loaned Accu-</option>
                                    @foreach ($stock as $row)
                                        <option value="{{ $row->productBy->id }}">
                                            ({{ $row->productBy->sub_materials->nama_sub_material }}/{{ $row->productBy->sub_types->type_name }})
                                            - {{ $row->productBy->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- end loaned battery --}}
                        </div>
                        {{-- END CLAIM ACCU --}}



                        {{-- FORM RECEIVED --}}
                        <div class="col-lg-12 col-md-12 form-group">
                            <label>Claim Evidence</label>
                            <input type="file" class="form-control" required name="file" id="inputreference">

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
                                <label class="" for="">Submitted By</label>
                                <br />
                                <div id="sig"></div>
                                <br />
                                <textarea id="signature64" name="signed" style="display: none"></textarea>
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
    </div>
    {{-- END CLAIM FORM --}}
</div>
