<?php

namespace App\Livewire\Frontend\Theme;

use Livewire\Component;

class Navbar extends Component
{
    public $menu = [
        // ['name' => 'Home', 'link' => '/', 'dropdown' => false],
        ['name' => 'Features', 'link' => '/features', 'dropdown' => false],
        [
            'name' => 'Services <i class="ri-arrow-down-s-line"></i>',
            'link' => '#',
            'dropdown' => true,
            'items' => [
                ['name' => 'Web Development', 'link' => '/services/web-development'],
                ['name' => 'SEO', 'link' => '/services/seo'],
            ],
        ],
        [
            'name' => 'Products <i class="ri-arrow-down-s-line"></i>',
            'link' => '#',
            'dropdown' => true,
            'mega' => true,
            'items' => [
                [
                    'name' => 'Blog',
                    'items' => [
                        [
                            'name' => 'Accounting',
                            'link' => '/products/accounting',
                            'text' => 'Get the latest news and insights on accounting software, financial reporting and more.',
                        ],
                        [
                            'name' => 'HR',
                            'link' => '/products/hr',
                            'text' => 'Get the latest news and insights on HR software, payroll, benefits and more.',
                        ],
                    ],
                ],
                [
                    'name' => 'Hardware',
                    'items' => [
                        ['name' => 'Laptops', 'link' => '/products/laptops'],
                        ['name' => 'Monitors', 'link' => '/products/monitors'],
                    ],
                ],
            ],
        ],
        [
            'name' => 'Company <i class="ri-arrow-down-s-line"></i>',
            'link' => '#',
            'dropdown' => true,
            'items' => [
                [
                    'name' => 'About Us',
                    'link' => '/services/web-development'
                ],
                [
                    'name' => 'Newsroom',
                    'link' => '/services/seo'
                ],
            ],
        ],
        ['name' => 'About', 'link' => '/about', 'dropdown' => false],
        ['name' => 'Contact', 'link' => '/contact', 'dropdown' => false],
    ];

    public $openMenuIndex = null;

    public function toggleMenu($index)
    {
        $this->openMenuIndex = $this->openMenuIndex === $index ? null : $index;
    }

    public function closeMenus()
    {
        $this->openMenuIndex = null;
    }

    protected $listeners = ['closeMenus'];

    public function render()
    {
        return view('livewire.frontend.theme.navbar');
    }
}
