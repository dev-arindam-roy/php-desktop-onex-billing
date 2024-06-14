
<section class="content">
    <div class="card">
    <div class="card-header">
        <h3 class="card-title">@yield('content_title')</h3>
        <div class="card-tools">
            @yield('content_buttons')
        </div>
    </div>
    <div class="card-body" style="min-height: 300px;">
        @yield('content_body')
    </div>
    <div class="card-footer">
        @yield('content_footer')
    </div>
    </div>
</section>
    