<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Page is not found</title>
        <link rel="stylesheet" href="{{ URL::asset('css/all.css') }}">

        <script>
            (
                function () {
                    window.Laravel = {
                        csrfToken: '{{ csrf_token() }}'
                    };
                }
            )();
        </script>
       

    </head>
    <body>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <h1 class="text-center">You don't have enuogh permission to access to this page.</h1>
                </div>
            </div>
        </div>
    </body>
</html>
