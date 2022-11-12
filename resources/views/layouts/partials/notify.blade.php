@can('isFinance')
    <script>
        $(document).ready(function() {

            Echo.channel('approval_notif').listen('ApprovalMessage', (e) => {
                let sound_approv = new Audio("{{ asset('sounds/sounds.wav') }}");
                sound_approv.play();
                let csrf = $('meta[name="csrf-token"]').attr("content");

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
                        let mark =
                            '<p class="f-w-700 mb-0">&nbsp;<span class="pull-right badge badge-primary badge-pill">' +
                            '<a href="/read_all_notif/{{ Auth::user()->job_id }}">Mark as readall</a></span></p>';
                        $('.mark-read').html(mark);


                        $('.notifCount').text(count + 1);
                        let animation =
                            ' <i class="fa-2x  text-danger  fa fa-bell fa-shake"></i><span class="dot-animated"></span>';
                        $('.notification-box').html(animation);
                        // $('.bell').remove();
                        let notif =
                            ' <li class="notif-primary"><div class="media"> <span class="notification-bg bg-light-primary"> <i class="fa fa-envelope">' +
                            ' </i></span> <div class="media-body"> <a href="/read_notif/' + data
                            .id +
                            '"><p> ' +
                            data.message +
                            ' </p><span>10 minutes ago</span> ' +
                            ' </a> </div> </div> </li> ';

                        $('.notifContainer').prepend(
                            notif);
                    },
                });
                const iconPath = '{{ asset('images/new1.png') }}'
                Push.create("Hay, {{ Auth::user()->name }}", {
                    body: "please check there is an order that must be approve.",
                    timeout: 10000,
                    icon: iconPath,
                    vibrate: [200, 100],

                });
            });
        });
    </script>
@elsecan('isVerificator')
    <script>
        $(document).ready(function() {
            Echo.channel('realtimeNotif').listen('SOMessage', (e) => {
                let soundPO = new Audio("{{ asset('sounds/so-created.wav') }}");
                soundPO.play();
                let csrf = $('meta[name="csrf-token"]').attr("content");
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
                        let mark =
                            '<p class="f-w-700 mb-0">&nbsp;<span class="pull-right badge badge-primary badge-pill">' +
                            '<a href="/read_all_notif/{{ Auth::user()->job_id }}">Mark as readall</a></span></p>';
                        $('.mark-read').html(mark);


                        $('.notifCount').text(count + 1);
                        let animation =
                            ' <i class="fa-2x text-danger fa fa-bell fa-shake"></i><span class="dot-animated"></span>';
                        $('.notification-box').html(animation);
                        // $('.bell').remove();
                        let notif =
                            ' <li class="notif-primary"><div class="media"> <span class="notification-bg bg-light-primary"> <i class="fa fa-envelope">' +
                            ' </i></span> <div class="media-body"> <a href="/read_notif/' + data
                            .id +
                            '"><p> ' +
                            data.message +
                            ' </p><span>10 minutes ago</span> ' +
                            ' </a> </div> </div> </li> ';

                        $('.notifContainer').prepend(
                            notif);

                        // setTimeout(realTime, 2000);
                    },
                });
                const iconPath = '{{ asset('images/new1.png') }}'
                Push.create("Hay, {{ Auth::user()->name }}", {
                    body: "please check there is an order that must be verified.",
                    timeout: 10000,
                    icon: iconPath,
                    vibrate: [200, 100],
                });
            });

        });
    </script>
@elsecan('isSuperAdmin')
    <script>
        $(document).ready(function() {
            Echo.channel('po_notif').listen('PoMessage', (e) => {
                let sounds = new Audio("{{ asset('sounds/so.wav') }}");
                let csrf = $('meta[name="csrf-token"]').attr("content");
                sounds.play();
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
                        let mark =
                            '<p class="f-w-700 mb-0">&nbsp;<span class="pull-right badge badge-primary badge-pill">' +
                            '<a href="/read_all_notif/{{ Auth::user()->job_id }}">Mark as readall</a></span></p>';
                        $('.mark-read').html(mark);


                        $('.notifCount').text(count + 1);
                        let animation =
                            ' <i class="fa-2x  text-danger  fa fa-bell fa-shake"></i><span class="dot-animated"></span>';
                        $('.notification-box').html(animation);
                        // $('.bell').remove();
                        let notif =
                            ' <li class="notif-primary"><div class="media"> <span class="notification-bg bg-light-primary"> <i class="fa fa-envelope">' +
                            ' </i></span> <div class="media-body"> <a href="/read_notif/' + data
                            .id +
                            '"><p> ' +
                            data.message +
                            ' </p><span>10 minutes ago</span> ' +
                            ' </a> </div> </div> </li> ';

                        $('.notifContainer').prepend(
                            notif);
                    },
                });
                const iconPath = '{{ asset('images/new1.png') }}'
                Push.create("Hay, {{ Auth::user()->name }}", {
                    body: "please check there is an purcahse order that must be approve.",
                    timeout: 10000,
                    icon: iconPath,
                    vibrate: [200, 100],
                });
            });
        });
    </script>
@endcan
