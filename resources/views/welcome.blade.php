<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Blog</title>
        <link type="stylesheet" href="/css/all.css">

    </head>
    <body class="antialiased">
       <div id="app">
           <mainapp></mainapp>
       </div>
    </body>
    <script src="{{ mix('/js/app.js') }}"></script>
</html>
