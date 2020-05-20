<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Str;
use Socialite;
use File;

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
     * @param $network
     * @return mixed
     */
    public function redirectToProvider($network)
    {
        return Socialite::driver($network)->redirect();
    }

    public function handleProviderCallback($network)
    {
        $user = Socialite::driver($network)->user();

        $existing = User::where('email', $user->email)->first();

        if (!$existing) {

            $fileContents = file_get_contents($user->avatar_original);
            $avatarImg = $user->getId() . ".jpg";
            File::put(storage_path('/app/public/img/profile/')  . $avatarImg , $fileContents);

            $register = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $avatarImg,
                'password' => bcrypt(Str::random()),
            ]);
            auth()->login($register);
        }else{
            auth()->login($existing);
        }

        return redirect('/');

    }
}
