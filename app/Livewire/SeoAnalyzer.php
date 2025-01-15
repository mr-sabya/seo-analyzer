<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Utils;
use Symfony\Component\DomCrawler\Crawler;

class SeoAnalyzer extends Component
{
    public $url;
    public $results = [];
    public $errors = [];
    public $brokenLinks = [];
    public $performanceData = [];

    // Method to initiate SEO and performance analysis
    public function analyze()
    {
        $this->results = [];
        $this->errors = [];

        $cacheKey = 'seo_analysis_' . md5($this->url);
        Cache::forget($cacheKey);

        // Check if results are cached
        if (Cache::has($cacheKey)) {
            $this->results = Cache::get($cacheKey);
            return;
        }

        // Validate URL
        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            $this->errors[] = "Please enter a valid URL.";
            return;
        }

        try {
            // Fetch website content using Guzzle
            $client = new Client();
            $response = $client->get($this->url);
            $html = $response->getBody()->getContents();

            // Pass the full URL as the base URI when initializing the Crawler
            $crawler = new Crawler($html, $this->url);  // <-- Pass the base URL here

            // Perform SEO checks
            $this->checkTitleTag($crawler);
            $this->checkMetaDescription($crawler);
            $this->checkHeadingTags($crawler);
            $this->checkImageAltTags($crawler);
            $this->checkLinks($crawler);

            // Perform Performance Analysis
            $this->analyzePerformance();

            // Dispatch updated SEO and performance data to frontend
            $this->dispatch('seoDataUpdated', $this->results);
            $this->dispatch('performanceUpdated', $this->performanceData);
        } catch (\Exception $e) {
            $this->errors[] = "An error occurred: " . $e->getMessage();
        }

        // Cache the results for 24 hours
        Cache::put($cacheKey, $this->results, now()->addHours(24));
    }

    // Method for checking the title tag
    protected function checkTitleTag(Crawler $crawler)
    {
        $title = $crawler->filter('title')->text();
        if (empty($title)) {
            $this->errors[] = "Missing title tag.";
        } else {
            $this->results['Title'] = $title;
        }
    }

    // Method for checking the meta description
    protected function checkMetaDescription(Crawler $crawler)
    {
        $metaDescription = $crawler->filter('meta[name="description"]')->attr('content');
        if (empty($metaDescription)) {
            $this->errors[] = "Missing meta description.";
        } else {
            $this->results['Meta Description'] = $metaDescription;
        }
    }

    // Method for checking heading tags (H1-H6)
    protected function checkHeadingTags(Crawler $crawler)
    {
        $headings = [];
        for ($i = 1; $i <= 6; $i++) {
            $crawler->filter('h' . $i)->each(function ($node) use ($i, &$headings) {
                $headings[] = "H{$i}: " . $node->text();
            });
        }

        if (empty($headings)) {
            $this->errors[] = "Missing heading tags (H1-H6).";
        } else {
            $this->results['Headings'] = $headings;
        }
    }

    // Method for checking images with missing alt attributes
    protected function checkImageAltTags(Crawler $crawler)
    {
        $imagesWithoutAlt = $crawler->filter('img:not([alt])');
        if ($imagesWithoutAlt->count() > 0) {
            $this->errors[] = "Found images without alt attributes.";
        } else {
            $this->results['Images'] = "All images have alt attributes.";
        }
    }

    // Method for checking links and determining broken links
    protected function checkLinks(Crawler $crawler)
    {
        $links = $crawler->filter('a')->links();  // Get all links from the page
        $client = new Client();
        $promises = [];  // Array to hold promises

        foreach ($links as $link) {
            // Create asynchronous requests (HEAD requests)
            $promises[] = $client->headAsync($link->getUri())->then(
                function ($response) use ($link) {
                    // Check status code and handle broken links
                    if ($response->getStatusCode() !== 200) {
                        return $link->getUri() . ' - Status: ' . $response->getStatusCode();
                    }
                    return null;  // No issue with this link
                },
                function ($exception) use ($link) {
                    // Handle request exceptions (e.g., network issues)
                    return $link->getUri() . ' - Error: ' . $exception->getMessage();
                }
            );
        }

        // Wait for all promises to resolve using Promise\Utils::all()
        $results = Utils::all($promises)->wait();

        // Filter out null results (valid links)
        $this->brokenLinks = array_filter($results);

        // Process broken links
        if (!empty($this->brokenLinks)) {
            $this->errors[] = "Found broken links:";
        } else {
            $this->results['Links'] = "All links are working.";
        }
    }

    // Method for analyzing performance of the URL
    protected function analyzePerformance()
    {
        // Simulate performance analysis (this could be extended with an actual performance API)
        $this->performanceData = [
            'Page Load Time' => rand(1000, 5000) . ' ms',
            'FCP' => rand(500, 2000) . ' ms',
            'LCP' => rand(1000, 3000) . ' ms',
            'TTI' => rand(1500, 4000) . ' ms',
            'CLS' => rand(0, 1) . ' (low)'
        ];
    }

    // Method to update performance metrics based on URL
    public function updatePerformanceMetrics($url, $metrics)
    {
        $this->url = $url;
        $this->performanceData = $metrics;

        // Dispatch performance metrics to frontend
        $this->dispatch('performanceUpdated', $metrics);
    }

    // Render method
    public function render()
    {
        return view('livewire.seo-analyzer');
    }
}
