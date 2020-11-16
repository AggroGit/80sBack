<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @if(!isset($noTypeScript))
      <script src="{{ asset('js/app.js') }}" defer></script>
    @else
    @endif

    @yield('cssExtra')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<footer>
  Al navegar aceptas el uso de Cookies, nuestra política de privacidad y nuestros términos de uso. 
  <ul>
    <li>
      <a href="{{url('legal/privacidad.pdf')}}">Política de privacidad</a>
    </li>
    <li>
      <a href="{{url('legal/politica-cookies.pdf')}}">Política de uso de Cookies</a>
    </li>
    <li>
      <a href="{{url('legal/aviso-legal.pdf')}}">Aviso legal</a>
  </ul>
</footer>
</html>
