<div class="row">
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-md-4">
                <label>Product Code</label>
                <input type="text" class="form-control" placeholder="Product Code">
            </div>
            <div class="col-md-4">
                <label>Product Name</label>
                <input type="text" class="form-control" placeholder="Product Name">
            </div>
            <div class="col-md-4">
                <label>Serial Number</label>
                <input type="text" class="form-control" placeholder="Serial Number">
            </div>
        </div>
    </div>
    <div class="col-md-12 text-capitalize">
        <div class="form-group row font-weight-bold">
            <div class="col-md-4">
                <label>
                    Unit of Measurement</label>
                <select name="" id="" class="form-control uoms">
                    <option value="" selected>-Choose Uom-</option>
                    @foreach ($uom as $list_uom)
                        <option value="{{ $list_uom->id }}">{{ $list_uom->satuan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>
                    Material</label>
                <select name="" id="" class="form-control uoms">
                    <option value="" selected>-Choose Material-</option>
                    @foreach ($material as $list_material)
                        <option value="{{ $list_material->id }}">{{ $list_material->nama_material }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>
                    Sub Material</label>
                <select name="" id="" class="form-control uoms">
                    <option value="" selected>-Choose Sub Material-</option>
                    @foreach ($subMaterial as $list_subMaterial)
                        <option value="{{ $list_subMaterial->id }}">{{ $list_subMaterial->nama_sub_material }}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-md-4">
                <label>Product Code</label>
                <input type="text" class="form-control" placeholder="Product Code">
            </div>
            <div class="col-md-4">
                <label>Product Name</label>
                <input type="text" class="form-control" placeholder="Product Name">
            </div>
            <div class="col-md-4">
                <label>Serial Number</label>
                <input type="text" class="form-control" placeholder="Serial Number">
            </div>
        </div>
    </div>
</div>
