@extends('backend.layout.app')

@section('page_header', 'Product Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">All Products</a></li>
    <li class="breadcrumb-item active">Add Product Category</li>
@endsection

@section('content_title', 'Add New Product Category')
@section('content_buttons')
    <a href="{{ route('product.category.allCategories') }}" class="btn btn-primary btn-sm"><i class="fas fa-list"></i> All Product Categories</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('product.category.saveCategory') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="categoryName" class="onex-form-label">Product Category Name: <em>*</em></label>
            <input type="text" name="name" id="categoryName" class="form-control" placeholder="Enter Category Name" required="required"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="categoryStatus" class="onex-form-label">Status: <em>*</em></label>
            <select name="status" class="form-control" id="categoryStatus">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 mt-1">
                <input type="checkbox" name="primary_visibility"
                    id="primaryVisibility" 
                    value="1"
                    data-bootstrap-switch 
                    data-size="mini" 
                    data-off-color="default" 
                    data-on-color="success"
                    data-on-text="Enabled"
                    data-off-text="Disabled"
                    data-label-text="Primary Visibility"
                    data-handle-width="100"
                    data-label-width="100">
            </div>
            <div class="col-md-12 mt-2">
                <input type="checkbox" name="menu_visibility" 
                    id="menuVisibility"
                    value="1" 
                    data-bootstrap-switch 
                    data-size="mini" 
                    data-off-color="default" 
                    data-on-color="success"
                    data-on-text="Enabled"
                    data-off-text="Disabled"
                    data-label-text="Menu Visibility"
                    data-handle-width="100"
                    data-label-width="100">
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="displayOrder" class="onex-form-label">Display Order: <em>*</em></label>
            <input type="number" class="form-control" name="display_order" min="1" max="999" disabled="disabled" />
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-8">
        <div class="form-group">
            <label for="categoryDescription" class="onex-form-label">Category Description: </label>
            <textarea name="description" id="categoryDescription" class="form-control" placeholder="Enter Category Description..."/></textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="frmActionBtn"><i class="fas fa-plus"></i> Add Category</button>
    </div>
    <div class="col-md-6"></div>
</div>
@endsection

@push('page_script')
<script src="{{ asset('public/master-assets/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
@endpush

@push('page_js')
<script>
$(document).ready(function() {
    $('input[data-bootstrap-switch]').each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
    $("#frmx").validate({
        errorClass: 'onex-error',
        errorElement: 'div',
        rules: {
            name: {
                required: true,
                maxlength: 30
            },
            display_order: {
                required: {
                    depends: function(element) {
                        if($('#menuVisibility').is(':checked')) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                digits: true
            }
        },
        messages: {
            name: {
                required: 'Please enter category name',
                maxlength: 'Maximum 30 chars accepted'
            },
            display_order: {
                required: 'Display order required',
                digits: 'Only digits accepted'
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
    $('input[type="checkbox"][name="menu_visibility"]').on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $('input[name="display_order"]').removeAttr('disabled');
        } else {
            $('input[name="display_order"]').attr('disabled', 'disabled');
        }
    });
});
</script>
@endpush