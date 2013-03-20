$(function () {

    $('#usertab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })

    $('.delete-template').each(function () {

        $(this).click(function (e) {

            e.preventDefault();

            var id = $($(this).parents().get()[1]).attr('id');

            bootbox.confirm("{{ 'wannado.this' | trans }}", function (result) {

                if (result) {
                    $.ajax({
                        url    :Routing.generate('scrclub_cms_deletetemplate', {id:id }),
                        type   :"post",
                        success:function (data) {
                            // delete li !
                            $("#" + id).remove();
                            $($(this).parents().get()[1]).hide(300, function () {
                                $(this).remove();
                            });

                        }

                    });
                }
            });

        });

    });

})