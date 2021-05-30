<?php

use App\categories_languages_descriptions;
use Illuminate\Database\Seeder;

class CategoriesLanguagesDescriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(categories_languages_descriptions::class, 5)->create();
    }
}
