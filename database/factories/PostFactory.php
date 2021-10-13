<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, User::count()),
            'title' => substr($this->faker->sentence, 0, 50),
            'content' => substr($this->faker->paragraph, 0, 500),
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween()
        ];
    }
}
