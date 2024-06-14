@extends('backend.layout.app')

@push('page_style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('page_header', 'Purchase Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management</a></li>
    <li class="breadcrumb-item active">All Purchases</li>
@endsection

@section('content_title', 'All Purchases')
@section('content_buttons')
    <a href="{{ route('purchase.add-purchase') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add New Purchase</a>
@endsection

@section('content_body')
<form name="search_frm" id="searchFrm" action="" method="GET">
    <div class="row mb-2">
        <div class="col-md-3">
            <input type="text" name="variant_name" id="searchVariantName" class="form-control" placeholder="Product Name" value="{{ (request()->has('variant_name')) ? request()->get('variant_name') : '' }}"/>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success">Search</button>
            <a href="{{ route('product.variant.allVariants') }}" class="btn btn-danger">Clear</a>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-12" id="displayData">
        @include('backend.purchase.all-purchase-render', array('data' => $data))
    </div>
</div>
@endsection

@section('content_footer')
    
@endsection

@push('page_script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
@endpush

@push('page_js')
<script>
$(document).ready( function () {
    $('body').on('click', '.remove-product-btn', function(e) {
        e.preventDefault();
        let deleteUrl = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this product variant",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed) {
                displayLoading();
                window.location.href = deleteUrl;
            }
        });
    });
});
</script>
@endpush