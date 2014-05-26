jQuery(document).ready(function($){
    function nsf_reset_widget_transient(sidebar_id) {

        // Run our AJAX call to delete our site transient
        $.ajax({
            type : 'post',
            dataType : 'json',
            url : ajaxurl,
            data : {
                'action' : 'nsf-reset-transient',
                'nsf-widget-nonce' : nsf_AJAX.nsf_widget_nonce,
                'sidebar' : sidebar_id
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                console.error( thrownError );
            }
        });
    }

    // If one of our update buttons is clicked on a single widget
    $( '.widgets-holder-wrap' ).on( 'click', '.widget-control-remove, .widget-control-close, .widget-control-save', function() {
        // Get our parent, or sidebar, ID
        var widget_parent_div = $(this).parents().eq(5).attr( 'id' );
        // Run our function
        nsf_reset_widget_transient(widget_parent_div);
    });

    $('.widget').on('mouseup', function(){
        var widget_parent_div = $(this).parents().eq(5).attr( 'id' );
        nsf_reset_widget_transient(widget_parent_div);
    });



});
