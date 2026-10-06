<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Bangladesh' => [
                ['name' => 'Barishal', 'code' => 'BD-A', 'type' => 'Division'],
                ['name' => 'Chattogram', 'code' => 'BD-B', 'type' => 'Division'],
                ['name' => 'Dhaka', 'code' => 'BD-C', 'type' => 'Division'],
                ['name' => 'Khulna', 'code' => 'BD-D', 'type' => 'Division'],
                ['name' => 'Mymensingh', 'code' => 'BD-H', 'type' => 'Division'],
                ['name' => 'Rajshahi', 'code' => 'BD-E', 'type' => 'Division'],
                ['name' => 'Rangpur', 'code' => 'BD-F', 'type' => 'Division'],
                ['name' => 'Sylhet', 'code' => 'BD-G', 'type' => 'Division'],
            ],

            'United States' => [
                ['name' => 'California', 'code' => 'US-CA', 'type' => 'State'],
                ['name' => 'New York', 'code' => 'US-NY', 'type' => 'State'],
                ['name' => 'Texas', 'code' => 'US-TX', 'type' => 'State'],
                ['name' => 'Florida', 'code' => 'US-FL', 'type' => 'State'],
                ['name' => 'Washington', 'code' => 'US-WA', 'type' => 'State'],
            ],

            'Canada' => [
                ['name' => 'Ontario', 'code' => 'CA-ON', 'type' => 'Province'],
                ['name' => 'Quebec', 'code' => 'CA-QC', 'type' => 'Province'],
                ['name' => 'British Columbia', 'code' => 'CA-BC', 'type' => 'Province'],
                ['name' => 'Alberta', 'code' => 'CA-AB', 'type' => 'Province'],
            ],

            'India' => [
                ['name' => 'West Bengal', 'code' => 'IN-WB', 'type' => 'State'],
                ['name' => 'Delhi', 'code' => 'IN-DL', 'type' => 'Union Territory'],
                ['name' => 'Maharashtra', 'code' => 'IN-MH', 'type' => 'State'],
                ['name' => 'Karnataka', 'code' => 'IN-KA', 'type' => 'State'],
                ['name' => 'Tamil Nadu', 'code' => 'IN-TN', 'type' => 'State'],
                ['name' => 'Kerala', 'code' => 'IN-KL', 'type' => 'State'],
            ],

            'Australia' => [
                ['name' => 'New South Wales', 'code' => 'AU-NSW', 'type' => 'State'],
                ['name' => 'Victoria', 'code' => 'AU-VIC', 'type' => 'State'],
                ['name' => 'Queensland', 'code' => 'AU-QLD', 'type' => 'State'],
                ['name' => 'Western Australia', 'code' => 'AU-WA', 'type' => 'State'],
                ['name' => 'South Australia', 'code' => 'AU-SA', 'type' => 'State'],
            ],

            'United Kingdom' => [
                ['name' => 'England', 'code' => 'GB-ENG', 'type' => 'Country'],
                ['name' => 'Scotland', 'code' => 'GB-SCT', 'type' => 'Country'],
                ['name' => 'Wales', 'code' => 'GB-WLS', 'type' => 'Country'],
                ['name' => 'Northern Ireland', 'code' => 'GB-NIR', 'type' => 'Country'],
            ],

            'United Arab Emirates' => [
                ['name' => 'Abu Dhabi', 'code' => 'AE-AZ', 'type' => 'Emirate'],
                ['name' => 'Dubai', 'code' => 'AE-DU', 'type' => 'Emirate'],
                ['name' => 'Sharjah', 'code' => 'AE-SH', 'type' => 'Emirate'],
                ['name' => 'Ajman', 'code' => 'AE-AJ', 'type' => 'Emirate'],
                ['name' => 'Ras Al Khaimah', 'code' => 'AE-RK', 'type' => 'Emirate'],
            ],

            'Pakistan' => [
                ['name' => 'Punjab', 'code' => 'PK-PB', 'type' => 'Province'],
                ['name' => 'Sindh', 'code' => 'PK-SD', 'type' => 'Province'],
                ['name' => 'Khyber Pakhtunkhwa', 'code' => 'PK-KP', 'type' => 'Province'],
                ['name' => 'Balochistan', 'code' => 'PK-BA', 'type' => 'Province'],
                ['name' => 'Islamabad Capital Territory', 'code' => 'PK-IS', 'type' => 'Federal Territory'],
            ],

            'Malaysia' => [
                ['name' => 'Selangor', 'code' => 'MY-10', 'type' => 'State'],
                ['name' => 'Johor', 'code' => 'MY-01', 'type' => 'State'],
                ['name' => 'Penang', 'code' => 'MY-07', 'type' => 'State'],
                ['name' => 'Sabah', 'code' => 'MY-12', 'type' => 'State'],
                ['name' => 'Sarawak', 'code' => 'MY-13', 'type' => 'State'],
                ['name' => 'Kuala Lumpur', 'code' => 'MY-14', 'type' => 'Federal Territory'],
            ],
        ];

        foreach ($data as $countryName => $states) {
            $country = Country::where('name', $countryName)->first();

            if (!$country) {
                $this->command?->warn("Country not found: {$countryName}");
                continue;
            }

            foreach ($states as $index => $state) {
                State::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'slug' => Str::slug($state['name']),
                    ],
                    [
                        'name' => $state['name'],
                        'code' => $state['code'],
                        'type' => $state['type'],
                        'is_active' => true,
                        'sort_order' => $index + 1,
                        'created_by' => null,
                        'updated_by' => null,
                        'deleted_by' => null,
                    ]
                );
            }
        }
    }
}