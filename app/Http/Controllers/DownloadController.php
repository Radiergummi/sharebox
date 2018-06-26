<?php

namespace App\Http\Controllers;

use App\Download;
use App\Template;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Webpatser\Uuid\Uuid;
use function basename;
use function file_exists;
use function is_null;
use function public_path;
use function redirect;
use function request;
use function response;
use function strtotime;
use const PATHINFO_FILENAME;

class DownloadController extends Controller
{
    /**
     * @param \App\Template|null $template
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Throwable
     */
    public function preview(Template $template = null)
    {
        switch ($template->slot) {

            case 'expired':
                return $this->expiredPreview();

            case 'missing':
                return $this->missingPreview();

            default:
                return $this->landingPreview($template);
        }
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    protected function expiredPreview()
    {
        // fetch the download-expired-template
        $template = Template::downloadExpiredTemplate();

        // create a new download that expired yesterday, without actually saving it
        $download = new Download([
            'path'    => 'i/do/not/exist/preview_file.zip',
            'size'    => 45100000000,
            'name'    => 'preview_file.zip',
            'expires' => date('Y-m-d', strtotime('yesterday'))
        ]);

        // render all template parts with several base variables
        $templateData = [
            'download'      => $download,
            'expired_since' => ! (is_null($download->expires))
                ? (new Carbon($download->expires))->diffForHumans()
                : __('downloads.expires_never'),
            'logoUrl'       => file_exists(public_path('storage/logo.svg'))
                ? asset('storage/logo.svg')
                : file_exists(public_path('storage/logo.png'))
                    ? asset('storage/logo.png')
                    : file_exists(public_path('storage/logo.jpg'))
                        ? asset('storage/logo.jpg')
                        : ''
        ];
        $body         = $template->renderBody($templateData);
        $head         = $template->renderHead($templateData);
        $footer       = $template->renderFooter($templateData);

        return response()->view('downloads.expired', [
            'preview'  => true,
            'template' => $template,
            'download' => $download,
            'head'     => $head,
            'body'     => $body,
            'footer'   => $footer
        ], 410);
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    protected function missingPreview()
    {
        // fetch the download-missing-template
        $template = Template::downloadMissingTemplate();

        // render all template parts with several base variables
        $templateData = [

            // valid but unused UUID
            'id'      => 'deadc0de-4f25-4c46-99df-1c04c0de8ca4',
            'url'     => request()->fullUrl(),
            'logoUrl' => file_exists(public_path('storage/logo.svg'))
                ? asset('storage/logo.svg')
                : file_exists(public_path('storage/logo.png'))
                    ? asset('storage/logo.png')
                    : file_exists(public_path('storage/logo.jpg'))
                        ? asset('storage/logo.jpg')
                        : ''
        ];
        $body         = $template->renderBody($templateData);
        $head         = $template->renderHead($templateData);
        $footer       = $template->renderFooter($templateData);

        return response()->view('downloads.missing', [
            'preview'  => true,
            'template' => $template,
            'head'     => $head,
            'body'     => $body,
            'footer'   => $footer
        ], 404);
    }

    /**
     * @param \App\Template $template
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    protected function landingPreview(Template $template)
    {
        // create a new download that expires tomorrow, without actually saving it
        $download = new Download([
            'id'      => 42,
            'path'    => 'i/do/not/exist/preview_file.zip',
            'name'    => 'preview_file.zip',
            'expires' => date('Y-m-d', strtotime('tomorrow'))
        ]);

        // size is not mass assignable, therefore we'll need to populate it manually
        $download->size = 45100000000;

        $ticket = new Ticket([
            'download_id' => 42
        ]);

        // render all template parts with a chunk of template variables
        $templateData = [
            'id'          => $download->uuid,
            'filename'    => $download->name,
            'filesize'    => $download->readableSize(),
            'expires'     => ! (is_null($download->expires))
                ? (new Carbon($download->expires))->diffForHumans()
                : __('downloads.expires_never'),
            'ticket'      => $ticket->token,
            'hasPassword' => ! ! $download->password,
            'logoUrl'     => file_exists(public_path('storage/logo.svg'))
                ? asset('storage/logo.svg')
                : file_exists(public_path('storage/logo.png'))
                    ? asset('storage/logo.png')
                    : file_exists(public_path('storage/logo.jpg'))
                        ? asset('storage/logo.jpg')
                        : ''
        ];

        $body   = $template->renderBody($templateData);
        $head   = $template->renderHead($templateData);
        $footer = $template->renderFooter($templateData);

        return view('downloads.landing', [
            'preview'  => true,
            'download' => $download,
            'template' => $template,
            'ticket'   => $ticket,
            'head'     => $head,
            'body'     => $body,
            'footer'   => $footer
        ]);
    }

    /**
     * @param string $uuid
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function landing(string $uuid)
    {
        /** @var Download $download */
        $download = Download::where('uuid', $uuid)->first();

        // check whether the download exists
        if ( ! $download) {

            // fetch the download-missing-template
            $template = Template::downloadMissingTemplate();

            // render all template parts with several base variables
            $templateData = [
                'id'      => $uuid,
                'url'     => request()->fullUrl(),
                'logoUrl' => file_exists(public_path('storage/logo.svg'))
                    ? asset('storage/logo.svg')
                    : file_exists(public_path('storage/logo.png'))
                        ? asset('storage/logo.png')
                        : file_exists(public_path('storage/logo.jpg'))
                            ? asset('storage/logo.jpg')
                            : ''
            ];
            $body         = $template->renderBody($templateData);
            $head         = $template->renderHead($templateData);
            $footer       = $template->renderFooter($templateData);

            return response()->view('downloads.missing', [
                'preview'  => false,
                'template' => $template,
                'head'     => $head,
                'body'     => $body,
                'footer'   => $footer
            ], 404);
        }

        // check if the download has an expiration date and if so, whether it is expired
        // by comparing the expiration timestamp to the current timestamp
        if ( ! is_null($download->expires) && strtotime($download->expires) < time()) {

            // fetch the download-expired-template
            $template = Template::downloadExpiredTemplate();

            // render all template parts with several base variables
            $templateData = [
                'download'      => $download,
                'expired_since' => ! (is_null($download->expires))
                    ? (new Carbon($download->expires))->diffForHumans()
                    : __('downloads.expires_never'),
                'logoUrl'       => file_exists(public_path('storage/logo.svg'))
                    ? asset('storage/logo.svg')
                    : file_exists(public_path('storage/logo.png'))
                        ? asset('storage/logo.png')
                        : file_exists(public_path('storage/logo.jpg'))
                            ? asset('storage/logo.jpg')
                            : ''
            ];
            $body         = $template->renderBody($templateData);
            $head         = $template->renderHead($templateData);
            $footer       = $template->renderFooter($templateData);

            return response()->view('downloads.expired', [
                'preview'  => false,
                'template' => $template,
                'download' => $download,
                'head'     => $head,
                'body'     => $body,
                'footer'   => $footer
            ], 410);
        }

        // create a ticket for the download
        $ticket = Ticket::for($download);
        $ticket->save();

        // render all template parts with a chunk of template variables
        $templateData = [
            'id'          => $download->uuid,
            'filename'    => $download->name,
            'filesize'    => $download->readableSize(),
            'expires'     => ! (is_null($download->expires))
                ? (new Carbon($download->expires))->diffForHumans()
                : __('downloads.expires_never'),
            'ticket'      => $ticket->token,
            'hasPassword' => ! ! $download->password,
            'logoUrl'     => file_exists(public_path('storage/logo.svg'))
                ? asset('storage/logo.svg')
                : file_exists(public_path('storage/logo.png'))
                    ? asset('storage/logo.png')
                    : file_exists(public_path('storage/logo.jpg'))
                        ? asset('storage/logo.jpg')
                        : ''
        ];

        $body   = $download->template->renderBody($templateData);
        $head   = $download->template->renderHead($templateData);
        $footer = $download->template->renderFooter($templateData);

        return view('downloads.landing', [
            'preview'  => false,
            'download' => $download,
            'template' => $download->template,
            'ticket'   => $ticket,
            'head'     => $head,
            'body'     => $body,
            'footer'   => $footer
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $uuid
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|string|\Symfony\Component\HttpFoundation\Response
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Exception
     */
    public function download(Request $request, string $uuid)
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::where('token', $request->query('ticket'))->first();

        /** @var Download $download */
        $download = Download::where('uuid', $uuid)->first();

        // direct downloads are forbidden
        if ( ! $ticket) {
            return redirect()
                ->route('downloads.landing', [$download->uuid])
                ->with('error', 'Directly downloading files is not allowed');
        }

        // check ticket expiration
        if (
            Carbon::parse($ticket->created_at)->addMinutes(env('DOWNLOAD_EXPIRATION', 30)) <
            Carbon::now()
        ) {
            return redirect()->route('downloads.landing', [$download->uuid])->with('error', 'Ticket expired');
        }

        if ($ticket->redeemed) {
            return redirect()->route('downloads.landing', [$download->uuid])->with('error', 'Ticket redeemed');
        }

        /** @var \League\Flysystem\Filesystem $fs */
        /** @noinspection PhpUndefinedMethodInspection */
        $fs = Storage::disk('remote')->getDriver();

        // invalidate the ticket
        $ticket->redeemed = true;

        // update the ticket
        $ticket->save();

        // increment the download counter
        $download->increaseCount();

        $stream = $fs->readStream($download->path);

        return response()->stream(
            function () use ($stream) {
                fpassthru($stream);
            },
            200,
            [
                'Content-Type'        => $fs->getMimetype($download->path),
                'Content-disposition' => 'attachment; filename="' . $download->name . '"',
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('downloads.index', [
            'downloads' => Download::all()
        ]);
    }

    public function show(Download $download)
    {
        return view('downloads.show', [
            'download' => $download
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('downloads.create', [
            'templates' => Template::allGroupedByLanguage()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'path'     => 'required|string',
            'name'     => 'nullable|string',
            'expires'  => 'nullable|date',
            'password' => 'nullable|string',
            'template' => 'nullable|integer|exists:templates,id'
        ]);

        $download = new Download();

        $download->path = $request->get('path');

        if ( ! Storage::disk('remote')->exists($download->path)) {
            throw new RuntimeException('Invalid file path');
        }

        if ($request->has('name') && strlen($request->get('name')) > 0) {
            $download->name = $request->get('name');
        } else {
            $download->name = basename($download->path, PATHINFO_FILENAME);
        }

        if ($request->has('expires')) {
            $download->expires = $request->get('expires');
        }

        if ($request->has('template')) {
            $download->template()->associate(Template::find((int)$request->get('template')));
        }

        if ($request->has('password') && strlen($request->get('password')) > 0) {
            $download->password = Hash::make($request->get('password'));
        }

        $download->uuid = Uuid::generate()->string;

        $download->size = Storage::disk('remote')->size($download->path);

        $download->saveOrFail();

        return redirect()->route('downloads.show', [$download->id], 303);
    }

    /**
     * @param \App\Download $download
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Download $download)
    {
        return view('downloads.edit', [
            'download'  => $download,
            'templates' => Template::allGroupedByLanguage()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Download            $download
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function update(Request $request, Download $download)
    {
        $this->validate($request, [
            'path'     => 'required|string',
            'name'     => 'nullable|string',
            'expires'  => 'nullable|date',
            'password' => 'nullable|string',
            'template' => 'nullable|integer|exists:templates,id'
        ]);

        $download->path = $request->get('path');

        if ( ! Storage::disk('remote')->exists($download->path)) {
            throw new RuntimeException('Invalid file path');
        }

        if ($request->has('name') && strlen($request->get('name')) > 0) {
            $download->name = $request->get('name');
        } else {
            $download->name = basename($download->path, PATHINFO_FILENAME);
        }

        if ($request->has('expires')) {
            $download->expires = $request->get('expires');
        }

        if ($request->has('template')) {
            $download->template()->associate(Template::find((int)$request->get('template')));
        }

        if ($request->has('password') && strlen($request->get('password')) > 0) {
            $download->password = Hash::make($request->get('password'));
        }

        $download->size = Storage::disk('remote')->size($download->path);

        $download->saveOrFail();

        return redirect()->route('downloads.show', [$download->id]);
    }

    /**
     * @param \App\Download $download
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Download $download)
    {
        $download->delete();

        return response('', 204);
    }
}
