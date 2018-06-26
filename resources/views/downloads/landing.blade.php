<!doctype html>
<html class="no-js" lang="{{ $template->language }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $download->filename }}</title>

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
        <script>
            @if (session('error'))
            console.error( '{{ session('error') }}' );
            @endif

            document.addEventListener( 'DOMContentLoaded', function () {
              var downloadButton = document.querySelector( '[data-start-download]' );

              if ( downloadButton ) {
                downloadButton.addEventListener( 'click', handleDownload );
                downloadButton.addEventListener( 'keyup', handleDownload );
              }
            } );

            function handleDownload ( event ) {
              event.preventDefault();

                {{-- Only handle keyboard events if [enter] or [space] have been pressed --}}
                if ( event.keyCode && ( event.keyCode !== 13 || event.keyCode !== 32 ) ) {
                  return;
                }

                {{-- Disable the input --}}
                event.target.setAttribute( 'disabled', 'true' );

              if ( event.target.dataset.loadingText ) {
                event.target.innerText = event.target.dataset.loadingText;
              }

                        {{--
                          -- The following creates a link element, hides it visually, sets it to download
                          -- mode and appends the ticket token to the download URL. This ensures a link
                          -- can only be downloaded by opening the page.
                          --}}
              var downloadLink              = document.createElement( 'a' );
              downloadLink.id               = 'download-link';
              downloadLink.style.opacity    = '0';
              downloadLink.style.width      = '1px';
              downloadLink.style.height     = '1px';
              downloadLink.style.visibility = 'hidden';

              downloadLink.href = window.location.href + '/save?ticket={{ $ticket->token }}';
              downloadLink.setAttribute( 'download', '{{ $download->name }}' );

              document.body.appendChild( downloadLink );

              downloadLink.click();
            }
        </script>
    </body>
</html>
