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

            #imgparent3,
            .provide_img_parent {
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
                            <div class="card mb-10 col-lg-12 col-12 ">
                                <form action="" id="inputaboutform" enctype="multipart/form-data">
                                    <h4>About Image 1</h4>
                                    <div class="row g-0 ">
                                        <div class="col-md-4 align-self-center " id="imgparent"
                                            style="background-image: url({{ $about || $about->image_1 != 'blank' || $about->image_1 != null ? url('public/images/cms/abouts/' . $about->image_1) : asset('images/no-image.png') }});">
                                            <input class="form-control form-control-sm w-100" id="imginput" name="img1"
                                                type="file" accept="image/png, image/jpeg"
                                                style="min-height:250px; opacity:0;">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <div class="mb-3">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter title..." name="title"
                                                            value="{{ $about->header_about }}">
                                                    </div>
                                                </h5>
                                                <p class="card-text">
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" id="" class="form-control" placeholder="Enter description...">{{ $about->description_about }}</textarea>
                                                </div>
                                                </p>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="mt-5">
                                        <h4>Provide List</h4>
                                        <div class="row g-0 ">
                                            @foreach ($about->provideBy as $item)
                                                <div class="col-md-6">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            <div class="mb-3">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter title..."
                                                                    name="soprovided[{{ $loop->index }}][title]"
                                                                    value="{{ $item->title }}">
                                                            </div>
                                                        </h5>
                                                        <div class="col-md-4 align-self-center provide_img_parent"
                                                            style="background-image: url({{ $item->image ? url('public/images/cms/abouts/' . $item->image) : asset('images/no-image.png') }});">
                                                            <input class="form-control form-control-sm w-150 provide_img"
                                                                name="soprovided[{{ $loop->index }}][image]"
                                                                value="{{ $item->image }}" type="file"
                                                                accept="image/png, image/jpeg"
                                                                style="min-height:200px; opacity:0;">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>


                                    <div class="mt-5">
                                        <div class="card-body">
                                            <h4>About Image 2</h4>
                                            <div class="row g-0 ">
                                                <div class="col-12 align-self-center " id="imgparent2"
                                                    style="background-image: url({{ $about || $about->image_2 != 'blank' || $about->images_2 != null ? url('public/images/cms/abouts/' . $about->image_2) : asset('images/no-image.png') }});">
                                                    <input class="form-control form-control-sm w-400" id="imginput2"
                                                        name="img2" type="file" accept="image/png, image/jpeg"
                                                        style="min-height:250px; opacity:0;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="mt-5">
                                        <h4>History</h4>
                                        <div class="row g-0 ">
                                            <div class="col-md-12">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <div class="mb-3">
                                                            <label class="form-label">Title</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter title..." name="title_history"
                                                                value="{{ $about->header_history }}">
                                                        </div>
                                                    </h5>
                                                    <p class="card-text">
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea name="description_history" id="" class="form-control" placeholder="Enter description...">{{ $about->description_history }}</textarea>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="formSo">
                                        <h4>Journey</h4>
                                        @foreach ($about->journeyBy as $item)
                                            <div class="mx-auto py-2 form-group rounded row">
                                                <div class="mb-2 col-6 col-lg-4">
                                                    <label>year</label>
                                                    <input type="number" required
                                                        name="soFields[{{ $loop->index }}][year]" id=""
                                                        value="{{ $item->year }}" class="form-control multi-so"
                                                        required multiple>
                                                </div>

                                                <div class="col-6 col-lg-4">
                                                    <label>Title</label>
                                                    <input type="text" class="form-control title cektitle" required
                                                        name="soFields[{{ $loop->index }}][title]" id=""
                                                        value="{{ $item->title }}">
                                                    <small class="text-danger title-warning" hidden></small>


                                                </div>
                                                <div class="col-md-6">
                                                    <label> Description </label>
                                                    <input type="text" class="form-control title cektitle" required
                                                        name="soFields[{{ $loop->index }}][description]" id=""
                                                        value="{{ $item->description }}">

                                                </div>
                                                <div class="col-6 col-md-1">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="form-control btn btn-sm text-white addSo text-center"
                                                        style="border:none; background-color:#276e61">+</a>

                                                </div>
                                                @if ($loop->index > 0)
                                                    <div class="col-6 col-md-1">
                                                        <label for=""> &nbsp; </label>
                                                        <a href="#" class="form-control text-white rem text-center"
                                                            style="border:none; background-color:#d94f5c">
                                                            - </a>
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach

                                    </div>
                                    <button type="submit" class="btn btn-success" id="btnaddabout">Add</button>
                                </form>
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

                //Add colomn data


                //Show Toast function
                function showToast(status = "", message = "") {
                    $('#statustoast').text(status);
                    $('#messagetoast').text(message);
                    $('#liveToast').show();
                };

                // Change Preview Image1 Handler
                $(document).on('change', '#imginput', function() {
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imgparent').css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // Change Preview Image2 Handler
                $(document).on('change', '#imginput2', function() {
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imgparent2').css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // // Change Preview Image Handler Provide List
                $(document).on('change', '.provide_img', function() {
                    let this_img = $(this)
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            this_img.parent().css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });


                let x = 0;
                //Add Data Banner
                $(document).on("click", ".addSo", function() {
                    ++x;
                    let form = `<div class="mx-auto py-2 form-group rounded row">
                                    <div class="mb-2 col-12 col-lg-5">
                                        <label>Year</label>
                                        <input type="number" name="soFields[${x}][year]" class="form-control multi-so" required multiple>
                                    </div>
                                    <div class="col-6 col-lg-5">
                                        <label>Title</label>
                                        <input type="text" class="form-control title cektitle" required name="soFields[${x}][title]"
                                                id="">
                                            <small class="text-danger title-warning" hidden></small>
                                    </div>
                                    <div class="col-6 col-md-1">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)" class="form-control addSo text-white  text-center"
                                            style="border:none;background-color:#276e61">+</a>
                                    </div>
                                    <div class="col-6 col-md-1">
                                        <label for=""> &nbsp; </label>
                                        <a href="#" class="form-control text-white remSo text-center" style="border:none; background-color:#d94f5c">
                                        - </a>
                                    </div>
                                    <div class="col-md-6 ">
                                        <label>Description</label>
                                        <input type="text" class="form-control description cekdescription" required name="soFields[${x}][description]"
                                                id="">
                                            <small class="text-danger title-warning" hidden></small>
                                    </div>
                                    
                                </div>`;
                    $("#formSo").append(form);
                    // $("#formSo").find('.hideAdd').not(':last').attr('hidden', 'true');


                });

                $(document).on('click', '.rem', function() {
                    $(this).closest('.row').remove()
                })

                // Create About Handler
                $(document).on('submit', '#inputaboutform', function(e) {
                    e.preventDefault();

                    let this_form = $(this);
                    let csrf_token = '{{ csrf_token() }}';
                    let form = new FormData(this);
                    let n = 0;

                    form.append(
                        "_token",
                        csrf_token
                    )

                    form.append(
                        "img1",
                        $('#imginput')[0].files[0]

                    )
                    form.append(
                        "img2",

                        $('#imginput2')[0].files[0]
                    )

                    $(".provide_img").each(function() {
                        console.log(n);
                        form.append(
                            `img_provide${n}`,

                            $(this)[0].files[0]
                        )
                        n++;
                    })


                    console.log(form);
                    $.ajax({
                        url: `api/about/store`,
                        type: `POST`,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        data: form,
                        beforeSend: function() {
                            $('#btnaddabout').attr('disabled', 'disabled');
                            $('#btnaddabout').text('Loading...')
                        },
                        success: function(res) {
                            //console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            $('#btnaddabout').removeAttr('disabled');
                            $('#btnaddabout').text('Add')
                            //Show toast as alert
                            if (res.status != 200) {


                                showToast("Failed", message);
                                return false;
                            }
                            message = res.message;
                            showToast("Succes", message);

                        },

                        error: function(err) {
                            //Show toast as alert

                            $('#btnaddgallery').removeAttr('disabled');
                            $('#btnaddgallery').text('Add')
                            showToast("Failed", err.responseJSON.message)

                        }
                    });
                });
                //ABOUT CRUD HANDLER END

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
