<div class="row">

    <div class="col-md-12">
        <div class="form-group row text-black font-weight-bold" id="formTradeIn">

            {{-- ! informasi produk --}}
            <div class="col-md-3 form-group">
                <label> Product Name</label>
                <input type="text" class="form-control" placeholder="Product Name" name="nama_barang"
                    value="{{ old('nama_barang', $data->nama_barang) }}" required>
            </div>
            <div class="col-md-3 form-group">
                <label>Serial Number</label>
                <input type="text" class="form-control" placeholder="Serial Number" name="no_seri"
                    value="{{ old('no_seri', $data->no_seri) }}" required>
            </div>
            <div class="col-md-3 form-group">
                <label>
                    Unit of Measurement</label>
                <select name="uom" id="" required class="form-control uoms">
                    <option value="" selected>-Choose Uom-</option>
                    @foreach ($uom as $list_uom)
                        <option value="{{ $list_uom->id }}" @if ($data->id_uom == $list_uom->id) selected @endif>
                            {{ $list_uom->satuan }}</option>
                    @endforeach
                </select>
            </div>
            {{-- ! end informasi produk --}}

            {{-- ! material produk --}}
            <div class="col-md-3 form-group">
                <label>
                    Material</label>
                <select name="material_grup" id="material" required class="form-control uoms">
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
                <select name="sub_material" id="sub-material" required class="form-control uoms">
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
                <select name="sub_type" id="sub-type" required class="form-control sub_type">
                    <option value="" selected>-Choose Sub Type Material-</option>
                    @if ($data->id_sub_type != null)
                        <option selected value="{{ $data->id_sub_type }}">{{ $data_sub_type->type_name }}
                        </option>
                    @endif
                </select>
            </div>
            {{-- ! end material produk --}}

            {{-- ! produk numeric informasi --}}
            <div class="col-md-3 form-group">
                <label>Product Weight <span><small class="badge badge-danger">gram</small></span></label>
                <input required type="text" class="form-control berat" required placeholder="Product Weight"
                    value="{{ number_format($data->berat, 0, '.', ',') }}">
                <input type="hidden" class="berat_" name="berat" value="{{ $data->berat }}">
            </div>
            @can('level1')
                <div class="col-md-3 form-group">
                    <label>Purchase Price Real<small class="badge badge-primary">(ex. PPN)</small> </label>
                    <input name="" type="text" class="form-control harga_beli" required
                        placeholder="Purchase Price" value="{{ $data->decryptPrice() }}">
                    <input type="hidden" class="harga_beli_" name="harga_beli" value="{{ $data->decryptPrice() }}">
                </div>
            @else
                <input type="hidden" class="harga_beli_" name="harga_beli" value="{{ $data->decryptPrice() }}">
            @endcan

            <div class="col-md-3 form-group">
                <label>Non Retail Price <small class="badge badge-primary">(ex. PPN)</small></label>
                <input type="text" class="form-control harga_jual_nonretail" required placeholder="0"
                    value="{{ $data->harga_jual_nonretail }}">
                <input class="harga_jual_nonretail_" type="hidden" name="harga_jual_nonretail"
                    value="{{ $data->harga_jual_nonretail }}">
            </div>
            <div class="col-md-3 form-group">
                <label>Min Stock</label>
                <input type="number" class="form-control" required placeholder="Min Stock" name="minstok"
                    value="{{ old('minstok', $data->minstok) }}">
            </div>
            {{-- ! end produk numeric informasi --}}

            {{-- ! status produk --}}
            <div class="col-md-3 form-group">
                <label>Shown At</label>
                <select name="shown" required class="form-control sub_type">
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
                        <option value="1">Active
                        </option>
                        <option value="0">Non
                            Active</option>
                    </select>
                </div>
            @endif
            {{-- ! end status produk --}}

            {{-- ! foto produk --}}
            <div class="col-md-3 form-group">
                <label>Product Photo</label>
                <input type="text" hidden value="{{ $data->foto_barang }}" name="url_lama">
                <input type="file" id="inputreference"
                    class="form-control {{ $errors->first('foto_barang') ? ' is-invalid' : '' }}"
                    placeholder="Product Photo" name="foto_barang" value="">
                    <small class="mt-2 text-info">* Old photo : {{$data->foto_barang}}</small>
            </div>
