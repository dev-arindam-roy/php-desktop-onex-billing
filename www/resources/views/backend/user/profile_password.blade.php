@extends('backend.layout.app')

@section('page_header', 'Account Settings')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Change Password</li>
@endsection

@section('content_title', 'Change Password')

@section('content_buttons')
    
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('myprofile.changePasswordUpdate') }}" method="POST" autocomplete="off">
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
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="saveChangesBtn"><i class="fas fa-key"></i> Reset Password</button>
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