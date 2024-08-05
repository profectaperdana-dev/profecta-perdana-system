<a href="#" class="fw-bold text-nowrap modalItem" data-id="{{ $item->id }}" data-bs-toggle="modal"
    data-original-title="test" data-bs-target="#trace{{ $item->id }}">{{ $item->trip_number }}
</a>

<div class="modal" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="{{ url('trip/completed/approve_by_ga/' . $item->id) }}" class="approved" enctype="multipart/form-data"
        method="POST">
        @csrf
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <div>
                        <h6 class="text-center">BUSINESS TRIP REPORT PROPOSAL {{ $item->trip_number }}</h6>
                        <input type="hidden" class="id-item" name="" id="" value="{{ $item->id }}">
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
                                <label for="">Submission Date</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ date('d-m-Y', strtotime($item->completedBy->propose_date)) }}">
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="">GA Note</label>
                                <input type="text" class="form-control" readonly value="{{ $item->notes }}">
                            </div>
                            <div class="col-12 col-lg-4 mb-3 ">
                                <label for="">Evidence File</label>
                                <br>
                                <a class="link-info"
                                    href="{{ url('storage/app/pdf/trip/evidence/' . $item->completedBy->evidence) }}"
                                    target="_blank">Open uploaded file</a>

                            </div>
                            <div class="col-12 col-lg-4 mb-3">
                                <label for="">Departure Odometer</label>
                                <br>
                                <img width="50%" class="img-fluid shadow-lg"
                                    src="{{ asset('public/images/trip/' . $item->pict_odometer) }}" alt="Departure Odometer">


                            </div>
                            <div class="col-12 col-lg-4 mb-3">
                                <label for="">Return Odometer</label>
                                <br>
                                <img width="50%" class="img-fluid shadow-lg"
                                    src="{{ asset('public/images/trip/' . $item->completedBy->img_odometer) }}"
                                    alt="Return Odometer">


                            </div>
                            @if ($item->transport == 'Own Vehicle' || $item->transport == 'Public Transport')
                                <div class="col-12 col-lg-6 mb-3">
                                    <label for="">Vehicle</label>
                                    <input name="vehicle" class="file form-control" readonly type="text"
                                        value="{{ $item->transport }}">
                                </div>
                            @else
                                <div class="row">
                                    <div class="col mx-auto text-center fw-bold fs-5">Vehicle Return Check </div>
                                </div>
                                <div id="formLoan{{ $item->id }}" class="row">
                                    {{-- granmax --}}
                                    <input type="hidden"
                                        value="{{ asset('public/images/' . $item->completedBy->img_vehicle) }}"
                                        id="mobil1{{ $item->id }}">

                                    <div class="col-12 col-lg-8">
                                        <div id="granmax{{ $item->id }}">
                                            <button type="button" id="undoButtonGranmax{{ $item->id }}"
                                                class="btn btn-sm btn-warning"><i class="fa fa-undo"></i></button>
                                            <center>
                                                <canvas id="canvas{{ $item->id }}" width="900"
                                                    height="618"></canvas>
                                                <input type="hidden" id="canvasDataInputGranmax{{ $item->id }}"
                                                    name="canvasDataUrlGranmax">
                                            </center>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4 pt-4">
                                        <ul id="annotationsList{{ $item->id }}"
                                            data-count="{{ $item->completedBy->annotationBy->count() }}">
                                            @foreach ($item->completedBy->annotationBy as $vehicle)
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
                    </div>
                    <hr>
                    {{-- <div>
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
                                                value="{{ number_format($item->completedBy->detailBy->sum('other')) }}"
                                                class="form-control text-end totalOther" type="text" readonly>
                                        </th>

                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">&nbsp;</th>

                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Total Expense</th>
                                        <th><input value="{{ number_format($item->completedBy->total) }}"
                                                class="form-control text-end subTotal" type="text" readonly>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Cash Advance</th>
                                        <th>
                                            <input readonly class="form-control text-end " type="text"
                                                value="{{ number_format($item->transport_expense + $item->acomodation_expense + $item->other_expense) }}">
                                            <div class="text-center bg-dark mt-2 text-white rounded expand">
                                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                                            </div>

                                            <div class="expand-advance" hidden>
                                                <div class="text-sm mt-2 text-end">
                                                    Transport : {{ number_format($item->transport_expense) }}
                                                </div>
                                                <div class="text-sm mt-2 text-end">
                                                    Accommodation : {{ number_format($item->acomodation_expense) }}
                                                </div>
                                                <div class="text-sm mt-2 text-end">
                                                    Other : {{ number_format($item->other_expense) }}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Cash Remaining</th>
                                        <th><input
                                                value="{{ number_format($item->transport_expense + $item->acomodation_expense + $item->other_expense - $item->completedBy->total) }}"
                                                class="form-control text-end cashRemain" type="text" readonly>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info hideModal" type="button" data-bs-dismiss="modal">Close</button>
                    @if ($item->completedBy->approval_ga)
                        <button class="btn btn-primary" disabled type="button">
                            Need Finance Approval
                        </button>
                    @else
                        <button class="btn btn-primary" type="submit">
                            Approve
                        </button>
                        <a data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}"
                            data-bs-dismiss="modal" class="btn btn-danger">
                            Reject
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal" id="delete{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Reject Confirmation: {{ $item->trip_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure to reject this proposal?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a type="button" class="btn btn-danger" href="{{ url('trip/completed/reject/' . $item->id) }}">Yes,
                    reject</a>
            </div>
        </div>
    </div>
</div>
