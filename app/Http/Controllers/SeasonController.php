<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::latest()->get();
        return view('admin.seasons.index', compact('seasons'));
    }

    public function create()
    {
        return view('admin.seasons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:seasons,name',
            'status' => 'required|integer|in:0,1',
        ]);

        Season::create($request->only('name', 'status'));

        return redirect()->route('admin.seasons.index')
            ->with('success', 'Season created successfully!');
    }

    public function edit(Season $season)
    {
        return view('admin.seasons.edit', compact('season'));
    }

    public function update(Request $request, Season $season)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:seasons,name,' . $season->id,
            'status' => 'required|integer|in:0,1',
        ]);

        $season->update($request->only('name', 'status'));

        return redirect()->route('admin.seasons.index')
            ->with('success', 'Season updated successfully!');
    }

    public function destroy(Season $season)
    {
        $season->delete();

        return redirect()->route('admin.seasons.index')
            ->with('success', 'Season deleted successfully!');
    }
}
