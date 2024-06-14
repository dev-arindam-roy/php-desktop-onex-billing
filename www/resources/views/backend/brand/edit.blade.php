@extends('backend.layout.app')

@section('page_header', 'Brand Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('brand.index') }}">Brand Management</a></li>
    <li class="breadcrumb-item active">Edit Brand</li>
@endsection

@section('content_title', 'Edit Brand')
@section('content_buttons')
    <a href="{{ route('brand.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-tags"></i> All Brands</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('brand.update', array('id' => $brand->id)) }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="brandName" class="onex-form-label">Brand Name: <em>*</em></label>
            <input type="text" name="name" id="brandName" class="form-control" placeholder="Enter Brand Name" required="required" value="{{ $brand->name }}"/>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="brandStatus" class="onex-form-label">Status: <em>*</em></label>
            <select name="status" class="form-control" id="brandStatus">
                <option value="1" @if($brand->status == 1) selected="selected" @endif>Active</option>
                <option value="0" @if($brand->status == 0) selected="selected" @endif>Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-md-6"></div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="brandDescription" class="onex-form-label">Brand Description: </label>
            <textarea name="description" id="brandDescription" class="form-control" placeholder="Enter Brand Description..."/>{{ $brand->description }}</textarea>
        </div>
    </div>
    <div class="col-md-6"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="updateBtn"><i class="fas fa-save"></i> Save Changes</button>
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
                maxlength: 60
            }
        },
        messages: {
            name: {
                required: 'Please enter brand name',
                maxlength: 'Maximum 60 chars accepted'
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
    $('#updateBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#updateBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
</script>
@endpush