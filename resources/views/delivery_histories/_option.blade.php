{{-- ! button action --}}
<a href="#" class="text-success fw-bold text-nowrap modal-btn2" href="#" data-bs-toggle="modal"
    data-original-title="test" data-bs-target="#manageData{{ $undelivered->id }}">
    {{ $undelivered->customerBy->code_cust . ' - ' . $undelivered->customerBy->name_cust }}</a>

{{-- ! modal edit invoice --}}
<div class="modal" id="manageData{{ $undelivered->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
    data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Sales
                    Order
                    {{ $undelivered->order_number }}</h6>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="{{ url('delivery_history/update_dot_stock') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_so" value="{{ $undelivered->id }}">
                        <div class="formSo-edit">
                            @foreach ($undelivered->salesOrderDetailsBy as $detail)
                                <div style="background-color: #f7f0c9" class="form-group row py-2 rounded">
                                    <input type="hidden" class="loop" value="{{ $loop->index }}">
                                    <input type="hidden" class="id_warehouse" value="{{ $undelivered->warehouse_id }}">
                                    <input type="hidden" class="so_detail_id" value="{{ $detail->id }}">
                                    <input type="hidden" type="" class="form-control qty_product" readonly
                                        value="{{ $detail->qty }}" />
                                    <input type="hidden" class="id_product" value="{{ $detail->products_id }}">
                                    <div class="col-lg-12 col-12 mb-3">
                                        <label for="">Product - Qty</label>
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $detail->productSales->sub_materials->nama_sub_material . ' ' . $detail->productSales->sub_types->type_name . ' ' . $detail->productSales->nama_barang }}<span
                                                    class="badge badge-primary rounded-pill counter">{{ $detail->qty }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-lg-8">
                                        {{-- <label>Product</label> --}}
                                        <input type="hidden" readonly class="form-control" value="">
                                    </div>
                                    <div class="col-12 col-lg-4">
                                        {{-- <label>Qty</label> --}}
                                        <input type="hidden" class="form-control qty_product" readonly
                                            value="{{ $detail->qty }}" />

                                    </div>
                                    @if ($detail->productSales->id_material == 18)
                                        @if ($undelivered->statusDot == 0)
                                            <div class="col-lg-12 form-addRow">
                                                <div class="row parentDot mx-auto rounded mb-2 py-2"
                                                    style="background-color: #369c89">
                                                    <div class="col-lg-4 col-12 mb-2">
                                                        <label for="">DOT - Qty
                                                        </label>
                                                        <select multiple name="dotForm[{{ $loop->index }}][dot]"
                                                            required class="form-control productDot" id="">
                                                            @foreach ($datas as $dataRow)
                                                                @if ($dataRow->id_product == $detail->products_id && $dataRow->id_warehouse == $undelivered->warehouse_id)
                                                                    <option value="{{ $dataRow->id }}">
                                                                        {{ $dataRow->dot . '  - [ ' . $dataRow->qty . ' pcs ]' }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-12 mb-2">
                                                        <label for="">Qty Out
                                                        </label>
                                                        <input name="dotForm[{{ $loop->index }}][qty_dot]"
                                                            type="number" required placeholder="0"
                                                            class="form-control qty_dot" value="">
                                                        <input type="hidden"
                                                            name="dotForm[{{ $loop->index }}][so_detail_id]"
                                                            value="{{ $detail->id }}">
                                                    </div>
                                                    <div class="col-lg-4 col-12 mb-2">
                                                        <label for="">&nbsp;</label>
                                                        <button type="button"
                                                            class="btn btn-primary text-center form-control btn-sm addRow">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach ($detail->salesOrderDotBy as $row)
                                                <div class="col-lg-2 col-6">

                                                    <ul class="list-group">
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $row->dot_id }}<span
                                                                class="badge badge-warning text-dark counter">{{ $row->qty }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if ($undelivered->salesOrderDetailsBy->contains('productSales.id_material', 18) && $undelivered->statusDot == 0)
                            <div class="col-lg-12">
                                <button type="submit"
                                    class="btn btn-primary text-center form-control btn-sm saveButton">Update
                                    DOT
                                    Stock</button>
                            </div>
                        @endif
                    </form>

                    <hr class="bg-primary fw-bold">
                    <!-- cd-timeline Start-->
                    <h4>Delivery History</h4>
                    <section class="cd-container" id="cd-timeline">
                        @foreach ($undelivered->deliveryHistoriesBy as $history)
                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-movie bg-primary">
                                    @if ($history->status == 'Packing')
                                        <i class="icon-dropbox-alt"></i>
                                    @elseif ($history->status == 'Ready For Delivery')
                                        <i class="fa fa-cubes"></i>
                                    @elseif ($history->status == 'Delivering')
                                        <i class="fa fa-car"></i>
                                    @else
                                        <i class="fa fa-check"></i>
                                    @endif
                                </div>
                                <div class="cd-timeline-content">
                                    <h4>{{ $history->status }}</h4>
                                    <p class="m-0">{{ $history->remark }}</p>
                                    <br>
                                    <small
                                        class="fw-light mt-4">{{ date('d-M-Y H:i:s', strtotime($history->history_date)) }}</small>
                                    <small>Updated by {{ $history->createdBy->name }}</small>
                                </div>
                            </div>
                        @endforeach
                    </section>
                    <!-- cd-timeline Ends-->
                    <hr>
                    <h4>Update Delivery History</h4>
                    <br>
                    <form action="{{ url('delivery_history/' . $undelivered->id . '/store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-4 form-group">
                                <label>Delivery Status</label>
                                <select multiple name="status" class="form-control status-select" required>
                                    <option value="Packing">Packing</option>
                                    <option value="Ready For Delivery">Ready For Delivery</option>
                                    <option value="Delivering">Delivering</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>Date</label>
                                <input class="datepicker-here form-control digits" data-position="top left"
                                    type="text" data-language="en" name="history_date">
                            </div>

                            <div class="col-lg-4 form-group">
                                <label>Remark</label>
                                <input class="form-control" name="remark" type="text">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                {{-- @if ($undelivered->statusDot == 1) --}}
                                <button class="btn btn-primary" type="submit">Update
                                </button>
                                {{-- @endif --}}

                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
{{-- !end modal edit invoice --}}
