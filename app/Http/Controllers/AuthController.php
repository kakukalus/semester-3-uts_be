<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        // buat array untuk request input
        $input = [
            'email'=> $request->email,
            'password' => Hash::make($request->password),
        ];

        // check data user berdasarkan email yang di input
        $user = User::where('email' , $input['email'])->first();
        
        //get data user email dan password
        $email    = $user->email;
        $password = $user->password;

        // check data user berdasarkan email dan password
        $isLoginSuccessFully = $input['email'] == $email && Hash::make($password == $password);

        //jika sukses maka masuk ke kondisi if
        if($isLoginSuccessFully === true){
            $token = $user->createToken('auth_token');
            $data = [
                'Message' => 'Login SuccesFully',
                'token'   => $token->plainTextToken
            ];
            return response()->json($data,200);
        }else{
            $data = [
                'Message' => 'Username or Password is wrong',
            ];
        }
    }

    public function register(Request $request){
        // buat array untuk request input
        $input = [
            'name' => $request->name,
            'email'=> $request->email,
            'password' => $request->password,
        ];

        $user = User::create($input);
        return response()->json([
            'Message' => 'User is created Succesfully',
            'StatusCode' => 201,
            'Data' => $user,
        ]);      
    }

    public function logoutApi(Request $request) {
        auth()->logout();
        auth()->logout(true);
        return response()->json([
            'StatusCode' => 200,
            'message' => 'User loggedOut successfully'
        ], 200);
    }
}
