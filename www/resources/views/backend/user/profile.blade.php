@extends('backend.layout.app')

@section('page_header', 'My Account')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">My Profile</li>
@endsection

@section('content_title', 'My Profile')

@section('content_buttons')
    
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('myprofile.myProfileUpdate') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
@csrf
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
    <div class="col-md-4 text-right">
        <a href="{{ route('myprofile.changePassword') }}"><i class="fas fa-key"></i> Change Password?</a>
    </div>
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
            <label for="userName" class="onex-form-label">User Name: <em>*</em></label>
            <input type="text" name="user_name" id="userName" class="form-control" placeholder="Enter User Name" required="required" autocomplete="new-username" value="{{ $user->user_name }}"/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="profileImage">Profile Image:</label>
            <div class="custom-file">
                <input type="file" name="image" accept="image/*" class="custom-file-input" id="profileImage">
                <label class="custom-file-label" for="profileImage">Choose file</label>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
@if(!empty($user->userProfile) && !empty($user->userProfile->image))
<div class="row">
    <div class="col-md-3">
        <div class="onex-preview-imgbox">
            <image src="{{ asset('public/uploads/images/users/thumbnail/' . $user->userProfile->image) }}" class="img-thumbnail"/>
            <a href="javascript:void(0);" class="table-image-remove" title="Remove Image" 
                data-table-row-id="{{ $user->userProfile->id }}" 
                data-table-name="users_profile"
                data-table-field="image"><i class="fas fa-trash-alt text-danger"></i></a>
        </div>
    </div>
    <div class="col-md-9"></div>
</div>
@endif
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="userAddress" class="onex-form-label">Full Address:</label>
            <textarea name="full_address" id="userAddress" class="form-control" autocomplete="false">@if(!empty($user->userProfile)){{ $user->userProfile->full_address }}@endif</textarea>
            <input type="hidden" name="geo_address" id="geoAddress" value="@if(!empty($user->userProfile)){{ $user->userProfile->geo_address }}@endif"/>
            <input type="hidden" name="latitude" id="latitude" value="@if(!empty($user->userProfile)){{ $user->userProfile->latitude }}@endif"/>
            <input type="hidden" name="longitude" id="longitude" value="@if(!empty($user->userProfile)){{ $user->userProfile->longitude }}@endif"/> 
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="userPincode" class="onex-form-label">Pincode:</label>
            <input type="text" maxlength="10" name="pincode" id="userPincode" class="form-control" value="@if(!empty($user->userProfile)){{ $user->userProfile->pincode }}@endif"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userCity" class="onex-form-label">City:</label>
            <input type="text" name="city" id="userCity" class="form-control" value="@if(!empty($user->userProfile)){{ $user->userProfile->city }}@endif"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userState" class="onex-form-label">State:</label>
            <input type="text" name="state" id="userState" class="form-control" value="@if(!empty($user->userProfile)){{ $user->userProfile->state }}@endif"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userCountry" class="onex-form-label">Country:</label>
            <input type="text" name="country" id="userCountry" class="form-control" value="IND" value="@if(!empty($user->userProfile)){{ $user->userProfile->country }}@endif"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="userLandmark" class="onex-form-label">Address Landmark: </label>
            <input type="text" name="land_mark" id="userLandmark" class="form-control" value="@if(!empty($user->userProfile)){{ $user->userProfile->land_mark }}@endif">
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="saveChangesBtn"><i class="fas fa-save"></i> Save Changes</button>
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
            user_name: {
                required: true,
                minlength: 6,
                maxlength: 20,
                validUsername: true
            }
        },
        messages: {
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
            },
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