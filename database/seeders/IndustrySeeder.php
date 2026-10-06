<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            'Software & Technology' => [
                'Software Development',
                'Web Development',
                'Mobile App Development',
                'SaaS',
                'Enterprise Software',
                'Cloud Computing',
                'Artificial Intelligence',
                'Cybersecurity',
                'IT Services',
                'Developer Tools',
            ],

            'Financial Services' => [
                'Banking',
                'FinTech',
                'Insurance',
                'Investment & Asset Management',
                'Payments',
                'Microfinance',
            ],

            'Commerce' => [
                'E-commerce',
                'Retail',
                'Wholesale',
                'Marketplace',
                'Consumer Services',
            ],

            'Healthcare' => [
                'Hospitals & Clinics',
                'Pharmaceuticals',
                'Medical Devices',
                'Biotechnology',
                'HealthTech',
            ],

            'Education' => [
                'Higher Education',
                'EdTech',
                'Training & Certification',
                'Educational Services',
            ],

            'Media & Entertainment' => [
                'Digital Media',
                'Publishing',
                'Film & Television',
                'Music',
                'Gaming',
                'Sports',
            ],

            'Professional Services' => [
                'Consulting',
                'Accounting',
                'Legal Services',
                'Human Resources',
                'Marketing & Advertising',
                'Recruitment',
            ],

            'Telecommunications' => [
                'Internet Service Providers',
                'Mobile Network Operators',
                'Telecom Equipment',
                'Communication Services',
            ],

            'Manufacturing' => [
                'Industrial Manufacturing',
                'Electronics Manufacturing',
                'Textile & Apparel',
                'Chemical Manufacturing',
                'Consumer Goods',
            ],

            'Construction & Engineering' => [
                'Construction',
                'Civil Engineering',
                'Architecture',
                'Building Materials',
                'Infrastructure',
            ],

            'Real Estate' => [
                'Property Development',
                'Property Management',
                'Real Estate Brokerage',
                'Commercial Real Estate',
            ],

            'Transportation & Logistics' => [
                'Logistics',
                'Freight & Shipping',
                'Courier & Delivery',
                'Public Transportation',
                'Transportation Technology',
            ],

            'Automotive' => [
                'Automobile Manufacturing',
                'Automotive Parts',
                'Vehicle Services',
                'Electric Vehicles',
            ],

            'Energy & Utilities' => [
                'Oil & Gas',
                'Renewable Energy',
                'Electricity',
                'Water & Utilities',
                'Energy Technology',
            ],

            'Agriculture' => [
                'Agriculture & Farming',
                'AgriTech',
                'Fisheries',
                'Livestock',
                'Agricultural Products',
            ],

            'Food & Beverage' => [
                'Food Production',
                'Food Processing',
                'Restaurants & Food Services',
                'Beverages',
                'Food Technology',
            ],

            'Hospitality & Tourism' => [
                'Hotels & Resorts',
                'Travel Services',
                'Tourism',
                'Hospitality Services',
            ],

            'Government & Public Sector' => [
                'Government Services',
                'Public Administration',
                'Public Infrastructure',
                'Government Technology',
            ],

            'Nonprofit & Social Impact' => [
                'Nonprofit Organizations',
                'NGOs',
                'Social Enterprise',
                'Charity & Philanthropy',
            ],
        ];

        $sortOrder = 1;

        foreach ($industries as $parentName => $children) {
            $parent = Industry::updateOrCreate(
                ['slug' => Str::slug($parentName)],
                [
                    'name' => $parentName,
                    'description' => null,
                    'parent_id' => null,
                    'icon' => null,
                    'color' => null,
                    'is_active' => true,
                    'sort_order' => $sortOrder++,
                    'created_by' => null,
                    'updated_by' => null,
                    'deleted_by' => null,
                ]
            );

            $childSortOrder = 1;

            foreach ($children as $childName) {
                Industry::updateOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'name' => $childName,
                        'description' => null,
                        'parent_id' => $parent->id,
                        'icon' => null,
                        'color' => null,
                        'is_active' => true,
                        'sort_order' => $childSortOrder++,
                        'created_by' => null,
                        'updated_by' => null,
                        'deleted_by' => null,
                    ]
                );
            }
        }
    }
}