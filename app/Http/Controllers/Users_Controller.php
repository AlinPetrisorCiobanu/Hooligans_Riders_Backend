<?php

namespace App\Http\Controllers;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function Laravel\Prompts\error;

class Users_Controller extends Controller
{
    public function list_users()
    {
        try {
            if(auth()->user()->is_active === 0){
                throw error('usuario borrado');
            }
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
                    'message' => 'Error marking user as inactive',
                    'error' => $th->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update_user(Request $request)
    {
        try {
            if(auth()->user()->is_active === 0){
                throw error('usuario borrado');
            }
            // Obtener el usuario autenticado
            $id_user = auth()->user()->id;
            // Actualizar el usuario solo si se proporciona al menos un campo
            if ((!$request->has('name') && !$request->has('last_name')) && (!$request->has('date') && !$request->has('phone') && !$request->has('nickname')) && !$request->has('password')) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'No se proporcionaron datos para actualizar',
                        'error' => 'Proporcione al menos un campo (name, last_name, date, phone, email, nickname, password) para la actualización.'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Validar solo los campos proporcionados
            $validatorData = [];

            if ($request->has('name')) {
                $validatorData['name'] = 'min:3|max:100';
            }

            if ($request->has('last_name')) {
                $validatorData['last_name'] = 'min:3|max:100';
            }

            if ($request->has('date')) {
                $validatorData['date'] = 'min:3|max:20';
            }

            if ($request->has('phone')) {
                $validatorData['phone'] = [ Rule::unique('users')->ignore($id_user)];
            }

            if ($request->has('nickname')) {
                $validatorData['nickname'] = [ Rule::unique('users')->ignore($id_user)];
            }

            if ($request->has('password')) {
                $validatorData['password'] = 'sometimes|min:6|max:50';
            }

            $validator = Validator::make($request->all(), $validatorData);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Error en los campos',
                        'error' => $validator->errors()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Actualizar el usuario
            $userToUpdate = User::findOrFail($id_user);

            // Actualizar campos según la solicitud

            if ($request->has('name')) {
                $userToUpdate->name = $request->input('name');
            }

            if ($request->has('last_name')) {
                $userToUpdate->last_name = $request->input('last_name');
            }

            if ($request->has('date')) {
                $userToUpdate->date = $request->input('date');
            }

            if ($request->has('phone')) {
                $userToUpdate->phone = $request->input('phone');
            }

            if ($request->has('nickname')) {
                $userToUpdate->nickname = $request->input('nickname');
            }

            // Actualizar la contraseña si se proporciona
            if ($request->has('password')) {
                $userToUpdate->password = bcrypt($request->input('password'));
            }
            
            // Guardar los cambios
            $userToUpdate->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User updated successfully',
                    'data' => $userToUpdate,
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error updating user',
                    'error' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function delete_user()
    {
        try {
            if(auth()->user()->is_active === 0){
                throw error('usuario borrado');
            }
            // Obtener el usuario autenticado
            $id_user = auth()->user()->id;

            // Obtener usuario para realizar la actualización
            $userToDelete = User::findOrFail($id_user);

            if (!$userToDelete) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User not found',
                        
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            // Realizar la actualización
            $userToDelete->update(['is_active' => 0]);

            // Devolver respuesta
            return response()->json(
                [
                    'success' => true,
                    'message' => 'User marked as inactive successfully',
                    'data' => $userToDelete
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error marking user as inactive',
                    'error' => $th->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
