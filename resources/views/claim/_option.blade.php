<a class="{{ $data->result != null ? 'fw-bold text-success' : 'fw-bold text-danger' }}" href="#"
    data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detail{{ $data->id }}">{{ $data->claim_number }}</a>

<div class="currentModal">
    <div class="modal" id="result{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="resultClaim" method="post" action="{{ url('mutasi_claim/' . $data->id) }}"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Claim Result
                    </h6>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="col-12 col-md-12 form-group">
                            <label>Result</label>
                            <select multiple name="result" class="form-select result selectMulti " required>

                                <option value="CP01 - Good Condition">CP01 -
                                    Good
                                    Condition</option>
                                <option value="CP02 - Waranty Rejected">CP02 -
                                    Waranty
                                    Rejected</option>
                                <option value="CP03 - Waranty Accepted">CP03 -
                                    Waranty
                                    Accepted</option>
                                <option value="CP04 - Good Will">CP04 - Good
                                    Will
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-12 warrantyTo form-group">
                            <label>Warranty To</label>
                            <select multiple name="to_vendor" id=""
                                class="form-select selectMulti warrantyAccepted" required>
                                @foreach ($warehouse_vendor as $row)
                                    <option value="{{ $row->id }}">
                                        {{ $row->warehouses }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-12 col-md-12 warehouseTo form-group" id="">
                            <label>Warehouse To</label>
                            <select multiple name="to_warehouse" id="" class="form-select goodWill selectMulti"
                                required>

                                @foreach ($warehouse_damaged as $row)
                                    <option value="{{ $row->id }}">
                                        {{ $row->warehouses }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger hideModalAdd" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary btnSubmit" type="submit">Save
                        changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" data-bs-backdrop="static" id="detail{{ $data->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Detail
                    {{ $data->claim_number }}</h6>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-14 col-md-12 col-lg-12">
                        <div class="card-body shadow">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link nav-new active"
                                        id="pills-home-tab-{{ $data->id }}" data-bs-toggle="pill"
                                        href="#pills-home-{{ $data->id }}" role="tab"
                                        aria-controls="pills-home" aria-selected="true">Prior
                                        Checking
                                        <div class="media"></div>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link nav-new"
                                        id="pills-profile-tab-{{ $data->id }}" data-bs-toggle="pill"
                                        href="#pills-profile-{{ $data->id }}" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">Final
                                        Checking</a></li>
                            </ul>
                            <div class="col-12">
                                <div class="form-group row font-weight-bold">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="form-group col-12 col-lg-4">
                                                <label>Claim
                                                    Number</label>
                                                <input type="text" class="form-control " placeholder="Product Name"
                                                    readonly value="{{ $data->claim_number }}">
                                            </div>
                                            <div class="form-group col-12 col-lg-4">
                                                <label>
                                                    Date</label>
                                                <input type="text" class="form-control" placeholder="Serial Number"
                                                    readonly value="{{ date('d F y', strtotime($data->claim_date)) }}">
                                            </div>
                                            <div class="form-group col-12 col-lg-4">
                                                <label>
                                                    Replaced
                                                </label>
                                                <input type="text" class="form-control" placeholder="Serial Number"
                                                    readonly
                                                    value="{{ date('d F y', strtotime($data->date_replaced)) }}">
                                            </div>
                                            <div class="form-group col-12 col-lg-4">
                                                <label>Vehicle</label>
                                                <input type="text" class="form-control text-capitalize"
                                                    placeholder="Serial Number" readonly
                                                    value="{{ $data->other_machine ? $data->other_machine : ($data->car_brand_id ? $data->carBrandBy->car_brand . ' - ' . (is_numeric($data->car_type_id) ? $data->carTypeBy->car_type : $data->car_type_id) : $data->motorBrandBy->name_brand . ' - ' . (is_numeric($data->motor_type_id) ? $data->motorTypeBy->name_type : $data->motor_type_id)) }}">

                                            </div>
                                            <div class="form-group col-12 col-lg-4">
                                                <label>Battery
                                                </label>
                                                <input type="text" class="form-control" placeholder="Product Code"
                                                    readonly
                                                    value="{{ $data->material }} {{ $data->type_material }} {{ $data->productSales->nama_barang }}">
                                            </div>
                                            <div class="form-group col-12 col-lg-4">
                                                <label>Product Code</label>
                                                <input type="text" class="form-control text-uppercase"
                                                    placeholder="Product Code" readonly
                                                    value="{{ $data->product_code }}">
                                            </div>

                                            <div class="form-group col-12 col-lg-4">
                                                <label>
                                                    Plate Number</label>
                                                <input type="text" class="form-control text-uppercase"
                                                    placeholder="Serial Number" readonly
                                                    value="{{ $data->plate_number }}">
                                            </div>
                                            <div class="form-group col-12 col-lg-4">
                                                <label>
                                                    Customer Source -
                                                    Name</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Serial Number" readonly
                                                    value="@if (is_numeric($data->customer_id)) {{ $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust . ' / ' . $data->sub_name }} @else {{ $data->customer_id . ' / ' . $data->sub_name }} @endif">
                                            </div>
                                            <div class="form-group col-12 col-lg-4">
                                                <label>
                                                    Customer
                                                    Phone - Email</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Serial Number" readonly
                                                    value="{{ $data->sub_phone }}@if ($data->email != null) - {{ $data->email }} @endif">
                                            </div>
                                            @if ($data->loan_product_id != null)
                                                <div class="form-group col-md-12">
                                                    <label>
                                                        Lend
                                                        Battery</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Serial Number" readonly
                                                        value="{{ $data->loanBy->sub_materials->nama_sub_material }} {{ $data->loanBy->sub_types->type_name }} {{ $data->loanBy->nama_barang }}">
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane  show active" id="pills-home-{{ $data->id }}"
                                            role="tabpanel" aria-labelledby="pills-home-tab">
                                            <div class="form-group col-12">
                                                <div class="row">
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>
                                                            Voltage
                                                        </label>
                                                        <input type="text" name="" readonly
                                                            class="form-control" value="{{ $data->e_voltage }}">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>CCA </label>
                                                        <input type="text" class="form-control" readonly
                                                            placeholder="Retail Selling Price"
                                                            value="{{ $data->e_cca }}" name="f_cca">

                                                    </div>
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>Starting</label>
                                                        <input type="text" class="form-control" readonly
                                                            placeholder="Non Retail Selling Price" name=""
                                                            value="{{ $data->e_starting }}">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>Charging</label>
                                                        <input type="text" class="form-control" readonly
                                                            name="" value="{{ $data->e_charging }}">
                                                    </div>
                                                    <div class="form-group col-lg-4 col-md-12">
                                                        <label>
                                                            Received
                                                            By,</label>
                                                        <br>
                                                        <p><strong>{{ $data->createdBy->name }}</strong>
                                                        </p>
                                                    </div>
                                                    <div class="form-group col-lg-4 col-md-12">
                                                        <label>
                                                            Evidence,</label>
                                                        <br>
                                                        <div class="text-center">
                                                            <img class="img-fluid shadow" style="width: 200px"
                                                                id="img"
                                                                src="{{ asset('file_evidence/' . $data->e_foto) }}"
                                                                alt="">
                                                        </div>

                                                    </div>
                                                    <div class="form-group col-lg-4 col-md-12">
                                                        <label>
                                                            Submitted
                                                            By</label>
                                                        <br>
                                                        <div class="text-center">
                                                            <img class="img-fluid img-rotate " style="width: 200px"
                                                                id="img"
                                                                src="{{ asset('file_signature/' . $data->e_receivedBy) }}"
                                                                alt="">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane " id="pills-profile-{{ $data->id }}"
                                            role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <div class="form-group col-12 col-lg-12">
                                                <div class="row">
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>
                                                            Voltage
                                                        </label>
                                                        <input type="text" name="" readonly
                                                            class="form-control" value="{{ $data->f_voltage }}">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>CCA
                                                        </label>
                                                        <input type="text" class="form-control" readonly
                                                            placeholder="Retail Selling Price"
                                                            value="{{ $data->f_cca }}" name="f_cca">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>Starting</label>
                                                        <input type="text" class="form-control" readonly
                                                            placeholder="Non Retail Selling Price" name=""
                                                            value="{{ $data->f_starting }}">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>Charging</label>
                                                        <input type="text" class="form-control" readonly
                                                            name="" value="{{ $data->f_charging }}">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-3">
                                                        <label>Cost</label>
                                                        <p>Rp {{ number_format($data->cost) }}</p>
                                                    </div>
                                                    <div class="form-group col-md-12">

                                                        <label for="">Diagnostic Result</label>
                                                        @foreach ($data->accuClaimDetailsBy as $key => $row)
                                                            <div>
                                                                {{ $key + 1 }}.
                                                                {{ $row->diagnosa }}
                                                            </div>
                                                        @endforeach


                                                    </div>
                                                    @if ($data->result != null)
                                                        <div class="form-group col-md-12">
                                                            <label>Result
                                                                Claim</label>

                                                            <p>@php
                                                                echo htmlspecialchars_decode(htmlspecialchars_decode($data->result));
                                                            @endphp
                                                            </p>


                                                        </div>
                                                    @endif
                                                    @if ($data->mutation_number != null)
                                                        <div class="form-group col-md-12">
                                                            <label>Mutation
                                                                Number
                                                            </label>
                                                            <p>
                                                                @php
                                                                    echo htmlspecialchars_decode(htmlspecialchars_decode($data->mutation_number));
                                                                @endphp
                                                            </p>
                                                        </div>
                                                    @endif


                                                    <div class="form-group col-lg-4 col-md-12">
                                                        <label>
                                                            Submitted
                                                            By,</label>
                                                        <br>
                                                        <p><strong>{{ $data->createdBy->name }}</strong>
                                                        </p>
                                                    </div>
                                                    <div class="form-group col-lg-4 col-md-12">
                                                        <label>
                                                            Evidence,</label>
                                                        <br>
                                                        <div class="text-center">
                                                            <img class="img-fluid shadow" style="width: 200px"
                                                                id="img"
                                                                src="{{ asset('file_evidence/' . $data->f_foto) }}"
                                                                alt="">
                                                        </div>

                                                    </div>
                                                    <div class="form-group col-lg-4 col-md-12">
                                                        <label>
                                                            Received
                                                            By,</label>
                                                        <br>
                                                        <div class="text-center">
                                                            <img class="img-fluid img-rotate " style="width: 200px"
                                                                id="img"
                                                                src="{{ asset('file_signature/' . $data->f_receivedBy) }}"
                                                                alt="">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <div class="btn-group dropup {{ $data->result != null ? 'd-none' : '' }}">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item modal-btn2" data-bs-toggle="modal" data-original-title="test"
                                data-bs-target="#result{{ $data->id }}" href="#"
                                data-bs-dismiss="modal">Result Claim
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    </ul>
                </div>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>

