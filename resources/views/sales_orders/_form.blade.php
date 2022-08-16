<div class="row">
    <div class="col-md-12">
        <div class="row font-weight-bold " id="formSo">
            <div class="form-group row">
                <div class="col-md-6 form-group">
                    <label>
                        Customers</label>
                    <select name="sub_type" id="" required
                        class="form-control sub_type customer-append {{ $errors->first('sub_type') ? ' is-invalid' : '' }}">
                        <option value="" selected>-Choose Customers-</option>
                        @foreach ($customer as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->code_cust }} | {{ $customer->name_cust }}
                            </option>
                        @endforeach
                    </select>
                    @error('sub_material')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-1 form-group">
                    <label for="">PPN 11%</label><br>
                    <label class="switch form-control">
                        <input type="checkbox" checked="" name="ppn"><span class="switch-state"></span>
                    </label>
                </div>
                <div class="col-md-5 form-group mr-5">
                    <label>Payment Method</label>
                    <select name="sub_type" id="" required class="form-control sub_type ">
                        <option value="" selected>-Choose Payment-</option>
                        <option value="1">Paid
                        </option>
                        <option value="2">Debt
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">

                <div class="col-md-2 form-group">
                    <label>Terms of Payment</label>
                    <input type="text" class="form-control {{ $errors->first('nama_barang') ? ' is-invalid' : '' }}"
                        placeholder="Product Name" name="nama_barang" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-5 form-group mr-5">
                    <label>Payment</label>
                    <select name="sub_type" id="" required class="form-control sub_type ">
                        <option value="" selected>-Choose Payment-</option>
                        <option value="1">CBD
                        </option>
                        <option value="2">COD
                        </option>
                    </select>
                </div>
                <div class="col-md-5 form-group mr-5">
                    <label>Payment Type</label>
                    <select name="sub_type" id="sub-type" required class="form-control sub_type ">
                        <option value="" selected>-Choose Payment-</option>
                        <option value="1">Cash
                        </option>
                        <option value="2">Transfer
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 form-group mr-5">
                    <label>Remarks</label>
                    <textarea class="form-control" name="" id="" cols="30" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group col-4">
                    <label>Product</label>
                    <select name="soFields[0][product_id]"
                        class="form-control productSo @error('soFields[0][product_id]') is-invalid @enderror" required>
                        <option value="">Choose Product</option>
                    </select>
                    @error('soFields[0][product_id]')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-3 col-md-3 form-group">
                    <label>Qty</label>
                    <input class="form-control" name="soFields[0][qty]" id="">
                </div>
                <div class="col-3 col-md-3 form-group">
                    <label>Discount</label>
                    <input class="form-control discount-append" name="soFields[0][discount]" id="" readonly>
                </div>
                <div class="col-2 col-md-1 form-group">

                    <label for="">&nbsp;</label>
                    <a id="addSo" href="javascript:void(0)" class="form-control text-white  text-center"
                        style="border:none; background-color:green">+</a>
                </div>
            </div>
        </div>

    </div>
    <div class="form-row">
        <div class="form-group col-md-4 offset-md-4 text-center">
            <label id="previewLabel" hidden>Preview Image</label>
            <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;" hidden />
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('products/') }}"> <i class="ti ti-arrow-left"> </i> Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
