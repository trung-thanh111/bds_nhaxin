<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AgentSeeder::class,
            PropertySeeder::class,
            PropertyFacilitySeeder::class,
            FloorplanSeeder::class,
            FloorplanRoomSeeder::class,
            GallerySeeder::class,
            LocationHighlightSeeder::class,
            ContactRequestSeeder::class,
            ProjectCatalogueSeeder::class,
        ]);
    }
}
