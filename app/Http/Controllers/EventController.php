<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return response()->json($events,200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos que llegan en el JSON
        $validator = Validator::make($request->all(), [
            'event_name'   => 'required|string|max:255',
            'event_detail' => 'nullable|string|max:255',
            'event_type_id' => 'required|integer|exists:event_types,id',
        ]);

        // 2. Si algo falla, devolvemos los errores
        if ($validator->fails()) {
            return response()->json(['message' => 'Failed', 'data' => $validator->messages()], 400);
        }

        // 3. Guardamos el evento en la base de datos
        $event = Event::create($validator->validated());

        // 4. Devolvemos el evento creado
        return response()->json(['message' => 'Event Created', 'data' => $event], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function listUsers(Event $event)
    {
        $users = $event->users;
        return response()->json(['message'=>null,'data'=>$users],200);
    }
}
