<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    // Display all careers sites
    public function index(){
        $careers = Career::latest()->paginate(25);
        return view('careers.index', compact('careers'));
    }

    // Show create career form
    public function create(){
        return view('careers.create');
    }

    // Store a new career site
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'required|string|url|unique:careers,website',
            'career' => 'required|string|unique:careers,career',
            'industry' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255'
        ]);

        Career::create($validated);

        return redirect()->route('careers.index')->with('success', 'Career created successfully.');
    }

    // Show single career site
    public function show(Career $career){
        return view('careers.show', compact('career'));
    }

    // Show edit form
    public function edit(Career $career){
        return view('careers.edit', compact('career'));
    }

    // Update a career site
    public function update(Request $request, Career $career){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'required|string|url|unique:careers,website,' . $career->id,
            'career' => 'required|string|unique:careers,career,' . $career->id,
            'industry' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255'
        ]);

        $career->update($validated);

        return redirect()->route('careers.index')->with('success', 'Career updated successfully.');
    }

    // Delete a career site
    public function destroy(Career $career){
        $career->delete();
        return redirect()->route('careers.index')->with('success', 'Career deleted successfully.');
    }
}
