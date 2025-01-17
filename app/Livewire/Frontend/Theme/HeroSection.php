<?php

namespace App\Livewire\Frontend\Theme;

use Livewire\Component;

class HeroSection extends Component
{
    public $title = 'Get measurable results<br>from online marketing';
    public $subtitle = 'Do SEO, content marketing, competitor research,<br>
PPC and social media marketing from just one platform.';
    public $ctaText = 'Get Started';
    public $ctaLink = '#'; // Link to registration page for pro features
    public $domain = '';
    public $seoResult = null;

    public function checkSeo()
    {
        // For demo purposes, simulate an SEO check. Replace with actual logic.
        if (filter_var($this->domain, FILTER_VALIDATE_URL)) {
            $this->seoResult = 'SEO data for ' . $this->domain . ' is healthy!';
        } else {
            $this->seoResult = null;
        }
    }

    public function render()
    {
        return view('livewire.frontend.theme.hero-section');
    }
}
