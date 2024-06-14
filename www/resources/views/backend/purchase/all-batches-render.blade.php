<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped table-hover onex-datatable nowrap" id="dataTable" style="width: 100%;">
        <thead>
            <tr>
                <th class="onex-xxs">SL</th>
                <th class="onex-md">Batch No</th>
                <th>Name</th>
                <th class="onex-sm">Status</th>
                <th class="onex-md">Created At</th>
                <th class="onex-md">Modified At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($data) && count($data))
            @php $sl = 1; @endphp
            @foreach($data as $key => $value)
                <tr>
                    <th>{{ $sl }}</th>
                    <td>{{ $value->batch_no }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{!! ($value->status == 1) ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>' !!}</td>
                    <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                    <td>{{ !empty($value->updated_at) ? date('d-m-Y', strtotime($value->updated_at)) : date('d-m-Y', strtotime($value->created_at)) }}</td>
                    <td>
                        <a href="{{ route('purchase.edit-batch', array('id' => $value->id)) }}" class="btn edit-batch-btn"><i class="far fa-edit text-success"></i></a>
                        <a href="{{ route('purchase.delete-batch', array('id' => $value->id)) }}" class="btn remove-batch-btn"><i class="far fa-trash-alt text-danger"></i></a>
                    </td>
                </tr>
                @php $sl++; @endphp
            @endforeach
        @else
            <tr>
                <td colspan="7">No batches found. Please create batch for stock entry</td>
            </tr>
        @endif
        </tbody>
    </table> 
</div>
@if(!empty($data) && count($data))
    <div class="onex-pagination">{!! $data->withQueryString()->links() !!}</div>
@endif