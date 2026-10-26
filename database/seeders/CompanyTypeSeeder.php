<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $companyTypes = [
            ['id' => 1, 'name' => 'Startup', 'slug' => 'startup', 'description' => 'Newly established business focused on growth', 'is_active' => true, 'sort_order' => 0],
            ['id' => 2, 'name' => 'Small Business', 'slug' => 'small-business', 'description' => 'Independently operated small business', 'is_active' => true, 'sort_order' => 1],
            ['id' => 3, 'name' => 'Corporation', 'slug' => 'corporation', 'description' => 'Large or formally incorporated business', 'is_active' => true, 'sort_order' => 2],
            ['id' => 4, 'name' => 'Software Company', 'slug' => 'software-company', 'description' => 'Develops and sells software products', 'is_active' => true, 'sort_order' => 3],
            ['id' => 5, 'name' => 'IT Services Company', 'slug' => 'it-services-company', 'description' => 'Provides IT and technology services', 'is_active' => true, 'sort_order' => 4],
            ['id' => 6, 'name' => 'Consulting Firm', 'slug' => 'consulting-firm', 'description' => 'Provides professional consulting services', 'is_active' => true, 'sort_order' => 5],
            ['id' => 7, 'name' => 'Digital Agency', 'slug' => 'digital-agency', 'description' => 'Provides web, digital marketing, or creative services', 'is_active' => true, 'sort_order' => 6],
            ['id' => 8, 'name' => 'Recruitment Agency', 'slug' => 'recruitment-agency', 'description' => 'Recruits candidates for employers', 'is_active' => true, 'sort_order' => 7],
            ['id' => 9, 'name' => 'Staffing Company', 'slug' => 'staffing-company', 'description' => 'Supplies temporary or permanent workforce', 'is_active' => true, 'sort_order' => 8],
            ['id' => 10, 'name' => 'Freelance Business', 'slug' => 'freelance-business', 'description' => 'Individual freelancer or freelance-led business', 'is_active' => true, 'sort_order' => 9],
            ['id' => 11, 'name' => 'Nonprofit Organization', 'slug' => 'nonprofit-organization', 'description' => 'Operates primarily for a nonprofit mission', 'is_active' => true, 'sort_order' => 10],
            ['id' => 12, 'name' => 'Government Organization', 'slug' => 'government-organization', 'description' => 'Government department or agency', 'is_active' => true, 'sort_order' => 11],
            ['id' => 13, 'name' => 'Educational Institution', 'slug' => 'educational-institution', 'description' => 'University, college, or training institution', 'is_active' => true, 'sort_order' => 12],
            ['id' => 14, 'name' => 'Financial Institution', 'slug' => 'financial-institution', 'description' => 'Bank or other financial services organization', 'is_active' => true, 'sort_order' => 13],
            ['id' => 15, 'name' => 'E-commerce Company', 'slug' => 'e-commerce-company', 'description' => 'Sells products or services online', 'is_active' => true, 'sort_order' => 14],
            ['id' => 16, 'name' => 'Marketplace Platform', 'slug' => 'marketplace-platform', 'description' => 'Connects buyers and sellers', 'is_active' => true, 'sort_order' => 15],
            ['id' => 17, 'name' => 'Healthcare Organization', 'slug' => 'healthcare-organization', 'description' => 'Provides healthcare or medical services', 'is_active' => true, 'sort_order' => 16],
            ['id' => 18, 'name' => 'Manufacturing Company', 'slug' => 'manufacturing-company', 'description' => 'Produces physical goods', 'is_active' => true, 'sort_order' => 17],
            ['id' => 19, 'name' => 'Telecommunications Company', 'slug' => 'telecommunications-company', 'description' => 'Provides telecommunications services', 'is_active' => true, 'sort_order' => 18],
            ['id' => 20, 'name' => 'Holding Company', 'slug' => 'holding-company', 'description' => 'Owns shares in other companies', 'is_active' => true, 'sort_order' => 19],
            ['id' => 21, 'name' => 'Franchise Business', 'slug' => 'franchise-business', 'description' => 'Operates through a franchise model', 'is_active' => true, 'sort_order' => 20],
        ];

        foreach ($companyTypes as $companyType) {
            DB::table('company_types')->updateOrInsert(
                ['slug' => $companyType['slug']],
                [
                    'id' => $companyType['id'],
                    'name' => $companyType['name'],
                    'description' => $companyType['description'],
                    'is_active' => $companyType['is_active'],
                    'sort_order' => $companyType['sort_order'],
                    'created_by' => null,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'deleted_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}