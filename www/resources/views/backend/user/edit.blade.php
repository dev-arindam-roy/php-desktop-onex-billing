@extends('backend.layout.app')

@section('page_header', 'User Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User Management</a></li>
    <li class="breadcrumb-item active">Edit User</li>
@endsection

@section('content_title', 'Edit User')
@section('content_buttons')
    <a href="{{ route('user.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> All Users</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('user.update', array('id' => $user->id)) }}" method="POST">
@csrf

@php
    $editUserRoleIds = [];
    if (!empty($user->userRoles) && count($user->userRoles)) {
        foreach ($user->userRoles as $v) {
            array_push($editUserRoleIds, $v->role_id);
        }
    }
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="userRole" class="onex-form-label">User Role: <em>*</em></label>
            <select name="role_id" id="userRole" class="form-control onex-select2" required="required">
                @if(!empty($roles) && count($roles))
                    @foreach($roles as $k => $v)
                        <option value="{{ $v->id }}" @if(in_array($v->id, $editUserRoleIds)) selected="selected" @endif>{{ $v->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="firstName" class="onex-form-label">First Name: <em>*</em></label>
            <input type="text" name="first_name" id="firstName" class="form-control" placeholder="Enter First Name" required="required" value="{{ $user->first_name }}"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="lastName" class="onex-form-label">Last Name: <em>*</em></label>
            <input type="text" name="last_name" id="lastName" class="form-control" placeholder="Enter Last Name" required="required" value="{{ $user->last_name }}"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="phoneNumber" class="onex-form-label">Phone Number: <em>*</em></label>
            <input type="number" name="phone_number" id="phoneNumber" class="form-control" placeholder="Enter Mobile Number" required="required" autocomplete="new-phone-number" value="{{ $user->phone_number }}"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="whatsappNumber" class="onex-form-label">Whatsapp Number:</label>
            <input type="number" name="whatsapp_number" id="whatsappNumber" class="form-control" placeholder="Enter Whatsapp Number" value="{{ $user->whatsapp_number }}"/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="emailId" class="onex-form-label">Email Id: <em>*</em></label>
            <input type="email" name="email_id" id="emailId" class="form-control" placeholder="Enter Email Id" required="required" autocomplete="new-email" value="{{ $user->email_id }}"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="onex-form-label">System Access:</label>
            <div class="form-check">
                <input name="is_crm_access" id="isCrmAccess" class="form-check-input" type="checkbox" @if($user->is_crm_access == 1) checked="checked" @endif/>
                <label class="form-check-label" for="isCrmAccess">Is able to access the CRM?</label>
                <input type="hidden" name="crm_access_value" id="crmAccessValue" value="{{ $user->is_crm_access }}"/>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="updateUserBtn"><i class="fas fa-save"></i> Save Changes</button>
        <a href="javascript:void(0);" class="btn btn-danger btn-reload">Cancel</a>
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
            role_id: {
                required: true,
                digits: true
            },
            first_name: {
                required: true,
                maxlength: 30
            },
            last_name: {
                required: true,
                maxlength: 20
            },
            email_id: {
                required: true,
                email: true,
                maxlength: 60
            },
            phone_number: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            whatsapp_number: {
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            crm_access_value: {
                required: true,
                digits: true
            }
        },
        messages: {
            role_id: {
                required: 'Please select user role',
                digits: 'Invalid user role'
            },
            first_name: {
                required: 'Please enter first name',
                maxlength: 'Maximum 30 chars accepted'
            },
            last_name: {
                required: 'Please enter last name',
                maxlength: 'Maximum 20 chars accepted'
            },
            email_id: {
                required: 'Please enter email',
                email: 'Please enter valid email',
                maxlength: 'Maximum 60 chars accepted'
            },
            phone_number: {
                required: 'Please enter mobile number',
                digits: 'Please enter valid mobile number',
                maxlength: '10 digitis mobile number required',
                minlength: '10 digitis mobile number required'
            },
            whatsapp_number: {
                digits: 'Please enter valid mobile number',
                maxlength: '10 digitis mobile number required',
                minlength: '10 digitis mobile number required'
            }
        }
    });
    $('#updateUserBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#updateUserBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
    $('#isCrmAccess').on('change', function() {
        if($(this).is(':checked')) {
            $('#crmAccessValue').val(1);
        } else {
            $('#crmAccessValue').val(0);
        } 
    });
    $('#userRole').on('change', function() {
        if($(this).val() == '1' || $(this).val() == '2') {
            $('#isCrmAccess').prop('checked', true);
            $('#isCrmAccess').attr('checked');
            $('#isCrmAccess').attr('disabled', 'disabled');
        } else {
            $('#isCrmAccess').prop('checked', true);
            $('#isCrmAccess').attr('checked');
            $('#isCrmAccess').removeAttr('disabled');
        }
        $('#isCrmAccess').trigger('change');
    });
    if($('#userRole').val() == '1' || $('#userRole').val() == '2') {
        $('#isCrmAccess').prop('checked', true);
        $('#isCrmAccess').attr('checked');
        $('#isCrmAccess').attr('disabled', 'disabled');
        $('#isCrmAccess').trigger('change');
    }
});
</script>
@endpush