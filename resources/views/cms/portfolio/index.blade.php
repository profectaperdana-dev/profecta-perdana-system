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
                            <h4>Input Portfolio</h4>

                            <div class="card mb-3 col-lg-8 col-12 ">
                                <form action="" id="inputportfolioform" enctype="multipart/form-data">
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
                                                </p>
                                                <button type="submit" class="btn btn-success"
                                                    id="btnaddportfolio">Add</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h4>Portfolio Images</h4>
                            <div class="row row-cols-1 row-cols-lg-4 g-2 portfolioparent">
                                @if (sizeof($all_portfolio) > 0)
                                    @foreach ($all_portfolio as $item)
                                        <div class="col portfoliorow" data-id="{{ $item->id }}">
                                            <div class="card h-100 overflow-hidden">
                                                <img src="{{ url('public/images/cms/portfolios/' . $item->img) }}"
                                                    class="card-img-top" alt="Thumbnail" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal">

                                                <div class="card-body portfolioreadmode">
                                                    <h5 class="card-title title">{{ $item->title }}</h5>
                                                    <p class="card-text description">{{ $item->description }}</p>
                                                    <div class="row justify-content-end buttonportfolio">
                                                        <div class="col-2">
                                                            <button type="button" class="text-warning editportfolio"
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

                                                <div class="card-body portfolioeditmode" style="display: none">
                                                    <form action="" class="portfolioeditform">

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
                                                        <div class="row justify-content-end buttonportfolio">
                                                            <div class="col-3">
                                                                <button type="button"
                                                                    class="text-secondary portfoliocancelbutton text-nowrap"
                                                                    style="background: none; border:none;">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                            <div class="col-6">
                                                                <button type="submit"
                                                                    class="text-warning portfoliochangebutton text-nowrap"
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
                    this_remgallery.closest('.buttonportfolio').hide();
                    let confirmationgallery = this_remgallery.closest('.buttonportfolio').siblings(
                        '.confirmationgallery');
                    confirmationgallery.show("slow");

                    //Cancel Handler
                    confirmationgallery.find('.cancelrem').off("click");
                    confirmationgallery.find('.cancelrem').on('click', function() {
                        confirmationgallery.hide();
                        this_remgallery.closest('.buttonportfolio').show("slow");

                    });

                    //Submit Gallery delete Handler
                    confirmationgallery.find('.yesrem').off('click');
                    confirmationgallery.find('.yesrem').on('click', function() {
                        let id = $(this).closest('.portfoliorow').attr('data-id');
                        let token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: `api/portfolio/${id}/delete`,
                            type: 'DELETE',
                            cache: false,
                            data: {
                                "_token": `{{ csrf_token() }}`
                            },
                            success: function(res) {
                                showToast("Success", res.message);
                                //Remove HTML Element
                                this_remgallery.closest('.portfoliorow').remove();
                            },
                            error: function(err) {
                                showToast("Failed", err.responseJSON.message);
                            }
                        });
                    });
                });

                // Edit Gallery Handler
                $(document).on('click', '.editportfolio', function() {
                    let this_btn = $(this);
                    this_btn.closest('.portfolioreadmode').hide();
                    let edit_mode = this_btn.closest('.portfolioreadmode').siblings('.portfolioeditmode');
                    edit_mode.show("slow");

                    //Cancel Handler
                    edit_mode.find('.portfoliocancelbutton').off("click");
                    edit_mode.find('.portfoliocancelbutton').on('click', function() {
                        // console.log("boo");
                        this_btn.closest('.portfolioreadmode').show("slow");
                        edit_mode.hide();
                    });

                    edit_mode.find('.portfolioeditform').off("submit");
                    edit_mode.find('.portfolioeditform').on("submit", function(e) {
                        e.preventDefault();
                        let this_form = $(this);
                        let csrfToken = '{{ csrf_token() }}';
                        let form = this_form.serializeArray();
                        let id = $(this).closest('.portfoliorow').attr('data-id');
                        form.push({
                            name: "_token",
                            value: csrfToken
                        });
                        $.ajax({
                            url: `api/portfolio/${id}/edit`,
                            type: 'PUT',
                            cache: false,
                            data: form,
                            beforeSend: function() {
                                this_form.find('.portfoliochangebutton').attr('disabled',
                                    'disabled');
                                this_form.find('.portfoliochangebutton').text('Loading...')

                            },
                            success: function(res) {
                                // console.log(res);
                                let message = '';
                                //Hide spinner and show button submit
                                this_form.find('.portfoliochangebutton').removeAttr(
                                    'disabled');
                                this_form.find('.portfoliochangebutton').text(
                                    'Save Change');

                                this_btn.closest('.portfolioreadmode').show("slow");
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

                                this_btn.closest('.portfolioreadmode').find('.title').text(
                                    res
                                    .data.title);
                                this_btn.closest('.portfolioreadmode').find('.description')
                                    .text(res.data.description);
                            },
                            error: function(err) {
                                //Show toast as alert

                                this_form.find('.portfoliochangebutton').removeAttr(
                                    'disabled');
                                this_form.find('.portfoliochangebutton').text(
                                    'Save Change');
                                showToast("Failed", err.responseJSON.message)

                            }
                        });
                    });
                });

                // Create Gallery Handler
                $(document).on('submit', '#inputportfolioform', function(e) {
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


                    $.ajax({
                        url: `api/portfolio/store`,
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        data: form,
                        beforeSend: function() {
                            $('#btnaddportfolio').attr('disabled', 'disabled');
                            $('#btnaddportfolio').text('Loading...')

                        },
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            $('#btnaddportfolio').removeAttr('disabled');
                            $('#btnaddportfolio').text('Add')
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

                            let new_element = `<div class="col portfoliorow" data-id="${res.data.id}">
                                    <div class="card h-100 overflow-hidden">
                                        <img src="{{ url('public/images/cms/portfolios/${res.data.img}') }}" class="card-img-top"
                                            alt="Thumbnail" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <div class="card-body portfolioreadmode">
                                            <h5 class="card-title">${res.data.title}</h5>
                                            <p class="card-text">${res.data.description}</p>
                                            <div class="row justify-content-end buttonportfolio"">
                                                div class="col-2">
                                                    <button type="button" class="text-warning editportfolio"
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

                                        <div class="card-body portfolioeditmode" style="display: none">
                                            <form action="" class="portfolioeditform">
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
                                                <div class="row justify-content-end buttonportfolio">
                                                    <div class="col-3">
                                                        <button type="button"
                                                            class="text-secondary portfoliocancelbutton text-nowrap"
                                                            style="background: none; border:none;">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="submit"
                                                            class="text-warning portfoliochangebutton text-nowrap"
                                                            style="background: none; border:none;">
                                                            Save change
                                                        </button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>


                                    </div>
                                </div>`;


                            $('.portfolioparent').prepend(new_element);
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

                            $('#btnaddportfolio').removeAttr('disabled');
                            $('#btnaddportfolio').text('Add')
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
