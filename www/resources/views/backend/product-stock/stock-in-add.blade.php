@extends('backend.layout.app')

@push('page_style')
<link rel="stylesheet" href="{{ asset('public') }}/master-assets/bs-datepicker/css/bootstrap-datepicker3.min.css"/>
@endpush

@section('page_header', 'Stock Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('stock.in') }}">Today Stock In</a></li>
    <li class="breadcrumb-item active">Add Stock</li>
@endsection

@section('content_title', 'Add New Stock')
@section('content_buttons')
    <a href="{{ route('stock.in') }}" class="btn btn-primary btn-sm"><i class="fas fa-layer-group"></i> All In Stocks</a>
@endsection

@section('content_body')
<form name="frm" id="frmx" action="{{ route('stock.save') }}" method="POST">
@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="challanNo" class="onex-form-label">Challan No:</label>
            <input type="text" name="challan_no" id="challanNo" class="form-control" placeholder="Enter challan No"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group" id="bsDatePickerContainer">
            <label for="stockReceivedDate" class="onex-form-label">Stock Received Date: <em>*</em></label>
            <input type="text" name="stock_received_date" id="stockReceivedDate" class="form-control onex-datepicker" readonly="readonly" placeholder="Enter Received Date" required="required"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="userId" class="onex-form-label">Received From Whom : <em>*</em></label>
            <select name="user_id" id="userId" class="form-control onex-select2" required="required">
                <option></option>
                @if(!empty($all_users) && count($all_users))
                    @foreach($all_users as $v)
                        <option value="{{ $v->id }}">{{ $v->first_name . ' ' . $v->last_name }} ({{ $v->user_category_name }})</option>
                    @endforeach
                @endif 
            </select>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="productId" class="onex-form-label">Select Product : <em>*</em></label>
            <select name="product_id" id="productId" class="form-control onex-select2" required="required">
                <option></option>
                @if(!empty($all_products) && count($all_products))
                    @foreach($all_products as $v)
                        <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->sku }})</option>
                    @endforeach
                @endif 
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="productQuantity" class="onex-form-label">Quantity: <em>*</em></label>
            <input type="number" name="product_quantity" id="productQuantity" class="form-control onlyNumber" placeholder="Enter Quantity" required="required"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="unitId" class="onex-form-label">Select Unit : <em>*</em></label>
            <select name="unit_id" id="unitId" class="form-control onex-select2" required="required">
                <option></option>
                @if(!empty($all_units) && count($all_units))
                    @foreach($all_units as $v)
                        <option value="{{ $v->id }}">{{ $v->short_name }}</option>
                    @endforeach
                @endif 
            </select>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="unitPrice" class="onex-form-label">Price/Rate: <em>*</em></label>
            <input type="number" name="unit_price" id="unitPrice" class="form-control onlyNumber" placeholder="Enter Price" required="required"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="unitTotal" class="onex-form-label">Total: <em>*</em></label>
            <input type="number" name="unit_total" id="unitTotal" class="form-control" placeholder="Get Total" readonly="readonly"/>
        </div>
    </div>
    <div class="col-md-8"></div>
</div>
</form>
<input type="hidden" id="todayDateHidden" value="{{ date('m/d/Y') }}"/>


