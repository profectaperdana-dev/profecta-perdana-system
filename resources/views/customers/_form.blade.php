<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Name</label>
                <input type="text" name="name_cust" value="{{ old('name_cust', $customer->name_cust) }}"
                    class="form-control
                    @error('name_cust') is-invalid @enderror"
                    placeholder="Customer Name" id="eventLocation" required>
                @error('name_cust')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>ID Card Number</label>
                <input type="text" name="id_card_number"
                    value="{{ old('id_card_number', $customer->id_card_number) }}"
                    class="form-control @error('id_card_number') is-invalid @enderror"
                    placeholder="Customer ID Card Number" required>
                @error('id_card_number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Office Phone Number</label>
                <input type="text" name="office_number" value="{{ old('office_number', $customer->office_number) }}"
                    class="form-control @error('office_number') is-invalid @enderror"
                    placeholder="Customer Office Phone Number" required>
                @error('office_number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>Cell Phone Number</label>
                <input type="text" name="phone_cust" value="{{ old('phone_cust', $customer->phone_cust) }}"
                    class="form-control @error('phone_cust') is-invalid @enderror" placeholder="Cell Phone Number"
                    required>
                @error('phone_cust')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label>Province</label>
                <select name="province" class="form-control province @error('province') is-invalid @enderror" required>
                    @if ($customer->province != null)
                        <option selected value="{{ $customer->province }}">{{ $customer->province }}
                        </option>
                    @endif
                </select>
                @error('province')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label>District</label>
                <select name="city" class="form-control city @error('city') is-invalid @enderror" required>
                    @if ($customer->city != null)
                        <option selected value="{{ $customer->city }}">{{ $customer->city }}
                        </option>
                    @endif
                </select>
                @error('city')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label>Sub-district</label>
                <select name="district" class="form-control district @error('district') is-invalid @enderror" required>
                    @if ($customer->district != null)
                        <option selected value="{{ $customer->district }}">{{ $customer->district }}
                        </option>
                    @endif
                </select>
                @error('district')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label>City</label>
                <input class="form-control" type="text" name="village"
                    value="{{ old('village', $customer->village) }}"
                    class="form-control @error('village') is-invalid @enderror" placeholder="Customer City" required>
                @error('village')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Address</label>
                <input type="text" name="address_cust" value="{{ old('address_cust', $customer->address_cust) }}"
                    class="form-control form-control-lg @error('address_cust') is-invalid @enderror"
                    placeholder="Customer Address" required>
                @error('address_cust')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>NPWP</label>
                <input type="text" name="npwp" value="{{ old('npwp', $customer->npwp) }}"
                    class="form-control form-control-lg @error('npwp') is-invalid @enderror" placeholder="Customer NPWP"
                    required>
                @error('npwp')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Email</label>
                <input type="text" name="email_cust" value="{{ old('email_cust', $customer->email_cust) }}"
                    class="form-control @error('email_cust') is-invalid @enderror" placeholder="Email Customer"
                    required>
                @error('email_cust')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label>Category</label>
                <select name="category_cust_id"
                    class="form-control category-cust @error('category_cust_id') is-invalid @enderror" required>
                    <option value="">Choose Category Customer</option>
                    @foreach ($customer_categories as $customer_category)
                        <option value="{{ $customer_category->id }}"
                            @if ($customer_category->id == $customer->category_cust_id) selected @elseif ($customer_category->id == old('category_cust_id')) selected @endif>
                            {{ $customer_category->category_name }}</option>
                    @endforeach
                </select>
                @error('category_cust_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label>Area</label>
                <select name="area_cust_id" class="form-control area-cust @error('area_cust_id') is-invalid @enderror"
                    required>
                    <option value="">Choose Customer Area</option>
                    @foreach ($customer_areas as $customer_area)
                        <option value="{{ $customer_area->id }}"
                            @if ($customer_area->id == $customer->area_cust_id) selected @elseif ($customer_area->id == old('area_cust_id')) selected @endif>
                            {{ $customer_area->area_name }}</option>
                    @endforeach
                </select>
                @error('area_cust_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Credit Limit</label>
                <input type="number" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}"
                    class="form-control @error('credit_limit') is-invalid @enderror"
                    placeholder="Customer Credit Limit" required>
                @error('credit_limit')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label>Label</label>
                <select name="label"
                    class="form-control uoms @error('label') invalid-feedback
                        {{ $message }} @enderror"
                    required>
                    <option value="" selected>Choose Customer Label</option>
                    <option value="Prospect" @if ($customer->label == 'Prospect') selected @endif>Prospect</option>
                    <option value="Customer" @if ($customer->label == 'Customer') selected @endif>Customer</option>
                    <option value="Bad Customer" @if ($customer->label == 'Bad Customer') selected @endif>Bad Customer
                    </option>
                </select>
                @error('label')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label>Due Date</label>
                <input type="number" name="due_date" value="{{ old('due_date', $customer->due_date) }}"
                    class="form-control @error('due_date') is-invalid @enderror" placeholder="Customer Due Date"
                    required>
                @error('due_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3" {{ request()->is('customers/create') ? 'hidden' : '' }}>
                <label>Overdue Status</label>
                <select name="isOverDue"
                    class="uoms form-control @error('isOverDue') invalid-feedback
                        {{ $message }} @enderror"
                    required>
                    <option value="" selected>Choose Customer Overdue Status</option>
                    <option value="1" @if ($customer->isOverDue == 1) selected @endif>Yes</option>
                    <option value="0" @if ($customer->isOverDue == 0) selected @endif>No</option>
                </select>
                @error('isOverDue')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3" {{ request()->is('customers/create') ? 'hidden' : '' }}>
                <label>Overplafond Status</label>
                <select name="isOverPlafoned"
                    class="uoms form-control @error('isOverPlafoned') invalid-feedback
                        {{ $message }} @enderror"
                    required>
                    <option value="" selected>Choose Customer Overplafond Status</option>
                    <option value="1" @if ($customer->isOverPlafoned == 1) selected @endif>Yes</option>
                    <option value="0" @if ($customer->isOverPlafoned == 0) selected @endif>No</option>
                </select>
                @error('isOverPlafoned')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3" {{ request()->is('customers/create') ? 'hidden' : '' }}>
                <label>Status</label>
                <select name="status"
                    class="uoms form-control @error('status') invalid-feedback
                        {{ $message }} @enderror"
                    required>
                    <option value="" selected>Choose Customer Status</option>
                    <option value="1" @if ($customer->status == 1) selected @endif>Active</option>
                    <option value="0" @if ($customer->status == 0) selected @endif>Non-active</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-6"
                {{ request()->is('customers/' . $customer->id . '/edit') ? 'hidden' : '' }}>
                <label>Customer Coordinate Location</label>
                <button type="button" class="btn btn-white form-control text-white" id="coorGenerate"
                    style="background-color: navy !important">Click this to
                    Generate</button>
                @if (!request('customers/create'))
                    <input type="text" class="form-control  @error('coordinate') is-invalid @enderror"
                        name="coordinate" id="coor" hidden>
                    @error('coordinate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                @endif
            </div>

        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label>Store Building Image</label>
                <input type="file" name="reference_image" id="inputreference"
                    class="form-control @error('reference_image') is-invalid @enderror mb-2">
                <small {{ request()->is('customers/' . $customer->id . '/edit') ? '' : 'hidden' }} class="mt-5"
                    id="modalreference"><a data-bs-toggle="modal" data-original-title="test"
                        data-bs-target="#referenceimage" href="#">{!! request()->is('customers/' . $customer->id . '/edit')
                            ? 'Uploaded File: ' . $customer->reference_image
                            : '<i class="fa fa-eye" aria-hidden="true"></i> Preview Image' !!}</a></small>
                @error('reference_image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label>ID Card Image</label>
                <input type="file" name="id_card_image" id="inputid"
                    class="form-control @error('id_card_image') is-invalid @enderror mb-2">
                <small {{ request()->is('customers/' . $customer->id . '/edit') ? '' : 'hidden' }} class="mt-5"
                    id="modalid"><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#idimage"
                        href="#">{!! request()->is('customers/' . $customer->id . '/edit')
                            ? 'Uploaded File: ' . $customer->id_card_image
                            : '<i class="fa fa-eye" aria-hidden="true"></i> Preview Image' !!}</a></small>
                @error('id_card_image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label>NPWP Image</label>
                <input type="file" name="npwp_image" id="inputnpwp"
                    class="form-control @error('npwp_image') is-invalid @enderror mb-2">
                <small {{ request()->is('customers/' . $customer->id . '/edit') ? '' : 'hidden' }} class="mt-5"
                    id="modalnpwp"><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#npwpimage"
                        href="#">{!! request()->is('customers/' . $customer->id . '/edit')
                            ? 'Uploaded File: ' . $customer->npwp_image
                            : '<i class="fa fa-eye" aria-hidden="true"></i> Preview Image' !!}</a></small>
                @error('npwp_image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label>Selfie with Owner Image</label>
                <input type="file" name="selfie_image" id="inputselfie"
                    class="form-control @error('selfie_image') is-invalid @enderror mb-2">
                <small {{ request()->is('customers/' . $customer->id . '/edit') ? '' : 'hidden' }} class="mt-5"
                    id="modalselfie"><a data-bs-toggle="modal" data-original-title="test"
                        data-bs-target="#selfieimage" href="#">{!! request()->is('customers/' . $customer->id . '/edit')
                            ? 'Uploaded File: ' . $customer->selfie_image
                            : '<i class="fa fa-eye" aria-hidden="true"></i> Preview Image' !!}</a></small>
                @error('selfie_image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        {{-- <div class="row justify-content-center">
            <div class="form-group col-md-3 text-center">
                <label id="previewLabel" hidden>Preview Image</label>
                <img src="#" id="previewimg" class="img-fluid shadow-lg" hidden />
            </div>
            <div class="form-group col-md-3 text-center">
                <label id="previewLabel" hidden>Preview Image</label>
                <img src="#" id="previewimg" class="img-fluid shadow-lg" hidden />
            </div>
            <div class="form-group col-md-3 text-center">
                <label id="previewLabel" hidden>Preview Image</label>
                <img src="#" id="previewimg" class="img-fluid shadow-lg" hidden />
            </div>
        </div> --}}
        <div class="row">
            @if (!request()->is('customers/create'))
                <div class="form-group col-md-12">
                    <label>Coordinate</label>
                    <input type="text" name="coordinate_" value="{{ old('coordinate', $customer->coordinate) }}"
                        class="form-control edit-coordinate @error('coordinate') is-invalid @enderror"
                        placeholder="Coordinate" required>
                    @error('coordinate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group col text-center">
                    <label for="">Map Preview</label>
                    <div id="peta" style="width: 100%; height: 500px;"></div>
                </div>
            @endif
        </div>
        <div class="form-group">
            <a class="btn btn-danger" href="{{ url('customers/') }}"> <i class="ti ti-arrow-left"> </i> Back
            </a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</div>

<div class="modal" id="referenceimage" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Preview: Image of Building Store
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <img src="{{ request()->is('customers/' . $customer->id . '/edit') ? url('public/images/customers/' . $customer->reference_image) : 'javascript:void(0)' }}"
                        id="preview-reference" class="img-fluid shadow-lg" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="idimage" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Preview: Image of ID Card
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <img src="{{ request()->is('customers/' . $customer->id . '/edit') ? url('public/images/customers/ktp/' . $customer->id_card_image) : 'javascript:void(0)' }}"
                        id="preview-id" class="img-fluid shadow-lg" />
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <style>
    a {
        max-width:
    }
</style> --}}
<div class="modal" id="npwpimage" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Preview: Image of NPWP
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <img src="{{ request()->is('customers/' . $customer->id . '/edit') ? url('public/images/customers/npwp/' . $customer->npwp_image) : 'javascript:void(0)' }}"
                        id="preview-npwp" class="img-fluid shadow-lg" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="selfieimage" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Preview: Image of Selfie with Owner
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <img src="{{ request()->is('customers/' . $customer->id . '/edit') ? url('public/images/customers/selfie/' . $customer->selfie_image) : 'javascript:void(0)' }}"
                        id="preview-selfie" class="img-fluid shadow-lg" />
                </div>
            </div>
        </div>
    </div>
</div>
