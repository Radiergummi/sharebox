@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="card" action="{{ route('templates.store') }}" method="post">
                    {{ csrf_field() }}

                    <header class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title template-name">{{ __('templates.create_template') }}</h3>
                        <div class="card-actions">
                            <button
                                    type="button"
                                    class="btn btn-light btn-icon edit-template-button"
                                    v-on:click.prevent="navigate('{{ route('templates.index') }}')"
                            >
                                <span class="material-icons">keyboard_backspace</span>
                                {{ __('global.cancel') }}
                            </button>
                            <button
                                    type="submit"
                                    class="btn btn-info btn-icon update-template-button"
                            >
                                <span class="material-icons">save</span>
                                {{ __('global.save') }}
                            </button>
                        </div>
                    </header>

                    <section class="card-body">
                        <div class="form-group">
                            <label for="field-name" class="card-subtitle label-required">{{ __('templates.name') }}</label>
                            <p class="text-muted">{{ __('templates.name_help') }}</p>
                            <input
                                    id="field-name"
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    required
                            />
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="field-description"
                                   class="card-subtitle">{{ __('templates.description') }}</label>
                            <p class="text-muted">{{ __('templates.description_help') }}</p>

                            <textarea
                                    id="field-description"
                                    name="description"
                                    class="form-control"
                                    rows="4"
                            ></textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="field-head" class="card-subtitle">{{ __('templates.html_head') }}</label>
                            <p class="text-muted">{{ __('templates.html_head_help') }}</p>

                            <textarea
                                    id="field-head"
                                    name="head"
                                    class="form-control code-input"
                                    rows="8"
                                    v-on:keydown.tab.prevent="handleTabInput"
                            ></textarea>
                            @if ($errors->has('head'))
                                <span class="text-danger">{{ $errors->first('head') }}</span>
                            @endif
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="field-body" class="card-subtitle label-required">{{ __('templates.html_body') }}</label>
                            <p class="text-muted">{{ __('templates.html_body_help') }}</p>

                            <textarea
                                    id="field-body"
                                    name="body"
                                    class="form-control code-input"
                                    rows="8"
                                    v-on:keydown.tab.prevent="handleTabInput"
                                    required
                            ></textarea>
                            @if ($errors->has('body'))
                                <span class="text-danger">{{ $errors->first('body') }}</span>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="field-footer" class="card-subtitle">{{ __('templates.html_footer') }}</label>
                            <p class="text-muted">{{ __('templates.html_footer_help') }}</p>
                            <textarea
                                    id="field-footer"
                                    name="footer"
                                    class="form-control code-input"
                                    rows="8"
                                    v-on:keydown.tab.prevent="handleTabInput"
                            ></textarea>
                            @if ($errors->has('footer'))
                                <span class="text-danger">{{ $errors->first('footer') }}</span>
                            @endif
                        </div>

                    </section>
                    <footer class="card-footer d-flex justify-content-end">
                        <button
                                type="button"
                                class="btn btn-light btn-icon edit-template-button"
                                v-on:click.prevent="navigate('{{ route('templates.index') }}')"
                        >
                            <span class="material-icons">keyboard_backspace</span>
                            {{ __('global.cancel') }}
                        </button>
                        <button
                                type="submit"
                                class="btn btn-info btn-icon update-template-button"
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
