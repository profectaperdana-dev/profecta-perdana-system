<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="viho admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, viho admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('assets') }}/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" type="image/x-icon">
    <title>Profecta Perdana | Information Asset</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/feather-icon.css">
    <!-- Plugins css start-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/style.css">
    <link id="color" rel="stylesheet" href="{{ asset('assets') }}/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/responsive.css">
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->

    <!-- Page Sidebar Start-->

    <!-- Page Sidebar Ends-->
    <div class="page-body mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="logo-wrapper text-start mt-3" style="margin-left: 30px">
                            <a href="index.html"><img class="img-fluid" style="width: 150px"
                                    src="{{ asset('images/logos.png') }}" alt=""></a>
                        </div>
                        <div class="card-header pb-0">
                            <h5>Informasi Asset / <i>Asset Information</i></h5>
                        </div>
                        <div class="card-body">
                            <div class="taskadd shadow">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>
                                                <h6 class="task_title_0">Status</h6>
                                            </td>
                                            <td>
                                                <p class="task_desc_0">
                                                    @if ($data->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($data->status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <h6 class="task_title_0">Asset Code</h6>
                                            </td>
                                            <td>
                                                <p class="task_desc_0">{{ $data->asset_code }}</p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <h6 class="task_title_0">Asset Name</h6>
                                            </td>
                                            <td>
                                                <p class="task_desc_0">{{ $data->asset_name }}</p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <h6 class="task_title_0">Asset Amount</h6>
                                            </td>
                                            <td>
                                                <p class="task_desc_0">
                                                    {{ $data->amount }}
                                                </p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <h6 class="task_title_0">Maintenance Last Date</h6>
                                            </td>
                                            <td>
                                                @if ($data->service_date == null)
                                                    <span class="badge bg-danger">Not Set</span>
                                                @else
                                                    <p class="task_desc_0">
                                                        {{ date('d F Y', strtotime($data->service_date)) }}
                                                    </p>
                                                @endif

                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <h6 class="task_title_0">Maintenance Distance </h6>
                                            </td>
                                            <td>
                                                <p class="task_desc_0">
                                                    {{ $data->range }}
                                                </p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <h6 class="task_title_0">Maintenance Next Date </h6>
                                            </td>
                                            <td>
                                                @if ($data->next_service == null)
                                                    <span class="badge bg-danger">Not Set</span>
                                                @else
                                                    <p class="task_desc_0">
                                                        {{ date('d F Y', strtotime($data->next_service)) }}
                                                    </p>
                                                @endif

                                            </td>

                                        </tr>


                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>


    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/js/jquery-3.5.1.min.js"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather-icon.js"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets') }}/js/sidebar-menu.js"></script>
    <script src="{{ asset('assets') }}/js/config.js"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets') }}/js/bootstrap/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap/bootstrap.min.js"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets') }}/js/form-wizard/form-wizard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
    <script src="{{ asset('assets') }}/js/script.js"></script>

    @include('prospective_employee.tab-0-js')
    @include('prospective_employee.tab-1-js')
    @include('prospective_employee.tab-2-js')
    <script>
        $(document).ready(function() {




            $('.select2').select2(

            );








        });


        $(function() {
            let validator = $('form.needs-validation').jbvalidator({
                errorMessage: true,
                successClass: false,
                language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
            });

            //reload instance after dynamic element is added

            $('input').on('keyup', function() {

                if (validator.checkAll() == 0) {
                    $('form').find('#nextBtn').attr('disabled', false);
                } else {
                    $('form').find('#nextBtn').attr('disabled', true);
                }
                console.log(validator.checkAll());
                // validator.reload();
            });
            console.log(validator.checkAll());


        })
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if (Session::has('success'))
        <script>
            swal("Success !", "{!! Session::get('success') !!}", "success", {
                button: "Close",
            });
        </script>
    @endif
    @if (Session::has('info'))
        <script>
            swal("For you information !", "{!! Session::get('info') !!}", "info", {
                button: "Close",
            });
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            swal("Attention !", "{!! Session::get('error') !!}", "error", {
                button: "Close",
            });
        </script>
    @endif

</body>

</html>
