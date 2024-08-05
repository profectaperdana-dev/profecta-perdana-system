

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
 <script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/push.js"
     integrity="sha512-lYYzkh4X04OJKecFNDnkk1ddO2Oo6BNVkysVAKZTQJC/xC7hsrqM8U24FbW8z2F0oxqJgXvodOziCdKj5gBjCw=="
     crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script src="{{ asset('assets') }}/js/prism/prism.min.js"></script>
 <!-- Plugins JS start-->
 @stack('scripts')
 <script src="{{ asset('js/select.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <script src="https://kit.fontawesome.com/e355a54691.js" crossorigin="anonymous"></script>
 <script src="{{ asset('assets') }}/js/script.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
     integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
     crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script>
     if ('serviceWorker' in navigator) {
         window.addEventListener('load', function() {
             navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                 console.log('Service worker registered with scope:', registration.scope);
             }, function(error) {
                 console.log('Service worker registration failed:', error);
             });
         });
     }
 </script>
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
 @if (Session::has('error2'))
     <script>
         swal("System Error !", "{!! Session::get('error2') !!}", "error", {
             button: "Close",
         });
     </script>
 @endif
 <script src="{{ asset('assets') }}/js/notify/bootstrap-notify.min.js"></script>
 <script src="{{ asset('assets') }}/js/notify/notify-script.js"></script>
 <script src="{{ asset('js/app.js') }}"></script>
 <script src="https://laravel.pixelstrap.com/viho/assets/js/owlcarousel/owl.carousel.js"></script>
 <script src="https://laravel.pixelstrap.com/viho/assets/js/owlcarousel/owl-custom.js"></script>
 <script>
     $(document).ready(function() {
         $('#owl-carousel-2e').owlCarousel({
            loop:false,
            margin:1,
            items:2,
            nav:false,
            responsive : {
                320 : {
                    items:1
                },
                576 : {
                    items:2
                },
                768 : {
                    items:2
                },
                992 : {
                    items:2
                }
            }
        })
        $('#owl-carousel-2a, #owl-carousel-2b, #owl-carousel-2c, #owl-carousel-2d').owlCarousel({
            loop:false,
            margin:10,
            items:1,
            nav:false,
            responsive : {
                0   : {
                    items:1
                },
                320 : {
                    items:1
                },
                576 : {
                    items:1
                },
                768 : {
                    items:1
                },
                992 : {
                    items:1
                }
            }
        });
        
         let url = $(this).find('.url').val();
        //  console.log(url);
         $('.btn-notif').on('click', function() {
             $.ajax({
                 url: url,
                 type: "GET",
                 dataType: "json",
                 data: '',
                 beforeSend: function() {
                     $('.btn-notif').attr('disabled', true);
                     $('.btn-notif').html(
                         `<i class="fa fa-spinner fa-spin"></i> Please wait...`
                     );
                 },
                 success: function(response) {
                     swal("Success !", response.message, "success", {
                         button: "Close",
                     });
                     $('.notifEach').each(function() {
                         $(this).remove();
                     });
                     $('.no-notif').removeClass('d-none');
                     $('.no-clear').addClass('d-none');
                     $('.bell').removeClass('fa-shake text-danger');
                     $('.bell').addClass('far');
                 },
                 error: function(jqXHR, textStatus, errorThrown) {
                     console.log('Error:', textStatus, errorThrown);
                 },
                 complete: function() { // menambahkan fungsi complete untuk mengubah tampilan tombol kembali ke tampilan semula
                     $('.btn-notif').attr('disabled', false);
                     $('.btn-notif').html('Clear Notifications');
                 }
             });
         });
     });
 </script>
   <script src="{{ asset('assets') }}/js/expired.js"></script>
 <script>
     $(window).on('load', function() {
         if ($('#default-pw').val() == 'true') {
             $('#aware-default').modal('show');
         }
     });
 </script>
 @include('layouts.partials.notify')
