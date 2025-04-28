<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    private function generateArticleContent($faker): string
    {
        $content = '<p>' . $faker->paragraph(5) . '</p>';
        $content .= '<h2>' . $faker->sentence(3) . '</h2>';
        $content .= '<p>' . $faker->paragraph(8) . '</p>';
        $content .= '<p><img src="' . $faker->imageUrl(800, 400) . '" alt="' . $faker->sentence(2) . '"></p>';
        $content .= '<p>' . $faker->paragraph(6) . '</p>';
        $content .= '<h3>' . $faker->sentence(4) . '</h3>';
        $content .= '<p>' . $faker->paragraph(7) . '</p>';
        
        return $content;
    }

    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 25; $i++) {
            $title = $faker->sentence(6);
            $slug = Str::slug($title);
            
            Article::create([
                'title' => $title,
                'slug' => $slug,
                'highlight' => $faker->optional(0.6)->sentence,
                'author' => $faker->optional()->randomElement($userIds),
                'approved_by' => $faker->optional(0.5)->randomElement($userIds),
                'publish_date' => $faker->dateTimeBetween('-6 months', '+1 week')->format('Y-m-d'),
                'content' => $this->generateArticleContent($faker),
            ]);
        }
    }
}
