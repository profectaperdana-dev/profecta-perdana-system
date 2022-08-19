@can('isVerificator')
  <script>
    Echo.channel('realtimeNotif').listen('SOMessage', (e) => {
      let sound = new Audio("{{ asset('sounds/so-created.wav') }}");
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
      })
    });
  </script>

  @can('isAdmin')
    <script>
      Echo.channel('realtimeNotif').listen('SOMessage', (e) => {
        let sound = new Audio("{{ asset('sounds/so-created.wav') }}");
        sound.play();
        $.notify({
          title: e.title,
          message: e.message
        }, {
          type: 'danger',
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
  @endcan
