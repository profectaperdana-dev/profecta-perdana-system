    <div class="btn-group">
        <a href="javascript:void(0)" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#detailDirect{{ $direct->id }}" class=" text-nowrap code fw-bold text-success"
            type="text">{{ $direct->order_number }}</a> <span>&nbsp;</span>
        <a href="javascript:void(0)" class="copy_code text-secondary">
            <i class="fas fa-copy fa-2x"></i></a>
    </div>
    <div class="currentModal">
        <div class="modal" id="receipt{{ $direct->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Cash Receipt {{ $direct->order_number }}</h6>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-striped" style="width:100%">
                        <caption class="text-info">*Settlement History</caption>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Payment Date</th>
                                <th class="text-center">Payment Method</th>
                                <th class="text-center">Amount</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($direct->directSalesCreditBy as $detail)
                                <tr>
                                    <td class="text-center"> {{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        {{ date('d F Y', strtotime($detail->payment_date)) }}
                                    </td>
                                    <td class="text-center">{{ $detail->payment_method }}</td>
                                    <td class="text-end">

                                        {{ number_format($detail->amount) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td class="text-end" colspan="3">Total Instalment</td>
                                <td class="text-end">
                                    {{ number_format($direct->directSalesCreditBy->sum('amount')) }}
                                </td>
                            </tr>
                            <tr class="fw-bold">
                                <td class="text-end" colspan="3">Total Invoice</td>
                                <td class="text-end">
                                    {{ number_format($direct->total_incl) }}
                                </td>
                            </tr>
                            <tr class="fw-bold">
                                <td class="text-end" colspan="3">Remaining Instalment</td>
                                <td class="text-end text-danger">
                                    {{ number_format($direct->total_incl - $direct->directSalesCreditBy->sum('amount')) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#detailData{{ $direct->id }}"
                            data-bs-dismiss="modal">Back
                        </button>
                        <button type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</button>
                        <a class="btn btn-primary" target="popup"
                            onclick="window.open('{{ url('direct_sales/' . $direct->id . '/cash_receipt') }}','name','width=600,height=400')">Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal" id="delete{{ $direct->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Delete {{ $direct->order_number }}</h6>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this invoice ?
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#detailDirect{{ $direct->id }}"
                            data-bs-dismiss="modal">Back
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <a type="button" href="{{ url('delete/direct_invoice/' . $direct->id) }}"
                            class="btn btn-primary btn-delete">Yes, delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="detailDirect{{ $direct->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        <div>
                            Order Number
                            {{ $direct->order_number }}
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
                                        @if (is_numeric($direct->cust_name))
                                            @if ($direct->customerBy == null)
                                                {{ $direct->cust_name }}
                                            @else
                                                {{ $direct->customerBy->name_cust }}
                                            @endif
                                        @else
                                            {{ $direct->cust_name }}
                                        @endif
                                    </div>
                                    <div class="form-group fw-bold col-7 col-lg-3">
                                        Order Date: {{ date('d F Y', strtotime($direct->order_date)) }}
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="form-group col-7 col-lg-5">
                                        Address:
                                        <address class="fw-bold"><i>{{ $direct->address }},
                                                {{ $direct->district }}</i>
                                        </address>

                                    </div>
                                    <div class="form-group col-7 col-lg-3">
                                        Email: {{ $direct->cust_email }}
                                    </div>
                                    <div class="form-group col-7 col-lg-5">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i> Delivery Point:
                                        <address class="fw-bold"><i>{{ $direct->delivery_point }}</i>
                                        </address>

                                    </div>
                                    <div class="form-group col-12 col-lg-12">
                                        <label for="">Remark</label>
                                        <!--<textarea class="form-control" rows="1"></textarea>-->
                                        <input class="form-control" value="{{ $direct->remark }} / @if ($direct->car_brand_id != null)
                        @if (is_numeric($direct->car_brand_id) && is_numeric($direct->car_type_id))
                            {{ $direct->carBrandBy->car_brand }} {{ $direct->carTypeBy->car_type }}
                        @else
                            {{ $direct->car_brand_id }} {{ $direct->car_type_id }}
                        @endif
                        @elseif($direct->motor_brand_id != null)
                        @if (is_numeric($direct->motor_brand_id) && is_numeric($direct->motor_type_id))
                            {{ $direct->motorBrandBy->name_brand }} {{ $direct->motorTypeBy->name_type }}
                        @else
                            {{ $direct->motor_brand_id }} {{ $direct->motor_type_id }}
                        @endif
                    @else
                        {{ $direct->other }}
                    @endif" readonly>
                                    </div>
                                </div>
                                <div class="" id="formReturn">

                                    @foreach ($direct->directSalesDetailBy as $item)
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
                                                    value="{{ number_format($item->discount_rp) }}" id="">
                                            </div>

                                            @php
                                                $retail_price = $item->price;
                                                if ($item->price == null) {
                                                    foreach ($item->retailPriceBy as $value) {
                                                        if ($value->id_warehouse == $direct->warehouse_id) {
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
                                        <strong>{{ number_format(round($direct->total_excl)) }}</strong>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="form-group col-3">
                                        <strong>PPN {{ $ppn * 100 }}%:</strong>

                                    </div>
                                    <div class="form-group col-4 col-lg-2 text-end ">
                                        <strong class="">{{ number_format(round($direct->total_ppn)) }}</strong>
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
                                                {{ number_format(round($direct->total_incl)) }}</strong>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Print
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" target="popup"
                                        onclick="window.open('{{ url('retail/print_struk/' . $direct->id) }}','name','width=600,height=400')">Print
                                        Struk</a></li>
                                <li><a class="dropdown-item" target="popup"
                                        onclick="window.open('{{ url('retail/print_invoice/' . $direct->id) }}','name','width=600,height=400')">Invoice</a>
                                </li>
                                <li><a class="dropdown-item" target="popup"
                                        onclick="window.open('{{ url('retail/print_do/' . $direct->id) }}','name','width=600,height=400')">Delivery
                                        Order</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                @canany(['level1', 'level2'])
                                    <li>
                                        <a href="javascript:void(0)" class="dropdown-item modalRetail"
                                            data-bs-toggle="modal" data-original-title="test"
                                            data-bs-target="#editDirect{{ $direct->id }}" data-bs-dismiss="modal">Edit
                                            Invoice
                                        </a>
                                    </li>
                                @endcanany
                                @canany(['level1'])
                                    <li><a href="" class="dropdown-item modal-btn2" data-bs-toggle="modal"
                                            data-original-title="test" data-bs-target="#delete{{ $direct->id }}"
                                            data-bs-dismiss="modal">Delete Invoice</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endcanany
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ url('material-promotion/transaction/create_by_invoice/' . $direct->id) }}">Create
                                        MP Transaction
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ url('create/trade_in/' . $direct->order_number) }}">Create Trade-In
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('retail/send_mail/' . $direct->id) }}">Send
                                        Email Invoice
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('return_retail/' . $direct->id) }}">Return
                                        Invoice
                                    </a>
                                </li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-original-title="test"
                                        data-bs-target="#receipt{{ $direct->id }}" href="#"
                                        data-bs-dismiss="modal">Cash Receipt
                                    </a></li>
                                <li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            </ul>
                        </div>

                        {{-- @canany(['level1', 'level2'])
                            <button class="btn btn-secondary modalRetail" type="button" data-bs-toggle="modal"
                                data-original-title="test" data-bs-target="#editDirect{{ $direct->id }}"
                                data-bs-dismiss="modal">Edit
                            </button>
                        @endcanany --}}
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editDirect{{ $direct->id }}" aria-labelledby="editDirect{{ $direct->id }}"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Retail
                        {{ $direct->order_number }}</h6>
                    {{-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <form action="{{ url('retail/' . $direct->id . '/update_retail') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="container-fluid">
                            <div class="row">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="warehouse_id" id="warehouse"
                                            value="{{ $direct->warehouse_id }}">
                                        <div class="row">
                                            <div class="mb-3 col-6">
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
                                                    type="text" name="cust_phone"
                                                    value="{{ $direct->cust_phone }}"
                                                    @if (is_numeric($direct->cust_name)) readonly @endif>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-sm-4 ">
                                                <label>ID Card Number</label>
                                                <input class="form-control id_card" placeholder="Enter ID Card Number"
                                                    type="text" name="cust_ktp" value="{{ $direct->cust_ktp }}"
                                                    @if (is_numeric($direct->cust_name)) readonly @endif>
                                                <div class="form-text">*Optional</div>
                                            </div>
                                            <div class="mb-3 col-sm-4">
                                                <label>Email Address</label>
                                                <input class="form-control email_add" placeholder="Enter Email"
                                                    type="text" name="cust_email"
                                                    value="{{ $direct->cust_email }}"
                                                    @if (is_numeric($direct->cust_name)) readonly @endif>
                                                <div class="form-text">*Optional</div>

                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label>Plate Number</label>
                                                <input class="form-control plate" placeholder="Enter Plate Number"
                                                    type="text" name="plate_number"
                                                    @if (is_numeric($direct->cust_name)) readonly @endif
                                                    value="{{ $direct->plate_number }}">
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-4 vehicle"
                                            @if (is_numeric($direct->cust_name)) hidden @endif>
                                            <label>Vehicle</label>
                                            <select class="form-control select2" name="vehicle" id="vehicle">
                                                <option value="">Choose Vehicle</option>
                                                <option value="Car"
                                                    @if ($direct->car_brand_id != null) selected @endif>Car
                                                </option>
                                                <option value="Motocycle"
                                                    @if ($direct->motor_brand_id != null) selected @endif>Motocycle
                                                </option>
                                                <option value="Other"
                                                    @if (($direct->car_brand_id == null) & ($direct->motor_brand_id == null)) selected @endif>Other
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" id="car" hidden>
                                        <div class="mb-3 col-sm-4">
                                            <label>Car Brand</label>
                                            <select class="form-control select2 car-brand" name="car_brand_id">
                                                <option selected="" value="">Choose Car Brand
                                                </option>
                                                @foreach ($car_brands as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($direct->car_brand_id == $item->car_brand) selected @endif>
                                                        {{ $item->car_brand }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label>Car Type</label>
                                            <select class="form-control select2 car-type" name="car_type_id">
                                                <option selected="" value="">Choose...</option>
                                                @if ($direct->car_type_id != null)
                                                    <option value="{{ $direct->car_type_id }}" selected>
                                                        {{ $direct->car_type_id }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-3 col-12 other-car" id="" hidden>
                                            <label>Other Car Type</label>
                                            <input type="text" placeholder="Enter Other Car Type" name="other_car"
                                                class="form-control other_car_input">
                                        </div>
                                    </div>
                                    <div class="row" id="motor" hidden>
                                        <div class="mb-3 col-sm-6">
                                            <label>Motocycle Brand</label>
                                            <select class="form-control select2 motor-brand" name="motor_brand_id">
                                                <option selected="" value="">Choose...</option>
                                                @foreach ($motor_brands as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($direct->motor_brand_id == $item->name_brand) selected @endif>
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
                                                        {{ $direct->motor_type_id }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb-3 col-12 other-motor" id="" hidden>
                                            <label>Other Motorcycle Type</label>
                                            <input type="text" placeholder="Enter Other Motorcycle Type"
                                                name="other_motor" class="form-control other_motor_input">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Province</label>
                                            <select name="province"
                                                class="form-control select2 province @error('province') is-invalid @enderror"
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
                                        <div class="mb-3 col-sm-4">
                                            <label>Address</label>
                                            <input type="text" placeholder="Enter Address"
                                                class="form-control address" name="address"
                                                value="{{ $direct->address }}"
                                                @if (is_numeric($direct->cust_name)) readonly @endif>
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label>Remark</label>
                                            <input class="form-control" placeholder="Enter Remark" type="text"
                                                name="remark" value="{{ $direct->remark }}">
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label>Payment Method</label>
                                            <select class="form-control select2" name="payment_method">
                                                <option value="1"
                                                    @if ($direct->payment_method == 1) selected @endif>
                                                    Cash
                                                </option>
                                                <option value="0"
                                                    @if ($direct->payment_method == 0) selected @endif>
                                                    Credit
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row box-select-all justify-content-end">
                                <button type="button" class="col-3 col-xl-1 me-3 btn btn-sm btn-primary addRetail"
                                    id="addRetail">+</button>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" id="so_id" value="{{ $direct->id }}">
                                <div class="row formRetail" id="formRetail">
                                    @foreach ($direct->directSalesDetailBy as $item)
                                        <div class="mx-auto py-2 rounded form-group row"
                                            style="background-color: #f0e194">
                                            <input type="hidden" class="loop" value="{{ $loop->index }}">
                                            <div class="col-3 col-lg-1 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control remSo-edit text-white text-center"
                                                    style="border:none; background-color:red">-</a>
                                            </div>
                                            <div class="form-group col-12 col-lg-5">
                                                <label>Product</label>
                                                <select name="retails[{{ $loop->index }}][product_id]"
                                                    class="form-control productRetail" required>
                                                    <option value="">Choose Product</option>
                                                    <option value="{{ $item->product_id }}" selected>
                                                        {{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}
                                                    </option>
                                                </select>
                                                @error('retails[{{ $loop->index }}][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-6 col-lg-2 form-group">
                                                <label>Qty</label>
                                                <input type="number" class="form-control qty-cart" required
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
                                                <input type="text" class="form-control" required
                                                    name="retails[{{ $loop->index }}][discount]"
                                                    value="{{ $item->discount }}" id="">
                                                @error('retails[{{ $loop->index }}][discount]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-5 col-lg-2 form-group">
                                                <label>Disc (Rp)</label>
                                                <input type="text" value="{{ $item->discount_rp }}"
                                                    class="form-control diskon" required>
                                                <input type="hidden" value="{{ $item->discount_rp }}"
                                                    name="retails[{{ $loop->index }}][discount_rp]">
                                                @error('retails[{{ $loop->index }}][discount_rp]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="row form-group series-code">
                                                @foreach ($item->directSalesCodeBy as $code)
                                                    <div class="col-6 col-lg-3 form-group first-code">
                                                        <input type="text" class="form-control" required
                                                            name="retails[{{ $loop->parent->index }}][{{ $loop->index }}][product_code]"
                                                            value="{{ $code->product_code }}" id="">
                                                        <input type="hidden" class="item-index"
                                                            value="{{ $loop->parent->index }}" id="">
                                                        <input type="hidden" class="loop-index"
                                                            value="{{ $loop->index }}" id="">
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <div class="btn-group">
                                        <button class="btn  btn-danger " type="button"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal"
                                            data-original-title="test"
                                            data-bs-target="#detailDirect{{ $direct->id }}"
                                            data-bs-dismiss="modal">Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>

        </div>
    </div>
    </div>
    
