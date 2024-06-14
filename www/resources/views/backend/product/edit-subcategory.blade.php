@extends('backend.layout.app')

@section('page_header', 'Product Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">All Products</a></li>
    <li class="breadcrumb-item active">Edit Product Sub-Category</li>
@endsection

@section('content_title', 'Edit Product Sub-Category')
@section('content_buttons')
    <a href="{{ route('product.subcategory.allSubCategories') }}" class="btn btn-primary btn-sm"><i class="fas fa-list"></i> All Product Sub-Categories</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('product.subcategory.updateSubCategory', array('id' => $data->id)) }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="categoryId" class="onex-form-label">Select Category: <em>*</em></label>
            <select name="category_id" class="form-control onex-select2" id="categoryId">
                <option></option>
                @if(!empty($all_categories) && count($all_categories))
                    @foreach($all_categories as $v)
                    <option value="{{ $v->id }}" @if($v->id == $data->category_id) selected="selected" @endif>{{ $v->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-7"></div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="subCategoryName" class="onex-form-label">Product Sub-Category Name: <em>*</em></label>
            <input type="text" name="name" id="subCategoryName" class="form-control" placeholder="Enter Category Name" required="required" value="{{ $data->name }}"/>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="subCategoryStatus" class="onex-form-label">Status: <em>*</em></label>
            <select name="status" class="form-control" id="subCategoryStatus">
                <option value="1" @if($data->status == 1) selected="selected" @endif>Active</option>
                <option value="0" @if($data->status == 0) selected="selected" @endif>Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="subCategoryDescription" class="onex-form-label">Sub-Category Description: </label>
            <textarea name="description" id="subCategoryDescription" class="form-control" placeholder="Enter Sub-Category Description..."/>{{ $data->description }}</textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="frmActionBtn"><i class="fas fa-save"></i> Save Changes</button>
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
            category_id: {
                required: true,
                digits: true
            },
            name: {
                required: true,
                maxlength: 30
            }
        },
        messages: {
            category_id: {
                required: 'Please select a category',
                digits: 'Invalid category'
            },
            name: {
                required: 'Please enter sub-category name',
                maxlength: 'Maximum 30 chars accepted'
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
    $('#frmActionBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#frmActionBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
</script>
@endpush