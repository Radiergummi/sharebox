@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('templates.templates') }}</h3>

                        <div class="card-actions">
                            <button
                                    class="btn btn-info btn-icon update-template-button"
                                    v-on:click="navigate('{{ route('templates.create') }}')"
                            >
                                <span class="material-icons">add</span>
                                {{ __('global.create') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($templates as $template)
                                <li
                                        class="list-group-item d-flex align-items-center justify-content-between {{ $template->locked ? 'default-template' : 'user-template' }}"
                                >
                                    @if ($template->locked)
                                        <span
                                                class="material-icons template-locked"
                                                title="{{ __('templates.delete_default_template_info') }}"
                                        >lock</span>
                                    @endif
                                    <a
                                            href="{{ route('templates.show', [ $template->id ]) }}"
                                            class="template-name"
                                            v-pre
                                    >
                                        {{ $template->name }}
                                    </a>
                                    <span class="template-language">{{ $template->language }}</span>
                                    <button
                                            class="btn btn-info btn-icon show-template-button"
                                            @click="navigate('{{ route('templates.show', [ $template->id ]) }}')"
                                    >
                                        <span class="material-icons">launch</span>
                                        {{ __('global.show') }}
                                    </button>
                                    <button
                                            class="btn btn-danger delete-template-button"
                                            @if ($template->locked)
                                            disabled
                                            @endif
                                            @click="deleteItem({{ $template->id }}, '{{ route('templates.destroy', [$template->id]) }}')"
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
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
