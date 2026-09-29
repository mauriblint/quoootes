<?php
declare(strict_types=1);

namespace App\Controller;

use App\Lib\DesignService;
use App\Lib\TagService;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Dynamic XML sitemaps: an index plus one sitemap for static pages/categories
 * and N chunked sitemaps for public designs.
 */
class SitemapController extends AppController
{
    const DESIGNS_PER_SITEMAP = 10000;

    public function index()
    {
        $total = $this->publicDesignsQuery()->count();
        $chunks = max(1, (int) ceil($total / self::DESIGNS_PER_SITEMAP));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= $this->sitemapEntry(Router::url('/sitemap-pages.xml', true));
        for ($i = 1; $i <= $chunks; $i++) {
            $xml .= $this->sitemapEntry(Router::url('/sitemap-designs-' . $i . '.xml', true));
        }
        $xml .= '</sitemapindex>';

        return $this->xmlResponse($xml);
    }

    public function pages()
    {
        $urls = ['/', '/quote-maker', '/quick-studio', '/quotes', '/templates', '/templates/instagram-quotes', '/inspirational-quotes-maker', '/blog/', '/support'];

        foreach (TagService::getPopularList() as $tag) {
            $urls[] = '/quotes/' . $tag['slug'];
        }

        $xml = $this->urlsetOpen();
        foreach ($urls as $url) {
            $xml .= $this->urlEntry(Router::url($url, true));
        }
        $xml .= '</urlset>';

        return $this->xmlResponse($xml);
    }

    public function designs($page = 1)
    {
        $page = max(1, (int) $page);

        $designs = $this->publicDesignsQuery()
            ->select(['id', 'shortcode', 'image_url', 'created'])
            ->order(['id' => 'ASC'])
            ->limit(self::DESIGNS_PER_SITEMAP)
            ->page($page)
            ->enableHydration(false)
            ->toArray();

        if (empty($designs) && $page > 1) {
            return $this->redirect('/sitemap.xml', 301);
        }

        $xml = $this->urlsetOpen(true);
        foreach ($designs as $design) {
            $lastmod = $design['created'] ? $design['created']->format('Y-m-d') : null;
            $xml .= $this->urlEntry(DesignService::url($design, true), $lastmod, $design['image_url']);
        }
        $xml .= '</urlset>';

        return $this->xmlResponse($xml);
    }

    private function publicDesignsQuery()
    {
        return TableRegistry::getTableLocator()->get('Designs')
            ->find()
            ->where(['status' => 1]);
    }

    private function urlsetOpen($withImages = false)
    {
        $ns = 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        if ($withImages) {
            $ns .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"';
        }

        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset ' . $ns . '>' . "\n";
    }

    private function sitemapEntry($loc)
    {
        return '<sitemap><loc>' . $this->esc($loc) . '</loc></sitemap>' . "\n";
    }

    private function urlEntry($loc, $lastmod = null, $image = null)
    {
        $xml = '<url><loc>' . $this->esc($loc) . '</loc>';
        if ($lastmod) {
            $xml .= '<lastmod>' . $lastmod . '</lastmod>';
        }
        if ($image) {
            $xml .= '<image:image><image:loc>' . $this->esc($image) . '</image:loc></image:image>';
        }

        return $xml . '</url>' . "\n";
    }

    private function esc($value)
    {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function xmlResponse($xml)
    {
        return $this->response
            ->withType('xml')
            ->withHeader('Cache-Control', 'public, max-age=3600')
            ->withStringBody($xml);
    }
}
