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

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save the OTP and its expiration time (e.g., 5 minutes) in the database
        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $user->email,
        ], [
            'email' => $user->email,
            'token' => $otp,
            'created_at' => now(),
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

    /**public function resetPassword(Request $request)
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
    
        // Validate the OTP against the one stored in the database
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();
        if($passwordReset &&$passwordReset->token != $request->otp)
        {
            DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->increment('attempts');
        }

        if (!$passwordReset || $passwordReset->token != $request->otp) {
            return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);
        }
    
        // Check if the OTP has expired (e.g., 5 minutes)
        $expirationTime = now()->subMinutes(5);
        if ($passwordReset->created_at < $expirationTime) {

            return $this->sendError('Invalid OTP. The OTP has expired.', ['error' => 'OTP has expired'], 404); 
        }
    
        // Check the number of attempts
        $attempts = $passwordReset->attempts ?? 0;
        if ($attempts >= 5) {
            // Delete the used OTP from the database
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
    
            // Send a new OTP and reset the attempts count
            // Generate a new OTP
            $newOtp = rand(100000, 999999);
    
            // Save the new OTP and reset the attempts count in the database
            DB::table('password_reset_tokens')->updateOrInsert([
                'email' => $request->email,
            ], [
                'email' => $request->email,
                'token' => $newOtp,
                'attempts' => 0,
                'created_at' => now(),
            ]);
    
            $mailData = [
                'otp' => $newOtp
            ];
    
            try {
                // Send the new OTP email
                Mail::to($user->email)->send(new OtpMail($mailData));
    
                // Do not provide specific error messages, only indicate that the email was sent successfully
                return $this->sendResponse('', 'Invalid OTP. Too many attempts. A new OTP has been sent to your email. Please check your mailbox.', 200);
            } catch (\Exception $e) {
                // Do not provide specific error messages, only indicate that the email sending failed
                return $this->sendError('', 'The system is unable to send emails. Please try again later.', 400);
            }
        }
    
         
    
        // Reset the password
        $user->update([
            'password' => bcrypt($request->password),
        ]);
    
        // Delete the used OTP from the database
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
    
        // Do not provide specific success messages, only indicate that the password has been successfully modified
        return $this->sendResponse('', 'Your password has been successfully modified.', 200);
    }*/
    // This function handles the initial OTP verification and password reset
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

    $passwordReset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();
        
        if (!$passwordReset) {
            return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);
        }else {
            if ($passwordReset->token != $request->otp) {
                // Increment the attempts count
                DB::table('password_reset_tokens')
                    ->where('email', $passwordReset->email)
                    ->increment('attempts');
                    $attempts = $passwordReset->attempts ?? 0;
                    if ($attempts >= 5) {
                        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                        
                        try {
                            $newOtp = rand(100000, 999999);
                            $mailData = [
                                'otp' => $newOtp
                            ];
                            // Send the new OTP email
                            Mail::to($request->email)->send(new OtpMail($mailData));
                            DB::table('password_reset_tokens')->updateOrInsert([
                                'email' => $request->email,
                            ], [
                                'email' => $request->email,
                                'token' => $newOtp,
                                'created_at' => now(),
                            ]);
                            // Do not provide specific error messages, only indicate that the email was sent successfully
                            return $this->sendError('Invalid OTP. Too many attempts. A new OTP has been sent to your email. Please check your mailbox.', [], 200);
                        } catch (\Exception $e) {
                            // Do not provide specific error messages, only indicate that the email sending failed
                            return $this->sendError('', 'The system is unable to send emails. Please try again later.', 400);
                        }
                        
                    }
                    return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);
                 
            }else{
                $expirationTime = now()->subMinutes(5);
                if ($passwordReset->created_at < $expirationTime) {
                    DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                    return $this->sendError('Invalid OTP. The OTP has expired.', ['error' => 'OTP has expired'], 404); 
                }
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
            
                // Delete the used OTP from the database
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return $this->sendResponse('', 'Your password has been successfully modified.', 200);
            }   
        }     
}


public function checkOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'otp' => 'required',
    ]);

    if ($validator->fails()) {
        return $this->sendError('Validation Error.', $validator->errors());
    }
   
    $passwordReset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();
         
    if (!$passwordReset) {
        return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);
    } else {
        if ($passwordReset->token != $request->otp) {
            // Increment the attempts count
            DB::table('password_reset_tokens')
                ->where('email', $passwordReset->email)
                ->increment('attempts');
            $attempts = $passwordReset->attempts ?? 0;
            if ($attempts >= 5) {
                DB::table('password_reset_tokens')->where('email', $passwordReset->email)->delete();

                try {
                    $newOtp = rand(100000, 999999);
                    $mailData = [
                        'otp' => $newOtp
                    ];
                    // Send the new OTP email
                    Mail::to($request->email)->send(new OtpMail($mailData));
                    DB::table('password_reset_tokens')->updateOrInsert([
                        'email' => $request->email,
                    ], [
                        'email' => $request->email,
                        'token' => $newOtp,
                        'created_at' => now(),
                    ]);
                    // Do not provide specific error messages, only indicate that the email was sent successfully
                    return $this->sendError('Invalid OTP. Too many attempts. A new OTP has been sent to your email. Please check your mailbox.', [], 200);
                } catch (\Exception $e) {
                    // Do not provide specific error messages, only indicate that the email sending failed
                    return $this->sendError('The system is unable to send emails. Please try again later.', 400);
                }
            }
            return $this->sendError('Invalid OTP. Please check the code and try again.', ['error' => 'Invalid OTP'], 404);
        } else {
            $expirationTime = now()->subMinutes(5);
            if ($passwordReset->created_at < $expirationTime) {
                DB::table('password_reset_tokens')->where('email', $passwordReset->email)->delete();
                return $this->sendError('Invalid OTP. The OTP has expired.', ['error' => 'OTP has expired'], 404);
            }
            return $this->sendResponse('', 'Valid OTP.', 200);
        }
    }
}

     
} 




 