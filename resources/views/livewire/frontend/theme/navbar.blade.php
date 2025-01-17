<div class="main-menu">
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <!-- <img src="/path-to-your-logo.png" alt="Logo"> -->
                <a href="/" wire:navigate>
                    <span>Q</span>uorank
                </a>
            </div>

            <ul class="menu">
                @foreach ($menu as $index => $item)
                <li wire:click="toggleMenu({{ $index }})" style="cursor: pointer;">
                    <a  @if (!$item['dropdown']) href="{{ $item['link'] }}" @endif wire:navigate>{!! $item['name'] !!}</a>
                    @if ($item['dropdown'] ?? false)
                    @if ($openMenuIndex === $index && ($item['mega'] ?? false))
                    <ul class="mega-menu">
                        @foreach ($item['items'] as $column)
                        <div class="column">

                            <ul>
                                @foreach ($column['items'] as $subItem)
                                <li class="mb-3">
                                    <a href="{{ $subItem['link'] }}" wire:navigate>
                                        <span class="d-block mb-2"><strong>{{ $subItem['name'] }}</strong></span>
                                        <small>Blog
                                            Read the industry's latest thoughts on digital marketing, content strategy, SEO, PPC, social media and more.</small>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </ul>
                    @elseif ($openMenuIndex === $index)
                    <ul class="dropdown">
                        @foreach ($item['items'] as $subItem)
                        <li><a href="{{ $subItem['link'] }}" wire:navigate>{{ $subItem['name'] }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>


<script>
    document.addEventListener('click', function(event) {
        // Check if the click happened inside the menu
        let menu = document.querySelector('.menu');
        if (!menu.contains(event.target)) {
            // Emit Livewire event to close all menus
            Livewire.dispatch('closeMenus');
        }
    });
</script>