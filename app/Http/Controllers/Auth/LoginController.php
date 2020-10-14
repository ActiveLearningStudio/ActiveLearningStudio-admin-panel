<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use RequestTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Authenticate user via API and set the bearer token in session
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws \Throwable
     */
    public function customLogin(Request $request){
        $this->response = Http::withHeaders(['Accept' => 'application/json',])->post(api_url().'/admin/login', $request->only('email', 'password'));
        // if validation fails laravel return 422 code
        if ($this->response->status() === 422) {
            return redirect()->back()->withErrors($this->response->json()['errors'])->withInput();
        }
        throw_if($this->response->failed() || $this->response->serverError(), new GeneralException($this->getError()));
        $user = $this->response['user'];
        $user['access_token'] = $this->response['access_token'];
        // set the login session of the user
        session(['auth_user' => $user]);
        return redirect(route('admin.dashboard'));
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($response = $this->loggedOut($request)) {
            return $response;
        }
        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
    }
}
