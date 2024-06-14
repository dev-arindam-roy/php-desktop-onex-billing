<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STOCK-LINE</title>
    <link rel="icon" type="image/png" href="{{ asset('public/images/favicon-32.png') }}"/>
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/pace-progress/themes/black/pace-theme-minimal.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/css/adminlte.min.css">
    @if(!empty($defaultShareData['theme']) && !empty($defaultShareData['theme']->css_style))
    <style type="text/css">{!! html_entity_decode($defaultShareData['theme']->css_style, ENT_QUOTES) !!}</style>
    @endif
    @stack('page_style')
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/style-fix.css">
    @stack('page_css')
</head>
<body class="pace-primary">
<div class="container mt-5">
@yield('content_body')
</div>
<script src="{{ asset('public') }}/master-assets/jquery/jquery.min.js"></script>
<script src="{{ asset('public') }}/master-assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('public') }}/master-assets/pace-progress/pace.min.js"></script>
@stack('page_script')
<script src="{{ asset('public') }}/master-assets/script-fix.js"></script>
@stack('page_js')
</body>
</html>
