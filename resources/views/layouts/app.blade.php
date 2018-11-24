<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Trip :: @section('title') @show</title>
    @section('meta_keywords')
        <meta name="keywords" content="animation, animator, director, creator, designer"/>
    @show @section('meta_author')
        <meta name="author" content="Brian Etheridge"/>
    @show @section('meta_description')
        <meta name="description" content="Tripulator"/>
    @show
        <meta property="og:title" content="Trip">
        <meta property="og:image" content="http://www.squaresquare.tv/public/img/thumbs/bathroomBoarder_hv.jpg">
        <meta property="og:description" content="It's a journey">

		<link href="{{ asset('css/site.css') }}" rel="stylesheet">
        <script src="{{ asset('js/site.js?v3') }}"></script>

    @yield('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="http://www.squaresquare.tv/favicon.ico" type="image/x-icon">
    <link rel="icon" href="http://www.squaresquare.tv/favicon.ico" type="image/x-icon">
</head>
<body>


<div class="wrapper">

    @if (!Auth::guest())
        @include('partials.nav')
    @endif

    @include('partials.header')

    <div class="container-fluid">

        @yield('content')

    </div>

    <div class="row footer-row-container">
        <div style="text-align: center;padding-top:80px;">&copy; 2018 Brian Etheridge</div>
    </div>

</div>

@yield('global-scripts')
@yield('page-scripts')

</body>
</html>