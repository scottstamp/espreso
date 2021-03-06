<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogsController as Logs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $log;

    public function __construct(Logs $logManager)
    {
       $this->log = $logManager;
    }

    /**
     * Display login view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate a user object
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {

        if(Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')]))
        {
            $this->log->create($request, "Successfully logged in to the Housekeeping");
            return redirect()->intended('dashboard');
        }

        else
        {
            $this->log->create($request, "Failed login attempt");
            $request->session()->flash('error', 'Wrong username/password combination');
            return redirect()->back();
        }
    }

    /**
     * Destroy user's current session
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $this->log->create($request, "Signed out of the Houskeeping");
        Auth::logout();
        return redirect()->intended('/');
    }
}
