<nav class="@if(!empty($defaultShareData['theme']) && !empty($defaultShareData['theme']->nav_class)){{ $defaultShareData['theme']->nav_class }} @else main-header navbar navbar-expand navbar-white navbar-light dropdown-legacy @endif">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    {{-- @include('backend.layout.includes.left-header-nav-menu') --}}    
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        {{-- @include('backend.layout.includes.header-nav-search') --}}
        {{-- @include('backend.layout.includes.header-nav-message-list') --}}
        {{-- @include('backend.layout.includes.header-nav-notification-list') --}}
        @include('backend.layout.includes.header-nav-logged-in-user')
        {{-- @include('backend.layout.includes.header-nav-language-flag') --}}
        {{-- @include('backend.layout.includes.header-nav-fullscreen-button') --}}
        {{-- @include('backend.layout.includes.header-nav-right-sidebar-button') --}}
    </ul>
</nav>
<!-- /.navbar -->