@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <article class="card">
                    <header class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title user-name" v-pre>{{ $user->full_name }}</h3>
                        <div class="card-actions">
                            <button
                                    class="btn btn-light btn-icon edit-user-button"
                                    @click="navigate('{{ route('users.index') }}')"
                            >
                                <span class="material-icons">keyboard_backspace</span>
                                {{ __('global.back') }}
                            </button>
                            <button
                                    class="btn btn-info btn-icon edit-user-button"
                                    @click="navigate('{{ route('users.edit', [ $user->id ]) }}')"
                            >
                                <span class="material-icons">edit</span>
                                {{ __('global.edit') }}
                            </button>
                        </div>
                    </header>

                    <section class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('users.name') }}</strong>
                                <span class="name" v-pre>{{ $user->full_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('users.username') }}</strong>
                                <span class="username" v-pre>{{ $user->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>{{ __('users.email') }}</strong>
                                <span class="email-address" v-pre>{{ $user->email }}</span>
                            </li>
                        </ul>
                    </section>
                    <footer class="card-footer d-flex justify-content-end">
                        <button
                                class="btn btn-danger delete-user-button"
                                @if (Auth::user()->id === $user->id)
                                disabled
                                @endif
                                @click="deleteItem({{ $user->id }}, '{{ route('users.destroy', [$user->id]) }}', '{{ route('users.index') }}')"
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
                    </footer>
                </article>
            </div>
        </div>
    </div>
@endsection
