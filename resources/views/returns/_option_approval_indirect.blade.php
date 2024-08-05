@if ($return->salesOrderBy->salesOrderCreditsBy->count() <= 0)
    <a href="#" class="fw-bold text-success text-nowrap modal-btn2" href="#" data-bs-toggle="modal"
        data-original-title="test" data-bs-target="#editReturn{{ $return->id }}">
        {{ $return->return_number }}</a>
@else
    @php
        $cust_name = $return->salesOrderBy->customerBy->name_cust;
        $num_order = $return->salesOrderBy->order_number;
    @endphp
    <a href="{{ url('invoice/manage_payment?cancel_cust=' . $cust_name . '&cancel_order=' . $num_order) }}"
        target="_blank" class="fw-bold text-success text-nowrap">
        {{ $return->return_number }}</a>
@endif
{{-- <a href="#" class="fw-bold text-success text-nowrap modal-btn2" href="#" data-bs-toggle="modal"
    data-original-title="test" data-bs-target="#editReturn{{ $return->id }}">
    {{ $return->return_number }}</a> --}}

<div class="currentModal">

<div class="modal" id="deleteReturn{{ $return->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Reject {{ $return->return_number }}</h6>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                Are you sure you want to reject this return ?
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal" data-original-title="test"
                    data-bs-target="#editReturn{{ $return->id }}" data-bs-dismiss="modal">Back
                </a>
                <a type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                <a type="button" href="{{ url('return/delete/return_indirect/' . $return->id) }}"
                    class="btn btn-delete btn-primary">Yes, reject</a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="editReturn{{ $return->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

        <div class="modal-content">
            

                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        <div>
                            Approve Return
                            :
                            {{ $return->return_number }}
                        </div>
                        <div>
                            From Invoice
                            :
                            {{ $return->salesOrderBy->order_number }}
                        </div>

                    </h6>
                    {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <form action="{{ url('return/' . $return->id . '/approve_return') }}" method="POST"
                enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label for="">Return Reason</label>
                                <select multiple name="return_reason1" class="form-control uoms return_reason1"
                                    required>
                                    <option value="{{ $return->return_reason }}" selected>
                                        {{ $return->return_reason }}</option>
                                    <option value="Wrong Discount">Wrong Discount</option>
                                    <option value="Wrong Quantity">Wrong Quantity</option>
                                    <option value="Wrong Product Type">Wrong Product Type</option>
                                    <option value="Bad Debt">Bad Debt</option>
                                    <option value="Change Customer Data">Change Customer Data</option>
                                    <option value="Double Input">Double Input</option>
                                    <option value="Change Invoice Data">Change Invoice Data</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group col-2 col-lg-1 return_reason2" hidden>
                                <label for="">&nbsp;</label>
                                <p class="form-group text-center pt-2"><strong>By:</strong></p>
                            </div>
                            <div class="form-group col-10 col-lg-3 return_reason2" hidden>
                                <label for="">&nbsp;</label>
                                <select multiple name="return_reason2" class="form-control uoms">
                                    <option value="Admin">Admin</option>
                                    <option value="Warehouse Keeper">Warehouse Keeper</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Retail">Retail</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3 other" hidden>
                                <label for="">&nbsp;</label>
                                <textarea name="return_reason" class="form-control" rows="3" placeholder="Write Your Reasons Here..."></textarea>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="">Return Date</label>
                                <input class="datepicker-here form-control digits return-date"
                                    data-position="bottom left" type="text" data-language="en"
                                    data-value="{{ date('Y-m-d', strtotime($return->return_date)) }}"
                                    name="return_date" autocomplete="off">
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" id="so_id" value="{{ $return->salesOrderBy->id }}">
                                <div class="form-group" id="formReturn">
                                    @foreach ($return->returnDetailsBy as $item)
                                        <div class="row rounded pt-2 mb-3 mx-auto" style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-12 col-lg-7">
                                                <label>Product</label>
                                                <select multiple name="returnFields[{{ $loop->index }}][product_id]"
                                                    class="form-control productReturn" required>
                                                    <option value="{{ $item->product_id }}" selected>
                                                        {{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}
                                                    </option>
                                                </select>
                                                @error('returnFields[{{ $loop->index }}][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-9 col-lg-3 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control" required
                                                    name="returnFields[{{ $loop->index }}][qty]"
                                                    value="{{ $item->qty }}" id="">
                                                {{-- <small class="text-xs box-order-amount">Order Amount: <span
                                                        class="order-amount">{{ $item->qty }}</span></small>
                                            <small class="text-xs box-return-amount "> | Returned: <span class="return-amount">{{ $return_amount[$loop->index] }}</span></small> --}}
                                                @error('returnFields[{{ $loop->index }}][qty]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-3 col-lg-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control remReturn text-white text-center"
                                                    style="border:none; background-color:red">-</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                            </div>
                        </div>
                        <div class="mb-3 row box-select-all justify-content-end">
                            <button class="col-2 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                        </div>
                        <div class="modal-footer">
                        <div class="btn-group">
                            <button class="btn btn-info" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                data-original-title="test" data-bs-dismiss="modal"
                                data-bs-target="#deleteReturn{{ $return->id }}">Reject
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                                Approve
                            </button>
                        </div>
                    </div>
                    </div>
                    
            </form>
            
        </div>
    </div>
</div>
</div>
</div>
