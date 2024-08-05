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
        <div class="row">
            <div class="card shadow">
                <div class="card-body">
                    <form class="needs-validation initialClaim" novalidate>
                        @csrf
                        <div class="row form-group col-md-12" style="color: black !important">
                            <div class="col-lg-12 col-md-12 form-group">
                                <label>Customer</label>
                                <select name="customer_id" id="cust" required class="form-select selectCustomer"
                                    multiple>
                                    <option value="Other Customer">Other Customer</option>
                                    @foreach ($customer as $row)
                                        <option value="{{ $row->id }}">
                                            {{ $row->code_cust }} - {{ $row->name_cust }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div hidden id="other_name" class="col-lg-4 col-md-12 mb-3">
                                <label for="">Name</label>
                                <input name="sub_name" type="text" required class="form-control text-capitalize fw-bold"
                                    placeholder="Enter Name" aria-label="Username" autocomplete="off">
                            </div>
                            <div hidden id="other_phone" class="col-lg-4 col-md-12 mb-3">
                                <label for="">Phone Number</label>
                                <input name="sub_phone" number data-v-min-length="9" data-v-max-length="13" type="number"
                                    required class="form-control fw-bold " placeholder="Enter Phone" aria-label="Server"
                                    autocomplete="off">
                            </div>
                            <div hidden id="other_email" class="col-lg-4 col-md-12 mb-3">
                                <label for="">Email</label>
                                <input name="email" type="email" class="form-control fw-bold "
                                    placeholder="Email is Optional" aria-label="Server" autocomplete="off">
                                <small class="text-primary">*e-mail is optional</small>
                            </div>
                            <div hidden id="address" class="col-lg-12 col-md-12 mb-3">
                                <label for="">Address</label>
                                <textarea name="alamat" required class="form-control" id="" cols="30" rows="2" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="reset" class="btn btn-warning">Reset</button>
                                <button type="submit" class="btn btn-primary btnSubmit">Save </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(function() {
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: false,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
                $('.selectCustomer').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });
                $('#cust').change(function() {
                    var val_cust = $('#cust').val();
                    var showFields = (val_cust == 'Other Customer' || val_cust != '');

                    $('#other_name').attr('hidden', !showFields);
                    $('#other_phone').attr('hidden', !showFields);
                    $('#other_email').attr('hidden', !showFields);
                    $('#address').attr('hidden', !showFields);
                });
                $('#inline-1').change(function() {
                    if ($(this).is(":checked")) {
                        console.log('checked');
                        $('#label-text').text('Chargeable (Rp 50,000)');
                    } else {
                        $('#label-text').html('Free of charge <s style="color:red;">(Rp 50,000)</s>');
                    }
                });
            });
            //Save with ajax

            $(document).on('submit', '.initialClaim', function(event) {
                console.log('test');
                event.preventDefault();
                var form_data = $(this).serialize();
                var formElement = $(this);
                var url = `{{ url('claim') }}`;
                $.ajax({
                    url: "{{ url('claim/store/initial') }}",
                    type: "POST",
                    dataType: "json",
                    data: form_data,
                    beforeSend: function() {
                        $('.btnSubmit').attr('disabled', true);
                        $('.btnSubmit').html(
                            `<i class="fa fa-spinner fa-spin"></i> Processing...`
                        );
                    },
                    success: function(response) {
                        console.log(response);
                        swal("Success !", "data has been saved", "success", {
                            button: "Close",
                        });
                        $('#cust').val(null).trigger('change');
                        formElement[0].reset();
                        window.location.replace(url);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error:', textStatus, errorThrown);
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
