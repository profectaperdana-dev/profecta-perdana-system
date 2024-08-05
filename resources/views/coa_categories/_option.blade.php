<a class="fw-bold modalItem" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->id }}">{{ $data->name }}
</a>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                            <input value="{{ $data->name }}" autocomplete="off" name="name" required type="text"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="">CoA Group</label>
                        <div class="input-group">
                            <input value="{{ $data->coa_group }}" type="text" name="coa_group" class="form-control"
                                placeholder="Group Number">
                            <span class="input-group-text">-</span>
                            <input value="{{ $data->category_number }}" type="text" name="category_number"
                                class="form-control" placeholder="Category Number">
                        </div>
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
