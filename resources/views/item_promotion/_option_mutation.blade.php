<a href="#" class="fw-bold text-success" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailMutation{{ $mutation->id }}">
    {{ $mutation->mutation_number }}</a>
<div class="currentModal">
    <div class="modal fade" id="detailMutation{{ $mutation->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mutation Number
                    :
                    {{ $mutation->mutation_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row justify-content-between">
                                <div class="form-group col-7 col-lg-3">
                                    <div>From:
                                        {{ $mutation->fromWarehouse->warehouses }}</div>
                                    <div> To: {{ $mutation->toWarehouse->warehouses }}
                                    </div>
                                </div>
                                <div class="form-group col-7 col-lg-3">
                                    Mutation Date: {{ date('d-M-Y', strtotime($mutation->mutation_date)) }}

                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-12 col-lg-6">
                                    <label for="">Remark</label>
                                    <input class="form-control" value="{{ $mutation->remark }}" readonly>
                                </div>
                            </div>
                            <div class="row" id="formReturn">
                                @foreach ($mutation->stockMutationDetailBy as $item)
                                    <div class="row rounded pt-2 mb-3" style="background-color: #f0e194">
                                        <div class="form-group col-12 col-lg-7">
                                            <label>Product</label>
                                            <input readonly class="form-control" value="{{ $item->itemBy->name }}">
                                        </div>
                                        <div class="col-12 col-lg-3 form-group">
                                            <label>Price by Purchase</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ number_format($item->price) }}" id="">
                                        </div>
                                        <div class="col-12 col-lg-2 form-group">
                                            <label>Qty</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $item->qty }}" id="">
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @canany(['level1', 'level2'])
                        <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#editMutation{{ $mutation->id }}"
                            data-bs-dismiss="modal">Edit
                        </a>
                    @endcanany
                    <a class="btn btn-info" target="_blank"
                        href="{{ url('material-promotion/mutation/print/' . $mutation->id) }}">Print
                        Delivery Order</a>
                    <a class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="editMutation{{ $mutation->id }}" data-bs-keyboard="false"
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
                                <input type="hidden" class="mutation_id" value="{{ $mutation->id }}">
                                <input type="hidden" class="from_warehouse" value="{{ $mutation->from }}">
                                <input type="hidden" class="to_warehouse" value="{{ $mutation->to }}">
                                <input type="hidden" class="product_type" value="{{ $mutation->product_type }}">
                                <div class="form-group row">
                                    <div class="col-12 form-group mr-5">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remark" id="" cols="30" rows="5" required>{{ $mutation->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="row" id="formMutation">
                                    @foreach ($mutation->stockMutationDetailBy as $item)
                                        <input type="hidden" class="loop" value="{{ $loop->index }}">
                                        <div class="row rounded pt-2 mb-3" style="background-color: #f0e194">
                                            @if ($mutation->product_type == 'Common')
                                                <div class="form-group col-12 col-lg-7">
                                                    <label>Product</label>
                                                    <select multiple
                                                        name="mutationFields[{{ $loop->index }}][product_id]"
                                                        class="form-control productM" required>
                                                        <option value="{{ $item->product_id }}" selected>
                                                            {{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}
                                                        </option>
                                                    </select>
                                                    @error('mutationFields[{{ $loop->index }}][product_id]')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            @else
                                                <div class="form-group col-12 col-lg-7">
                                                    <label>Product</label>
                                                    <select multiple
                                                        name="mutationFields[{{ $loop->index }}][product_id]"
                                                        class="form-control productM" required>
                                                        <option value="{{ $item->product_id }}" selected>
                                                            {{ $item->productSecondBy->name_product_trade_in }}
                                                        </option>
                                                    </select>
                                                    @error('mutationFields[{{ $loop->index }}][product_id]')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            @endif

                                            <div class="col-9 col-lg-3 form-group">
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
                                                <div class="col-3 col-lg-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a id="addM" href="javascript:void(0)"
                                                        class="form-control text-white text-center"
                                                        style="border:none; background-color:green">+</a>
                                                </div>
                                            @else
                                                <div class="col-3 col-lg-2 form-group">
                                                    <label for="">&nbsp;</label>
                                                    <a id="" href="javascript:void(0)"
                                                        class="form-control remMutation text-white text-center"
                                                        style="border:none; background-color:red">-</a>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                                            data-original-title="test"
                                            data-bs-target="#detailMutation{{ $mutation->id }}"
                                            data-bs-dismiss="modal">Detail
                                        </button>
                                        <button class="btn btn-danger" type="button"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="saveBtn">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                                aria-hidden="true"></span>
                                            <span class="sr-only">Loading...</span>
                                            Save
                                        </button>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div> --}}
</div>

