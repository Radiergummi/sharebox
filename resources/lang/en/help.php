<?php

return [
    'help'            => 'Help',
    'intro'           => 'On these help pages you\'ll find general notes on using the app.',
    'general_heading' => 'General notes',
    'general'         => 'The <strong>Sharebox</strong> is an application to share files on an (S)FTP server securely on the internet. Therefore, it creates unique links that can be shared with selected recipients. Downloading files is only possible for those in possession of the link.<br>All links lead to a <strong>landing page</strong> that displays a download button. The landing page can be customized for each download or use a default template. That way it\'s possible to create targeted download pages without compromises.<br>Shared files can get very big, which is a problem usually: By default, PHP loads files into the memory completely before sending them to clients. Since most servers only have limited memory (especially on shared hosters), the Sharebox just <em>streams</em> files from the source server to the client. That way, download size is practically unlimited.',
    'templates'       => [
        'heading'                    => 'Templates',
        'intro'                      => '<em>Templates</em> are used to render public pages. Following this approach, you can create landing pages for specific clients or occasions.<br>We distinguish between so-called <strong>Default templates</strong> and normal templates. Currently, there are three default templates: One for the default landing page, one for unavailable (deleted) shares and one for expired shares.',
        'types_markup'               => 'The content of a template can be affected using three fields:
                                <strong>head</strong>, <strong>body</strong> and <strong>footer</strong>. The content of these fields will make up most of the landing page HTML code. That way, the UX and design of the template can be customized without compromising the download ability: Every template provides the basic HTML layout as well as a JavsScript-API to initialize the download process.<br>
                                The minimum configuration for a landing page template will always be a download button as follows:
                            <pre><code class="html">&lt;button data-start-download&gt;Button label&lt;/button&gt;</code></pre>
                            This button <strong>must</strong> have set the data attribute <code>data-start-download</code>. This is the selector used by the API to identify the download button.',
        'button_attributes'          => 'The download button can have a range of additional attributes to customize the button behaviour:
                            <ul><li>
                                    <strong><code>data-loading-text</code></strong><br>
                                    <span>Set this attribute to define an alternative button label that is being set as soon as the button is clicked. Sometimes it can take a while to initialize the download stream, especially for large files. So using this, you can provide a little visual feedback.</span>
                                </li></ul>',
        'how_downloads_work_heading' => 'How downloads work',
        'how_downloads_work'         => 'Basically, all shared files are available via their individual download links. To avoid direct links or any search engine indexing, <em>tickets</em> have been implemented.<br>They work like so: On requesting a download landing page, a random ticket will be generated that entitles for actually downloading the file, a random password more or less. This ticket passwort is embedded on the download page. Upon clicking the download button, a special URL is called that contains both download and ticket ID.<br>This ensures the file can only be downloaded if the landing page has been opened previously.',
        'ticket_expiration'          => 'All download tickets are only valid for a certain amount of time (You can configure this value in the <code>.env</code> file), the default is 30 minutes. This means that users can idle for 30 minutes between opening the landing page and clicking on <em>Download</em>; If that time is expired, the page will be reloaded to generate a new ticket.',
        'javascript_api_heading'     => 'JavaScript API',
        'javascript_api'             => 'The default JS API will be loaded on every landing page. <strong>If</strong> there is a button on the page with the <code>data-start-download</code> attribute set, a <code>click</code> event listener is attached to the button to automatically start the download.<br>This API doesn\'t have to be used, though: If you use other markup, the JS simply won\'t do anything. You will need to call the following URL to kickoff the download in that case:
         <pre><code>:url?ticket={Ticket-ID}</code></pre>That will start the download, invalidate the ticket and increment the download counter.<br>How the URL is called doesn\'t really matter; The default implementation uses an anchor element with the download attribute (no new windows, just a file download).',
        'variables_heading'          => 'Template variables',
        'variables'                  => 'In every template you\'ve got access to a range of variables. Additionally, all templates will be parsed by the <a href="https://laravel.com/docs/5.6/blade" target="_blank">Blade template engine</a>, so you\'ll always have the full power of PHP7+ at your hands.<br>The following variables are always available:
           <ul><li>
           <strong><code>id</code></strong>
           <p>The unique download ID (UUIDv4) which is part of the download link.</p>
           </li>
           <li>
           <strong><code>filename</code></strong>
           <p>The name of the file to download. If a display name has been set, it will be used, the real filename otherwise.</p>
           </li>
           <li>
           <strong><code>filesize</code></strong>
           <p>The size of the file, converted to human readable units (2KB, 510MB, 2GB, etc.)</p>
           </li>
           <li>
           <strong><code>expires</code></strong>
           <p>The expiration date of the file, as human readable time diff. Could be <em>in 10 hours</em> or <em>in four days</em>, for example. If the share has no expiration set, the value will be <em>:never</em>.</p>
           </li>
           <li>
           <strong><code>ticket</code></strong>
           <p>The download ticket for this visitor (UUIDv4), that must be passed in the actual download initialization URL.</p>
           </li>
           <li>
           <strong><code>hasPassword</code></strong>
           <p>Whether a password has been set for this share. If it is, the password must be passed in the <code>Authorization</code> header of the download request, so the download page will need to provide a password field. Using the header transfer should be no problem on HTTPS connections.</p>
           </li>
           </ul>'
    ],

    'user_management' => [
        'heading'     => 'User management',
        'intro'       => 'The Sharebox provides a complete user management to create administrative accounts. You do <strong>not</strong> need to have an account to download a file; Accounts are solely required to manage shares, templates and users.',
        'permissions' => 'All registered users have the possibility to manage all data. Fine-grained permissions are currently not planned.'
    ]
];
