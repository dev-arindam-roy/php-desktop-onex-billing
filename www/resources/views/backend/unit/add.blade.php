@extends('backend.layout.app')

@section('page_header', 'Unit Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('unit.index') }}">Unit Management</a></li>
    <li class="breadcrumb-item active">Add Unit</li>
@endsection

@section('content_title', 'Add New Unit')
@section('content_buttons')
    <a href="{{ route('unit.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-balance-scale"></i> All Units</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('unit.save') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="unitName" class="onex-form-label">Unit Name: <em>*</em></label>
            <input type="text" name="name" id="unitName" class="form-control" placeholder="Enter Unit Name" required="required"/>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="shortName" class="onex-form-label">Short Name: <em>*</em></label>
            <input type="text" name="short_name" id="shortName" class="form-control" placeholder="Enter Short Name" required="required"/>
        </div>
    </div>
    <div class="col-md-6"></div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="unitDescription" class="onex-form-label">Unit Description: </label>
            <textarea name="description" id="unitDescription" class="form-control" placeholder="Enter Unit Description..."/></textarea>
        </div>
    </div>
    <div class="col-md-6"></div>
</div>
@if(count($all_units))
<div class="row">
    <div class="col-md-12">
        <blockquote>
            <p>Sub Unit / Child Unit</p>
        </blockquote>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <label for="childUnitValue" class="onex-form-label">Unit Value: </label>
        <input type="number" name="child_unit_value" id="childUnitValue" class="form-control" placeholder="Enter Child Unit" />
    </div>
    <div class="col-md-3">
        <label for="childUnitId" class="onex-form-label">Unit Value: </label>
        <select name="child_unit_id" id="childUnitId" class="form-control onex-select2">
            <option value=""></option>
            @foreach($all_units as $k => $v)
                <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->short_name }})</option>
            @endforeach
        </select>
    </div>
</div>
@endif
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="addUnitBtn"><i class="fas fa-plus"></i> Add Unit</button>
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
            short_name: {
                required: true,
                maxlength: 10
            },
            child_unit_value: {
                required: {
                    depends: function(element) {
                        if($('#childUnitId').val() != '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                digits: true
            },
            child_unit_id: {
                required: {
                    depends: function(element) {
                        if($('#childUnitValue').val() != '') {
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
                required: 'Please enter unit name',
                maxlength: 'Maximum 20 chars accepted'
            },
            short_name: {
                required: 'Please enter short name',
                maxlength: 'Maximum 10 chars accepted'
            },
            child_unit_value: {
                required: 'Please enter unit value',
                digits: 'Please enter number'
            },
            child_unit_id: {
                required: 'Please select an unit',
                digits: 'Invalid unit'
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
    $('#addUnitBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#addUnitBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
</script>
@endpush