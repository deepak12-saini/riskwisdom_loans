<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $lastmod = now()->toAtomString();

        $paths = [
            ['path' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['path' => '/book', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/about', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/rate-review', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/home-loans', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/refinance', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/refinance-home-loan-rates', 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['path' => '/refinance-home-loan-calculator', 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['path' => '/refinance-cashback-offers', 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['path' => '/investment-property-loans', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/first-home-buyer', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/commercial-finance', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/tools/borrowing-power', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/tools/repayment-calculator', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/tools/stamp-duty', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/guides', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/partners', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/privacy-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/credit-guide', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/thank-you', 'changefreq' => 'yearly', 'priority' => '0.2'],
        ];

        $guideSlugs = [
            'when-to-refinance-home-loan-australia',
            'first-home-buyer-checklist-australia',
            'fixed-vs-variable-home-loans-australia',
            'how-much-can-i-borrow-australia',
            'refinance-readiness-checklist',
            'investment-property-loan-basics-australia',
        ];

        foreach ($guideSlugs as $slug) {
            $paths[] = [
                'path' => '/guides/'.$slug,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        foreach (array_keys(config('riskwisdom.download_guides', [])) as $slug) {
            $paths[] = [
                'path' => '/download-guides/'.$slug,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        $urls = array_map(static function (array $entry) use ($lastmod) {
            return [
                'loc' => url($entry['path']),
                'lastmod' => $lastmod,
                'changefreq' => $entry['changefreq'],
                'priority' => $entry['priority'],
            ];
        }, $paths);

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
