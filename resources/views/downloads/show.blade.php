@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <article class="card">
                    <header class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title download-name" v-pre>
                            {{ $download->name ?? $download->path }}
                        </h3>
                        <div class="card-actions">
                            <button
                                    class="btn btn-light btn-icon edit-download-button"
                                    @click="navigate('{{ route('downloads.index') }}')"
                            >
                                <span class="material-icons">keyboard_backspace</span>
                                {{ __('global.back') }}
                            </button>
                            <button
                                    class="btn btn-info btn-icon edit-download-button"
                                    @click="navigate('{{ route('downloads.edit', [ $download->id ]) }}')"
                            >
                                <span class="material-icons">edit</span>
                                {{ __('global.edit') }}
                            </button>
                        </div>
                    </header>

                    <section class="card-body">
                        <div class="download-link-container">
                            <span class="card-subtitle">{{ __('downloads.link') }}</span>
                            <p>
                                <a href="{{ route('downloads.landing', [ $download->uuid ]) }}" target="_blank">
                                    {{ route('downloads.landing', [ $download->uuid ]) }}
                                </a>
                            </p>
                            <div class="download-count text-right text-muted">{{ trans_choice('downloads.times_downloaded', $download->download_count, [ 'count' => $download->download_count ]) }}</div>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('downloads.unique_id') }}</strong>
                                <span>{{ $download->uuid }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('downloads.name') }}</strong>
                                <span v-pre>{{ $download->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('downloads.path') }}</strong>
                                <span v-pre>{{ $download->path }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('downloads.size') }}</strong>
                                <span>{{ $download->readableSize() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('downloads.template') }}</strong>
                                <span>
                                    <a href="{{ route('templates.show', [ $download->template->id ]) }}" v-pre>
                                        {{ $download->template->name }}
                                    </a>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('downloads.expires_in') }}</strong>
                                @if ($download->expires)
                                    <span class="has-title"
                                          title="{{ (new \Carbon\Carbon($download->expires))->format('d.m.Y H:i') }}">
                                    {{ (new \Carbon\Carbon($download->expires))->diffForHumans() }}
                                </span>
                                @else
                                    <span>{{ __('downloads.expires_never') }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('downloads.password_set') }}</strong>
                                <span>{{ !is_null($download->password) ? __('global.yes') : __('global.no') }}</span>
                            </li>
                        </ul>
                    </section>

                    <footer class="card-footer d-flex justify-content-end">
                        <button
                                class="btn btn-danger delete-download-button"
                                @if ($download->locked)
                                disabled
                                @endif
                                @click="deleteItem({{ $download->id }}, '{{ route('downloads.destroy', [$download->id]) }}', '{{ route('downloads.index') }}')"
                        >
                            <template v-if="confirmDeleteId === {{ $download->id }}">
                                            <span class="material-icons">
                                                warning
                                            </span>
                                {{ __('global.delete_confirmation') }}
                            </template>
                            <template v-else>
                                            <span class="material-icons">
                                                delete
                                            </span>
                                {{ __('global.delete') }}
                            </template>
                        </button>
                    </footer>
                </article>
            </div>
        </div>
    </div>
@endsection
