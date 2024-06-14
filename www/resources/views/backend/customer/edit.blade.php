@extends('backend.layout.app')

@section('page_header', 'Customer Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer Management</a></li>
    <li class="breadcrumb-item active">Edit Customer</li>
@endsection

@section('content_title', 'Edit Customer')
@section('content_buttons')
    <a href="{{ route('vendor.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> All Customers</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('customer.update', array('id' => $user->id)) }}" method="POST">
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
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-12">
        <blockquote>
            <p><strong>Address Information</strong></p>
        </blockquote>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="userAddress" class="onex-form-label">Full Address:</label>
            <textarea name="full_address" id="userAddress" class="form-control" autocomplete="false" placeholder="Full Address">{{ !empty($user->userProfile->full_address) ? $user->userProfile->full_address : '' }}</textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="userPincode" class="onex-form-label">Pincode:</label>
            <input type="text" maxlength="10" name="pincode" id="userPincode" class="form-control" placeholder="Pincode" value="{{ !empty($user->userProfile->pincode) ? $user->userProfile->pincode : '' }}"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userCity" class="onex-form-label">City:</label>
            <input type="text" name="city" id="userCity" class="form-control" placeholder="City" value="{{ !empty($user->userProfile->city) ? $user->userProfile->city : '' }}"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userState" class="onex-form-label">State:</label>
            <input type="text" name="state" id="userState" class="form-control" placeholder="State" value="{{ !empty($user->userProfile->state) ? $user->userProfile->state : '' }}"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userCountry" class="onex-form-label">Country:</label>
            <input type="text" name="country" id="userCountry" class="form-control" placeholder="Country" value="{{ !empty($user->userProfile->country) ? $user->userProfile->country : 'IND' }}"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="userLandmark" class="onex-form-label">Address Landmark: </label>
            <input type="text" name="land_mark" id="userLandmark" class="form-control" placeholder="Landmark" value="{{ !empty($user->userProfile->land_mark) ? $user->userProfile->land_mark : '' }}" />
        </div>
    </div>
    <div class="col-md-4"></div>
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
});
</script>
@endpush