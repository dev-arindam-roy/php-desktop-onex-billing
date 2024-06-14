@extends('errors.layout')
@section('content_body')
<div class="row">
    <div class="col-md-6 col-xl-6 offset-md-3 col-sm-12 col-xs-12 text-center">
        <h1 class="text-danger">429</h1>
        <h2><strong>Oops! Too Many Request</strong></h2>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6 col-xl-6 offset-md-3 col-sm-12 col-xs-12 text-center">
        <a href="{{ route('signin.index') }}" class="btn btn-danger">Get Me Out</a>
    </div>
</div>
@endsection