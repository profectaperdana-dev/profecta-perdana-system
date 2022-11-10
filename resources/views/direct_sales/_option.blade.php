<a href="#" class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailDirect{{ $direct->id }}">
    ACTION</a>

<div class="modal fade" id="detailDirect{{ $direct->id }}" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <div>
                        Order Number
                        :
                        {{ $direct->order_number }}
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
                                    @if (is_numeric($direct->cust_name))
                                        {{ $direct->customerBy->name_cust }}
                                    @else
                                        {{ $direct->cust_name }}
                                    @endif
                                </div>
                                <div class="form-group col-7 col-lg-3">
                                    Order Date: {{ date('d-M-Y', strtotime($direct->order_date)) }}
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-7 col-lg-5">
                                    Address:
                                    {{ $direct->address }}, {{ $direct->district }}
                                </div>
                                <div class="form-group col-7 col-lg-3">
                                    Email: {{ $direct->cust_email }}
                                </div>
                            </div>
                            <div class="row" id="formReturn">
                                @foreach ($direct->directSalesDetailBy as $item)
                                    <div class="row mx-auto py-2 form-group bg-primary">
                                        <div class="form-group col-9 col-lg-4">
                                            <label>Product</label>
                                            <input readonly class="form-control"
                                                value="{{ $item->productBy->nama_barang . ' (' . $item->productBy->sub_materials->nama_sub_material . ', ' . $item->productBy->sub_types->type_name . ')' }}">
                                        </div>
                                        <div class="col-3 col-lg-2 form-group">
                                            <label>Qty</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $item->qty }}" id="">
                                        </div>
                                        <div class="col-4 col-lg-1 form-group">
                                            <label>Disc (%)</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $item->discount }}" id="">
                                        </div>
                                        <div class="col-4 col-lg-2 form-group">
                                            <label>Disc (Rp)</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $item->discount_rp }}" id="">
                                        </div>

                                        @php
                                            $disc = $item->discount / 100;
                                            $hargadisc = $item->productBy->harga_jual * $disc;
                                            $harga = $item->productBy->harga_jual - $hargadisc;
                                            $total = $harga * $item->qty;
                                        @endphp
                                        <div class="col-4 col-lg-3 form-group">
                                            <label>Amount (Rp)</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ number_format($total, 0, ',', '.') }}" id="">
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
                                    <strong>Rp. {{ number_format($direct->total_excl, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <strong>PPN {{ $ppn * 100 }}%:</strong>

                                </div>
                                <div class="form-group col-4 col-lg-2">
                                    <strong>Rp. {{ number_format($direct->total_ppn, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <hr>
                            <div class="row justify-content-between">
                                <div class="form-group col-3">
                                    <h5>total (Include PPN):</h5>

                                </div>
                                <div class="form-group col-4 col-lg-2">
                                    <h5>Rp. {{ number_format($direct->total_incl, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-6">
                                    <label for="">Remark</label>
                                    <input class="form-control" value="{{ $direct->remark }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-info" href="{{ url('retail/print_invoice/' . $direct->id) }}">Print</a>
                    <a class="btn btn-primary" href="{{ url('retail/send_mail/' . $direct->id) }}">Send
                        Email
                    </a>
                    @can('isSuperAdmin')
                        <button class="btn btn-secondary modalRetail" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#editDirect{{ $direct->id }}"
                            data-bs-dismiss="modal">Edit
                        </button>
                    @endcan
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editDirect{{ $direct->id }}" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Retail
                    :
                    {{ $direct->order_number }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('retail/' . $direct->id . '/update_retail') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="mb-3 col-sm-6">
                                            <label>Name</label>
                                            <select class="form-control select2" name="cust_name" id="cust"
                                                required>
                                                <option value="">Choose Customer
                                                </option>
                                                <option value="other_cust"
                                                    @if (!is_numeric($direct->cust_name)) selected @endif>
                                                    Other
                                                </option>
                                                @foreach ($customers as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($direct->cust_name == $item->id) selected @endif>
                                                        {{ $item->name_cust }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input class="form-control manual-cust" placeholder="Enter Name"
                                                type="text" name="cust_name_manual"
                                                @if (!is_numeric($direct->cust_name)) value="{{ $direct->cust_name }}"
                                                @else
                                                hidden @endif>
                                        </div>
                                        <div class="mb-3 col-sm-6">
                                            <label>Phone Number</label>
                                            <input class="form-control phone" placeholder="Enter Phone Number"
                                                type="text" name="cust_phone" value="{{ $direct->cust_phone }}"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-sm-6 ">
                                            <label>ID Card Number</label>
                                            <input class="form-control id_card" placeholder="Enter ID Card Number"
                                                type="text" name="cust_ktp" value="{{ $direct->cust_ktp }}"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                            <div class="form-text">*Optional</div>
                                        </div>
                                        <div class="mb-3 col-sm-6">
                                            <label>Email Address</label>
                                            <input class="form-control email_add" placeholder="Enter Email"
                                                type="text" name="cust_email" value="{{ $direct->cust_email }}"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                            <div class="form-text">*Optional</div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-sm-6">
                                            <label>Plate Number</label>
                                            <input class="form-control plate" placeholder="Enter Plate Number"
                                                type="text" name="plate_number"
                                                @if (is_numeric($direct->cust_name)) readonly @endif
                                                value="{{ $direct->plate_number }}">
                                        </div>
                                        <div class="mb-3 col-sm-6 vehicle"
                                            @if (is_numeric($direct->cust_name)) hidden @endif>
                                            <label>Vehicle</label>
                                            <select class="form-control select2" name="vehicle" id="vehicle">
                                                <option value="">Choose Vehicle</option>
                                                <option value="Car">Car</option>
                                                <option value="Motocycle">Motocycle</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" id="car" hidden>
                                        <div class="mb-3 col-sm-6">
                                            <label>Car Brand</label>
                                            <select class="form-control select2 car-brand" name="car_brand_id">
                                                <option selected="" value="">Choose Car Brand
                                                </option>
                                                @foreach ($car_brands as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($direct->car_brand_id == $item->id) selected @endif>
                                                        {{ $item->car_brand }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-sm-6">
                                            <label>Car Type</label>
                                            <select class="form-control select2 car-type" name="car_type_id">
                                                <option selected="" value="">Choose...</option>
                                                @if ($direct->car_type_id != null)
                                                    <option value="{{ $direct->car_type_id }}" selected>
                                                        {{ $direct->carTypeBy->car_type }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" id="motor" hidden>
                                        <div class="mb-3 col-sm-6">
                                            <label>Motocycle Brand</label>
                                            <select class="form-control select2 motor-brand" name="motor_brand_id">
                                                <option selected="" value="">Choose...</option>
                                                @foreach ($motor_brands as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($direct->motor_brand_id == $item->id) selected @endif>
                                                        {{ $item->name_brand }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-sm-6">
                                            <label>Motocycle Type</label>
                                            <select class="form-control motor-type" name="motor_type_id">
                                                <option selected="" value="">Choose...</option>
                                                @if ($direct->motor_type_id != null)
                                                    <option value="{{ $direct->motor_type_id }}" selected>
                                                        {{ $direct->motorTypeBy->name_type }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Province</label>
                                            <select name="province"
                                                class="form-control province @error('province') is-invalid @enderror"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                                <option value="{{ $direct->province }}" selected>
                                                    {{ $direct->province }}
                                                </option>
                                                {{-- @if ($customer->province != null)
                                                    <option selected value="{{ $customer->province }}">
                                                        {{ $customer->province }}
                                                    </option>
                                                @endif --}}
                                            </select>
                                            @error('province')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>District</label>
                                            <select name="district"
                                                class="form-control city @error('district') is-invalid @enderror"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                                <option value="{{ $direct->district }}" selected>
                                                    {{ $direct->district }}
                                                </option>
                                                {{-- @if ($customer->city != null)
                                                    <option selected value="{{ $customer->city }}">
                                                        {{ $customer->city }}
                                                    </option>
                                                @endif --}}
                                            </select>
                                            @error('district')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Sub-district</label>
                                            <select name="sub_district"
                                                class="form-control district @error('sub_district') is-invalid @enderror"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                                <option value="{{ $direct->sub_district }}" selected>
                                                    {{ $direct->sub_district }}
                                                </option>
                                                {{-- @if ($customer->district != null)
                                                    <option selected value="{{ $customer->district }}">
                                                        {{ $customer->district }}
                                                    </option>
                                                @endif --}}
                                            </select>
                                            @error('sub_district')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-sm-6">
                                            <label>Address</label>
                                            <input type="text" placeholder="Enter Address"
                                                class="form-control address" name="address"
                                                value="{{ $direct->address }}"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                        </div>
                                        <div class="mb-3 col-sm-6">
                                            <label>Remark</label>
                                            <input class="form-control" placeholder="Enter Remark" type="text"
                                                name="remark" value="{{ $direct->remark }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row box-select-all justify-content-end">
                                <button type="button" class="col-3 col-xl-1 me-3 btn btn-sm btn-primary"
                                    id="addRetail">+</button>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" id="so_id" value="{{ $direct->id }}">
                                <div class="row" id="formRetail">
                                    @foreach ($direct->directSalesDetailBy as $item)
                                        <input type="hidden" class="loop" value="{{ $loop->index }}">
                                        <div class="mx-auto py-2 form-group row bg-primary">
                                            <div class="form-group col-12 col-lg-6">
                                                <label>Product</label>
                                                <select name="retails[{{ $loop->index }}][product_id]"
                                                    class="form-control productRetail" required>
                                                    <option value="">Choose Product</option>
                                                    <option value="{{ $item->product_id }}" selected>
                                                        {{ $item->productBy->nama_barang . ' (' . $item->productBy->sub_materials->nama_sub_material . ', ' . $item->productBy->sub_types->type_name . ')' }}
                                                    </option>
                                                </select>
                                                @error('retails[{{ $loop->index }}][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control" required
                                                    name="retails[{{ $loop->index }}][qty]"
                                                    value="{{ $item->qty }}" id="">
                                                @error('retails[{{ $loop->index }}][qty]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-4 col-lg-2 form-group">
                                                <label>Disc (%)</label>
                                                <input type="number" class="form-control" required
                                                    name="retails[{{ $loop->index }}][discount]"
                                                    value="{{ $item->discount }}" id="">
                                                @error('retails[{{ $loop->index }}][discount]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-3 col-md-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control remSo-edit text-white text-center"
                                                    style="border:none; background-color:red">-</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#detailDirect{{ $direct->id }}"
                                        data-bs-dismiss="modal">Detail
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
