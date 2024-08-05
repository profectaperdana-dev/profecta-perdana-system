@extends('layouts.master')
@section('content')
    @push('css')
            @include('report.style')

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"
            integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/gh/dubrox/Multiple-Dates-Picker-for-jQuery-UI@master/jquery-ui.multidatespicker.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/eggplant/theme.min.css"
            integrity="sha512-W7T9CmbGyR3T8S8gHkzLXMbXbP9tzYYKAQXM9x4C8OkDwGZd+NTsJvUAghZQdMW8Wkq5hr+bojzHdtuW2yaahA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        {{-- <style>
            .red span {
                background-color: red !important;
                color: white !important;
                pointer-events: initial !important;
            }

            .green span {
                background-color: green !important;
                color: white !important;
                pointer-events: initial !important;
            }
        </style> --}}
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create {{ $title }} </h6>
                </div>

            </div>
        </div>
    </div>
    {{-- @php
        dd($arr_days);
    @endphp --}}
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Leave Proposal Form {{$cek_ga}}</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="post" action="{{ url('leave/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @include('vacation._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmationModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4 id="selectedDateText"></h4>
                    <hr>
                    <center>
                        <button type="button" data="full" class="btn btn-block btn-primary cekButton">Full Day</button>
                        <button type="button" data="half" class="btn btn-block btn-secondary cekButton">Half
                            Day</button>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid Ends-->

    @push('scripts')
        <!-- Plugins JS start-->
        <script></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
            integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script
            src="https://cdn.jsdelivr.net/gh/dubrox/Multiple-Dates-Picker-for-jQuery-UI@master/jquery-ui.multidatespicker.js">
        </script>
        <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>

        <script>
            $(function() {
                //initialize validator
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
                //reload instance after dynamic element is added
                // validator.reload();
                $('form').submit(function(e) {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                    if (validator.checkAll() != 0) {
                        $(this).find('button[type="submit"]').prop('disabled', false);
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                        var csrf = $('meta[name="csrf-token"]').attr('content');

$(document).find('.select-employee').select2({
                placeholder: 'Select an option',
                allowClear: true,
                maximumSelectionLength: 1,
                width: '100%',
                ajax: {
                    context: this,
                    type: "GET",
                    url: "/leave/get-employee/",
                    data: function(params) {
                        return {
                            _token: csrf,
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
                                    vacation: item.vacation,
                                }, ];
                            }),
                        };
                    },
                },
            }).on('select2:select', function(e) {
                var selectedOption = $(this).select2('data')[0];
                var vacation = selectedOption.vacation;
                var id = selectedOption.id;
                $(document).find('#vacation_default').val(vacation);
            });
            
            
            
                var disabledDates = JSON.parse(`<?= $arr_days ?>`);
                var cekValue; // Declare cekValue outside the event handlers
                let partTime = []; // Declare partTime outside the onSelect function




                console.log(partTime);
                $('#mdp-demo').multiDatesPicker({
                    altField: '#altField',
                    width: '100%',
                    // maxPicks: <?= $vacation['vacation'] ?>,
                    todayBtn: false,
                    language: 'en',
                    beforeShowDay: function(date) {
                        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                        var day = date.getDay();
                        return [day > 0 && disabledDates.indexOf(string) == -1];
                    },
                    onSelect: function(dateText, inst) {

                        let cekDate = dateText.split('/');
                        let valDate = cekDate[1] + '/' + cekDate[0] + '/' + cekDate[2];

                        $('#selectedDateText').text(valDate);

                        // Tampilkan modal
                        $('#confirmationModal').modal('show');

                        // console.log(altField.value);


                        if (altField.value == '') {
                            $('#vacation_get').val(0);
                            $('#vacation_remain').val(0);
                            partTime = [];
                        }

                    }

                });
                $(document).find('.cekButton').click(function() {
                    $(this).attr('data');
                    if ($(this).attr('data') == 'full') {
                        cekValue = 1;
                    } else {
                        cekValue = 0.5;
                    }
                    partTime.push(
                        cekValue
                    );
                    let partLength = partTime.length;
                    let count_date = $('.change-date').val(); // get value count date
                    let array_date = count_date.split(','); // split value count date
                    let count_length = array_date.length;
                    let vacation_default = $('#vacation_default')
                        .val(); // get value vacation default
                    let vacation_get = $('#vacation_get').val(); // get value vacation get
                    // result vacation remain
                    let partTimeSum = partTime.reduce((acc, val) => acc + val,
                        0); // Calculate the sum of partTime
                    // let totalVacation = count_length + partTimeSum -
                    //     1; // Add the sum of partTime to count_length
                    let vacation_remain = parseFloat(vacation_default) - partTimeSum;

                    $('#vacation_remain').val(
                        vacation_remain); // Set value of vacation remain
                    $('#vacation_get').val(partTimeSum); // Set value of vacation get
                    console.log(partTime);
                    $('#confirmationModal').modal('hide');
                })

                $('#clearDatesButton').on('click', function() {
                    // Hapus semua tanggal terpilih
                    $('#mdp-demo').multiDatesPicker('resetDates');

                    // Lakukan tindakan tambahan jika diperlukan, seperti mengatur kembali nilai - nilai lainnya.
                    // Misalnya:
                    $('#vacation_get').val(0);
                    $('#vacation_remain').val(0);
                    partTime = [];
                });
            });
            // if necessity
            $('#edo-ani,#edo-ani1').on('change', function() {
                if (this.value == 'Annual Leave') { //** get value from select option Annual Vacation
                    // set attribute
                    $('#choose_date_special').attr('hidden', true); // show choose date special
                    $('#choose_date_annual').attr('hidden', false); // show choose date annual
                    $('#other_vacation').attr('hidden', false); // condition hidden
                    $('.change-date').attr('required', true); // show choose date annual
                    $('#other_reason').attr('required', true); // condition require
                    $('#special_vacation').attr('hidden', true); // condition hidden
                    $('#reason').attr('required', false); // condition required
                    $('#end_date').attr('required', false); // condition atrribute required
                    $('#start_date').attr('required', false); // condition atrribute required
                } else {
                    $('#choose_date_annual').attr('hidden', true); // show choose date annual
                    $('.change-date').attr('required', false); // show choose date annual
                    $('#choose_date_special').attr('hidden', false); // show choose date special
                    $('#end_date').attr('readonly', true); // condition atrribute readonly
                    $('#start_date').attr('readonly', true); // condition atrribute readonly
                    $('#special_vacation').attr('hidden', false); // condition hidden
                    $('#other_vacation').attr('hidden', true); // condition hidden
                    $('#reason').attr('required', true); // condition required
                    $('#other_reason').attr('required', false);
                    $('#reason').on('change', function() { // condition reason on change
                        $('#start_date').attr('readonly', false); // readonly false
                        $('#end_date').val(''); // set value end date empty
                        $('#start_date').val(''); // set value start date empty
                        var days = $(this).find('option:selected').attr(
                            'days'); // get value days
                        // when start date change
                        $('#start_date').on('change', function() {
                            $('#end_date').val(''); // set value end date empty

                            // if not weekend and not holiday end date is normal
                            var start_on_change = $(this).val();
                            var end_on_change = new Date(new Date(start_on_change)
                                .setDate(new Date(start_on_change).getDate() + parseInt(
                                        days) -
                                    1));
                            var dd = String(end_on_change.getDate()).padStart(2, '0');
                            var mm = String(end_on_change.getMonth() + 1).padStart(2,
                                '0'); //January is 0!
                            var yyyy = end_on_change.getFullYear();
                            end_on_change = yyyy + '-' + mm + '-' + dd;
                            $('#end_date').val(
                                end_on_change
                            );

                            //  value vacation get normal
                            var add_date = new Date(new Date(start_on_change)
                                .setDate(new Date(start_on_change).getDate() + parseInt(
                                    days)));
                            var dd = String(add_date.getDate()).padStart(2, '0');
                            var mm = String(add_date.getMonth() + 1).padStart(2,
                                '0'); //January is 0!
                            var yyyy = add_date.getFullYear();
                            add_date = yyyy + '-' + mm + '-' + dd;
                            let diffTime = Math.abs(new Date(add_date) - new Date(
                                start_on_change));
                            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            $('#vacation_get').val(diffDays);

                            // function check range date weekend and holiday
                            var date1 = start_on_change;
                            var date2 = end_on_change;

                            function isWeekend(date1, date2) {
                                var d1 = new Date(date1),
                                    d2 = new Date(date2),
                                    isWeekend = false;
                                while (d1 < d2) {
                                    var day = d1.getDay();
                                    isWeekend = (day == 6) || (day == 0);
                                    if (isWeekend) {
                                        return true;
                                    }
                                    d1.setDate(d1.getDate() + 1);
                                }
                                return false;
                            }
                            if (isWeekend(date1, date2)) {

                                // set value end date if weekend
                                var start_on_change = $(this).val();
                                var end_on_change = new Date(new Date(start_on_change)
                                    .setDate(new Date(start_on_change).getDate() +
                                        parseInt(
                                            days)));
                                var dd = String(end_on_change.getDate()).padStart(2, '0');
                                var mm = String(end_on_change.getMonth() + 1).padStart(2,
                                    '0'); //January is 0!
                                var yyyy = end_on_change.getFullYear();
                                end_on_change = yyyy + '-' + mm + '-' + dd;
                                $('#end_date').val(
                                    end_on_change
                                );

                                // set value vacation get if weekend
                                const diffTime = Math.abs(new Date(end_on_change) -
                                    new Date(
                                        start_on_change));
                                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 *
                                    24));
                                $('#vacation_get').val(diffDays);
                                $('#remark').val('Include Weekend');

                            }
                        })
                    })
                }
                
            });
        </script>
    @endpush
@endsection
