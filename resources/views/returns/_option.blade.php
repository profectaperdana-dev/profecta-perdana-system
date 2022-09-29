<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    ACTION</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    @can('isSuperAdmin')
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#editReturn{{ $return->id }}">Edit</a>
    @endcan
    <h5 class="dropdown-header">Prints</h5>
    <a class="dropdown-item" href="{{ url('return/' . $return->id . '/print') }}">Print Return</a>

</div>

<div class="modal fade" id="editReturn{{ $return->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Return From Sales
                    Order
                    :
                    {{ $return->salesOrderBy->order_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row box-select-all justify-content-end">
                    <button class="col-1 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                </div>
                <form action="{{ url('return/' . $return->id . '/update_return') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="so_id" value="{{ $return->salesOrderBy->id }}">
                                <div class="row" id="formReturn">
                                    @foreach ($return->returnDetailsBy as $item)
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
                                                {{-- <small class="text-xs box-order-amount">Order Amount: <span
                                                        class="order-amount">{{ $item->qty }}</span></small>
                                                <small class="text-xs box-return-amount "> | Returned: <span
                                                        class="return-amount">{{ $return_amount[$loop->index] }}</span></small> --}}
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
                                        <select name="return_reason1" class="form-control uoms return_reason1" required>
                                            <option value="">-- Choose Return Reason -- </option>
                                            <option value="{{ $return->return_reason }}" selected>
                                                {{ $return->return_reason }}</option>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on("click", ".modal-btn2", function(event) {
            let csrf = $('meta[name="csrf-token"]').attr("content");

            // $(document).on("click", ".modal-btn2", function() {

            let modal_id = $(this).attr('data-bs-target');
            let so_id = $('#so_id').val();

            //Get Customer ID
            $(modal_id).find(".uoms").select2({
                width: "100%",
            });

            $(modal_id).find('.return_reason1').change(function() {
                let return_reason1 = $(this).val();
                if (return_reason1 == "Wrong Quantity" || return_reason1 ==
                    "Wrong Product Type") {
                    $(modal_id).find('.return_reason2').attr('hidden', false);
                    $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                        .attr('required',
                            true);
                    $(modal_id).find('.other').attr('hidden', true);
                    $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                        'required', false);
                } else if (return_reason1 == "Other") {
                    $(modal_id).find('.return_reason2').attr('hidden', true);
                    $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                        .attr('required',
                            false);
                    $(modal_id).find('.other').attr('hidden', false);
                    $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                        'required', true);
                } else {
                    $(modal_id).find('.return_reason2').attr('hidden', true);
                    $(modal_id).find('.return_reason2').find('select[name="return_reason2"]')
                        .attr('required',
                            false);
                    $(modal_id).find('.other').attr('hidden', true);
                    $(modal_id).find('.other').find('textarea[name="return_reason"]').attr(
                        'required', false);
                }
            });

            $(modal_id).find(".productReturn").select2({
                dropdownParent: modal_id,
                width: "100%",
                ajax: {
                    type: "GET",
                    url: "/sales_order/selectReturn",
                    data: function(params) {
                        return {
                            _token: csrf,
                            q: params.term, // search term
                            s: so_id
                        };
                    },
                    dataType: "json",
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return [{
                                    text: item.nama_barang +
                                        " (" +
                                        item.type_name +
                                        ", " +
                                        item.nama_sub_material +
                                        ")",
                                    id: item.id,
                                }, ];
                            }),
                        };
                    },
                },
            });

            //Get Customer ID
            let x = $(modal_id)
                .find('.modal-body')
                .find('.formSo-edit')
                .children('.form-group')
                .last()
                .find('.loop')
                .val();

            $(modal_id).on("click", "#addReturn", function() {
                ++x;
                let form =
                    '<div class="form-group row">' +
                    '<div class="form-group col-7">' +
                    "<label>Product</label>" +
                    '<select name="returnFields[' +
                    x +
                    '][product_id]" class="form-control productReturn" required>' +
                    '<option value=""> Choose Product </option> ' +

                    '</select>' +
                    '</div>' +
                    '<div class="col-3 col-md-3 form-group">' +
                    '<label> Qty </label> ' +
                    '<input class="form-control" required name="returnFields[' +
                    x +
                    '][qty]">' +
                    '<small class="text-xs box-order-amount" hidden>Order Amount: <span class="order-amount">0</span></small>' +
                    '<small class="text-xs box-return-amount" hidden> | Returned: <span class="return-amount">0</span></small>' +
                    '</div>' +
                    '<div class="col-2 col-md-2 form-group">' +
                    '<label for=""> &nbsp; </label>' +
                    '<a class="form-control text-white remReturn text-center" style="border:none; background-color:red">' +
                    '- </a> ' +
                    '</div>' +
                    ' </div>';
                $(modal_id).find("#formReturn").append(form);

                $(modal_id).find(".productReturn").select2({
                    dropdownParent: modal_id,
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/sales_order/selectReturn",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                s: so_id
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_barang +
                                            " (" +
                                            item.type_name +
                                            ", " +
                                            item.nama_sub_material +
                                            ")",
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });
            });

            $(modal_id).on("click", ".remReturn", function() {
                $(this).closest(".row").remove();
            });


            $(modal_id).on('hidden.bs.modal', function() {
                $(modal_id).unbind();
            });
        });
    });
</script>
