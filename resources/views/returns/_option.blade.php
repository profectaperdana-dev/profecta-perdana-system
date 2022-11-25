<a href="#" class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailReturn{{ $return->id }}">
    ACTION</a>
{{-- <div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    @can('isSuperAdmin')
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#editReturn{{ $return->id }}">Edit</a>
    @endcan
    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-original-title="test"
        data-bs-target="#detailReturn{{ $return->id }}">Detail</a>
    <h5 class="dropdown-header">Prints</h5>
    <a class="dropdown-item" href="{{ url('return/' . $return->id . '/print') }}">Print Return</a>
</div> --}}

<div class="modal fade" id="detailReturn{{ $return->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <div>
                        Return Number
                        :
                        {{ $return->return_number }}
                    </div>
                    <div>
                        From Invoice
                        :
                        {{ $return->salesOrderBy->order_number }}
                    </div>
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row justify-content-between">
                                <div class="form-group col-7 col-lg-5">
                                    Customer:
                                    {{ $return->salesOrderBy->customerBy->name_cust . ' | ' . $return->salesOrderBy->customerBy->code_cust }}
                                </div>
                                <div class="form-group col-7 col-lg-3">
                                    Return Date: {{ date('d-M-Y', strtotime($return->return_date)) }}
                                </div>
                            </div>
                            <div class="row" id="formReturn">
                                @foreach ($return->returnDetailsBy as $item)
                                    <div class="row">
                                        <div class="form-group col-5 col-md-7">
                                            <label>Product</label>
                                            <input readonly class="form-control"
                                                value="{{ $item->productBy->nama_barang . ' (' . $item->productBy->sub_materials->nama_sub_material . ', ' . $item->productBy->sub_types->type_name . ')' }}">
                                        </div>
                                        <div class="col-3 col-md-2 form-group">
                                            <label>Qty</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $item->qty }}" id="">
                                        </div>

                                        @php
                                            $diskon = 0;
                                            $diskon_rp = 0;
                                            $getdiskon = $return->salesOrderBy->salesOrderDetailsBy;
                                            foreach ($getdiskon as $dis) {
                                                if ($dis->products_id == $item->product_id) {
                                                    $diskon = $dis->discount / 100;
                                                    $diskon_rp = $dis->discount_rp;
                                                }
                                            }
                                            $ppn_cost = $item->productBy->harga_jual_nonretail * $ppn;
                                            $ppn_total = $item->productBy->harga_jual_nonretail + $ppn_cost;
                                            $hargaDiskon = $ppn_total * $diskon;
                                            $hargaAfterDiskon = $ppn_total - $hargaDiskon - $diskon_rp;
                                            $sub_total = $hargaAfterDiskon * $item->qty;
                                            
                                        @endphp
                                        <div class="col-4 col-md-3 form-group">
                                            <label>Amount (Rp)</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ number_format($sub_total, 0, ',', '.') }}" id="">
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <strong>Total:</strong>

                                </div>
                                <div class="form-group col-4 col-lg-2">
                                    <strong>Rp. {{ number_format($return->total, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-6">
                                    <label for="">Return Reason</label>
                                    <input class="form-control" value="{{ $return->return_reason }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-info" href="{{ url('return/' . $return->id . '/print') }}">Print</a>
                    <a class="btn btn-primary" href="{{ url('return/' . $return->id . '/send_email') }}">Send
                        Email
                    </a>
                    @can('isSuperAdmin')
                        <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#editReturn{{ $return->id }}">Edit
                        </button>
                    @endcan
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editReturn{{ $return->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Return
                    :
                    {{ $return->return_number }}</h5>
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
                                        <input type="hidden" class="loop" value="{{ $loop->index }}">
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
                                        <select name="return_reason1" class="form-control uoms return_reason1"
                                            required>
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
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                                        data-original-title="test"
                                        data-bs-target="#detailReturn{{ $return->id }}">Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