<!--             <div class="col-md-3 form-group">-->
<!--                <input type="file" name="uploadfile" id="img" style="display:none;"/>-->
<!--<label for="img">Click me to upload image</label>-->
<!--            </div>-->
            <div class="form-group col-md-3 text-center">
            <label id="previewLabel" hidden>Preview Image</label>
            <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;" hidden />
        </div>
            {{-- ! end foto produk --}}
            @if (request()->is('products/create'))
                {{-- ! warehouse shown --}}
                <div class="col-md-12 form-group">
                    <label for="">Warehouse Shown</label>
                    <div class="form-group m-t-15 m-checkbox-inline mb-0">
                        @foreach ($warehouse as $item)
                            <label class="d-block" for="chk-{{ $item->id }}">
                                <input name="cek_warehouse[]" checked="checked" class="checkbox_animated" id="chk-{{ $item->id }}"
                                    value="{{ $item->id }}" type="checkbox">{{ $item->warehouses }}
                            </label>
                        @endforeach
                        @foreach ($warehouse_vendor as $item)
                            <label class="" for="chk-{{ $item->id }}" style="display: none;">
                                <input name="cek_warehouse[]" checked="checked" class="checkbox_animated" id="chk-{{ $item->id }}"
                                    value="{{ $item->id }}" type="checkbox">{{ $item->warehouses }}
                            </label>
                        @endforeach
                        {{-- @if (in_array($item->id, $data->wa)) checked @endif --}}
                    </div>
            @endif
            @if (!request()->is('products/create'))
                <div class="col-md-12 form-group">
                    <label for="">Warehouse Shown</label>
                    <div class="form-group m-t-15 m-checkbox-inline mb-0">
                        @foreach ($warehouse as $item)
                            <label class="d-block" for="chk-{{ $item->id }}">
                                <input name="cek_warehouse[]"  class="checkbox_animated" id="chk-{{ $item->id }}"
                                    value="{{ $item->id }}"
                                    @if ($stock->contains('warehouses_id', $item->id)) checked="checked" @endif
                                    type="checkbox">{{ $item->warehouses }}
                            </label>
                        @endforeach
                         @foreach ($warehouse_vendor as $item)
                            <label class="" for="chk-{{ $item->id }}" style="display: none;">
                                <input name="cek_warehouse[]"  class="checkbox_animated" id="chk-{{ $item->id }}"
                                    value="{{ $item->id }}"
                                    @if ($stock->contains('warehouses_id', $item->id)) checked="checked" @endif
                                    type="checkbox">{{ $item->warehouses }}
                            </label>
                        @endforeach
                        {{-- @if (in_array($item->id, $data->wa)) checked @endif --}}
                    </div>
            @endif
        </div>

        <h5 class="f-w-600">Retail Price</h5>
        <div>
            <hr class="bg-primary">
        </div>


        {{-- ? harga retail --}}
        @if (!request()->is('products/create'))
            {{-- ! edit data harga retail produk --}}
            <div id="formEdit">
                @foreach ($data->productCosts as $value)
                    <div class="mx-auto py-2 form-group row rounded getIndex hargaJual"
                        style="background-color: #f0e194">
                        <div class="form-group col-12 col-lg-5">
                            <label>Warehouse</label>
                            <select name="tradeFields[{{ $loop->index }}][id_warehouse]"
                                class="form-control getWarehouse" required>
                                @if ($value->id_warehouse != null)
                                    <option value="{{ $value->id_warehouse }}" selected>
                                        {{ $value->warehouseBy->warehouses }}
                                    </option>
                                @endif
                                <option value="">-Choose Warehouse-</option>
                            </select>
                        </div>
                        <div class="col-10 col-lg-5 form-group hargaJual_">
                            <label class="next">Retail Price <small class="badge badge-primary">(ex.
                                    PPN)</small></label>
                            <input type="text" class="form-control harga_jual" required
                                placeholder="Retail Selling Price" value="{{ $value->harga_jual }}">
                            <input type="hidden" class="harga_jual_"
                                name="tradeFields[{{ $loop->index }}][harga_jual]"
                                value="{{ $value->harga_jual }}">
                        </div>
                        @if ($loop->index == 0)
                            <div class="col-2 col-lg-2 form-group">
                                <label for="">&nbsp;</label>
                                <a id="addTradeIn" href="javascript:void(0)"
                                    class="form-control text-white  text-center"
                                    style="border:none; background-color:#2b786a">+</a>
                            </div>
                        @else
                            <div class="col-2 col-lg-2 form-group">
                                <label for="">&nbsp;</label>
                                <a href="javascript:void(0)"class="form-control text-white remTradeIn text-center"
                                    style="border:none; background-color:#dd5f6c">-</a>
                            </div>
                        @endif

                        {{-- ! get index terakhir  --}}
                        <input type="hidden" class="index" value="{{ $loop->index }}">
                        {{-- ! end get index terakhir --}}
                    </div>
                @endforeach
            </div>
            {{-- ! end edit data harga retail produk --}}
        @else
            {{-- ! add data harga retail produk --}}
            <div class="mx-auto py-2 rounded form-group row" style="background-color: #f0e194">
                <div class="col-12 col-lg-5 form-group">
                    <label>Warehouse</label>
                    <select name="tradeFields[0][id_warehouse]" class="form-control getWarehouse" required>
                        <option value="">-Choose Warehouse-</option>
                    </select>
                </div>
                <div class="col-10 col-lg-5 form-group">
                    <label>Retail Price <small class="badge badge-primary">(ex.
                            PPN)</small></label>
                    <input type="text" class="form-control harga_jual" required placeholder="0">
                    <input type="hidden" class="harga_jual_" name="tradeFields[0][harga_jual]"
                        value="{{ $data->harga_jual }}">
                </div>
                <div class="col-2 col-md-2 form-group">
                    <label for="">&nbsp;</label>
                    <a id="addTradeIn" href="javascript:void(0)" class="form-control text-white  text-center"
                        style="border:none; background-color:#2b786a">+</a>
                </div>
            </div>
            {{-- ! end add data harga retail produk --}}
        @endif
        {{-- ? end harga retail --}}
    </div>

    <!--{{-- ! preview image --}}-->
    <!--<div class="form-row">-->
    <!--    <div class="form-group col-md-4 offset-md-4 text-center">-->
    <!--        <label id="previewLabel" hidden>Preview Image</label>-->
    <!--        <img src="#" id="previewimg" class="img-fluid shadow-lg" style="width:350px;" hidden />-->
    <!--    </div>-->
    <!--</div>-->
    <!--{{-- ! end preview image --}}-->

    <div class="form-group">
        <a class="btn btn-danger" href="{{ url('products/') }}"> <i class="ti ti-arrow-left"> </i> Back
        </a>
        <button type="reset" class="btn btn-warning">Reset</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
</div>
