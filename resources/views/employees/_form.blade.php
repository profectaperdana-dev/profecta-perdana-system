<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Personal Data</h5>
                <hr class="bg-primary">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}"
                            class="form-control
                  @error('name') is-invalid @enderror"
                            placeholder="Employee Name" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Gender</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="0"
                                @if ($employee->gender == 0) checked @endif>
                            <label class="form-check-label" for="inlineRadio1">Woman</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="1"
                                @if ($employee->gender == 1) checked @endif>
                            <label class="form-check-label" for="inlineRadio2">Man</label>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Birth Place</label>
                        <input type="text" name="birth_place"
                            value="{{ old('birth_place', $employee->birth_place) }}"
                            class="form-control
                  @error('birth_place') is-invalid @enderror"
                            placeholder="Employee Birth Place" required>
                        @error('birth_place')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Birth Date</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $employee->birth_date) }}"
                            class="form-control
                  @error('birth_date') is-invalid @enderror"
                            placeholder="Employee Birth Date" required>
                        @error('birth_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Employee Email Number" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="Employee Phone Number" required>
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Emergency Contact</label>
                        <div class="input-group">
                            <input type="text" name="emergency_phone" class="form-control"
                                value="{{ old('emergency_phone', $employee->emergency_phone) }}"
                                placeholder="Enter Phone Number" required>
                            <select name="emergency_relation"
                                class="form-control emergency @error('emergency_relation') is-invalid @enderror"
                                required>
                                <option selected value="">
                                    -- Choose The Relationship --
                                </option>
                                <option value="Brother" @if ($employee->emergency_relation == 'Brother') selected @endif>
                                    Brother
                                </option>
                                <option value="Sister" @if ($employee->emergency_relation == 'Sister') selected @endif>
                                    Sister
                                </option>
                                <option value="Mother" @if ($employee->emergency_relation == 'Mother') selected @endif>
                                    Mother
                                </option>
                                <option value="Father" @if ($employee->emergency_relation == 'Father') selected @endif>
                                    Father
                                </option>
                                <option value="Uncle" @if ($employee->emergency_relation == 'Uncle') selected @endif>
                                    Uncle
                                </option>
                                <option value="Aunt" @if ($employee->emergency_relation == 'Aunt') selected @endif>
                                    Aunt
                                </option>
                                <option value="Husband" @if ($employee->emergency_relation == 'Husband') selected @endif>
                                    Husband
                                </option>
                                <option value="Wife" @if ($employee->emergency_relation == 'Wife') selected @endif>
                                    Wife
                                </option>
                            </select>
                        </div>
                        {{-- <label>Emergency Phone Number</label>
                        <input type="text" name="emergency_phone"
                            value="{{ old('emergency_phone', $employee->emergency_phone) }}"
                            class="form-control @error('emergency_phone') is-invalid @enderror"
                            placeholder="Employee Emergency Phone Number" required>
                        @error('emergency_phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror --}}
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Province</label>
                        <select name="province" class="form-control province @error('province') is-invalid @enderror"
                            required>
                            @if ($employee->province != null)
                                <option selected value="{{ $employee->province }}">{{ $employee->province }}
                                </option>
                            @endif
                        </select>
                        @error('province')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>District</label>
                        <select name="district" class="form-control city @error('district') is-invalid @enderror"
                            required>
                            @if ($employee->district != null)
                                <option selected value="{{ $employee->district }}">{{ $employee->district }}
                                </option>
                            @endif
                        </select>
                        @error('district')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Sub-district</label>
                        <select name="sub_district"
                            class="form-control district @error('sub_district') is-invalid @enderror" required>
                            @if ($employee->sub_district != null)
                                <option selected value="{{ $employee->sub_district }}">{{ $employee->sub_district }}
                                </option>
                            @endif
                        </select>
                        @error('sub_district')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address', $employee->address) }}"
                            class="form-control form-control-lg @error('address') is-invalid @enderror"
                            placeholder="Employee Address" required>
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h5>Education Data</h5>
                <h6 class="card-subtitle my-2 text-muted">Last Two Educations</h6>
                <hr class="bg-primary">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Study Degree</label>
                        <select name="last_edu_first"
                            class="form-control uoms @error('last_edu_first') is-invalid @enderror" required>
                            <option selected value="">
                                -- Choose Study Degree --
                            </option>
                            <option value="SHS" @if ($employee->last_edu_first == 'SHS') selected @endif>
                                Senior High School
                            </option>
                            <option value="Associate" @if ($employee->last_edu_first == 'Associate') selected @endif>
                                Associate Degree
                            </option>
                            <option value="Bachelor" @if ($employee->last_edu_first == 'Bachelor') selected @endif>
                                Bachelor Degree
                            </option>
                            <option value="Master" @if ($employee->last_edu_first == 'Master') selected @endif>
                                Master Degree
                            </option>
                            <option value="Doctoral" @if ($employee->last_edu_first == 'Doctoral') selected @endif>
                                Doctoral Degree
                            </option>
                        </select>
                        @error('last_edu_first')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Institution Name</label>
                        <input type="text" name="school_name_first"
                            value="{{ old('school_name_first', $employee->school_name_first) }}"
                            class="form-control form-control-lg @error('school_name') is-invalid @enderror"
                            placeholder="Enter Institution Name" required>
                        @error('school_name_first')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Study Period</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" placeholder="From" name="from_first"
                                value="{{ old('from_first', $employee->from_first) }}" required>
                            <span class="input-group-text">To</span>
                            <input type="date" class="form-control" placeholder="To" name="to_first"
                                value="{{ old('to_first', $employee->to_first) }}" required>
                        </div>
                        @error('from_first')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @error('to_first')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Study Degree</label>
                        <select name="last_edu_sec"
                            class="form-control uoms @error('last_edu_sec') is-invalid @enderror" required>
                            <option selected value="">
                                -- Choose Study Degree --
                            </option>
                            <option value="SHS" @if ($employee->last_edu_sec == 'SHS') selected @endif>
                                Senior High School
                            </option>
                            <option value="Associate" @if ($employee->last_edu_sec == 'Associate') selected @endif>
                                Associate Degree
                            </option>
                            <option value="Bachelor" @if ($employee->last_edu_sec == 'Bachelor') selected @endif>
                                Bachelor Degree
                            </option>
                            <option value="Master" @if ($employee->last_edu_sec == 'Master') selected @endif>
                                Master Degree
                            </option>
                            <option value="Doctoral" @if ($employee->last_edu_sec == 'Doctoral') selected @endif>
                                Doctoral Degree
                            </option>
                        </select>
                        @error('last_edu_sec')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Institution Name</label>
                        <input type="text" name="school_name_sec"
                            value="{{ old('school_name_sec', $employee->school_name_sec) }}"
                            class="form-control form-control-lg @error('school_name_sec') is-invalid @enderror"
                            placeholder="Enter Institution Name" required>
                        @error('school_name_sec')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Study Period</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" placeholder="From" name="from_sec"
                                value="{{ old('from_sec', $employee->from_sec) }}" required>
                            <span class="input-group-text">To</span>
                            <input type="date" class="form-control" placeholder="To" name="to_sec"
                                value="{{ old('to_sec', $employee->to_sec) }}" required>
                        </div>
                        @error('from_sec')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        @error('to_sec')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h5>Family Data</h5>
                <hr class="bg-primary">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Mother's Name</label>
                        <input type="text" name="mom_name" value="{{ old('mom_name', $employee->mom_name) }}"
                            class="form-control @error('mom_name') is-invalid @enderror"
                            placeholder="Enter Mother's Name" required>
                        @error('mom_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Mother's Phone Number</label>
                        <input type="text" name="mom_phone" value="{{ old('mom_phone', $employee->mom_phone) }}"
                            class="form-control @error('mom_phone') is-invalid @enderror"
                            placeholder="Enter Mother's Phone Number" required>
                        @error('mom_phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Father's Name</label>
                        <input type="text" name="father_name"
                            value="{{ old('father_name', $employee->father_name) }}"
                            class="form-control @error('father_name') is-invalid @enderror"
                            placeholder="Enter Father's Name" required>
                        @error('father_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Father's Phone Number</label>
                        <input type="text" name="father_phone"
                            value="{{ old('father_phone', $employee->father_phone) }}"
                            class="form-control @error('father_phone') is-invalid @enderror"
                            placeholder="Enter Father's Phone Number" required>
                        @error('father_phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h5>Work Data</h5>
                <hr class="bg-primary">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Start Work Date</label>
                        <input type="date" name="work_date" value="{{ old('work_date', $employee->work_date) }}"
                            class="form-control @error('work_date') is-invalid @enderror"
                            placeholder="Enter Work Date" required>
                        @error('work_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Salary</label>
                        <input type="text" name="salary" value="{{ old('salary', $employee->salary) }}"
                            class="form-control @error('salary') is-invalid @enderror"
                            placeholder="Enter Employee Salary" required>
                        @error('salary')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Job</label>
                        <select name="job" class="form-control uoms @error('job') is-invalid @enderror" required>
                            <option selected value="">
                                -- Choose Employee Job --
                            </option>
                            <option value="Administrator" @if ($employee->job == 'Administrator') selected @endif>
                                Administrator
                            </option>
                            <option value="Warehousekeeper" @if ($employee->job == 'Warehousekeeper') selected @endif>
                                Warehousekeeper
                            </option>
                            <option value="Sales" @if ($employee->job == 'Sales') selected @endif>
                                Sales
                            </option>
                            <option value="Finance" @if ($employee->job == 'Finance') selected @endif>
                                Finance
                            </option>
                            <option value="Programmer" @if ($employee->job == 'Programmer') selected @endif>
                                Programmer
                            </option>
                            <option value="Technician" @if ($employee->job == 'Technician') selected @endif>
                                Technician
                            </option>
                            <option value="Human Resource and General Affair"
                                @if ($employee->job == 'Human Resource and General Affair') selected @endif>
                                Human Resource and General Affair
                            </option>
                            <option value="Office Boy" @if ($employee->job == 'Office Boy') selected @endif>
                                Office Boy
                            </option>
                        </select>
                        @error('job')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Employee Photo</label>
                        <input type="file" name="photo" id="inputreference"
                            class="form-control @error('photo') is-invalid @enderror">
                        @error('photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4 offset-md-4 text-center">
                        <label id="previewLabel" hidden>Preview Image</label>
                        <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;"
                            hidden />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <a class="btn btn-danger" href="{{ url('employee/') }}"> <i class="ti ti-arrow-left"> </i> Back
            </a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</div>
