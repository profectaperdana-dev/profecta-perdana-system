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
 {{-- <script src="{{ asset('assets') }}/js/notify/index.js"></script> --}}
 <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/push.js"
     integrity="sha512-lYYzkh4X04OJKecFNDnkk1ddO2Oo6BNVkysVAKZTQJC/xC7hsrqM8U24FbW8z2F0oxqJgXvodOziCdKj5gBjCw=="
     crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <!-- Plugins JS start-->
 @stack('scripts')
 <script src="{{ asset('js/select.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <script src="https://kit.fontawesome.com/e355a54691.js" crossorigin="anonymous"></script>


 <!-- Plugins JS Ends-->
 <!-- Theme js-->
 <script src="{{ asset('assets') }}/js/script.js"></script>
 {{-- <script src="{{ asset('assets') }}/js/theme-customizer/customizer.js"></script> --}}
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
 <script src="{{ asset('assets') }}/js/notify/bootstrap-notify.min.js"></script>
 <script src="{{ asset('assets') }}/js/notify/notify-script.js"></script>
 <script src="{{ asset('js/app.js') }}"></script>
 <!-- login js-->
 <!-- Plugin used-->

 <!-- Notification -->
 @include('layouts.partials.notify')
