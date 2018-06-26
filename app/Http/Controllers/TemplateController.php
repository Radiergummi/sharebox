<?php

namespace App\Http\Controllers;

use App\Template;
use Illuminate\Http\Request;
use RuntimeException;
use function redirect;
use function response;
use function trans;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('templates.index', [
            'templates' => Template::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'        => 'nullable|string|min:3|unique:templates',
            'language'    => 'nullable|string|size:2',
            'description' => 'nullable|string',
            'head'        => 'nullable|string',
            'body'        => 'required|string',
            'footer'      => 'nullable|string'
        ]);

        $template = new Template();

        $template->name = $request->get('name');

        if ($request->has('language')) {
            $template->language = $request->get('language');
        } else {
            $template->language = 'de';
        }

        if ($request->has('description')) {
            $template->description = trim($request->get('description'));
        }

        if ($request->has('head')) {
            $template->head = trim($request->get('head'));
        }

        $template->body = $request->get('body');

        if ($request->has('footer')) {
            $template->footer = trim($request->get('footer'));
        }

        $template->saveOrFail();

        $request->session()->flash(trans('the template has been updated'));

        return redirect()->route('templates.show', [$template->id], 303);
    }

    /**
     * Display the specified resource.
     *
     * @param  Template $template
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        return view('templates.show', [
            'template' => $template
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Template $template
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        return view('templates.edit', [
            'template'  => $template,
            'languages' => [
                'de',
                'en',
                'fr',
                'it',
                'es',
                'tr',
                'ja',
                'cn',
                'cs',
                'ar',
                'ru',
                'pl'
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\Template             $template
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function update(Request $request, Template $template)
    {
        $this->validate($request, [
            'name'        => 'nullable|string|min:3|unique:templates',
            'language'    => 'nullable|string|size:2',
            'description' => 'nullable|string',
            'head'        => 'nullable|string',
            'body'        => 'required|string',
            'footer'      => 'nullable|string'
        ]);

        if ( ! $template->locked) {
            if ( ! $request->has('name')) {
                throw new RuntimeException('No template name provided');
            }

            $template->name = $request->get('name');
        }

        if ($request->has('language')) {
            $template->language = $request->get('language');
        } else {
            $template->language = 'de';
        }

        if ($request->has('description')) {
            $template->description = trim($request->get('description'));
        }

        if ($request->has('head')) {
            $template->head = trim($request->get('head'));
        }

        $template->body = $request->get('body');

        if ($request->has('footer')) {
            $template->footer = trim($request->get('footer'));
        }

        $template->saveOrFail();

        $request->session()->flash(trans('the template has been updated'));

        return redirect(route('templates.show', [$template->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Template $template
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Template $template)
    {
        if ($template->locked) {
            return response('Cannot delete a locked template!', 400);
        }

        $template->delete();

        return response('', 204);
    }
}
