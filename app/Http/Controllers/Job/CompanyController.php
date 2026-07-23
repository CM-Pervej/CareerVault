<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderBy('name', 'asc')->paginate(15);
        return view('job.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job.company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'website' => 'nullable|url|max:255|unique:companies,website',
            'career_page' => 'nullable|url|max:255|unique:companies,career_page',
            'emails'      => 'nullable|array',
            'emails.*.emails_type' => 'required_with:emails|string|max:255',
            'emails.*.email' => 'required_with:emails|email|max:255',
            'phones'        => 'nullable|array',
            'phones.*.phone_type' => 'required_with:phones|string|max:255',
            'phones.*.phone' => 'required_with:phones|string|max:50',
            'industry'      => 'nullable|array',
            'address'       => 'nullable|array',
            'address.*.address_type' => 'required_with:address|string|max:255',
            'address.*.address' => 'required_with:address|string|max:255',
            'country'       => 'nullable|array',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links|string|max:100',
            'social_links.*.url' => 'required_with:social_links|url|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('job.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('job.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'website' => 'nullable|url|max:255|unique:companies,website,' . $company->id,
            'career_page' => 'nullable|url|max:255|unique:companies,career_page,' . $company->id,
            'emails'      => 'nullable|array',
            'emails.*.email_type' => 'required_with:emails|string|max:255',
            'emails.*.email' => 'required_with:emails|email|max:255',
            'phones'        => 'nullable|array',
            'phones.*.phone_type' => 'required_with:phones|string|max:255',
            'phones.*.phone' => 'required_with:phones|string|max:50',
            'industry'      => 'nullable|array',
            'address'       => 'nullable|array',
            'address.*.address_type' => 'required_with:address|string|max:255',
            'address.*.address' => 'required_with:address|string|max:255',
            'country'       => 'nullable|array',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links|string|max:100',
            'social_links.*.url' => 'required_with:social_links|url|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $company->update($validated);

        return redirect()->route('companies.show', $company)->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
