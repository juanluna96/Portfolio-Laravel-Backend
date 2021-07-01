<?php

use App\Proyect;
use Illuminate\Database\Seeder;

class ProyectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Proyect::class, 2)->create();
    }
}
