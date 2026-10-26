<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Example Technology Ltd.',
                'legal_name' => 'Example Technology Limited',
                'tagline' => 'Building digital solutions',
                'short_desc' => 'An example technology company for testing the CareerVault directory.',
                'description' => 'Replace this sample description with researched company information.',
                'website' => 'https://example.com',
                'career_page' => 'https://example.com/careers',
                'emails' => ['careers@example.com', 'info@example.com'],
                'phones' => [],
                'founded_by' => null,
                'founded_month' => null,
                'founded_year' => null,
                'employee_count' => null,
                'status' => 'active',
                'operating_status' => 'unknown',
                'is_verified' => false,
                'is_featured' => false,
                'last_verified_at' => null,
                'notes' => 'Sample record. Verify all information before using it as real company data.',
            ],
            [
                'name' => 'Example Software Solutions',
                'legal_name' => null,
                'tagline' => 'Software for modern businesses',
                'short_desc' => 'A second sample company for testing company listings and filters.',
                'description' => 'This is placeholder data for development and UI testing.',
                'website' => null,
                'career_page' => null,
                'emails' => [],
                'phones' => [],
                'founded_by' => null,
                'founded_month' => null,
                'founded_year' => null,
                'employee_count' => null,
                'status' => 'draft',
                'operating_status' => 'unknown',
                'is_verified' => false,
                'is_featured' => false,
                'last_verified_at' => null,
                'notes' => 'Sample record. Replace or remove before production.',
            ],
        ];

        foreach ($companies as $data) {
            $slug = Str::slug($data['name']);

            Company::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'slug' => $slug,
                    'created_by' => null,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'deleted_at' => null,
                ])
            );
        }
    }
}