<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STOCK-LINE</title>
    <link rel="icon" type="image/png" href="{{ asset('public/images/favicon-32.png') }}"/>
    <style type="text/css">
        body {
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/pace-progress/themes/black/pace-theme-minimal.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/toastr/toastr.min.css">
    @if(!empty($defaultShareData['theme']) && !empty($defaultShareData['theme']->css_style))
    <style type="text/css">{!! html_entity_decode($defaultShareData['theme']->css_style, ENT_QUOTES) !!}</style>
    @endif
    @stack('page_style')
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/style-fix.css">
    @stack('page_css')
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed sidebar-mini-xs pace-primary">