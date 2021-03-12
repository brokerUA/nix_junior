<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create();

        $this->call([
            AuthorsSeeder::class,
            CategoriesSeeder::class,
            BooksSeeder::class,
            LaratrustSeeder::class,
        ]);

        if (! config('laratrust_seeder.create_users')) {
            $this->call(UsersSeeder::class,);
        }
    }
}
