$(function () {

    $('.remove-category').each(function () {

        $(this).click(function (e) {

            e.preventDefault();

            var id = $($(this).parents().get()[1]).attr('id');

            bootbox.confirm(translations['wannado.this'], function (result) {

                if (result) {
                    $.ajax({
                        url    :Routing.generate('scrclub_cms_removeCategory', {id:id }),
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
