$(function () {
    $('.form-validate').each(function () {
        var $form = $(this);
        $form.validate();
    });
});

$(function () {
    $('.check_all').each(function () {
        var $chk_all = $(this);
        $chk_all.change(function () {
            $($chk_all.attr('data-target'))
                    .not(':disabled')
                    .not('[readonly]')
                    .prop('checked', $chk_all.prop('checked'));
        });
    });
});

$(function () {
    $('.datepicker').each(function () {
        $(this).datepicker({
            todayHighlight: true,
            format: "dd/mm/yyyy"
        });
    });
    $('.timepicker').each(function () {
        $(this).timepicker({
            showMeridian: false
        });
    });
});

function confirm_delete() {
    return confirm('Bạn chắc chắn muốn xóa?');
}