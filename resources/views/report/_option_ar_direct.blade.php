<div class="btn-group">
    <a href="javascript:void(0)" data-bs-toggle="modal" data-original-title="test"
        data-bs-target="#detailDirect{{ $data->id }}" class=" text-nowrap code fw-bold text-success"
        type="text">{{ $data->order_number }}</a> <span>&nbsp;</span>

</div>

<!-- Modal -->
<div class="modal" id="detailDirect{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    <div>
                        Order Number
                        {{ $data->order_number }}
                    </div>
                </h6>
                {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row justify-content-between">
                                <div class="form-group fw-bold col-7 col-lg-5">
                                    Customer:
                                    @if (is_numeric($data->cust_name))
                                        @if ($data->customerBy == null)
                                            {{ $data->cust_name }}
                                        @else
                                            {{ $data->customerBy->name_cust }}
                                        @endif
                                    @else
                                        {{ $data->cust_name }}
                                    @endif
                                </div>
                                <div class="form-group fw-bold col-7 col-lg-3">
                                    Order Date: {{ date('d F Y', strtotime($data->order_date)) }}
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-7 col-lg-5">
                                    Address:
                                    <address class="fw-bold"><i>{{ $data->address }},
                                            {{ $data->district }}</i>
                                    </address>

                                </div>
                                <div class="form-group col-7 col-lg-3">
                                    Email: {{ $data->cust_email }}
                                </div>

                                <div class="form-group col-12 col-lg-12">
                                    <label for="">Remark</label>
                                    <input class="form-control" value="{{ $data->remark }}" readonly>
                                </div>
                            </div>
                            <div class="" id="formReturn">

                                @foreach ($data->directSalesDetailBy as $item)
                                    <div class="row mx-auto py-2 rounded form-group mb-3"
                                        style="background-color: #f0e194">
                                        <div class="form-group col-12 col-lg-4">
                                            <label>Product
                                            </label>
                                            <input readonly class="form-control"
                                                value="{{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}">
                                        </div>
                                        <div class="col-6 col-lg-2 form-group">
                                            <label>Qty</label>
                                            <input type="" class="form-control" readonly
                                                value="{{ $item->qty }}" id="">
                                        </div>
                                        <div class="col-6 col-lg-1 form-group">
                                            <label>Disc (%)</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ $item->discount }}" id="">
                                        </div>
                                        <div class="col-6 col-lg-2 form-group">
                                            <label>Disc (Rp)</label>
                                            <input type="" class="form-control" readonly
                                                value="{{ $item->discount_rp }}" id="">
                                        </div>

                                        @php
                                            $retail_price = $item->price;
                                            if ($item->price == null) {
                                                foreach ($item->retailPriceBy as $value) {
                                                    if ($value->id_warehouse == $data->warehouse_id) {
                                                        $retail_price = $value->harga_jual;
                                                        $ppn_cost = (float) $retail_price * 0.11;
                                                        $retail_price = (float) $retail_price + $ppn_cost;
                                                    }
                                                }
                                            }
                                            
                                            $disc = (float) $item->discount / 100;
                                            $hargadisc = (float) $retail_price * $disc;
                                            $harga = (float) $retail_price - $hargadisc - $item->discount_rp;
                                            $total = (float) $harga * $item->qty;
                                        @endphp
                                        <div class="col-6 col-lg-3 form-group">
                                            <label>Amount (Rp)</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ number_format(round($total)) }}" id="">
                                        </div>
                                        <div>
                                            <ul class="list-group">

                                                <li class="list-group-item fw-bold">
                                                    @foreach ($item->directSalesCodeBy as $code)
                                                        @if ($loop->iteration == $item->directSalesCodeBy->count())
                                                            @if ($loop->iteration == 1)
                                                                Series Code:
                                                                {{ '[ ' . $code->product_code . ' ]' }}
                                                            @else
                                                                {{ '[ ' . $code->product_code . ' ]' }}
                                                            @endif
                                                        @else
                                                            Series Code:
                                                            {{ '[ ' . $code->product_code . ' ]' . ', ' }}
                                                        @endif
                                                    @endforeach
                                                </li>
                                                @if ($item->productBy->materials->nama_material == 'Tyre')
                                                    <li class="list-group-item fw-bold">
                                                        @foreach ($item->directSalesCodeBy as $code)
                                                            @if ($code->dotBy != null)
                                                                @if ($loop->iteration == $item->directSalesCodeBy->count())
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
                                                            @endif
                                                        @endforeach
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <strong>Total (Excl. PPN):</strong>

                                </div>
                                <div class="form-group col-4 col-lg-2 text-end ">
                                    <strong>{{ number_format(round($data->total_excl)) }}</strong>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <strong>PPN {{ $ppn * 100 }}%:</strong>

                                </div>
                                <div class="form-group col-4 col-lg-2 text-end ">
                                    <strong class="">{{ number_format(round($data->total_ppn)) }}</strong>
                                </div>
                            </div>
                            <hr>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <h5><strong>Total (Include PPN):</strong></h5>

                                </div>
                                <div class="form-group col-4 text-success col-lg-2 text-end ">
                                    <h5>
                                        <strong>
                                            {{ number_format(round($data->total_incl)) }}</strong>
                                    </h5>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <h5><strong>Return Total:</strong></h5>

                                </div>
                                <div class="form-group col-4 text-danger col-lg-2 text-end ">
                                    <h5>
                                        <strong>
                                            {{ number_format($data->directSalesReturnBy->sum('total')) }}</strong>
                                    </h5>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <h5><strong>Settlement Total:</strong></h5>

                                </div>
                                <div class="form-group col-4 text-danger col-lg-2 text-end ">
                                    <h5>
                                        <strong>
                                            {{ number_format($data->directSalesCreditBy->sum('amount')) }}</strong>
                                    </h5>
                                </div>
                            </div>
                            <hr>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <h5><strong>AR Total:</strong></h5>

                                </div>
                                <div class="form-group col-4 fw-bold col-lg-2 text-end ">
                                    <h5>
                                        <strong>

                                            {{ number_format($data->total_incl - $data->directSalesReturnBy->sum('total') - $data->directSalesCreditBy->sum('amount')) }}
                                        </strong>
                                    </h5>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
