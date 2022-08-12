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

 <!-- Plugins JS start-->
 @stack('scripts')
 <script src="{{ asset('js/select.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


 <!-- Plugins JS Ends-->
 <!-- Theme js-->
 <script src="{{ asset('assets') }}/js/script.js"></script>
 {{-- <script src="{{ asset('assets') }}/js/theme-customizer/customizer.js"></script> --}}
 <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
     integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
     crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 @if (Session::has('success'))
     <script>
         swal("Berhasil !", "{!! Session::get('success') !!}", "success", {
             button: "Ok",
         });
     </script>
 @endif
 @if (Session::has('error'))
     <script>
         swal("Berhasil !", "{!! Session::get('error') !!}", "success", {
             button: "Ok",
         });
     </script>
 @endif
 @if (Session::has('info'))
     <script>
         swal("Berhasil !", "{!! Session::get('info') !!}", "success", {
             button: "Ok",
         });
     </script>
 @endif
 <!-- login js-->
 <!-- Plugin used-->
