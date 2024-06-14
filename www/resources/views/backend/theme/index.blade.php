@extends('backend.layout.app')

@section('page_header', 'Theme Settings')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Theme Settings</li>
@endsection

@section('content_title', 'Theme Settings')

@section('content_body')
<form name="frm" id="frmx" action="{{ route('theme.save') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="themeName" class="onex-form-label">Choose Theme Name: <em>*</em></label>
            <select name="name" id="themeName" class="form-control onex-select2" required="required">
                @if(!empty($theme_settings) && count($theme_settings))
                    @foreach($theme_settings as $k => $v) 
                        <option value="{{ $v->id }}" @if($v->is_active == 1) selected="selected" @endif>{{ $v->name }}</option>
                    @endforeach
                @endif
            </select>
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
        <button type="button" class="btn btn-success" id="themeSaveChanges"><i class="fas fa-save"></i> Save Changes</button>
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
                required: true
            }
        },
        messages: {
            name: {
                required: 'Please select a theme'
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
    $('#themeSaveChanges').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#themeSaveChanges').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
</script>
@endpush