@extends('backend.layout.app')

@push('page_style')
@endpush

@section('page_header', 'Purchase Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('purchase.all-batches') }}">All Batches</a></li>
    <li class="breadcrumb-item active">Edit Batch</li>
@endsection

@section('content_title', 'Edit Batch')
@section('content_buttons')
    <a href="{{ route('purchase.all-batches') }}" class="btn btn-primary btn-sm"><i class="fas fa-cubes"></i> All Batches</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('purchase.update-batch', array('id' => $data->id)) }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="batchStatus" class="onex-form-label">Status: <em>*</em></label>
            <select name="status" class="form-control" id="batchStatus" required="required">
                <option value="1" @if($data->status == 1) selected="selected" @endif>Active</option>
                <option value="0" @if($data->status == 0) selected="selected" @endif>Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="batchNo" class="onex-form-label">Batch No: <em>*</em></label>
            <input type="text" name="batch_no" id="batchNo" class="form-control" placeholder="Enter Batch Number" required="required" value="{{ $data->batch_no }}" readonly="readonly" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="batchName" class="onex-form-label">Batch Name: <em>*</em></label>
            <input type="text" name="name" id="batchName" class="form-control" placeholder="Enter Batch Name" required="required" value="{{ $data->name }}"/>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="description" class="onex-form-label">Description: </label>
            <textarea name="description" id="description" class="form-control" placeholder="Enter Description..."/>{{ $data->description }}</textarea>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
</form>
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="addBtn"><i class="fas fa-plus"></i> Add Batch</button>
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
                maxlength: 90
            },
            batch_no: {
                required: true
            }
        },
        messages: {
            name: {
                required: 'Please enter batch name',
                maxlength: 'Maximum 90 chars accepted'
            },
            batch_no: {
                required: 'Please enter batch no'
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
    $('#addBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#addBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        } else {
            displayAlert('error', 'Oops!', 'Please check all the required fields');
        }
    });
});
</script>
@endpush