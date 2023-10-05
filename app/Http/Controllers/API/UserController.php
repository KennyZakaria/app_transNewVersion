<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\DB;
use Validator;
class UserController  extends BaseController
{
   /* public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ROLE_ADMIN');
    }*/
    public function me(Request $request): JsonResponse
    {
       // Retrieve the authenticated user with their roles
        $user = User::with('roles')->find($request->user()->id);
       // $client = Client::find($request->user()->id);
       // $user['tel'] = $client['tel'];
        // Check if the user exists
        if (!$user) {
            return $this->sendError('User not found.');
        }
        return $this->sendResponse(['user' => $user], 'User and roles found.');
    }
    public function sendOtpCheckMail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('not found.', ['error' => 'User not found']);
        }
        $Otp = rand(100000, 999999);
        $mailData = ['otp' => $Otp];
        Mail::to($request->email)->send(new OtpMail($mailData));
        DB::table('users')->updateOrInsert([
                                'email' => $request->email,
                            ], [
                                'remember_token' => $Otp,
                            ]);
         // Do not provide specific error messages, only indicate that the email was sent successfully
         return $this->sendResponse('', 'Email sent successfully. Please check your mailbox.', 200);

    }
    public function  CheckMail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|min:6',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('not found.', ['error' => 'User not found']);
        }
        $user = User::where('remember_token', $request->otp)->first();
        if (!$user) {
            return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);
        }
        DB::table('users')->updateOrInsert([
                                'email' => $request->email,
                                'email' => $request->email,
                            ], [
                                'remember_token' =>'',
                                'email_verified_at' =>now(),
                                'verifiedEmail' =>true,
                            ]);
         return $this->sendResponse('', ' the email has been successfully checked ', 200);
    }
}
