<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Itinerary;

class Groupseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = Group::factory()->count(20)->create();
        Itinerary::factory()->create([
            'group_id' => $group->first()->id,
        ]);
    }
}
