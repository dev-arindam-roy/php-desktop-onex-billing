@extends('backend.layout.app')

@section('page_header', 'CRM Settings')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">CRM Settings</li>
@endsection

@section('content_title', 'CRM Settings')

@section('content_body')
<form name="frm" id="frmx" action="{{ route('crm.save') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="themeName" class="onex-form-label">CRM Name: <em>*</em></label>
            <input type="text" name="name" id="crmName" class="form-control" placeholder="Enter CRM Name" maxlength="20" required="required" value="{{ $crm_settings->name }}" />
        </div>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-6"></div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="displayPerPage" class="onex-form-label">List Per Page: <em>*</em></label>
            <input type="number" name="list_per_page" id="displayPerPage" class="form-control" placeholder="Enter Number" min="25" max="500" required="required" value="{{ !empty($crm_settings->list_per_page) ? $crm_settings->list_per_page : 25 }}" />
        </div>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-6"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="crmSaveChanges"><i class="fas fa-save"></i> Save Changes</button>
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
            name: {
                required: true,
                maxlength: 20
            },
            list_per_page: {
                required: true,
                min: 25,
                max: 500
            }
        },
        messages: {
            name: {
                required: 'Please enter crm name',
                maxlength: 'Maximum 20 chars accepted'
            },
            list_per_page: {
                required: 'Please enter a number',
                min: 'Minimum is 25',
                max: 'Maximum is 500'
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
    $('#crmSaveChanges').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#crmSaveChanges').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
</script>
@endpush