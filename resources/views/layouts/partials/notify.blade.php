@can('finance')
  <script>
    Echo.channel('approval_notif').listen('ApprovalMessage', (e) => {
      let sound_approv = new Audio("{{ asset('sounds/sounds.wav') }}");
      sound_approv.play();

      $.notify({
        title: e.title,
        message: e.message
      }, {
        type: 'warning',
        allow_dismiss: true,
        newest_on_top: true,
        mouse_over: true,
        showProgressbar: false,
        spacing: 10,
        timer: 10000,
        placement: {
          from: 'top',
          align: 'right'
        },
        offset: {
          x: 30,
          y: 30
        },
        delay: 1000,
        z_index: 10000,
        animate: {
          enter: 'animated swing',
          exit: 'animated swing'
        }
      })
    });
  </script>
@elsecan('verificator')
  <script>
    Echo.channel('realtimeNotif').listen('SOMessage', (e) => {
      let sound = new Audio("{{ asset('sounds/so-created.wav') }}");
      let csrf = $('meta[name="csrf-token"]').attr("content");

      sound.play();
      $.notify({
        title: e.title,
        message: e.message
      }, {
        type: 'primary',
        allow_dismiss: true,
        newest_on_top: true,
        mouse_over: true,
        showProgressbar: false,
        spacing: 10,
        timer: 10000,
        placement: {
          from: 'top',
          align: 'right'
        },
        offset: {
          x: 30,
          y: 30
        },
        delay: 1000,
        z_index: 10000,
        animate: {
          enter: 'animated swing',
          exit: 'animated swing'
        }
      });
      $.ajax({
        type: 'get',
        url: '/notification/getAll',
        data: {
          '_token': csrf,
        },
        success: function(data) {
          let count = $('.notifCount').text();
          count = parseInt(count);
          $('.notifCount').text(count + 1);
          let animation =
            ' <i class="fa-2x far fa-bell fa-shake"></i><span class="dot-animated"></span>';
          $('.notification-box').html(animation);
          // $('.bell').remove();
          let notif =
            ' <li class="notif-primary"><div class="media"> <span class="notification-bg bg-light-primary"> <i class="fa fa-envelope">' +
            ' </i></span> <div class="media-body"> <a href="/read_notif/' + data.id +
            '"><p> ' +
            data.message +
            ' </p><span>10 minutes ago</span> ' +
            ' </a> </div> </div> </li> ';

          $('.notifContainer').prepend(
            notif);

          // setTimeout(realTime, 2000);
        },
      });
    });
  </script>
@endcan
