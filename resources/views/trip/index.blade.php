@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/jquery.datetimepicker.css') }}"> --}}
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        @include('report.style')


        <style>
            @media (min-width: 768px) {

                /* Sesuaikan nilai 768px sesuai kebutuhan Anda */

                .mobile {
                    display: none;
                }
            }

            .vertical-text {
                writing-mode: vertical-rl;
                text-orientation: upright;
                /* Optional, for vertical orientation */
            }

            #canvasVehicle,
            #canvas {
                width: 100% !important;
                height: auto !important;
            }

            .color-icon {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: inline-block;
                margin-right: 5px;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                padding: 2px 6px !important;
                margin-top: 0 !important;
                background-color: #24695c !important;
                border-color: #17433b !important;
                color: #fff;
                margin-right: 8px !important;
                margin-bottom: 2px !important;
            }

            #directions-panel {
                margin-top: 10px;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>{{ $title }}</h4>
                </div>

            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <form class="needs-validation " method="POST" enctype="multipart/form-data" novalidate
                    action="{{ url('trip/store') }}">
                    @method('POST')
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" readonly class="form-control" id="selectedOptionsInput"
                                    name="route">
                                <input type="hidden" id="awal" name="start">
                                <input type="hidden" id="akhir" name="end">
                                <input type="hidden" value="0" id="fuel_price" name="fuel_price">
                                <input type="hidden" value="0" id="toll_cost" name="toll_cost">
                                <input type="hidden" value="0" id="transport_expense" name="transport_expense">
                                <input type="hidden" value="0" id="acomodation_expense" name="acomodation_expense">
                                <input type="hidden" value="0" id="other_expense" name="other_expense">
                                <input type="hidden" id="canvasDataInputGranmax" name="canvasDataUrlGranmax">
                                <input type="hidden" id="canvasDataInputMobilio" name="canvasDataUrlMobilio">
                                <div class="form-group mx-auto col-lg-1 col-2">
                                    <label for="">&nbsp;</label>
                                    <br>
                                    <button type="button" class="btn btn-primary addPartner">+</button>
                                </div>

                                <div class="form-group mx-auto col-lg-4 col-9">
                                    <label>Name</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->employeeBy->name }}"
                                        placeholder="enter name" readonly>
                                    <input type="hidden" name="formPartner[0][id_employee]"
                                        value="{{ Auth::user()->employeeBy->id }}">
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>NIK</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->employeeBy->nik }}"
                                        placeholder="enter NIK" readonly>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->employeeBy->phone }}"
                                        placeholder="enter NIK" readonly>
                                </div>
                                <div id="formPartner">

                                </div>
                                <div class="form-group col-12">
                                    <label for="">Purpose</label>
                                    <textarea required name="purpose" placeholder="Enter your purpose" class="form-control" id="" cols="30"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group col-12 col-lg-6">
                                    <label>Departure Date
                                    </label>
                                    @php
                                        $now = date('d-m-Y');
                                    @endphp
                                    <div class="input-group"><input class="datepicker-here form-control digits"
                                            data-position="bottom left" type="text" data-language="en"
                                            data-value="{{ date('d-m-Y', strtotime($now)) }}" name="departure_date"
                                            autocomplete="off" required>
                                        <input required class="form-control" name="departure_time" type="time"
                                            value="">
                                    </div>

                                </div>
                                <div class="form-group col-12 col-lg-6">
                                    <label>Return Date
                                    </label>
                                    <div class="input-group "><input required class="datepicker-here form-control digits"
                                            data-position="bottom left" type="text" data-language="en"
                                            data-value="{{ date('d-m-Y', strtotime($now)) }}" name="return_date"
                                            autocomplete="off">
                                        <input required class="form-control" name="return_time" type="time"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label>Transportation Type
                                    </label>
                                    <select required name="transport" class="form-control transports" multiple>
                                        <option value="Own Vehicle">Own Vehicle</option>
                                        <option value="Public Transport">Public Transport</option>
                                        <option value="Operational Vehicle">Operational Vehicle</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Cash Advance</label>
                                    <input type="text" readonly class="form-control dp totalCashAdvance"
                                        placeholder="Click for input cash advance">
                                    <input type="hidden" class="totalCashAdvance_">
                                    <small style="font-size: 8pt"> <span class="text-info">* Click for input cash
                                            advance</span>
                                        </span>
                                        <br>
                                    </small>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="">Distance Route (Km)</label>
                                    <input name="distance_route" type="text" readonly class="form-control distance">
                                </div>
                                <div class="row" hidden id="formLoan">
                                    
                                    <div class="mx-auto form-group col-lg-6">
                                        <label>Vehicle Type
                                        </label>
                                        <select name="vehicle" class="form-control vehicle" multiple>
                                            @foreach ($vehicle as $item)
                                                <option value="{{ $item->asset_name }}">{{ $item->asset_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <label for="">Notes : GA</label>
                                    <textarea name="notes" placeholder="Enter your notes" class="form-control" id="" cols="30"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="">Account Number</label>
                                    <input required type="text" name="account_number" class="form-control"
                                        placeholder="Enter name account number">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="">Bank</label>
                                    <select required name="account_bank" class="bank form-select" id=""
                                        multiple></select>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="">Name Account Number</label>
                                    <input required type="text" name="account_name" class="form-control"
                                        placeholder="Enter account number">
                                </div>
                                <div class="form-group">
                                    <small> <span class="text-danger">*Note : </span><br>
                                        - Expenses must be calculated no later than 5 working days after returning from a
                                        business trip <br>
                                        - Each expenditure must be accompanied by valid proof of expenditure <br>
                                    </small>

                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btnSubmit">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal" id="modalVehicle" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="exampleModalLabel">Detail Vehicle</h6>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-2 mobile">
                                            <div class="vertical-text">
                                                <p style="margin-top: 250px" class=" text-danger">

                                                    <span style="font-size: 14pt">^^^</span> scroll disini vvv
                                                </p>
                                            </div>
                                        </div>
                                        {{-- mobiliio --}}
                                        <div class="col-lg-9 col-10">
                                            <input type="hidden" value="{{ asset('images/mobilio.png') }}"
                                                id="mobil1">
                                            {{-- granmax --}}
                                            <input type="hidden" value="{{ asset('images/granmax_.png') }}"
                                                id="mobil2">


                                            <div hidden id="granmax">

                                                <p class="mobile mb-5 mt-5 text-danger">
                                                    <<< Silahkan geser gambar disini>>>
                                                </p>
                                                <button type="button" id="undoButtonGranmax"
                                                    class="btn btn-sm btn-warning mb-1"><i
                                                        class="fa fa-undo"></i></button>
                                                <canvas width="900" height="618" style="border: solid 5px black;"
                                                    class="mb-5 mr-5" id="canvas"></canvas>
                                                <p class="mobile mb-5 mt-5 text-danger">
                                                    <<< Silahkan geser gambar disini>>>
                                                </p>
                                            </div>
                                            <div hidden id="mobilio">
                                                <p class="mobile mb-5 mt-5 text-danger">
                                                    <<< Silahkan geser gambar disini>>>
                                                </p>
                                                <button type="button" id="undoButtonMobilio"
                                                    class="btn btn-sm btn-warning"><i class="fa fa-undo"></i></button>
                                                <center>
                                                    <canvas width="900" height="618" id="canvasVehicle"
                                                        style="border: solid 5px black;" class="mb-5 mr-5"></canvas>
                                                </center>
                                                <p class="mobile mb-5 mt-5 text-danger">
                                                    <<< Silahkan geser gambar disini>>>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <br>
                                            <br>
                                            <ul id="annotationsList"></ul>
                                        </div>



                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <a type="button" class="btn  btn-primary" data-bs-dismiss="modal">Finish</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal" id="detailForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Route Business Trip </h6>
                </div>
                <div class="modal-body">
                    <div id="container">


                        {{-- <div id="sidebar"> --}}
                        <div class="row">
                            <div class="col-12 col-lg-9 mb-3">
                                <div id="map" style="height: 500px;width: 100%;position:unset"></div>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Start:</label>
                                <select required name="start" multiple class="form-select mb-3" id="start">
                                    <option value="other">Other Location</option>
                                    @foreach ($warehouse as $warehouse)
                                        <option data-nama="{{ $warehouse->warehouses }}"
                                            value="{{ $warehouse->alamat }}">{{ $warehouse->warehouses }}
                                        </option>
                                    @endforeach
                                </select>
                                <div style="display:none" class="outsideStart_ mt-1">
                                    <input type="text" name="start" class="form-control getOutsideStart"
                                        placeholder="Enter the place name..." />
                                </div>
                                <br>
                                <div id="formLocation" class="mx-auto mt-3">
                                    <div class="row mx-auto mb-3">
                                        <div class="col-lg-4 col-4 mb-3">
                                            <label for="">&nbsp;</label>
                                            <br>
                                            <button type="button"
                                                class="text-center addLocation btn btn-sm btn-primary">+</button>
                                        </div>
                                        <div class=" col-lg-8 col-8">
                                            <label>Route : 
                                            </label>
                                            <select name="location[0]['waypoint']" class="form-select getRoute_ waypoints"
                                                multiple>
                                                <option value="other">Other Location</option>
                                                <option value="outside">Inter-island Trip</option>
                                                @foreach ($customer as $customer)
                                                    <option
                                                        data-nama="{{ $customer->code_cust . ' - ' . $customer->name_cust }}"
                                                        value="{{ $customer->coordinate }}">
                                                        {{ $customer->code_cust . ' - ' . $customer->name_cust }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div hidden class="otherRoute_ mt-1">
                                                <select name="" class="getCity form-control " multiple></select>
                                            </div>
                                            <div style="display:none" class="outsideRoute_ mt-1">
                                                <input type="text" name="" class="form-control getOutside" placeholder="Enter the place name..." />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>End:</label>
                                    <select required name="end" multiple class="form-select" id="end">
                                        <option value="other">Other Location</option>
                                        @foreach ($warehouse_ as $item)
                                            <option data-nama="{{ $item->warehouses }}"
                                                value="{{ $item->alamat }}">
                                                {{ $item->warehouses }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div style="display:none" class="outsideEnd_ mt-1">
                                        <input type="text" name="end" class="form-control getOutsideEnd"
                                            placeholder="Enter the place name..." />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-sm btn-primary btn-block" value="Calculate Distance"
                                        type="submit" id="submit" />
                                </div>
                            </div>

                        </div>
                        <div id="directions-panel"></div>
                        {{-- </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn  btn-primary" data-bs-dismiss="modal">Finish</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Detail Cash Advance</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div hidden id="fuel" class="col-lg-12 col-12 mb-3">
                            <label for="">Fuel Price (Rp)/L</label>
                            <input type="text" value="{{ number_format(10000) }}" class="form-control fuel_price"
                                placeholder="0">
                            <input type="hidden" value="10000" class="fuel_price_">
                            <small class="text-danger">* if the price of fuel has changed please change the price</small>
                        </div>
                        <div hidden id="toll" class="col-lg-12 col-12 mb-3">
                            <label for="">Toll Fee (Rp)</label>
                            <input type="text" class="form-control toll_cost" placeholder="0">
                            <input type="hidden" value="0" class="toll_cost_">
                        </div>
                        <div class="col-lg-12 mb-3 col-12">
                            <label for="">Transport (Rp)</label>
                            <input type="text" required class="form-control transport" placeholder="0">
                            <input type="hidden" value="0" class="transport_">
                        </div>
                        <div class="col-lg-12 mb-3 col-12">
                            <label for="">Accomodation (Rp)</label>
                            <input type="text" required class="form-control accomodation" placeholder="0">
                            <input type="hidden" value="0" class="accomodation_">
                        </div>
                        <div class="col-lg-12 mb-3 col-12">
                            <label for="">Other (Rp)</label>
                            <input type="text" class="form-control other" placeholder="0">
                            <input type="hidden" value="0" class="other_">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <a type="button" class="btn  btn-primary" data-bs-dismiss="modal">Finish</a>

                </div>
            </div>
        </div>
    </div>




    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/jquery.datetimepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1MgLuZuyqR_OGY3ob3M52N46TDBRI_9k&callback=initMap&v=weekly"
            defer></script>
        @include('trip.js_trip')
    @endpush
@endsection
