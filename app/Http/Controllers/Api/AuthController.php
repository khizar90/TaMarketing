<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\DeleteAccountRequest;
use App\Http\Requests\Api\EditProfileRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\LogoutRequest;
use App\Http\Requests\Api\NewPasswordRequest;
use App\Http\Requests\Api\OtpVerifyRequest;
use App\Http\Requests\Api\RecoverVerifyRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\VerifyRequest;
use App\Mail\ForgotOtp;
use App\Mail\OtpSend;
use App\Models\OtpVerify;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function verify(VerifyRequest $request)
    {

        $otp = random_int(100000, 999999);

        $mail_details = [
            'body' => $otp,
        ];
        Mail::to($request->email)->send(new OtpSend($mail_details));


        $user = new OtpVerify();
        $user->email = $request->email;
        $user->otp = $otp;
        $user->save();
        return response()->json([
            'status' => true,
            'action' => 'User verify and Otp send',
        ]);
    }
    public function otpVerify(OtpVerifyRequest $request)
    {
        $user = OtpVerify::where('email', $request->email)->latest()->first();
        if ($user) {
            if ($request->otp == $user->otp) {
                $user = OtpVerify::where('email', $request->email)->delete();
                return response()->json([
                    'status' => true,
                    'action' => 'OTP verify',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'OTP is invalid, Please enter a valid OTP',
                ]);
            }
        }
    }
    public function register(RegisterRequest $request)
    {
        $create = new User();
        $create->name = $request->name;
        $create->email = $request->email;
        $create->password = Hash::make($request->password);
        

      
        $create->save();

        $userdevice = new UserDevice();
        $userdevice->user_id = $create->uuid;
        $userdevice->device_name = $request->device_name ?? 'No name';
        $userdevice->device_id = $request->device_id ?? 'No ID';
        $userdevice->timezone = $request->timezone ?? 'No Time';
        $userdevice->token = $request->fcm_token ?? 'No tocken';
        $userdevice->save();


        $newuser  = User::where('uuid', $create->uuid)->first();

        return response()->json([
            'status' => true,
            'action' => 'User register successfully',
            'data' => $newuser
        ]);
    }
    public function login(LoginRequest $request)
    {
        $user = User::Where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $userdevice = new UserDevice();
                $userdevice->user_id = $user->uuid;
                $userdevice->device_name = $request->device_name ?? 'No name';
                $userdevice->device_id = $request->device_id ?? 'No ID';
                $userdevice->timezone = $request->timezone ?? 'No Time';
                $userdevice->token = $request->fcm_token ?? 'No tocken';
                $userdevice->save();

                return response()->json([
                    'status' => true,
                    'action' => "Login successfully",
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'Password is invalid, please enter a valid Password',
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'action' => "Account not Found",

        ]);
    }
    public function recover(RecoverVerifyRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $otp = random_int(100000, 999999);

            $userOtp = new OtpVerify();
            $userOtp->email = $request->email;
            $userOtp->otp = $otp;
            $userOtp->save();

            $mailDetails = [
                'body' => $otp,
                'name' => $user->name
            ];

            Mail::to($request->email)->send(new ForgotOtp($mailDetails));

            return response()->json([
                'status' => true,
                'action' => 'Otp send successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'Account not found'
            ]);
        }
    }

    public function newPassword(NewPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'action' => "New password is same as Old password",
                ]);
            } else {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                return response()->json([
                    'status' => true,
                    'action' => "New password set",
                ]);
            }
            // $user->update([
            //     'password' => Hash::make($request->password)
            // ]);
            return response()->json([
                'status' => true,
                'action' => "New Password set"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'This Email Address is not registered'
            ]);
        }
    }


    public function logout(LogoutRequest $request)
    {
        UserDevice::where('user_id', $request->user_id)->where('device_id', $request->device_id)->delete();

        return response()->json([
            'status' => true,
            'action' => 'User logged out'
        ]);
    }

    public function deleteAccount(DeleteAccountRequest $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'action' => "Account deleted",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'Please enter correct password',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found"
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                if (Hash::check($request->new_password, $user->password)) {

                    return response()->json([
                        'status' => false,
                        'action' => "New password is same as old password",
                    ]);
                } else {
                    $user->update([
                        'password' => Hash::make($request->new_password)
                    ]);
                    return response()->json([
                        'status' => true,
                        'action' => "Password  change",
                    ]);
                }
            }
            return response()->json([
                'status' => false,
                'action' => "Old password is wrong",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found'
            ]);
        }
    }

    public function editImage(Request $request)
    {

        $user = User::find($request->user_id);
        if ($user) {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // $path = Storage::disk('s3')->putFile('user/' . $request->user_id . '/profile', $file);
                // $path = Storage::disk('s3')->url($path);
                $extension = $file->getClientOriginalExtension();
                $mime = explode('/', $file->getClientMimeType());
                $filename = time() . '-' . uniqid() . '.' . $extension;
                if ($file->move('uploads/user/' . $request->user_id . '/profile/', $filename))
                    $path = '/uploads/user/' . $request->user_id . '/profile/' . $filename;
                $user->image = $path;
            }
            $user->save();


            return response()->json([
                'status' => true,
                'action' => "Image edit",
                'data' => $user
            ]);
        }

        return response()->json([
            'status' => false,
            'action' => "User not found"
        ]);
    }

    public function removeImage($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $user->image = '';

            $user->save();
            return response()->json([
                'status' => true,
                'action' => "Image remove",
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found"
            ]);
        }
    }
    public function editProfile(EditProfileRequest $request)
    {

        $user = User::find($request->user_id);
        if ($user) {
        
            if ($request->has('email')) {
                if (User::where('email', $request->email)->where('uuid', '!=', $request->user_id)->exists()) {
                    return response()->json([
                        'status' => false,
                        'action' => 'Email Address is already registered'
                    ]);
                } else {
                    $user->email = $request->email;
                }
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

          


            $user->save();
            return response()->json([
                'status' => true,
                'action' => "Profile edit",
                'data' => $user
            ]);
        }

        return response()->json([
            'status' => false,
            'action' => "User not found"
        ]);
    }
}
