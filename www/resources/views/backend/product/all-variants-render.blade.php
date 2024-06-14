<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped table-hover onex-datatable nowrap" id="dataTable" style="width: 100%;">
        <thead>
            <tr>
                <th class="onex-xxs">SL</th>
                <th class="onex-lg">Base Product</th>
                <th>Image</th>
                <th class="onex-xl">Variant Name</th>
                <th class="onex-md">Brand</th>
                <th class="onex-sm">Unit</th>
                <th class="onex-sm">Price</th>
                <th class="onex-xxs">Bundle</th>
                <th class="onex-xxs">Free</th>
                <th class="onex-md">Category</th>
                <th class="onex-md">Sub-Category</th>
                <th class="onex-sm">Status</th>
                <th class="onex-sm">Created At</th>
                <th class="onex-sm">Modified At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($data) && count($data))
            @php $sl = 1; @endphp
            @foreach($data as $key => $value)
                <tr>
                    <td>{{ $sl }}</td>
                    <td>@if(!empty($value->baseProduct) && !empty($value->baseProduct->name)){{ $value->baseProduct->name }}@endif</td>
                    <td>
                        @if(!empty($value->image))
                            <img src="{{ asset('public/uploads/images/products/thumbnail/' . $value->image) }}" class="dt-table-image"/>
                        @else
                            <img src="{{ asset('public/images/blank_image.png') }}" class="dt-table-image"/>
                        @endif
                    </td>
                    <td>{{ $value->name }}<br/><strong>SKU:</strong> {{ $value->sku }}</td>
                    <td>@if(!empty($value->productBrand) && !empty($value->productBrand->name)){{ $value->productBrand->name }}@endif</td>
                    <td>@if(!empty($value->productUnit) && !empty($value->productUnit->name)){{ $value->productUnit->name }}@endif</td>
                    <td>{{ $value->price }}</td>
                    <td>{!! ($value->is_bundle_product == 1) ? '<span class="text-success">YES</span>' : '<span class="text-danger">NO</span>' !!}</td>
                    <td>{!! ($value->have_free_product == 1) ? '<span class="text-success">YES</span>' : '<span class="text-danger">NO</span>' !!}</td>
                    <td>@if(!empty($value->baseProduct) && !empty($value->baseProduct->productCategory) && !empty($value->baseProduct->productCategory->name)){{ $value->baseProduct->productCategory->name }}@endif</td>
                    <td>@if(!empty($value->baseProduct) && !empty($value->baseProduct->productSubCategory) && !empty($value->baseProduct->productSubCategory->name)){{ $value->baseProduct->productSubCategory->name }}@endif</td>
                    <td>{!! ($value->status == 1) ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>' !!}</td>
                    <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                    <td>{{ !empty($value->updated_at) ? date('d-m-Y', strtotime($value->updated_at)) : date('d-m-Y', strtotime($value->created_at)) }}</td>
                    <td class="action-col">
                        <div class="btn-group">
                            <a href="{{ route('product.variant.editVariants', array('id' => $value->id)) }}" class="btn edit-product-btn"><i class="far fa-edit text-success"></i></a>
                            <a href="{{ route('product.variant.deleteVariants', array('id' => $value->id)) }}" class="btn remove-product-btn"><i class="far fa-trash-alt text-danger"></i></a>
                            {{--<div class="btn-group">
                                <a href="javascript:void(0);" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fas fa-ellipsis-v text-navy"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('user.profileInformation', array('id' => $value->id)) }}">Meta Fields</a>
                                    <a class="dropdown-item" href="{{ route('user.profileInformation', array('id' => $value->id)) }}">More Images</a>
                                    <h5 class="dropdown-header">Settings</h5>
                                    <a class="dropdown-item" href="{{ route('product.variant.free.allVariantFree', array('variant_id' => $value->id)) }}">Free Product</a>
                                    <a class="dropdown-item" href="{{ route('product.variant.bundle.allVariantBundle', array('variant_id' => $value->id)) }}">Bundle Product</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">View Details</a>
                                </div>
                            </div>--}}
                        </div>
                    </td>
                </tr>
                @php $sl++; @endphp
            @endforeach
        @else
            <tr>
                <td colspan="15">No product variants found. Please create product variant</td>
            </tr>
        @endif
        </tbody>
    </table> 
</div>
@if(!empty($data) && count($data))
    <div class="onex-pagination">{!! $data->withQueryString()->links() !!}</div>
@endif