<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotificationByDeviceId($device_id)
    {
        $notification = Notification::where('device_id', '=', $device_id)->get();
        return response()->json($notification, 200);
    }

    public function add(Request $request)
    {
        $data = $request->all();

        $statusCode = 200;

        try{
            Notification::create($data);
            $message = "Success saved token";
        }catch (\Exception $exception){
            $message = $exception->getMessage();
            $statusCode = 500;
        }

        return response()->json($message, $statusCode);
    }
}
