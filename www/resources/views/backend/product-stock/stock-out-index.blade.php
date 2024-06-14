@extends('backend.layout.app')

@push('page_style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" />
@endpush

@section('page_header', 'Stock Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Stock Management</a></li>
    <li class="breadcrumb-item active">Today Stock Out</li>
@endsection

@section('content_title', 'Today Stock Out - ' . date('d F, Y'))
@section('content_buttons')
    <a href="{{ route('stock.out-add') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Out Stock</a>
@endsection

@section('content_body')
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover nowrap" id="todayStockOutTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 40px;">SL</th>
                        <th>Issued Date</th>
                        <th>Challan No</th>
                        <th>Issued To</th>
                        <th>Product Name</th>
                        <th>QTY</th>
                        <th>Unit</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($data) && count($data))
                    @php 
                        $sl = 1;
                        $todayTotal = 0; 
                    @endphp
                    @foreach($data as $key => $value)
                        @php
                            $user = Helper::userInfo($value->user_id);
                        @endphp
                        <tr>
                            <td>{{ $sl }}</td>
                            <td>{{ date('m-d-Y', strtotime($value->stock_issued_date)) }}</td>
                            <td>{{ $value->challan_no }}</td>
                            <td>
                                <span>
                                    {{ $value->user_first_name . ' ' . $value->user_last_name }}
                                    @if(!empty($user) && !empty($user->userCategory))
                                        (<small><strong>{{ $user->userCategory->name }}</strong></small>)
                                    @endif
                                </span>
                            </td>
                            <td>{{ $value->product_name }}</td>
                            <td>{{ $value->product_quantity }}</td>
                            <td>{{ $value->unit_name }}</td>
                            <td>{{ $value->unit_price }}</td>
                            <td>{{ $value->unit_total }}</td>
                            <td>{{ date('m-d-Y', strtotime($value->created_at)) }}</td>
                        </tr>
                        @php
                            $todayTotal = $todayTotal + $value->unit_total;
                            $sl++; 
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td style="display: none;"></td>
                        <td colspan="10">No records found for today. Please dispatch stock for the day</td>
                    </tr>
                @endif
                </tbody>
            </table> 
        </div>
    </div>
</div>
@endsection

@section('content_footer')
    @if(!empty($data) && count($data))
    <div class="row">
        <div class="col-md-12 text-right">Today Total Stock Out Amount: <strong>{{ $todayTotal }}</strong></div>
    </div>
    @endif
@endsection

@push('page_script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
@endpush

@push('page_js')
<script>
$(document).ready( function () {
    $('#todayStockOutTable').DataTable({
        //responsive: true,
        scrollX: true,
        "pageLength": 25,
        order: [[0, 'asc']],
        'columnDefs': [{
            'targets': [6],
            'orderable': false
        }]
    });
});
</script>
@endpush