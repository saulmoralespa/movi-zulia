<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManagerSettingsController extends Controller
{
    public function profile()
    {
        return view('manager.profile');
    }

    public function setProfile(Request $request)
    {
        $user = Auth::user();
        if ($request->file('avatar_user'))
        {
            Storage::delete($user->avatar);
            $user->avatar = $request->file('avatar_user')->store('public/img/profile');
        }

        $user->name = $request->get('name_user');
        $user->email = $request->get('email_user');

        if($request->input('name_cooperative') &&
            $request->input('description_cooperative')){
            $user->name_cooperative = $request->get('name_cooperative');
            $user->description_cooperative = $request->get('description_cooperative');
        }

        $user->save();

        return back();
    }

    public function password()
    {
        return view('manager.password');
    }

    public function changePassword(Request $request)
    {
        if ((Hash::check($request->get('new_password'), Auth::user()->password))) {
            return redirect()->back()->with("error","La nueva contraseña no puede ser igual a su contraseña actual. Por favor elija una contraseña diferente.");
        }

        if(strcmp($request->get('new_password'), $request->get('new_password_confirm')) != 0)
        {
            return redirect()->back()->with("error","La confirmación de la contraseña no coincide.");
        }

        $user = Auth::user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        Auth::logout();
        return redirect()->route('login');
    }
}
