<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped table-hover onex-datatable nowrap" id="dataTable" style="width: 100%;">
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th class="text-center">Status</th>
                <th class="text-center">Created</th>
                <th style="width: 65px;">Action</th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($data) && count($data))
            @php $sl = 1; @endphp
            @foreach($data as $key => $value)
                <tr>
                    <td>{{ $sl }}</td>
                    <td>{{ $value->first_name . ' ' . $value->last_name }}<br/><span class="td-unique-id">{{ $value->unique_id }}</span></td>
                    <td>{{ $value->email_id }}</td>
                    <td>
                        <i class="fas fa-mobile-alt"></i> {{ $value->phone_number }}
                        @if(!empty($value->whatsapp_number))
                            <br/><span>
                            <i class="fab fa-whatsapp text-success"></i>
                            {{ $value->whatsapp_number }}
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($value->status == 1) 
                            <a href="{{ route('user.lockUnlock', array('id' => $value->hash_id, 'statusId' => 0)) }}" class="lock-unlock-user" title="Lock user"><i class="fas fa-unlock-alt text-success" style="font-size: 22px;"></i></a>
                        @endif
                        @if($value->status == 0) 
                            <a href="{{ route('user.lockUnlock', array('id' => $value->hash_id, 'statusId' => 1)) }}" class="lock-unlock-user" title="Unlock user"><i class="fas fa-lock text-danger" style="font-size: 22px;"></i></a>
                        @endif
                    </td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                    <td class="action-col">
                        <div class="btn-group">
                            <a href="{{ route('customer.edit', array('id' => $value->hash_id)) }}" class="btn edit-user-btn"><i class="far fa-edit text-success"></i></a>
                            <a href="{{ route('customer.delete', array('id' => $value->hash_id)) }}" class="btn remove-user-btn"><i class="far fa-trash-alt text-danger"></i></a>
                        </div>
                    </td>
                </tr>
                @php $sl++; @endphp
            @endforeach
        @else
            <tr>
                <td colspan="7">No customer found! Please create customer</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@if(!empty($data) && count($data))
    <div class="onex-pagination">{!! $data->withQueryString()->links() !!}</div>
@endif