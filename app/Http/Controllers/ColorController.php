<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->get();
        return view('backend.color.index', compact('colors'));
    }

    public function create()
    {
        return view('backend.color.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Color::create($request->all());

        return redirect()->route('color.index')->with('success', 'Color added successfully.');
    }

    public function edit(Color $color)
    {
        return view('backend.color.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $color->update($request->all());

        return redirect()->route('color.index')->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('color.index')->with('success', 'Color deleted successfully.');
    }
}
