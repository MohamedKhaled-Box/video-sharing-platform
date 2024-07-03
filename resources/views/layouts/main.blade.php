<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos Platform</title>

    <!-- bootstarp -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>

    <!-- fontawesome -->
    <script src="https://kit.fontawesome.com/597cb1f685.js" crossorigin="anonymous"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @livewireStyles

    <link href="{!! asset('theme/css/sb-admin-2.css') !!}" rel="stylesheet">
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('a0d6cc95560992d9f759', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script>
</head>

<body dir="rtl" style="text-align: right">
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light bg-secondary">
            <a class="navbar-brand" href="{{ route('dashboard') }}">فيديو حسوب</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item  {{ request()->is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('main') }}">
                            <i class="fas fa-home"></i>
                            الصفحة الرئيسية
                        </a>
                    </li>

                    @auth
                        <li class="nav-item {{ request()->is('history') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('history') }}">
                                <i class="fas fa-history"></i>
                                سجل المشاهدة
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('videos/create*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('videos.create') }}">
                                <i class="fas fa-upload"></i>
                                رفع فيديو
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('videos') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('videos.index') }}">
                                <i class="far fa-play-circle"></i>
                                فيديوهاتي
                            </a>
                        </li>
                    @endauth

                    <li class="nav-item {{ request()->is('channel*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('channel.index') }}">
                            <i class="fas fa-film"></i>
                            القنوات
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav mr-auto">
                    <div class="topbar" style="z-index:1">
                        @auth
                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown no-arrow alert-dropdown mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw fa-lg"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter notif-count"
                                        data-count="{{ App\Models\Alert::where('user_id', Auth::user()->id)->first()->alert }}">{{ App\Models\Alert::where('user_id', Auth::user()->id)->first()->alert }}</span>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right text-right mt-2"
                                    aria-labelledby="alertsDropdown">
                                    <div class="alert-body">

                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500"
                                        href="{{ route('all.notification') }}">عرض جميع
                                        الإشعارات</a>
                                </div>
                            </li>
                        @endauth
                    </div>
                    @guest
                        <li class="nav-item mt-2">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('تسجيل الدخول') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item mt-2">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('إنشاء حساب') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown justify-content-left mt-2">
                            <a id="navbarDropdown" class="nav-link" href="#" data-toggle="dropdown">
                                <img class="rounded-circle" src="{{ Auth::user()->profile_photo_url }}"
                                    alt="{{ Auth::user()->name }}" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-left px-2 text-right mt-2">
                                @can('update-videos')
                                    <a href="{{ route('admin.index') }}" class="dropdown-item text-right">لوحة الإدارة</a>
                                @endcan
                                <div class="pt-4 pb-1 border-t border-gray-200">
                                    <div class="flex items-center px-4">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <div class="shrink-0 me-3">
                                                <img class="rounded-circle" src="{{ Auth::user()->profile_photo_url }}"
                                                    alt="{{ Auth::user()->name }}" />
                                            </div>
                                        @endif

                                        <div>
                                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-1">
                                        <!-- Account Management -->
                                        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                                            {{ __('site.profile') }}
                                        </x-responsive-nav-link>

                                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <x-responsive-nav-link href="{{ route('api-tokens.index') }}"
                                                :active="request()->routeIs('api-tokens.index')">
                                                {{ __('site.api_token') }}
                                            </x-responsive-nav-link>
                                        @endif

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}" x-data>
                                            @csrf

                                            <x-responsive-nav-link href="{{ route('logout') }}"
                                                @click.prevent="$root.submit();">
                                                {{ __('site.logout') }}
                                            </x-responsive-nav-link>
                                        </form>

                                        <!-- Team Management -->
                                        @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                            <div class="border-t border-gray-200"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('site.manage_team') }}
                                            </div>

                                            <!-- Team Settings -->
                                            <x-responsive-nav-link
                                                href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                                :active="request()->routeIs('teams.show')">
                                                {{ __('site.team_settings') }}
                                            </x-responsive-nav-link>

                                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                                <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                                    {{ __('site.new_team') }}
                                                </x-responsive-nav-link>
                                            @endcan

                                            <!-- Team Switcher -->
                                            @if (Auth::user()->allTeams()->count() > 1)
                                                <div class="border-t border-gray-200"></div>

                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{ __('site.team_switch') }}
                                                </div>

                                                @foreach (Auth::user()->allTeams() as $team)
                                                    <x-switchable-team :team="$team" component="responsive-nav-link" />
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4">

            @if (Session::has('success'))
                <div class="p-3 mb-2 bg-success text-white rounded mx-auto col-8">
                    <span class="text-center">{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script src="{{ asset('js/pushNotifications.js') }}"></script>
    <script src="{{ asset('js/failedNotifications.js') }}"></script>

    <script>
        var token = '{{ Session::token() }}';
        // console.log('asd');
        var urlNotify = '{{ route('notification') }}';
        $('#alertsDropdown').on('click', function(event) {
            event.preventDefault();

            var notificationsWrapper = $('.alert-dropdown');
            var notificationsToggle = notificationsWrapper.find("a[data-toggle]");
            var notificationsCountElem = notificationsToggle.find("span[data-count]");

            notificationsCount = 0;
            notificationsCountElem.attr("data-count", notificationsCount);
            notificationsWrapper.find(".notif-count").text(notificationsCount);
            notificationsWrapper.show();

            $.ajax({
                method: 'POST',
                url: urlNotify,
                data: {
                    _token: token
                },
                success: function(data) {
                    var resposeNotifications = "";
                    $.each(data.someNotifications, function(i, item) {
                        var responseDate = new Date(item.created_at);
                        var date = responseDate.getFullYear() + '-' + (responseDate.getMonth() +
                            1) + '-' + responseDate.getDate();
                        var time = responseDate.getHours() + ":" + responseDate.getMinutes() +
                            ":" + responseDate.getSeconds();
                        if (item.success) {
                            resposeNotifications +=
                                '<a class="dropdown-item d-flex align-items-center" href="#">\
                                                                                                                                                                                <div class="ml-3">\
                                                                                                                                                                                    <div class="icon-circle bg-secondary">\
                                                                                                                                                                                        <i class="far fa-bell text-white"></i>\
                                                                                                                                                                                    </div>\
                                                                                                                                                                                    </div>\
                                                                                                                                                                                    <div>\
                                                                                                                                                                                        <div class="small text-gray-500">' +
                                date +
                                ' الساعة ' +
                                time +
                                '</div>\
                                                                                                                                                                                    <span>تهانينا لقد تم معالجة مقطع الفيديو <b>' +
                                item
                                .notification + '</b> بنجاح</span>\
                                                                                                                    </div>\
                                                                                                                    </a>';
                        } else {
                            resposeNotifications +=
                                '<a class="dropdown-item d-flex align-items-center" href="#">\
                                                                                                                                                                                <div class="ml-3">\
                                                                                                                                                                                    <div class="icon-circle bg-secondary">\
                                                                                                                                                                                        <i class="far fa-bell text-white"></i>\
                                                                                                                                                                                    </div>\
                                                                                                                                                                                </div>\
                                                                                                                                                                                <div>\
                                                                                                                                                                                    <div class="small text-gray-500">' +
                                date +
                                ' الساعة ' +
                                time +
                                '</div>\
                                                                                                                                                                                    <span>للأسف حدث خطأ غير متوقع أثناء معالجة مقطع الفيديو <b>' +
                                item.notification +
                                '</b> يرجى رفعه مرة أخرى</span>\
                                                                                                                                                                                </div>\
                                                                                                                                                                            </a>';
                        }
                        $('.alert-body').html(resposeNotifications);
                    });
                }
            });
        });
    </script>
    @yield('script')
</body>

</html>
