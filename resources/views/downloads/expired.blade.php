<!doctype html>
<html class="no-js" lang="{{ $template->language }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="manifest" href="site.webmanifest">
        <link rel="apple-touch-icon" href="icon.png">
        {!! $head !!}
        @if ($preview)
            <style>
                .__sharebox-preview-banner-container {
                    position:       fixed;
                    top:            -2.5rem;
                    right:          -7rem;
                    pointer-events: none;
                    user-select:    none;
                    z-index:        99;
                    opacity:        0.75;
                }

                .__sharebox-preview-banner {
                    display:          block;
                    padding:          1rem 5rem;
                    transform:        rotate(45deg);
                    transform-origin: 0 0;
                    font-size:        1rem;
                    line-height:      1;
                    font-family:      sans-serif;
                    text-align:       center;
                    background-color: #005cbf;
                    color:            #fff;
                    box-shadow:       0 0 10px rgba(0, 0, 0, 0.5);
                }
            </style>
        @endif
    </head>

    <body>
        {!! $body !!}
        {!! $footer !!}
        @if ($preview)
            <div class="__sharebox-preview-banner-container">
                <span class="__sharebox-preview-banner">{{ __('templates.preview') }}</span>
            </div>
        @endif
    </body>
</html>
