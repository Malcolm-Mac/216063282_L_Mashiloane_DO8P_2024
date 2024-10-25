<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script> --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <style>
        body {
            color: black;
            overflow: auto;
            background: linear-gradient(135deg, #e0f7fa, #e0e0e0);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        /* .wrapper {
            max-width: 100%;
            min-height: 500px;
            margin: 80px auto;
            padding: 50px 40px;
            background-color: #2c2f33;
            border-radius: 15px;
            box-shadow:
                10px 10px 15px rgba(0, 0, 0, 0.6),
                -10px -10px 15px rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        } */

        .wrapper {
            max-width: 100%;
            min-height: 443px;
            margin: 80px auto;
            padding: 50px 40px;
            background-color: #ecf0f3;
            border-radius: 15px;
            box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .form-control {
            border-radius: 0.2rem !important;
        }

        .form-control::placeholder {
            font-size: 0.89rem;
            color: #6c757d;
        }

        .btn-bg-color {
            background-color: #77DCF5;
            color: white;
        }

        .btn-bg-color:hover {
            background-color: #50b6d0;
        }

        @media(max-width: 380px) {
            .wrapper {
                margin: 30px 20px;
                padding: 40px 15px 15px 15px;
            }
        }
    </style>
</head>

<body>
    <div id="app" class="position-relative">
        <div style="mix-blend-mode: multiply; left:35%!important;" class="position-absolute top-50 start-25 translate-middle">
            <img src="https://res.cloudinary.com/droskhnig/image/upload/v1728817734/Benchmark_pzzjb7.png"
                alt="logo" class="img-fluid w-50" />
        </div>
        <main class="py-4">
            @yield('content')
        </main>
        <div style="mix-blend-mode: multiply; left:80%!important;" class="position-absolute top-50 start-25 translate-middle">
            <img src="https://res.cloudinary.com/droskhnig/image/upload/v1728817310/blue-black-abstract-honeycomb-logo-icon-hexagons_95164-3552_ewwg8u.avif"
                alt="logo" class="img-fluid w-100" />
        </div>
    </div>
</body>

</html>
