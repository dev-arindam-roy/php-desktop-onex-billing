@extends('backend.layout.app')

@section('page_header', 'Barcode Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('barcode.product.index') }}">Products Barcode</a></li>
    <li class="breadcrumb-item active">Create Product Barcode</li>
@endsection

@section('content_title', 'Create Product Barcode')
@section('content_buttons')
    
@endsection

@section('content_body')
<form name="frmx" id="frmx" action="{{ route('barcode.product.index') }}" method="GET">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="itemCode">Select Product: <em>*</em></label>
                <select name="code" id="itemCode" class="form-control onex-select2" required="required">
                    <option value=""></option>
                    @if(!empty($products))
                        @foreach($products as $k => $v)
                            <option value="{{ $v->barcode_no }}" @if(isset($_GET['code']) && !empty($_GET['code']) && $_GET['code'] == $v->barcode_no) selected="selected" @endif>{{ $v->name }} ({{ $v->sku }})</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
              <label for="barcodeColor">Barcode Color: </label>
              <select name="color" id="barcodeColor" class="form-control onex-select2" required="required">
                @if(!empty($colors))
                  @foreach($colors as $k => $v)
                  <option value="{{ $v }}" @if(isset($_GET['color']) && !empty($_GET['color']) && $_GET['color'] == $v) selected="selected" @endif>{{ ucfirst($v) }}</option>
                  @endforeach
                @endif
              </select>
            </div>
        </div>
    </div>
</form>

@if(!empty($barcode))
    <div class="row mt-5">
      <div class="col-md-6 svg-barcode-container" style="text-align: center;">{!! $barcode !!}</div>
      <div class="col-md-6">
        <button type="button" class="btn btn-sm btn-primary" onclick="downloadSVGAsText()">Download As SVG</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="downloadSVGAsPNG()">Download As PNG</button>
      </div>
    </div>
@endif
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="createBtn"><i class="fas fa-save"></i> Generate</button>
        <a href="{{ route('barcode.product.index') }}" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Clear</a>
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
            code: {
                required: true
            },
            color: {
                required: true
            },
            size: {
                required: true
            }
        },
        messages: {
            code: {
                required: 'Please select a product'
            },
            color: {
                required: 'Please select a colour'
            },
            size: {
                required: 'Please select a size'
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
    $('#createBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#addBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
function downloadSVGAsText() {
    const svg = document.querySelector('svg');
    const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
    const a = document.createElement('a');
    const e = new MouseEvent('click');
    a.download = document.getElementById('itemCode').value + '.svg';
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
        window.navigator.msSaveBlob(canvas.msToBlob(), document.getElementById('itemCode').value + ".png");
        e.preventDefault();
        } else {
        const a = document.createElement('a');
        const my_evt = new MouseEvent('click');
        a.download = document.getElementById('itemCode').value + ".png";
        a.href = dataURL;
        a.dispatchEvent(my_evt);
        }
        //canvas.parentNode.removeChild(canvas);
    }  
}
</script>
@endpush