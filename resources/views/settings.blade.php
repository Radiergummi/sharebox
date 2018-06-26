@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form
                        method="post"
                        action="{{ route('settings.update') }}"
                        class="logo-section"
                        enctype="multipart/form-data"
                >
                    <div class="card">
                        <header class="card-header">
                            <h2 class="card-title">{{ __('settings.settings') }}</h2>
                        </header>

                        <div class="card-body">

                            {{ csrf_field() }}

                            <h3 class="card-subtitle">{{ __('settings.upload_logo') }}</h3>
                            <p class="text-muted" v-pre>
                                {!! __('settings.upload_logo_help') !!}
                            </p>
                            <file-upload
                                    v-if="logoUrl"
                                    name="logo"
                                    type="image/png,image/jpg,image/jpeg,image/svg+xml"
                                    v-bind:initial="logoUrl"
                                    v-on:preview-rendered="updateAppLogo"
                            >
                                <template slot="button-label">{{ __('global.browse') }}</template>
                            </file-upload>
                            @if ($errors->has('logo'))
                                <p class="text-danger">{{ $errors->first('logo') }}</p>
                            @endif

                            <hr>

                            <div class="form-group">
                                <h3 class="card-subtitle">{{ __('settings.app_colors') }}</h3>
                                <p class="text-muted">{!! __('settings.primary_color_help') !!}</p>
                                <label for="field-primary_color">{{ __('settings.primary_color') }}</label>

                                <color-field
                                        name="primary_color"
                                        placeholder="{{ $defaultPrimaryColor }}"
                                        initial="{{ $primaryColor ?? $defaultPrimaryColor }}"
                                        v-bind:required="true"
                                ></color-field>
                            </div>
                        </div>

                        <footer class="card-footer d-flex justify-content-end">
                            <button
                                    type="submit"
                                    class="btn btn-info btn-icon upload-logo-button"
                            >
                                <span class="material-icons">save</span>
                                {{ __('global.save') }}
                            </button>
                        </footer>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
