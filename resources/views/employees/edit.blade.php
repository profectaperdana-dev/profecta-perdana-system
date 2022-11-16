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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Edit
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

                    <div class="card-body">
                        <form class="needs-validation" novalidate method="post"
                            action="{{ url('employee/' . $employee->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('employees._form')
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
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="{{ asset('js/custom.js') }}"></script>
        <script>
            let csrf = $('meta[name="csrf-token"]').attr("content");

            $(document).ready(function() {
                $('form').submit(function(e) {
                    if (e.result == true) {
                        $(this).find('button[type="submit"]').prop('disabled', true);
                    }
                });
                $(".province").select2({
                    width: "100%",
                    placeholder: "Select Province",
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
                        placeholder: "Select District",
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
                        placeholder: "Select Sub-District",
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

                $(function() {

                    let validator = $('form.needs-validation').jbvalidator({
                        errorMessage: true,
                        successClass: true,
                        language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                    });
                    //custom validate methode
                    validator.validator.custom = function(el, event) {
                        if ($(el).is('[name=signed]') && $(el).val().length < 1) {
                            return "<span class='text-danger'>Please don't leave the signature form blank </span>";
                        }
                    }



                    //reload instance after dynamic element is added
                    validator.reload();
                })


            });
        </script>
    @endpush
@endsection
