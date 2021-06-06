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
            'description' => 'spanish',
            'abbreviation' => 'es',
            'flag' => 'spa'
        ]);
        Language::create([
            'description' => 'english',
            'abbreviation' => 'en',
            'flag' => 'en'
        ]);
    }
}
