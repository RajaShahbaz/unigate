<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
     * University admission app students (Student role, no school) use the dedicated web portal.
     */
    protected function authenticated(Request $request, $user)
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('Student') && empty($user->school_id)) {
            return redirect()->intended(route('user.portal.dashboard'));
        }

        return redirect()->intended($this->redirectPath());
    }
}
