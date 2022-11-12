@extends('prospective_employee.master')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> </h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                    </h6>
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
                        <h5>{{ $title }}</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="post"
                            action="{{ url('prospective_employees/store_form') }}" enctype="multipart/form-data">
                            @csrf
                            @include('prospective_employee._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(function() {

                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
                //reload instance after dynamic element is added
                validator.reload();
            })
        </script>

        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");

                $('.last_salary_1').on('keyup', function() {
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
                $('.last_salary_2').on('keyup', function() {
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
                $('.salary_expected').on('keyup', function() {
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


                $('.check_status').attr('hidden', true);
                $(document).on('change', '.marital', function() {
                    var val = $(this).val();
                    if (val == 'Marry') {
                        $('.check_status').attr('hidden', false);
                    } else {
                        $('.check_status').attr('hidden', true);
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
            });
        </script>
    @endpush
@endsection
