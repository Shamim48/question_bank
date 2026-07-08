<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['classLevel', 'season'])
            ->where('status', 1)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('website.events', compact('events'));
    }
}
