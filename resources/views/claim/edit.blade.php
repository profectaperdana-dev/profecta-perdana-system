@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href={{ url('css/jquery.signature.css') }}>
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
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <form class="needs-validation finishClaim" novalidate method="post"
                action="{{ url('claim/' . $value->id . '/store/final') }}" enctype="multipart/form-data">
                @method('POST')
                @csrf
                @include('claim._form_finish')
            </form>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/js/jquery.signature.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(function() {
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: false,
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
            });
            $(document).ready(function() {
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
            $(document).on('submit', '.finishClaim', function(event) {
                event.preventDefault();
                var form_data = new FormData($(this)[0]);
                var formElement = $(this);
                var id = `{{ $value->id }}`;
                let action = $(this).attr('action');
                console.log(action);
                let url = `{{ url('history_claim') }}`;
                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: form_data,
                    processData: false, // prevent jQuery from processing the data
                    contentType: false, // prevent jQuery from setting the content type
                    beforeSend: function() {
                        $('.btnSubmit').attr('disabled', true);
                        $('.btnSubmit').html(
                            `<i class="fa fa-spinner fa-spin"></i> Processing...`
                        );
                    },
                    success: function(response) {
                        console.log(form_data);
                        swal("Success !", response.message, "success", {
                            button: "Close",
                        });
                        $('#cust').val(null).trigger('change');
                        formElement[0].reset();
                        window.location.href = url;

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal("Error !", 'Error : Please call your Most Valuable IT Team. ', "error", {
                            button: "Close",
                        });
                    },
                    complete: function() { // menambahkan fungsi complete untuk mengubah tampilan tombol kembali ke tampilan semula
                        $('.btnSubmit').attr('disabled', false);
                        $('.btnSubmit').html('Save');
                    }
                });
            })
        </script>
    @endpush
@endsection
