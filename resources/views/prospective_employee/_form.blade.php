    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="ribbon ribbon-bookmark ribbon-primary">Personal Data</div>
                    <input type="hidden" value="{{ url()->current() }}" name="link">
                    <div class="row font-weight-bold">
                        <div class="form-group row">
                            <div class="col-md-6 form-group">
                                <label>
                                    <span class="text-danger">*</span> Full Name </label>
                                <input type="text" name="name" class="form-control text-capitalize"
                                    placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><span class="text-danger">*</span>
                                    Choose Gender</label><br>
                                <input required type="radio" class="form-check-input" name="gender" value="male">
                                Male
                                <input required type="radio" class="form-check-input" name="gender" value="female">
                                Female

                            </div>
                            <div class="col-md-6 form-group">
                                <label><span class="text-danger">*</span>
                                    Place of Birth</label>
                                <input type="text" name="place_of_birth" class="form-control"
                                    placeholder="Place of Birth" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><span class="text-danger">*</span>
                                    Date of Birth</label>
                                <input required type="date" name="date_of_birth" class="form-control"
                                    placeholder="Date of Birth">
                            </div>
                            <div class="form-group col-md-4">
                                <label><span class="text-danger">*</span> Province</label>
                                <select name="province" class="form-control province  required">

                                </select>

                            </div>
                            <div class="form-group
                                    col-md-4">
                                <label><span class="text-danger">*</span> District</label>
                                <select name="city" class="form-control city " required>

                                </select>

                            </div>
                            <div class="form-group col-md-4">
                                <label><span class="text-danger">*</span> Sub-district</label>
                                <select name="district" class="form-control district" required>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for=""><span class="text-danger">*</span> Address</label>
                                <textarea required name="address" class="form-control" id="" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label><span class="text-danger">*</span>Email</label>
                                <input type="email" name="email" class="form-control text-capitalize"
                                    placeholder="Email" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label><span class="text-danger">*</span> Phone Number</label>
                                <input type="text" name="phone_number" class="form-control text-capitalize"
                                    placeholder="Phone Number" required>

                            </div>
                            <div class="form-group col-md-6">
                                <label>Telp. Number</label>
                                <input type="text" name="house_phone_number	" class="form-control text-capitalize"
                                    placeholder="House Phone Number" required>
                            </div>
                            <div class="col-md-12
                                    form-group">
                                <label><span class="text-danger">*</span>
                                    Order of Birth</label>
                                <div class="input-group">
                                    <input type="text" name="birth_order" class="form-control"
                                        placeholder="Order of Birth" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>Order of</small></span>
                                    </div>
                                    <input type="text" name="from_order" class="form-control"
                                        placeholder="Birth Siblings" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><small>Siblings</small></span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12 form-group">
                                <label><span class="text-danger">*</span>
                                    Formal Education <small>[1]</small></label>
                                <div class="input-group">
                                    <input type="text" name="formal_education_1" class="form-control"
                                        placeholder="Name of School" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>From</small></span>
                                    </div>
                                    <input type="text" name="formal_education_from_1" class="form-control"
                                        placeholder="From Year" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><small>To</small></span>
                                    </div>
                                    <input type="text" name="formal_education_to_1" class="form-control"
                                        placeholder="To Year" required>
                                </div>

                            </div>
                            <div class="col-md-12 form-group">
                                <label><span class="text-danger">*</span>
                                    Formal Education <small>[2]</small></label>
                                <div class="input-group">
                                    <input type="text" name="formal_education_2" class="form-control"
                                        placeholder="Name of School" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>From</small></span>
                                    </div>
                                    <input type="text" name="formal_education_from_2" class="form-control"
                                        placeholder="From Year" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><small>To</small></span>
                                    </div>
                                    <input type="text" name="formal_education_to_2" class="form-control"
                                        placeholder="To Year" required>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="ribbon ribbon-bookmark ribbon-primary">Family Data</div>
                    <div class="row font-weight-bold">
                        <div class="form-group row">

                            <div class="col-md-12 form-group">
                                <label><span class="text-danger">*</span>
                                    Marital Status</label><br>
                                <input required type="radio" class="form-check-input marital" name="marital_status"
                                    value="Bachelor">
                                Bachelor
                                <input required type="radio" class="form-check-input marital" name="marital_status"
                                    value="Marry">
                                Marry
                                <input required type="radio" class="form-check-input marital" name="marital_status"
                                    value="Widower">
                                Widow/Widower

                            </div>
                            <div class="form-group row check_status" hidden>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Couple Name</label>
                                    <input type="text" name="couple_name" class="form-control text-capitalize"
                                        placeholder="Couple Name">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Couple Education</label>
                                    <input type="text" name="couple_education" class="form-control"
                                        placeholder="Couple Education">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Couple Occupation</label>
                                    <input type="text" name="couple_occupation" class="form-control"
                                        placeholder="Couple Occupation">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Number of Children</label>
                                    <input type="number" name="number_of_children" class="form-control"
                                        placeholder="Number of Children">

                                </div>
                                <div class="form-group col-md-3">
                                    <label>Child's Age <small>1st</small></label>
                                    <input type="number" name="child_1_age" class="form-control"
                                        placeholder="Child's Age">

                                </div>
                                <div class="form-group col-md-3">
                                    <label>Child's Age <small>2nd</small></label>
                                    <input type="number" name="child_2_age" class="form-control"
                                        placeholder="Child's Age">

                                </div>
                                <div class="form-group col-md-3">
                                    <label>Child's Age <small>3rd</small></label>
                                    <input type="number" name="child_3_age" class="form-control"
                                        placeholder="Child's Age">

                                </div>
                                <div class="form-group col-md-3">
                                    <label>Child's Age <small>4th</small></label>
                                    <input type="number" name="child_4_age" class="form-control"
                                        placeholder="Child's Age">

                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>
                                    Father Name</label>
                                <input type="text" name="father_name" class="form-control text-capitalize"
                                    placeholder="Father Name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>
                                    Father Occupation</label>
                                <input type="text" name="father_occupation" class="form-control text-capitalize"
                                    placeholder="Father Occupation" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Address</label>
                                <textarea required name="father_address" class="form-control" id="" cols="30" rows="5"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>
                                    Mother Name</label>
                                <input type="text" name="mother_name" class="form-control text-capitalize"
                                    placeholder="Mother Name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>
                                    Mother Occupation</label>
                                <input type="text" name="mother_occupation" class="form-control text-capitalize"
                                    placeholder="Mother Occupation" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Address</label>
                                <textarea required name="mother_address" class="form-control" id="" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Name and mobile number where you can be contacted</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>Name</small></span>
                                    </div>
                                    <input type="text" name="related_name_1" class="form-control"
                                        placeholder="Name Can Relate" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>Number Phone</small></span>
                                    </div>
                                    <input type="text" name="related_number_phone_1" class="form-control"
                                        placeholder="Number Phone Relate" required>

                                </div>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>Name</small></span>
                                    </div>
                                    <input type="text" name="related_name_2" class="form-control"
                                        placeholder="Name Can Relate" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>Number Phone</small></span>
                                    </div>
                                    <input type="text" name="related_number_phone_2" class="form-control"
                                        placeholder="Number Phone Relate" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12 col-lg-12">
            <div class="ribbon-wrapper card">
                <div class="card-body">
                    <div class="ribbon ribbon-bookmark ribbon-primary">Experience Skill</div>
                    <div class="row font-weight-bold">
                        <div class="col-6">

                            <div class="form-group row">
                                <div class="col-md-6 form-group">
                                    <label>
                                        Company Name <small>[1]</small></label>
                                    <input type="text" name="company_name_1" class="form-control text-capitalize"
                                        placeholder="Company Name">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Job Position <small>[1]</small></label>
                                    <input placeholder="Job Position" type="text" name="position_1"
                                        class="form-control text-capitalize">

                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Time Work (In Month) <small>[1]</small></label>
                                    <input type="text" name="length_of_work_1" class="form-control"
                                        placeholder="Time Work">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Last Salary <small>[1]</small></label>
                                    <input type="text" class="form-control last_salary_1"
                                        placeholder="Last Salary">
                                    <input type="hidden" name="last_salary_1">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Reason Stop Work<small>[1]</small></label>
                                    <textarea name="reason_stop_1" class="form-control" id="" cols="30" rows="5"></textarea>

                                </div>
                            </div>
                        </div>
                        <div class="col-6">

                            <div class="form-group row">
                                <div class="col-md-6 form-group">
                                    <label>
                                        Company Name <small>[2]</small></label>
                                    <input type="text" name="company_name_2" class="form-control text-capitalize"
                                        placeholder="Company Name">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Job Position <small>[2]</small></label>
                                    <input placeholder="Job Position" type="text" name="position_2"
                                        class="form-control text-capitalize">

                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Time Work (In Month) <small>[2]</small></label>
                                    <input type="text" name="length_of_work_2" class="form-control"
                                        placeholder="Time Work">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Last Salary <small>[2]</small></label>
                                    <input type="text" class="form-control last_salary_2"
                                        placeholder="Last Salary">
                                    <input type="hidden" name="last_salary_2">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Reason Stop Work<small>[2]</small></label>
                                    <textarea name="reason_stop_2" class="form-control" id="" cols="30" rows="5"></textarea>

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group col-md-12">
                                <label for="">Language Skill</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><small>1</small></span>
                                    </div>
                                    <input type="text" name="language_skill_1" class="form-control"
                                        placeholder="Language Skill">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><Small>2</Small></span>
                                    </div>
                                    <input type="text" name="language_skill_2" class="form-control"
                                        placeholder="Language Skill">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-xs"><Small>3</Small></span>
                                    </div>
                                    <input type="text" name="language_skill_3" class="form-control"
                                        placeholder="Language Skill">

                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label><span class="text-danger">*</span>
                                Computer Skill</label><br>
                            <input required type="radio" class="form-check-input" name="computer_skill"
                                value="Yes">
                            Yes
                            <input required type="radio" class="form-check-input" name="computer_skill"
                                value="No">
                            No
                        </div>
                        <div class="col-md-6 form-group">
                            <label><span class="text-danger">*</span>
                                Out-of-Town Placement
                            </label><br>
                            <input required type="radio" class="form-check-input" name="placement" value="Yes">
                            Yes
                            <input required type="radio" class="form-check-input" name="placement" value="No">
                            No
                        </div>
                        <div class="col-md-12 form-group">
                            <label><span class="text-danger">*</span>
                                Salary Expected
                            </label>
                            <input type="text" class="form-control salary_expected" placeholder="Salary Expected"
                                required>
                            <input type="hidden" name="salary_expected">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">

                <button type="reset" class="btn btn-warning">Reset</button>

                <button type="submit" class="btn btn-primary save-button">Save</button>
            </div>
        </div>
