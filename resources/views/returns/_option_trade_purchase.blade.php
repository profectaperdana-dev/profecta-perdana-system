<a href="#" class="fw-bold text-nowrap text-success" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailReturn{{ $return->id }}">
    {{ $return->return_number }}</a>

<div class="currentModal">
    <div class="modal" id="deleteReturn{{ $return->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Delete {{ $return->return_number }}</h6>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                Are you sure you want to delete this return ?
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal" data-original-title="test"
                    data-bs-target="#detailReturn{{ $return->id }}" data-bs-dismiss="modal">Back
                </a>
                <a type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                <a type="button" href="{{ url('return_trade_in/delete/return_trade_in/' . $return->id) }}"
                    class="btn btn-primary btn-delete">Yes, Delete</a>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="detailReturn{{ $return->id }}" data-bs-keyboard="false" data-bs-backdrop="static"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    <div>
                        Return Number

                        {{ $return->return_number }}
                    </div>
                    <div>
                        From Trade-In Purchase

                        {{ $return->TradeInBy->trade_in_number }}
                    </div>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="">Return Date</label>
                            <input type="text" value="{{ date('d F Y', strtotime($return->return_date)) }}" readonly
                                class="form-control">
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="">Return Reason</label>
                            <input class="form-control" value="{{ $return->return_reason }}" readonly>
                        </div>
                    </div>
                    @foreach ($return->returnDetailsBy as $item)
                        <div class="row rounded py-2 mx-auto mb-3" style="background-color: #f0e194">
                            <div class="form-group col-12 col-lg-8">
                                <label>Product</label>
                                <input readonly class="form-control"
                                    value="{{ $item->productBy->name_product_trade_in }}">
                            </div>
                            <div class="col-12 col-lg-4 form-group">
                                <label>Qty</label>
                                <input type="" class="form-control" readonly value="{{ $item->qty }}"
                                    id="">
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" target="popup"
                        onclick="window.open('{{ url('return_trade_in/' . $return->id . '/print') }}','name','width=600,height=400')">Print</button>
                <div class="btn-group dropup">
                    

                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        @canany(['level1', 'level2'])
                            <li>
                                <a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                    data-original-title="test" data-bs-target="#editReturn{{ $return->id }}"
                                    data-bs-dismiss="modal">Edit
                                    Return
                                </a>
                            </li>
                        @endcanany
                        @canany(['level1'])
                            <li>
                                <a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                    data-original-title="test" data-bs-target="#deleteReturn{{ $return->id }}"
                                    data-bs-dismiss="modal">Delete Return</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endcanany


                    </ul>
                    

                </div>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="editReturn{{ $return->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Edit Return

                    {{ $return->return_number }}</h6>

            </div>
            <div class="modal-body">
                <div class="mb-3 row box-select-all justify-content-end">
                    <button class="col-2  me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                </div>
                <form action="{{ url('return_trade_in/' . $return->id . '/update_return') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="">Return Reason</label>
                                <select multiple name="return_reason1" class="form-control multi return_reason1"
                                    required>
                                    <option value="{{ $return->return_reason }}" selected>
                                        {{ $return->return_reason }}</option>
                                    <option value="Wrong Quantity">Wrong Quantity</option>
                                    <option value="Wrong Product Type">Wrong Product Type</option>
                                    <option value="Reference to Invoice: {{$return->TradeInBy->retail_order_number}}">Reference to Invoice: {{$return->TradeInBy->retail_order_number}}</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group col-2 col-lg-1 return_reason2" hidden>
                                <label for="">&nbsp;</label>
                                <p class="form-group text-center pt-2"><strong>By:</strong></p>
                            </div>
                            <div class="form-group col-10 col-lg-5 return_reason2" hidden>
                                <label for="">&nbsp;</label>
                                <select multiple name="return_reason2" class="form-control multi">
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
                                <input type="hidden" id="po_id" value="{{ $return->TradeInBy->id }}">
                                <div class="form-group" id="formReturn">
                                    @foreach ($return->returnDetailsBy as $item)
                                        <div class="row rounded mx-auto  pt-2 mb-3" style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="form-group col-12 col-lg-7">
                                                <label>Product</label>
                                                <select multiple name="returnFields[{{ $loop->index }}][product_id]"
                                                    class="form-control productReturn" required>
                                                    <option value="{{ $item->productBy->id }}" selected>
                                                        {{ $item->productBy->name_product_trade_in }}
                                                    </option>
                                                </select>
                                                @error('returnFields[{{ $loop->index }}][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-8 col-lg-3 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control" required
                                                    name="returnFields[{{ $loop->index }}][qty]"
                                                    value="{{ $item->qty }}" id="">

                                                @error('returnFields[{{ $loop->index }}][qty]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-4 col-lg-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control remReturn text-white text-center"
                                                    style="border:none; background-color:red">-</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                                            data-original-title="test" data-bs-dismiss="modal"
                                            data-bs-target="#detailReturn{{ $return->id }}">Back
                                        </button>
                                        <button type="submit" class="btn btn-primary">Save</button>

                                        <button class="btn btn-danger" type="button"
                                            data-bs-dismiss="modal">Close</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

</div>    
