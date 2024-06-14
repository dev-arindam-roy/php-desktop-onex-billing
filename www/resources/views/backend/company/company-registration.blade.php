@extends('backend.layout.app')

@section('page_header', 'Company Registration')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.registration') }}">Company Registration</a></li>
    <li class="breadcrumb-item active">Company Information</li>
@endsection

@section('content_title', 'Company Registration')
@section('content_buttons')
    
@endsection

@section('content_body')
<form name="frmx" id="frmx" action="{{ route('company.registration.save') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="companyName">Company Name: <em>*</em></label>
                <input type="text" name="company_name" id="companyName" class="form-control" placeholder="Company Name" required="required" value="{{ !empty($company_information->company_name) ? $company_information->company_name : '' }}" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="companyType">Company Type: <em>*</em></label>
                <select name="company_type" id="companyType" class="form-control onex-select2" required="required">
                    <option value=""></option>
                    @if(!empty($company_categories))
                        @foreach($company_categories as $k => $v)
                            <option value="{{ $v->id }}" @if(!empty($company_information->company_type) && $v->id == $company_information->company_type) selected="selected" @endif>{{ $v->name }} @if(!empty($v->short_code))({{ $v->short_code }})@endif</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="brandName">Brand Name: <em>*</em></label>
                <input type="text" name="brand_name" id="brandName" class="form-control" placeholder="Brand Name" required="required" value="{{ !empty($company_information->brand_name) ? $company_information->brand_name : '' }}" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <blockquote>
                <p>Registration Information</p>
            </blockquote>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="cinNo">CIN No:</label>
                <input type="text" name="cin_no" id="cinNo" class="form-control" placeholder="CIN No" value="{{ !empty($company_information->cin_no) ? $company_information->cin_no : '' }}" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="panNo">PAN No:</label>
                <input type="text" name="pan_no" id="panNo" class="form-control" placeholder="PAN No" value="{{ !empty($company_information->pan_no) ? $company_information->pan_no : '' }}" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="tanNo">TAN No:</label>
                <input type="text" name="tan_no" id="tanNo" class="form-control" placeholder="TAN No" value="{{ !empty($company_information->tan_no) ? $company_information->tan_no : '' }}" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <blockquote>
                <p>Tax Information</p>
            </blockquote>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="gstNo">GST No:</label>
                <input type="text" name="gst_no" id="gstNo" class="form-control" placeholder="GST No" value="{{ !empty($company_information->gst_no) ? $company_information->gst_no : '' }}" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="vatNo">VAT No:</label>
                <input type="text" name="vat_no" id="vatNo" class="form-control" placeholder="VAT No" value="{{ !empty($company_information->vat_no) ? $company_information->vat_no : '' }}" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <blockquote>
                <p>Contact Information</p>
            </blockquote>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="contactNo">Contact No:</label>
                <input type="text" name="contact_number" id="contactNo" class="form-control" placeholder="Contact No" value="{{ !empty($company_information->contact_number) ? $company_information->contact_number : '' }}" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="whatsAppNo">WhatsApp No:</label>
                <input type="text" name="whatsapp_number" id="whatsAppNo" class="form-control" placeholder="WhatsApp Number" value="{{ !empty($company_information->whatsapp_number) ? $company_information->whatsapp_number : '' }}" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="contactEmail">Contact Email:</label>
                <input type="email" name="contact_email" id="contactEmail" class="form-control" placeholder="Contact Email" value="{{ !empty($company_information->contact_email) ? $company_information->contact_email : '' }}" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="websiteUrl">Website URL/Link:</label>
                <input type="url" name="website_url" id="websiteUrl" class="form-control" placeholder="Website Link" value="{{ !empty($company_information->website_url) ? $company_information->website_url : '' }}" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <blockquote>
                <p>Address Information</p>
            </blockquote>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="fullAddress">Registered Address (Full):</label>
                <textarea name="full_address" id="fullAddress" class="form-control" placeholder="Full Address">{{ !empty($company_information->full_address) ? $company_information->full_address : '' }}</textarea>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ !empty($company_information->state) ? $company_information->state : '' }}" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ !empty($company_information->city) ? $company_information->city : '' }}" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="pincode">Pincode:</label>
                <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Pincode" value="{{ !empty($company_information->pincode) ? $company_information->pincode : '' }}" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="landMark">Land Mark:</label>
                <input type="text" name="land_mark" id="landMark" class="form-control" placeholder="Land Mark" value="{{ !empty($company_information->land_mark) ? $company_information->land_mark : '' }}" />
            </div>
        </div>
    </div>
</form>

<form name="delete-frm" id="frmxDel" action="{{ route('company.registration.delete') }}" method="POST">
@csrf
<input type="hidden" name="company_id" @if(!empty($company_information->id)) value="{{ $company_information->id }}" @endif />
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="addBtn"><i class="fas fa-save"></i> Save</button>
        <button type="button" class="btn btn-danger" id="delBtn" @if(empty($company_information)) disabled @endif><i class="fas fa-trash-alt"></i> Delete</button>
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
        rules: {
            company_name: {
                required: true
            },
            company_type: {
                required: true
            },
            brand_name: {
                required: true
            },
            contact_number: {
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            whatsapp_number: {
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            contact_email: {
                email: true
            },
            website_url: {
                url: true
            },
            pincode: {
                digits: true,
                maxlength: 8,
                minlength: 6
            }
        },
        messages: {
            company_name: {
                required: 'Please enter company name'
            },
            company_type: {
                required: 'Please select company type'
            },
            brand_name: {
                required: 'Please enter brand name'
            },
            contact_number: {
                digits: 'Only numbers allowed',
                maxlength: 'Maximum 10 digits',
                minlength: 'Minimum 10 digitis'
            },
            whatsapp_number: {
                digits: 'Only numbers allowed',
                maxlength: 'Maximum 10 digits',
                minlength: 'Minimum 10 digitis'
            },
            contact_email: {
                email: 'Please enter valid email'
            },
            website_url: {
                url: 'Please enter valid website address'
            },
            pincode: {
                digits: 'Only numbers allowed',
                maxlength: 'Maximum 8 digits',
                minlength: 'Minimum 6 digitis'
            }
        },
        errorPlacement: function (error, element) {
            if(element.hasClass('onex-select2')) {
                error.insertAfter(element.parent().find('span.select2-container'));
            } else {
                error.insertAfter(element);
            }
        }
    });
    $('body').on('select2:select', '.onex-select2', function (e) { 
        if($(this).val() != '') {
            $('#' + $(this).attr('id') + '-error').hide();
            $(this).next('span.select2-container').removeClass('select2-custom-error');
            $(this).parent().find('.onex-form-lebel').removeClass('onex-error-label');
        }
    });
    $('#addBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#addBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
    $('#delBtn').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete these information",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed) {
                displayLoading();
                $('#delBtn').attr('disabled', 'disabled');
                $("#frmxDel").submit();
            }
        });
    });
});
</script>
@endpush