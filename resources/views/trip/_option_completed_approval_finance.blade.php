<a href="#" class="fw-bold text-nowrap modalItem" data-id="{{ $item->id }}" data-bs-toggle="modal"
    data-original-title="test" data-bs-target="#trace{{ $item->id }}">{{ $item->trip_number }}
</a>

<div class="modal" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="{{ url('trip/completed/approve_by_finance/' . $item->id) }}" class="approved"
        enctype="multipart/form-data" method="POST">
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
                                    src="{{ url('public/images/trip/' . $item->pict_odometer) }}" alt="Departure Odometer">

                            </div>
                            <div class="col-12 col-lg-4 mb-3">
                                <label for="">Return Odometer</label>
                                <br>
                                <img width="50%" class="img-fluid shadow-lg"
                                    src="{{ url('public/images/trip/' . $item->completedBy->img_odometer) }}"
                                    alt="Return Odometer">

                            </div>

                        </div>
                    </div>
                    <hr>
                    <div>
                        <h6 class="text-center">TRIP EXPENSE</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Transport</th>
                                        <th>Accommodation</th>
                                        <th>Per-Diem</th>
                                        {{-- <th>Toll</th> --}}
                                        <th>Other</th>

                                    </tr>
                                </thead>
                                <tbody id="table-body{{ $item->id }}"
                                    data-count={{ $item->completedBy->detailBy->count() }}>
                                    @foreach ($item->completedBy->detailBy as $detail)
                                        <tr>
                                            @if ($loop->index == 0)
                                                <td class="d-flex">
                                                    <button type="button" class="btn-sm btn-primary addRow">+</button>
                                                </td>
                                            @else
                                                <td class="d-flex">
                                                    <button type="button"
                                                        class="btn-sm btn-danger me-2 remRow">-</button>
                                                    <button type="button" class="btn-sm btn-primary addRow">+</button>
                                                </td>
                                            @endif

                                            <td><input name="expense[{{ $loop->index }}][date]"
                                                    class="datepicker-here form-control digits" data-position="top left"
                                                    type="text" data-language="en"
                                                    data-value="{{ date('d-m-Y', strtotime($detail->date)) }}"
                                                    autocomplete="on"
                                                    value="{{ date('d-m-Y', strtotime($detail->date)) }}">
                                            </td>
                                            <td>
                                                <textarea class="form-control" name="expense[{{ $loop->index }}][desc]" id="" cols="30" rows="1">{{ $detail->description }}</textarea>
                                            </td>
                                            <td><input placeholder="0" class="transport form-control text-end"
                                                    type="text" value="{{ number_format($detail->transport) }}">
                                                <input value="{{ $detail->transport }}" type="hidden"
                                                    class="realTransport"
                                                    name="expense[{{ $loop->index }}][transport]">
                                            </td>
                                            <td><input placeholder="0" class="acomodation form-control text-end"
                                                    type="text"
                                                    value="{{ number_format($detail->accommodation) }}">
                                                <input value="{{ $detail->accommodation }}" type="hidden"
                                                    class="realAcomodation"
                                                    name="expense[{{ $loop->index }}][acomodation]">
                                            </td>
                                            <td><input placeholder="0" class="perDiem form-control text-end"
                                                    type="text" value="{{ number_format($detail->perdiem) }}">
                                                <input value="{{ $detail->perdiem }}" type="hidden"
                                                    class="realPerDiem"
                                                    name="expense[{{ $loop->index }}][per_diem]">
                                            </td>
                                            <!--<input placeholder="0" class="toll form-control text-end" type="hidden"-->
                                            <!--    value="{{ number_format($detail->toll) }}">-->
                                            <!--<input value="{{ $detail->toll }}" type="hidden" class="realToll"-->
                                            <!--    name="expense[{{ $loop->index }}][toll]">-->

                                            <td><input placeholder="0" class="other form-control text-end"
                                                    type="text" value="{{ number_format($detail->other) }}">
                                                <input value="{{ $detail->other }}" type="hidden" class="realOther"
                                                    name="expense[{{ $loop->index }}][other]">
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-center">Total</th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('transport')) }}"
                                                class="form-control text-end totalTransport" type="text" readonly>
                                            <input type="hidden" class="totalTransport_"
                                                value="{{ $item->completedBy->detailBy->sum('transport') }}">
                                        </th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('accommodation')) }}"
                                                class="form-control text-end totalAcomodation" type="text"
                                                readonly>
                                            <input type="hidden" class="totalAcomodation_"
                                                value="{{ $item->completedBy->detailBy->sum('accommodation') }}">
                                        </th>
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('perdiem')) }}"
                                                class="form-control text-end totalPerDiem" type="text" readonly>
                                            <input type="hidden" class="totalPerDiem_"
                                                value="{{ $item->completedBy->detailBy->sum('perdiem') }}">
                                        </th>
                                        <!--<th><input-->
                                        <!--        value="{{ number_format($item->completedBy->detailBy->sum('toll')) }}"-->
                                        <!--        class="form-control text-end totalToll" type="text" readonly>-->
                                        <!--    <input type="hidden" class="totalToll_"-->
                                        <!--        value="{{ $item->completedBy->detailBy->sum('toll') }}">-->
                                        <!--</th>-->
                                        <th><input
                                                value="{{ number_format($item->completedBy->detailBy->sum('other')) }}"
                                                class="form-control text-end totalOther" type="text" readonly>
                                            <input type="hidden" class="totalOther_"
                                                value="{{ $item->completedBy->detailBy->sum('other') }}">
                                        </th>

                                    </tr>
                                    <tr>
                                        <th colspan="7" class="text-end">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Total Expense</th>
                                        <th><input value="{{ number_format($item->completedBy->total) }}"
                                                class="form-control text-end subTotal" type="text" readonly>
                                            <input type="hidden" value="{{ $item->completedBy->total }}"
                                                class="subTotal_" name="sub_total">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Cash Advance</th>
                                        <th>
                                            <input readonly class="form-control text-end " type="text"
                                                value="{{ number_format($item->transport_expense + $item->acomodation_expense + $item->other_expense + $item->toll_cost) }}">
                                            <input type="hidden"
                                                value="{{ $item->transport_expense + $item->acomodation_expense + $item->other_expense + $item->toll_cost }}"
                                                class="cashAdvance_">
                                            <div class="text-center bg-dark mt-2 text-white rounded expand">
                                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                                            </div>

                                            <div class="expand-advance" hidden>
                                                <div class="text-sm mt-2 text-end">
                                                    Transport + Toll :
                                                    {{ number_format($item->transport_expense + $item->toll_cost) }}
                                                </div>
                                                <div class="text-sm mt-2 text-end">
                                                    Accommodation : {{ number_format($item->acomodation_expense) }}
                                                </div>
                                                {{-- <div class="text-sm mt-2 text-end">
                                                    : {{ number_format() }}
                                                </div> --}}
                                                <div class="text-sm mt-2 text-end">
                                                    Other : {{ number_format($item->other_expense) }}
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Cash Remaining</th>
                                        @php
                                            $cashRemain = $item->transport_expense + $item->acomodation_expense + $item->other_expense + $item->toll_cost - $item->completedBy->total;
                                        @endphp
                                        <th><input value="{{ number_format($cashRemain) }}"
                                                class="form-control text-end cashRemain @if ($cashRemain < 0) text-danger @endif"
                                                type="text" readonly>
                                            <input type="hidden" class="cashRemain_" value="{{ $cashRemain }}">
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info hideModal" type="button" data-bs-dismiss="modal">Close</button>
                   @if ($item->completedBy->approval_ga)
                        <button class="btn btn-primary" type="submit">
                            Approve
                        </button>
                        <a data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}"
                            data-bs-dismiss="modal" class="btn btn-danger">
                            Reject
                        </a>
                    @else
                        <button class="btn btn-primary" disabled type="button">
                            Need GA Approval
                        </button>
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
                <a type="button" class="btn btn-danger"
                    href="{{ url('trip/completed/reject_by_finance/' . $item->id) }}">Yes,
                    reject</a>
            </div>
        </div>
    </div>
</div>
