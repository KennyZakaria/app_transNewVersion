<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
class PasswordResetController  extends BaseController
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Delay response for 1 second to prevent email enumeration
        sleep(1);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);

        DB::table('users')->updateOrInsert([
            'email' => $user->email,
        ], [
            'remember_token' => $otp
        ]);

        $mailData = [
            'otp' => $otp
        ];

        try {
            // Send the OTP email
            Mail::to($user->email)->send(new OtpMail($mailData));

            // Do not provide specific error messages, only indicate that the email was sent successfully
            return $this->sendResponse('', 'Email sent successfully. Please check your mailbox.', 200);
        } catch (\Exception $e) {
            // Do not provide specific error messages, only indicate that the email sending failed
            return $this->sendError('', 'The system is unable to send emails. Please try again later.', 400);
        }
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:8', // Enforce a minimum password length
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found'], 404);
        }

        $passwordReset = DB::table('users')
            ->where('email', $request->email)
            ->first();

            if (!$passwordReset) {
                return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);
            }else {
                if ($passwordReset->remember_token != $request->otp) {
                    return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);

                }else{
                    $user->update([
                        'password' => bcrypt($request->password),
                    ]);
                    return $this->sendResponse('', 'Your password has been successfully modified.', 200);
                }
            }
    }
    public function checkOtp(Request $request)
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
                                'email' => $request->email
                            ], [
                                'email_verified_at' =>now(),
                                'verifiedEmail' =>true,
                            ]);
         return $this->sendResponse('', ' the email has been successfully checked ', 200);


    }
}




