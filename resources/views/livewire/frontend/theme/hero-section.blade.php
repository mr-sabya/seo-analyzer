<div class="hero-section">
    <div class="banner">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 text-center text-white">
                    <!-- Hero Title -->
                    <h1 class="">{!! $title !!}</h1>
                    <!-- Hero Subtitle -->
                    <p class="lead">{!! $subtitle !!}</p>

                    <div class="hero-search">


                        <!-- Domain Search Form -->
                        <form wire:submit.prevent="checkSeo">
                            <div class="search">
                                <input type="text" placeholder="Enter your website or domain" wire:model="domain" aria-label="Enter your website or domain" aria-describedby="button-addon2">
                                <button type="submit" id="button-addon2">Check SEO</button>
                            </div>
                        </form>
                    </div>

                    <p class="mt-5">SEO | Website Speed | Rank | Keyword Research | Analytics</p>
                </div>
            </div>
        </div>
    </div>

</div>