<a href="#"
    class="fw-bold text-nowrap modalItem
    @if ($item->status_lpd == 0) text-danger
    @else
        text-info @endif"
    data-id="{{ $item->id }}" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#trace{{ $item->id }}">{{ $item->trip_number }}</a>

<div class="modal" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <input type="hidden" class="id-item" name="" id="" value="{{ $item->id }}">

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
                        <div class="col-12 col-lg-4 mb-3">
                            <label for="">Departure Odometer</label>
                            <br>
                            <img width="50%" class="img-fluid shadow-lg"
                                src="{{ asset('/public/images/trip/' . $item->pict_odometer) }}" alt="Departure Odometer">


                        </div>
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="">Return Odometer</label>
                            <br>
                            <img width="50%" class="img-fluid shadow-lg"
                                src="{{ asset('/public/images/trip/' . $item->completedBy->img_odometer) }}"
                                alt="Return Odometer">
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
                                src="{{ asset('/public/images/' . $item->completedBy->img_vehicle) }}" alt="">
                        </div>
                        <div class="col-12 col-lg-4">
                            <ul id="annotationsList{{ $item->id }}">
                                @foreach ($item->completedBy->annotationBy as $vehicle)
                                    <li><i class="fa fa-circle" style="color:{{ $vehicle->color }}"></i>
                                        {{ $vehicle->note }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                
                <div class="col-lg-12 mb-3">
                    <label for="">Route Business Trip</label>
                    <p>
                        @foreach ($data_route as $key => $route)
                            @if ($route->id_trip == $item->id)
                                @if ($loop->first)
                                    <span class="badge badge-success">{{ $route->place }}</span>
                                @elseif($loop->last)
                                    <span class="badge badge-success">{{ $route->place }}</span>
                                @else
                                    <span class="badge badge-success">{{ $route->place }}</span>
                                @endif
                            @endif
                        @endforeach

                    </p>
                </div>

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
                                    {{-- <th>Toll</th> --}}
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
                                        <td><input placeholder="0" class="transport form-control text-end" readonly
                                                type="text" value="{{ number_format($detail->transport) }}">

                                        </td>
                                        <td><input placeholder="0" class="acomodation form-control text-end"
                                                type="text" readonly
                                                value="{{ number_format($detail->accommodation) }}">

                                        </td>
                                        <td><input placeholder="0" class="perDiem form-control text-end"
                                                type="text" readonly
                                                value="{{ number_format($detail->perdiem) }}">
                                        </td>
                                        {{-- <td><input placeholder="0" class="toll form-control text-end" type="text"
                                                readonly value="{{ number_format($detail->toll) }}">
                                        </td> --}}
                                        <td><input placeholder="0" class="other form-control text-end" type="text"
                                                readonly value="{{ number_format($detail->other) }}">

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="1" class="text-center">Total</th>
                                    <th><input
                                            value="{{ number_format($item->completedBy->detailBy->sum('transport')) }}"
                                            class="form-control text-end totalTransport" type="text" readonly>
                                    </th>
                                    <th><input
                                            value="{{ number_format($item->completedBy->detailBy->sum('accommodation')) }}"
                                            class="form-control text-end totalAcomodation" type="text" readonly>
                                    </th>
                                    <th><input
                                            value="{{ number_format($item->completedBy->detailBy->sum('perdiem')) }}"
                                            class="form-control text-end totalPerDiem" type="text" readonly>
                                    </th>
                                    <th><input value="{{ number_format($item->completedBy->detailBy->sum('toll')) }}"
                                            class="form-control text-end totalToll" type="text" readonly>
                                    </th>
                                    <th><input value="{{ number_format($item->completedBy->detailBy->sum('other')) }}"
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
                                    <th><input readonly class="form-control text-end " type="text"
                                            value="{{ number_format($item->transport_expense + $item->acomodation_expense + $item->other_expense + $item->toll_cost) }}">
                                        <input type="hidden"
                                            value="{{ $item->transport_expense + $item->acomodation_expense + $item->other_expense + $item->toll_cost }}"
                                            class="cashAdvance_">
                                    </th>
                                </tr>
                                @php
                                    $cashRemain = $item->transport_expense + $item->acomodation_expense + $item->toll_cost + $item->other_expense - $item->completedBy->total;
                                @endphp
                                <tr>
                                    <th colspan="5" class="text-end">Cash Remaining</th>
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
                <button class="btn btn-primary" id="print{{ $item->id }}"
                    data-url="{{ url('trip/completed/print/' . $item->id) }}">Print</button>
            </div>
        </div>
    </div>
</div>
