@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>
    <head>

        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="{{ url()->to('/') }}"
        >
        <meta
            name="currency"
            content="{{ core()->getCurrentCurrency()->toJson() }}"
        >

        <meta
            name="locale"
            content="{{ app()->getLocale() }}"
        >

        @stack('meta')

        @bagistoVite(['src/Resources/assets/css/main.css', 'src/Resources/assets/css/mobile.css'], 'zplus')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

        {!! view_render_event('bagisto.shop.layout.head.after') !!}

    </head>

    <body>

        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <div id="app">

            <!-- Flash Message Blade Component -->
            <x-shop::flash-group />

            <!-- Header -->
            @if ($hasHeader)
                {!! view_render_event('bagisto.shop.layout.header.before') !!}

                <x-zplus-theme::layouts.header />

                {!! view_render_event('bagisto.shop.layout.header.after') !!}
            @endif

            <!-- Content -->
            {!! view_render_event('bagisto.shop.layout.content.before') !!}

            {{ $slot }}

            {!! view_render_event('bagisto.shop.layout.content.after') !!}

            <!-- Footer -->
            @if ($hasFooter)
                {!! view_render_event('bagisto.shop.layout.footer.before') !!}

                <x-zplus-theme::layouts.footer />

                {!! view_render_event('bagisto.shop.layout.footer.after') !!}
            @endif

        </div>

        @stack('scripts')

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        <!-- Initialize Lucide Icons -->
        <script>
            lucide.createIcons();
        </script>

    </body>
</html>