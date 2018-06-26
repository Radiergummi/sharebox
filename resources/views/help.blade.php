@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3 py-2">
                <h4 class="text-muted my-2">Navigation</h4>
                <nav class="py-4 sticky-top position-sticky text-info d-flex flex-column align-items-stretch">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent px-0 py-1">
                            <a href="#general">{{ __('help.general_heading') }}</a>
                        </li>
                        <li class="list-group-item bg-transparent px-0 py-1">
                            <a href="#templates">{{ __('help.templates.heading') }}</a>

                            <ul class="list-group list-group-flush pl-3 mt-1">
                                <li class="list-group-item bg-transparent px-0 py-1">
                                    <a href="#downloads">{{ __('help.templates.how_downloads_work_heading') }}</a>
                                </li>
                                <li class="list-group-item bg-transparent px-0 py-1">
                                    <a href="#javascript-api">{{ __('help.templates.javascript_api_heading') }}</a>
                                </li>
                                <li class="list-group-item bg-transparent px-0 py-1">
                                    <a href="#variables">{{ __('help.templates.variables_heading') }}</a>
                                </li>
                            </ul>
                        </li>
                        <li class="list-group-item bg-transparent px-0 py-1">
                            <a href="#user-management">{{ __('help.user_management.heading') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <header class="card-header">
                        <h2 class="card-title">{{ __('help.help') }}</h2>
                    </header>

                    <div class="card-body">
                        <p>
                            {!! __('help.intro') !!}
                        </p>
                        <section class="help-section" v-pre>
                            <header class="help-section-header">
                                <h3 id="general">{{ __('help.general_heading') }}</h3>
                            </header>
                            <p>{!! __('help.general') !!}</p>
                        </section>
                        <section class="help-section" v-pre>
                            <header class="help-section-header">
                                <h3 id="templates">{{ __('help.templates.heading') }}</h3>
                            </header>
                            <p>{!! __('help.templates.intro') !!}</p>
                            <p>{!! __('help.templates.types_markup') !!}</p>
                            <p>{!! __('help.templates.button_attributes') !!}</p>
                            <h4 id="downloads">{{ __('help.templates.how_downloads_work_heading') }}</h4>
                            <p>{!! __('help.templates.how_downloads_work') !!}</p>
                            <p>{!! __('help.templates.ticket_expiration') !!}</p>
                            <h4 id="javascript-api">{{ __('help.templates.javascript_api_heading') }}</h4>
                            <p>{!! __('help.templates.javascript_api', [ 'url' => route('downloads.download', ['uuid'=>'{Download-ID}', '&#123;Download-ID&#125;'])]) !!}</p>
                            <h4 id="variables">{{ __('help.templates.variables_heading') }}</h4>
                            <p>{!! __('help.templates.variables', ['never'=>__('downloads.expires_never')]) !!}</p>
                        </section>
                        <section class="help-section" v-pre>
                            <header class="help-section-header">
                                <h3 id="user-management">{{ __('help.user_management.heading') }}</h3>
                            </header>
                            <p>{!! __('help.user_management.intro') !!}</p>
                            <p>{!! __('help.user_management.permissions') !!}</p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
