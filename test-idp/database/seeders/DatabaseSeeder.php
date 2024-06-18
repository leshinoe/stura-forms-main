<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'display' => 'Admin',
            'btu_id' => '756edf498829da38dd9c5bb48b784b12',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@b-tu.de',
            'identifiers' => [
                'urn:schac:personalUniqueCode:de:b-tu.de:BTU_ID:756edf498829da38dd9c5bb48b784b12',
            ],
            'scoped_affiliations' => [
                'student@b-tu.de',
                'member@b-tu.de',
            ],
            'entitlements' => [
                'urn:mace:ride-ticketing.de:entitlement:dticket:timeframe:20990401-20990930',
            ],
        ]);

        User::create([
            'display' => 'Has Semesterticket',
            'btu_id' => '4341b41dbeb54bc9e8b601ef2f2a720c',
            'firstname' => 'Has Semesterticket',
            'lastname' => 'Student',
            'email' => 'HasSemesterticket.Student@b-tu.de',
            'identifiers' => [
                'urn:schac:personalUniqueCode:de:b-tu.de:BTU_ID:4341b41dbeb54bc9e8b601ef2f2a720c',
            ],
            'scoped_affiliations' => [
                'student@b-tu.de',
                'member@b-tu.de',
            ],
            'entitlements' => [
                'urn:mace:ride-ticketing.de:entitlement:dticket:timeframe:20990401-20990930',
            ],
        ]);

        User::create([
            'display' => 'No Semesterticket',
            'btu_id' => 'e05ba74ab66bd62646d2dde351273a1b',
            'firstname' => 'No Semesterticket',
            'lastname' => 'Student',
            'email' => 'NoSemesterticket.Student@b-tu.de',
            'identifiers' => [
                'urn:schac:personalUniqueCode:de:b-tu.de:BTU_ID:e05ba74ab66bd62646d2dde351273a1b',
            ],
            'scoped_affiliations' => [
                'student@b-tu.de',
                'member@b-tu.de',
            ],
            'entitlements' => [],
        ]);
    }
}
