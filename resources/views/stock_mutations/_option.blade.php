<a href="#" class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailReturn{{ $mutation->id }}">
    ACTION</a>
{{-- <div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    @can('isSuperAdmin')
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#editReturn{{ $mutation->id }}">Edit</a>
    @endcan
    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-original-title="test"
        data-bs-target="#detailReturn{{ $mutation->id }}">Detail</a>
    <h5 class="dropdown-header">Prints</h5>
    <a class="dropdown-item" href="{{ url('return/' . $mutation->id . '/print') }}">Print Return</a>
</div> --}}

<div class="modal fade" id="detailReturn{{ $mutation->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Mutation
                    :
                    {{ $mutation->mutation_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" id="formReturn">
                                @foreach ($mutation->stockMutationDetailBy as $item)
                                    <div class="row">
                                        <div class="form-group col-7">
                                            <label>Product</label>
                                            <input readonly class="form-control"
                                                value="{{ $item->productBy->nama_barang . ' (' . $item->productBy->sub_materials->nama_sub_material . ', ' . $item->productBy->sub_types->type_name . ')' }}">
                                        </div>
                                        <div class="col-3 col-md-3 form-group">
                                            <label>Qty</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $item->qty }}" id="">
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-6">
                                    <label for="">Remark</label>
                                    <input class="form-control" value="{{ $mutation->remark }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @can('isSuperAdmin')
                        <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#editMutation{{ $mutation->id }}"
                            data-bs-dismiss="modal">Edit
                        </button>
                    @endcan
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editReturn{{ $mutation->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Mutation
                    :
                    {{ $mutation->mutation_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row box-select-all justify-content-end">
                    <button class="col-1 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                </div>
                <form action="{{ url('stock_mutaton/' . $mutation->id . '/update_mutation') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="so_id" value="{{ $mutation->salesOrderBy->id }}">
                                <div class="row" id="formReturn">
                                    @foreach ($mutation->returnDetailsBy as $item)
                                        <input type="hidden" class="loop" value="{{ $loop->index }}">
                                        <div class="row">
                                            <div class="form-group col-7">
                                                <label>Product</label>
                                                <select name="returnFields[{{ $loop->index }}][product_id]"
                                                    class="form-control productReturn" required>
                                                    <option value="">Choose Product</option>
                                                    <option value="{{ $item->product_id }}" selected>
                                                        {{ $item->productBy->nama_barang . ' (' . $item->productBy->sub_materials->nama_sub_material . ', ' . $item->productBy->sub_types->type_name . ')' }}
                                                    </option>
                                                </select>
                                                @error('returnFields[{{ $loop->index }}][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-3 col-md-3 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control" required
                                                    name="returnFields[{{ $loop->index }}][qty]"
                                                    value="{{ $item->qty }}" id="">

                                                @error('returnFields[{{ $loop->index }}][qty]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-2 col-md-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control remReturn text-white text-center"
                                                    style="border:none; background-color:red">-</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-6">
                                        <label for="">Return Reason</label>
                                        <select name="return_reason1" class="form-control uoms return_reason1"
                                            required>
                                            <option value="">-- Choose Return Reason -- </option>
                                            <option value="{{ $mutation->return_reason }}" selected>
                                                {{ $mutation->return_reason }}</option>
                                            <option value="Wrong Discount">Wrong Discount</option>
                                            <option value="Wrong Quantity">Wrong Quantity</option>
                                            <option value="Wrong Product Type">Wrong Product Type</option>
                                            <option value="Bad Debt">Bad Debt</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-1 return_reason2" hidden>
                                        <label for="">&nbsp;</label>
                                        <p class="form-group text-center pt-2"><strong>By:</strong></p>
                                    </div>
                                    <div class="form-group col-5 return_reason2" hidden>
                                        <label for="">&nbsp;</label>
                                        <select name="return_reason2" class="form-control uoms">
                                            <option value="">-- Choose Who's Responsible -- </option>
                                            <option value="Admin">Admin</option>
                                            <option value="Warehouse Keeper">Warehouse Keeper</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-6 other" hidden>
                                        <label for="">&nbsp;</label>
                                        <textarea name="return_reason" class="form-control" rows="3" placeholder="Write Your Reasons Here..."></textarea>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#detailReturn{{ $mutation->id }}"
                                        data-bs-dismiss="modal">Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
