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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }}
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <form class="needs-validation" novalidate method="post" action="{{ url('claim/' . $value->id) }}"
                enctype="multipart/form-data">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                @include('claim._form_finish')
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
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(document).ready(function() {
                // OTHER DIAGNOSE
                // $('#otherDiagnosa').hide();

                // OTHER DIAGNOSE
                $('.reqdiag').attr('required', false);
                $('#cekDiagnosa').click(function() {
                    var checked = $(this).prop('checked');
                    // console.log(checked);
                    if (checked == true) {
                        $('#otherDiagnosa').attr('hidden', false);
                        $('.reqdiag').attr('required', true);
                    } else {
                        $('#otherDiagnosa').attr('hidden', true);
                        $('.reqdiag').attr('required', false);

                    }
                });


                // CHOOSE SUPPLIER
                $('#warrantyAccepted').attr('required', false);
                $('#goodWill').attr('required', false);
                $('#warrantyTo').hide();
                $('#warehouseTo').hide();
                $('#result').on('change', function() {
                    var result = $(this).val();
                    if (result == "CP03 - Waranty Accepted") {
                        $('#warrantyTo').show();
                        $('#warehouseTo').hide();
                        $('#warrantyAccepted').attr('required', true);
                        $('#goodWill').attr('required', false);
                    } else if (result == "CP04 - Good Will") {
                        $('#warrantyTo').hide();
                        $('#warehouseTo').show();
                        $('#warrantyAccepted').attr('required', false);
                        $('#goodWill').attr('required', true);

                    } else {
                        $('#warrantyTo').hide();
                        $('#warehouseTo').hide();
                        $('#warrantyAccepted').attr('required', false);
                        $('#goodWill').attr('required', false);
                    }
                });

                // SUBMIT 1x
                // $('form').submit(function() {
                //     $(this).find('button[type="submit"]').prop('disabled', true);
                // });

                // SIGNATURE
                var sig = $('#sig').signature({
                    syncField: '#signature64',
                    syncFormat: 'PNG',
                    // distance: 0
                });

                // CLEAR SIGNATURE
                $('#clear').click(function(e) {
                    e.preventDefault();
                    sig.signature('clear');
                    $("#signature64").val('');
                });

                // CUSTOMER
                $('#otheCustomer').hide();
                $('#cust').change(function() {
                    var val_cust = $('#cust').val();

                    if (val_cust == 'other') {
                        $('#otheCustomer').show();
                    } else {
                        $('#otheCustomer').hide();

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
        <script>
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
        </script>
    @endpush
@endsection
