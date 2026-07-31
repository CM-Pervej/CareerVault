<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::orderBy('name')->get();
        return view('general.country', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('countries.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries,name',
            'iso_code' => 'nullable|string|max:10',
            'phone_code' => 'nullable|string|max:20',
            'currency' => 'nullable|string|max:50',
            // 'currency_code' => 'nullable|string|max:10',
            'capital' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'flag' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Country::create($validated);

        return redirect()->route('countries.index')->with('success', 'Country created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return redirect()->route('countries.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        $countries = Country::orderBy('name')->get();

        return view('general.country', compact('countries', 'country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries,name,' . $country->id,
            'iso_code' => 'nullable|string|max:10',
            'phone_code' => 'nullable|string|max:20',
            'currency' => 'nullable|string|max:50',
            // 'currency_code' => 'nullable|string|max:10',
            'capital' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'flag' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $country->update($validated);

        return redirect()->route('countries.index')->with('success', 'Country updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        if ($country->companies()->exists()) {
            return redirect()->route('countries.index')->with('error', 'Cannot delete country assigned to companies.');
        }

        $country->delete();

        return redirect()->route('countries.index')->with('success', 'Country deleted successfully.');
    }
}