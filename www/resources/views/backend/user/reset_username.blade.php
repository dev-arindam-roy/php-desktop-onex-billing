@extends('backend.layout.app')

@section('page_header', 'Account Settings')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Account Settings</li>
@endsection

@section('content_title', 'Reset Login Username')

@section('content_buttons')
    <a href="{{ route('user.edit', array('id' => $user->hash_id)) }}" class="btn btn-success btn-sm"><i class="far fa-user"></i> Edit Account</a>
    <a href="{{ route('user.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> All Users</a>
@endsection

@section('content_body')

@if($user->is_crm_access == 1)
<form name="frm" id="frmx" action="{{ route('user.resetSaveUsername', array('id' => $user->hash_id)) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="userName" class="onex-form-label">User Name: <em>*</em></label>
            <input type="text" name="user_name" id="userName" class="form-control" placeholder="Enter User Name" required="required" autocomplete="new-username" value="{{ $user->user_name }}"/>
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
            Sorry! Reset or Update username not applicable for this user as the CRM access is disabled already.
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
            <button type="button" class="btn btn-success" id="saveChangesBtn"><i class="fas fa-key"></i> Update Username</button>
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
    $.validator.addMethod("validUsername", function (value, element) {
        return /^[a-zA-Z0-9_.-]+$/.test(value);
    }, "Please enter a valid username");
    $("#frmx").validate({
        errorClass: 'onex-error',
        errorElement: 'div',
        ignore: '.ignore',
        rules: {
            user_name: {
                required: true,
                minlength: 6,
                maxlength: 20,
                validUsername: true
            }
        },
        messages: {
            user_name: {
                required: 'Please enter new username',
                minlength: 'Minimum 6 chars require',
                maxlength: 'Maximum 20 chars accepted'
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