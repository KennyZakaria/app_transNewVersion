<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ClientController  extends BaseController
{
    public function update(Request $request)
    {
        $user_id=$request->user()->id;
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'unique:users,email,'.$user_id,
            'tel' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            $user = User::find($user_id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $dataToUpdate = [
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName')
            ];

            if ($user->email !== $request->input('email')) {
                $dataToUpdate['email'] = $request->input('email');
                $dataToUpdate['verifiedEmail'] = false;
                $dataToUpdate['email_verified_at'] = null;
            }

            $user->update($dataToUpdate);
            $transporteur = Client::where('user_id', $user_id)->first();
            Client::where('user_id', $user_id)->update([
                'tel' => $request->input('tel')

            ]);

            DB::commit();
            $client = Client::where('user_id', $user_id)->first();

            return response()->json(['message' => 'Transporteur updated successfully', 'data' => $client], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }

    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required|min:8',
            'newPassword' => 'required|min:8', // Fix the typo here to match your input field name
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user(); // Assuming you're using Laravel's built-in authentication system
        $currentPassword = $request->input('currentPassword');

        if (Hash::check($currentPassword, $user->password)) {
            $newPassword = $request->input('newPassword');
            $user->password = Hash::make($newPassword);
            $user->save();

            return response()->json(['message' => 'Password updated successfully']);
        } else {
            return response()->json(['errors' => ['ancienPassword' => 'Incorrect old password']], 422);
        }
    }
}
