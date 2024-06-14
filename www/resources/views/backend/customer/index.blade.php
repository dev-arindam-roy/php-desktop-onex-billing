@extends('backend.layout.app')

@push('page_style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('page_header', 'Customer Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);" class="btn-reload">Customer Management</a></li>
    <li class="breadcrumb-item active">All Customers</li>
@endsection

@section('content_title', 'All Customers')

@section('content_buttons')
    <a href="{{ route('customer.add') }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Add Customer</a>
@endsection

@section('content_body')
<div class="row">
    <div class="col-md-12" id="displayData">
        @include('backend.customer.all-customers-render', array('data' => $data))
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
    $('body').on('click', '.remove-user-btn', function(e) {
        e.preventDefault();
        let deleteUrl = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this customer",
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
    $('body').on('click', '.lock-unlock-user', function(e) {
        e.preventDefault();
        let actionUrl = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to change the customer status",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if(result.isConfirmed) {
                displayLoading();
                window.location.href = actionUrl;
            }
        });
    });
});
</script>
@endpush