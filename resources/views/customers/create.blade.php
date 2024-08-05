@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }}
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
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ url('customers/') }}" enctype="multipart/form-data">
                            @csrf
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
                $(".province").select2({
                    width: "100%",
                    placeholder: "Select Customer Province",
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

                    $(".city").select2({
                        width: "100%",
                        minimumResultsForSearch: -1,
                        placeholder: "Select Customer City",
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
                        placeholder: "Select Customer District",
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
                        placeholder: "Select Customer Village",
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
                        $('#modalreference').removeAttr('hidden');
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
                        $('#modalid').removeAttr('hidden');
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
                        $('#modalnpwp').removeAttr('hidden');
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
                        $('#modalselfie').removeAttr('hidden');
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
