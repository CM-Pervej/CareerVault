<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StateController extends Controller
{
    public function index(Request $request, Country $country)
    {
        $query = $country->states()
            ->withCount('cities');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
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

        $types = $country->states()
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('admin.reference.states.index', compact(
            'country',
            'states',
            'types'
        ));
    }

    public function create(Country $country)
    {
        $types = $country->states()
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('admin.reference.states.create', compact(
            'country',
            'types'
        ));
    }

    public function store(Request $request, Country $country)
    {
        $validated = $this->validateState($request, $country);

        $validated['country_id'] = $country->id;
        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name'],
            $country
        );
        $validated['created_by'] = Auth::id();

        State::create($validated);

        return redirect()
            ->route('admin.countries.states.index', $country)
            ->with('success', 'State / division created successfully.');
    }

    public function show(Country $country, State $state)
    {
        $this->ensureCountryState($country, $state);

        $state->loadCount('cities');

        return view('admin.reference.states.show', compact(
            'country',
            'state'
        ));
    }

    public function edit(Country $country, State $state)
    {
        $this->ensureCountryState($country, $state);

        $types = $country->states()
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('admin.reference.states.edit', compact(
            'country',
            'state',
            'types'
        ));
    }

    public function update(
        Request $request,
        Country $country,
        State $state
    ) {
        $this->ensureCountryState($country, $state);

        $validated = $this->validateState(
            $request,
            $country,
            $state
        );

        if ($validated['name'] !== $state->name) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $country,
                $state->id
            );
        }

        $validated['updated_by'] = Auth::id();

        $state->update($validated);

        return redirect()
            ->route('admin.countries.states.index', $country)
            ->with('success', 'State / division updated successfully.');
    }

    public function destroy(Country $country, State $state)
    {
        $this->ensureCountryState($country, $state);

        if ($state->cities()->exists()) {
            return back()->with(
                'error',
                'This state / division has cities. Remove or reassign them before deleting it.'
            );
        }

        $state->deleted_by = Auth::id();
        $state->save();
        $state->delete();

        return redirect()
            ->route('admin.countries.states.index', $country)
            ->with('success', 'State / division moved to trash.');
    }

    public function trash(Country $country)
    {
        $states = $country->states()
            ->onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view('admin.reference.states.trash', compact(
            'country',
            'states'
        ));
    }

    public function restore(Country $country, State $state)
    {
        $this->ensureCountryState($country, $state, true);

        $state->restore();

        $state->deleted_by = null;
        $state->save();

        return redirect()
            ->route('admin.countries.states.trash', $country)
            ->with('success', 'State / division restored successfully.');
    }

    public function forceDelete(Country $country, State $state)
    {
        $this->ensureCountryState($country, $state, true);

        if ($state->cities()->withTrashed()->exists()) {
            return back()->with(
                'error',
                'This state / division still has cities and cannot be permanently deleted.'
            );
        }

        $state->forceDelete();

        return redirect()
            ->route('admin.countries.states.trash', $country)
            ->with('success', 'State / division permanently deleted.');
    }

    private function validateState(
        Request $request,
        Country $country,
        ?State $state = null
    ): array {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('states', 'name')
                    ->where(fn ($query) => $query->where('country_id', $country->id))
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

    private function ensureCountryState(
        Country $country,
        State $state,
        bool $withTrashed = false
    ): void {
        $query = $withTrashed
            ? $country->states()->withTrashed()
            : $country->states();

        abort_unless(
            $query->whereKey($state->id)->exists(),
            404
        );
    }
}