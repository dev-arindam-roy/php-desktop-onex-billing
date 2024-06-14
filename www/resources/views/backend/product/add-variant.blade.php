@extends('backend.layout.app')

@push('page_style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('page_header', 'Product Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product Management</a></li>
    <li class="breadcrumb-item active">Add Product variant</li>
@endsection

@section('content_title', 'Add New Product variant')
@section('content_buttons')
    <a href="{{ route('product.variant.allVariants') }}" class="btn btn-primary btn-sm"><i class="fas fa-cubes"></i> All Product Variants</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('product.variant.saveVariants') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="productStatus" class="onex-form-label">Status: <em>*</em></label>
            <select name="status" class="form-control" id="productStatus" required="required">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-md-8"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="baseProduct" class="onex-form-label">Select Base Product: <em>*</em></label>
            <select name="product_id" class="form-control onex-select2" id="baseProduct" required="required">
                <option></option>
                @if(!empty($all_products) && count($all_products))
                    @foreach($all_products as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="productBrand" class="onex-form-label">Select Brand: <em>*</em></label>
            <select name="brand_id" class="form-control onex-select2" id="productBrand" required="required">
                <option></option>
                @if(!empty($all_brands) && count($all_brands))
                    @foreach($all_brands as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="productName" class="onex-form-label">Product Variant Name: <em>*</em></label>
            <input type="text" name="name" id="productName" class="form-control" placeholder="Enter Product Variant Name" required="required"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="productUnit" class="onex-form-label">Select Unit: <em>*</em></label>
            <select name="unit_id" class="form-control onex-select2" id="productUnit" required="required">
                <option></option>
                @if(!empty($all_units) && count($all_units))
                    @foreach($all_units as $v)
                    <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->short_name }})</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="productSku" class="onex-form-label">Product SKU: <em>*</em></label>
            <input type="text" name="sku" id="productSku" class="form-control" placeholder="Enter Product SKU" required="required"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="productPrice" class="onex-form-label">Product Price (Sale): <em>*</em></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                </div>
                <input type="number" maxlength="10" min="0" name="price" id="productPrice" class="form-control cal-price" placeholder="Enter Product Price" required="required" value="0"/>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="productOldPrice" class="onex-form-label">Product Price (MRP): <em>*</em></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                </div>
                <input type="number" maxlength="10" min="0" name="old_price" id="productOldPrice" class="form-control cal-price" placeholder="Enter Product Old Price" required="required" value="0"/>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="productDiscountPerc" class="onex-form-label">Discount (%):</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                </div>
                <input type="number" maxlength="5" min="0" name="percentage_discount" id="productDiscountPerc" class="form-control cal-price" placeholder="Enter Discount %" value="0"/>
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="productDiscountFlat" class="onex-form-label">Discount (Flat):</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                </div>
                <input type="number" maxlength="5" min="0" name="flat_discount" id="productDiscountFlat" class="form-control cal-price" placeholder="Enter Flat Discount" value="0"/>
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="hsnCode" class="onex-form-label">HSN Code:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                <input type="text" maxlength="15" name="hsn_code" id="hsnCode" class="form-control" placeholder="Enter HSN Code"/>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="gstRate" class="onex-form-label">GST Rate (%):</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                </div>
                <input type="number" maxlength="5" min="0" name="gst_rate" id="gstRate" class="form-control" placeholder="Enter GST Rate"/>
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                </div>
            </div>
        </div>
    </div>
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
            <label for="productOfferText" class="onex-form-label">Any Offer Text: </label>
            <textarea name="offer_text" id="productOfferText" class="form-control" placeholder="Enter Product Offer Text..."/></textarea>
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
        <button type="button" class="btn btn-success" id="addProductBtn"><i class="fas fa-plus"></i> Add Product Variant</button>
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
                maxlength: 90
            },
            status: {
                required: true,
                digits: true
            },
            brand_id: {
                required: true,
                digits: true
            },
            product_id: {
                required: true,
                digits: true
            },
            unit_id: {
                required: true,
                digits: true
            },
            sku: {
                required: true,
                maxlength: 30
            },
            price: {
                required: true,
                number: true,
                maxlength: 10,
                min: 0
            },
            old_price: {
                required: true,
                number: true,
                maxlength: 10,
                min: 0
            },
            percentage_discount: {
                number: true,
                maxlength: 10,
                min: 0
            },
            flat_discount: {
                number: true,
                maxlength: 10,
                min: 0
            },
            gst_rate: {
                number: true
            }
        },
        messages: {
            name: {
                required: 'Please enter product variant name',
                maxlength: 'Maximum 90 chars accepted'
            },
            status: {
                required: 'Please select status',
                digits: 'Only number accepted'
            },
            product_id: {
                required: 'Please select base product',
                digits: 'Invalid base product'
            },
            unit_id: {
                required: 'Please select an unit',
                digits: 'Invalid unit'
            },
            brand_id: {
                required: 'Please select a brand',
                digits: 'Invalid brand'
            },
            sku: {
                required: 'Please enter product sku',
                maxlength: 'Maximum 30 chars accepted'
            },
            price: {
                required: 'Please enter product price',
                number: 'Only number accepted',
                maxlength: 'Maximum 10 chars accepted',
                min: 'Enter the amount'
            },
            old_price: {
                required: 'Please enter product old price',
                number: 'Only number accepted',
                maxlength: 'Maximum 10 chars accepted',
                min: 'Enter the amount'
            },
            percentage_discount: {
                number: 'Only number accepted',
                maxlength: 'Maximum 5 chars accepted',
                min: 'Enter the discount %'
            },
            flat_discount: {
                number: 'Only number accepted',
                maxlength: 'Maximum 5 chars accepted',
                min: 'Enter the discount amount'
            },
            gst_rate: {
                number: 'Enter gst rate'
            }
        },
        errorPlacement: function (error, element) {
            if(element.hasClass('onex-select2')) {
                error.insertAfter(element.parent().find('span.select2-container'));
            } else if(element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
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
            let getSalePrice = parseFloat($('#productPrice').val());
            let getMrpPrice = parseFloat($('#productOldPrice').val());
            if(getSalePrice > getMrpPrice) {
                displayAlert('error', 'Wrong Pricing', 'Please check the sale price & mrp price');
            } else {
                displayLoading();
                $('#addProductBtn').attr('disabled', 'disabled');
                $("#frmx").submit();
            }
        } else {
            displayAlert('error', 'Oops!', 'Please check all the required fields');
        }
    });

    let salePrice = 0;
    let mrpPrice = 0;
    let disperPrice = 0;
    let disfltPrice = 0;
    function calculateProductPrice(elemt) {
        let elmId = elemt.attr('id');
        let _salePrice = parseFloat($('#productPrice').val());
        let _mrpPrice = parseFloat($('#productOldPrice').val());
        let _disperPrice = parseFloat($('#productDiscountPerc').val());
        let _disfltPrice = parseFloat($('#productDiscountFlat').val());
        if(!isNaN(_salePrice) && !isNaN(_mrpPrice)) {
            if(elmId == 'productDiscountPerc' && (disperPrice != _disperPrice)) {
                _salePrice = _mrpPrice - ((_mrpPrice * _disperPrice) / 100);
                _disfltPrice = _mrpPrice - _salePrice;
            }
            if(elmId == 'productDiscountFlat' && (disfltPrice != _disfltPrice)) {
                _salePrice = _mrpPrice - _disfltPrice;
                _disperPrice = (_disfltPrice * 100) / _mrpPrice;
            } 
            if(elmId == 'productOldPrice' && (mrpPrice != _mrpPrice)) {
                _disfltPrice = _mrpPrice - _salePrice;
                _disperPrice = (_disfltPrice / _mrpPrice) * 100;
            }
            if(elmId == 'productPrice' && (salePrice != _salePrice)) {
                _disfltPrice = _mrpPrice - _salePrice;
                _disperPrice = (_disfltPrice / _mrpPrice) * 100;
            }
        }
        
        salePrice = _salePrice;
        mrpPrice = _mrpPrice;
        disperPrice = _disperPrice;
        disfltPrice = _disfltPrice;
        
        if(salePrice > 0 && mrpPrice > 0 && (_salePrice > _mrpPrice)) {
            displayAlert('error', 'Wrong Pricing', 'Please check the sale price & mrp price');
        }
        
        $('#productPrice').val(salePrice.toFixed(2));
        $('#productOldPrice').val(mrpPrice.toFixed(2));
        $('#productDiscountFlat').val(disfltPrice.toFixed(2));
        $('#productDiscountPerc').val(disperPrice.toFixed(2));
    }

    $('.cal-price').on('blur', function() {
        calculateProductPrice($(this));
    });
    
});
</script>
@endpush