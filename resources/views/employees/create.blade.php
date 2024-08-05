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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create
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
                        <form class="needs-validation" novalidate method="post" action="{{ url('employee/') }}"
                            enctype="multipart/form-data">
                            @csrf
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js"
            integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                // regex phone number
                $('#phone').on('keyup', function() {
                    var string = $(this).val();
                    var phone = string.replace(/\D*(\d{3})\D*(\d{4})\D*(\d{4})\D*/, '$1 $2 $3');
                    $(this).val(phone);
                });
                $('.total').on('keyup', function() {
                    var selection = window.getSelection().toString();
                    if (selection !== '') {
                        return;
                    }
                    // When the arrow keys are pressed, abort.
                    if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                        return;
                    }
                    var $this = $(this);
                    // Get the value.
                    var input = $this.val();
                    var input = input.replace(/[\D\s\._\-]+/g, "");
                    input = input ? parseInt(input, 10) : 0;
                    $this.val(function() {
                        return (input === 0) ? "" : input.toLocaleString("id-ID");
                    });
                    $this.next().val(input);
                });
                $(
                    ".emergency"
                ).select2({
                    width: "50%",
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
