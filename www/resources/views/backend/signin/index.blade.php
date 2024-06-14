<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STOCK-LINE</title>
    <link rel="icon" type="image/png" href="{{ asset('public/images/favicon-32.png') }}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/toastr/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('public') }}/master-assets/style-fix.css">
    <style>
      .signin-logo {
        width: 60px;
        margin-top: -8px;
      }
      a.h1:hover {
        color: #000;
      }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <div class="text-center">
        <img src="{{ asset('public') }}/images/logo-sigin-256.png" alt="{{ $defaultShareData['crm']->name }}" class="signin-logo">
      </div>
      <a href="javascript:void(0);" class="h1" style="font-size: 32px;"><b>{{ $defaultShareData['crm']->name }}</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">System User - SignIn</p>
      <form name="frm" id="frmx" action="{{ route('signin.signin') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="text" name="login_id" id="loginId" required="required" class="form-control" placeholder="Email/Mobile/User Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="login_password" id="loginPassword" required="required" class="form-control" placeholder="Account Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">Login To System</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!--div class="text-center mt-3"><a href="login.html" class="text-center">I want to create Account</a></div>
      <div class="text-center mt-1"><a href="login.html" class="text-center">Forgot Password?</a></div-->
    </div>
  </div>
</div>
<script src="{{ asset('public') }}/master-assets/jquery/jquery.min.js"></script>
<script src="{{ asset('public') }}/master-assets/toastr/toastr.min.js"></script>
<script>
$(document).ready(function(){
	toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $('#SmoothScrollToTopBtN').on('click', function () {
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 200) {
            $('#SmoothScrollToTopBtN').fadeIn();
        } else {
            $('#SmoothScrollToTopBtN').fadeOut();
        }
    });
});
</script>
@php
    $toastrType = '';
    $toastrTitle = '';
	$toastrMessage = '';
	if(Session::has('message_type') && Session::has('message_text') && Session::has('message_title')) {
        $toastrType = Session::get('message_type');
        $toastrTitle = Session::get('message_title');
		$toastrMessage = Session::get('message_text');
	}
@endphp
@if($toastrType == 'error')
<script>
$(document).ready(function(){
	toastr.error('{{ $toastrMessage }}', '{{ $toastrTitle }}');
});
</script>
@endif
@if($toastrType == 'success')
<script>
$(document).ready(function(){
	toastr.success('{{ $toastrMessage }}', '{{ $toastrTitle }}');
});
</script>
@endif
</body>
</html>
