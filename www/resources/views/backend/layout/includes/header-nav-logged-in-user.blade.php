@php
$user = Auth::user();
$profileImage = asset('public') . '/images/dummy-user-160.png';
if(!empty($user->userProfile) && !empty($user->userProfile->image)) {
    $profileImage = asset('public/uploads/images/users/thumbnail/' . $user->userProfile->image);
} 
@endphp
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <img src="{{ $profileImage }}" class="user-image img-circle elevation-2" alt="User Image">
        <span class="d-none d-md-inline">{{ Auth::user()->first_name }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <li class="user-header @if(!empty($defaultShareData['theme']) && !empty($defaultShareData['theme']->bg_class)){{ $defaultShareData['theme']->bg_class }} @else bg-primary @endif">
            <img src="{{ $profileImage }}" class="img-circle" alt="User Image">
            <p>
                {{ $user->first_name . ' ' . $user->last_name }} {{ !empty($user->user_name) ? ' - ' . $user->user_name : '' }}
                <small>{{ $user->email_id }}</small>
            </p>
        </li>
        <!--li class="user-body" style="border-bottom: 1px solid #dee2e6;">
            <div class="row">
                <div class="col-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </div>
        </li-->
        <li class="user-footer">
            <a href="{{ route('myprofile.myProfile') }}" class="btn btn-default btn-flat">Profile</a>
            <a href="{{ route('signin.logout') }}" class="btn btn-default btn-flat float-right">Sign out</a>
        </li>
    </ul>
</li>