<a href="#"
    class="fw-bold text-nowrap modalItem
    @if ($item->status_lpd == 0) text-danger
    @else
        text-info @endif"
    data-id="{{ $item->id }}" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#trace{{ $item->id }}">{{ $item->trip_number }}</a>
@if ($item->status_lpd == 0)
    <div class="modal fade" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ url('trip/completed/propose/' . $item->id) }}" class="approved" enctype="multipart/form-data"
            method="POST">
            @csrf
            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <div>
                            <h6 class="text-center">BUSINESS TRIP REPORT {{ $item->trip_number }}</h6>
                            <input type="hidden" class="id-item" name="" id=""
                                value="{{ $item->id }}">
                            <input type="hidden" class="id-vehicle" name="" id=""
                                value="{{ $item->transport }}">
                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" readonly value="{{ $item?->getName() }}">
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label for="">NIK</label>
                                    <input type="text" class="form-control" readonly value="{{ $item->getNik() }}">
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label for="">Departure Date</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ date('d-m-Y, H:i', strtotime($item->departure_date)) }} WIB">
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label for="">Return Date</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ date('d-m-Y, H:i', strtotime($item->return_date)) }} WIB">
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label for="">Evidence File (pdf)</label>
                                    <input name="evidence" class="file form-control" required type="file">

                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label for="">Return Odometer (jpg/png/jpeg)</label>
                                    <input name="odometer" class="file form-control" required type="file">

                                </div>

                                @if ($item->transport == 'Own Vehicle' || $item->transport == 'Public Transport')
                                    <div class="col-12 col-lg-6 mb-3">
                                        <label for="">Vehicle</label>
                                        <input name="vehicle" class="file form-control" readonly type="text"
                                            value="{{ $item->transport }}">
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col mx-auto text-center fw-bold fs-5">Vehicle Return Check</div>
                                    </div>
                                    <div id="formLoan{{ $item->id }}" class="row">
                                        {{-- granmax --}}
                                        <input type="hidden" value="{{ asset('public/images/' . $item->pict_vehicle) }}"
                                            id="mobil1{{ $item->id }}">

                                        <div class="col-12 col-lg-8">
                                            <div id="granmax{{ $item->id }}">
                                                <button type="button" id="undoButtonGranmax{{ $item->id }}"
                                                    class="btn btn-sm btn-warning"><i class="fa fa-undo"></i></button>
                                                <center>
                                                    <canvas id="canvas{{ $item->id }}" width="900"
                                                        height="618"></canvas>
                                                    <input type="hidden"
                                                        id="canvasDataInputGranmax{{ $item->id }}"
                                                        name="canvasDataUrlGranmax">
                                                </center>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4 pt-4">
                                           <ul id="annotationsList{{ $item->id }}"
                                                data-count="{{ $item->vehicleBy->count() }}">
                                                @foreach ($item->vehicleBy as $vehicle)
                                                    <li class="mb-2">
                                                        <div class="input-group flex-nowrap">
                                                            <span class="input-group-text"
                                                                style="background: white !important"><i
                                                                    class="fa fa-circle"
                                                                    style="color:{{ $vehicle->color }}"></i></span>
                                                            <input type="text" class="form-control"
                                                                name="formVehicle[{{ $loop->index }}][notes]"
                                                                value=" {{ $vehicle->note }}">
                                                        </div>
                                                        <input type="hidden"
                                                            name="formVehicle[{{ $loop->index }}][color]"
                                                            value="{{ $vehicle->color }}">
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                            </div>
                            {{-- <table class="table mb-3">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>:</th>
                                        <th></th>
                                        <th>NIK</th>
                                        <th>:</th>
                                        <th><input type="text" class="form-control" readonly
                                                value="{{ $item->employeeBy->nik }}"></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>:</th>
                                        <th><input type="text" class="form-control" readonly
                                                value="{{ }}"></th>
                                        <th></th>
                                        <th>:</th>
                                        <th><input type="text" class="form-control" readonly
                                                value="{{ }}"></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>:</th>
                                        <th><input type="text" class="form-control" readonly
                                                value="{{ }}"></th>
                                        <th>Return Date</th>
                                        <th>:</th>
                                        <th><input type="text" class="form-control" readonly
                                                value="{{ }}"></th>
                                    </tr>
                                    <tr>
                                        <th>Evidence File</th>
                                        <th>:</th>
                                        <th>
                                            <input name="expense[0]['file']" class="file form-control" type="file">
                                        </th>
                                    </tr>
                                </thead>
                            </table> --}}
                            <small> <span class="text-danger">*Note : </span><br>
                                -Please fill in the Business Trip Report Proposal with the evidence of expenses
                                incurred
                                by the aforementioned individual by attaching the necessary and accountable
                                supporting
                                documents.
                                <br>

                            </small>
                        </div>
                        <hr>
                        <div>
                            <h6 class="text-center">TRIP EXPENSE</h6>

                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Transport</th>
                                        <th>Accommodation</th>
                                        <th>Per-Diem</th>
                                        {{-- <th>Toll</th> --}}
                                        <th>Other</th>

                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <tr>
                                        <td><button type="button" class="btn-sm btn-primary addRow">+</button></td>
                                        <td><input name="expense[0][date]" class="datepicker-here form-control digits"
                                                data-position="top left" type="text" data-language="en"
                                                data-value="{{ date('d-m-Y') }}" autocomplete="on">
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="expense[0][desc]" id="" cols="30" rows="1"></textarea>
                                        </td>
                                        <td><input placeholder="0" class="transport form-control text-end"
                                                type="text">
                                            <input value="0" type="hidden" class="realTransport"
                                                name="expense[0][transport]">
                                        </td>
                                        <td><input placeholder="0" class="acomodation form-control text-end"
                                                type="text">
                                            <input value="0" type="hidden" class="realAcomodation"
                                                name="expense[0][acomodation]">
                                        </td>
                                        <td><input placeholder="0" class="perDiem form-control text-end"
                                                type="text">
                                            <input value="0" type="hidden" class="realPerDiem"
                                                name="expense[0][per_diem]">
                                        </td>
                                        {{-- <td><input placeholder="0" class="toll form-control text-end" type="text"> --}}
                                        <input value="0" type="hidden" class="realToll"
                                            name="expense[0][toll]">
                                        {{-- </td> --}}
                                        <td><input placeholder="0" class="other form-control text-end"
                                                type="text">
                                            <input value="0" type="hidden" class="realOther"
                                                name="expense[0][other]">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-center">Total</th>
                                        <th><input value="0" class="form-control text-end totalTransport"
                                                type="text" readonly>
                                            <input type="hidden" class="totalTransport_" value="0">
                                        </th>
                                        <th><input value="0" class="form-control text-end totalAcomodation"
                                                type="text" readonly>
                                            <input type="hidden" class="totalAcomodation_" value="0">
                                        </th>
                                        <th><input value="0" class="form-control text-end totalPerDiem"
                                                type="text" readonly>
                                            <input type="hidden" class="totalPerDiem_" value="0">
                                        </th>
                                        {{-- <th><input value="0" class="form-control text-end totalToll"
                                                type="text" readonly> --}}
                                        <input type="hidden" class="totalToll_" value="0">
                                        {{-- </th> --}}
                                        <th><input value="0" class="form-control text-end totalOther"
                                                type="text" readonly>
                                            <input type="hidden" class="totalOther_" value="0">
                                        </th>

                                    </tr>
                                    <tr>
                                        <th colspan="7" class="text-end">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Total Expense</th>
                                        <th><input value="0" class="form-control text-end subTotal"
                                                type="text" readonly>
                                            <input type="hidden" class="subTotal_" name="sub_total">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Cash Advance</th>
                                        <th><input readonly class="form-control text-end " type="text"
                                                value="{{ number_format($item->toll_cost + $item->transport_expense + $item->acomodation_expense + $item->other_expense) }}">
                                            <input type="hidden"
                                                value="{{ $item->toll_cost + $item->transport_expense + $item->acomodation_expense + $item->other_expense }}"
                                                class="cashAdvance_">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Cash Remaining</th>
                                        <th><input value="0" class="form-control text-end cashRemain"
                                                type="text" readonly>
                                            <input type="hidden" class="cashRemain_">
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger hideModal" type="button"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btnSubmit">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@else
    <div class="modal fade" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <div>
                        <h6 class="text-center">BUSINESS TRIP REPORT {{ $item->trip_number }}</h6>

                        <div class="row">
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">Name</label>
                                <input type="text" class="form-control" readonly value="{{ $item->getName() }}">
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">NIK</label>
                                <input type="text" class="form-control" readonly value="{{ $item->getNik() }}">
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">Departure Date</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ date('d-m-Y, H:i', strtotime($item->departure_date)) }} WIB">
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">Return Date</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ date('d-m-Y, H:i', strtotime($item->return_date)) }} WIB">
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">Proposed Date</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ date('d-m-Y', strtotime($item->completedBy->propose_date)) }}">
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">Evidence File</label>
                                <br>
                                <a class="link-info"
                                    href="{{ url('storage/app/pdf/trip/evidence/' . $item->completedBy->evidence) }}"
                                    target="_blank">Open uploaded file</a>

                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">Return Odometer</label>
                                <br>
                                <img width="50%" class="img-fluid shadow-lg"
                                    src="{{ asset('images/trip/' . $item->completedBy->img_odometer) }}"
                                    alt="">
                                {{-- <a class="link-info"
                                    href="{{ url('images/trip/' . $item->completedBy->img_odometer) }}"
                                    target="_blank">Open uploaded Image</a> --}}
                            </div>
                        </div>
                    </div>
                    @if ($item->transport == 'Own Vehicle' || $item->transport == 'Public Transport')
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="">Vehicle</label>
                            <input name="vehicle" class="file form-control" readonly type="text"
                                value="{{ $item->transport }}">
                        </div>
                    @else
                        <hr>
                        <div class="row">
                            <div class="col text-center fw-bold fs-5">Vehicle Return Check</div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <img width="100%" class="img-fluid shadow-lg"
                                    src="{{ asset('images/' . $item->completedBy->img_vehicle) }}" alt="">
                            </div>
                            <div class="col-12 col-lg-4">
                                <ul id="annotationsList{{ $item->id }}">
                                    @foreach ($item->vehicleBy as $vehicle)
                                        <li><i class="fa fa-circle" style="color:{{ $vehicle->color }}"></i>
                                            {{ $vehicle->note }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div>
                        <h6 class="text-center">TRIP EXPENSE</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Transport</th>
                                        <th>Accommodation</th>
                                        <th>Per-Diem</th>
                                        <th>Toll</th>
                                        <th>Other</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->completedBy->detailBy as $detail)
                                        <tr>
                                            <td><input readonly class=" form-control "
                                                    value="{{ date('d-m-Y', strtotime($detail->date)) }}">
                                            </td>
                                            <td>
                                                <textarea readonly class="form-control" cols="30" rows="1">{{ $detail->description }}</textarea>
                                            </td>
                                            <td><input placeholder="0" class="transport form-control text-end"
                                                    readonly type="text"
                                                    value="{{ number_format($detail->transport) }}">

                                            </td>
                                            <td><input placeholder="0" class="acomodation form-control text-end"
                                                    type="text" readonly
                                                    value="{{ number_format($detail->accommodation) }}">

                                            </td>
                                            <td><input placeholder="0" class="perDiem form-control text-end"
                                                    type="text" readonly
                                                    value="{{ number_format($detail->perdiem) }}">
                                            </td>
                                            <td><input placeholder="0" class="toll form-control text-end"
                                                    type="text" readonly
                                                    value="{{ number_format($detail->toll) }}">
                                            </td>
                                            <td><input placeholder="0" class="other form-control text-end"
                                                    type="text" readonly
                                                    value="{{ number_format($detail->other) }}">

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-center">Total</th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('transport')) }}"
                                                class="form-control text-end totalTransport" type="text" readonly>
                                        </th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('accommodation')) }}"
                                                class="form-control text-end totalAcomodation" type="text"
                                                readonly>
                                        </th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('perdiem')) }}"
                                                class="form-control text-end totalPerDiem" type="text" readonly>
                                        </th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('toll')) }}"
                                                class="form-control text-end totalToll" type="text" readonly>
                                        </th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('other')) }}"
                                                class="form-control text-end totalOther" type="text" readonly>
                                        </th>

                                    </tr>
                                    <tr>
                                        <th colspan="7" class="text-end">&nbsp;</th>

                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Total Expense</th>
                                        <th><input value="{{ number_format($item->completedBy->total) }}"
                                                class="form-control text-end subTotal" type="text" readonly>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Cash Advance</th>
                                        <th><input readonly class="form-control text-end " type="text"
                                                value="{{ number_format($item->transport_expense + $item->acomodation_expense + $item->toll_cost + $item->other_expense) }}">
                                            <input type="hidden"
                                                value="{{ $item->transport_expense + $item->acomodation_expense + $item->toll_cost + $item->other_expense }}"
                                                class="cashAdvance_">
                                        </th>
                                    </tr>
                                    @php
                                        $cashRemain = $item->transport_expense + $item->acomodation_expense + $item->toll_cost + $item->other_expense - $item->completedBy->total;
                                    @endphp
                                    <tr>
                                        <th colspan="6" class="text-end">Cash
                                            Remaining</th>
                                        <th><input value="{{ number_format($cashRemain) }}"
                                                class="form-control text-end cashRemain @if ($cashRemain < 0) text-danger @endif"
                                                type="text" readonly>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger hideModal" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
