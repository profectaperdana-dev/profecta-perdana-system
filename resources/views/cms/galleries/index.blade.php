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

            #imgparent {
                /* background-image: url({{ asset('images/no-image.png') }}); */
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;
            }

            .card-img-top {
                transform-origin: 50% 65%;
                transition: transform 1s, filter 2s ease-in-out;

            }

            .card-img-top:hover {

                transform: scale(1.2);
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
                            <h4>Input Image</h4>

                            <div class="card mb-3 col-lg-8 col-12 ">
                                <form action="" id="inputgalleryform" enctype="multipart/form-data">
                                    <div class="row g-0 ">
                                        <div class="col-md-4 align-self-center " id="imgparent"
                                            style="background-image: url({{ asset('images/no-image.png') }});">
                                            <input class="form-control form-control-sm w-100" id="imginput" name="img"
                                                type="file" accept="image/png, image/jpeg"
                                                style="min-height:250px; opacity:0;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <div class="mb-3">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter title..." name="title">
                                                    </div>
                                                </h5>
                                                <p class="card-text">
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" id="" class="form-control" placeholder="Enter description..."></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class=" row justify-content-between">
                                                        <div class="col-4">Category</div>
                                                        <div class="col-6 text-end">
                                                            <a href="javascript:void(0)" class="me-1 text-success"
                                                                id="showaddcategory"><i class="fa-solid fa-plus"></i>
                                                                Add</a>
                                                            <a href="javascript:void(0)" class="text-warning"
                                                                id="showlistcategory"><i
                                                                    class="fa-solid fa-clipboard-list"></i>
                                                                List</a>
                                                        </div>
                                                    </label>
                                                    <select name="category_id" id="category" required
                                                        class="form-control selectMulti" multiple>
                                                        @foreach ($all_categories as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                        {{-- <option value="1">coba</option> --}}
                                                    </select>
                                                </div>
                                                </p>
                                                <button type="submit" class="btn btn-success"
                                                    id="btnaddgallery">Add</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div class="card mb-3 col-lg-4 col-12 p-2" id="addcategoryparent" style="display: none">
                                <div class="my-auto">
                                    <h5>Add Category</h5>
                                    <form action="" id="addcategoryform">
                                        <div class="my-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Enter the category name...">
                                        </div>
                                        <button class="btn btn-sm btn-success" type="submit">Add</button>
                                        <div class="spinner-border text-success" style="display: none" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <button class="btn btn-sm btn-secondary" type="button"
                                            onclick="$('#addcategoryparent').hide('slow')">close</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-3 col-lg-4 col-12 p-2" id="listcategoryparent" style="display: none">
                                <h5>Category List</h5>
                                <div class="overflow-auto" style="max-height: 250px">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody id="categorytable">
                                            @foreach ($all_categories as $item)
                                                <tr class="parentcategory" data-iteration="{{ $loop->index + 1 }}"
                                                    data-id="{{ $item->id }}">

                                                    <th scope="row">{{ $loop->index + 1 }}</th>

                                                    <td>
                                                        <form action="" class="listcategoryform">
                                                            <input type="text" class="form-control listcategoryinput"
                                                                name="name" value="{{ $item->name }}">
                                                            <button type="submit" class="mt-2 text-warning editcategory"
                                                                style="background: none; border:none;display:none">
                                                                <i class="fa-solid fa-pen-to-square"></i> Save change
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="text-danger remcategory"
                                                            style="background: none; border:none;">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                        <div class="confirmationcategory" style="display: none">
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
                                    onclick="$('#listcategoryparent').hide('slow')">close</button>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h4>Gallery Images</h4>
                            <div class="row row-cols-1 row-cols-lg-4 g-2 galleryparent">
                                @if (sizeof($all_galleries) > 0)
                                    @foreach ($all_galleries as $item)
                                        <div class="col galleryrow" data-id="{{ $item->id }}">
                                            <div class="card h-100 overflow-hidden">
                                                <img src="{{ url('public/images/cms/galleries/' . $item->img) }}"
                                                    class="card-img-top" alt="Thumbnail" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal">

                                                <div class="card-body galleryreadmode">
                                                    <span
                                                        class="my-2 badge rounded-pill bg-light text-dark category_id">{{ $item->categoryBy->name }}</span>
                                                    <h5 class="card-title title">{{ $item->title }}</h5>
                                                    <p class="card-text description">{{ $item->description }}</p>
                                                    <div class="row justify-content-end buttongallery">
                                                        <div class="col-2">
                                                            <button type="button" class="text-warning editgallery"
                                                                style="background: none; border:none;">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="button" class="text-danger remgallery"
                                                                style="background: none; border:none;">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="row justify-content-end confirmationgallery"
                                                        style="display: none">
                                                        <div class="col-6 text-nowrap"><span>Wanna delete?</span></div>
                                                        <div class="col-3">
                                                            <button type="button" class="text-secondary cancelrem"
                                                                style="background: none; border:none;">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                        <div class="col-3">
                                                            <button type="button" class="text-danger yesrem"
                                                                style="background: none; border:none;">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body galleryeditmode" style="display: none">
                                                    <form action="" class="galleryeditform">
                                                        <div class="form-group">
                                                            <select name="category_id" required
                                                                class="form-control selectMulti" multiple>
                                                                @foreach ($all_categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        @if ($item->category_id == $category->id) selected @endif>
                                                                        {{ $category->name }}</option>
                                                                @endforeach
                                                                {{-- <option value="1">coba</option> --}}
                                                            </select>
                                                        </div>
                                                        <h5 class="card-title">
                                                            <div class="mb-3">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter title..." name="title"
                                                                    value="{{ $item->title }}">
                                                            </div>
                                                        </h5>
                                                        <p class="card-text">
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea name="description" id="" class="form-control" placeholder="Enter description...">{{ $item->description }}</textarea>
                                                        </div>
                                                        </p>
                                                        <div class="row justify-content-end buttongallery">
                                                            <div class="col-3">
                                                                <button type="button"
                                                                    class="text-secondary gallerycancelbtn text-nowrap"
                                                                    style="background: none; border:none;">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                            <div class="col-6">
                                                                <button type="submit"
                                                                    class="text-warning gallerychangebtn text-nowrap"
                                                                    style="background: none; border:none;">
                                                                    Save change
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </form>
                                                </div>


                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col">No data</div>
                                @endif


                            </div>
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
                $(document).on('click', '#showaddcategory', function() {
                    let this_btn = $(this);
                    $('#addcategoryparent').show();
                    $('#listcategoryparent').hide();
                });

                //Button show list category
                $(document).on('click', '#showlistcategory', function() {
                    let this_btn = $(this);
                    $('#addcategoryparent').hide();
                    $('#listcategoryparent').show();
                });

                // CATEGORY CRUD HANDLER START

                // Category Delete Handler
                $(document).on('click', '.remcategory', function() {
                    let this_remcategory = $(this);
                    this_remcategory.toggle();
                    let confirmationcategory = this_remcategory.siblings('.confirmationcategory');
                    confirmationcategory.toggle();

                    //Cancel Handler
                    confirmationcategory.find('.canceldel').off("click");
                    confirmationcategory.find('.canceldel').on('click', function() {
                        this_remcategory.toggle();
                        confirmationcategory.toggle();
                    });

                    //Submit Category delete Handler
                    confirmationcategory.find('.yesdel').off('click');
                    confirmationcategory.find('.yesdel').on('click', function() {
                        let id = $(this).closest('.parentcategory').attr('data-id');
                        let token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: `api/category_gallery/${id}/delete`,
                            type: 'DELETE',
                            cache: false,
                            data: {
                                "_token": `{{ csrf_token() }}`
                            },
                            success: function(res) {
                                showToast("Success", res.message);
                                //Remove HTML Element
                                this_remcategory.closest('.parentcategory').remove();
                                $('#category').find(`option[value="${id}"]`).remove();
                            },
                            error: function(err) {
                                showToast("Failed", err.responseJSON.message);
                            }
                        });
                    });

                });

                //Category Edit Handler

                $(document).on('focus', '.listcategoryinput', function() {
                    let this_input = $(this);
                    let old_value = this_input.val();


                    //Change Value Handler
                    this_input.off('input');
                    this_input.on('input', function() {
                        let new_value = $(this).val();

                        if (new_value != old_value) {
                            this_input.siblings('.editcategory').show();
                        } else {
                            this_input.siblings('.editcategory').hide();
                        }
                    });
                    // console.log(this_input.parents('.listcategoryform').html());
                    this_input.closest('.listcategoryform').off('submit');
                    this_input.closest('.listcategoryform').on('submit', function(e) {
                        e.preventDefault();
                        let csrfToken = '{{ csrf_token() }}';
                        let id = this_input.closest('.parentcategory').attr('data-id');
                        let formData = $(this).serializeArray();
                        formData.push({
                            name: "_token",
                            value: csrfToken
                        });
                        $.ajax({
                            url: `api/category_gallery/${id}/edit`,
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
                                this_input.siblings('.editcategory').hide();
                                $('#category').find(`option[value="${id}"]`).val(res.data
                                    .id);
                                $('#category').find(`option[value="${id}"]`).text(res.data
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

                //Category Create Handler
                $(document).on('submit', '#addcategoryform', function(e) {
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
                        url: `api/category_gallery/store`,
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
                            let new_category =
                                `<option value="${res.data.id}">${res.data.name}</option>`;
                            $('#category').append(new_category);
                            this_form.find('input').val('');

                            //add to table
                            let data_iteration = $('#categorytable').find('tr').last().attr(
                                'data-iteration');
                            // console.log(data_iteration);
                            let new_row_category = `<tr data-iteration="${parseInt(data_iteration) + 1}" data-id="${res.data.id}">
                                                    <th scope="row">${parseInt(data_iteration) + 1}</th>
                                                    <form action="" class="listcategoryform">
                                                        <td><input type="text" class="form-control listcategoryinput" name="name"
                                                                value="${res.data.name}"></td>
                                                        <td class="text-center">
                                                            <button type="submit" class="me-2 text-warning editcategory"
                                                                style="background: none; border:none;display:none">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                            <button type="button" class="text-danger remcategory"
                                                                style="background: none; border:none">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                            <div class="confirmationcategory" style="display: none">
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

                            $('#categorytable').append(new_row_category);

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

                // CATEGORY CRUD HANDLER END

                // GALLERY CRUD HANDLER START

                // Change Preview Image Handler
                $(document).on('change', '#imginput', function() {
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imgparent').css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
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
                $(document).on('submit', '#inputgalleryform', function(e) {
                    e.preventDefault();


                    let this_form = $(this);
                    let csrfToken = '{{ csrf_token() }}';
                    let form = new FormData(this);
                    form.append(
                        "_token",
                        csrfToken
                    )

                    form.append(
                        "img",
                        $('#imginput')[0].files[0]
                    );

                    // console.log(form);
                    $.ajax({
                        url: `api/gallery/store`,
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        data: form,
                        beforeSend: function() {
                            $('#btnaddgallery').attr('disabled', 'disabled');
                            $('#btnaddgallery').text('Loading...')

                        },
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            $('#btnaddgallery').removeAttr('disabled');
                            $('#btnaddgallery').text('Add')
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

                            let list_category = $('#category').html();
                            // list_category.attr('multiple', 'multiple');
                            // console.log(list_category.prop('outerHTML'));

                            let new_element = `<div class="col galleryrow" data-id="${res.data.id}">
                                    <div class="card h-100 overflow-hidden">
                                        <img src="{{ url('public/images/cms/galleries/${res.data.img}') }}" class="card-img-top"
                                            alt="Thumbnail" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <div class="card-body galleryreadmode">
                                            <span class="my-2 badge rounded-pill bg-light text-dark">${res.data.category_by.name}</span>
                                            <h5 class="card-title">${res.data.title}</h5>
                                            <p class="card-text">${res.data.description}</p>
                                            <div class="row justify-content-end buttongallery">
                                                <div class="col-2">
                                                    <button type="button" class="text-warning editgallery"
                                                        style="background: none; border:none;">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="text-danger remgallery"
                                                        style="background: none; border:none;">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row justify-content-end confirmationgallery"
                                                style="display: none">
                                                <div class="col-6 text-nowrap"><span>Wanna delete?</span></div>
                                                <div class="col-3">
                                                    <button type="button" class="text-secondary cancelrem"
                                                        style="background: none; border:none;">
                                                        Cancel
                                                    </button>
                                                </div>
                                                <div class="col-3">
                                                    <button type="button" class="text-danger yesrem"
                                                        style="background: none; border:none;">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body galleryeditmode" style="display: none">
                                            <form action="" class="galleryeditform">
                                                <div class="form-group">
                                                    <select name="category_id" required
                                                        class="form-control selectMulti" multiple>
                                                        ${list_category}
                                                        {{-- <option value="1">coba</option> --}}
                                                    </select>
                                                </div>
                                                <h5 class="card-title">
                                                    <div class="mb-3">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter title..." name="title"
                                                            value="${res.data.title}">
                                                    </div>
                                                </h5>
                                                <p class="card-text">
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" id="" class="form-control" placeholder="Enter description...">${res.data.description}</textarea>
                                                </div>
                                                </p>
                                                <div class="row justify-content-end buttongallery">
                                                    <div class="col-3">
                                                        <button type="button"
                                                            class="text-secondary gallerycancelbtn text-nowrap"
                                                            style="background: none; border:none;">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="submit"
                                                            class="text-warning gallerychangebtn text-nowrap"
                                                            style="background: none; border:none;">
                                                            Save change
                                                        </button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>


                                    </div>
                                </div>`;

                            $('.galleryparent').prepend(new_element);
                            this_form[0].reset();
                            this_form.find('input[type=file], select').val('');
                            $('#imgparent').css('background-image',
                                "url({{ asset('images/no-image.png') }})");

                            $('.selectMulti').select2({
                                placeholder: 'Select an option',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%',
                            });
                        },
                        error: function(err) {
                            //Show toast as alert

                            $('#btnaddgallery').removeAttr('disabled');
                            $('#btnaddgallery').text('Add')
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
