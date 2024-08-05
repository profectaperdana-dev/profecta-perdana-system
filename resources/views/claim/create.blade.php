@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href={{ url('css/jquery.signature.css') }}>
        @include('report.style')
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
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        {{--  action="{{ url('claim/' . $data->id . '/store/prior') }} --}}

        <form class="needs-validation priorClaim" novalidate method="POST" enctype="multipart/form-data">
            @csrf
            @include('claim._form_early')

        </form>

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

                // SIGNATURE
                var sig = $('#sig').signature({
                    syncField: '#signature64',
                    syncFormat: 'PNG',
                });
                // CLEAR SIGNATURE
                $('#clear').click(function(e) {
                    e.preventDefault();
                    sig.signature('clear');
                    $("#signature64").val('');
                });
            });
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                $('.selectMulti,.warehouse').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                $('.cost').on('input', function(event) {
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
                    input = input.replace(/[\D\s\._\-]+/g, "");
                    input = input ? parseInt(input, 10) : 0;
                    $this.val(function() {
                        return input.toLocaleString("EN-en");
                    });
                    $this.next().val(input);
                });
                let warehouse = $('.warehouse').val();
                $('.warehouse').change(function() {
                    warehouse = $(this).val();
                });

                $('.batLend').select2({
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    placeholder: 'Select Loaned Battery',
                    ajax: {
                        type: "GET",
                        url: "/claim/selectProduct",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                                w: warehouse
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_sub_material + " " +
                                            item.type_name + " " + item.nama_barang + " " +
                                            "(" + item.stock + ")",
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });
            });
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                $('#edo-ani,#edo-ani1').on('change', function() {
                    let lended = $(this).val();
                    if (lended == 'Annual Leave') {
                        $('#lended').attr('hidden', false);
                        $('.warehouse').attr('required', true);
                        $('.batLend').attr('required', true);
                    } else {
                        $('#lended').attr('hidden', true);
                        $('.warehouse').attr('required', false);
                        $('.batLend').attr('required', false);
                    }
                })


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


            $(document).on('submit', '.priorClaim', function(event) {
                // console.log('ok');
                event.preventDefault();
                var form_data = new FormData($(this)[0]);
                var formElement = $(this);
                var id = `{{ $data->id }}`;
                let url = `{{ url('claim/final/check') }}`;
                $.ajax({
                    url: `{{ url('claim/${id}/store/prior') }}`,
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
