<!DOCTYPE html>
<html lang="{{ $page->language ?? 'en' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>{{ $page->title ? $page->title . ' | ' : '' }}{{ $page->siteName }}</title>
        <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">

        <meta property="og:title" content="{{ $page->title ? $page->title . ' | ' : '' }}{{ $page->siteName }}"/>
        <meta property="og:type" content="{{ $page->type ?? 'website' }}" />
        <meta property="og:url" content="{{ $page->getUrl() }}"/>
        <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}" />
        <meta property="og:image" content="{{ $page->baseUrl }}/assets/images/about-page.webp" />

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="{{ str_replace( 'https://', '', $page->baseUrl ) }}">
        <meta property="twitter:url" content="{{ $page->baseUrl }}">
        <meta name="twitter:title" content="{{ $page->title ? $page->title . ' | ' : '' }}{{ $page->siteName }}">
        <meta name="twitter:description" content="{{ $page->description ?? $page->siteDescription }}">
        <meta name="twitter:image" content="{{ $page->baseUrl }}/assets/images/about-page.webp">


        <link rel="canonical" href="{{ $page->getUrl() }}">
        <link rel="icon" href="/assets/images/favicon-32x32.jpg" sizes="32x32" />
        <link rel="icon" href="/assets/images/favicon-192x192.jpg" sizes="192x192" />
        <link rel="apple-touch-icon" href="/assets/images/favicon-180x180.jpg" />
        <meta name="msapplication-TileImage" content="/assets/images/favicon-270x270.jpg" />

        @if ($page->production)
        <script defer data-domain="jrtashjian.com" src="https://plausible.jrtashjian.com/js/plausible.js"></script>
        @endif

        <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
        <script defer src="{{ mix('js/main.js', 'assets/build') }}"></script>

        <link rel="alternate" type="application/rss+xml" title="All Posts by {{ $page->siteName }} (The RSS Feed)" href="{{ $page->baseUrl }}/feed.xml" />
    </head>
    <body class="bg-white dark:bg-black text-black dark:text-white font-mono text-lg leading-relaxed px-4">
        <div class="mx-auto max-w-prose">

            <header class="flex flex-wrap justify-between mb-8 py-10 border-b-8 border-black dark:border-white gap-3" role="banner">
                <a href="/" title="{{ $page->siteName}} home">
                    <h1 class="font-bold">~/jrtashjian</h1>
                </a>
                <div class="h-card flex items-center gap-x-3">
                    <a href="https://talos.link/@jrtashjian" rel="me" class="u-url inline-flex items-center gap-x-2">
                        <svg class="fill-current" width="24" height="24" view-box="0 0 24 24" version="1.1">
                            <path d="M23.193 7.879c0-5.206-3.411-6.732-3.411-6.732C18.062.357 15.108.025 12.041 0h-.076c-3.068.025-6.02.357-7.74 1.147 0 0-3.411 1.526-3.411 6.732 0 1.192-.023 2.618.015 4.129.124 5.092.934 10.109 5.641 11.355 2.17.574 4.034.695 5.535.612 2.722-.15 4.25-.972 4.25-.972l-.09-1.975s-1.945.613-4.129.539c-2.165-.074-4.449-.233-4.799-2.891a5.499 5.499 0 0 1-.048-.745s2.125.52 4.817.643c1.646.075 3.19-.097 4.758-.283 3.007-.359 5.625-2.212 5.954-3.905.517-2.665.475-6.507.475-6.507zm-4.024 6.709h-2.497V8.469c0-1.29-.543-1.944-1.628-1.944-1.2 0-1.802.776-1.802 2.312v3.349h-2.483v-3.35c0-1.536-.602-2.312-1.802-2.312-1.085 0-1.628.655-1.628 1.944v6.119H4.832V8.284c0-1.289.328-2.313.987-3.07.68-.758 1.569-1.146 2.674-1.146 1.278 0 2.246.491 2.886 1.474L12 6.585l.622-1.043c.64-.983 1.608-1.474 2.886-1.474 1.104 0 1.994.388 2.674 1.146.658.757.986 1.781.986 3.07v6.304z" />
                        </svg>
                        Mastodon
                    </a>
                    <a href="mailto:hello@jrtashjian.com" class="u-email inline-flex items-center gap-x-2">
                        <svg class="fill-current" width="24" height="24" view-box="0 0 24 24" version="1.1">
                            <path d="M20,4H4C2.895,4,2,4.895,2,6v12c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2V6C22,4.895,21.105,4,20,4z M20,8.236l-8,4.882 L4,8.236V6h16V8.236z" />
                        </svg>
                        Email
                    </a>
                </div>
            </header>

            <main role="main">
                @yield('body')
            </main>

            <footer class="flex justify-end mt-8 py-8 border-t-8 border-black dark:border-white" role="contentinfo">
                Copyright (c) {{ date('Y') }} JR Tashjian
            </footer>
        </div>
    </body>
</html>
