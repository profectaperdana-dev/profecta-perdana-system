<a href="#" class="fw-bold text-success modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#editReturn{{ $return->id }}">
    {{ $return->return_number }}</a>

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
                <a type="button" href="{{ url('return_retail/delete/return_direct/' . $return->id) }}"
                    class="btn btn-primary btn-delete">Yes, reject</a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="editReturn{{ $return->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
          
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        <div>
                            Receive Return
                            :
                            {{ $return->return_number }}
                        </div>
                    </h6>
                </div>
                <div class="modal-body">
                      <form action="{{ url('return_retail/' . $return->id . '/receive_return_retail') }}" method="POST"
                enctype="multipart/form-data">
                                          @csrf

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="">Return Reason</label>
                                <select multiple name="return_reason1" class="form-control multi-select return_reason1"
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
                            <div class="form-group col-10 col-lg-5 return_reason2" hidden>
                                <label for="">&nbsp;</label>
                                <select multiple name="return_reason2" class="form-control multi-select">
                                    <option value="Admin">Admin</option>
                                    <option value="Warehouse Keeper">Warehouse Keeper</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Retail">Retail</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-6 other" hidden>
                                <label for="">&nbsp;</label>
                                <textarea name="return_reason" class="form-control" rows="3" placeholder="Write Your Reasons Here..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" id="retail_id" value="{{ $return->retailBy->id }}">
                                <div class="form-group" id="formReturn">
                                    @foreach ($return->returnDetailsBy as $item)
                                        <div class="row rounded pt-2 mb-3 mx-auto" style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-12 col-lg-7">
                                                <label>Product</label>
                                                <select readonly multiple name="returnFields[{{ $loop->index }}][product_id]"
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

                                            <div class="parentDot">
                                                @if ($item->productBy->materials->nama_material == 'Tyre')
                                                    @foreach ($item->returnDirectCodeBy as $code)
                                                        <div class="row parentRow"
                                                            data-dotIndex="{{ $loop->index }}">
                                                            <label for="DOT">DOT</label>
                                                            <div class="col-4 col-lg-3 form-group mt-0">
                                                                <select multiple
                                                                    name="returnFields[{{ $loop->parent->index }}][{{ $loop->index }}][dot]"
                                                                    class="form-control dotReturn" required>
                                                                    {{-- <option value="" selected>
                                                            DOT
                                                        </option> --}}
                                                                    @php
                                                                        $options = collect($return->retailCodeBy($item->product_id))->groupBy('dot'); // group the items by their dot attribute
                                                                    @endphp
                                                                    @foreach ($options as $option)
                                                                        @if ($option->first()->dotBy != null)
                                                                            {{-- // check if the option has a dotBy attribute --}}
                                                                            <option
                                                                                value="{{ $option->first()->dot }}"
                                                                                @if ($option->first()->dot == $code->dot) selected @endif>
                                                                                {{ $option->first()->dotBy->dot }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>

                                                            </div>

                                                            <div class="col-4 col-lg-3 form-group">
                                                                <input type="text" class="form-control"
                                                                    name="returnFields[{{ $loop->parent->index }}][{{ $loop->index }}][qtyDot]"
                                                                    id="" placeholder="Qty"
                                                                    value="{{ $code->qty }}" required>
                                                            </div>
                                                            <div class="col-4 col-lg-2 form-group">
                                                                <a href="javascript:void(0)"
                                                                    class="form-control text-white text-center addDot"
                                                                    style="border:none; background-color:#276e61">+</a>
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="mb-3 row box-select-all justify-content-end">
                        <button class="col-2 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#deleteReturn{{ $return->id }}"
                            data-bs-dismiss="modal">Reject
                        </button>
                        <button type="submit" class="btn btn-primary">Receive</button>
                        <button type="button" class="btn btn-info" data-bs-dismiss="modal">Close</button>
                </div>
                 </form>
                </div>
                
           
        </div>
    </div>
</div>
</div>
