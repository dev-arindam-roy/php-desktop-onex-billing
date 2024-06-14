@extends('backend.layout.app')

@section('page_header', 'Customer Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer Management</a></li>
    <li class="breadcrumb-item active">Add Customer</li>
@endsection

@section('content_title', 'Add New Customer')

@section('content_buttons')
    <a href="{{ route('customer.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> All Customers</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('customer.save') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="firstName" class="onex-form-label">First Name: <em>*</em></label>
            <input type="text" name="first_name" id="firstName" class="form-control" placeholder="Enter First Name" required="required"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="lastName" class="onex-form-label">Last Name: <em>*</em></label>
            <input type="text" name="last_name" id="lastName" class="form-control" placeholder="Enter Last Name" required="required"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="phoneNumber" class="onex-form-label">Phone Number: <em>*</em></label>
            <input type="number" name="phone_number" id="phoneNumber" class="form-control" placeholder="Enter Mobile Number" required="required" autocomplete="new-phone-number"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="whatsappNumber" class="onex-form-label">Whatsapp Number:</label>
            <input type="number" name="whatsapp_number" id="whatsappNumber" class="form-control" placeholder="Enter Whatsapp Number"/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="emailId" class="onex-form-label">Email Id: <em>*</em></label>
            <input type="email" name="email_id" id="emailId" class="form-control" placeholder="Enter Email Id" required="required" autocomplete="new-email"/>
        </div>
    </div>
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
            <textarea name="full_address" id="userAddress" class="form-control" autocomplete="false" placeholder="Full Address"></textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="userPincode" class="onex-form-label">Pincode:</label>
            <input type="text" maxlength="10" name="pincode" id="userPincode" class="form-control" placeholder="Pincode"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userCity" class="onex-form-label">City:</label>
            <input type="text" name="city" id="userCity" class="form-control" placeholder="City"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userState" class="onex-form-label">State:</label>
            <input type="text" name="state" id="userState" class="form-control" placeholder="State"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="userCountry" class="onex-form-label">Country:</label>
            <input type="text" name="country" id="userCountry" class="form-control" placeholder="Country" value="IND"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="userLandmark" class="onex-form-label">Address Landmark: </label>
            <input type="text" name="land_mark" id="userLandmark" class="form-control" placeholder="Landmark" />
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<input type="hidden" name="user_category" id="userCategory" value="5"/>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="addUserBtn"><i class="fas fa-plus"></i> Add Customer</button>
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
    $('#addUserBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#addUserBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
    $('input[name="phone_number"]').on('blur', function() {
        if($(this).valid()) {
            $('input[name="whatsapp_number"]').val($(this).val());
        }
    });
});
</script>
@endpush