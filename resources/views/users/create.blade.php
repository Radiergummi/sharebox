@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="card" action="{{ route('users.store') }}" method="post">
                    {{ csrf_field() }}

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title user-name">{{ __('users.create_user') }}</h3>
                        <div class="card-actions">
                            <button
                                    type="button"
                                    class="btn btn-light btn-icon edit-user-button"
                                    v-on:click.prevent="navigate('{{ route('users.index') }}')"
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
                    </div>

                    <section class="card-body">
                        <div class="form-group">
                            <label for="field-name" class="card-subtitle label-required">{{ __('users.name') }}</label>
                            <p class="text-muted">{{ __('users.name_help') }}</p>
                            <input
                                    id="field-name"
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    required
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
                                    type="text"
                                    class="form-control"
                                    required
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
                                    type="email"
                                    class="form-control"
                                    required
                                    autocomplete="off"
                            />
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="field-password" class="card-subtitle">{{ __('users.password') }}</label>
                            <p class="text-muted">{{ __('users.password_help') }}</p>
                            <input
                                    id="field-password"
                                    name="password"
                                    type="password"
                                    class="form-control"
                                    placeholder="********"
                                    required
                                    autocomplete="new-password"
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
                                v-on:click.prevent="navigate('{{ route('users.index') }}')"
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
