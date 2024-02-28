<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ],
            [],
            ['name' => 'Name', 'password' => 'Password', 'email' => 'E-Mail']
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["status" => false, "messages" => $messages], 500);
        }

        try {
            $user = new User;
            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->password = Hash::make($request->input("password"));
            $user->save();
            return response()->json(['status' => true, 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
    function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ],
            [],
            ['password' => 'Password', 'email' => 'E-Mail']
        );
        
        try {
            $user = User::where("email", $request->input("email"))->first();
            return ($user);
            if ($user) {
                if (Hash::check($user->password, $request->input("password"))) {
                    return response()->json(['status' => true, 'user' => $user]);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid Password"]);
                }
            } else {
                return response()->json(['status' => false, "message" => "Invalid E-Mail"]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
