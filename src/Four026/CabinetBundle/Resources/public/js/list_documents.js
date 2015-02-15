
$(function() {

    $('.ellipsis').dotdotdot({});

    $('.casefile').click(function() {
        window.location.assign($(this).data('readpath'));
    });
});