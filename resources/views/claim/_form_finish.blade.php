        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="shadow card">
                <div class="card-body">
                    <div class="form-group row font-weight-bold">
                        <div class="col-lg-3 col-md-12 form-group">
                            <label>Voltage</label>
                            <input type="text" required placeholder="Enter Voltage" class="form-control"
                                name="f_voltage">
                        </div>
                        <div class="col-lg-3 col-md-12 form-group">
                            <label>CCA</label>
                            <input type="text" placeholder="Enter CCA" required class="form-control" name="f_cca">

                        </div>
                        <div class="col-lg-3  col-md-12 form-group">
                            <label>Starting</label>
                            <input type="text" required placeholder="Enter Starting" class="form-control"
                                name="f_starting">

                        </div>
                        <div class="col-lg-3 col-md-12 form-group">
                            <label>Charging</label>
                            <input type="text" required placeholder="Enter Charging" class="form-control"
                                name="f_charging">

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
                                <input class="checkbox_animated" id="cekDiagnosa" type="checkbox" check value="">
                                Other Diagnosa
                            </label>
                        </div>

                        <div hidden class="col-lg-12 col-md-12 form-group" id="otherDiagnosa">
                            <input type="text" class="form-control reqdiag" placeholder="Enter other diagnosa"
                                name="other_diagnosa" required>
                        </div>


                        <div id="file_received" class="col-lg-12 col-md-12 form-group">
                            <label>Evidence of Delivery</label>
                            <input type="file" class="form-control" name="file" id="inputreference" required>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 offset-md-4 text-center">
                                <label id="previewLabel" hidden>Preview Image</label>
                                <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;"
                                    hidden />
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

                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button type="submit" class="btn btn-primary btnSubmit">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
