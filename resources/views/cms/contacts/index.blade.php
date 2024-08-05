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
                        <div class="row ">
                            <h4>Choose the Area</h4>

                            <div class="card mb-3 col-lg-8 col-12 ">

                                <div class="card-body">

                                    <div class="form-group">
                                        <label class=" row justify-content-between">
                                            <div class="col-4">Area</div>
                                            <div class="col-6 text-end">
                                                <a href="javascript:void(0)" class="me-1 text-success" id="showaddarea"><i
                                                        class="fa-solid fa-plus"></i>
                                                    Add</a>
                                                <a href="javascript:void(0)" class="text-warning" id="showlistarea"><i
                                                        class="fa-solid fa-clipboard-list"></i>
                                                    List</a>
                                            </div>
                                        </label>
                                        <select name="area_id" id="area" required class="form-control selectMulti"
                                            multiple>
                                            @foreach ($all_areas as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                            {{-- <option value="1">coba</option> --}}
                                        </select>
                                    </div>
                                    </p>

                                </div>

                            </div>

                            <div class="card mb-3 col-lg-4 col-12 p-2" id="addareaparent" style="display: none">
                                <div class="my-auto">
                                    <h5>Add Area</h5>
                                    <form action="" id="addareaform">
                                        <div class="my-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Enter the area name...">
                                        </div>
                                        <button class="btn btn-sm btn-success addareabtn" type="submit">Add</button>
                                        <div class="spinner-border text-success" style="display: none" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <button class="btn btn-sm btn-secondary" type="button"
                                            onclick="$('#addareaparent').hide('slow')">close</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-3 col-lg-4 col-12 p-2" id="listareaparent" style="display: none">
                                <h5>Area List</h5>
                                <div class="overflow-auto" style="max-height: 250px">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="areatable">
                                            @foreach ($all_areas as $item)
                                                <tr class="parentarea" data-iteration="{{ $loop->index + 1 }}"
                                                    data-id="{{ $item->id }}">

                                                    <th scope="row">{{ $loop->index + 1 }}</th>

                                                    <td>
                                                        <form action="" class="listareaform">
                                                            <input type="text" class="form-control listareainput"
                                                                name="name" value="{{ $item->name }}">
                                                            <button type="submit" class="mt-2 text-warning editarea"
                                                                style="background: none; border:none;display:none">
                                                                <i class="fa-solid fa-pen-to-square"></i> Save change
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="text-danger remarea"
                                                            style="background: none; border:none;">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                        <div class="confirmationarea" style="display: none">
                                                            <span class=" me-3">
                                                                <a href="javascript:void(0)" class="text-danger yesdel">
                                                                    <i class="fa-solid fa-check"></i>

                                                                </a>
                                                            </span>
                                                            <span class="">
                                                                <a href="javascript:void(0)"
                                                                    class="text-secondary canceldel">
                                                                    <i class="fa-solid fa-x"></i>

                                                                </a>
                                                            </span>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <button class="col-4 btn btn-sm btn-secondary mt-3" type="button"
                                    onclick="$('#listareaparent').hide('slow')">close</button>
                            </div>
                        </div>

                        <div class="mt-5" id="contactparent" style="display: none">
                            <h4 id="contacttitle"></h4>
                            <form id="contactform" class="mt-3">
                                <div class="row contactrow">
                                    <input type="hidden" class="form-control" id="area_id" name="area_id">
                                    <div class="col-lg-4 col-12 mb-4">
                                        <label class="form-label">Phone Number 1</label>
                                        <input type="text" class="form-control" id="phone_1"
                                            placeholder="Enter the phone number..." name="phone_1">
                                    </div>
                                    <div class="col-lg-4 col-12 mb-4">
                                        <label class="form-label">Phone Number 2</label>
                                        <input type="text" class="form-control" id="phone_2"
                                            placeholder="Enter the phone number..." name="phone_2">
                                    </div>
                                    <div class="col-lg-4 col-12 mb-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email"
                                            placeholder="Enter the email..." name="email">
                                    </div>
                                    <div class="col-lg-6 col-12 mb-4">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" id="address" cols="2" class="form-control" placeholder="Enter the address..."></textarea>
                                    </div>
                                    <div class="col-lg-6 col-12 mb-4">
                                        <label class="form-label">Embedded Maps Link</label>
                                        <input type="text" class="form-control" id="embedded_maps"
                                            placeholder="Enter the embedded maps link..." name="embedded_maps">
                                        <div class="form-text">
                                            Go to "Google Maps" <i class="fa-solid fa-arrow-right"></i> Select the location
                                            <i class="fa-solid fa-arrow-right"></i> Choose "Share" <i
                                                class="fa-solid fa-arrow-right"></i> Choose "Embed a
                                            map" <i class="fa-solid fa-arrow-right"></i> Copy HTML
                                        </div>
                                    </div>
                                    <div class="col-12 border border-2 border-bottom border-black mb-4">

                                    </div>

                                    <div class="col-lg-6 col-12 mb-4">
                                        <label class="form-label">Shopee URL</label>
                                        <input type="text" class="form-control" id="shopee_url"
                                            placeholder="Enter the shopee URL..." name="shopee_url">
                                    </div>
                                    <div class="col-lg-6 col-12 mb-4">
                                        <label class="form-label">Tokopedia URL</label>
                                        <input type="text" class="form-control" id="tokopedia_url"
                                            placeholder="Enter the tokopedia URL..." name="tokopedia_url">
                                    </div>
                                    <div class="col-lg-6 col-12 mb-4">
                                        <label class="form-label">Instagram URL</label>
                                        <input type="text" class="form-control" id="instagram_url"
                                            placeholder="Enter the instagram URL..." name="instagram_url">
                                    </div>
                                    <div class="col-lg-6 col-12 mb-4">
                                        <label class="form-label">Facebook URL</label>
                                        <input type="text" class="form-control" id="facebook_url"
                                            placeholder="Enter the facebook URL..." name="facebook_url">
                                    </div>
                                    <div class="col-lg-6 col-12 mb-4">
                                        <label class="form-label">Tiktok URL</label>
                                        <input type="text" class="form-control" id="tiktok_url"
                                            placeholder="Enter the tiktok URL..." name="tiktok_url">
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <button type="submit" id="btncontactform"
                                                class="btn btn-primary">Save</button>
                                        </div>

                                    </div>

                                </div>
                            </form>

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

    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content ">

                <div class="modal-body bg-success  p-0">
                    <img src="{{ asset('images/no-image.png') }}" class="img-fluid w-100" alt="...">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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

                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
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

                //Button show add category
                $(document).on('click', '#showaddarea', function() {
                    let this_btn = $(this);
                    $('#addareaparent').show();
                    $('#listareaparent').hide();
                });

                //Button show list area
                $(document).on('click', '#showlistarea', function() {
                    let this_btn = $(this);
                    $('#addareaparent').hide();
                    $('#listareaparent').show();
                });

                // CATEGORY CRUD HANDLER START

                // Category Delete Handler
                $(document).on('click', '.remarea', function() {
                    let this_remarea = $(this);
                    this_remarea.toggle();
                    let confirmationarea = this_remarea.siblings('.confirmationarea');
                    confirmationarea.toggle();

                    //Cancel Handler
                    confirmationarea.find('.canceldel').off("click");
                    confirmationarea.find('.canceldel').on('click', function() {
                        this_remarea.toggle();
                        confirmationarea.toggle();
                    });

                    //Submit area delete Handler
                    confirmationarea.find('.yesdel').off('click');
                    confirmationarea.find('.yesdel').on('click', function() {
                        let id = $(this).closest('.parentarea').attr('data-id');
                        let token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: `api/area/${id}/delete`,
                            type: 'DELETE',
                            cache: false,
                            data: {
                                "_token": `{{ csrf_token() }}`
                            },
                            success: function(res) {
                                showToast("Success", res.message);
                                //Remove HTML Element
                                this_remarea.closest('.parentarea').remove();
                                $('#area').find(`option[value="${id}"]`).remove();
                            },
                            error: function(err) {
                                showToast("Failed", err.responseJSON.message);
                            }
                        });
                    });

                });

                //Area Edit Handler

                $(document).on('focus', '.listareainput', function() {
                    let this_input = $(this);
                    let old_value = this_input.val();


                    //Change Value Handler
                    this_input.off('input');
                    this_input.on('input', function() {
                        let new_value = $(this).val();

                        if (new_value != old_value) {
                            this_input.siblings('.editarea').show();
                        } else {
                            this_input.siblings('.editarea').hide();
                        }
                    });
                    // console.log(this_input.parents('.listareaform').html());
                    this_input.closest('.listareaform').off('submit');
                    this_input.closest('.listareaform').on('submit', function(e) {
                        e.preventDefault();
                        let csrfToken = '{{ csrf_token() }}';
                        let id = this_input.closest('.parentarea').attr('data-id');
                        let formData = $(this).serializeArray();
                        formData.push({
                            name: "_token",
                            value: csrfToken
                        });
                        $.ajax({
                            url: `api/area/${id}/edit`,
                            type: 'PUT',
                            cache: false,
                            data: formData,
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
                                this_input.siblings('.editarea').hide();
                                $('#area').find(`option[value="${id}"]`).val(res.data
                                    .id);
                                $('#area').find(`option[value="${id}"]`).text(res.data
                                    .name);
                                showToast("Success", message)
                            },
                            error: function(err) {
                                //Show toast as alert

                                // this_form.find('.spinner-border').toggle();
                                // this_form.find('.addfaq').toggle("slow");
                                showToast("Failed", err.responseJSON.message)

                            }
                        });
                    });

                });

                //Area Create Handler
                $(document).on('submit', '#addareaform', function(e) {
                    e.preventDefault();
                    let this_form = $(this);

                    //hide button submit and show spinner
                    this_form.find('button[type="submit"]').toggle();
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
                        url: `api/area/store`,
                        type: 'POST',
                        cache: false,
                        data: formData,
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            this_form.find('.spinner-border').toggle();
                            this_form.find('button[type="submit"]').toggle();
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

                            //Create new Gallery Category
                            let new_area =
                                `<option value="${res.data.id}">${res.data.name}</option>`;
                            $('#area').append(new_area);
                            this_form.find('input').val('');

                            //add to table
                            let data_iteration = $('#areatable').find('tr').last().attr(
                                'data-iteration');
                            // console.log(data_iteration);
                            let new_row_area = `<tr class="parentarea" data-iteration="${parseInt(data_iteration) + 1}" data-id="${res.data.id}">
                                            <th scope="row">${parseInt(data_iteration) + 1}</th>
                                            <form action="" class="listareaform">
                                                <td><input type="text" class="form-control listareainput" name="name"
                                                        value="${res.data.name}"></td>
                                                <td class="text-center">
                                                    <button type="submit" class="me-2 text-warning editarea"
                                                        style="background: none; border:none;display:none">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="text-danger remarea"
                                                        style="background: none; border:none">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                    <div class="confirmationarea" style="display: none">
                                                        <span class=" me-3">
                                                            <a href="javascript:void(0)"
                                                                class="text-danger yesdel">
                                                                <i class="fa-solid fa-check"></i>

                                                            </a>
                                                        </span>
                                                        <span class="">
                                                            <a href="javascript:void(0)"
                                                                class="text-secondary canceldel">
                                                                <i class="fa-solid fa-x"></i>

                                                            </a>
                                                        </span>
                                                    </div>
                                                </td>
                                            </form>
                                        </tr>;`

                            $('#areatable').append(new_row_area);

                        },
                        error: function(err) {
                            //Show toast as alert
                            console.log(err);
                            this_form.find('.spinner-border').toggle();
                            this_form.find('.addareabtn').toggle("slow");
                            showToast("Failed", err.responseJSON.message)

                        }
                    });
                });

                // Area CRUD HANDLER END

                // Contact CRUD HANDLER START

                // Change Area Handler
                $(document).on('change', '#area', function() {
                    let this_area = $(this);
                    if (this_area.val() == null || this_area.val() == '') {
                        $('#contactparent').hide();
                    } else {
                        $('#contacttitle').text(`${this_area.find('option:selected').text()} Contact Data`)
                        $.ajax({
                            url: `api/contact/getDataByArea`,
                            type: 'GET',
                            cache: false,
                            data: {
                                "area_id": this_area.val()
                            },
                            success: function(res) {
                                // console.log(res);
                                $('#phone_1').val('');
                                $('#phone_2').val('');
                                $('#email').val('');
                                $('#address').val('');
                                $('#embedded_maps').val('');
                                $('#shopee_url').val('');
                                $('#tokopedia_url').val('');
                                $('#instagram_url').val('');
                                $('#facebook_url').val('');
                                $('#tiktok_url').val('');

                                if (res.data) {
                                    $('#phone_1').val(res.data.phone_1);
                                    $('#phone_2').val(res.data.phone_2);
                                    $('#email').val(res.data.email);
                                    $('#address').val(res.data.address);
                                    $('#embedded_maps').val(res.data.embedded_maps);
                                    $('#shopee_url').val(res.data.shopee_url);
                                    $('#tokopedia_url').val(res.data.tokopedia_url);
                                    $('#instagram_url').val(res.data.instagram_url);
                                    $('#facebook_url').val(res.data.facebook_url);
                                    $('#tiktok_url').val(res.data.tiktok_url);
                                }
                            }
                        });
                        $('#area_id').val(this_area.val());
                        $('#contactparent').show();
                    }

                });

                //Delete Handler
                $(document).on('click', '.remgallery', function() {
                    let this_remgallery = $(this);
                    this_remgallery.closest('.buttongallery').hide();
                    let confirmationgallery = this_remgallery.closest('.buttongallery').siblings(
                        '.confirmationgallery');
                    confirmationgallery.show("slow");

                    //Cancel Handler
                    confirmationgallery.find('.cancelrem').off("click");
                    confirmationgallery.find('.cancelrem').on('click', function() {
                        confirmationgallery.hide();
                        this_remgallery.closest('.buttongallery').show("slow");

                    });

                    //Submit Gallery delete Handler
                    confirmationgallery.find('.yesrem').off('click');
                    confirmationgallery.find('.yesrem').on('click', function() {
                        let id = $(this).closest('.galleryrow').attr('data-id');
                        let token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: `api/gallery/${id}/delete`,
                            type: 'DELETE',
                            cache: false,
                            data: {
                                "_token": `{{ csrf_token() }}`
                            },
                            success: function(res) {
                                showToast("Success", res.message);
                                //Remove HTML Element
                                this_remgallery.closest('.galleryrow').remove();
                            },
                            error: function(err) {
                                showToast("Failed", err.responseJSON.message);
                            }
                        });
                    });
                });

                // Edit Gallery Handler
                $(document).on('click', '.editgallery', function() {
                    let this_btn = $(this);
                    this_btn.closest('.galleryreadmode').hide();
                    let edit_mode = this_btn.closest('.galleryreadmode').siblings('.galleryeditmode');
                    edit_mode.show("slow");

                    //Cancel Handler
                    edit_mode.find('.gallerycancelbtn').off("click");
                    edit_mode.find('.gallerycancelbtn').on('click', function() {
                        // console.log("boo");
                        this_btn.closest('.galleryreadmode').show("slow");
                        edit_mode.hide();
                    });

                    edit_mode.find('.galleryeditform').off("submit");
                    edit_mode.find('.galleryeditform').on("submit", function(e) {
                        e.preventDefault();
                        let this_form = $(this);
                        let csrfToken = '{{ csrf_token() }}';
                        let form = this_form.serializeArray();
                        let id = $(this).closest('.galleryrow').attr('data-id');
                        form.push({
                            name: "_token",
                            value: csrfToken
                        });
                        $.ajax({
                            url: `api/gallery/${id}/edit`,
                            type: 'PUT',
                            cache: false,
                            data: form,
                            beforeSend: function() {
                                this_form.find('.gallerychangebtn').attr('disabled',
                                    'disabled');
                                this_form.find('.gallerychangebtn').text('Loading...')

                            },
                            success: function(res) {
                                // console.log(res);
                                let message = '';
                                //Hide spinner and show button submit
                                this_form.find('.gallerychangebtn').removeAttr('disabled');
                                this_form.find('.gallerychangebtn').text('Save Change');

                                this_btn.closest('.galleryreadmode').show("slow");
                                edit_mode.hide();
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

                                this_btn.closest('.galleryreadmode').find('.category_id')
                                    .text(res.data.category_name);
                                this_btn.closest('.galleryreadmode').find('.title').text(res
                                    .data.title);
                                this_btn.closest('.galleryreadmode').find('.description')
                                    .text(res.data.description);
                            },
                            error: function(err) {
                                //Show toast as alert

                                this_form.find('.gallerychangebtn').removeAttr('disabled');
                                this_form.find('.gallerychangebtn').text('Save Change');
                                showToast("Failed", err.responseJSON.message)

                            }
                        });
                    });
                });

                // Create Gallery Handler
                $(document).on('submit', '#contactform', function(e) {
                    e.preventDefault();
                    let this_form = $(this);
                    let csrfToken = '{{ csrf_token() }}';
                    let form = new FormData(this);
                    form.append(
                        "_token",
                        csrfToken
                    )

                    $.ajax({
                        url: `api/contact/store`,
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        data: form,
                        beforeSend: function() {
                            $('#btncontactform').attr('disabled', 'disabled');
                            $('#btncontactform').text('Loading...')

                        },
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            $('#btncontactform').removeAttr('disabled');
                            $('#btncontactform').text('Add')
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
                        },
                        error: function(err) {
                            //Show toast as alert

                            $('#btncontactform').removeAttr('disabled');
                            $('#btncontactform').text('Add')
                            showToast("Failed", err.responseJSON.message)

                        }
                    });
                });

                // GALLERY CRUD HANDLER END

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
