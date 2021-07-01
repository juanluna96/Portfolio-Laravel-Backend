<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BiographySeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(ProyectSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(ProyectsLanguagesSeeder::class);
        $this->call(CategoriesLanguagesDescriptionsSeeder::class);
        $this->call(CategoriesProyectsSeeder::class);
    }
}
