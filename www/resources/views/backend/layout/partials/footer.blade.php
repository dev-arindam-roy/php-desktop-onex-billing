<script src="{{ asset('public') }}/master-assets/jquery/jquery.min.js"></script>
<script src="{{ asset('public') }}/master-assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('public') }}/master-assets/pace-progress/pace.min.js"></script>
<script src="{{ asset('public') }}/master-assets/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="{{ asset('public') }}/master-assets/select2/js/select2.full.min.js"></script>
<script src="{{ asset('public') }}/master-assets/js/adminlte.min.js"></script>
<script src="{{ asset('public') }}/master-assets/toastr/toastr.min.js"></script>
<script src="{{ asset('public') }}/master-assets/sweetalert2/sweetalert2@8.js"></script>
@stack('page_script')
<script src="{{ asset('public') }}/master-assets/script-fix.js"></script>
<script>
$(document).ready(function() {
    jQuery('body').css('height', jQuery('#bodyHeight').css('height'));
    $('body').on('click', '.table-image-remove', function(e) {
        e.preventDefault();
        let _this = $(this);
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this image",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed) {
                displayLoading();
                let _tabName = $(this).data('table-name');
                let _tabField = $(this).data('table-field');
                let _tabId = $(this).data('table-row-id');
                if(_tabName != undefined && _tabField != undefined && _tabId != undefined && _tabName != '' && _tabField != '' && _tabId != '') {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('ajax.removeTableImage') }}",
                        data: {
                            'tab_name': _tabName,
                            'tab_field': _tabField,
                            'tab_id': _tabId,
                            '_token': "{{ csrf_token() }}"
                        },
                        cache: false,
                        beforeSend: function() {
                            displayLoading();
                        },
                        success: function(data) {
                            if(data && data.is_success) {
                                _this.parent('.onex-preview-imgbox').remove();    
                            }
                            closeSwal();
                        },
                        error: function() {
                            displayAlert('error', 'Server Error!', 'Please reload the page and try again');
                        }
                    });
                } else {
                    displayAlert('error', 'Something Wrong!', 'Please reload the page and try again');
                }
            }
        });
    });
    $('body').on('click', '.onex-pagination .pagination a', function(event) {
        event.preventDefault();
        let _this = $(this);
        let url = _this.attr('href');
        let page = url.split('page=')[1];
        ajaxPagination(url, page);
        $('.onex-pagination ul.pagination li').removeClass('active');
        _this.parent('li').addClass('active');
    });
    function ajaxPagination(pageUrl, page = 1) {
        $.ajax({
            type: 'GET',
            url: pageUrl,
            cache: false,
            datatype: 'html',
            beforeSend: function() {
                displayLoading();
            },
            success: function(data) {
                $("#displayData").empty().html(data);
                closeSwal();
                //location.hash = page;
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
});
</script>
@php
    $toastrType = '';
    $toastrTitle = '';
	$toastrMessage = '';
	if(Session::has('message_type') && Session::has('message_text') && Session::has('message_title')) {
        $toastrType = Session::get('message_type');
        $toastrTitle = Session::get('message_title');
		$toastrMessage = Session::get('message_text');
	}
    $messageHeader = '';
    $messageContent = '';
    if(Session::has('message_header') && Session::has('message_content')) {
        $messageHeader = Session::get('message_header');
        $messageContent = Session::get('message_content');
	}
@endphp
@if($toastrType == 'error')
<script>
$(document).ready(function(){
	toastr.error('{{ $toastrMessage }}', '{{ $toastrTitle }}');
});
</script>
@endif
@if($toastrType == 'success')
<script>
$(document).ready(function(){
	toastr.success('{{ $toastrMessage }}', '{{ $toastrTitle }}');
});
</script>
@endif
@if(!empty($messageHeader) && !empty($messageContent))
<script>
$(document).ready(function(){
    displayAlert('success', '{{ $messageHeader }}',  '{!! $messageContent !!}', true);
});
</script>
@endif
@stack('page_js')
</body>
</html>