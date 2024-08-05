<a class="fw-bold modalItem" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->id }}">{{ $data->coa_code }}
</a>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="needs-validation editItemPromotion" novalidate enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="id" value="{{ $data->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Edit Item</h6>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label>Name</label>
                            <input autocomplete="off" name="name" required value="{{ $data->name }}" type="text"
                                class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Detail</label>
                            <textarea class="form-control" name="detail" id="" cols="30" rows="1" required>{{ $data->detail }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Description</label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="1" required>{{ $data->description }}</textarea>
                        </div>
                        @foreach ($data->saldo as $item)
                            <div class="col-4">
                                <label for="">Warehouse</label>
                                <input type="text" class="form-control" value="{{ $item->warehouse->warehouses }}"
                                    readonly>
                                <input type="hidden" class="form-control" value="{{ $item->warehouse_id }}"
                                    name="coa_saldo[{{ $loop->index }}][warehouse_id]">
                            </div>
                            <div class="col-8 mb-3">
                                <label for="">Start Balance</label>
                                <input type="text" class="form-control" value={{ $item->saldo }}
                                    name="coa_saldo[{{ $loop->index }}][start_balance]">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger delete-item" type="button"
                        data-id="{{ $data->id }}">Delete</button>
                    <button type="button" class="btn btn-warning hideModalEdit" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
