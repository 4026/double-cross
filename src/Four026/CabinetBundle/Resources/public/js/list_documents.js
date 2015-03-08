$(function() {
    var client = new ZeroClipboard($('#btn_copy'));
    client.on( "ready", function( readyEvent ) {
        client.on( "aftercopy", function( event ) {
            var $this = $(event.target);
            $this
                .removeClass('btn-info')
                .addClass('btn-success')
                .html('<span class="glyphicon glyphicon-ok"></span> Copied!')
            ;
            $(document.body).click(function() {
                $this
                    .removeClass('btn-success')
                    .addClass('btn-info')
                    .html('<span class="glyphicon glyphicon-copy"></span> Copy')
                ;
            })
        } );
    } );

    $('.ellipsis').dotdotdot({});

    $('.casefile').click(function() {
        window.location.assign($(this).data('readpath'));
    });
});