<div class="row">
    <div class="col-sm-14 col-md-12 col-lg-12">
        <div class="shadow card">
            <div class="card-body">
                <div class="row mb-3 col-md-12" style="color: black !important">
                    <div class="col-lg-6 col-md-12 mb-3">
                        <label>Battery Type </label>
                        <select multiple name="product_id" required class="form-select selectMulti">
                            @foreach ($product as $row)
                                <option value="{{ $row->id }}">
                                    {{ $row->sub_materials->nama_sub_material }} {{ $row->sub_types->type_name }}
                                    {{ $row->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 col-12  mb-3">
                        <label>Product Code</label>
                        <input type="text" placeholder="Enter Product Code" required
                            class="form-control text-uppercase" name="product_code">
                    </div>
                    <div class="col-lg-3 col-md-12 mb-3">
                        <label>Voltage</label>
                        <input required type="text" placeholder="Enter Voltage" class="form-control"
                            name="e_voltage">
                    </div>
                    <div class="col-lg-3 col-md-12 mb-3">
                        <label>CCA</label>
                        <input required type="text" placeholder="Enter CCA" class="form-control" name="e_cca">
                    </div>
                    <div class="col-lg-3  col-md-12 mb-3">
                        <label>Starting</label>
                        <input required type="text" placeholder="Enter Starting" class="form-control"
                            name="e_starting">
                    </div>
                    <div class="col-lg-3 col-md-12 mb-3">
                        <label>Charging</label>
                        <input required type="text" placeholder="Enter Charging" class="form-control"
                            name="e_charging">
                    </div>

                    <div class="col-lg-12 col-md-12 mb-3">
                        <label>Diagnostic :<span class="text-danger" style="font-size: 9pt">
                                *Please check diagnostic
                                suitably</span>
                        </label>
                    </div>
                    <div class="row" style="font-size: 9pt" data-checkbox-group data-v-min-select="1"
                        data-v-required>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label class="d-block" for="chk-ani">
                                <input class="checkbox_animated" id="chk-ani" type="checkbox" value="Starting problem"
                                    name="diagnosa[]">
                                Starting problem
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">

                            <label class="d-block" for="chk-ani1">
                                <input class="checkbox_animated" id="chk-ani1" type="checkbox" name="diagnosa[]"
                                    value="Charging Problem">
                                Charging Problem
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label class="d-block" for="chk-ani2">
                                <input class="checkbox_animated" id="chk-ani2" type="checkbox" name="diagnosa[]"
                                    value="Current Leakage">
                                Current Leakage
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label class="d-block" for="chk-ani3">
                                <input class="checkbox_animated" id="chk-ani3" type="checkbox" name="diagnosa[]"
                                    value="Forgot to turn off the vehicle electricity">
                                Forgot to turn off the vehicle electricity
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">

                            <label class="d-block" for="chk-ani4">
                                <input class="checkbox_animated" id="chk-ani4" type="checkbox" name="diagnosa[]"
                                    value="Loose or dirty battery fastener">
                                Loose or dirty
                                battery fastener
                            </label>
                        </div>

                        <div class="col-lg-6 col-md-12 mb-3">
                            <label class="d-block" for="chk-ani5">
                                <input class="checkbox_animated" id="chk-ani5" type="checkbox" Ignition problem
                                    name="diagnosa[]" value="Ignition problem">
                                Ignition problem
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">

                            <label class="d-block" for="chk-ani6">
                                <input class="checkbox_animated" id="chk-ani6" type="checkbox" name="diagnosa[]"
                                    value="Negative Cable is non standard">
                                Negative Cable is non standard
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label class="d-block" for="chk-ani7">
                                <input class="checkbox_animated" id="chk-ani7" type="checkbox" name="diagnosa[]"
                                    value="Broken or damaged battery">
                                Broken or damaged battery
                            </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label class="d-block" for="chk-ani8">
                                <input class="checkbox_animated" id="chk-ani8" type="checkbox" name="diagnosa[]"
                                    value="Product defects">
                                Product defects </label>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label class="d-block" for="chk-ani9">
                                <input class="checkbox_animated" id="cekDiagnosa" type="checkbox" check
                                    value="">
                                Other Diagnostic
                            </label>

                        </div>
                        <div hidden class="col-lg-12 col-md-12 mb-3" id="otherDiagnosa">
                            <input type="text" class="form-control reqdiag" placeholder="Enter other diagnosa"
                                name="other_diagnosa" required>
                        </div>
                    </div>
                    <div class="animate-chk row" style="font-size: 9pt">
                        <div class="col-6 col-lg-6 mb-3">
                            <label class="d-block" for="edo-ani">
                                <input required class="radio_animated" id="edo-ani" value="Annual Leave"
                                    type="radio" name="select_necess">Lended

                            </label>
                        </div>
                        <div class="col-6 col-lg-6 mb-3">
                            <label class="d-block" for="edo-ani1">
                                <input class="radio_animated" id="edo-ani1" value="Special Leave" type="radio"
                                    name="select_necess">Not Lended
                            </label>
                        </div>
                    </div>
                    <div id="lended" hidden>
                        <div class="row">
                            @if ($user_warehouse->count() == 1)
                                <div class="col-12 col-lg-6 mb-3">
                                    <label>Warehouse</label>
                                    <select name="loan_warehouses" class="form-control warehouse" required>
                                        <option value="{{ $user_warehouse->first()->id }}" selected>{{ $user_warehouse->first()->warehouses }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-12 mb-3">
                                    <label>Lended Battery</label>
                                    <select name="loan_product_id" class="form-select batLend multiSelect" multiple
                                        required>

                                    </select>
                                </div>
                            @else
                                <div class="col-12 col-lg-6 mb-3">
                                    <label>Warehouse</label>
                                    <select multiple name="loan_warehouses" class="form-control warehouse" required>
                                        @foreach ($user_warehouse as $item)
                                            <option value="{{ $item->id }}">{{ $item->warehouses }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-12 mb-3">
                                    <label>Lended Battery</label>
                                    <select name="loan_product_id" class="form-select batLend multiSelect" multiple
                                        required>

                                    </select>
                                </div>
                            @endif
                        </div>

                    </div>
                    {{-- end loaned battery --}}
                </div>
                <div class="col-lg-12 col-md-12 mb-3">
                    <label for="">Cost</label>
                    <input type="text" required class="form-control text-capitalize fw-bold cost"
                        placeholder="Enter Cost" aria-label="Username" autocomplete="off">
                    <input type="hidden" name="cost">
                </div>
                <div class="col-lg-12 col-md-12 mb-3">
                    <label>Claim Evidence</label>
                    <input type="file" class="form-control" required name="gambar" id="inputreference">

                </div>
                <div class="form-row">
                    <div class="mb-3 col-md-4 offset-md-4 text-center">
                        <label id="previewLabel" hidden>Preview Image</label>
                        <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;"
                            hidden />
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="" for="">Submitted By</label>
                    <br />
                    <div id="sig"></div>
                    <br />
                    <textarea id="signature64" name="signed" style="display: none"></textarea>
                    <br>
                    <button id="clear" class="btn btn-warning">Clear Signature</button>
                </div>
                <div class="mb-3">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary btnSubmit">Save</button>
                </div>
            </div>
        </div>
    </div>
    {{-- CLAIM FORM --}}
</div>
