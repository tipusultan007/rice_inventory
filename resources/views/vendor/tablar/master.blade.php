<!doctype html>
<html lang="{{ Config::get('app.locale') }}" {!! config('tablar.layout') == 'rtl' ? 'dir="rtl"' : '' !!}>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')
    {{-- Title --}}
    <title>
        @yield('title_prefix', config('tablar.title_prefix', ''))
        @yield('title', config('tablar.title', 'Tablar'))
        @yield('title_postfix', config('tablar.title_postfix', ''))
    </title>
{{--
    <script type="module" src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.0/b-3.0.0/sb-1.7.0/datatables.min.js"></script>
--}}
   {{-- <script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script type="module" src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.2/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/r-3.0.0/sr-1.4.0/datatables.min.js"></script> --}}   <!-- CSS files -->
    @if(config('tablar','vite'))
        @vite('resources/js/app.js')
    @endif

    @yield('tablar_css')

    <link rel="stylesheet" href="{{asset('fonts/style.css')}}">
    <link href="{{asset('DataTables/datatables.min.css')}}" rel="stylesheet">

</head>
<body>
@yield('body')
@include('tablar::extra.modal')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script  src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@yield('tablar_js')
@yield('scripts')
<script src="{{asset('DataTables/datatables.min.js')}}"></script>

<script type="module">
    Echo.channel('csv-generated')
        .listen('.csv-file-event', (e) => {
            console.log(e);

            let notification = e.notification;

            let notificationCountElement = $('.notification-count');
            let currentCount = parseInt(notificationCountElement.html());
            notificationCountElement.html(currentCount + 1);

            let notificationListElement = $('.notification-list');
            let newNotificationElement = `<div class="list-group-item py-1">
                            <div class="row align-items-center">

                                <div class="col text-truncate">
                                    ${notification.title}
                                    <div class="d-block text-muted text-truncate mt-n1">
                                        ${notification.message}
                                    </div>
                                    <small class="text-muted">
                                       ${formatTimeAgoBengali(notification.created_at)}
                                    </small>
                                </div>

                            </div>
                        </div>`;
            notificationListElement.prepend(newNotificationElement);
        });

    function formatTimeAgoBengali(timestamp) {
        const now = new Date();
        const diff = now - new Date(timestamp);
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (days > 0) {
            return days === 1 ? '1 দিন আগে' : `${days} দিন আগে`;
        } else if (hours > 0) {
            return hours === 1 ? '1 ঘণ্টা আগে' : `${hours} ঘণ্টা আগে`;
        } else if (minutes > 0) {
            return minutes === 1 ? '1 মিনিট আগে' : `${minutes} মিনিট আগে`;
        } else {
            return 'এইমাত্র';
        }
    }
    $(document).ready(function() {
        var notificationList = $('.notification-list');
        var loaderContainer = $('.loader-container');

        // Show loader icon
        loaderContainer.show();
        // Fetch notifications via AJAX
        $.ajax({
            url: "{{ route('notifications.index') }}",
            method: "GET",
            success: function(response) {
                // Hide loader icon
                loaderContainer.hide();

                // Update notification count
                $('.notification-count').text(response.length);

                // Update notification list
                var notificationList = $('.notification-list');
                notificationList.empty(); // Clear existing notifications

                $.each(response, function(index, notification) {
                    var notificationItem = `
                        <div class="list-group-item py-1">
                            <div class="row align-items-center">

                                <div class="col text-truncate">
                                    ${notification.data.title}
                                    <div class="d-block text-muted text-truncate mt-n1">
                                        ${notification.data.message}
                                    </div>
                                    <small class="text-muted">
                                       ${formatTimeAgoBengali(notification.created_at)}
                                    </small>
                                </div>

                            </div>
                        </div>
                    `;
                    notificationList.append(notificationItem);
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
</script>

</body>
</html>
