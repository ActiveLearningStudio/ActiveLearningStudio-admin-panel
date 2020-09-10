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
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
        $this->response = Http::withHeaders(['Accept' => 'application/json',])->post(api_url().'/login', $request->only('email', 'password'));
        // if validation failse laravel return 422 code
        if ($this->response->status() === 422) {
            return redirect()->back()->withErrors($this->response->json()['errors'])->withInput();
        }
        throw_if($this->response->failed() || $this->response->serverError(), new GeneralException($this->getError()));
        session(['access_token' => $this->response['access_token']]);
        // also login the user locally
        Auth::loginUsingId($this->response['user']['id'], true);
        return redirect(route('admin.dashboard'));
    }
}
