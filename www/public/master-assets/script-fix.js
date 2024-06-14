$(document).ready(function() {
    $('.onex-select2').select2({
        width: '100%',
        allowClear: true,
        placeholder: 'Select an option',
        dropdownPosition: 'below'
    });
	toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    $('#SmoothScrollToTopBtN').on('click', function () {
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
    $('body').on('click', '.btn-reload', function() {
        window.location.reload();
    });
    $('body').on('select2:select', '.onex-select2', function (e) { 
        if($(this).val() != '') {
            $('#' + $(this).attr('id') + '-error').hide();
            $(this).next('span.select2-container').removeClass('select2-custom-error');
            $(this).parent().find('.onex-form-lebel').removeClass('onex-error-label');
        }
    });
    $('.modal').on('show.bs.modal', function() {
        $(this).show();
        setModalMaxHeight(this);
    });
    $('body').on('keypress', '.onlyNumber', function(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    });
    $('.onex-check-all-ckb').on('click',function() {
        var isCK = $(this).is(':checked');
        if(isCK == true){
            $('.ckbs').prop('checked', true);
        }
        if(isCK == false){
            $('.ckbs').prop('checked', false);
        }
        if($('.ckbs:checked').length) {
            $('.ckb-action-btn').removeAttr('disabled');
        } else {
            $('.ckb-action-btn').attr('disabled', 'disabled');
        }
        colMark();
    });
    $('.ckbs').on('click', function() {
        var c = 0;
        $('.ckbs').each(function()  {
            colMark();
            if($(this).is(':checked')) {
                c++;
            }
        });
        if(c == 0) {
            $('.onex-check-all-ckb').prop('checked', false);
            $('.ckb-action-btn').attr('disabled', 'disabled');
        }
        if(c > 0) {
            $('.onex-check-all-ckb').prop('checked', true);
            $('.ckb-action-btn').removeAttr('disabled');
        }
    });

});

const displayLoading = (timer = 10000, title = 'Please Wait...', text = "System Processing Your Request") => {
    Swal.fire({
        title: title,
        text: text,
        allowEscapeKey: false,
        allowOutsideClick: false,
        timer: timer,
        didOpen: () => {
            Swal.showLoading()
        }
    });
}
const closeSwal = () => {
    swal.close();
}
const displayToast = () => {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Its copied!',
        showConfirmButton: false,
        timer: 1000
    });
}
const displayAlert = (icon = 'success', title = '', html = '', showConfirmButton = true, confirmButtonText = 'OK') => {
    Swal.fire({
        icon: icon,
        title: title,
        html: html,
        confirmButtonColor: '#0d6efd',
        confirmButtonText: confirmButtonText,
        showConfirmButton: showConfirmButton
    });
}

const totalPriceCalculation = (price, quantity) => {
    if (price == '' || quantity == '') {
        return 0;
    }
    let _price = parseFloat(price);
    let _quantity = parseFloat(quantity);
    if (!isNaN(_price) && !isNaN(_quantity)) {
        return (_price * _quantity).toFixed(2);
    }
    return 0;
}

function setModalMaxHeight(element) {
    this.$element     = $(element);  
    this.$content     = this.$element.find('.modal-content');
    var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
    var dialogMargin  = $(window).width() < 768 ? 20 : 60;
    var contentHeight = $(window).height() - (dialogMargin + borderWidth);
    var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
    var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
    var maxHeight     = contentHeight - (headerHeight + footerHeight);
  
    this.$content.css({
        'overflow': 'hidden'
    });
    
    this.$element
      .find('.modal-body').css({
        'max-height': maxHeight,
        'overflow-y': 'auto'
    });
}
  
$(window).resize(function() {
    if ($('.modal.in').length != 0) {
        setModalMaxHeight($('.modal.in'));
    }
});

$(window).on('scroll', function () {
    if ($(this).scrollTop() > 200) {
        $('#SmoothScrollToTopBtN').fadeIn();
    } else {
        $('#SmoothScrollToTopBtN').fadeOut();
    }
});

function colMark() {
    $('.ckbs').each(function() {
        if($(this).is(':checked')) {
            $(this).parents('tr').css('background-color', '#ffe6e6');
        } else {
            $(this).parents('tr').removeAttr('style');
        }
    });
}

function loadingPlaceholder() {
    return `<div class="ph-item">
        <div class="ph-col-12">
            <div class="ph-row">
                <div class="ph-col-4 big"></div>
                <div class="ph-col-8 empty"></div>
                <div class="ph-col-6"></div>
                <div class="ph-col-6 empty"></div>
                <div class="ph-col-12"></div>
            </div>
        </div>
    </div>`;
}

function select2OptionProductImage(imagePath, image) {
    if (image && imagePath) {
        return `<img src="${imagePath}/${image}" class="select2-option-avatar-image" />`;
    }
    return `<svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 512 512"><path d="M488.6 250.2L392 214V105.5c0-15-9.3-28.4-23.4-33.7l-100-37.5c-8.1-3.1-17.1-3.1-25.3 0l-100 37.5c-14.1 5.3-23.4 18.7-23.4 33.7V214l-96.6 36.2C9.3 255.5 0 268.9 0 283.9V394c0 13.6 7.7 26.1 19.9 32.2l100 50c10.1 5.1 22.1 5.1 32.2 0l103.9-52 103.9 52c10.1 5.1 22.1 5.1 32.2 0l100-50c12.2-6.1 19.9-18.6 19.9-32.2V283.9c0-15-9.3-28.4-23.4-33.7zM358 214.8l-85 31.9v-68.2l85-37v73.3zM154 104.1l102-38.2 102 38.2v.6l-102 41.4-102-41.4v-.6zm84 291.1l-85 42.5v-79.1l85-38.8v75.4zm0-112l-102 41.4-102-41.4v-.6l102-38.2 102 38.2v.6zm240 112l-85 42.5v-79.1l85-38.8v75.4zm0-112l-102 41.4-102-41.4v-.6l102-38.2 102 38.2v.6z"/></svg>`;
}