<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Image;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    private $faker;
    private $usersId;
    private $categoryIds = [];

    public function __construct()
    {
        $this->faker = Faker::create('vi_VN');
        $this->usersId = User::whereNotNull('role_id')->pluck('id')->toArray();
    }

    private function generateArticleContent(array $imageData): string
    {
        $content = '';
        $usedImages = [];
        for ($section = 0; $section < 3; $section++) {
            $content .= '<div><b>' . $this->faker->sentence . '</b></div>';
            $numSentences = rand(3, 6);
            for ($i = 0; $i < $numSentences; $i++) {
                $content .= '<p>' . $this->faker->sentence(rand(8, 15), true) . '</p>';
            }
            $availableImages = collect($imageData)->where('is_thumbnail', false)->shuffle()->take(2);
            foreach ($availableImages as $image) {
                $filename = $image['filename'];
                $path = basename($image['path']);
                $content .= '<img alt="' . $filename . '" src="/storage/default/product/' . $path . '">';
                $usedImages[] = $filename;
            }
        }
        return $content;
    }

    private function createArticles(array $files): void {
        $categories = [
            ['name' => 'Tin nổi bật', 'type' => 'article'],
            ['name' => 'Tin công ty', 'type' => 'article'],
            ['name' => 'Tin bán hàng', 'type' => 'article'],
        ];
        foreach ($categories as $c) {
            $category = Category::create($c);
            $this->categoryIds[] = $category->id;
        }
        
        for ($i = 0; $i < 25; $i++) {
            $article = Article::create([
                'author' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->randomElement($this->usersId),
                'title' => $this->faker->sentence,
                'highlight' => $this->faker->sentence,
                'publish_date' => $this->faker->dateTimeBetween('-6 months', '+1 week')->format('Y-m-d'),
                'category_id' => $this->faker->randomElement($this->categoryIds),
            ]);

            $thumbnailFile = $this->faker->randomElement($files);
            $thumbnailImage = Image::create([
                'article_id' => $article->id,
                'filename' => 'thumbnail',
                'path' => '/storage/default/product/' . basename($thumbnailFile),
                'is_thumbnail' => true,
                'size' => rand(100000, 800000),
                'mime_type' => 'image/jpeg',
            ]);
            $images = [];
            $imageData = [];
            for ($j = 0; $j < 6; $j++) {
                $selectedFile = $this->faker->randomElement($files);
                $imageInfo = [
                    'article_id' => $article->id,
                    'filename' => $j,
                    'path' => '/storage/default/product/' . basename($selectedFile),
                    'is_thumbnail' => false,
                    'size' => rand(100000, 800000),
                    'mime_type' => 'image/jpeg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $images[] = $imageInfo;
                $imageData[] = $imageInfo;
            }
            Image::insert($images);
            $imageData[] = [
                'filename' => 'thumbnail',
                'path' => '/storage/default/product/' . basename($thumbnailFile),
                'is_thumbnail' => true
            ];
            $content = $this->generateArticleContent($imageData);
            $article->update(['content' => $content]);
        }
        for ($i = 0; $i < 5; $i++) {
            Article::create([
                'author' => $this->faker->randomElement($this->usersId),
                'title' => $this->faker->sentence,
                'highlight' => $this->faker->sentence,
                'content' => $this->faker->text,
                'category_id' => $this->faker->randomElement($this->categoryIds),
            ]);
        }
    }

    public function run(): void
    {
        $files = collect(Storage::disk('public')->allFiles('default/product'))
            ->filter(function ($file) {
                return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
            })->values()->all();
        $this->createArticles($files);
    }
}
