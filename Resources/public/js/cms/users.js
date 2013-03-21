$('.user-name').each(function () {
    $(this).tooltip();
})

$('#usertab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
})

$('.delete-user').each(function () {
    $(this).click(function () {

        var id = $(this).attr('id');

        bootbox.confirm("<i class='icon-warning-sign'></i> " + translations["wanadoo.this"], function (result) {

            if (result) {
                $.ajax({
                    type   :'post',
                    cache  :false,
                    url    :deleteUserPath,
                    data   :{data:id},
                    success:function (data) {

                        location.reload();

                    }
                });
            }
        });
    })
});