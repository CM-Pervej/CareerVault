<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $query = State::query()
            ->with('country')
            ->withCount('cities');

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

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status'));
        }

        $states = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $types = State::query()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        $trashedStatesCount = State::onlyTrashed()->count();

        return view('admin.reference.states.index', compact(
            'states',
            'countries',
            'types',
            'trashedStatesCount'
        ));
    }

    public function create()
    {
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.reference.states.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateState($request);

        $country = Country::findOrFail($validated['country_id']);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name'],
            $country
        );

        $validated['created_by'] = Auth::id();

        State::create($validated);

        return redirect()
            ->route('admin.states.index')
            ->with('success', 'State / Division created successfully.');
    }

    public function show(State $state)
    {
        $state->load('country');
        $state->loadCount('cities');

        return view('admin.reference.states.show', compact('state'));
    }

    public function edit(State $state)
    {
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.reference.states.edit', compact(
            'state',
            'countries'
        ));
    }

    public function update(Request $request, State $state)
    {
        $validated = $this->validateState($request, $state);

        $country = Country::findOrFail($validated['country_id']);

        if (
            $validated['name'] !== $state->name ||
            $validated['country_id'] != $state->country_id
        ) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $country,
                $state->id
            );
        }

        $validated['updated_by'] = Auth::id();

        $state->update($validated);

        return redirect()
            ->route('admin.states.index')
            ->with('success', 'State / Division updated successfully.');
    }

    public function destroy(State $state)
    {
        DB::transaction(function () use ($state) {
            $userId = Auth::id();

            $state->cities()
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => now(),
                    'deleted_by' => $userId,
                ]);

            $state->deleted_by = $userId;
            $state->save();
            $state->delete();
        });

        return redirect()
            ->route('admin.states.index')
            ->with('success', 'State / Division and its cities moved to trash.');
    }

    public function trash()
    {
        $states = State::onlyTrashed()
            ->with('country')
            ->withCount([
                'cities' => function ($query) {
                    $query->withTrashed();
                },
            ])
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view('admin.reference.states.trash', compact('states'));
    }

    public function restore(State $state)
    {
        if (State::where('country_id', $state->country_id)
            ->where('slug', $state->slug)
            ->where('id', '!=', $state->id)
            ->exists()) {
            return redirect()
                ->route('admin.states.trash')
                ->with('error', 'This State / Division cannot be restored because its slug is already in use.');
        }

        DB::transaction(function () use ($state) {
            $state->restore();
            $state->update(['deleted_by' => null]);

            $state->cities()
                ->onlyTrashed()
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null,
                ]);
        });

        return redirect()
            ->route('admin.states.trash')
            ->with('success', 'State / Division and its cities restored successfully.');
    }

    public function forceDelete(State $state)
    {
        DB::transaction(function () use ($state) {
            $state->cities()->withTrashed()->forceDelete();

            $state->forceDelete();
        });

        return redirect()
            ->route('admin.states.trash')
            ->with('success', 'State / Division and its cities permanently deleted.');
    }

    private function validateState(
        Request $request,
        ?State $state = null
    ): array {
        return $request->validate([
            'country_id' => [
                'required',
                'integer',
                'exists:countries,id',
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('states', 'name')
                    ->where(function ($query) use ($request) {
                        return $query
                            ->where('country_id', $request->input('country_id'))
                            ->whereNull('deleted_at');
                    })
                    ->ignore($state?->id),
            ],

            'code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'type' => [
                'nullable',
                'string',
                'max:50',
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
            State::withTrashed()
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
}