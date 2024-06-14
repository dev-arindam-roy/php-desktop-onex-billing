<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped table-hover onex-datatable nowrap" id="dataTable" style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 50px;">SL</th>
                <th style="width: 15%;">Name</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 10%;">Mobile</th>
                <th style="width: 15%;">Role</th>
                <th class="text-center">CRM Access</th>
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
                    <td>
                        @if(!empty($value->userRoles) && count($value->userRoles))
                            @foreach($value->userRoles as $uRole)
                                @if(!empty($uRole->role) && !empty($uRole->role->name))
                                    <span class="role-tag">{{$uRole->role->name}}</span>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td class="text-center">
                        {!! ($value->is_crm_access == 1) ? '<span class="text-success">YES</span>' : '<span class="text-danger">NO</span>' !!}
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
                            <a href="{{ route('user.edit', array('id' => $value->hash_id)) }}" class="btn edit-user-btn"><i class="far fa-edit text-success"></i></a>
                            <a href="{{ route('user.delete', array('id' => $value->hash_id)) }}" class="btn remove-user-btn"><i class="far fa-trash-alt text-danger"></i></a>
                            <div class="btn-group dt-action-dropdown">
                                <a href="javascript:void(0);" id="dLabel{{$value->hash_id}}" class="btn dropdown-toggle" data-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v text-navy"></i></a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel{{$value->hash_id}}">
                                    <a class="dropdown-item" href="{{ route('user.profileInformation', array('id' => $value->hash_id)) }}">View Profile</a>
                                    <h5 class="dropdown-header">Settings</h5>
                                    <a class="dropdown-item" href="{{ route('user.resetUsername', array('id' => $value->hash_id)) }}">Reset Username</a>
                                    <a class="dropdown-item" href="{{ route('user.resetPassword', array('id' => $value->hash_id)) }}">Reset Password</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">View Details</a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @php $sl++; @endphp
            @endforeach
        @else
            <tr>
                <td colspan="9">No users found! Please create user</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@if(!empty($data) && count($data))
    <div class="onex-pagination">{!! $data->withQueryString()->links() !!}</div>
@endif