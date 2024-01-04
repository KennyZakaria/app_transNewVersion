<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends BaseController
{
    public function notification(Request $request)
    {
        $user_id = auth()->id();
        $perPage = $request->input('per_page', 10);
        $notifications = Notification::where('user_id', $user_id)->paginate($perPage);
        return response()->json($notifications, 200);
    }

}
