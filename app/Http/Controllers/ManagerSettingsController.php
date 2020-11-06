<?php

namespace App\Http\Controllers;

use File;
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
            $dir = '/';
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));

            $file = $contents
                ->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($user->avatar, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($user->avatar, PATHINFO_EXTENSION))
                ->first(); // there can be duplicate file names!

            Storage::cloud()->delete($file['path']);

            $nameAvatar = $request->file('avatar_user')->hashName();

            $savedImage = Storage::cloud()->put($nameAvatar, $request->file('avatar_user')->get());

            $user->avatar = '';

            if ($savedImage){
                $dir = '/';
                $recursive = false; // Get subdirectories also?
                $contents = collect(Storage::cloud()->listContents($dir, $recursive));

                $file = $contents
                    ->where('type', '=', 'file')
                    ->where('filename', '=', pathinfo($nameAvatar, PATHINFO_FILENAME))
                    ->where('extension', '=', pathinfo($nameAvatar, PATHINFO_EXTENSION))
                    ->first(); // there can be duplicate file names!

                $user->avatar = $file['path'];
            }
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
