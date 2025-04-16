<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        foreach (range(1, 20) as $index) {
            $title = $faker->sentence($nbWords = 8, $variableNbWords = true);
            $slug = Str::slug($title);
            $highlight = $faker->text(64);
            $author = $faker->name;
            $publish_date = $faker->dateTimeThisYear()->format('Y-m-d H:i:s');
            $content = $faker->paragraphs($nb = 5, $asText = true);

            Article::create([
                'title' => $title,
                'slug' => $slug,
                'highlight' => $highlight,
                'author' => $author,
                'publish_date' => $publish_date,
                'content' => $content,
                'status' => $faker->randomElement([0, 1]),
            ]);
        }
    }
}
