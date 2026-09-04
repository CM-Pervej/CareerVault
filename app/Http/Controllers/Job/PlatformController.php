<?php

namespace App\Http\Controllers\job;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlatformController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $totalPlatform = Platform::count();

        $platforms = Platform::query()
            ->when($search !== '',function($query)use($search){
                $query->where(function($query)use($search){
                    $query->where('name','like',"%{$search}%")
                        ->orWhere('official_name','like',"%{$search}%")
                        ->orWhere('slug','like',"%{$search}%")
                        ->orWhere('job_type','like',"%{$search}%")
                        ->orWhere('business_model','like',"%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        if($request->ajax()){
            return response()->json([
                'html' => view('job.platform.index',compact('platforms', 'totalPlatform'))->render()
            ]);
        }

        return view('job.platform.index',compact('platforms', 'totalPlatform'));
    }

    public function create()
    {
        return view('job.platform.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'account_required' => $request->boolean('account_required'),
            'is_active' => $request->boolean('is_active'),
            'is_bangladesh_focused' => $request->boolean('is_bangladesh_focused'),
        ]);

        $validated = $request->validate($this->validationRules());

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = Str::random(40) . '.' . $logo->getClientOriginalExtension();

            $validated['logo'] = $logo->storeAs(
                'platforms/logos',
                $logoName,
                'public'
            );
        }

        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = Str::random(40) . '.' . $coverImage->getClientOriginalExtension();

            $validated['cover_image'] = $coverImage->storeAs(
                'platforms/covers',
                $coverImageName,
                'public'
            );
        }

        $platform = Platform::create($validated);

        return redirect()
            ->route('platforms.show', $platform)
            ->with('success', 'Platform created successfully.');
    }

    public function show(Platform $platform)
    {
        $platforms=Platform::query()
            ->select([
                'id',
                'name',
                'slug',
                'logo',
                'icon',            // needed for the icon fallback when there's no logo
                'color',
                'job_type',
                'business_model',  // needed for the Bangladesh-focused list
                'is_active',
                'is_bangladesh_focused',
            ])
            ->orderBy('name')
            ->get();

        return view('job.platform.show',compact(
            'platform',
            'platforms'
        ));
    }

    public function edit(Platform $platform)
    {
        return view('job.platform.edit',compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $request->merge([
            'account_required' => $request->boolean('account_required'),
            'is_active' => $request->boolean('is_active'),
            'is_bangladesh_focused' => $request->boolean('is_bangladesh_focused'),
        ]);

        $validated = $request->validate($this->validationRules($platform));

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            $oldLogo = $platform->logo;
            $logo = $request->file('logo');
            $logoName = Str::random(40) . '.' . $logo->getClientOriginalExtension();

            $validated['logo'] = $logo->storeAs(
                'platforms/logos',
                $logoName,
                'public'
            );

            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
        }

        if ($request->hasFile('cover_image')) {
            $oldCoverImage = $platform->cover_image;
            $coverImage = $request->file('cover_image');
            $coverImageName = Str::random(40) . '.' . $coverImage->getClientOriginalExtension();

            $validated['cover_image'] = $coverImage->storeAs(
                'platforms/covers',
                $coverImageName,
                'public'
            );

            if ($oldCoverImage && Storage::disk('public')->exists($oldCoverImage)) {
                Storage::disk('public')->delete($oldCoverImage);
            }
        }

        $platform->update($validated);

        return redirect()
            ->route('platforms.show', $platform)
            ->with('success', 'Platform updated successfully.');
    }

    public function destroy(Platform $platform)
    {
        $platform->delete();

        return redirect()
            ->route('platforms.index')
            ->with('success','Platform moved to trash successfully.');
    }

    private function validationRules(?Platform $platform = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('platforms','name')->ignore($platform?->id)
            ],
            'official_name' => ['nullable','string','max:255'],
            'short_desc' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'icon' => ['nullable','string','max:255'],
            'color' => ['nullable','regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo' => ['nullable','file','extensions:jpg,jpeg,png,webp,svg','max:2048'],
            'cover_image' => ['nullable','file','extensions:jpg,jpeg,png,webp,svg','max:5120'],
            'base_url' => ['nullable','url','max:255'],
            'job_url' => ['nullable','url','max:255'],
            'job_type' => ['required',Rule::in(['Onsite','Remote','Both'])],
            'business_model' => ['required',Rule::in(['Free','Freemium','Paid'])],
            'account_required' => ['boolean'],
            'is_active' => ['boolean'],
            'is_bangladesh_focused' => ['boolean'],
            'sort_order' => ['required','integer','min:0'],
            'founded_month' => ['nullable','integer','between:1,12'],
            'founded_year' => ['nullable','integer','min:1800','max:' . now()->year],
            'last_verified_at' => ['nullable','date'],
        ];
    }
}