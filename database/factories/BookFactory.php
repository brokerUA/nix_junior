<?php

namespace Database\Factories;

use App\Models\{
    Category,
    Author,
    Book,
    User
};
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => Author::inRandomOrder()->first(),
            'description' => $this->faker->realText(255),
            'category_id' => Category::inRandomOrder()->first(),
            'poster' => null,
            'user_id' => User::first(),
        ];
    }
}
