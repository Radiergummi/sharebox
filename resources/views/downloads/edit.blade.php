@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="card" action="{{ route('downloads.update', [ $download->id ]) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <header class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title download-name">
                            {{ __('downloads.edit_download', [ 'name' => $download->name ]) }}
                        </h3>
                        <div class="card-actions">
                            <button
                                    type="button"
                                    class="btn btn-light btn-icon edit-download-button"
                                    v-on:click.prevent="navigate('{{ route('downloads.show', [ $download->id ]) }}')"
                            >
                                <span class="material-icons">keyboard_backspace</span>
                                {{ __('global.cancel') }}
                            </button>
                            <button
                                    type="submit"
                                    class="btn btn-info btn-icon update-download-button"
                            >
                                <span class="material-icons">save</span>
                                {{ __('global.save') }}
                            </button>
                        </div>
                    </header>

                    <section class="card-body">
                        <div class="form-group">
                            <label for="field-path"
                                   class="card-subtitle label-required">{{ __('downloads.path') }}</label>
                            <p class="text-muted">{{ __('downloads.path_help') }}</p>
                            <file-browser path="{{ $download->path }}"></file-browser>
                            @if ($errors->has('path'))
                                <span class="text-danger">{{ $errors->first('path') }}</span>
                            @endif
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="field-name" class="card-subtitle">{{ __('downloads.name') }}</label>
                            <p class="text-muted">{{ __('downloads.name_help') }}</p>
                            <input
                                    id="field-name"
                                    name="name"
                                    type="text"
                                    value="{{ $download->name }}"
                                    placeholder="{{ __('downloads.name_leave_empty_note') }}"
                                    class="form-control"
                                    autocomplete="off"
                            />
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="field-expires" class="card-subtitle">{{ __('downloads.expires') }}</label>
                            <p class="text-muted">{{ __('downloads.expires_help') }}</p>

                            <date-field
                                    id="field-expires"
                                    name="expires"
                                    initial="{{ $download->expires }}"
                                    min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d')}}"
                                    max="{{ (new \Carbon\Carbon())->addYears(10)->format('Y-m-d')}}"
                                    placeholder="{{ __('global.select_date') }}"
                                    reset-title="{{ __('global.reset') }}"
                            ></date-field>

                            @if ($errors->has('expires'))
                                <span class="text-danger">{{ $errors->first('expires') }}</span>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="field-template" class="card-subtitle">{{ __('downloads.template') }}</label>
                            <p class="text-muted">{{ __('downloads.template_help') }}</p>
                            <select
                                    id="field-template"
                                    name="template"
                                    class="form-control"
                            >
                                @foreach ($templates as $group)
                                    <optgroup label="{{ __('languages.' . $group['language']) }}">
                                        @foreach ($group['templates'] as $template)
                                            @unless ($template->slot === 'missing' || $template->slot === 'expired')
                                                <option
                                                        value="{{ $template->id }}"
                                                        {{ $download->template->id === $template->id ? ' selected' : '' }}
                                                >{{ $template->name }}</option>
                                            @endunless
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @if ($errors->has('template'))
                                <span class="text-danger">{{ $errors->first('template') }}</span>
                            @endif
                        </div>
                        <hr>

                        <div class="form-group">
                            <label for="field-password" class="card-subtitle">{{ __('downloads.password') }}</label>
                            <p class="text-muted">{{ __('downloads.password_help') }}</p>
                            <input
                                    id="field-password"
                                    name="password"
                                    type="text"
                                    class="form-control"
                                    value="{{ $download->password }}"
                                    placeholder="{{ __('downloads.password_leave_empty_note') }}"
                                    autocomplete="off"
                            />
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <hr>

                    </section>
                    <footer class="card-footer d-flex justify-content-end">
                        <button
                                type="button"
                                class="btn btn-light btn-icon edit-download-button"
                                v-on:click.prevent="navigate('{{ route('downloads.index') }}')"
                        >
                            <span class="material-icons">keyboard_backspace</span>
                            {{ __('global.cancel') }}
                        </button>
                        <button
                                type="submit"
                                class="btn btn-info btn-icon update-download-button"
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
