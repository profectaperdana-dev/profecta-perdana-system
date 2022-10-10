@extends('layouts.master')
@section('content')
    @push('css')
        <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
            rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">

        <style>
            .kbw-signature {
                width: 100%;
                height: 500px;
            }

            #sig canvas {
                width: 100% !important;
                height: auto;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create Claim
                        {{ $title }}</h6>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">

            <form method="post" action="{{ url('claim/') }}" enctype="multipart/form-data">
                @csrf
                @include('claim._form_early')
            </form>

        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
        <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

        <script>
            $(document).ready(function() {

                // OTHER DIAGNOSE
                $('#cekDiagnosa').click(function() {
                    var checked = $(this).prop('checked');
                    console.log(checked);
                    if (checked == true) {
                        $('#otherDiagnosa').attr('hidden', false);

                    } else {
                        $('#otherDiagnosa').attr('hidden', true);
                    }
                });

                // PRODUCT
                $('#accu_claims').hide();
                $(document).on('change', '#product_id', function() {
                    var material = $(this).find('option:selected').attr('data-material');
                    var type_material = $(this).find('option:selected').attr('data-type_material');
                    var parent_material = $(this).find('option:selected').attr('data-parent_material');

                    $('#material').val(material);
                    $('#type_material').val(type_material);
                    $('#parent_material').val(parent_material);

                    if (parent_material == 'Battery') {
                        $('#accu_claims').show();
                    } else {
                        $('#accu_claims').hide();
                    }
                });

                // CHOOSE CAR
                $("#brand").change(function() {
                    //clear select
                    $("#carType").empty();
                    //set id
                    let host = window.location.host;
                    let brand_id = $("#brand").val();
                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    if (brand_id) {
                        $("#carType").select2({
                            width: "100%",
                            ajax: {
                                type: "GET",
                                url: "/car_brand/select/" + brand_id,
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
                        $("#carType").empty();
                    }
                });


                // SUBMIT 1x
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });

                // SIGNATURE
                var sig = $('#sig').signature({
                    syncField: '#signature64',
                    syncFormat: 'PNG',
                });

                // SELECT2
                $('.receipt').select2({
                    placeholder: '-Select Method-',
                    allowClear: true
                });

                // CLEAR SIGNATURE
                $('#clear').click(function(e) {
                    e.preventDefault();
                    sig.signature('clear');
                    $("#signature64").val('');
                });

                // customer
                $('#other_name').hide();
                $('#other_phone').hide();
                $('#cust').change(function() {
                    var val_cust = $('#cust').val();
                    if (val_cust == 'Other Customer') {
                        $('#other_name').show();
                        $('#other_phone').show();
                    } else if (val_cust != '') {
                        $('#other_name').show();
                        $('#other_phone').show();
                    } else {
                        $('#other_name').hide();
                        $('#other_phone').hide();
                    }
                });

                //    PREVIEW IMAGE
                const imgInput = document.getElementById('inputreference');
                const imgEl = document.getElementById('previewimg');
                const previewLabel = document.getElementById('previewLabel');
                imgInput.addEventListener('change', () => {
                    if (imgInput.files && imgInput.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            imgEl.src = e.target.result;
                            imgEl.removeAttribute('hidden');
                            previewLabel.removeAttribute('hidden');
                        }
                        reader.readAsDataURL(imgInput.files[0]);
                    }
                });
            });
        </script>
    @endpush
@endsection
