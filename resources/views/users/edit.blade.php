@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="card" action="{{ route('users.update', [ $user->id ]) }}" method="post">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}

                    <header class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title user-name">
                            {{ __('users.edit_user', ['name' => $user->full_name]) }}
                        </h3>
                        <span class="user-language">{{ $user->language }}</span>
                        <div class="card-actions">
                            <button
                                    type="button"
                                    class="btn btn-light btn-icon edit-user-button"
                                    v-on:click.prevent="navigate('{{ route('users.show', [ $user->id ]) }}')"
                            >
                                <span class="material-icons">keyboard_backspace</span>
                                {{ __('global.cancel') }}
                            </button>
                            <button
                                    type="submit"
                                    class="btn btn-info btn-icon update-user-button"
                            >
                                <span class="material-icons">save</span>
                                {{ __('global.save') }}
                            </button>
                        </div>
                    </header>

                    <section class="card-body">
                        <div class="form-group">
                            <label for="field-name" class="card-subtitle label-required">{{ __('users.name') }}</label>
                            <p class="text-muted">{{ __('users.name_help') }}</p>
                            <input
                                    id="field-name"
                                    name="name"
                                    value="{{ $user->full_name }}"
                                    type="text"
                                    class="form-control"
                                    autocomplete="off"
                            />
                            @if ($errors->has('full_name'))
                                <span class="text-danger">{{ $errors->first('full_name') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="field-username"
                                   class="card-subtitle label-required">{{ __('users.username') }}</label>
                            <p class="text-muted">{{ __('users.username_help') }}</p>
                            <input
                                    id="field-username"
                                    name="username"
                                    value="{{ $user->name }}"
                                    type="text"
                                    class="form-control"
                                    autocomplete="off"
                            />
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="field-email"
                                   class="card-subtitle label-required">{{ __('users.email') }}</label>
                            <p class="text-muted">{{ __('users.email_help') }}</p>
                            <input
                                    id="field-email"
                                    name="email"
                                    value="{{ $user->email }}"
                                    type="email"
                                    class="form-control"
                                    autocomplete="off"
                            />
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="field-new-password" class="card-subtitle">{{ __('users.new_password') }}</label>
                            <p class="text-muted">{{ __('users.new_password_help') }}</p>
                            <input
                                    id="field-new-password"
                                    name="password"
                                    type="password"
                                    class="form-control"
                                    placeholder="********"
                                    autocomplete="off"
                            />
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </section>
                    <footer class="card-footer d-flex justify-content-end">
                        <button
                                type="button"
                                class="btn btn-light btn-icon edit-user-button"
                                v-on:click.prevent="navigate('{{ route('users.show', [ $user->id ]) }}')"
                        >
                            <span class="material-icons">keyboard_backspace</span>
                            {{ __('global.cancel') }}
                        </button>
                        <button
                                type="submit"
                                class="btn btn-info btn-icon update-user-button"
                        >
                            <span class="material-icons">save</span>
                            {{ __('global.save') }}
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
@endsection
