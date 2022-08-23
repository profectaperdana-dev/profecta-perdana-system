<div class="row">
  <div class="col-md-12">
    <div class="row">
      <div class="form-group col-md-4">
        <label>Name</label>
        <input type="text" name="name_cust" value="{{ old('name_cust', $customer->name_cust) }}"
          class="form-control
                    @error('name_cust') is-invalid @enderror" placeholder="Customer Name"
          id="eventLocation" required>
        @error('name_cust')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
      <div class="form-group col-md-4">
        <label>Phone Number</label>
        <input type="text" name="phone_cust" value="{{ old('phone_cust', $customer->phone_cust) }}"
          class="form-control @error('phone_cust') is-invalid @enderror" placeholder="Customer Phone Number" required>
        @error('phone_cust')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
      <div class="form-group col-md-4">
        <label>ID Card Number</label>
        <input type="text" name="id_card_number" value="{{ old('id_card_number', $customer->id_card_number) }}"
          class="form-control @error('id_card_number') is-invalid @enderror" placeholder="Customer ID Card Number"
          required>
        @error('id_card_number')
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
        <label>City</label>
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
        <label>District</label>
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
        <label>Village</label>
        <select name="village" class="form-control village @error('village') is-invalid @enderror" required>
          @if ($customer->village != null)
            <option selected value="{{ $customer->village }}">{{ $customer->village }}
            </option>
          @endif
        </select>
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
          class="form-control form-control-lg @error('npwp') is-invalid @enderror" placeholder="Customer NPWP" required>
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
          class="form-control @error('email_cust') is-invalid @enderror" placeholder="Email Customer" required>
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
      <div class="form-group col-md-3">
        <label>Credit Limit</label>
        <input type="number" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}"
          class="form-control @error('credit_limit') is-invalid @enderror" placeholder="Customer Credit Limit"
          required>
        @error('credit_limit')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
      <div class="form-group col-md-3">
        <label>Label</label>
        <select name="label"
          class="form-control @error('label') invalid-feedback
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
      <div class="form-group col-md-3">
        <label>Status</label>
        <select name="status"
          class="form-control @error('status') invalid-feedback
                        {{ $message }} @enderror"
          required>
          <option value="" selected>Choose Customer Status</option>
          <option value="1" @if ($customer->status == 1) selected @endif>Active</option>
          <option value="0" @if ($customer->status == 0) selected @endif>Nonactive</option>
        </select>
        @error('status')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
      <div class="form-group col-md-3">
        <label>Due Date</label>
        <input type="number" name="due_date" value="{{ old('due_date', $customer->due_date) }}"
          class="form-control @error('due_date') is-invalid @enderror" placeholder="Customer Due Date" required>
        @error('due_date')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-6">
        <label>Customer Reference Image</label>
        <input type="file" name="reference_image" id="inputreference"
          class="form-control @error('reference_image') is-invalid @enderror">
        @error('reference_image')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
      <div class="form-group col-md-6"
        {{ request()->is('customers/' . $customer->code_cust . '/edit') ? 'hidden' : '' }}>
        <label>Customer Coordinate Location</label>
        <button type="button" class="btn btn-secondary form-control" id="coorGenerate">Click this to
          Generate</button>
        <input type="text" class="form-control  @error('coordinate') is-invalid @enderror" name="coordinate"
          id="coor" hidden>
        @error('coordinate')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-4 offset-md-4 text-center">
        <label id="previewLabel" hidden>Preview Image</label>
        <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;" hidden />
      </div>
    </div>
    <div class="form-group">
      <a class="btn btn-danger" href="{{ url('customers/') }}"> <i class="ti ti-arrow-left"> </i> Back
      </a>
      <button type="reset" class="btn btn-warning">Reset</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </div>
</div>
