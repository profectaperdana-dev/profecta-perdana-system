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
                        <div class="table-responsive">
                            <table class="dataTable display table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th>#</th>
                                        <th>Claim Number</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Battery</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Date</th>
                                        <th>Cost</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(document).ready(function() {
                $(document).on("click", ".modalItem", function(event) {
                    let modal_id = $(this).attr('data-bs-target');
                    $(function() {
                        let validator = $('form.needs-validation').jbvalidator({
                            errorMessage: true,
                            successClass: false,
                            language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                        });
                    });
                    $('.selectMulti').select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                    });
                    $(modal_id).find('.vehicle').change(function() {
                        if ($(this).val() == "Car") {
                            $(modal_id).find('#car').attr('hidden', false);
                            $(modal_id).find('#motor').attr('hidden', true);
                            $(modal_id).find('#other').attr('hidden', true);
                            $(modal_id).find('.car-brand').prop('required', true);
                            $(modal_id).find('.car-type').prop('required', true);
                        } else if ($(this).val() == "Motocycle") {
                            $(modal_id).find('#car').attr('hidden', true);
                            $(modal_id).find('#motor').attr('hidden', false);
                            $(modal_id).find('#other').attr('hidden', true);
                            $(modal_id).find('.motor-brand').prop('required', true);
                            $(modal_id).find('.motor-type').prop('required', true);
                        } else {
                            $(modal_id).find('#car').attr('hidden', true);
                            $(modal_id).find('#motor').attr('hidden', true);
                            $(modal_id).find('#other').attr('hidden', false);
                            $(modal_id).find('.other-brand').prop('required', true);
                        }
                    });
                    $(".car-brand").change(function() {
                        $(".car-type").empty();
                        let car_brand = $(this).val();
                        if (car_brand) {
                            $(".car-type").select2({
                                dropdownParent: modal_id,
                                placeholder: 'Select an option',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%',
                                ajax: {
                                    type: "GET",
                                    url: "/car_brand/select/" + car_brand,
                                    data: function(params) {
                                        return {
                                            _token: `{{ csrf_token() }}`,
                                            q: params.term, // search term
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.car_type,
                                                    id: item.id,
                                                };
                                            }),
                                        };
                                    },
                                },
                            });
                        } else {
                            $(".car-type").empty();
                        }
                    });

                    $(".motor-brand").change(function() {
                        $(".motor-type").empty();
                        let motor_brand = $(this).val();
                        if (motor_brand) {
                            $(".motor-type").select2({
                                dropdownParent: modal_id,
                                placeholder: 'Select an option',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%',
                                ajax: {
                                    type: "GET",
                                    url: "/motocycle_brand/select/" + motor_brand,
                                    data: function(params) {
                                        return {
                                            _token: `{{ csrf_token() }}`,
                                            q: params.term, // search term
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.name_type,
                                                    id: item.id,
                                                };
                                            }),
                                        };
                                    },
                                },
                            });
                        } else {
                            $(".motor-type").empty();
                        }
                    });
                    $(".car-type").change(function() {
                        if ($(this).val() == 'other_car') {
                            $("#other-car").attr('hidden', false);
                        } else {
                            $("#other-car").attr('hidden', true);
                            $("#other_car_input").val(null);
                        }
                    });
                    $(".motor-type").change(function() {
                        if ($(this).val() == 'other_motor') {
                            $("#other-motor").attr('hidden', false);
                        } else {
                            $("#other-motor").attr('hidden', true);
                            $("#other_motor_input").val(null);
                        }
                    });
                });

                var table = $('.dataTable').DataTable({
                    "responsive": true,
                    "language": {
                        "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                    },
                    "lengthChange": false,
                    "bPaginate": false, // disable pagination
                    "bLengthChange": false, // disable show entries dropdown
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false, // disable automatic column width
                    fixedColumns: {
                        leftColumns: 0,
                        rightColumns: 0
                    },
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    "fixedHeader": true,
                    processing: true,
                    serverSide: true,
                    pageLength: -1,
                    ajax: "{{ url('claim/final/check') }}",
                    columns: [{
                            className: 'dtr-control',
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        }, {
                            className: 'text-end fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                        },
                        {
                            data: 'claim_number',
                            name: 'claim_number',
                        },
                        {
                            data: 'sub_name',
                            name: 'sub_name',
                        },
                        {
                            data: 'product_code',
                            name: 'product_code',

                        },
                        {
                            data: 'product_id',
                            name: 'product_id',

                        },
                        {
                            data: 'customer_id',
                            name: 'customer_id',
                        },

                        {
                            data: 'sub_phone',
                            name: 'sub_phone',
                        },
                        {
                            data: 'claim_date',
                            name: 'claim_date',
                        },
                        {
                            className: 'fw-bold',
                            data: 'cost',
                            name: 'cost',
                        },

                    ],
                    responsive: {
                        details: {
                            type: 'column'
                        }
                    },
                    drawCallback: function(settings) {
                        // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                        $('#thisModal').html('');
                        $('.currentModal').each(function(){
                            let currentModal = $(this).html();
                            $(this).html('');
                            $('#thisModal').append(currentModal);
                        });
                        
                        // console.log($('#currentModal').html());
                        // Lakukan tindakan lain yang Anda inginkan di sini
                    },
                });
                $(document).on('click', '.btnSubmit', function(e) {
                    $('.btnSubmit').attr('disabled', true);
                    $('.btnSubmit').html(
                        `<i class="fa fa-spinner fa-spin"></i> Please Wait...`
                    );
                })
                // $(document).on('submit', '.processClaim', function(e) {
                //     console.log('test');
                //     e.preventDefault();
                //     let id = $(this).attr('id');
                //     console.log(id);
                //     let form = $(this);
                //     let data = form.serialize();
                //     let url = `{{ url('claim/${id}/create/prior') }}`;
                //     $.ajax({
                //         url: `{{ url('claim/${id}/update/initial') }}`,
                //         type: 'POST',
                //         dataType: 'json',
                //         data: data,
                //         beforeSend: function() {
                //             $('.btnSubmit').attr('disabled', true);
                //             $('.btnSubmit').html(
                //                 `<i class="fa fa-spinner fa-spin"></i> Processing...`
                //             );
                //         },
                //         success: function(data) {
                //             swal("Success !", data.message, "success", {
                //                 button: "Close",
                //             });
                //             $('.dataTable').DataTable().ajax.reload();
                //             $('.hideModalEdit').click();
                //             window.location.href = url;

                //         },
                //         error: function(xhr, status, error) {
                //             swal("Error !", error, "error", {
                //                 button: "Close",
                //             });
                //         },
                //         complete: function() { // menambahkan fungsi complete untuk mengubah tampilan tombol kembali ke tampilan semula
                //             $('.btnSubmit').attr('disabled', false);
                //             $('.btnSubmit').html('Process');
                //         }
                //     });
                // });
            });
        </script>
    @endpush
@endsection
