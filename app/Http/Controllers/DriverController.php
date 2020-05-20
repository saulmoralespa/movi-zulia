<?php

namespace App\Http\Controllers;

use App\Driver;
use http\Env\Response;
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

    public function delete($id)
    {
        Driver::find($id)->delete();
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|file|mimes:jpg,jpeg,png',
            'filename.*' => 'required|mimes:jpg,jpeg,png',
            'phone' => 'required|numeric|min:10|unique:drivers',
            'plate_number' => 'required|min:6|unique:drivers'
        ]);

        if ($validator->fails())
            return response()->json(['errors'=>$validator->errors()->all()]);

        $avatar = $request->file('avatar');
        $nameAvatar = $avatar->hashName();
        $request->file('avatar')->storeAs(self::PATH_IMAGES_PROFILE, $nameAvatar);

        $images = [];

        foreach($request->file('filename') as $file)
        {
            $name = $avatar->hashName();
            $file->storeAs(self::PATH_IMAGES_CARS, $name);
            $images[] = $name;
        }

        Driver::create([
            'name' => $request->name,
            'avatar' => $name,
            'plate_number' => $request->plate_number,
            'phone' => $request->phone,
            'filename' => json_encode($images),
            'user_id' => $request->id
        ]);

        return response()->json(['avatar' => $nameAvatar]);

    }

    public function update(Request $request)
    {
        $driver = Driver::find($request->id);

        $validator = Validator::make($request->all(), [
            'phone' => $driver->phone === $request->phone ? 'required|numeric|min:10' : 'required|numeric|min:10|unique:drivers',
            'plate_number' => $driver->plate_number === $request->plate_number ? 'required|min:6' : 'required|unique:drivers|min:6'
        ]);

        if ($validator->fails())
            return response()->json(['errors'=>$validator->errors()->all()]);

        if ($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $name = $avatar->hashName();
            $pathOldImage = self::PATH_IMAGES_PROFILE . $driver->avatar;
            Storage::delete($pathOldImage);
            $request->file('avatar')->storeAs(self::PATH_IMAGES_PROFILE, $name);
            $driver->avatar = $name;
        }

        $images = [];

        if ($request->hasFile('filename'))
        {
            $oldImages = json_encode($driver->filename, true);

            /*foreach ($oldImages as $image){
                $pathOldImage = self::PATH_IMAGES_CARS . $image;
                Storage::delete($pathOldImage);
            }*/

            foreach($request->file('filename') as $file)
            {
                $name = $avatar->hashName();
                $file->storeAs(self::PATH_IMAGES_CARS, $name);
                $images[] = $name;
            }

            $driver->filename = json_encode($images);
        }

        $driver->name = $request->name;
        $driver->phone = $request->phone;
        $driver->plate_number = $request->plate_number;
        $driver->save();

        return response()->json(['avatar' => $driver->avatar]);
    }
}
