<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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

    public function handleProviderCallback(Request $request, $provider)
    {
        if (! config('services.' . $provider)) {
            return redirect($this->redirectPath())
                ->withErrors(['Provider "' . $provider . '" does not exists.']);
        }

        if (Auth::attempt($request->only('email', 'provider_id', 'provider'), true)) {
            $request->session()->regenerate();

            return redirect($this->redirectPath());
        }

        $user = Socialite::driver($provider)->user();

        if (User::where('email', $user->email)->exists()) {
            return redirect($this->redirectPath())
                ->withErrors(['This email already registered. Recover your password if you forgot it.']);
        }

        $authUser = User::firstOrCreate(
            [
                'email'    => $user->email,
                'provider' => $provider,
                'provider_id' => $user->id
            ],
            [
                'email_verified_at' => date('Y-m-d H:i:s'),
                'name' => $user->name
            ]
        );

        Auth::login($authUser, true);

        return redirect($this->redirectPath());
    }

    public function redirectToProvider($provider)
    {
        if (config('services.' . $provider)) {
            return Socialite::driver($provider)->redirect();
        }

        return redirect($this->redirectPath())
            ->withErrors(['Provider "' . $provider . '" does not exists.']);
    }
}
