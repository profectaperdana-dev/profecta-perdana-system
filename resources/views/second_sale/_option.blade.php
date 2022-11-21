<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    INV</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    @can('isSuperAdmin')
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#manageData{{ $invoice->id }}">Edit Invoice</a>
    @endcan

    @if ($invoice->customer_email != '-')
        <a class="dropdown-item" href="{{ url('send_email_trade_invoice/' . $invoice->id) }}">Send Invoice by
            Email</a>
    @endif
    <h5 class="dropdown-header">Prints</h5>
    <a class="dropdown-item" href="{{ url('second_sale/print_struk/' . $invoice->id . '/print') }}">Print Struk</a>
</div>

{{-- ! Modal Edit Invoice Trade In --}}
<div class="modal fade" id="manageData{{ $invoice->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Trade-In
                    Order
                    :
                    {{ $invoice->second_sale_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate method="post"
                    action="{{ url('second_sale/' . $invoice->id . '/edit_superadmin') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="form-group row">
                            <div class="col-md-6 form-group">
                                <label>
                                    Customers Name</label>
                                <input type="text" name="customer_name" required class="form-control"
                                    value="{{ $invoice->customer_name }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>
                                    Customers NIK</label>
                                <input type="text" name="customer_nik" class="form-control"
                                    value="{{ $invoice->customer_nik }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>
                                    Customers Phone</label>
                                <input type="text" data-v-min-length="9" data-v-max-length="13" number required
                                    name="customer_phone" class="form-control" value="{{ $invoice->customer_phone }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>
                                    Customers Email</label>
                                <input type="text" name="customer_email" class="form-control"
                                    value="{{ $invoice->customer_email }}">
                            </div>

                        </div>
                        <hr>
                        <div class="form-group row formSo-edit">
                            @foreach ($invoice->second_sale_details as $detail)
                                <div class="mx-auto py-2 form-group row bg-primary">
                                    <div class="form-group col-6 col-md-4">
                                        <label>Baterry</label>
                                        <select name="tradeFields[{{ $loop->index }}][product_trade_in]"
                                            class="form-control productSo-edit id_product" required>
                                            @if ($detail->secondProduct != null)
                                                <option value="{{ $detail->product_second_id }}" selected>
                                                    {{ $detail->secondProduct->name_product_trade_in }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-2 form-group">
                                        <label>Qty</label>
                                        <small class="text-danger qty-warning" hidden>Out of Stock</small>
                                        <input class="form-control cek_stock cekQty-edit" required
                                            name="tradeFields[{{ $loop->index }}][qty]" id=""
                                            value="{{ $detail->qty }}">

                                    </div>
                                    <div class="col-5 col-md-2 form-group">
                                        <label>Disc (%)</label>
                                        <input type="number" class="form-control disc_persen"
                                            name="tradeFields[{{ $loop->index }}][disc_percent]" id=""
                                            value="{{ $detail->discount }}">
                                    </div>
                                    <div class="col-5 col-md-2 form-group">
                                        <label>Disc (Rp)</label>
                                        <input class="form-control disc_rp" id=""
                                            name="tradeFields[{{ $loop->index }}][disc_rp]"
                                            value="{{ $detail->discount_rp }}">

                                    </div>
                                    @if ($loop->index == 0)
                                        <div class="col-2 col-md-2 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="form-control addSo-edit text-white  text-center"
                                                style="border:none; background-color:green">+</a>
                                        </div>
                                    @else
                                        <div class="col-2 col-md-2 form-group">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)"
                                                class="btn btn-danger form-control text-white remSo-edit">-</a>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-lg-12">
                                <label>TOTAL</label>
                                <input class="form-control total"
                                    value="{{ 'Rp. ' . number_format($invoice->total, 0, ',', '.') }}" id=""
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group col-12">
                            <button type="button" class="col-12 btn btn-outline-success btn-reload">--
                                Click this to
                                reload total
                                --</button>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
