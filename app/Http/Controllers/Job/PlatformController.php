<?php

namespace App\Http\Controllers\job;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $totalPlatform = Platform::count();

        $platforms = Platform::query()
            ->when($search !== '',function($query)use($search){
                $query->where(function($query)use($search){
                    $query->where('name','like',"%{$search}%")
                        ->orWhere('slug','like',"%{$search}%")
                        ->orWhere('job_type','like',"%{$search}%");
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:platforms,name',],
            'icon' => ['nullable', 'string', 'max:255',],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/',],
            'base_url' => ['nullable', 'url', 'max:255',],
            'job_url'=>['nullable', 'url', 'max:255',],
            'job_type'=>['nullable', 'string', 'max:100',],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Platform::create($validated);

        return redirect()
            ->route('platforms.index')
            ->with('success','Platform created successfully.');
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
    public function edit(Platform $platform)
    {
        $platforms = Platform::orderBy('name')->paginate(25);
        $totalPlatform = Platform::count();

        return view('job.platform.index', compact('platforms','totalPlatform','platform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('platforms','name')->ignore($platform->id),],
            'icon' => ['nullable', 'string', 'max:255',],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/',],
            'base_url' => ['nullable', 'url', 'max:255',],
            'job_url'=>['nullable', 'url', 'max:255',],
            'job_type'=>['nullable', 'string', 'max:100',],
        ]);

        if($validated['name'] !== $platform->name){
            $validated['slug'] = Str::slug($validated['name']);
        }

        $platform->update($validated);

        return redirect()
            ->route('platforms.index')
            ->with('success','Platform updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $platform)
    {
        $platform->delete();

        return redirect()
            ->route('platforms.index')
            ->with('success','Platform deleted successfully.');
            }
}