<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('countries')->insert([
            'name' => 'Japan',
            'code' => 'jp',
            'city' => 'Tokyo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'United States',
            'code' => 'us',
            'city' => 'New York',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'United Kingdom',
            'code' => 'gb',
            'city' => 'London',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'France',
            'code' => 'fr',
            'city' => 'Paris',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'Germany',
            'code' => 'de',
            'city' => 'Berlin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'Philippines',
            'code' => 'ph',
            'city' => 'Manila',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'Australia',
            'code' => 'au',
            'city' => 'Sydney',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'Canada',
            'code' => 'ca',
            'city' => 'Toronto',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'South Korea',
            'code' => 'kr',
            'city' => 'Seoul',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('countries')->insert([
            'name' => 'Thailand',
            'code' => 'th',
            'city' => 'Bangkok',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
