<?php

namespace App\Http\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class Users_Controller extends Controller
{
    public function list_users(Request $request)
    {
        try {
            $id_user = auth()->user()->id;
            $user = User::find($id_user);
            
            return response()->json(
                [
                    'succes' => true,
                    'message' => 'usuarios',
                    'data' => $user
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'succes' => false,
                    'message' => 'NO',
                    'error' => $th->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
