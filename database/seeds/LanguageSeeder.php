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
            'description' => 'Español',
            'abbreviation' => 'es'
        ]);
        Language::create([
            'description' => 'English',
            'abbreviation' => 'en'
        ]);
    }
}
