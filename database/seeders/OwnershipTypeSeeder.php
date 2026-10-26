<?php

namespace Database\Seeders;

use App\Models\OwnershipType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OwnershipTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['Privately Owned', 'Owned by private individuals, families, or private investors.'],
            ['Publicly Traded', 'Ownership shares are traded on a public stock exchange.'],
            ['Family Owned', 'Owned or controlled by one or more members of a family.'],
            ['Founder Owned', 'Primarily owned or controlled by the company founders.'],
            ['Employee Owned', 'Employees hold a significant ownership stake in the organization.'],
            ['Government Owned', 'Owned wholly or partly by a government entity.'],
            ['State Owned Enterprise', 'A commercial enterprise owned or controlled by the state.'],
            ['Nonprofit Owned', 'Operates under a nonprofit structure without private equity ownership.'],
            ['Cooperative Owned', 'Owned and governed by its members.'],
            ['Investor Backed', 'Has received equity investment from external investors.'],
            ['Venture Capital Backed', 'Has received investment from venture capital funds.'],
            ['Private Equity Owned', 'Owned or controlled by a private equity investor or fund.'],
            ['Joint Venture', 'Owned by two or more parties collaborating in a business venture.'],
            ['Subsidiary', 'Controlled by a parent company or organization.'],
            ['Partnership Owned', 'Owned by two or more partners under a partnership arrangement.'],
        ];

        foreach ($types as $index => [$name, $description]) {
            OwnershipType::withTrashed()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'deleted_at' => null,
                ]
            );
        }
    }
}