<?php

namespace App\Http\Controllers;

use App\Models\events_routes;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\error;

class Events_Controller extends Controller
{
    public function list_events()
    {
        try {
            if(auth()->user()->is_active === 0){
                throw error('usuario borrado');
            }
            $events = events_routes::get(['*']);
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
    
    public function new_event(Request $request)
    {
        try {
            if(auth()->user()->is_active === 0){
                throw error('usuario borrado');
            }
            $events = events_routes::get(['*']);
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
