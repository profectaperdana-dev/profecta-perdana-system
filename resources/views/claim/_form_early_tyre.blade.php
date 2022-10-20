<div class="row text-dark">
    <div class="col-sm-14 col-md-12 col-lg-12">
        <div class="ribbon-wrapper card">
            <div class="card-body">
                <div class="ribbon ribbon-clip ribbon-primary">Information Claim</div>
                <div class="row form-group col-md-12">
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
                        <label>Product </label>
                        <select name="product_id" id="product_id" required class="form-select select2">
                            <option value="" selected>-Choose Product-</option>
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
                        @error('car_brand_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4  form-group">
                        <label>Car Type</label>
                        <select name="car_type_id" id="carType" required class="form-control select2">
                            <option value="" selected>-Choose Car Brands-</option>
                        </select>
                        @error('car_type_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- End Information Car --}}

                </div>
            </div>
        </div>
        {{-- CLAIM FORM --}}
        <div class="col-sm-14 col-md-12 col-lg-12">
            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="ribbon ribbon-clip ribbon-warning">Early Checking</div>
                    <div class="col-md-12">
                        {{-- FORM TYRE CLAIM --}}
                        <div class="form-group row font-weight-bold">
                            <div class="col-lg-12 col-md-12 form-group">
                                <h5 class="text-center"><span class="bg-primary text-white form-control"><strong>TYRE
                                            COMPLAINT FORM</strong>
                                    </span> </h5>
                            </div>
                            <div class="col-lg-6  col-md-12 form-group">
                                <label>Application</label>
                                <select name="application" class="form-select" id="" required>
                                    <option value="" selected>-Choose Application-</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="passenger">Passenger</option>
                                </select>

                            </div>

                            <div class="col-lg-6 col-md-12 form-group">
                                <label>DOT/DOM</label>
                                <input type="text" placeholder="Enter DOT/DOM" class="form-control" name="dot"
                                    required>

                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label>Serial Number</label>
                                <input type="number" placeholder="Enter serial number" class="form-control" required
                                    name="serial_number">

                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="">Remaining Thread Depth</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">RTD</span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter" required
                                        name="rtd1" aria-label="Username" aria-describedby="basic-addon1">
                                    <input type="number" class="form-control" placeholder="Enter" required
                                        name="rtd2" aria-label="Username" aria-describedby="basic-addon1">
                                    <input type="number" class="form-control" placeholder="Enter" required
                                        name="rtd3" aria-label="Username" aria-describedby="basic-addon1">
                                </div>

                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="">Complaint Area</label>
                                <select name="complaint_area" id="" class="form-select uoms" required>
                                    <option value="">-Choose Area-</option>
                                    <option value="Inner Tire"></option>
                                    <option value="Belt Package">Belt Package</option>
                                    <option value="Tread">Tread</option>
                                    <option value="Sidewall">Sidewall</option>
                                    <option value="Bead">Bead</option>
                                </select>

                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="">Reason for Complaint</label>
                                <input name="reason" type="text" class="form-control"
                                    placeholder="Enter Reason for Complaint" required>

                            </div>

                        </div>
                        {{-- END FORM TYRE CLAIM --}}




                        {{-- FORM RECEIVED --}}
                        <div class="col-lg-12 col-md-12 form-group">
                            <label>Claim Evidence</label>
                            <input type="file" class="form-control " required name="file" id="inputreference">

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
                            <a class="btn btn-danger" href="{{ url('claim_tyre/') }}"> <i class="ti ti-arrow-left">
                                </i>
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
