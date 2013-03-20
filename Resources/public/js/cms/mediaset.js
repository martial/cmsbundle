// here we create an multiuploader instance for each mediaset

$(function () {

    assignRemoveAction();
    assignModalLinks();
    setPlaceHolders();
    assignNodeToMedia();

})

function createUploader(id) {

    var uploader = new qq.FineUploader({
        debug                  :true,
        inputName              :'fos_user_registration_form[image][file]',
        multiple               :true,
        element                :document.getElementById('mediaset-upload-' + id),
        request                :{
            endpoint:Routing.generate('scrclub_cms_fineuploadhandle', {mediaset_id:id })
        },
        text                   :{
            uploadButton:'<i class="icon-upload icon-white"></i> ' + translations['upload.drag']
        },
        template               :'<div class="qq-uploader row ">' +
            '<span class="qq-drop-processing span12"><span>{dropProcessingText}</span><i class="icon-spinner icon-spin"></i></span>' +
            '<div class="qq-upload-drop-area btn span3">{dragZoneText}</div>' +
            '<div class="qq-upload-button btn span3" >{uploadButtonText}</div>' +
            '<div class="qq-upload-button btn span3"><a  href="' + Routing.generate('scrclub_cms_updateEmbedPopOver', {mediasetId:id }) + '" data-target="#embed-edit-modal" ><i class="icon-globe"></i> ' + translations['embed.files'] + '</a></div>' +
            '<ul class="qq-upload-list span12 row-fluid" style="margin-top: 10px; text-align: center;"></ul>' +
            '</div>',
        fileTemplate           :'<li class="alert span2"><a class="close qq-upload-cancel" data-dismiss="alert" href="#">&times;</a>' +
            '<div class="qq-progress-bar clearfix"></div>' +
            '<span class="qq-upload-spinner clearfix"><i class=" icon-spinner icon-spin"></i></span>' +
            '<span class="qq-upload-finished clearfix"></span>' +
            '<span class="qq-upload-file"></span>' +
            '<span class="qq-upload-size clearfix"></span>' +
            '<a class="qq-upload-retry clearfix" href="#">{retryButtonText}</a>' +
            '<span class="qq-upload-status-text">{statusText}</span>' +
            '</li>',
        classes                :{
            success:'alert alert-success',
            fail   :'alert alert-error'

        },
        failedUploadTextDisplay:{
            mode            :'custom',
            maxChars        :40,
            responseProperty:'error',
            enableTooltip   :true
        },
        callbacks              :{
            onComplete:function (id, fileName, responseJSON) {

                // append new view into mediaset

                $('#mediaset-all-' + responseJSON.mediaset_id).find('.media-scroll-container').append(responseJSON.media_html);
                assignRemoveAction();
                assignModalLinks();
                setPlaceHolders();
                assignNodeToMedia();

            }
        }
    });
    assignModalLinks();
}

function assignNodeToMedia() {

    // function for adding pin to media and media to post
    $('.add-media-post').unbind('click');
    $('.add-media-post').each(function () {

        $(this).click(function () {
            //console.log("click");
            addMedia($(this));
        });

    });

}

function addMedia(el) {

    // get media id and media set id
    var elementId = $(el.parents().get()[2]).attr('id');
    var mediaId = elementId.substring(6, elementId.length);

    var mediasetElementId = $(el.parents().get()[6]).attr('id');
    var mediaSetId = mediasetElementId.substring(9, mediasetElementId.length);

    var nodeId = $('.post').attr('id');

    // post
    $.ajax({
        url    :Routing.generate('scrclub_cms_addNodeToMedia', {nodeId:nodeId, mediaId:mediaId }),
        success:function (data) {

            // add media node into the right tab :)
            // we know the media set so
            var mediasetContainer = $('#mediaset-node-' + mediaSetId).find('ul');
            mediasetContainer.append(data);

            assignRemoveAction();
            assignModalLinks();
            setPlaceHolders();

        }

    });

}

function assignRemoveAction() {

    // remove media relation

    $('.remove-media').each(function () {

        $(this).click(function () {

            var mediaNodeId = $($(this).parents().get()[1]).attr('id');

            $.ajax({
                url    :Routing.generate('scrclub_cms_removeNodeToMedia', {mediaNodeId:mediaNodeId }),
                success:function (data) {
                    //console.log(data);
                }

            });

            $($(this).parents().get()[1]).hide(300, function () {
                $(this).remove();
            });

        });

    });

    $('.remove-media-mediaset').each(function () {

        $(this).click(function () {

            var mediaId = $($(this).parents().get()[1]).attr('id');
            mediaId = mediaId.substring(6, mediaId.length);

            $.ajax({
                url    :Routing.generate('scrclub_cms_deletedocument', {id:mediaId }),
                success:function (data) {

                    $('.media-' + mediaId).remove();

                }

            });

        });

    });

}

function assignModalLinks() {

    $('a[data-target=#media-edit-modal]').unbind('click');
    $('a[data-target=#media-edit-modal]').click(function (ev) {

        ev.preventDefault();
        var target = $(this).attr('href');

        var id = $(this).attr('data-medianodeid');
        var mediaId = $(this).attr('data-mediaid');

        ev.preventDefault();
        var target = $(this).attr('href');

        $.ajax({
            cache   :false,
            dataType:'json',
            url     :target,
            success :function (data) {

                $('#media-edit-modal').modal('show');
                $('#media-edit-modal .modal-body').html(data.html);

            }


        });

    });

    $('a[data-target=#embed-edit-modal]').click(function (ev) {
        ev.preventDefault();
        var target = $(this).attr('href');

        $.ajax({
            type    :'GET',
            cache   :false,
            dataType:'json',
            url     :target,
            success :function (data) {

                $('#media-edit-modal').modal('show');
                $('#media-edit-modal .modal-body').html(data.html);
            }

        });

    });

}

function setPlaceHolders() {

    Holder.run({
        images:'.img-thumb-cms'
    });

}

function updateMediasItems(mediaId) {

    //a bit tricky but we would need to get through the node to get medianodeId

    $('.media-post-container .media-' + mediaId).each(function () {

        var mediaNodeId = $(this).attr("id");
        var container = $(this);

        $.ajax({
            type   :'post',
            cache  :false,
            url    :Routing.generate('scrclub_cms_getMediaNodeAjax', { mediaNodeId:mediaNodeId }),
            success:function (data) {

                container.replaceWith(data);
                assignRemoveAction();
                assignModalLinks();
                setPlaceHolders();

            }

        });

        $.ajax({
            type   :'post',
            cache  :false,
            url    :Routing.generate('scrclub_cms_getMediaAjax', { mediaId:mediaId }),
            success:function (data) {
                $('.media-scroll-container .media-' + mediaId).replaceWith(data);
                assignRemoveAction();
                assignModalLinks();
                setPlaceHolders();
            }

        });

    })

}
