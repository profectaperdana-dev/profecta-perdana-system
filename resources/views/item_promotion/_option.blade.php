<a class="fw-bold modalItem" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->id }}">{{ $data->name }}
</a>
<!-- Modal -->
<div class="currentModal">
    <div class="modal fade" id="staticBackdrop{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="needs-validation editItemPromotion" novalidate enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_" class="id_item" value="{{ $data->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Edit Item</h6>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <div class="mb-3">
                        <label>Item</label>
                        <input autocomplete="off" value="{{ $data->name }}" name="name" required type="text"
                            class="form-control">
                        <input type="hidden" value="{{ $data->id }}" class="id">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <input name="description" value="{{ $data->description }}" autocomplete="off" required
                            type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                            <label for="">Category</label>
                            <select name="category" id="" multiple class="form-control promosi" required>
                                <option value="{{$data->category_id}}" selected>{{ $data->categoryBy?->name }}</option>
                            </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select multiple name="status" class="form-select selectMulti">
                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Warehouse Shown</label>
                        <div class="form-group m-checkbox-inline mb-0">
                            @foreach ($warehouse as $item)
                                <div class="checkbox checkbox-dark">
                                    <input name="cek_warehouse[]" id="chk-{{ $item->id . $data->id }}" type="checkbox"
                                        value="{{ $item->id }}" @if (in_array($item->id, $data->stockBy->pluck('id_warehouse')->toArray())) checked @endif>
                                    <label id="label-text"
                                        for="chk-{{ $item->id . $data->id }}">{{ $item->warehouses }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        @if ($data->img_ref)
                            <p>Uploaded File: {{ $data->img_ref }}</p>
                        @else
                            <p>Uploaded File: -</p>
                        @endif
                        <input autocomplete="off" name="img" type="file" class="form-control"
                            placeholder="Choose the image" id="inputreference{{ $data->id }}" alt="Image"
                            value="{{ $data->img_ref }}">
                    </div>
                    <div class="mb-3">
                        <div class="form-group col-md-6 offset-md-3 text-center">
                            <label id="previewLabel{{ $data->id }}" hidden>Preview Image</label>
                            <img src="{{ url('/public/images/material_promotion/' . $data->img_ref) }}"
                                id="img_real{{ $data->id }}" class="img-fluid shadow-lg" style="width:550px;" />
                            <img src="#" id="previewimg{{ $data->id }}" class="img-fluid shadow-lg"
                                style="width:550px;" hidden />
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
