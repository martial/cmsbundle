$(function() {




// used for the lang tabulations
$('#langTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
});




// wysiwig for default description
// $('#scrclub_cmsbundle_posttype_description').wysihtml5();

// for sub menu
jQuery('.submenu').hover(function() {
    jQuery(this).children('ul').removeClass('submenu-hide').addClass('submenu-show');
},function() {
    jQuery(this).children('ul').removeClass('.submenu-show').addClass('submenu-hide');
}).find('a:first').append(' &raquo; ');




// go sortable and update database on the fly

$('.sortable-thumb').sortable({

    update: function(event, ui) {
        var order = $(this).sortable('toArray', {attribute: 'id'});

        $.ajax({
            type: 'post',
            cache: false,
            url: Routing.generate('scrclub_cms_updateMediaOrder'),
            data: {data: order},
            success: function(data) {

            }

        });

    }

});

$('#mediaset-tab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
});





assignModalLinks();
assignRemoveAction();
assignNodeToMedia();



});


