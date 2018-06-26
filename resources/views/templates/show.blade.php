@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <article class="card">
                    <header class="card-header d-flex justify-content-between align-items-center">
                        @if ($template->locked)
                            <span
                                    class="material-icons template-locked"
                                    title="{{ __('templates.default_template_locked') }}"
                            >lock</span>
                        @endif
                        <h3 class="template-name card-title" v-pre>{{ $template->name }}</h3>
                        <span class="template-language">{{ $template->language }}</span>
                        <div class="card-actions">
                            <button
                                    class="btn btn-light btn-icon edit-template-button"
                                    @click="navigate('{{ route('templates.index') }}')"
                            >
                                <span class="material-icons">keyboard_backspace</span>
                                {{ __('global.back') }}
                            </button>
                            <button
                                    class="btn btn-info btn-icon edit-template-button"
                                    @click="navigate('{{ route('templates.edit', [ $template->id ]) }}')"
                            >
                                <span class="material-icons">edit</span>
                                {{ __('global.edit') }}
                            </button>
                        </div>
                    </header>

                    <section class="card-body">
                        <span class="card-subtitle">{{ __('templates.description') }}</span>
                        <p v-pre>
                            @if ($template->description)
                                {{ $template->description }}
                            @else
                                <span class="text-muted">({{ __('templates.no_description') }})</span>
                            @endif
                        </p>
                        <hr>
                        <span class="card-subtitle">{{ __('HTML head code') }}</span>
                        <p class="text-muted">{{ __('templates.html_head_help') }}</p>
                        @if ($template->head)
                            <pre v-highlightjs class="pre-scrollable">
                                <code class="html" v-pre>{{ $template->head }}</code>
                            </pre>
                        @else
                            <p class="text-muted">({{ __('templates.no_head') }})</p>
                        @endif
                        <hr>
                        <span class="card-subtitle">{{ __('HTML body code') }}</span>
                        <p class="text-muted">{{ __('templates.html_body_help') }}</p>
                        @if ($template->body)
                            <pre v-highlightjs class="pre-scrollable">
                                <code class="html" v-pre>{{ $template->body }}</code>
                            </pre>
                        @else
                            <p class="text-muted">({{ __('templates.no_body') }})</p>
                        @endif
                        <hr>
                        <span class="card-subtitle">{{ __('HTML footer code') }}</span>
                        <p class="text-muted">{{ __('templates.html_footer_help') }}</p>
                        @if ($template->footer)
                            <pre v-highlightjs class="pre-scrollable">
                                <code class="html" v-pre>{{ $template->footer }}</code>
                            </pre>
                        @else
                            <p class="text-muted">({{ __('templates.no_footer') }})</p>
                        @endif
                    </section>
                    @unless ($template->slot && ($template->slot === 'missing' || $template->slot === 'expired'))
                        <section class="card-body">
                        <span class="card-subtitle">
                            {{ trans_choice('templates.downloads_using', count($template->downloads), [
                              'count' => count($template->downloads)
                            ]) }}
                        </span>
                            <br>
                            <br>
                            @if (count($template->downloads) === 1)
                                <a href="{{ route('downloads.show', [ $template->downloads[0]->id ]) }}">
                                    {{ $template->downloads[0]->name ?? $template->downloads[0]->path }}
                                </a>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach ($template->downloads as $download)
                                        <li class="list-group-item">
                                            <a href="{{ route('downloads.show', [ $download->id ]) }}">
                                                {{ $download->name ?? $download->path }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </section>
                    @endunless
                    <footer class="card-footer d-flex justify-content-between">
                        <a
                                class="btn btn-info"
                                href="{{ route('templates.preview', [ 'template' => $template->id ]) }}"
                                target="_blank"
                        >
                            {{ __('templates.preview') }}
                        </a>
                        <button
                                class="btn btn-danger delete-template-button"
                                @if ($template->locked)
                                disabled
                                @endif
                                @click="deleteItem({{ $template->id }}, '{{ route('templates.destroy', [$template->id]) }}', '{{ route('templates.index') }}')"
                        >
                            <template v-if="confirmDeleteId === {{ $template->id }}">
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
