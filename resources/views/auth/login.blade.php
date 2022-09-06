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
  <title>Login | Profecta Perdana</title>
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
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-5"><img class="bg-img-cover bg-center" src="{{ asset('images/depan.jpg') }}" alt="looginpage">
      </div>
      <div class="col-xl-7 p-0">
        <div class="login-card">

          <form class="theme-form login-form needs-validation" novalidate="" method="POST"
            action="{{ route('login') }}">
            @csrf

            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="{{ asset('/images/logo.png') }}"
                  alt=""></a></div>
            <h4>Login</h4>
            <h6>Welcome back! Log in to your account.</h6>
            <div class="form-group">
              <label>Email Address</label>
              <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                <input class="form-control @error('email') is-invalid @enderror" name="email"
                  value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email"
                  type="email">

                <div class="invalid-tooltip">Please enter proper email.</div>

              </div>
            </div>
            <div class="form-group">
              <label>Password</label>
              <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                <input class="form-control @error('password') is-invalid @enderror" name="password" required
                  autocomplete="current-password" placeholder="Password" type="password">
                <div class="invalid-tooltip">Please enter password.</div>
              </div>
            </div>

            <div class="form-group">
              <button class="text-white btn btn-primary form-control" type="submit">Sign in</button>
            </div>
            <div class="login-social-title">
              <h5>Wellcome </h5>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>
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
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="{{ asset('assets') }}/js/script.js"></script>
  <!-- login js-->
  <!-- Plugin used-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  @if (Session::has('error'))
    <script>
      swal("Login failed !", "{!! Session::get('error') !!}", "error", {
        button: "Again",
      });
    </script>
  @endif

</body>

</html>
