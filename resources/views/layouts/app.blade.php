<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <script src="{{ mix('js/app.js') }}" defer></script>
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        @if (file_exists(public_path('storage/custom.css')))
            <link href="{{ asset('storage/custom.css') }}" rel="stylesheet">
        @endif

        <script>
          window.__CONFIG__ = {
            baseUrl: '{{ url('/') }}',
            apiUrl:  '{{ url('/') }}/api'
          };
        </script>
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-dark navbar-laravel">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        @if (file_exists(public_path('storage/logo.svg')))
                            <img class="logo" v-bind:src="logoUrl" data-src="{{ asset('storage/logo.svg') }}">
                        @elseif (file_exists(public_path('storage/logo.png')))
                            <img class="logo" v-bind:src="logoUrl" data-src="{{ asset('storage/logo.png') }}">
                        @elseif (file_exists(public_path('storage/logo.jpg')))
                            <img class="logo" v-bind:src="logoUrl" data-src="{{ asset('storage/logo.jpg') }}">
                        @else
                            <img class="logo" v-bind:src="logoUrl" data-src="">
                        @endif
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link {{{ (Request::is('downloads*') ? 'active' : '') }}}"
                                   href="{{ route('downloads.index') }}">{{ __('downloads.downloads') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{{ (Request::is('users*') ? 'active' : '') }}}"
                                   href="{{ route('users.index') }}">
                                    {{ __('users.users') }}
                                </a>
                            </li>
                            <li class="nav-item {{{ (Request::is('templates*') ? 'active' : '') }}}">
                                <a class="nav-link"
                                   href="{{ route('templates.index') }}">{{ __('templates.templates') }}</a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                @unless (Request::is('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                                    </li>
                                @endunless
                            @else
                                <user-menu logout-route="{{ route('logout') }}">
                                    <template slot="toggle">{{ Auth::user()->name }}</template>
                                    <template slot="logout">{{ __('auth.logout') }}</template>
                                    <template slot="items">
                                        <li class="nav-item dropdown-item">
                                            <a class="nav-link"
                                               href="{{ route('settings.index') }}">{{ __('settings.settings') }}</a>
                                        </li>
                                        <li class="nav-item dropdown-item">
                                            <a class="nav-link" href="{{ route('help') }}">{{ __('help.help') }}</a>
                                        </li>
                                    </template>
                                </user-menu>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>

            <footer class="container main-footer">
                <div class="row">
                    <div class="col-md-12 text-muted py-4">
                        <div class="d-flex justify-content-between">
                            <span>Sharebox · Copyrightless {{ date('Y') }} · Open Source Software (MIT)</span>
                            <span>Developed by <a class="text-muted" href="https://www.moritzfriedrich.com" target="_blank">Moritz Friedrich</a></span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
