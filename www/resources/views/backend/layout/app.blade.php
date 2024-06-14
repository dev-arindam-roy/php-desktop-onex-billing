@include('backend.layout.partials.header')
<!-- Site wrapper -->
<div class="wrapper" id="bodyHeight">
  @include('backend.layout.partials.header-navbar')
  @include('backend.layout.partials.left-sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper text-sm">
    @include('backend.layout.partials.page-header')
    @include('backend.layout.partials.page-content')
    @include('backend.layout.includes.back-to-top')
  </div>
  <!-- /.content-wrapper -->
  @include('backend.layout.partials.footer-bar')
  @include('backend.layout.partials.right-sidebar')
</div>
<!-- ./wrapper -->
@include('backend.layout.partials.footer')
