<?php

namespace App\Http\Controllers;

use App\Models\events_routes;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use function Laravel\Prompts\error;

class Events_Controller extends Controller
{
    public function list_events(Request $request)
    {
        try {
            if (auth()->user()->is_active === 0) {
                throw error('usuario borrado');
            }
            $page_count = $request->query('count', 1);
            $today = Carbon::now()->toDateString();
            $events = events_routes::with('usersData')->whereDate('date','>=',$today)->paginate($page_count);
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
            if (auth()->user()->is_active === 0) {
                throw error('usuario borrado');
            }

            // validar
            $validator = $this->validate_event($request);

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
            $date = Carbon::parse($request->input('date'))->toDateString();
            $kms = $request->input('kms');
            $img = $request->input('img');
            $maps = $request->input('maps');

            // guardarla
            $new_event = events_routes::create(
                [
                    'id_user' => auth()->user()->id,
                    'date' => $date,
                    'kms' => $kms,
                    'img' => $img,
                    'maps' => $maps,
                    'participants' => 1
                ]
            );
            $new_event->usersData()->attach(auth()->user()->id);
            // devolver respuesta
            return response()->json(
                [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'data' => $new_event
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
    public function validate_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|min:3|max:20',
            'kms' => 'required|min:2|max:5',
            'img' => 'required|min:1|max:250',
            'maps' => 'required|min:1|max:12',
        ]);
        return $validator;
    }

    public function add_participant(Request $request, $id_event)
    {
        try {
            $user = auth()->user();
            if ($user->is_active === 0) {
                throw error('usuario borrado');
            } else {

                $event = events_routes::findOrFail($id_event);
                $event->participants = $event->participants + 1;
                $event->save();

                // devolver respuesta
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'User registered successfully',
                        'data' => $event
                    ],
                    Response::HTTP_OK
                );
            }
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
    public function remove_participant(Request $request, $id_event)
    {
        try {
            $user = auth()->user();
            if ($user->is_active === 0) {
                throw error('usuario borrado');
            }
            $event = events_routes::findOrFail($id_event);
            $event->participants = $event->participants - 1;
            $event->save();

            // devolver respuesta
            return response()->json(
                [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'data' => $event
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
