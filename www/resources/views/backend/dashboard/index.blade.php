@extends('backend.layout.appx')

@push('page_style')

@endpush

@section('page_header', 'Dashdoard Analytics')
@section('page_breadcrumb')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Dashdoard Analytics</li>
@endsection

@section('content_body')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="callout callout-info">
                  <h5>Dashboard Will Coming Soon!</h5>
                  <p>Hi, {{ auth()->user()->first_name }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('page_script')

@endpush

@push('page_js')
<script>
    $(document).ready(function() {
        
    });
</script>
@endpush
