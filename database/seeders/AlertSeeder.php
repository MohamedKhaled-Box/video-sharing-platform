<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alert1 = Alert::create([
            'user_id' => User::where('name', 'hsoub-academy')->first()->id,
            'alert' => '0',
        ]);

        $alert2 = Alert::create([
            'user_id' => User::where('name', 'hsoub')->first()->id,
            'alert' => '0',
        ]);

        $alert3 = Alert::create([
            'user_id' => User::where('name', 'Mostaql')->first()->id,
            'alert' => '0',
        ]);

        $alert4 = Alert::create([
            'user_id' => User::where('name', 'Baeed')->first()->id,
            'alert' => '0',
        ]);

        $alert5 = Alert::create([
            'user_id' => User::where('name', 'Khamsat')->first()->id,
            'alert' => '0',
        ]);

        $alert6 = Alert::create([
            'user_id' => User::where('name', 'Ana')->first()->id,
            'alert' => '0',
        ]);
    }
}
