@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <header class="card-header">
                        <h2 class="card-title">{{ __('dashboard.dashboard') }}</h2>
                    </header>
                </div>
                <div class="container container-fluid px-0">
                    <div class="row py-2 py-md-3">
                        <div class="col">
                            <div class="card widget-card">
                                <header class="card-header">
                                    <h4 class="card-title">
                                        {{ trans_choice('downloads.downloads_count', $downloadCount, [ 'count' => $downloadCount ]) }}
                                    </h4>
                                </header>

                                <div class="card-body">
                                    @if ($downloadCount === 0)
                                        <div class="empty-state">
                                            <span class="material-icons empty-state-icon">cloud_off</span>
                                            <span class="empty-state-title">
                                                {{ __('downloads.none_yet') }}
                                            </span>
                                            <span class="empty-state-help">
                                                {{ __('downloads.none_yet_help') }}
                                            </span>
                                            <button
                                                    class="btn btn-primary btn-icon empty-state-cta"
                                                    v-on:click="navigate('{{ route('downloads.create') }}')"
                                            >
                                                <span class="material-icons">add</span>
                                                {{ __('downloads.create_download') }}
                                            </button>
                                        </div>
                                    @else
                                    <!--
                                        <div class="download-charts">
                                            <tickets-chart v-bind:tickets="{{ json_encode($tickets) }}"></tickets-chart>
                                        </div>
                                    -->

                                        <ul class="list-group list-group-flush">
                                            @for ($i = 0; $i < min(5, $downloadCount); $i++)
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span class="download-name">
                                                        {{ $downloads[$i]['name'] }}
                                                    </span>
                                                    <span class="times-downloaded">
                                                        {{ trans_choice('downloads.times_downloaded', $downloads[$i]['download_count'], [ 'count' => $downloads[$i]['download_count'] ]) }}
                                                    </span>
                                                </li>
                                            @endfor
                                            @if ($downloadCount > 5)
                                                <li class="list-group-item">
                                                    <span>
                                                        {{ trans_choice('downloads.x_more', $downloadCount - 5, [ 'count' => $downloadCount - 5 ]) }}
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </div>

                                @if ($downloadCount > 0)
                                    <footer class="card-footer d-flex justify-content-between">
                                        <button
                                                class="btn btn-icon btn-light"
                                                v-on:click="navigate('{{ route('downloads.index') }}')"
                                        >
                                            <span class="material-icons">list</span>
                                            {{ __('global.list') }}
                                        </button>
                                        <button
                                                class="btn btn-icon btn-info"
                                                v-on:click="navigate('{{ route('downloads.create') }}')"
                                        >
                                            <span class="material-icons">add</span>
                                            {{ __('global.create_new') }}
                                        </button>
                                    </footer>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col py-2 py-md-3">
                            <div class="card widget-card">
                                <header class="card-header">
                                    <h4 class="card-title">
                                        {{ trans_choice('templates.templates_count', $templateCount, [ 'count' => $templateCount ]) }}
                                    </h4>
                                </header>

                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @for ($i = 0; $i < min(5, $templateCount); $i++)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span class="template-name">{{ $templates[$i]->name }}</span>
                                                <span class="template-language">{{ $templates[$i]->language }}</span>
                                            </li>
                                        @endfor
                                        @if ($templateCount > 5)
                                            <li class="list-group-item">
                                                <span>
                                                    {{ trans_choice('templates.x_more', $templateCount - 5, [ 'count' => $templateCount - 5 ]) }}
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <footer class="card-footer d-flex justify-content-between">
                                    <button
                                            class="btn btn-icon btn-light"
                                            v-on:click="navigate('{{ route('templates.index') }}')"
                                    >
                                        <span class="material-icons">list</span>
                                        {{ __('global.list') }}
                                    </button>
                                    <button
                                            class="btn btn-icon btn-info"
                                            v-on:click="navigate('{{ route('templates.create') }}')"
                                    >
                                        <span class="material-icons">add</span>
                                        {{ __('templates.create_template') }}
                                    </button>
                                </footer>
                            </div>
                        </div>

                        <div class="col py-2 py-md-3">
                            <div class="card widget-card">
                                <header class="card-header">
                                    <h4 class="card-title">
                                        {{ trans_choice('users.users_count', $userCount, [ 'count' => $userCount ]) }}
                                    </h4>
                                </header>

                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @for ($i = 0; $i < min(5, $userCount); $i++)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span class="user-full-name">{{ $users[$i]->full_name }}</span>
                                                <span class="template-language">{{ $users[$i]->name }}</span>
                                            </li>
                                        @endfor
                                        @if ($userCount > 5)
                                            <li class="list-group-item">
                                                <span>
                                                    {{ trans_choice('users.x_more', $userCount - 5, [ 'count' => $userCount - 5 ]) }}
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <footer class="card-footer d-flex justify-content-between">
                                    <button
                                            class="btn btn-icon btn-light"
                                            v-on:click="navigate('{{ route('users.index') }}')"
                                    >
                                        <span class="material-icons">list</span>
                                        {{ __('global.list') }}
                                    </button>
                                    <button
                                            class="btn btn-icon btn-info"
                                            v-on:click="navigate('{{ route('users.create') }}')"
                                    >
                                        <span class="material-icons">add</span>
                                        {{ __('users.create_user') }}
                                    </button>
                                </footer>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
