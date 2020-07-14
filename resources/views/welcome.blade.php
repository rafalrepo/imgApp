<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Muli:ital,wght@0,500;0,700;1,300&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <style>
        body{
            background-color: #4f4f4f;
            color: #ffffff;
            font-weight: bold
        }

        .img__container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .img__box {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .img__box img{
            display: block;
            width: 90%;
            height: auto;
            overflow: hidden;
            margin: auto
        }

        .img__box input{
            position: absolute;
            width: 1px;
            height: 1px;
        }

        .check__img{
            border: 4px solid rgba(240, 52, 52, 1);
        }

        .output__form button{
            width: 100%;
            margin: 20px auto;
        }

    </style>

</head>

<body>
    <div class="container">
        @yield('content')
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @yield('scripts')
</body>

</html>
