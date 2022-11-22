<div class="row">
    <div class="col-md-12">
        <div class="form-group row font-weight-bold" id="formTradeIn">
            <div class="col-md-3 form-group">
                <label>Product Name</label>
                <input type="text" class="form-control {{ $errors->first('nama_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Name" name="nama_barang" value="{{ old('nama_barang', $data->nama_barang) }}"
                    required>
            </div>
            <div class="col-md-3 form-group">
                <label>Serial Number</label>
                <input type="text" class="form-control {{ $errors->first('no_seri') ? ' is-invalid' : '' }}"
                    placeholder="Serial Number" name="no_seri" value="{{ old('no_seri', $data->no_seri) }}" required>
            </div>

            <div class="col-md-3 form-group">
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

            </div>
            <div class="col-md-3 form-group">
                <label>
                    Material</label>
                <select name="material_grup" id="material" required
                    class="form-control uoms {{ $errors->first('material_grup') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Material-</option>
                    @foreach ($material as $list_material)
                        <option value="{{ $list_material->id }}" @if ($data->id_material == $list_material->id) selected @endif>
                            {{ $list_material->nama_material }}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-3 form-group">
                <label>
                    Sub Material</label>
                <select name="sub_material" id="sub-material" required
                    class="form-control uoms {{ $errors->first('sub_material') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Sub Material-</option>
                    @if ($data->id_sub_material != null)
                        <option selected value="{{ $data->id_sub_material }}">{{ $data_sub->nama_sub_material }}
                        </option>
                    @endif
                </select>

            </div>
            <div class="col-md-3 form-group">
                <label>
                    Sub Material Type</label>
                <select name="sub_type" id="sub-type" required
                    class="form-control sub_type {{ $errors->first('sub_type') ? ' is-invalid' : '' }}">
                    <option value="" selected>-Choose Sub Type Material-</option>
                    @if ($data->id_sub_type != null)
                        <option selected value="{{ $data->id_sub_type }}">{{ $data_sub_type->type_name }}
                        </option>
                    @endif
                </select>

            </div>
            <div class="col-md-3 form-group">
                <label>Product Weight <span><small class="badge badge-danger">gram</small></span></label>
                <input type="text" class="form-control {{ $errors->first('berat') ? ' is-invalid' : '' }}" required
                    placeholder="Product Weight" id="berat"
                    value="{{ old('berat', number_format($data->berat, 0, ',', '.')) }}">
                <input type="hidden" name="berat" id="berat_" value="{{ $data->berat }}">

            </div>
            <div class="col-md-3 form-group">
                <label>Purchase Price <small class="badge badge-primary">(exclude PPN)</small> </label>
                <input type="text" class="form-control {{ $errors->first('harga_beli') ? ' is-invalid' : '' }}"
                    required placeholder="Purchase Price" id="harga_beli"
                    value="{{ old('harga_beli', number_format($data->harga_beli, 0, ',', '.')) }}">
                <input type="hidden" id="harga_beli_" name="harga_beli" value="{{ $data->harga_beli }}">

            </div>
            <div class="col-md-3 form-group">
                <label>Non Retail Price <small class="badge badge-primary">(exclude PPN)</small></label>
                <input type="text"
                    class="form-control harga_jual_nonretail {{ $errors->first('harga_jual') ? ' is-invalid' : '' }}"
                    required placeholder="Retail Selling Price"
                    value="{{ old('harga_jual', number_format($data->harga_jual, 0, ',', '.')) }}">
                <input class="harga_jual_nonretail_" type="hidden" name="harga_jual_nonretail" id=""
                    value="{{ $data->harga_jual }}">

            </div>

            <div class="col-md-3 form-group">
                <label>Min Stock</label>
                <input type="number" class="form-control {{ $errors->first('minstok') ? ' is-invalid' : '' }}"
                    required placeholder="Min Stock" name="minstok" value="{{ old('minstok', $data->minstok) }}">

            </div>
            <div class="col-md-3 form-group">
                <label>Shown At</label>
                <select name="shown" required
                    class="form-control sub_type {{ $errors->first('shown') ? ' is-invalid' : '' }}">
                    <option value="">-Choose Shown At-</option>
                    <option value="all" @if ($data->shown == 'all') selected @endif>All</option>
                    <option value="retail" @if ($data->shown == 'retail') selected @endif>Retail</option>
                    <option value="non-retail" @if ($data->shown == 'non-retail') selected @endif>Non-retail</option>
                </select>
            </div>
            @if (!request()->is('products/create'))
                <div class="col-md-3 form-group">
                    <label>
                        Status</label>
                    <select id="" required name="status"
                        class="form-control uoms {{ $errors->first('status') ? ' is-invalid' : '' }}">
                        <option value="{{ $data->status }}" selected>
                            @if ($data->status == 0)
                                Non Active
                            @else
                                Active
                            @endif
                        </option>
                        <option data="1">Active
                        </option>
                        <option value="0">Non
                            Active</option>
                    </select>

                </div>
            @endif


            <div class="col-md-3 form-group">
                <label>Product Photo</label>
                <input type="text" hidden value="{{ $data->foto_barang }}" name="url_lama">
                <input type="file" id="inputreference"
                    class="form-control {{ $errors->first('foto_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Photo" name="foto_barang" value="">

            </div>
            <h5>Price Retail</h5>
            <hr class="bg-primary">
            <div class="mx-auto py-2 form-group row bg-primary">
                <div class="form-group col-5">
                    <label>Warehouse</label>
                    <select name="tradeFields[0][id_warehouse]" class="form-control all_product_TradeIn" required>
                        <option value="">-Choose Warehouse-</option>
                    </select>

                </div>
                <div class="col-5 form-group">
                    <label>Retail Price <small class="badge badge-primary">(exclude
                            PPN)</small></label>
                    <input type="text"
                        class="form-control harga_jual {{ $errors->first('harga_jual') ? ' is-invalid' : '' }}"
                        required placeholder="Retail Selling Price"
                        value="{{ old('harga_jual', number_format($data->harga_jual, 0, ',', '.')) }}">
                    <input type="hidden" class="harga_jual_" name="tradeFields[0][harga_jual]"
                        value="{{ $data->harga_jual }}">

                </div>
                <div class="col-2 col-md-2 form-group">
                    <label for="">&nbsp;</label>
                    <a id="addTradeIn" href="javascript:void(0)" class="form-control text-white  text-center"
                        style="border:none; background-color:green">+</a>
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
</div>
