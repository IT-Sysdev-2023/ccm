<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('ccmlogo/lgremove.png')}}" type="image/png">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('/css/font.css')}}">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="antialiased" >
    @inertia
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Fira+Sans&family=Oswald&family=Poppins&family=Roboto+Condensed:wght@300&display=swap'); */

        * {

            font-family: 'Fira Sans', sans-serif;
            text-decoration: none;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        .btn-submit {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 12px;
            /* Rounded corners */
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            /* Box shadow */
            transition-duration: 0.4s;
        }

        .btn-submit:hover {
            background-color: #45a049;
            /* Darker green on hover */
            box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
</body>

</html>
