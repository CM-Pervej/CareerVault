<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $totalCity = City::count();

        $cities = City::with('country')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('country_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
            // ->paginate(200)
            // ->withQueryString();

        $countries = Country::orderBy('name')->get();

                // AJAX search/pagination request
        if ($request->ajax()) {
            return response()->json(['html' => view('general.city', compact('cities', 'countries', 'totalCity'))->render(),]);
        }

        return view('general.city', compact('cities', 'countries', 'totalCity'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('general.city', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => [
                'required',
                'exists:countries,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities', 'name')
                    ->where('country_id', $request->country_id),
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        City::create($validated);

        return redirect()
            ->route('cities.index')
            ->with('success', 'City created successfully.');
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
    public function edit(Country $country, City $city)
    {
        abort_unless($city->country_id === $country->id, 404);
        $totalCity = City::count();

        $cities = City::with('country')
            ->orderBy('name')
            ->get();

        $countries = Country::orderBy('name')->get();

        return view('general.city', compact(
            'city',
            'cities',
            'countries',
            'totalCity'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country, City $city)
    {
        abort_unless($city->country_id === $country->id, 404);

        $validated = $request->validate([
            'country_id' => [
                'required',
                'exists:countries,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities', 'name')
                    ->where('country_id', $request->country_id)
                    ->ignore($city->id),
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $city->update($validated);

        return redirect()
            ->route('cities.index')
            ->with('success', 'City updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(City $city)
    // {
    //     $city->delete();

    //     return redirect()->route('cities.index')->with('success', 'City deleted successfully.');
    // }
    public function destroy(Country $country, City $city)
    {
        abort_unless($city->country_id === $country->id, 404);

        $city->delete();

        return redirect()
            ->route('cities.index')
            ->with('success', 'City deleted successfully.');
    }
}
