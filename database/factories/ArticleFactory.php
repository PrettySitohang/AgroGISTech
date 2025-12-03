<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        // List gambar bertema teknologi perkebunan sawit
        $images = [
            'https://source.unsplash.com/800x600/?palm-oil,technology',
            'https://source.unsplash.com/800x600/?drone,plantation',
            'https://source.unsplash.com/800x600/?palm,agriculture',
            'https://source.unsplash.com/800x600/?palm,farm',
            'https://source.unsplash.com/800x600/?agriculture,mapping',
            'https://source.unsplash.com/800x600/?tractor,palm',
            'https://source.unsplash.com/800x600/?industry,palm-oil',
        ];

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(5, true),
            'status' => $this->faker->randomElement(['draft', 'pending', 'published']),

            // Laravel otomatis buat user baru
            'author_id' => User::factory(),
            'editor_id' => User::factory(),

            // Pilih gambar bertema sawit
            'cover_image' => $this->faker->randomElement($images),

            'published_at' => $this->faker->optional()->dateTimeThisYear(),
        ];
    }
}
