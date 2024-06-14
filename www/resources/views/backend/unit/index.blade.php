@extends('backend.layout.app')

@push('page_style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" />
@endpush

@section('page_header', 'Unit Management')
@section('page_breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Unit Management</a></li>
    <li class="breadcrumb-item active">All Units</li>
@endsection

@section('content_title', 'All Units')
@section('content_buttons')
    <a href="{{ route('unit.add') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Unit</a>
@endsection

@section('content_body')
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover nowrap" id="unitTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 40px;">SL</th>
                        <th>Unit Name</th>
                        <th>Short Name</th>
                        <th>Sub Unit</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="width: 60px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($all_units) && count($all_units))
                    @php $sl = 1; @endphp
                    @foreach($all_units as $key => $value)
                        <tr>
                            <td>{{ $sl }}</td>
                            <td>{{ $value->name }}</td>
                            <td>{{ $value->short_name }}</td>
                            <td>
                                {{ $value->child_unit_value }}
                                @if(!empty($value->subUnit) && !empty($value->subUnit->short_name))
                                    {{ $value->subUnit->short_name }}
                                @endif
                            </td>
                            <td>{!! ($value->status == 1) ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>' !!}</td>
                            <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                            <td>
                                <a href="{{ route('unit.edit', array('id' => $value->id)) }}" class="edit-unit-btn"><i class="far fa-edit text-success"></i></a>
                                &nbsp;
                                <a href="{{ route('unit.delete', array('id' => $value->id)) }}" class="remove-unit-btn"><i class="far fa-trash-alt text-danger"></i></a>
                            </td>
                        </tr>
                        @php $sl++; @endphp
                    @endforeach
                @else
                    <tr>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td style="display:none;"></td>
                        <td colspan="6">No unis found. Please add unit</td>
                    </tr>
                @endif
                </tbody>
            </table> 
        </div>
    </div>
</div>
@endsection

@section('content_footer')
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
    $('#unitTable').DataTable({
        //responsive: true,
        scrollX: true,
        "pageLength": 25,
        order: [[0, 'asc']],
        'columnDefs': [{
            'targets': [5],
            'orderable': false
        }]
    });
    $('body').on('click', '.remove-unit-btn', function(e) {
        e.preventDefault();
        let deleteUrl = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this unit",
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