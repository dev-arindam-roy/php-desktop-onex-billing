@extends('backend.layout.app')

@push('page_style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('page_header', 'Product Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product Management</a></li>
    <li class="breadcrumb-item active">Add Product</li>
@endsection

@section('content_title', 'Add New Product')
@section('content_buttons')
    <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-cubes"></i> All Products</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('product.save') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="productName" class="onex-form-label">Product Name: <em>*</em></label>
            <input type="text" name="name" id="productName" class="form-control" placeholder="Enter Product Name" required="required"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="productStatus" class="onex-form-label">Status: <em>*</em></label>
            <select name="status" class="form-control" id="productStatus">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="categoryId" class="onex-form-label">Select Category: <em>*</em></label>
            <select name="category_id" class="form-control onex-select2" id="categoryId">
                <option></option>
                @if(!empty($all_categories) && count($all_categories))
                    @foreach($all_categories as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="subCategoryId" class="onex-form-label">Select Sub-Category: <em>*</em></label>
            <select name="subcategory_id" class="form-control onex-select2" id="subCategoryId">
                <option></option>
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="productImage">Product Image:</label>
            <div class="custom-file">
                <input type="file" name="image" accept="image/*" class="custom-file-input" id="productImage">
                <label class="custom-file-label" for="productImage">Choose file</label>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="productDescription" class="onex-form-label">Product Description: </label>
            <textarea name="description" id="productDescription" class="form-control" placeholder="Enter Product Description..."/></textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="addProductBtn"><i class="fas fa-plus"></i> Add Product</button>
    </div>
    <div class="col-md-6"></div>
</div>
@endsection

@push('page_script')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
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
            },
            status: {
                required: true,
                digits: true
            },
            category_id: {
                required: true,
                digits: true
            },
            subcategory_id: {
                required: true,
                digits: true
            }
        },
        messages: {
            name: {
                required: 'Please enter product name',
                maxlength: 'Maximum 60 chars accepted'
            },
            status: {
                required: 'Please select status',
                digits: 'Only number accepted'
            },
            category_id: {
                required: 'Please select category',
                digits: 'Invalid category'
            },
            subcategory_id: {
                required: 'Please select sub-category',
                digits: 'Invalid sub-category'
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
    $('#productDescription').summernote({
        placeholder: 'Enter Product Description...',
        tabsize: 2,
        height: 300
    });
    $('body').on('select2:select', '.onex-select2', function (e) { 
        if($(this).val() != '') {
            $('#' + $(this).attr('id') + '-error').hide();
            $(this).next('span.select2-container').removeClass('select2-custom-error');
            $(this).parent().find('.onex-form-lebel').removeClass('onex-error-label');
        }
    });
    $('#addProductBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#addProductBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        } else {
            displayAlert('error', 'Oops!', 'Please check all the required fields');
        }
    });
    $('#categoryId').on('change', function() {
        let catId = $(this).val();
        if(catId != '') {
            $.ajax({
                type: 'POST',
                url: "{{ route('product.category.getAllCategories') }}",
                data: {
                    'category_id': catId,
                    '_token': "{{ csrf_token() }}"
                },
                cache: false,
                beforeSend: function() {
                    displayLoading(10000, 'Please Wait...', 'System fetching sub-categories');
                },
                success: function(data) {
                    if(data && data.is_success && data.all_subcategories != '') {
                        let allSubCats = data.all_subcategories;
                        if(allSubCats.length) {
                            for(let i = 0; i < allSubCats.length; i++) {
                                let newOption = new Option(allSubCats[i].name, allSubCats[i].id, false, false);
                                $('#subCategoryId').append(newOption);
                            }
                        }
                    }
                    $('#subCategoryId-error').hide();
                    closeSwal();
                }
            });
        }
    });
});
</script>
@endpush