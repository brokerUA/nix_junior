<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;


class AuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::factory()
            ->count(10)
            ->create();
    }
}
