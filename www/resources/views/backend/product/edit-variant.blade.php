@extends('backend.layout.app')

@push('page_style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('page_header', 'Product Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product Management</a></li>
    <li class="breadcrumb-item active">Edit Product variant</li>
@endsection

@section('content_title', 'Edit Product variant')
@section('content_buttons')
    <a href="{{ route('product.variant.allVariants') }}" class="btn btn-primary btn-sm"><i class="fas fa-cubes"></i> All Product Variants</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('product.variant.updateVariants', array('id' => $product->id)) }}" method="POST" enctype="multipart/form-data">
@csrf

@if(!empty($product->barcode_no))
<div class="row">
    <div class="col-md-6 svg-barcode-container">
        {!! DNS1D::getBarcodeSVG($product->barcode_no, 'C39+', 2, 45, 'black', true) !!}
    </div>
    <div class="col-md-6">
        <div class="dropdown">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-download"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);" onclick="downloadSVGAsText();">Download As SVG</a>
                <a class="dropdown-item" href="javascript:void(0);" onclick="downloadSVGAsPNG();">Download As PNG</a>
            </div>
        </div>
    </div>
    <div class="col-md-12"><hr/></div>
</div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="productStatus" class="onex-form-label">Status: <em>*</em></label>
            <select name="status" class="form-control" id="productStatus" required="required">
                <option value="1" @if($product->status == 1) selected="selected" @endif>Active</option>
                <option value="0" @if($product->status == 0) selected="selected" @endif>Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="barcodeNo" class="onex-form-label">Barcode No:</label>
            <input type="text" name="barcode_no" id="barcodeNo" class="form-control" placeholder="Barcode Number" readonly @if(!empty($product->barcode_no)) value="{{ $product->barcode_no }}" @endif />
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="baseProduct" class="onex-form-label">Select Base Product: <em>*</em></label>
            <select name="product_id" class="form-control onex-select2" id="baseProduct" required="required">
                <option></option>
                @if(!empty($all_products) && count($all_products))
                    @foreach($all_products as $v)
                    <option value="{{ $v->id }}" @if($product->product_id == $v->id) selected="selected" @endif>{{ $v->name }}</option>
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
                    <option value="{{ $v->id }}" @if($product->brand_id == $v->id) selected="selected" @endif>{{ $v->name }}</option>
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
            <input type="text" name="name" id="productName" class="form-control" placeholder="Enter Product Variant Name" required="required" value="{{ $product->name }}"/>
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
                    <option value="{{ $v->id }}" @if($product->unit_id == $v->id) selected="selected" @endif>{{ $v->name }} ({{ $v->short_name }})</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="productSku" class="onex-form-label">Product SKU: <em>*</em></label>
            <input type="text" name="sku" id="productSku" class="form-control" placeholder="Enter Product SKU" required="required" value="{{ $product->sku }}"/>
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
                <input type="number" maxlength="10" min="0" name="price" id="productPrice" class="form-control cal-price" placeholder="Enter Product Price" required="required" value="{{ $product->price }}"/>
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
                <input type="number" maxlength="10" min="0" name="old_price" id="productOldPrice" class="form-control cal-price" placeholder="Enter Product Old Price" required="required" value="{{ $product->old_price }}"/>
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
                <input type="number" maxlength="5" min="0" name="percentage_discount" id="productDiscountPerc" class="form-control cal-price" placeholder="Enter Discount %" value="{{ $product->percentage_discount }}"/>
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
                <input type="number" maxlength="5" min="0" name="flat_discount" id="productDiscountFlat" class="form-control cal-price" placeholder="Enter Flat Discount" value="{{ $product->flat_discount }}"/>
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
                <input type="text" maxlength="15" name="hsn_code" id="hsnCode" class="form-control" placeholder="Enter HSN Code" value="{{ $product->hsn_code }}"/>
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
                <input type="number" maxlength="5" min="0" name="gst_rate" id="gstRate" class="form-control" placeholder="Enter GST Rate" value="{{ $product->gst_rate }}"/>
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
@if(!empty($product->image))
<div class="row">
    <div class="col-md-3">
        <div class="onex-preview-imgbox">
            <image src="{{ asset('public/uploads/images/products/thumbnail/' . $product->image) }}" class="img-thumbnail"/>
            <a href="javascript:void(0);" class="table-image-remove" title="Remove Image" 
                data-table-row-id="{{ $product->id }}" 
                data-table-name="product_variants"
                data-table-field="image"><i class="fas fa-trash-alt text-danger"></i></a>
        </div>
    </div>
    <div class="col-md-9"></div>
</div>
@endif
<div class="row mt-2">
    <div class="col-md-8">
        <div class="form-group">
            <label for="productOfferText" class="onex-form-label">Any Offer Text: </label>
            <textarea name="offer_text" id="productOfferText" class="form-control" placeholder="Enter Product Offer Text..."/>{{ $product->offer_text }}</textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row mt-3">
    <div class="col-md-8">
        <div class="form-group">
            <label for="productDescription" class="onex-form-label">Product Description: </label>
            <textarea name="description" id="productDescription" class="form-control" placeholder="Enter Product Description..."/>{!! html_entity_decode($product->description, ENT_QUOTES ) !!}</textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="updateProductBtn"><i class="fas fa-save"></i> Save Changes</button>
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
            product_id: {
                required: true,
                digits: true
            },
            unit_id: {
                required: true,
                digits: true
            },
            brand_id: {
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
            unit_id: {
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
    $('#updateProductBtn').on('click', function() {
        if($("#frmx").valid()) {
            let getSalePrice = parseFloat($('#productPrice').val());
            let getMrpPrice = parseFloat($('#productOldPrice').val());
            if(getSalePrice > getMrpPrice) {
                displayAlert('error', 'Wrong Pricing', 'Please check the sale price & mrp price');
            } else {
                displayLoading();
                $('#updateProductBtn').attr('disabled', 'disabled');
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
function downloadSVGAsText() {
    const svg = document.querySelector('svg');
    const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
    const a = document.createElement('a');
    const e = new MouseEvent('click');
    a.download = document.getElementById('barcodeNo').value + '.svg';
    a.href = 'data:image/svg+xml;base64,' + base64doc;
    a.dispatchEvent(e);
}
function downloadSVGAsPNG(e){
    const canvas = document.createElement("canvas");
    const svg = document.querySelector('svg');
    const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
    const w = parseInt(svg.getAttribute('width'));
    const h = parseInt(svg.getAttribute('height'));
    const img_to_download = document.createElement('img');
    img_to_download.src = 'data:image/svg+xml;base64,' + base64doc;
    img_to_download.onload = function () {    
        canvas.setAttribute('width', w);
        canvas.setAttribute('height', h);
        const context = canvas.getContext("2d");
        //context.clearRect(0, 0, w, h);
        context.drawImage(img_to_download,0,0,w,h);
        const dataURL = canvas.toDataURL('image/png');
        if (window.navigator.msSaveBlob) {
        window.navigator.msSaveBlob(canvas.msToBlob(), document.getElementById('barcodeNo').value + ".png");
        e.preventDefault();
        } else {
        const a = document.createElement('a');
        const my_evt = new MouseEvent('click');
        a.download = document.getElementById('barcodeNo').value + ".png";
        a.href = dataURL;
        a.dispatchEvent(my_evt);
        }
        //canvas.parentNode.removeChild(canvas);
    }  
}
</script>
@endpush