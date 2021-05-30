<?php

use App\CategoriesProyects;
use Illuminate\Database\Seeder;

class CategoriesProyectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CategoriesProyects::class, 8)->create();
    }
}
