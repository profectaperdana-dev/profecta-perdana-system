<a href="#" class="fw-bold text-success" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailReturn{{ $return->id }}">
    {{ $return->return_number }}</a>
<div class="currentModal">
    <div class="modal" id="detailReturn{{ $return->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    <div class="text-danger">
                        Return Number

                        {{ $return->return_number }}
                    </div>
                    <div class="text-success">
                        From Purchase

                        {{ $return->retailBy->order_number }}
                    </div>
                    {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="form-group col-12 col-lg-6">
                                    <label for=""> Customer </label>
                                    <input type="text"
                                        value=" @if (is_numeric($return->retailBy->cust_name)) {{ $return->retailBy->customerBy->name_cust }}@else{{ $return->retailBy->cust_name }} @endif"
                                        readonly class="form-control">
                                </div>
                                <div class="form-group col-12 col-lg-6">
                                    <label for="">Return Date</label>
                                    <input type="text" value="{{ date('d F Y', strtotime($return->return_date)) }}"
                                        readonly class="form-control">
                                </div>
                                <div class="form-group col-12 col-lg-12">
                                    <label for="">Return Reason</label>
                                    <input class="form-control" value="{{ $return->return_reason }}" readonly>
                                </div>
                            </div>

                            <div class="" id="formReturn">
                                @foreach ($return->returnDetailsBy as $item)
                                    <div class="row rounded pt-2 mb-3 mx-auto" style="background-color: #f0e194">
                                        <div class="form-group col-12 col-lg-7">
                                            <label>Product</label>
                                            <input readonly class="form-control"
                                                value="{{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}">
                                        </div>
                                        <div class="col-12 col-lg-2 form-group">
                                            <label>Qty</label>
                                            <input type="" class="form-control" readonly
                                                value="{{ $item->qty }}" id="">
                                        </div>
                                        @php
                                            $diskon = 0;
                                            $diskon_rp = 0;
                                            $price = 0;
                                            $getdiskon = $return->retailBy->directSalesDetailBy;
                                            foreach ($getdiskon as $dis) {
                                                if ($dis->product_id == $item->product_id) {
                                                    $diskon = $dis->discount / 100;
                                                    $diskon_rp = $dis->discount_rp;
                                                    $price = $dis->price;
                                                }
                                            }
                                            
                                            
                                            $hargaDiskon = $price * $diskon;
                                            $hargaAfterDiskon = $price - $hargaDiskon - $diskon_rp;
                                            $sub_total = $hargaAfterDiskon * $item->qty;
                                            
                                        @endphp
                                        <div class="col-6 col-lg-3 form-group">
                                            <label>Amount (Rp)</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ number_format($sub_total) }}" id="">
                                        </div>

                                        <div class="mb-3">
                                            <ul class="list-group">
                                                @if ($item->productBy->materials->nama_material == 'Tyre')
                                                    <li class="list-group-item fw-bold">
                                                        @foreach ($item->returnDirectCodeBy as $code)
                                                            @if ($loop->iteration == $item->returnDirectCodeBy->count())
                                                                @if ($loop->iteration == 1)
                                                                    DOT:
                                                                    {{ '[ ' . $code->dotBy->dot . ' ]' }}
                                                                @else
                                                                    {{ '[ ' . $code->dotBy->dot . ' ]' }}
                                                                @endif
                                                            @else
                                                                DOT:
                                                                {{ '[ ' . $code->dotBy->dot . ' ]' . ', ' }}
                                                            @endif
                                                        @endforeach
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                         <button class="btn btn-info" target="popup"
                                onclick="window.open('{{ url('return_retail/' . $return->id . '/print') }}','name','width=600,height=400')">Print</button>

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
</div>
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
                <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                    data-original-title="test" data-bs-target="#detailReturn{{ $return->id }}"
                    data-bs-dismiss="modal">Back
                </a>
                <a type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                <a type="button" href="{{ url('return_retail/delete/return_direct/' . $return->id) }}"
                    class="btn btn-primary btn-delete">Yes, delete</a>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="editReturn{{ $return->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form action="{{ url('return_retail/' . $return->id . '/update_return_retail') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Return
                        :
                        {{ $return->return_number }}</h5>
                    {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <div class="mb-3 row box-select-all justify-content-end">
                        <button class="col-2 me-3 btn btn-sm btn-primary" id="addReturn">+</button>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-12 col-lg-6">
                                <label for="">Return Reason</label>
                                <select multiple name="return_reason1"
                                    class="form-control multi-select return_reason1" required>
                                    <option value="{{ $return->return_reason }}" selected>
                                        {{ $return->return_reason }}</option>
                                    <option value="Wrong Discount">Wrong Discount</option>
                                    <option value="Wrong Quantity">Wrong Quantity</option>
                                    <option value="Wrong Product Type">Wrong Product Type</option>
                                    <option value="Bad Debt">Bad Debt</option>
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
                    </div>

                </div>
                <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#detailReturn{{ $return->id }}"
                            data-bs-dismiss="modal">Back
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>

                    
                </div>
            </form>
        </div>
    </div>
</div>
</div>

