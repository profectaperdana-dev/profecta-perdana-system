<div class="row">
    <div class="col-12 col-lg-12">
        <div class="row">
            <div class="col-12 col-lg-12 form-group" {{ ($cek_ga == 0) ? '' : 'hidden' }}>
                <label for="vacation_type_id">Employee Name</label>
                <input type="text" class="form-control" readonly value="{{ Auth::user()->employeeBy->name }}">
                <input type="hidden" name="employee_id" class="form-control" readonly value="{{ Auth::user()->employee_id }}">

            </div>
            <div class="col-12 col-lg-12 form-group" {{ ($cek_ga == 0) ? 'hidden' : '' }}>
                <label for="vacation_type_id">Select Employee</label>
                                    <select name="employee_id"  class="form-control select-employee"  multiple>
                                    </select>            
                                    </div>
        </div>
        <div class="col-12 col-lg-12 form-group">
            <label for="">Necessity</label>
            <div class="row">
                <div class="card-body animate-chk">
                    <div class="row">
                        <div class="col">

                            {{-- ! annual vacation --}}
                            <label class="d-block" for="edo-ani">
                                <input required class="radio_animated" id="edo-ani" value="Annual Leave"
                                    type="radio" name="select_necess">Annual Leave
                            </label>

                            {{-- ! special vacation --}}
                            <label class="d-block" for="edo-ani1">
                                <input class="radio_animated" id="edo-ani1" value="Special Leave" type="radio"
                                    name="select_necess">Special
                                Leave
                            </label>
                            {{-- ** annual vacation date --}}
                            <div class="form-group" id="choose_date_annual" hidden>
                                <label class="">Choose Date</label>
                                <div class="col-lg-12 col-12">
                                    <div id="mdp-demo"></div>
                                    <button type="button" class="mt-2 mb-2 btn btn-sm btn-warning"
                                        id="clearDatesButton">Reset</button>

                                    <textarea required name="datepicker" readonly class="change-date form-control" id="altField" cols="30"
                                        rows="2">
                                    </textarea>


                                </div>
                            </div>
                            <div id="other_vacation" hidden>
                                <div class="row">
                                    <div class="col-12 col-lg-12 form-group">
                                        <label for="vacation_type_id">Reason Leave</label>
                                        <textarea id="other_reason" name="other_reason" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- ** special vacation date --}}
                            <div class="row" id="choose_date_special" hidden>

                                <div class="col-12 col-lg-6 form-group">
                                    @php
                                        $date = date('Y-m-d');
                                    @endphp
                                    <label for="vacation_type_id">Start Date</label>
                                    <input type="date" value="" class="form-control" 
                                        name="start_date" id="start_date" required>
                                </div>
                                <div class="col-12 col-lg-6 form-group">
                                    <label for="vacation_type_id">End Date</label>
                                    <input type="date" value="" class="form-control" name="end_date"
                                        id="end_date" required>
                                </div>
                            </div>
                            <div id="special_vacation" hidden>
                                <div class="row">
                                    <div class="col-12 col-lg-12 form-group">
                                        <select required name="reason" id="reason" class="form-control uoms">
                                            <option days="" value="" selected>--choose reason--</option>
                                            <option days="3" value="Marriage">Marriage (3 Days)</option>
                                            <option days="2" value="Child Marriage">
                                                Child Marriage (2 Days)
                                            </option>
                                            <option days="2" value="Circumcision / Baptize Children">
                                                Circumcision / Baptize Children (2 Days)
                                            </option>
                                            <option days="2" value="Child Birth / Miscarriage">
                                                Child Birth / Miscarriage (2 Days)
                                            <option days="1" value="Family Members Died">
                                                Family Members Died (1 Days)
                                            </option>
                                            <option days="90" value="Maternity Leave">
                                                Maternity Leave (90 Days)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-4 form-group">
                <label for="vacation_type_id">Ongoing Leave Entitlement
                </label>
                <input type="text" id="vacation_default" readonly class="form-control"
                    value="{{ $vacation->vacation }}">
            </div>
            <div class="col-12 col-lg-4 form-group">
                <label for="vacation_type_id">Leave to Take</label>
                <input type="text" value="0" min="1" name="vacation_get" required id="vacation_get"
                    class="form-control" readonly>
            </div>
            <div class="col-12 col-lg-4 form-group">
                <label for="vacation_type_id">Remaining Leave Entitlements
                </label>
                <input type="text" value="0" name="vacation_remain" required id="vacation_remain" readonly
                    class="form-control">
            </div>
        </div>
        <div class="form-group">
            <a class="btn btn-danger" href="{{ url()->previous() }}"> <i class="ti ti-arrow-left"> </i> Back
            </a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</div>
