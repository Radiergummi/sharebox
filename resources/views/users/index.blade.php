@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('users.users') }}</h3>

                        <div class="card-actions">
                            <button
                                    class="btn btn-info btn-icon update-user-button"
                                    v-on:click="navigate('{{ route('users.create') }}')"
                            >
                                <span class="material-icons">add</span>
                                {{ __('global.create') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($users as $user)
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <span class="user-full-name" v-pre>{{ $user->full_name }}</span>
                                    <span class="user-name" v-pre>{{ $user->name }}</span>
                                    <button
                                            class="btn btn-info btn-icon show-user-button"
                                            @click="navigate('{{ route('users.show', [ $user->id ]) }}')"
                                    >
                                        <span class="material-icons">launch</span>
                                        {{ __('global.show') }}
                                    </button>
                                    <button
                                            class="btn btn-danger delete-user-button"
                                            @if (Auth::user()->id === $user->id)
                                            disabled
                                            @endif
                                            @click="deleteItem({{ $user->id }}, '{{ route('users.destroy', [$user->id]) }}')"
                                    >
                                        <user v-if="confirmDeleteId === {{ $user->id }}">
                                            <span class="material-icons">
                                                warning
                                            </span>
                                            {{ __('global.delete_confirmation') }}
                                        </user>
                                        <user v-else>
                                            <span class="material-icons">
                                                delete
                                            </span>
                                            {{ __('global.delete') }}
                                        </user>
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
