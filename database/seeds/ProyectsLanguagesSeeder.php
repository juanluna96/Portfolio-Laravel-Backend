<?php

use App\proyects_languages;
use Illuminate\Database\Seeder;

class ProyectsLanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(proyects_languages::class, 5)->create();
    }
}
