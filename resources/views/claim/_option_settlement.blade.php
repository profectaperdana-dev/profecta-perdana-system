<a class="fw-bold text-nowrap modal-btn2" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->id }}">{{ $data->claim_number }}
</a>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">Settlement</h6>
            </div>
            <div class="modal-body" style="font-size: 10pt">

                <form action="{{ url('claim/settlement/' . $data->id . '/update') }}"
                    class="needs-validation settlementClaim" novalidate>
                    @csrf
                    <div class="form-group mx-auto row rounded pt-2" style="background-color: #f0e194">
                        <div class="col-lg-4 col-12 mb-3">
                            <label>Pay Date</label>
                            <input required class="datepicker-here form-control digits" data-position="bottom left"
                                type="text" data-language="en" name="payment_date" autocomplete="off">
                            <input type="hidden" name="amount" value="{{ $data->cost }}">
                        </div>
                        <div class="col-lg-5 col-12 mb-3">
                            <label>Payment Method</label>
                            <select multiple name="payment_method" id="" required
                                class="form-control payment selectMulti">
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-12 mb-3">
                            <label>&nbsp;</label>
                            <button class="form-control btnSubmit btn btn-primary btn-sm" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning hideModalEdit" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
