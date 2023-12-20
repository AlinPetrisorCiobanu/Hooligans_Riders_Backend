<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // validar
            $validator = $this->validateRegisterDataUser($request);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not registered',
                        'error' => $validator->errors()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // recoger info
            $name = $request->input('name');
            $last_name = $request->input('last_name');
            $date = $request->input('date');
            $phone = $request->input('phone');
            $email = $request->input('email');
            $nickname = $request->input('nickname');
            $password = $request->input('password');

            // tratar info
            $encryptedPassword = bcrypt($password);

            // guardarla
            $newUser = User::create(
                [
                    'name' => $name,
                    'last_name' => $last_name,
                    'date' => $date,
                    'phone' => $phone,
                    'email' => $email,
                    'nickname' => $nickname,
                    'password' => $encryptedPassword
                ]
            );

            // devolver respuesta
            return response()->json(
                [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'data' => $newUser
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User cant be registered',
                    'error' => $th->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function validateRegisterDataUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'date' => 'required|min:3|max:20',
            'phone' => 'required|unique:users|min:8|max:15',
            'email' => 'required|unique:users|email|min:6|max:250',
            'nickname' => 'required|unique:users|min:3|max:100',
            'password' => 'required|min:6|max:12',
        ]);

        return $validator;
    }
}