<!-- Modal -->
<div class="modal fade" id="addNewUserModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title select2-option-modal-title"><i class="fas fa-plus-square text-success"></i> Add New User</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <form name="add_user_frm" id="addUserFrm" action="{{ route('user.quick-add') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="userCategory" class="onex-form-label">Category: <em>*</em></label>
                        <select name="user_category" id="userCategory" class="form-control" required="required">
                            @if(!empty($all_user_categories) && count($all_user_categories))
                                @foreach($all_user_categories as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="userMobile" class="onex-form-label">Mobile No: <em>*</em></label>
                        <input type="number" name="phone_number" class="form-control" id="userMobile" placeholder="Mobile No" maxlength="10" required="required"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="userFirstName" class="onex-form-label">First Name: <em>*</em></label>
                        <input type="text" name="first_name" class="form-control" id="userFirstName" placeholder="First Name" maxlength="30" required="required"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="userLastName" class="onex-form-label">Last Name: <em>*</em></label>
                        <input type="text" name="last_name" class="form-control" id="userLastName" placeholder="Last Name" maxlength="30" required="required"/>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="addNewUserBtn" class="btn btn-success mr-auto">Add User</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<div class="modal fade" id="addNewProductModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title select2-option-modal-title"><i class="fas fa-plus-square text-success"></i> Add New Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <form name="add_product_frm" id="addProductFrm" action="{{ route('product.quick-add') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="productName" class="onex-form-label">Product Name: <em>*</em></label>
                        <input type="text" name="name" class="form-control" id="productName" placeholder="Product Name" maxlength="20" required="required"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="productSku" class="onex-form-label">Product SKU: <em>*</em></label>
                        <input type="text" name="sku" class="form-control" id="productSku" placeholder="Product SKU" maxlength="20" required="required"/>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="addNewProductBtn" class="btn btn-success mr-auto">Add Product</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<div class="modal fade" id="addNewUnitModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title select2-option-modal-title"><i class="fas fa-plus-square text-success"></i> Add New Unit</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <form name="add_unit_frm" id="addUnitFrm" action="{{ route('product.quick-add-unit') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="productUnitName" class="onex-form-label">Unit Name: <em>*</em></label>
                        <input type="text" name="name" class="form-control" id="productUnitName" placeholder="Unit Name" maxlength="10" required="required"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="shortUnitName" class="onex-form-label">Short Name: <em>*</em></label>
                        <input type="text" name="short_name" class="form-control" id="shortUnitName" placeholder="Short Name" maxlength="10" required="required"/>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="addNewUnitBtn" class="btn btn-success mr-auto">Add Unit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->
@endsection

@section('content_footer')
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="addStockBtn"><i class="fas fa-plus"></i> Add Stock</button>
    </div>
    <div class="col-md-6"></div>
</div>
@endsection

@push('page_script')
<script src="{{ asset('public') }}/master-assets/bs-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
@endpush

@push('page_js')
<script>
$(document).ready(function() {
    $('.onex-datepicker').datepicker({
        container: '#bsDatePickerContainer',
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose:true
    });
    $('#stockReceivedDate').datepicker('setDate', $('#todayDateHidden').val());
    $('#userId').on('select2:open', () => {
        $(".select2-results:not(:has(a))").prepend('<a href="javascript:void(0);" class="select2-add-new-option" data-select2id="userId" data-modal-id="addNewUserModal"><i class="fas fa-plus"></i> Add New User</a>');
    });
    $('#productId').on('select2:open', () => {
        $(".select2-results:not(:has(a))").prepend('<a href="javascript:void(0);" class="select2-add-new-option" data-select2id="productId" data-modal-id="addNewProductModal"><i class="fas fa-plus"></i> Add New Product</a>');
    });
    $('#unitId').on('select2:open', () => {
        $(".select2-results:not(:has(a))").prepend('<a href="javascript:void(0);" class="select2-add-new-option" data-select2id="unitId" data-modal-id="addNewUnitModal"><i class="fas fa-plus"></i> Add New Unit</a>');
    });
    $("#frmx").validate({
        errorClass: 'onex-error',
        errorElement: 'div',
        rules: {
            stock_received_date: {
                required: true
            },
            user_id: {
                required: true,
                digits: true
            },
            product_id: {
                required: true,
                digits: true
            },
            product_quantity: {
                required: true
            },
            unit_id: {
                required: true,
                digits: true
            },
            unit_price: {
                required: true
            }
        },
        messages: {
            stock_received_date: {
                required: 'Please select date'
            },
            user_id: {
                required: 'Please select an user',
                digits: 'Invalid user'
            },
            product_id: {
                required: 'Please select a product',
                digits: 'Invalid product'
            },
            product_quantity: {
                required: 'Please enter quantity'
            },
            unit_id: {
                required: 'Please select an unit',
                digits: 'Invalid unit'
            },
            unit_price: {
                required: 'Please enter price'
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
    $("#addUserFrm").validate({
        errorClass: 'onex-error',
        errorElement: 'div',
        rules: {
            user_category: {
                required: true
            },
            phone_number: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            first_name: {
                required: true,
                maxlength: 30
            },
            last_name: {
                required: true,
                maxlength: 30
            }
        },
        messages: {
            user_category: {
                required: 'Please select user'
            },
            phone_number: {
                required: 'Please enter mobile-no',
                digits: 'Invalid mobile-no',
                maxlength: 'Invalid mobile-no',
                minlength: 'Invalid mobile-no'
            },
            first_name: {
                required: 'Please enter first name',
                maxlength: 'Maximum 30 chars'
            },
            last_name: {
                required: 'Please enter last name',
                maxlength: 'Maximum 30 chars'
            }
        }
    });
    $("#addProductFrm").validate({
        errorClass: 'onex-error',
        errorElement: 'div',
        rules: {
            name: {
                required: true,
                maxlength: 20
            },
            sku: {
                required: true,
                maxlength: 20
            }
        },
        messages: {
            name: {
                required: 'Please enter product name',
                maxlength: 'Maximum 20 chars'
            },
            sku: {
                required: 'Please enter product sku',
                maxlength: 'Maximum 20 chars'
            }
        }
    });
    $("#addUnitFrm").validate({
        errorClass: 'onex-error',
        errorElement: 'div',
        rules: {
            name: {
                required: true,
                maxlength: 10
            },
            short_name: {
                required: true,
                maxlength: 10
            }
        },
        messages: {
            name: {
                required: 'Please enter product name',
                maxlength: 'Maximum 10 chars'
            },
            short_name: {
                required: 'Please enter product sku',
                maxlength: 'Maximum 10 chars'
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
    $('body').on('click', '.select2-add-new-option', function(e) {
        e.preventDefault();
        $('#' + $(this).data('select2id')).select2('close');
        $('#' + $(this).data('modal-id')).modal({
            backdrop: 'static', 
            keyboard: false
        }, 'show');
    });
    $('input[name="unit_price"]').on('blur', function() {
        $('#unitTotal').val(totalPriceCalculation($(this).val(), $('#productQuantity').val()));
    });
    $('input[name="product_quantity"]').on('blur', function() {
        $('#unitTotal').val(totalPriceCalculation($('#unitPrice').val(), $(this).val()));
    });
    $('body').on('click', '#addNewUserBtn', function() {
        if($('#addUserFrm').valid()) {
            displayLoading();
            $.ajax({
                type: $('#addUserFrm').attr('method'),
                url: $('#addUserFrm').attr('action'),
                data: $('#addUserFrm').serialize(),
                cache: false,
                beforeSend: function() {

                },
                success: function(responseData) {
                    closeSwal();
                    if(responseData) {
                        if(responseData.response == 'success' && (responseData.data !== null || responseData.data !== '')) {
                            let data = {
                                id: responseData.data.id,
                                text: `${responseData.data.first_name} ${responseData.data.last_name} (${$('#userCategory option:selected').text()})` 
                            };
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#userId').append(newOption).val(data.id).trigger('change');
                            $('#userId-error').hide();
                            $('#addUserFrm').find('.form-control').val('');
                            $('#userCategory').val($('#userCategory option:first').val());
                            $('#addNewUserModal').modal('hide');
                            toastr.success(responseData.response_message, 'Done!');
                        }
                        if(responseData.response == 'error') {
                            toastr.success(responseData.response_message, 'Sorry!');
                        }
                    }
                },
                error: function(errorData) {
                    displayAlert('error', 'SERVER ERROR!', 'Something Went Wrong');
                }
            });
        }
    });
    $('body').on('click', '#addNewProductBtn', function() {
        if($('#addProductFrm').valid()) {
            displayLoading();
            $.ajax({
                type: $('#addProductFrm').attr('method'),
                url: $('#addProductFrm').attr('action'),
                data: $('#addProductFrm').serialize(),
                cache: false,
                beforeSend: function() {

                },
                success: function(responseData) {
                    closeSwal();
                    if(responseData) {
                        if(responseData.response == 'success' && (responseData.data !== null || responseData.data !== '')) {
                            let data = {
                                id: responseData.data.id,
                                text: `${responseData.data.name}` 
                            };
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#productId').append(newOption).val(data.id).trigger('change');
                            $('#productId-error').hide();
                            $('#addProductFrm').find('.form-control').val('');
                            $('#addNewProductModal').modal('hide');
                            toastr.success(responseData.response_message, 'Done!');
                        }
                        if(responseData.response == 'error') {
                            toastr.success(responseData.response_message, 'Sorry!');
                        }
                    }
                },
                error: function(errorData) {
                    displayAlert('error', 'SERVER ERROR!', 'Something Went Wrong');
                }
            });
        }
    });
    $('body').on('click', '#addNewUnitBtn', function() {
        if($('#addUnitFrm').valid()) {
            displayLoading();
            $.ajax({
                type: $('#addUnitFrm').attr('method'),
                url: $('#addUnitFrm').attr('action'),
                data: $('#addUnitFrm').serialize(),
                cache: false,
                beforeSend: function() {

                },
                success: function(responseData) {
                    closeSwal();
                    if(responseData) {
                        if(responseData.response == 'success' && (responseData.data !== null || responseData.data !== '')) {
                            let data = {
                                id: responseData.data.id,
                                text: `${responseData.data.short_name}` 
                            };
                            var newOption = new Option(data.text, data.id, false, false);
                            $('#unitId').append(newOption).val(data.id).trigger('change');
                            $('#unitId-error').hide();
                            $('#addUnitFrm').find('.form-control').val('');
                            $('#addNewUnitModal').modal('hide');
                            toastr.success(responseData.response_message, 'Done!');
                        }
                        if(responseData.response == 'error') {
                            toastr.success(responseData.response_message, 'Sorry!');
                        }
                    }
                },
                error: function(errorData) {
                    displayAlert('error', 'SERVER ERROR!', 'Something Went Wrong');
                }
            });
        }
    });
    $('#addStockBtn').on('click', function() {
        if($("#frmx").valid()) {
            displayLoading();
            $('#addStockBtn').attr('disabled', 'disabled');
            $("#frmx").submit();
        }
    });
});
</script>
@endpush