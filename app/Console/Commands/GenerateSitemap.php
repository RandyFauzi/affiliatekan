<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate public sitemap.xml for Affiliatekan B2B SaaS platform';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $domain = 'https://affiliatekan-by.autogrowthid.site';

        $urls = [
            [
                'loc' => $domain . '/',
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => $domain . '/login',
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $domain . '/register',
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $domain . '/forgot-password',
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
            // Placeholders for prospective public pages mentioned in instructions
            [
                'loc' => $domain . '/about',
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
            [
                'loc' => $domain . '/pricing',
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '    <url>' . "\n";
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '        <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '    </url>' . "\n";
        }

        $xml .= '</urlset>' . "\n";

        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap generated successfully at ' . public_path('sitemap.xml'));
    }
}
