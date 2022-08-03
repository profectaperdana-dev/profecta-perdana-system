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
            <div class="col-md-3">
                <label>Product Weight</label>
                <input type="number" class="form-control" placeholder="Product Weight">
            </div>
            <div class="col-md-3">
                <label>Purchase Price</label>
                <input type="number" class="form-control" placeholder="Purchase Price">
            </div>
            <div class="col-md-3">
                <label>Retail Selling Price </label>
                <input type="number" class="form-control" placeholder="Retail Selling Price">
            </div>
            <div class="col-md-3">
                <label>Non Retail Selling Price</label>
                <input type="number" class="form-control" placeholder="Non Retail Selling Price">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group row font-weight-bold">
            <div class="col-md-3">
                <label>Qty Stock</label>
                <input type="number" class="form-control" placeholder="Qty Stock">
            </div>
            <div class="col-md-3">
                <label>Min Stock</label>
                <input type="number" class="form-control" placeholder="Min Stock">
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <input type="number" class="form-control" placeholder="Status">
            </div>
            <div class="col-md-3">
                <label>Product Photo</label>
                <input type="file" class="form-control" placeholder="Product Photo">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <a href="{{ URL::previous() }}" class="btn btn-danger">Back</a>
                <button type="reset" class="btn btn-warning" data-dismiss="modal">Reset</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
