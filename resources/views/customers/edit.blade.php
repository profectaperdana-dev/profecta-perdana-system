@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet">

        <link rel="stylesheet"
            href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css"
            type="text/css" />

        @include('report.style')
        <style>
            .custom-popup {
                width: 1000px;
            }

            .marker {
                background-image: url('https://akademitrainer.com/wp-content/uploads/2017/12/map-marker-at.png');
                /* Ganti dengan URL gambar marker Anda */
                background-size: cover;
                width: 50px;
                height: 50px;
                cursor: pointer;
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
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Edit Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ url('customers/' . $customer->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            @include('customers._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
        <script src="{{ asset('js/custom.js') }}"></script>
        <script>
            let csrf = $('meta[name="csrf-token"]').attr("content");

            $(document).ready(function() {

                $(document).on('submit', 'form', function() {
                    // console.log('click');
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    // console.log(form.html());

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);

                    }
                });

                let coord = $('.edit-coordinate').val();
                let split_coor = coord.trim().split(',');
                var map;

                mapboxgl.accessToken =
                    'pk.eyJ1IjoibWF1bGF5eWFjeWJlciIsImEiOiJja3N5bTU2ZTkxZGMyMnZsZ2V2aTc5enlrIn0.AoQDAKuMyXgRBRptUQ-8Bw';
                map = new mapboxgl.Map({
                    container: 'peta',
                    style: 'mapbox://styles/mapbox/streets-v11',
                    center: [parseFloat(split_coor[1]), parseFloat(split_coor[
                        0])], // Koordinat pusat peta
                    zoom: 12 // Tingkat zoom awal
                });
                map.addControl(
                    new MapboxDirections({
                        accessToken: mapboxgl.accessToken,
                        unit: 'metric',
                        profile: 'mapbox/driving',
                        interactive: true,
                        steps: true,
                    }),
                    'top-left'
                );
                var markers = []; // Tambahkan inisialisasi untuk variabel markers

                var markerElement = document.createElement('div');
                markerElement.className = 'marker';

                var lang = split_coor[0];
                var lat = split_coor[1];
                var marker = new mapboxgl.Marker(markerElement)
                    .setLngLat([lat, lang])
                    .addTo(map);

                // markers.push(marker); // Tambahkan marker ke dalam array markers

                $('.edit-coordinate').change(function() {
                    let new_coor = $(this).val();
                    new_coor = new_coor.trim().split(',');
                    if (new_coor != null && new_coor != '' && new_coor.length > 1 && new_coor.length < 3) {
                        map.remove();
                        $('#peta').html('');


                        mapboxgl.accessToken =
                            'pk.eyJ1IjoibWF1bGF5eWFjeWJlciIsImEiOiJja3N5bTU2ZTkxZGMyMnZsZ2V2aTc5enlrIn0.AoQDAKuMyXgRBRptUQ-8Bw';
                        map = new mapboxgl.Map({
                            container: 'peta',
                            style: 'mapbox://styles/mapbox/streets-v11',
                            center: [parseFloat(new_coor[1]), parseFloat(
                                new_coor[
                                    0])], // Koordinat pusat peta
                            zoom: 12 // Tingkat zoom awal
                        });
                        map.addControl(
                            new MapboxDirections({
                                accessToken: mapboxgl.accessToken,
                                unit: 'metric',
                                profile: 'mapbox/driving',
                                interactive: true,
                                steps: true,
                            }),
                            'top-left'
                        );
                        var markers = []; // Tambahkan inisialisasi untuk variabel markers

                        var markerElement = document.createElement('div');
                        markerElement.className = 'marker';

                        var lang = new_coor[0];
                        var lat = new_coor[1];
                        var marker = new mapboxgl.Marker(markerElement)
                            .setLngLat([lat, lang])
                            .addTo(map);
                    }

                });

                $(".province").select2({
                    width: "100%",
                    minimumResultsForSearch: -1,
                    sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                    ajax: {
                        type: "GET",
                        url: "/customers/getProvince",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.name,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                $('.province').change(function() {
                    let province_value = $('.province').val();

                    $(".city").empty();
                    $(".district").empty();
                    $(".village").empty();

                    $(".city").select2({
                        width: "100%",
                        minimumResultsForSearch: -1,
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getCity/" + province_value,
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });

                $('.city').change(function() {
                    let city_value = $('.city').val();

                    $(".district").select2({
                        width: "100%",
                        minimumResultsForSearch: -1,
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getDistrict/" + city_value,
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });

                $('.district').change(function() {
                    let district_value = $('.district').val();

                    $(".village").select2({
                        width: "100%",
                        minimumResultsForSearch: -1,
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getVillage/" + district_value,
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });

                $('#inputreference').change(function() {
                    if (this.files && this.files[0]) {
                        $('#modalreference').find('a').text('New File: ' + this.files[0]['name']);
                        // console.log($('#preview-reference').parent().html());
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview-reference').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                $('#inputid').change(function() {
                    if (this.files && this.files[0]) {
                        $('#modalid').find('a').text('New File: ' + this.files[0]['name']);
                        // console.log($('#preview-id').parent().html());
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview-id').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                $('#inputnpwp').change(function() {
                    if (this.files && this.files[0]) {
                        $('#modalnpwp').find('a').text('New File: ' + this.files[0]['name']);
                        // console.log($('#preview-id').parent().html());
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview-npwp').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                $('#inputselfie').change(function() {
                    if (this.files && this.files[0]) {
                        $('#modalselfie').find('a').text('New File: ' + this.files[0]['name']);
                        // console.log($('#preview-id').parent().html());
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview-selfie').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                let eventLoc = document.getElementById('coorGenerate');
                let coor = document.getElementById('coor');

                const options = {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                };

                eventLoc.addEventListener("click", getLocation, false);

                function getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.watchPosition(showPosition, function() {}, options);
                    } else {
                        coor.innerHTML = "Geolocation is not supported by this browser.";
                    }
                }

                function showPosition(position) {
                    eventLoc.setAttribute('hidden', 'true');
                    coor.removeAttribute('hidden');
                    coor.setAttribute('readonly', 'true');
                    coor.value = position.coords.latitude + ", " + position.coords.longitude;
                }



            });
        </script>
    @endpush
@endsection
