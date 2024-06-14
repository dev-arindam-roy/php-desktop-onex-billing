@extends('backend.layout.app')

@section('page_header', 'Account Settings')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Account Settings</li>
@endsection

@section('content_title', 'Reset User Password')

@section('content_buttons')
    <a href="{{ route('user.edit', array('id' => $user->hash_id)) }}" class="btn btn-success btn-sm"><i class="far fa-user"></i> Edit Account</a>
    <a href="{{ route('user.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> All Users</a>
@endsection

@section('content_body')

@if($user->is_crm_access == 1)
<form name="frm" id="frmx" action="{{ route('user.resetSavePassword', array('id' => $user->hash_id)) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="password" class="onex-form-label">New Password: <em>*</em></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required="required" autocomplete="new-password"/>
        </div>
    </div>
    <div class="col-md-8"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="confirmPassword" class="onex-form-label">Confirm Password: <em>*</em></label>
            <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Enter confirm Password" required="required" autocomplete="new-password"/>
        </div>
    </div>
    <div class="col-md-8"></div>
</div>
</form>
@else
<div class="row">
    <div class="col-md-8">
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Oops!</h5>
            Sorry! Reset password not applicable for this user as the CRM access is disabled already.
            <br/> Please enable the CRM access settings from the edit account section.
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
@endif
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        @if($user->is_crm_access == 1)
            <button type="button" class="btn btn-success" id="saveChangesBtn"><i class="fas fa-key"></i> Reset Password</button>
        @endif
    </div>
    <div class="col-md-6"></div>
</div>
@endsection

@push('page_script')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
@endpush

@push('page_js')
<script>
$(document).ready(function() {
    $("#frmx").validate({
        errorClass: 'onex-error',
        errorElement: 'div',
        ignore: '.ignore',
        rules: {
            password: {
                required: true,
                minlength: 8,
                maxlength: 16
            },
            confirm_password: {
                required: true,
                equalTo: '#password'
            }
        },
        messages: {
            password: {
                required: 'Please enter new password',
                minlength: 'Minimum 8 chars require',
                maxlength: 'Maximum 16 chars accepted'
            },
            confirm_password: {
                required: 'Please enter confirm password',
                equalTo: 'Confirm password not match with password'
            }
        }
    });
    $('#saveChangesBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#saveChangesBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
</script>
@endpush