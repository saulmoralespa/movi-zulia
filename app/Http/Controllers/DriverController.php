<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class DriverController extends Controller
{
    const PATH_IMAGES_PROFILE = 'public/img/profile/';
    const PATH_IMAGES_CARS = 'public/img/cars/';

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
            'filename' => 'required',
            'filename.*' => 'required|mimes:jpg,jpeg,png|max:1000',
            'phone' => 'required|numeric|min:10|unique:drivers',
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

        $images = [];

        foreach($request->file('filename') as $file)
        {
            $name = $file->hashName();

            $savedImage = Storage::cloud()->put($name, $file->get());

            if ($savedImage){
                $dir = '/';
                $recursive = false; // Get subdirectories also?
                $contents = collect(Storage::cloud()->listContents($dir, $recursive));

                $file = $contents
                    ->where('type', '=', 'file')
                    ->where('filename', '=', pathinfo($name, PATHINFO_FILENAME))
                    ->where('extension', '=', pathinfo($name, PATHINFO_EXTENSION))
                    ->first(); // there can be duplicate file names!

                $images[] = $file['path'];
            }
        }

        $driver = Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $nameAvatar,
            'plate_number' => $request->plate_number,
            'phone' => $request->phone,
            'filename' => json_encode($images),
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
            'phone' => $driver->phone === $request->phone ? 'required|numeric|min:10' : 'required|numeric|min:10|unique:drivers',
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

        $images = [];

        if ($request->hasFile('filename'))
        {
            foreach($request->file('filename') as $file)
            {
                $name = $file->hashName();
                $savedImage = Storage::cloud()->put($name, $file->get());

                if ($savedImage){
                    $dir = '/';
                    $recursive = false; // Get subdirectories also?
                    $contents = collect(Storage::cloud()->listContents($dir, $recursive));

                    $file = $contents
                        ->where('type', '=', 'file')
                        ->where('filename', '=', pathinfo($name, PATHINFO_FILENAME))
                        ->where('extension', '=', pathinfo($name, PATHINFO_EXTENSION))
                        ->first(); // there can be duplicate file names!
                    $images[] = $file['path'];
                }
            }

            $driver->filename = json_encode($images);
        }

        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->phone = $request->phone;
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
        $drivers = Driver::all();
        return response()->json($drivers, 200);
    }
}
