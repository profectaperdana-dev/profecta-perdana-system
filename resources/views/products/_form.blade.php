<div class="row">
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-md-4">
                <label>Product Code</label>
                <input type="text" class="form-control {{ $errors->first('kode_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Code" name="kode_barang" value="{{ old('kode_barang', $data->kode_barang) }}"
                    required>
                @error('kode_barang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label>Product Name</label>
                <input type="text" class="form-control {{ $errors->first('nama_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Name" name="nama_barang" value="{{ old('nama_barang', $data->nama_barang) }}"
                    required>
                @error('nama_barang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label>Serial Number</label>
                <input type="text" class="form-control {{ $errors->first('no_seri') ? ' is-invalid' : '' }}"
                    placeholder="Serial Number" name="no_seri" value="{{ old('no_seri', $data->no_seri) }}" required>
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
                <select name="uom" id="" required
                    class="form-control uoms {{ $errors->first('uom') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Uom-</option>
                    @foreach ($uom as $list_uom)
                        <option value="{{ $list_uom->id }}" @if ($data->id_uom == $list_uom->id) selected @endif>
                            {{ $list_uom->satuan }}</option>
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
                <select name="material_grup" id="" required
                    class="form-control uoms {{ $errors->first('material_grup') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Material-</option>
                    @foreach ($material as $list_material)
                        <option value="{{ $list_material->id }}" @if ($data->id_material == $list_material->id) selected @endif>
                            {{ $list_material->nama_material }}</option>
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
                <select name="sub_material" id="" required
                    class="form-control uoms {{ $errors->first('sub_material') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Sub Material-</option>
                    @foreach ($subMaterial as $list_subMaterial)
                        <option value="{{ $list_subMaterial->id }}" @if ($data->id_sub_material == $list_subMaterial->id) selected @endif>
                            {{ $list_subMaterial->nama_sub_material }}
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
                <input type="number" class="form-control {{ $errors->first('berat') ? ' is-invalid' : '' }}" required
                    placeholder="Product Weight" name="berat" value="{{ old('berat', $data->berat) }}">
                @error('berat')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Purchase Price</label>
                <input type="number" class="form-control {{ $errors->first('harga_beli') ? ' is-invalid' : '' }}"
                    required placeholder="Purchase Price" name="harga_beli"
                    value="{{ old('harga_beli', $data->harga_beli) }}">
                @error('harga_beli')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Retail Selling Price </label>
                <input type="number" class="form-control {{ $errors->first('harga_jual') ? ' is-invalid' : '' }}"
                    required placeholder="Retail Selling Price" name="harga_jual"
                    value="{{ old('harga_jual', $data->harga_jual) }}">
                @error('harga_jual')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Non Retail Selling Price</label>
                <input type="number"
                    class="form-control {{ $errors->first('harga_jual_nonretail') ? ' is-invalid' : '' }}" required
                    placeholder="Non Retail Selling Price" name="harga_jual_nonretail"
                    value="{{ old('harga_jual_nonretail', $data->harga_jual_nonretail) }}">
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
                <input type="number" class="form-control {{ $errors->first('qty') ? ' is-invalid' : '' }}" required
                    placeholder="Qty Stock" name="qty" value="{{ old('qty', $data->qty) }}">
                @error('qty')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Min Stock</label>
                <input type="number" class="form-control {{ $errors->first('minstok') ? ' is-invalid' : '' }}"
                    required placeholder="Min Stock" name="minstok" value="{{ old('minstok', $data->minstok) }}">
                @error('minstok')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <input type="number" class="form-control {{ $errors->first('status') ? ' is-invalid' : '' }}" required
                    placeholder="Status" name="status" value="{{ old('status', $data->status) }}">
                @error('status')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-3">
                <label>Product Photo</label>
                <input type="text" hidden value="{{ $data->foto_barang }}" name="url_lama">
                <input type="file" id="inputreference"
                    class="form-control {{ $errors->first('foto_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Photo" name="foto_barang" value="">
                @error('foto_barang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
        <div class="form-row">
            <div class="form-group col-md-4 offset-md-4 text-center">
                <label id="previewLabel" hidden>Preview Image</label>
                <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;" hidden />
            </div>
        </div>
        <div class="form-group">
            <a class="btn btn-danger" href="{{ url('customers/') }}"> <i class="ti ti-arrow-left"> </i> Back
            </a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </div>
</div>
