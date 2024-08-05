<a class="fw-bold modalItem" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->stock_id }}">{{ $data->name }}
</a>
<div class="currentModal">
    <!-- Modal -->
<div class="modal fade" id="staticBackdrop{{ $data->stock_id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="needs-validation editItemPromotionStock" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">{{ $data->name }} at {{ $data->warehouses }}</h6>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <div class="mb-3">
                        <label>Qty</label>
                        <input autocomplete="off" value="{{ $data->qty }}" name="qty" required type="text"
                            class="form-control">
                        <input type="hidden" value="{{ $data->stock_id }}" class="id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning hideModalEdit" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

</div>
