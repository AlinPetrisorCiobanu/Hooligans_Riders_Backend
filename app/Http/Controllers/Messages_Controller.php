<?php

namespace App\Http\Controllers;

use App\Models\messages;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\error;

class Messages_Controller extends Controller
{
    public function list_messages(Request $request)
    {
        try {
            if (auth()->user()->is_active === 0) {
                throw error('usuario borrado');
            }
            if (auth()->user()->role === "rider") {
                throw error('no tienes permiso');
            }
            $page_count = $request->query('count', 6);
            $events = messages::paginate($page_count);
            return response()->json(
                [
                    'succes' => true,
                    'message' => 'usuarios',
                    'data' => $events
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
}
