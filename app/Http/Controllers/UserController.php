<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'nullable|string|min:3',
            'username' => 'required|string|min:3|unique:users',
            'password' => 'required|string|min:8',
            'email'    => 'required|email'
        ]);

        $user = new User();

        $user->full_name = $request->get('name');
        $user->name      = $request->get('username');
        $user->email     = $request->get('email');
        $user->password  = Hash::make($request->get('password'));

        $user->saveOrFail();

        return redirect()->route('users.show', [$user->id], 303);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\User                 $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name'     => 'nullable|string|min:3',
            'username' => 'nullable|string|min:3|unique:users',
            'password' => 'nullable|string|min:8',
            'email'    => 'nullable|email'
        ]);

        if ($request->has('name')) {
            $user->full_name = $request->get('name');
        }

        if ($request->has('username')) {
            $user->name = $request->get('username');
        }

        if ($request->has('email')) {
            $user->email = $request->get('email');
        }

        if ($request->has('password') && strlen($request->get('password')) >= 8) {
            $user->password = Hash::make($request->get('password'));
        }

        $user->saveOrFail();

        return redirect()->route('users.show', [$user->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if (Auth::user()->id === $user->id) {
            return response(trans('Cannot delete own account'), 400);
        }

        $user->delete();

        return response('', 204);
    }
}
