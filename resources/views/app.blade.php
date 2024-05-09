<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="alwaysmeo" />
    <meta name="keywords" content="{{ config('app.name') }}" />
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="generator" content="laravel-vite-vue3" />
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta name="copyright" content="{{ config('app.name') }}" />
    <meta name="application-name" content="{{ config('app.name') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
    <div id="app"></div>
    @vite('resources/app/main.js')
</body>
</html>
