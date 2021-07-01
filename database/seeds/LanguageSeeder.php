<?php

use App\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create([
            'name' => 'Español',
            'description' => 'spanish',
            'abbreviation' => 'es',
            'flag' => 'es'
        ]);
        Language::create([
            'name' => 'Ingles',
            'description' => 'english',
            'abbreviation' => 'en',
            'flag' => 'gb'
        ]);
    }
}
