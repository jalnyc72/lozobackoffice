<!DOCTYPE html>
<html lang="{{ Lang::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="robots" content="NOINDEX, NOFOLLOW">
    <link rel="shortcut icon" href="{{ config("$contextKey.favicon_url", '/vendor/backoffice/images/favicon.png') }}" type="image/png">

    <title>@yield('head.title', trans('backoffice::default.backoffice'))</title>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/vendor/backoffice/js/ie8compat.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="/vendor/backoffice/css/backoffice.css">
    @yield('head.stylesheets')
    @yield('head.javascripts')
</head>
<body class="@yield('body.class')">
<section>
    @yield('body.content')
</section>
@foreach(['info', 'warning', 'danger', 'success', 'primary'] as $message)
    @if(Session::has($message))
        <div class="shoutMe" data-title="{{ trans('backoffice::default.message') }}"
             data-class_name="growl-{{ $message }}">{!! Session::remove($message) !!}</div>
    @endif
@endforeach
<script type="text/javascript">var _CSRF_TOKEN = '{{ csrf_token() }}';</script>
<script type="text/javascript" src="/vendor/backoffice/js/backoffice.min.js"></script>
@yield('body.javascripts')
@if(app()->isLocale('es'))
	<script type="text/javascript" src="{{ asset('/vendor/backoffice/js/jquery.ui.datepicker-es.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/vendor/backoffice/js/select2_locale_es.js') }}"></script>
@endif
</body>
</html>
