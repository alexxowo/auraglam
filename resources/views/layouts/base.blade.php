<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aura Glam') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #be004c;
            --primary-container: #fc306f;
            --on-primary: #fff7f7;
            --secondary-container: #ffd9e2;
            --surface: #faf9f9;
            --surface-container-low: #f3f3f4;
            --surface-container-lowest: #ffffff;
            --surface-container-highest: #e1e3e3;
            --on-background: #303334;
            --on-surface-variant: #5d5f60;
            --outline-variant: rgba(93, 95, 96, 0.15);
            --error-container: #f97386;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surface);
            color: var(--on-background);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6, .display-text {
            font-family: 'Manrope', sans-serif;
            font-weight: 700;
        }

        .display-lg { font-size: 3.5rem; letter-spacing: -0.02em; }
        .headline-md { font-size: 1.75rem; }
        .body-md { font-size: 0.875rem; }
        .label-md { font-size: 0.75rem; color: var(--on-surface-variant); font-weight: 500; }

        .card {
            background-color: var(--surface-container-lowest);
            border-radius: 1.5rem;
            padding: 1.4rem;
            box-shadow: 0px 20px 40px rgba(190, 0, 76, 0.03);
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--on-primary);
            border-radius: 1.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: transform 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: scale(1.02);
        }

        .input-field {
            background-color: var(--surface-container-highest);
            border: 1px solid var(--outline-variant);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(190, 0, 76, 0.1);
        }

        /* Hide original selects to avoid flash or double display */
        .select2 {
            display: none;
        }

        .select2-hidden-accessible {
            border: 0 !important;
            clip: rect(0 0 0 0) !important;
            height: 1px !important;
            margin: -1px !important;
            overflow: hidden !important;
            padding: 0 !important;
            position: absolute !important;
            width: 1px !important;
        }

        /* Select2 Custom Theme */
        .select2-container {
            width: 100% !important;
            display: block;
        }

        .select2-container--default .select2-selection--single {
            background-color: var(--surface-container-highest);
            border: 1px solid var(--outline-variant);
            border-radius: 0.75rem;
            height: 48px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .select2-container--default .select2-selection--single:focus {
            outline: none;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(190, 0, 76, 0.1);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--on-background);
            padding: 0;
            font-size: 0.875rem;
            font-weight: 500;
            margin-right: 20px;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--on-surface-variant);
            opacity: 0.6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
            right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--on-surface-variant) transparent transparent transparent;
            border-width: 5px 4px 0 4px;
            margin-left: 0;
            static: relative;
            top: auto;
            left: auto;
        }

        .select2-dropdown {
            background-color: var(--surface-container-lowest);
            border: 1px solid var(--outline-variant);
            border-radius: 1rem;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 4px;
            z-index: 1000;
            animation: select2-dropdown-fade 0.2s ease-out;
        }

        @keyframes select2-dropdown-fade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .select2-results__option {
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            color: var(--on-background);
            transition: background 0.1s;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--secondary-container);
            color: var(--primary);
            font-weight: 600;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--primary);
            color: var(--on-primary);
        }

        .select2-search--dropdown {
            padding: 0.75rem;
        }

        .select2-search--dropdown .select2-search__field {
            background-color: var(--surface-container-low);
            border: 1px solid var(--outline-variant);
            border-radius: 0.75rem;
            padding: 0.6rem 1rem;
            width: 100%;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary);
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(190, 0, 76, 0.05);
        }
    </style>
</head>
<body class="antialiased h-full">
    @yield('body')

    <!-- jQuery & Select2 Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
</body>
</html>
