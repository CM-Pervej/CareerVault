<?php

namespace App\Http\Controllers\job;

use App\Http\Controllers\Controller;
use App\Models\PlatformPage;
use Illuminate\Http\Request;

class PlatformPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $totalPlatformPage = PlatformPage::count();

        $platformPages = PlatformPage::query()
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
                'html' => view('job.platform.page',compact('platformPages', 'totalPlatformPage'))->render()
            ]);
        }

        return view('job.platform.page',compact('platformPages', 'totalPlatformPage'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PlatformPage $platformPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlatformPage $platformPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlatformPage $platformPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlatformPage $platformPage)
    {
        //
    }
}
