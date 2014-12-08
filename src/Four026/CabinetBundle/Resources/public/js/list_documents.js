
$(function() {
    $('.casefile').click(function() {
        window.location.assign($(this).data('readpath'));
    });
});