@include('backend.layout.partials.header')
<!-- Site wrapper -->
<div class="wrapper">
  @include('backend.layout.partials.header-navbar')
  @include('backend.layout.partials.left-sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper text-sm">
    @include('backend.layout.partials.page-header')
    @yield('content_body')
    @include('backend.layout.includes.back-to-top')
  </div>
  <!-- /.content-wrapper -->
  @include('backend.layout.partials.footer-bar')
  @include('backend.layout.partials.right-sidebar')
</div>
<!-- ./wrapper -->
@include('backend.layout.partials.footer')
