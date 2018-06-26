@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('downloads.downloads') }}</h3>

                        <div class="card-actions">
                            <button
                                    class="btn btn-info btn-icon update-download-button"
                                    v-on:click="navigate('{{ route('downloads.create') }}')"
                            >
                                <span class="material-icons">add</span>
                                {{ __('global.create') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="list-group">
                            @unless (count($downloads))
                                <li class="list-group-item no-data">{{ __('downloads.none_yet') }}</li>
                            @endunless
                            @foreach ($downloads as $download)
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="download-name" v-pre>{{ $download->name ?? $download->path }}</span>
                                    <button
                                            class="btn btn-info btn-icon show-download-button"
                                            @click="navigate('{{ route('downloads.show', [ $download->id ]) }}')"
                                    >
                                        <span class="material-icons">launch</span>
                                        {{ __('global.show') }}
                                    </button>
                                    <button
                                            class="btn btn-danger delete-download-button"
                                            @click="deleteItem({{ $download->id }}, '{{ route('downloads.destroy', [$download->id]) }}')"
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
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
