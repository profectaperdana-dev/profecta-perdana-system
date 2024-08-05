<a class="fw-bold modalItem" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->id }}">{{ $data->name }}
</a>
<!-- Modal -->
<div class="currentModal">
    <div class="modal fade" id="staticBackdrop{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form class="needs-validation editItemPromotion" novalidate enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_" class="id_item" value="{{ $data->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Edit Item</h6>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <div class="row mb-3">
                        <div class="col-12 col-lg-4">
                            <label>Name</label>
                            <input autocomplete="off" value="{{ $data->name }}" name="name" required type="text"
                                class="form-control">
                            <input type="hidden" value="{{ $data->id }}" class="id">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label>Phone</label>
                            <input autocomplete="off" name="phone_number" value="{{ $data->phone_number }}" required
                                type="text" class="form-control">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label>Email</label>
                            <input autocomplete="off" name="email" value="{{ $data->email }}" required type="text"
                                class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-lg-4">
                            <label>NPWP</label>
                            <input autocomplete="off" name="npwp" value="{{ $data->npwp }}" required type="text"
                                class="form-control">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label>Address</label>
                            <input autocomplete="off" name="address" value="{{ $data->address }}" required
                                type="text" class="form-control">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label>PIC</label>
                            <input autocomplete="off" name="pic" value="{{ $data->pic }}" required
                                type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-lg-4">
                            <label>Bank</label>
                            <input autocomplete="off" name="bank" value="{{ $data->bank }}" required
                                type="text" class="form-control">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label>Acc. Number</label>
                            <input autocomplete="off" name="no_rek" value="{{ $data->no_rek }}" required
                                type="text" class="form-control">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label>Status</label>
                            <select name="status" class="form-control selectMulti" multiple id="">
                                <option value="1" @if ($data->status == 1) selected @endif>Active</option>
                                <option value="0" @if ($data->status == 0) selected @endif>Non-active
                                </option>
                            </select>
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

</div>
