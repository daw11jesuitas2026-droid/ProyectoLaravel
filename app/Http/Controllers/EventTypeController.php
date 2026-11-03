<?php

namespace App\Http\Controllers;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    public function listEvents(EventType $type){
        $events = $type->events;
        return response()->json(['message'=>null,'data'=>$events],200);
    }
}
