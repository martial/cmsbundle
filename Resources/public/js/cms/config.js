$(document).ready(function () {

    $("#lang_combo").select2({
            placeholder:translations['choose.lang']
        }
    );


    $('#submit_lang').unbind("click");
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

            $('#submit_lang').popover({

                placement:"top",
                trigger  :"manual",
                title    :translations['status'],
                content  :translations['update.success']

            });
            $('#submit_lang').popover('show');
            setTimeout(function () {
                $('#submit_lang').popover('hide');
            }, 4000);
        }

    });
}