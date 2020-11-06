<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class DriverController extends Controller
{
    public function fetch($user_id)
    {
        $drivers = Driver::where('user_id', $user_id)->orderBy('name', 'asc')->get();
        return response()->json($drivers);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|file|mimes:jpg,jpeg,png',
            'email' => 'required|email|unique:drivers',
            'plate_number' => 'required|min:6|unique:drivers'
        ]);

        if ($validator->fails())
            return response()->json(['errors'=>$validator->errors()->all()]);

        $avatar = $request->file('avatar');
        $nameAvatar = $avatar->hashName();
        $savedImage = Storage::cloud()->put($nameAvatar, $request->file('avatar')->get());

        if ($savedImage){
            $dir = '/';
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));

            $file = $contents
                ->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($nameAvatar, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($nameAvatar, PATHINFO_EXTENSION))
                ->first(); // there can be duplicate file names!

            $nameAvatar = $file['path'];
        }

        $driver = Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $nameAvatar,
            'plate_number' => $request->plate_number,
            'user_id' => $request->id
        ]);

        return response()->json(
            [
            'avatar' => $nameAvatar,
            'id'    => $driver->id
            ]
        );

    }

    public function update(Request $request)
    {
        $driver = Driver::find($request->id);

        $validator = Validator::make($request->all(), [
            'email' => $driver->email === $request->email ? 'required|email' : 'required|email|unique:drivers',
            'plate_number' => $driver->plate_number === $request->plate_number ? 'required|min:6' : 'required|unique:drivers|min:6'
        ]);

        if ($validator->fails())
            return response()->json(['errors'=>$validator->errors()->all()]);

        if ($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $name = $avatar->hashName();

            $dir = '/';
            $recursive = false; // Get subdirectories also?
            $contents = collect(Storage::cloud()->listContents($dir, $recursive));

            $file = $contents
                ->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($driver->avatar, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($driver->avatar, PATHINFO_EXTENSION))
                ->first(); // there can be duplicate file names!

            Storage::cloud()->delete($file['path']);

            $savedImage = Storage::cloud()->put($name, $request->file('avatar')->get());

            $driver->avatar = '';

            if ($savedImage){
                $dir = '/';
                $recursive = false; // Get subdirectories also?
                $contents = collect(Storage::cloud()->listContents($dir, $recursive));

                $file = $contents
                    ->where('type', '=', 'file')
                    ->where('filename', '=', pathinfo($name, PATHINFO_FILENAME))
                    ->where('extension', '=', pathinfo($name, PATHINFO_EXTENSION))
                    ->first(); // there can be duplicate file names!

                $driver->avatar = $file['path'];
            }
        }

        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->plate_number = $request->plate_number;
        $driver->save();

        return response()->json(['avatar' => $driver->avatar]);
    }

    public function delete($id)
    {
        $driver = Driver::find($id);

        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($driver->avatar, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($driver->avatar, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        Storage::cloud()->delete($file['path']);

        $driver->delete();
    }

    public function show()
    {
        $drivers = Driver::paginate(3);
        return response()->json($drivers, 200);
    }

    public function getDriverByEmail($email)
    {
        $driver = Driver::where('email', '=', $email)->get();
        return response()->json($driver, 200);
    }
}
