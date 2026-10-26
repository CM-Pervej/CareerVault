<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $bangladesh = Country::where('iso2', 'BD')->firstOrFail();

        $cities = [
            // Dhaka Division
            ['state' => 'Dhaka', 'name' => 'Dhaka', 'capital' => true],
            ['state' => 'Dhaka', 'name' => 'Gazipur'],
            ['state' => 'Dhaka', 'name' => 'Narayanganj'],
            ['state' => 'Dhaka', 'name' => 'Narsingdi'],
            ['state' => 'Dhaka', 'name' => 'Tangail'],
            ['state' => 'Dhaka', 'name' => 'Faridpur'],
            ['state' => 'Dhaka', 'name' => 'Gopalganj'],
            ['state' => 'Dhaka', 'name' => 'Madaripur'],
            ['state' => 'Dhaka', 'name' => 'Manikganj'],
            ['state' => 'Dhaka', 'name' => 'Munshiganj'],
            ['state' => 'Dhaka', 'name' => 'Rajbari'],
            ['state' => 'Dhaka', 'name' => 'Shariatpur'],
            ['state' => 'Dhaka', 'name' => 'Kishoreganj'],

            // Chattogram Division
            ['state' => 'Chattogram', 'name' => 'Chattogram'],
            ['state' => 'Chattogram', 'name' => "Cox's Bazar"],
            ['state' => 'Chattogram', 'name' => 'Cumilla'],
            ['state' => 'Chattogram', 'name' => 'Feni'],
            ['state' => 'Chattogram', 'name' => 'Noakhali'],
            ['state' => 'Chattogram', 'name' => 'Lakshmipur'],
            ['state' => 'Chattogram', 'name' => 'Chandpur'],
            ['state' => 'Chattogram', 'name' => 'Brahmanbaria'],
            ['state' => 'Chattogram', 'name' => 'Rangamati'],
            ['state' => 'Chattogram', 'name' => 'Khagrachhari'],
            ['state' => 'Chattogram', 'name' => 'Bandarban'],

            // Khulna Division
            ['state' => 'Khulna', 'name' => 'Khulna'],
            ['state' => 'Khulna', 'name' => 'Jashore'],
            ['state' => 'Khulna', 'name' => 'Bagerhat'],
            ['state' => 'Khulna', 'name' => 'Chuadanga'],
            ['state' => 'Khulna', 'name' => 'Jhenaidah'],
            ['state' => 'Khulna', 'name' => 'Kushtia'],
            ['state' => 'Khulna', 'name' => 'Magura'],
            ['state' => 'Khulna', 'name' => 'Meherpur'],
            ['state' => 'Khulna', 'name' => 'Narail'],
            ['state' => 'Khulna', 'name' => 'Satkhira'],

            // Rajshahi Division
            ['state' => 'Rajshahi', 'name' => 'Rajshahi'],
            ['state' => 'Rajshahi', 'name' => 'Bogura'],
            ['state' => 'Rajshahi', 'name' => 'Naogaon'],
            ['state' => 'Rajshahi', 'name' => 'Natore'],
            ['state' => 'Rajshahi', 'name' => 'Chapainawabganj'],
            ['state' => 'Rajshahi', 'name' => 'Pabna'],
            ['state' => 'Rajshahi', 'name' => 'Sirajganj'],
            ['state' => 'Rajshahi', 'name' => 'Joypurhat'],

            // Rangpur Division
            ['state' => 'Rangpur', 'name' => 'Rangpur'],
            ['state' => 'Rangpur', 'name' => 'Dinajpur'],
            ['state' => 'Rangpur', 'name' => 'Gaibandha'],
            ['state' => 'Rangpur', 'name' => 'Kurigram'],
            ['state' => 'Rangpur', 'name' => 'Lalmonirhat'],
            ['state' => 'Rangpur', 'name' => 'Nilphamari'],
            ['state' => 'Rangpur', 'name' => 'Panchagarh'],
            ['state' => 'Rangpur', 'name' => 'Thakurgaon'],

            // Sylhet Division
            ['state' => 'Sylhet', 'name' => 'Sylhet'],
            ['state' => 'Sylhet', 'name' => 'Habiganj'],
            ['state' => 'Sylhet', 'name' => 'Moulvibazar'],
            ['state' => 'Sylhet', 'name' => 'Sunamganj'],

            // Mymensingh Division
            ['state' => 'Mymensingh', 'name' => 'Mymensingh'],
            ['state' => 'Mymensingh', 'name' => 'Jamalpur'],
            ['state' => 'Mymensingh', 'name' => 'Netrokona'],
            ['state' => 'Mymensingh', 'name' => 'Sherpur'],

            // Barishal Division
            ['state' => 'Barishal', 'name' => 'Barishal'],
            ['state' => 'Barishal', 'name' => 'Bhola'],
            ['state' => 'Barishal', 'name' => 'Barguna'],
            ['state' => 'Barishal', 'name' => 'Jhalokathi'],
            ['state' => 'Barishal', 'name' => 'Patuakhali'],
            ['state' => 'Barishal', 'name' => 'Pirojpur'],
        ];

        foreach ($cities as $index => $data) {
            $state = State::where('country_id', $bangladesh->id)
                ->where('name', $data['state'])
                ->first();

            if (!$state) {
                $this->command->warn(
                    "State not found: {$data['state']} — {$data['name']} skipped."
                );
                continue;
            }

            $slug = Str::slug($data['name']);

            City::updateOrCreate(
                [
                    'country_id' => $bangladesh->id,
                    'slug' => $slug,
                ],
                [
                    'state_id' => $state->id,
                    'name' => $data['name'],
                    'code' => null,
                    'is_capital' => $data['capital'] ?? false,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $this->command->info(
            'Bangladesh cities seeded successfully.'
        );
    }
}