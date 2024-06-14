@extends('backend.layout.appx')

@push('page_style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('page_header', 'Stock Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Stock Management</a></li>
    <li class="breadcrumb-item active">Stock Report</li>
@endsection

@section('content_body')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stock Report By Date Range</h3>
            <div class="card-tools">
                <a href="{{ route('stock.in') }}" class="btn btn-primary btn-sm"><i class="fas fa-cubes"></i> Today Stock In</a>
                <a href="{{ route('stock.out') }}" class="btn btn-primary btn-sm"><i class="fas fa-layer-group"></i> Today Stock Out</a>
            </div>
        </div>
        <div class="card-body">
            <form name="frm" id="frmx" action="" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="report_date_type" id="reportDateType" class="form-control" required="required">
                                <option value="created_at" @if(!empty($_GET['report_date_type']) && $_GET['report_date_type'] == 'created_at') selected="selected" @endif>By Entry Date</option>
                                <option value="issue_receive_date" @if(!empty($_GET['report_date_type']) && $_GET['report_date_type'] == 'issue_receive_date') selected="selected" @endif>By Issued/Received Date</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="stock_report_date_range" id="stockReportDateRange" class="form-control" placeholder="Select Dates" required="required" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success" id="reportSearchBtn"><i class="fas fa-search"></i> Get Stock Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="content">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title"><strong>Stock In Report</strong></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover nowrap" id="todayStockInTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">SL</th>
                                    <th>Received Date</th>
                                    <th>Challan No</th>
                                    <th>Received From</th>
                                    <th>Product Name</th>
                                    <th>QTY</th>
                                    <th>Unit</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($stockIn) && count($stockIn))
                                @php 
                                    $sl = 1;
                                    $stockInTotal = 0; 
                                @endphp
                                @foreach($stockIn as $key => $value)
                                    @php
                                        $user = Helper::userInfo($value->user_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $sl }}</td>
                                        <td>{{ date('m-d-Y', strtotime($value->stock_received_date)) }}</td>
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
                                        $stockInTotal = $stockInTotal + $value->unit_total;
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
                                    <td colspan="10">No stock records found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
        @if(!empty($stockIn) && count($stockIn))
        <div class="row">
            <div class="col-md-12 text-right">Total Stock In Amount: <strong>{{ $stockInTotal }}</strong></div>
        </div>
        @endif
        </div>
    </div>
</section>

<section class="content">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title"><strong>Stock Out Report</strong></h3>
        </div>
        <div class="card-body">
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
                            @if(!empty($stockOut) && count($stockOut))
                                @php 
                                    $sl = 1;
                                    $stockOutTotal = 0; 
                                @endphp
                                @foreach($stockOut as $key => $value)
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
                                        $stockOutTotal = $stockOutTotal + $value->unit_total;
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
                                    <td colspan="10">No stock records found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
        @if(!empty($stockOut) && count($stockOut))
        <div class="row">
            <div class="col-md-12 text-right">Total Stock Out Amount: <strong>{{ $stockOutTotal }}</strong></div>
        </div>
        @endif
        </div>
    </div>
</section>

<input type="hidden" id="userSearchDate" value="@if(!empty($_GET['stock_report_date_range'])){{ $_GET['stock_report_date_range'] }}@endif"/>
@endsection


@push('page_script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endpush

@push('page_js')
<script>
$(document).ready( function () {
    let autoSetStartDate = moment().subtract(29, 'days');
    let autoSetEndDate = moment();

    function cb(autoSetStartDate, autoSetEndDate) {
        $('#stockReportDateRange span').html(autoSetStartDate.format('DD/MM/YYYY') + ' - ' + autoSetEndDate.format('DD/MM/YYYY'));
    }
    $('#stockReportDateRange').daterangepicker({
        maxDate: new Date(),
        startDate: autoSetStartDate, 
        endDate: autoSetEndDate,
        'showDropdowns': true,
        'showWeekNumbers': true,
        'showISOWeekNumbers': true,
        //'autoApply': true,
        'alwaysShowCalendars': true,
        locale: {
            format: 'DD/MM/YY'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(autoSetStartDate, autoSetEndDate);
    $('#stockReportDateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });
    $('#stockReportDateRange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val(autoSetStartDate.format('DD/MM/YYYY') + ' - ' + autoSetEndDate.format('DD/MM/YYYY'));
    });
    $('#stockReportDateRange').on('keypress', function() {
        return false;
    });
    $('#todayStockInTable').DataTable({
        //responsive: true,
        scrollX: true,
        "pageLength": 25,
        order: [[0, 'asc']],
        'columnDefs': [{
            'targets': [6],
            'orderable': false
        }]
    });
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