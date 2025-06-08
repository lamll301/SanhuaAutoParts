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
    private $path = 'sanhua';

    public function __construct()
    {
        $this->faker = Faker::create('vi_VN');
        $this->usersId = User::whereNotNull('role_id')->pluck('id')->toArray();
    }

    private function generateArticleContent(array $files): string
    {
        $content = '';
        $j = 0;
        for ($section = 0; $section < 3; $section++) {
            $content .= '<div><b>' . $this->faker->sentence . '</b></div>';
            $numSentences = rand(3, 6);
            for ($i = 0; $i < $numSentences; $i++) {
                $content .= '<p>' . $this->faker->sentence(rand(10, 20), true) . '</p>';
            }
            $randomImages = collect($files)->shuffle()->take(rand(1, 2));
            foreach ($randomImages as $image) {
                $filename = $j;
                $path = basename($image);
                $content .= '<img alt="' . $filename . '" src="/storage/' . $path . '">';
                $j++;
            }
        }
        $content .= '<p>' . $this->faker->sentence(rand(50, 100), true) . '</p>';
        return $content;
    }

    private function createArticles(array $files): void {
        $categories = [
            ['name' => 'Tin nổi bật', 'type' => 'article'],
            ['name' => 'Tin công ty', 'type' => 'article'],
            ['name' => 'Tin bán hàng', 'type' => 'article'],
        ];
        $titles = [
            "Top 10 phụ tùng ô tô cần thay thế định kỳ để đảm bảo an toàn", "Xu hướng phụ tùng ô tô chính hãng năm 2024: Điểm mặt những sản phẩm hot", "Hướng dẫn cách nhận biết phụ tùng ô tô giả - kém chất lượng",
            "5 dấu hiệu cảnh báo cần thay thế phanh ô tô ngay lập tức", "So sánh ưu nhược điểm giữa phụ tùng OEM và Aftermarket", "Bí quyết chọn lọc dầu động cơ phù hợp cho từng dòng xe",
            "Công nghệ mới trong sản xuất phụ tùng ô tô: Tương lai của ngành công nghiệp ô tô", "Hướng dẫn tự kiểm tra và bảo dưỡng hệ thống treo tại nhà", "Tổng hợp các mã lỗi động cơ thường gặp và cách khắc phục",
            "Xu hướng nâng cấp phụ tùng thể thao cho dòng xe phổ thông", "Đánh giá chi tiết bộ lọc gió KN cao cấp cho xe SUV đô thị", "Cảnh báo: 7 phụ tùng ô tô không nên mua hàng giá rẻ",
            "Hệ thống làm mát động cơ: Cấu tạo và những hư hỏng thường gặp", "Hướng dẫn chọn size lốp xe phù hợp và những điều cần lưu ý", "Công nghệ phanh mới trên các dòng xe 2024 - Điểm khác biệt",
            "Bộ dụng cụ sửa chữa ô tô cơ bản mọi tài xế nên có trong xe", "Phân tích ưu điểm của bugi Iridium so với bugi thông thường", "Tư vấn chọn phụ tùng thay thế cho xe ô tô đời cũ",
            "Xu hướng độ xe theo phong cách OEM+ đang thịnh hành hiện nay", "Hệ thống điện ô tô: Những sự cố thường gặp và cách xử lý"
        ];
        foreach ($categories as $c) {
            $category = Category::create($c);
            $this->categoryIds[] = $category->id;
        }
        
        for ($i = 0; $i < 20; $i++) {
            $article = Article::create([
                'author' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->randomElement($this->usersId),
                'title' => $this->faker->randomElement($titles),
                'highlight' => $this->faker->sentence,
                'publish_date' => $this->faker->dateTimeBetween('-6 months', '+1 week')->format('Y-m-d'),
                'content' => $this->generateArticleContent($files),
                'category_id' => $this->faker->randomElement($this->categoryIds),
            ]);
            Image::create([
                'article_id' => $article->id,
                'filename' => 7,
                'path' => '/storage/' . $this->faker->randomElement($files),
                'is_thumbnail' => true,
                'size' => rand(100000, 800000),
                'mime_type' => 'image/jpeg',
            ]);
            $images = [];
            for ($j = 0; $j < 6; $j++) {
                $images[] = [
                    'article_id' => $article->id,
                    'filename' => $j,
                    'path' => '/storage/' . $this->faker->randomElement($files),
                    'is_thumbnail' => false,
                    'size' => rand(100000, 800000),
                    'mime_type' => 'image/jpeg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Image::insert($images);
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
        $files = collect(Storage::disk('public')->allFiles($this->path))
            ->filter(function ($file) {
                return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
            })->values()->all();
        $this->createArticles($files);
    }
}
