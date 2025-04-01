<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Hướng dẫn thay má phanh ô tô',
                'slug' => 'huong-dan-thay-ma-phanh-o-to',
                'highlight' => 'Hệ thống phanh',
                'author' => 'Nguyễn Văn A',
                'publish_date' => '2025-04-01 10:00:00',
                'content' => 'Bài viết hướng dẫn cách thay má phanh ô tô một cách an toàn và hiệu quả.',
                'status' => 1,
            ],
            [
                'title' => 'Lọc dầu ô tô quan trọng như thế nào?',
                'slug' => 'loc-dau-o-to-quan-trong-nhu-the-nao',
                'highlight' => 'Bảo dưỡng động cơ',
                'author' => 'Trần Thị B',
                'publish_date' => '2025-05-15 14:30:00',
                'content' => 'Tầm quan trọng của lọc dầu đối với hiệu suất và tuổi thọ động cơ ô tô.',
            ],
            [
                'title' => 'Cách chọn bugi đánh lửa phù hợp',
                'slug' => 'cach-chon-bugi-danh-lua-phu-hop',
                'highlight' => 'Hệ thống đánh lửa',
                'author' => 'Lê Văn C',
                'publish_date' => '2025-06-20 09:00:00',
                'content' => 'Hướng dẫn lựa chọn bugi đánh lửa tốt nhất cho xe của bạn.',
            ],
            [
                'title' => 'Lọc gió động cơ và tác dụng của nó',
                'slug' => 'loc-gio-dong-co-va-tac-dung',
                'highlight' => 'Bảo dưỡng động cơ',
                'author' => 'Phạm Thị D',
                'publish_date' => '2025-07-10 16:00:00',
                'content' => 'Lọc gió có ảnh hưởng lớn đến hiệu suất động cơ và tiết kiệm nhiên liệu.',
            ],
            [
                'title' => 'Thay dây đai cam: Khi nào là thời điểm thích hợp?',
                'slug' => 'thay-day-dai-cam-khi-nao-thich-hop',
                'highlight' => 'Bảo dưỡng động cơ',
                'author' => 'Hoàng Văn E',
                'publish_date' => '2025-08-05 12:00:00',
                'content' => 'Dấu hiệu nhận biết thời điểm cần thay dây đai cam để đảm bảo an toàn cho động cơ.',
                'status' => 1,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
