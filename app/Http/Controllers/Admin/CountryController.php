<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search){
                $q->where('name','like',"%{$search}%")
                    ->orWhere('iso2','like',"%{$search}%")
                    ->orWhere('iso3','like',"%{$search}%")
                    ->orWhere('capital','like',"%{$search}%")
                    ->orWhere('region','like',"%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active',$request->input('status') === 'active');
        }

        $countries = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.reference.countries.index',compact('countries'));
    }

    public function create()
    {
        return view('admin.reference.countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:100'],
            'slug' => ['nullable','string','max:120','unique:countries,slug'],
            'iso2' => ['required','string','size:2','unique:countries,iso2'],
            'iso3' => ['required','string','size:3','unique:countries,iso3'],
            'phone_code' => ['nullable','string','max:10'],
            'currency_code' => ['nullable','string','size:3'],
            'currency_name' => ['nullable','string','max:100'],
            'capital' => ['nullable','string','max:100'],
            'region' => ['nullable','string','max:50'],
            'subregion' => ['nullable','string','max:100'],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['iso2'] = strtoupper(trim($validated['iso2']));
        $validated['iso3'] = strtoupper(trim($validated['iso3']));
        $validated['currency_code'] = isset($validated['currency_code'])
            ? strtoupper(trim($validated['currency_code']))
            : null;
        $validated['slug'] = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['created_by'] = Auth::id();

        Country::create($validated);

        return redirect()->route('admin.countries.index')->with('success','Country created successfully.');
    }

    public function show(Country $country)
    {
        $country->loadCount([
            'states',
            'cities',
            'companies',
        ]);

        return view('admin.reference.countries.show',compact('country'));
    }

    public function edit(Country $country)
    {
        return view('admin.reference.countries.edit',compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:100'],
            'slug' => [
                'nullable', 'string', 'max:120',
                Rule::unique('countries','slug')->ignore($country->id),
            ],
            'iso2' => [
                'required', 'string', 'size:2',
                Rule::unique('countries','iso2')->ignore($country->id),
            ],
            'iso3' => [
                'required', 'string', 'size:3',
                Rule::unique('countries','iso3')->ignore($country->id),
            ],
            'phone_code' => ['nullable','string','max:10'],
            'currency_code' => ['nullable','string','size:3'],
            'currency_name' => ['nullable','string','max:100'],
            'capital' => ['nullable','string','max:100'],
            'region' => ['nullable','string','max:50'],
            'subregion' => ['nullable','string','max:100'],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['iso2'] = strtoupper(trim($validated['iso2']));
        $validated['iso3'] = strtoupper(trim($validated['iso3']));
        $validated['currency_code'] = isset($validated['currency_code'])
            ? strtoupper(trim($validated['currency_code']))
            : null;
        $validated['slug'] = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['updated_by'] = Auth::id();

        $country->update($validated);

        return redirect()->route('admin.countries.index')->with('success','Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        $country->deleted_by = Auth::id();
        $country->save();
        $country->delete();

        return redirect()->route('admin.countries.index')->with('success','Country moved to trash.');
    }

    public function trash(Request $request)
    {
        $query = Country::onlyTrashed()
            ->with(['deletedBy']);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function($q) use ($search){
                $q->where('name','like',"%{$search}%")
                    ->orWhere('region','like',"%{$search}%")
                    ->orWhere('subregion','like',"%{$search}%")
                    ->orWhere('iso2','like',"%{$search}%")
                    ->orWhere('iso3','like',"%{$search}%");
            });
        }

        $countries = $query
            ->orderByDesc('deleted_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.reference.countries.trash',compact('countries'));
    }

    public function restore(Country $country)
    {
        $country->restore();

        $country->update([
            'deleted_by' => null,
        ]);

        return redirect()->route('admin.countries.trash')->with('success','Country restored successfully.');
    }

    public function forceDelete(Country $country)
    {
        $country->forceDelete();

        return redirect()->route('admin.countries.trash')->with('success','Country permanently deleted.');
    }
}