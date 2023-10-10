<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss'])
    <title>Мой склад</title>
</head>
<body>
<header class="navbar navbar-dark bg-dark p-2">
    <a class="navbar-brand" href="/">Мой склад</a>
    @if (Session::get('login'))
        <div class="btn-group float-end" role="group">
            <button type="button" class="btn text-white text-end dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                {{ Session::get('login') }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{route('logout.index')}}">Выход</a></li>
            </ul>
        </div>
    @endif
</header>
<main role="main" class="container">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= $error ?>
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif

    @yield('content')

</main>

@vite(['resources/js/app.js'])
</body>
</html>
