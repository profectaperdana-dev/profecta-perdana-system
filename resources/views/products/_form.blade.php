<div class="row">
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-md-4">
                <label>Product Code</label>
                <input type="text" class="form-control {{ $errors->first('kode_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Code" name="kode_barang">
                @error('kode_barang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label>Product Name</label>
                <input type="text" class="form-control {{ $errors->first('nama_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Name" name="nama_barang">
                @error('nama_barang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label>Serial Number</label>
                <input type="text" class="form-control {{ $errors->first('no_seri') ? ' is-invalid' : '' }}"
                    placeholder="Serial Number" name="no_seri">
                @error('no_seri')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-12 text-capitalize">
        <div class="form-group row font-weight-bold">
            <div class="col-md-4">

                <label>
                    Unit of Measurement</label>
                <select name="uom" id=""
                    class="form-control uoms {{ $errors->first('uom') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Uom-</option>
                    @foreach ($uom as $list_uom)
                        <option value="{{ $list_uom->id }}">{{ $list_uom->satuan }}</option>
                    @endforeach
                </select>
                @error('uom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label>
                    Material</label>
                <select name="material_grup" id=""
                    class="form-control uoms {{ $errors->first('material_grup') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Material-</option>
                    @foreach ($material as $list_material)
                        <option value="{{ $list_material->id }}">{{ $list_material->nama_material }}</option>
                    @endforeach
                </select>
                @error('material_grup')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label>
                    Sub Material</label>
                <select name="sub_material" id=""
                    class="form-control uoms {{ $errors->first('sub_material') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Sub Material-</option>
                    @foreach ($subMaterial as $list_subMaterial)
                        <option value="{{ $list_subMaterial->id }}">{{ $list_subMaterial->nama_sub_material }}
                        </option>
                    @endforeach
                </select>
                @error('sub_material')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-md-3">
                <label>Product Weight</label>
                <input type="number" class="form-control {{ $errors->first('berat') ? ' is-invalid' : '' }}"
                    placeholder="Product Weight" name="berat">
                @error('berat')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Purchase Price</label>
                <input type="number" class="form-control {{ $errors->first('harga_beli') ? ' is-invalid' : '' }}"
                    placeholder="Purchase Price" name="harga_beli">
                @error('harga_beli')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Retail Selling Price </label>
                <input type="number" class="form-control {{ $errors->first('harga_jual') ? ' is-invalid' : '' }}"
                    placeholder="Retail Selling Price" name="harga_jual">
                @error('harga_jual')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Non Retail Selling Price</label>
                <input type="number"
                    class="form-control {{ $errors->first('harga_jual_nonretail') ? ' is-invalid' : '' }}"
                    placeholder="Non Retail Selling Price" name="harga_jual_nonretail">
                @error('harga_jual_nonretail')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-md-3">
                <label>Qty Stock</label>
                <input type="number" class="form-control {{ $errors->first('qty') ? ' is-invalid' : '' }}"
                    placeholder="Qty Stock" name="qty">
                @error('qty')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Min Stock</label>
                <input type="number" class="form-control {{ $errors->first('minstok') ? ' is-invalid' : '' }}"
                    placeholder="Min Stock" name="minstok">
                @error('minstok')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <input type="number" class="form-control {{ $errors->first('status') ? ' is-invalid' : '' }}"
                    placeholder="Status" name="status">
                @error('status')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-3">
                <label>Product Photo</label>
                <input type="file" id="inputreference"
                    class="form-control {{ $errors->first('foto_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Photo" name="foto_barang">
                @error('foto_barang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
        <div class="form-group">
            <div class="col-md-4 offset-md-4 text-center">
                <h3 id="previewLabel" hidden>Preview Image</h3>
                <img style="width:250px;border: 5px solid rgb(0, 0, 0);" hidden src="#" id="previewimg"
                    class="img-fluid" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <a href="{{ url('/products') }}" class="btn btn-danger">Back</a>
                <button type="reset" class="btn btn-warning" data-dismiss="modal">Reset</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
