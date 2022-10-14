<a href="#" class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailMutation{{ $mutation->id }}">
    ACTION</a>

<div class="modal fade" id="detailMutation{{ $mutation->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
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
                    <a class="btn btn-info" href="{{ url('stock_mutation/' . $mutation->id . '/print_do') }}">Print</a>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editMutation{{ $mutation->id }}" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Mutation
                    :
                    {{ $mutation->mutation_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('stock_mutation/' . $mutation->id . '/update_mutation') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="mutation_id" value="{{ $mutation->id }}">
                                <input type="hidden" id="from_warehouse" value="{{ $mutation->from }}">
                                <input type="hidden" id="to_warehouse" value="{{ $mutation->to }}">
                                <div class="row" id="formMutation">
                                    @foreach ($mutation->stockMutationDetailBy as $item)
                                        <input type="hidden" class="loop" value="{{ $loop->index }}">
                                        <div class="row">
                                            <div class="form-group col-7">
                                                <label>Product</label>
                                                <select name="mutationFields[{{ $loop->index }}][product_id]"
                                                    class="form-control productM" required>
                                                    <option value="">Choose Product</option>
                                                    <option value="{{ $item->product_id }}" selected>
                                                        {{ $item->productBy->nama_barang . ' (' . $item->productBy->sub_materials->nama_sub_material . ', ' . $item->productBy->sub_types->type_name . ')' }}
                                                    </option>
                                                </select>
                                                @error('mutationFields[{{ $loop->index }}][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-3 col-md-3 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control" required
                                                    name="mutationFields[{{ $loop->index }}][qty]"
                                                    value="{{ $item->qty }}" id="">
                                                <small class="from-stock" hidden>Stock : 0</small>

                                                @error('mutationFields[{{ $loop->index }}][qty]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            @if ($loop->index == 0)
                                                <div class="col-2 col-md-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a id="addM" href="javascript:void(0)"
                                                        class="form-control text-white text-center"
                                                        style="border:none; background-color:green">+</a>
                                                </div>
                                            @else
                                                <div class="col-2 col-md-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a id="" href="javascript:void(0)"
                                                        class="form-control remMutation text-white text-center"
                                                        style="border:none; background-color:red">-</a>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 form-group mr-5">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remark" id="" cols="30" rows="5" required>{{ $mutation->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                                        data-original-title="test"
                                        data-bs-target="#detailMutation{{ $mutation->id }}"
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
