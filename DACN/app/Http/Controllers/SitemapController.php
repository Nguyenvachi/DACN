<?php
/**
 * Parent file: app/Http/Controllers/SitemapController.php
 * Tạo sitemap.xml động cho SEO
 */

namespace App\Http\Controllers;

use App\Models\BaiViet;
use App\Models\DanhMuc;
use App\Models\BacSi;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    /**
     * Generate sitemap.xml
     */
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $sitemap .= $this->addUrl(URL::to('/'), now(), 'daily', '1.0');

        // Bài viết published
        $posts = BaiViet::published()->get();
        foreach ($posts as $post) {
            $sitemap .= $this->addUrl(
                route('blog.show', $post->slug),
                $post->updated_at ?? $post->created_at,
                'weekly',
                '0.8'
            );
        }

        // Danh mục
        $categories = DanhMuc::all();
        foreach ($categories as $cat) {
            $sitemap .= $this->addUrl(
                route('blog.category', $cat->slug),
                $cat->updated_at ?? now(),
                'weekly',
                '0.7'
            );
        }

        // Blog index
        $sitemap .= $this->addUrl(
            route('blog.index'),
            now(),
            'daily',
            '0.9'
        );

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Helper: tạo URL node cho sitemap
     */
    protected function addUrl($loc, $lastmod = null, $changefreq = 'weekly', $priority = '0.5')
    {
        $url = '<url>';
        $url .= '<loc>' . htmlspecialchars($loc) . '</loc>';

        if ($lastmod) {
            $url .= '<lastmod>' . $lastmod->format('Y-m-d') . '</lastmod>';
        }

        $url .= '<changefreq>' . $changefreq . '</changefreq>';
        $url .= '<priority>' . $priority . '</priority>';
        $url .= '</url>';

        return $url;
    }
}
