<?php
/**
 * Parent file: app/Console/Commands/PublishScheduledPosts.php
 * Command tự động publish các bài viết có published_at <= now()
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BaiViet;
use Carbon\Carbon;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động publish các bài viết đã đến thời gian xuất bản';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Tìm các bài draft có published_at <= now
        $posts = BaiViet::where('status', 'draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->get();

        if ($posts->isEmpty()) {
            $this->info('✅ Không có bài viết nào cần publish.');
            return 0;
        }

        $count = 0;
        foreach ($posts as $post) {
            $post->update(['status' => 'published']);
            $count++;
            $this->info("✅ Published: {$post->title}");
        }

        $this->info("🎉 Đã publish {$count} bài viết!");
        return 0;
    }
}
