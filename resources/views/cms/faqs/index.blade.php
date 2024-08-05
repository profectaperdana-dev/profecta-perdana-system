@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
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
                        <div class="row " id="grandparentfaq">
                            @if (sizeof($all_faq) > 0)
                                @foreach ($all_faq as $item)
                                    <div class="col-12 col-md-6 parentfaq">
                                        <div class="card">
                                            <div class="card-body">
                                                <form action="" class="editformfaq" data-id="{{ $item->id }}">
                                                    <div class="d-flex flex-row-reverse bd-highlight">
                                                        <div class="bd-highlight buttonfaq">
                                                            <a href="javascript:void(0)"
                                                                class="text-danger fs-5 px-3 remfaq">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                        <div class="bg-light rounded-pill py-1 px-3 text-dark confirmationfaq"
                                                            style="display: none">

                                                            Are you sure
                                                            want to
                                                            delete?
                                                            <br>
                                                            <span class=" me-3">
                                                                <a href="javascript:void(0)" class="text-danger yesdel"
                                                                    data-id={{ $item->id }}>
                                                                    <i class="fa-solid fa-check"></i>
                                                                    Yes
                                                                </a>
                                                            </span>
                                                            <span class="">
                                                                <a href="javascript:void(0)"
                                                                    class="text-secondary canceldel">
                                                                    <i class="fa-solid fa-x"></i>
                                                                    Cancel
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1 changefaq" style="display: none">
                                                            <button type="submit" class="text-warning editfaq"
                                                                style="background: none; border:none">

                                                                <div><i class="fa-solid fa-pen-to-square"></i>
                                                                    Save change</div>

                                                            </button>
                                                        </div>

                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Order</label>
                                                        <input type="number" class="form-control" name="sort_number"
                                                            value="{{ $item->sort_number }}"
                                                            placeholder="Enter the order data...">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Question</label>
                                                        <input type="text" class="form-control" name="question"
                                                            value="{{ $item->question }}" placeholder="Enter question...">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1"
                                                            class="form-label">Answer</label>
                                                        <textarea class="form-control" name="answer" placeholder="Enter answer..." rows="3">{{ $item->answer }}</textarea>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-12 col-md-6 parentfaq">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="" class="formfaq">
                                                <div class="d-flex flex-row-reverse bd-highlight">
                                                    <div class="bd-highlight buttonfaq">
                                                        <div class="spinner-border text-success" style="display: none"
                                                            role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <button type="submit" class="text-success fs-5 px-3 addfaq"
                                                            style="background: none; border:none">

                                                            <div><i class="fa-solid fa-plus"></i>
                                                                Add</div>

                                                        </button>

                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1"
                                                        class="form-label">Question</label>
                                                    <input type="text" required class="form-control" name="question"
                                                        placeholder="Enter question...">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Answer</label>
                                                    <textarea class="form-control" required name="answer" placeholder="Enter answer..." rows="3"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-12 col-md-6 parentfaq">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="" class="formfaq">
                                                @csrf
                                                <div class="d-flex flex-row-reverse bd-highlight">
                                                    <div class="bd-highlight buttonfaq">
                                                        <div class="spinner-border text-success" style="display: none"
                                                            role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <button type="submit" class="text-success fs-5 px-3 addfaq"
                                                            style="background: none; border:none">

                                                            <div><i class="fa-solid fa-plus"></i>
                                                                Add</div>

                                                        </button>

                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1"
                                                        class="form-label">Question</label>
                                                    <input type="text" required class="form-control" name="question"
                                                        placeholder="Enter question...">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Answer</label>
                                                    <textarea class="form-control" required name="answer" placeholder="Enter answer..." rows="3"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="statustoast">Bootstrap</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" onclick="$('#liveToast').hide()"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body" id="messagetoast">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>


        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                //Show Toast function
                function showToast(status = "", message = "") {
                    $('#statustoast').text(status);
                    $('#messagetoast').text(message);
                    $('#liveToast').show();
                }

                // Fungsi untuk membandingkan dua array serialize
                function compareSerializeArrays(arr1, arr2) {
                    // Ubah array menjadi string JSON agar mudah dibandingkan
                    let json1 = JSON.stringify(arr1);
                    let json2 = JSON.stringify(arr2);

                    // Bandingkan string JSON
                    return json1 === json2;
                }

                // Delete Handler
                $(document).on('click', '.remfaq', function() {
                    let this_remfaq = $(this);
                    this_remfaq.toggle("slow");
                    let confirmationfaq = this_remfaq.parent().siblings('.confirmationfaq');
                    confirmationfaq.toggle("slow");

                    //Cancel Handler
                    confirmationfaq.find('.canceldel').off("click");
                    confirmationfaq.find('.canceldel').on('click', function() {
                        this_remfaq.toggle("slow");
                        confirmationfaq.toggle("slow");
                    });

                    //Submit delete Handler
                    confirmationfaq.find('.yesdel').off('click');
                    confirmationfaq.find('.yesdel').on('click', function() {
                        let id = $(this).attr('data-id');
                        let token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: `api/faq/${id}/delete`,
                            type: 'DELETE',
                            cache: false,
                            data: {
                                "_token": `{{ csrf_token() }}`
                            },
                            success: function(res) {
                                showToast("Success", res.message);
                                //Remove HTML Element
                                this_remfaq.closest('.parentfaq').remove();
                            },
                            error: function(err) {
                                showToast("Failed", err.responseJSON.message);
                            }
                        });
                    });

                });

                //Edit Handler
                let old_value = '';
                let old_el = '';
                $(document).on('focus', '.editformfaq input, .editformfaq textarea', function() {
                    let this_input = $(this);
                    let new_el = this_input.closest('.editformfaq').attr('data-id');

                    if (old_value.length <= 0 || (old_el.length <= 0 || old_el != new_el)) {
                        old_value = this_input.closest('.editformfaq').serializeArray();
                        old_el = new_el;
                    }

                    let new_value = old_value;

                    //Change Value Handler
                    this_input.off('input');
                    this_input.on('input', function() {
                        new_value = this_input.closest('.editformfaq').serializeArray();
                        if (!compareSerializeArrays(old_value, new_value)) {
                            this_input.closest('.editformfaq').find('.changefaq').show();
                        } else {
                            this_input.closest('.editformfaq').find('.changefaq').hide();
                        }
                    });

                    this_input.closest('.editformfaq').off('submit');
                    this_input.closest('.editformfaq').on('submit', function(e) {
                        e.preventDefault();
                        let csrfToken = '{{ csrf_token() }}';
                        new_value.push({
                            name: "_token",
                            value: csrfToken
                        });
                        $.ajax({
                            url: `api/faq/${new_el}/edit`,
                            type: 'PUT',
                            cache: false,
                            data: new_value,
                            success: function(res) {
                                // console.log(res);
                                let message = '';
                                //Show toast as alert
                                if (res.status != 200) {

                                    for (const key in res.message) {
                                        if (res.message.hasOwnProperty(key)) {
                                            message += '\n' + res.message[key];
                                        }
                                    }
                                    showToast("Failed", message);
                                    return false;
                                }
                                message = res.message;
                                this_input.closest('.editformfaq').find('.changefaq')
                                    .hide();
                                showToast("Success", message)
                            },
                            error: function(err) {
                                //Show toast as alert

                                this_form.find('.spinner-border').toggle();
                                this_form.find('.addfaq').toggle("slow");
                                showToast("Failed", err.responseJSON.message)

                            }
                        });
                    });

                });

                //Create Handler
                $(document).on('submit', '.formfaq', function(e) {
                    e.preventDefault();
                    let this_form = $(this);

                    //hide button submit and show spinner
                    this_form.find('.addfaq').toggle();
                    this_form.find('.spinner-border').toggle();
                    // Dapatkan token CSRF dari blade template Laravel
                    let csrfToken = '{{ csrf_token() }}';

                    // Buat objek data yang berisi data formulir dan token CSRF
                    let formData = this_form.serializeArray();
                    formData.push({
                        name: "_token",
                        value: csrfToken
                    });

                    //AJAX Post
                    $.ajax({
                        url: `api/faq/store`,
                        type: 'POST',
                        cache: false,
                        data: formData,
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            this_form.find('.spinner-border').toggle();
                            this_form.find('.addfaq').toggle();
                            //Show toast as alert
                            if (res.status != 200) {

                                for (const key in res.message) {
                                    if (res.message.hasOwnProperty(key)) {
                                        message += '\n' + res.message[key];
                                    }
                                }
                                showToast("Failed", message);
                                return false;
                            }
                            message = res.message;
                            showToast("Success", message);

                            //Create new Add FAQ Card
                            let faq_element = this_form.closest('.parentfaq').clone();
                            // console.log(faq_element);
                            faq_element.find('input, textarea').val('');
                            $('#grandparentfaq').append(faq_element);

                            //change submit button to remove button
                            let faq_rembutton = `<a href="javascript:void(0)"
                            class="text-danger fs-5 px-3 remfaq"><i
                                class="fa-solid fa-trash-can"></i></a>`;

                            let faq_confirmation = `<div class="bg-light rounded-pill py-1 px-3 text-dark confirmationfaq"
                                                            style="display: none">

                                                            Are you sure
                                                            want to
                                                            delete?
                                                            <br>
                                                            <span class=" me-3">
                                                                <a href="javascript:void(0)" class="text-danger yesdel"
                                                                    data-id={{ $item->id }}>
                                                                    <i class="fa-solid fa-check"></i>
                                                                    Yes
                                                                </a>
                                                            </span>
                                                            <span class="">
                                                                <a href="javascript:void(0)"
                                                                    class="text-secondary canceldel">
                                                                    <i class="fa-solid fa-x"></i>
                                                                    Cancel
                                                                </a>
                                                            </span>
                                                        </div>

                                                        <div class="flex-grow-1 changefaq" style="display: none">
                                                            <button type="submit" class="text-warning editfaq"
                                                                style="background: none; border:none">

                                                                <div><i class="fa-solid fa-pen-to-square"></i>
                                                                    Save change</div>

                                                            </button>
                                                        </div>`;

                            this_form.find('.buttonfaq').html(faq_rembutton);
                            this_form.find('.buttonfaq').parent().append(faq_confirmation);

                            //Change class
                            this_form.removeClass('formfaq');
                            this_form.addClass('editformfaq');
                            this_form.attr("data-id", res.data.id);
                        },
                        error: function(err) {
                            //Show toast as alert
                            console.log(err);
                            this_form.find('.spinner-border').toggle();
                            this_form.find('.addfaq').toggle("slow");
                            showToast("Failed", err.responseJSON.message)

                        }
                    });
                });

            });

            $(function() {
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: false,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
            });
        </script>
    @endpush
@endsection
