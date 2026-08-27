<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $totalIndustry = Industry::count();

        $industries = Industry::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate(50)
            ->withQueryString();

        if($request->ajax()){
            return response()->json(['html' => view('general.industry', compact('industries', 'totalIndustry'))->render(),]);
        }

        return view('general.industry', compact('industries', 'totalIndustry'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('industries.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:industries,name',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Industry::create($validated);

        return redirect()
            ->route('industries.index')
            ->with('success', 'Industry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Industry $industry)
    {
        return redirect()->route('industries.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Industry $industry)
    {
        $industries = Industry::orderBy('name')->paginate(50);
        $totalIndustry = Industry::count();

        return view('general.industry', compact('industries', 'industry', 'totalIndustry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Industry $industry)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:industries,name,' . $industry->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $industry->update($validated);

        return redirect()
            ->route('industries.index')
            ->with('success', 'Industry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Industry $industry)
    {
        if ($industry->companies()->exists()) {
            return redirect()->route('countries.index')->with('error', 'Cannot delete country assigned to companies.');
        }

        $industry->delete();

        return redirect()
            ->route('industries.index')
            ->with('success', 'Industry deleted successfully.');
    }
}