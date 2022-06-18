<?php

namespace App\Http\Controllers;

use App\Models\LocalOfficial;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use App\Models\Log;
use App\Models\Instance;
use App\Speed;

class AuthController extends Controller
{
    public function index()
    {
        Speed::create(['speed' => rand(20, 200)]);

        $speeds = Speed::latest()->take(30)->get()->sortBy('id');
        $labels = $speeds->pluck('id');
        $data = $speeds->pluck('speed');

        return response()->json(compact('labels', 'data'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));

        return response()
            ->json([
                'status' => 'success',
                'message' => 'You have successfully registered !',
                'data' => $user,
            ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        if (is_numeric($request->get('email'))) {
            if (!Auth::attempt([
                'phone_number' => $request['email'],
                'password' => $request['password']
            ])) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid phone number or password',
                ], 401);
            }
        } else {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid email or password',
                ], 401);
            }
        }
        if (is_numeric($request->get('email'))) {
            $user = User::where('phone_number', $request['email'])->firstOrFail();
        } else {
            $user = User::where('email', $request['email'])->firstOrFail();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Logging
        // $log = new Log();
        // $log->content = $user . ' logged in';
        // $log->save();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully logged in !',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        // Logging
        // $log = new Log();
        // $log->content = $request->user() . ' logged out';
        // $log->save();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully request profile information',
            'data' => $request->user(),
        ], 200);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            // 'username' => ['required', Rule::unique('users', 'username')->ignore($request->user())],
            // 'old_password' => 'string|min:8',
            // 'new_password' => 'string|min:8|different:old_password',
            // 'confirm_password' => 'require   d_with:new_password|same:new_password|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        $user = $request->user();

        $user->name = $request->name;
        $user->no_telp = $request->no_telp;
        // $user->username = $request->username;

        // if (isset($request->new_password)) {
        //     if (!Hash::check($request->old_password, $request->user()->password)) {
        //         return response()->json([
        //             'status' => 'failed',
        //             'message' => 'You have input wrong password !'
        //         ], 400);
        //     }
        //     $user->password = Hash::make($request->new_password);
        // }

        $user->save();

        // Logging
        // $log = new Log();
        // $log->content = $user . ' update profile';
        // $log->save();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully update your profile !',
            'name' => $user->name,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ], 200);
    }

    public function delete_profile(Request $request)
    {
        // Logging
        // $log = new Log();
        // $log->content = $request->user() . ' delete profile';
        // $log->save();

        // $user = $request->user();
        // $user->delete();

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'You account have successfully deleted'
        // ], 200);
    }

    public function checkPassword(Request $request)
    {
        // if (!Hash::check($request->password, $request->user()->password)) {
        //     return response()->json([
        //         'status' => 'failed',
        //         'message' => 'You have input wrong password !',
        //     ], 400);
        // }

        // // Logging
        // $log = new Log();
        // $log->content = $request->user() . ' check password';
        // $log->save();

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'You have input right password !'
        // ], 200);
    }

    public function test(Request $request)
    {
        // $answer = $request->content;
        // $answer_array = [];
        // foreach ($answer as $as) {
        //     array_push($answer_array,$as["answerUser"]);
        // }
        // return $answer_array;
    }
}
