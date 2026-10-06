<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::query()
            ->with(['country', 'state']);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('country')) {
            $query->whereHas('country', function ($q) use ($request) {
                $q->where('slug', $request->input('country'));
            });
        }

        if ($request->filled('state')) {
            $query->whereHas('state', function ($q) use ($request) {
                $q->where('slug', $request->input('state'));
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status'));
        }

        if ($request->filled('capital')) {
            $query->where('is_capital', $request->input('capital'));
        }

        $cities = $query
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $states = State::query()
            ->where('is_active', true)
            ->with('country')
            ->orderBy('name')
            ->get();

        return view('admin.reference.cities.index', compact(
            'cities',
            'countries',
            'states'
        ));
    }

    public function create()
    {
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $states = State::query()
            ->where('is_active', true)
            ->with('country')
            ->orderBy('name')
            ->get();

        return view('admin.reference.cities.create', compact(
            'countries',
            'states'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCity($request);

        $country = Country::findOrFail($validated['country_id']);

        if (!empty($validated['state_id'])) {
            $this->ensureStateBelongsToCountry(
                $validated['state_id'],
                $country->id
            );
        }

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name'],
            $country
        );

        $validated['created_by'] = Auth::id();

        City::create($validated);

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function show(City $city)
    {
        $city->load(['country', 'state']);

        return view('admin.reference.cities.show', compact('city'));
    }

    public function edit(City $city)
    {
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $states = State::query()
            ->where('is_active', true)
            ->with('country')
            ->orderBy('name')
            ->get();

        return view('admin.reference.cities.edit', compact(
            'city',
            'countries',
            'states'
        ));
    }

    public function update(Request $request, City $city)
    {
        $validated = $this->validateCity($request, $city);

        $country = Country::findOrFail($validated['country_id']);

        if (!empty($validated['state_id'])) {
            $this->ensureStateBelongsToCountry(
                $validated['state_id'],
                $country->id
            );
        }

        if (
            $validated['name'] !== $city->name ||
            $validated['country_id'] != $city->country_id
        ) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $country,
                $city->id
            );
        }

        $validated['updated_by'] = Auth::id();

        $city->update($validated);

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        $city->deleted_by = Auth::id();
        $city->save();

        $city->delete();

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'City moved to trash.');
    }

    public function trash()
    {
        $cities = City::onlyTrashed()
            ->with(['country', 'state'])
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view('admin.reference.cities.trash', compact('cities'));
    }

    public function restore(City $city)
    {
        $city->restore();

        $city->deleted_by = null;
        $city->save();

        return redirect()
            ->route('admin.cities.trash')
            ->with('success', 'City restored successfully.');
    }

    public function forceDelete(City $city)
    {
        $city->forceDelete();

        return redirect()
            ->route('admin.cities.trash')
            ->with('success', 'City permanently deleted.');
    }

    private function validateCity(
        Request $request,
        ?City $city = null
    ): array {
        return $request->validate([
            'country_id' => [
                'required',
                'integer',
                'exists:countries,id',
            ],

            'state_id' => [
                'nullable',
                'integer',
                'exists:states,id',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cities', 'name')
                    ->where(fn ($query) => $query->where(
                        'country_id',
                        $request->input('country_id')
                    ))
                    ->ignore($city?->id),
            ],

            'code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'is_capital' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);
    }

    private function generateUniqueSlug(
        string $name,
        Country $country,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 2;

        while (
            City::withTrashed()
                ->where('country_id', $country->id)
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function ensureStateBelongsToCountry(
        int $stateId,
        int $countryId
    ): void {
        abort_unless(
            State::whereKey($stateId)
                ->where('country_id', $countryId)
                ->exists(),
            422
        );
    }
}