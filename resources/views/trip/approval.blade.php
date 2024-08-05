@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
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

            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                </div>
            </div>
        </div>
    </div>



    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Choose Start Date" data-position="bottom left" name="from_date"
                                        id="from_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" type="text" data-language="en"
                                        placeholder="Choose End Date" data-position="bottom left" name="to_date"
                                        id="to_date">
                                </div>
                            </div>

                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary form-control text-white" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning form-control text-white" name="refresh"
                                        id="refresh">Refresh</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="dataTable" class="display table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>#</th>
                                        <th>Trip Number</th>
                                        <th>Name</th>
                                        <th>Departure Date</th>
                                        <th>Return Date</th>
                                        <th>Approval Status</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="form-group">
                                <small> <span class="text-danger">*Note : </span><br>
                                    - BTRPP is Business Trip Profecta Perdana <br>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($data as $item)
        <form action="{{ url('trip/approval') }}" method="POST" class="approved" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="modal fade" id="trace{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-fullscreen">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>APPROVAL BUSINESS TRIP PROPOSAL {{ $item->trip_number }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="canvasDataInputVariable{{ $item->id }}"
                                name="canvasDataUrlDefault">
                            <input type="hidden" id="canvasDataInputGranmax{{ $item->id }}"
                                name="canvasDataUrlGranmax">
                            <input type="hidden" id="canvasDataInputMobilio{{ $item->id }}"
                                name="canvasDataUrlMobilio">
                            <input type="hidden" name="id" class="id" value="{{ $item->id }}">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control" value="{{ $item->getName() }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Departure Date</label>
                                    <input type="text" class="form-control"
                                        value="{{ date('d-m-Y H:i', strtotime($item->departure_date)) }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="">Departure Date</label>
                                    <input type="text" class="form-control"
                                        value="{{ date('d-m-Y H:i', strtotime($item->return_date)) }}" readonly>
                                </div>
                                <div
                                    class="mb-3 transport_ {{ $item->transport == 'Operational Vehicle' ? 'col-lg-6' : 'col-lg-6' }} {{ $item->transport == 'Own Vehicle' ? 'col-lg-6' : 'col-lg-4' }}">
                                    <label for="">Transport</label>
                                    <select required name="transport" class="form-control transports" multiple>
                                        <option value="Own Vehicle"
                                            {{ $item->transport == 'Own Vehicle' ? 'selected' : '' }}>
                                            Own Vehicle</option>
                                        <option value="Public Transport"
                                            {{ $item->transport == 'Public Transport' ? 'selected' : '' }}>Public Transport
                                        </option>
                                        <option value="Operational Vehicle"
                                            {{ $item->transport == 'Operational Vehicle' ? 'selected' : '' }}>Operational
                                            Vehicle</option>
                                    </select>

                                </div>
                                <div
                                    class="mb-3 vehicle_ {{ $item->transport == 'Operational Vehicle' ? 'col-lg-6' : 'd-none' }} {{ $item->transport == 'Own Vehicle' ? 'd-none' : '' }}">
                                    <label for="vehicle">Vehicle</label>
                                    <select name="vehicle" class="form-control vehicle" multiple>
                                        @foreach ($vehicle as $option)
                                            <option value="{{ $option->asset_name }}"
                                                {{ $option->asset_name == $item->vehicle ? 'selected' : '' }}>
                                                {{ $option->asset_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div
                                    class="mb-3 vehicle_ {{ $item->transport == 'Operational Vehicle' ? 'col-lg-6' : 'd-none' }} {{ $item->transport == 'Own Vehicle' ? 'd-none' : '' }}">
                                    <label for="">Pict. Odometer</label>
                                    <input name="pict_odomoter" type="file" class="form-control">
                                </div>
                                <div
                                    class="distance_ mb-3 {{ $item->transport == 'Operational Vehicle' ? 'col-lg-6' : 'col-lg-6' }} {{ $item->transport == 'Own Vehicle' ? 'col-lg-6' : 'col-lg-4' }}">
                                    <label for="">Distance (Km)</label>
                                    <input type="text" class="form-control"
                                        value="{{ number_format($item->distance_route) }}" readonly>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="">Route Business Trip</label>
                                    <p>
                                        @php
                                            
                                            $data_route = $data_route->sortBy('id');
                                            
                                        @endphp
                                        @foreach ($data_route as $key => $route)
                                            @if ($route->id_trip == $item->id)
                                                <span class="badge badge-success">{{ $route->place }}</span>
                                                @if (!$loop->last)
                                                    <span></span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </p>
                                </div>

                                <div
                                    class="mb-3 purpose {{ $item->transport == 'Public Transport' ? 'col-lg-6' : '' }} {{ $item->transport == 'Own Vehicle' ? 'col-lg-6' : 'col-lg-4' }}">
                                    <label for="">Purpose</label>
                                    <textarea readonly class="form-control" name="" id="" cols="30" rows="1">{{ $item->purpose }}</textarea>
                                </div>
                                <div
                                    class="mb-3 notes {{ $item->transport == 'Public Transport' ? 'col-lg-6' : '' }} {{ $item->transport == 'Own Vehicle' ? 'col-lg-6' : 'col-lg-4' }}">
                                    <label for="">Notes : GA</label>
                                    <textarea readonly class="form-control" name="" id="" cols="30" rows="1">{{ $item->notes }}</textarea>

                                </div>
                                <div
                                    class="mb-3 odometer {{ $item->transport == 'Public Transport' ? 'd-none' : '' }} {{ $item->transport == 'Own Vehicle' ? 'd-none' : 'col-lg-4' }}">
                                    <label for="">Departure Odometer</label>
                                    <br>
                                    <img style="width: 50%" src="{{ asset('images/trip/' . $item->pict_odometer) }}"
                                        alt="">
                                </div>
                                <div hidden
                                    class="col-lg-4 odometer_ {{ $item->transport == 'Operational Vehicle' ? 'd-none' : '' }}">
                                    <label for="">Odometer</label>
                                    <input type="file" class="form-control">
                                </div>
                                <div class="row hideVehicle">
                                    <div class="col-2 mobile">
                                        <div class="vertical-text">
                                            <p style="margin-top: 250px" class=" text-danger">

                                                <span style="font-size: 14pt">^^^</span> scroll disini vvv
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-10 kendaraan{{ $item->id }}"
                                        {{ $item->transport == 'Public Transport' ? 'hidden' : '' }}
                                        {{ $item->transport == 'hidden' ? 'd-none' : '' }}>
                                        {{-- ?? MobilDefault --}}
                                        <input type="hidden" value="{{ url('public/images/' . $item->pict_vehicle) }}"
                                            id="mobilDefault{{ $item->id }}">
                                        <div hidden id="default{{ $item->id }}">
                                            <p class="mobile mb-5 mt-5 text-danger">
                                                <<< Silahkan geser gambar disini>>>
                                            </p>
                                            <button type="button" id="undoDefault"
                                                class="btn mb-1 btn-sm btn-warning"><i class="fa fa-undo"></i></button>
                                            <center>
                                                <canvas style="border: solid 5px black;"
                                                    id="canvasDefault{{ $item->id }}" width="900"
                                                    height="618"></canvas>

                                            </center>
                                            <p class="mobile mb-5 mt-5 text-danger">
                                                <<< Silahkan geser gambar disini>>>
                                            </p>
                                        </div>

                                        {{-- ?? mobiliio --}}
                                        <input type="hidden" value="{{ asset('images/mobilio.png') }}"
                                            id="mobilMobilio{{ $item->id }}">
                                        <div hidden id="mobilio{{ $item->id }}">
                                            <p class="mobile mb-5 mt-5 text-danger">
                                                <<< Silahkan geser gambar disini>>>
                                            </p>
                                            <button type="button" id="undoButtonMobilio"
                                                class="btn mb-1 btn-sm btn-warning"><i class="fa fa-undo"></i></button>
                                            <center>
                                                <canvas style="border: solid 5px black;"
                                                    id="canvasVehicle{{ $item->id }}" width="900"
                                                    height="618"></canvas>

                                            </center>
                                            <p class="mobile mb-5 mt-5 text-danger">
                                                <<< Silahkan geser gambar disini>>>
                                            </p>
                                        </div>

                                        {{-- ?? granmax --}}
                                        <input type="hidden" value="{{ asset('images/granmax_.png') }}"
                                            id="mobilGranMax{{ $item->id }}">
                                        <div hidden id="granmax{{ $item->id }}">
                                            <p class="mobile mb-5 mt-5 text-danger">
                                                <<< Silahkan geser gambar disini>>>
                                            </p>
                                            <button type="button" id="undoButtonGranmax"
                                                class="btn mb-1 btn-sm btn-warning"><i class="fa fa-undo"></i></button>
                                            <center>
                                                <canvas style="border: solid 5px black;" id="canvas{{ $item->id }}"
                                                    width="900" height="618"></canvas>

                                            </center>
                                            <p class="mobile mb-5 mt-5 text-danger">
                                                <<< Silahkan geser gambar disini>>>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12 mb-3 anotasi{{ $item->id }}"
                                        {{ $item->transport == 'Public Transport' ? 'hidden' : '' }}
                                        {{ $item->transport == 'Own Vehicle' ? 'hidden' : '' }}>
                                        <label for="">Annotation</label>
                                        <br />
                                        <br />

                                        <ul id="annotationsListDefault{{ $item->id }}"
                                            loop="{{ $item->vehicleBy->count() }}">
                                            @foreach ($item->vehicleBy as $key => $annotation)
                                                <li class="mb-2">
                                                    <div class="input-group flex-nowrap">
                                                        <span class="input-group-text"
                                                            style="background: white !important"><i class="fa fa-circle"
                                                                style="color:{{ $annotation->color }}"></i></span>
                                                        <input type="text" class="form-control"
                                                            name="formVehicle[{{ $loop->index }}][notes]"
                                                            value=" {{ $annotation->note }}">
                                                    </div>
                                                </li>
                                                <input type="hidden" name="formVehicle[{{ $loop->index }}][color]"
                                                    value="{{ $annotation->color }}">
                                            @endforeach

                                        </ul>
                                        <ul id="annotationsList{{ $item->id }}"></ul>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            @if ($item->finance_approval)
                                <a class="btn btn-warning" href="{{ url('trip/delete/' . $item->id) }}">Reject</a>
                            @endif
                            <button class="btn btn-danger hideModal" type="button"
                                data-bs-dismiss="modal">Close</button>
                            @if (!$item->finance_approval)
                                <button type="button" disabled class="btn btn-primary">
                                    Need Finance Approval
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary btnSubmit">
                                    Approve
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </form>
    @endforeach

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="{{ asset('js/date_convert.js') }}"></script>
        <script>
            // $(document).on('submit', '.approved', function(event) {
            //     event.preventDefault();
            //     var form_data = $(this).serialize();
            //     var url = $(this).attr('action');
            //     $.ajax({
            //         url: url,
            //         type: "POST",
            //         dataType: "json",
            //         data: form_data,
            //         beforeSend: function() {
            //             $('.btnSubmit').attr('disabled', true);
            //             $('.btnSubmit').html(
            //                 `<i class="fa fa-spinner fa-spin"></i> Processing...`
            //             );
            //         },
            //         success: function(response) {
            //             console.log(response);
            //             swal("Success !", "data has been saved", "success", {
            //                 button: "Close",
            //             });
            //             $('.dataTable').DataTable().ajax.reload();


            //         },
            //         error: function(jqXHR, textStatus, errorThrown) {
            //             console.log('Error:', textStatus, errorThrown);
            //         },
            //         complete: function() { // menambahkan fungsi complete untuk mengubah tampilan tombol kembali ke tampilan semula
            //             $('.btnSubmit').attr('disabled', false);
            //             $('.btnSubmit').html('Save');
            //         }
            //     });
            // });
        </script>

        @include('trip.js_approval')
    @endpush
@endsection
