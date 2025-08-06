<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Itinerary;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([
        //     UserSeeder::class,
        //     GroupSeeder::class,
        //     ItinerarySeeder::class,
        //     BillSeeder::class,
        // ]);

        $this->call([
            GroupMemberSeeder::class,
        ]);

        User::factory(10)->create();

        $this->call([
            CountrySeeder::class,
        ]);

    }
}
