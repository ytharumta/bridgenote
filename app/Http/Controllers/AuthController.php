<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validate = \Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null,
            ];
            return response()->json($respon, 200);
        } else {
            $credentials = request(['email', 'password']);
            $credentials = Arr::add($credentials, 'status', 'active');
            if (!Auth::attempt($credentials)) {
                $respon = [
                    'status' => 'error',
                    'msg' => 'Unathorized',
                    'errors' => null,
                    'content' => null,
                ];
                return response()->json($respon, 401);
            }

            $user = User::where('email', $request->email)->first();
            if (! \Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            $tokenResult = $user->createToken('token-auth')->plainTextToken;
            $respon = [
                'status' => 'success',
                'msg' => 'Login successfully',
                'errors' => null,
                'content' => [
                    'status_code' => 200,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ]
            ];
            return response()->json($respon, 200);
        }
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $respon = [
            'status' => 'success',
            'msg' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }

    public function register(Request $request){
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required'],
            'position' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
         
        $user = new User;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = \Hash::make($request->get('password'));
        $user->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function all(){
        $users = User::all();
        
        return response()->json([
            'status'=> 'success',
            'msg' => 'all list user',
            'errors' => null,
            'content' => $users
        ],200);
    }

    public function edit($id, Request $request){
        $user = User::findOrFail($id);
        
        if($request->name){
            $user->name = $request->name;
        }
        if($request->email){
            $user->email = $request->email;
        }
        if($request->password){
            $user->password = $request->password;
        }
        if($request->status){
            $user->status = $request->status;
        }
        if($request->position){
            $user->position = $request->position;
        }
        $user->update();

        $respon = [
            'status' => 'success',
            'msg' => 'User has been updated',
            'errors' => null,
            'content' => null,
        ];

        return response()->json($respon, 200);
    }

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();

        $respon = [
            'status' => 'success',
            'msg' => 'User has been deleteed',
            'errors' => null,
            'content' => null,
        ];

        return response()->json($respon, 200);
    }

    public function show($id){
        $user = User::findOrFail($id);
        $respon = [
            'status' => 'success',
            'msg' => 'show user',
            'errors' => null,
            'content' => $user,
        ];
        return response()->json($respon, 200);
    }
}
