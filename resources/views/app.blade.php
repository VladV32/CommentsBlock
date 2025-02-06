<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{env('APP_NAME', 'Laravel')}}</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>