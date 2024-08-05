<div class="row">
    <div class="col-12 col-lg-12">
        <div class="row" id="formEmployee">
            <div class="col-6 col-lg-4">
                <label>Employee</label>
                <div class="input-group">
                    <select multiple class="form-select  select-employee" name="formEmployee[0][employee]">

                    </select>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label for="">&nbsp;</label>
                <a href="javascript:void(0)" class="form-control btn btn-sm text-white addEmployee text-center"
                    style="border:none; background-color:#276e61">+</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-4 form-group">
                <label for="vacation_type_id">Addition</label>
                <input type="number" min="1" name="vacation_get" required id="vacation_get" class="form-control"
                    placeholder="Addition">
            </div>
            <div class="col-12 col-lg-4 form-group">
                <label for="vacation_type_id">Date</label>
                <div class="input-group">
                    <input class="datepicker-here form-control digits" data-position="bottom left" type="text"
                        data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}" name="from_date"
                        autocomplete="off">

                </div>
            </div>
            <div class="col-12 col-lg-4 form-group">
                <label for="vacation_type_id">Remark</label>
                <input type="text" min="1" name="remark" required id="vacation_get" class="form-control"
                    placeholder="Remark">
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
