$(document).ready(function () {

    $("#lang_combo").select2({
            placeholder:translations['choose.lang']
        }
    );

    $('#validate_lang').avgrund({
        height              :130,
        holderClass         :'custom',
        showClose           :false,
        showCloseText       :'Close',
        enableStackAnimation:true,
        onBlurContainer     :'.container',
        template            :alertTemplate
    });

    $('#submit_lang').click(function () {
        updateLangs();
    })

});

function updateLangs() {

    var langValues = $("#lang_combo").val() || [];

    $.ajax({
        type   :'post',
        cache  :false,
        url    :updateLangUrl,
        data   :{data:langValues},
        success:function (data) {

            $('#validate_lang').popover({

                placement:"top",
                trigger  :"manual",
                title    :translations['status'],
                content  :translations['update.success']

            });
            $('#validate_lang').popover('show');
            setTimeout(function () {
                $('#validate_lang').popover('hide');
            }, 4000);
        }

    });
}