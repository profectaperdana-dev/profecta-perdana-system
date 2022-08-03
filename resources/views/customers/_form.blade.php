<div class="row">
    <div class="col-md-12">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Name</label>
                <input type="text" name="name_cust" class="form-control @error('name_cust') is-invalid @enderror"
                    placeholder="Customer Name">
                @error('name_cust')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>Phone Number</label>
                <input type="text" name="phone_cust" class="form-control @error('phone_cust') is-invalid @enderror"
                    placeholder="Customer Phone Number">
                @error('phone_cust')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address_cust"
                class="form-control form-control-lg @error('address_cust') is-invalid @enderror"
                placeholder="Customer Address">
            @error('address_cust')
                {{ $message }}
            @enderror
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Email</label>
                <input type="email" name="email_cust" class="form-control @error('email_cust') is-invalid @enderror"
                    placeholder="Email Customer">
                @error('email_cust')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label>Category</label>
                <select name="category_cust_id"
                    class="form-control category-cust @error('category_cust_id') is-invalid @enderror">
                    <option value="">Choose Category Customer</option>
                    @foreach ($customer_categories as $customer_category)
                        <option value="{{ $customer_category->id }}">
                            {{ $customer_category->category_name }}</option>
                    @endforeach
                </select>
                @error('category_cust_id')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label>Area</label>
                <select name="area_cust_id" class="form-control area-cust @error('area_cust_id') is-invalid @enderror">
                    <option value="">Choose Customer Area</option>
                    @foreach ($customer_areas as $customer_area)
                        <option value="{{ $customer_area->id }}">
                            {{ $customer_area->area_name }}</option>
                    @endforeach
                </select>
                @error('area_cust_id')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Coordinate</label>
                <input type="text" name="coordinate" class="form-control @error('coordinate') is-invalid @enderror"
                    placeholder="Enter Customer Coordinate">
                @error('coordinate')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label>Credit Limit</label>
                <input type="number" name="credit_limit"
                    class="form-control @error('credit_limit') is-invalid @enderror"
                    placeholder="Credit Limit Customer">
                @error('credit_limit')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Status</label>
                <select name="status" class="form-control @error('status') {{ $message }} @enderror">
                    <option value="" selected>Choose Customer Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
                @error('status')
                    {{ $message }}
                @enderror
            </div>
            <div class="form-group col-md-8">
                <label>Reference Image Customer</label>
                <input type="file" name="reference_image" id="inputreference"
                    class="form-control @error('reference_image') is-invalid @enderror">
                @error('reference_image')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <img src="#" id="previewimg" class="img-fluid" hidden />
            </div>
        </div>
        <div class="form-group">
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </div>
</div>
