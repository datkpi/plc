function getAjax(url, data, method = "post") {
    return $.ajax({
        url: url,
        data: data,
        method: method
    });
}
function sortImage(elm) {
    if (elm) {
        if ($(elm).parents('.form-group').find('.file-preview-thumbnails .file-initial-thumbs').length) {
            $(elm).parents('.form-group').find('.file-preview-thumbnails .file-initial-thumbs').sortable({
                revert: true,
                stop: function (event, ui) {
                    $(ui.item[0]).parents('.form-group').find("input[type='hidden']").val($(ui.item[0]).parents('.form-group').find('img:visible').map(function () {
                        return $(this).attr('src').replace(/^https:\/\/hatex\.vn\//gm, '').replace(/^\//gm, '');
                    }).get().join(','))
                }
            });
        }
    } else {
    }
}
$('[data-action="delete"]').click(function (e) {
    var elm = this;
    bootbox.confirm("Bạn có chắc chắn muốn xóa?", function (result) {
        if (result === true) {
            $(elm).parents('form').submit();
        }
    });
});

$('[data-action="logout"]').click(function () {
    location.href = "/logout";
})

$('[name="title"]').blur(function () {
    if ($('[name="alias"]').length) {
        getAjax('/api/slugify', {title: $(this).val()}).done(function (resp) {
            $('[name="alias"]').val(resp.alias);
        });
    }
});
function BrowseServer(div,name_of_input) {
    curInputId = div;
    window.KCFinder = {
        callBack: function(url) {
            fileUrl = url;
            //window.KCFinder = null;
            div.innerHTML = '<div style="margin:5px">Loading...</div>';
            var img = new Image();
            img.src = url;
            img.onload = function() {
                var fileName = fileUrl.split('/');
                fileName = fileName[fileName.length-1];
                //Hien tai co anh
                var guid_name = curInputId;
                var elm_parent = jQuery('input[name="'+guid_name+'"]').parents('.form-group');
                if (!jQuery('input[name="'+curInputId+'"]').length && name_of_input){
                    elm_parent = jQuery('input[data-guid="'+name_of_input+'"]').parents('.form-group');
                }
                jQuery(elm_parent).find('.file-input-new').removeClass('file-input-new');
                jQuery(elm_parent).find('.file-caption-name').text(fileName).attr('title',fileName);
                //Hien tai co anh
                if (jQuery('#'+curInputId).length && $(elm_parent).find('.file-preview-image').length){
                    //Remove old preview images
                    if (!$('#'+curInputId+'[multiple]').length) jQuery(elm_parent).find('.file-preview-frame').remove();
                    var count = $(elm_parent).find('.file-preview-image').length;
                    //Adding preview image
                    var html = '<div class="file-preview-frame" id="preview-'+$.now()+'-'+(count)+'" data-fileindex="'+(count)+'" data-template="image"><div class="kv-file-content"> <img src="'+fileUrl+'" class="kv-preview-data file-preview-image" title="'+fileName+'" alt="'+fileName+'" style="width:auto;height:160px;"> </div><div class="file-thumbnail-footer"> <div class="file-footer-caption" title=""> <div class="file-caption-info"></div> <div class="file-size-info"></div> </div> <div class="file-thumb-progress kv-hidden"><div class="progress"> <div class="progress-bar bg-success progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"> Initializing... </div></div></div><div class="file-actions"> <div class="file-footer-buttons"> <button type="button" class="kv-file-remove btn btn-link btn-icon btn-xs" title="Remove file" data-url="" data-key="2"><i class="icon-trash"></i></button> <button type="button" class="kv-file-zoom btn btn-link btn-xs btn-icon" title="View Details"><i class="icon-zoomin3"></i></button> </div></div><span class="file-drag-handle drag-handle-init btn btn-link btn-xs btn-icon" title="Move / Rearrange"><i class="icon-three-bars"></i></span><div class="clearfix"></div></div> </div>';
                    var parent_container = '.file-preview-thumbnails';
                    if (!$(elm_parent).find('.file-preview-thumbnails').length)
                        parent_container = '.file-preview-thumbnails';
                    $(elm_parent).find(parent_container).append(html);
                    $(elm_parent).find('.file-caption-name:last').prepend('<i class="glyphicon glyphicon-file kv-caption-icon"></i>');
                    //Adding value to the input
                    var values = [];
                    if ($('#'+curInputId+'[multiple]').length && jQuery(elm_parent).find('input[type="hidden"]').val()) values.push(jQuery(elm_parent).find('input[type="hidden"]').val());
                    values.push(fileUrl);
                    console.log(values);
                    jQuery(elm_parent).find('input[type="hidden"]').val(values.join(','));

                }
                //Hien tai chua co anh
                else{
                    //Remove old preview images
                    if (!$('#'+curInputId+'[multiple]').length) jQuery(elm_parent).find('.file-preview-frame').remove();
                    //Adding preview image
                    var html = '<div class="file-preview"> <div class="close fileinput-remove">×</div> <div class="file-drop-disabled"> <div class="file-preview-thumbnails"> <div class="file-live-thumbs"> <div class="file-preview-frame" id="preview-'+$.now()+'-0" data-fileindex="0" data-template="image"><div class="kv-file-content"> <img src="'+fileUrl+'" class="kv-preview-data file-preview-image" title="'+fileName+'" alt="'+fileName+'" style="width:auto;height:160px;"> </div><div class="file-thumbnail-footer"> <div class="file-footer-caption" title="'+fileName+'">'+fileName+' <br></div> <div class="file-actions"> <div class="file-footer-buttons"> <button type="button" class="kv-file-zoom btn btn-link btn-xs btn-icon" title="View Details"><i class="icon-zoomin3"></i></button>     </div>  <div class="file-upload-indicator" title="Not uploaded yet"><i class="icon-file-plus text-slate"></i></div> <div class="clearfix"></div> </div> </div> </div>  </div> </div></div> <div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div> <div class="kv-fileinput-error file-error-message" style="display: none;"></div> </div> </div>';
                    $(elm_parent).find('.file-input').prepend(html);
                    $(elm_parent).find('.file-caption-name').prepend('<i class="glyphicon glyphicon-file kv-caption-icon"></i>');
                    //Adding value to the input
                    jQuery(elm_parent).find('input[type="hidden"]').val(fileUrl);
                }
                $(elm_parent).find('.file-preview-thumbnails').each(function(){
                    if (!$(this).html().trim().length) $(this).parents('.file-preview').remove();
                });
            }

        }
    };
    window.open("/assets/backend/kcfinder-3.20" + '/browse.php?type=images', //type=image&
            'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
            'directories=0, resizable=1, scrollbars=0, width=800, height=600'
            );
}
function openKCFinder(div) {
    var field = div.id;
    window.KCFinder = {
        callBack: function (url) {
            window.KCFinder = null;
            var img = new Image();
            img.src = url;
            var image_id = jQuery.now();
            var image_idx = $('.form-group[data-field="' + field + '"]').find('.file-initial-thumbs .file-preview-frame:visible').length + 1;
            var filename = url.substring(url.lastIndexOf('/') + 1);
            img.onload = function () {
                var html = '' +
                        '<div class="file-preview-frame krajee-default kv-preview-thumb" id="preview-' + image_id + '-' + image_idx + '" data-fileindex="' + image_idx + '" data-template="image">' +
                        '<div class="kv-file-content">' +
                        '<img src="' + url + '"' +
                        'class="file-preview-image kv-preview-data" title="' + filename + '" alt="' + filename + '" style="width:auto;height:160px;">' +
                        '</div>' +
                        '<div class="file-thumbnail-footer"> <div class="file-footer-caption" title=""> <div class="file-caption-info"></div> <div class="file-size-info"></div> </div> <div class="file-thumb-progress kv-hidden"><div class="progress"> <div class="progress-bar bg-success progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"> Initializing... </div></div></div><div class="file-actions"> <div class="file-footer-buttons"> <button type="button" class="kv-file-remove btn btn-link btn-icon btn-xs" title="Remove file" data-url="" data-key="1"><i class="icon-trash"></i></button> <button type="button" class="kv-file-zoom btn btn-link btn-xs btn-icon" title="View Details"><i class="icon-zoomin3"></i></button> </div></div><span class="file-drag-handle drag-handle-init btn btn-link btn-xs btn-icon" title="Move / Rearrange"><i class="icon-three-bars"></i></span><div class="clearfix"></div></div>' +
                        '</div>';
                if ($('.form-group[data-field="' + field + '"]').find('.file-initial-thumbs .file-preview-frame:visible').length) {
                    if ($('#' + field).attr('multiple')) {
                        $('.form-group[data-field="' + field + '"]').find('.file-drop-zone-title').remove();
                        $('.form-group[data-field="' + field + '"]').find('.file-initial-thumbs:visible').append(html);
                        $('input[name="' + field + '"]').val($('input[name="' + field + '"]').attr('value') + ',' + url);
                    } else {
                        $('.form-group[data-field="' + field + '"]').find('.file-drop-zone-title').remove();
                        $('.form-group[data-field="' + field + '"]').find('.file-initial-thumbs:visible').html(html);
                        $('input[name="' + field + '"]').val(url);
                    }
                } else {
                     $('.form-group[data-field="' + field + '"]').find('.file-drop-zone-title').remove();
                    $('.form-group[data-field="' + field + '"]').find('.file-preview-thumbnails').html('<div class="file-initial-thumbs">' + html + '</div>');
                    $('input[name="' + field + '"]').val(url);
                }
                $('.form-group[data-field="' + field + '"]').find('.file-input-new').removeClass('file-input-new');
                $('.form-group[data-field="' + field + '"]').find('.file-caption-name').text(filename);
            }
        }
    };
    window.open("/assets/backend/kcfinder-3.20" + '/browse.php?type=images', //type=image&
            'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
            'directories=0, resizable=1, scrollbars=0, width=800, height=600'
            );
}
jQuery(function () {
    if ($('.file-input-overwrite').length) {
        var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
                '  <div class="modal-content">\n' +
                '    <div class="modal-header">\n' +
                '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
                '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
                '    </div>\n' +
                '    <div class="modal-body">\n' +
                '      <div class="floating-buttons btn-group"></div>\n' +
                '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>\n';
        // Buttons inside zoom modal
        var previewZoomButtonClasses = {
            toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
            fullscreen: 'btn btn-default btn-icon btn-xs',
            borderless: 'btn btn-default btn-icon btn-xs',
            close: 'btn btn-default btn-icon btn-xs'
        };
        // Icons inside zoom modal classes
        var previewZoomButtonIcons = {
            prev: '<i class="icon-arrow-left32"></i>',
            next: '<i class="icon-arrow-right32"></i>',
            toggleheader: '<i class="icon-menu-open"></i>',
            fullscreen: '<i class="icon-screen-full"></i>',
            borderless: '<i class="icon-alignment-unalign"></i>',
            close: '<i class="icon-cross2 font-size-base"></i>'
        };
        // File actions
        var fileActionSettings = {
            zoomClass: 'btn btn-link btn-xs btn-icon',
            zoomIcon: '<i class="icon-zoomin3"></i>',
            dragClass: 'btn btn-link btn-xs btn-icon',
            dragIcon: '<i class="icon-three-bars"></i>',
            removeClass: 'btn btn-link btn-icon btn-xs',
            removeIcon: '<i class="icon-trash"></i>',
            indicatorNew: '<i class="icon-file-plus text-slate"></i>',
            indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
            indicatorError: '<i class="icon-cross2 text-danger"></i>',
            indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
        };
        $(".file-input-overwrite").each(function () {
            var field = this.dataset.field;
            var images_str =$(this).parents('.div-image').find('.image_data').val();
            var url = [];
            var file_size = [];
            var file_name = [];
            var images = [];
            var image_info = [];
            if (images_str) {
                url = images_str.split(',');
                file_size = [];// this.dataset.size.split('|');
                file_name = [];// this.dataset.name.split('|');
            }
            if (url && url.length) {
                for (i = 0; i < url.length; i++) {
                    images.push(((url[i].match(/^upload/) || url[i].match(/^public/)) ? '/' : '') + url[i]);
                    //var tmp = file_name[i].split('/');
                    image_info.push({key: (i + 1)});
                }
            }
            $(this).fileinput({
                removeLabel: 'Xóa',
                browseLabel: 'Chọn',
                browseIcon: '<i class="icon-file-plus"></i>',
                uploadIcon: '<i class="icon-file-upload2"></i>',
                removeIcon: '<i class="icon-cross3"></i>',
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>',
                    modal: modalTemplate
                },
                showUpload: false,
                initialPreview: images,
                initialPreviewConfig: image_info,
                initialPreviewAsData: true,
                overwriteInitial: true,
                previewZoomButtonClasses: previewZoomButtonClasses,
                previewZoomButtonIcons: previewZoomButtonIcons,
                fileActionSettings: fileActionSettings,

            });
            sortImage(this);
        });
    }
});
//delete img file upload
// $(document).on("click", ".kv-file-remove", function () {
//     var image = $(this).parents('.file-preview-frame').find('.file-preview-image').attr("src");
//     var $input = $(this).parents('.div-image').find('.image_data');
//     var images = $(this).parents('.div-image').find('.image_data').val();
//     images = images.split(',');
//     var index = images.indexOf(image);
//     if (index > -1) {
//       images.splice(index, 1);
//     }
//     var res = images.join(',');
//     $input.val(res);
//     $(this).parents('.file-preview-frame').remove();
// });

$('[data-action="change-status"]').click(function (e) {
    e.preventDefault();
    getAjax('/api/change-status', {order_id: this.dataset.order_id, status: this.dataset.status}).done(function () {
        location.reload();
    })
});
if($('.upload-image').length){
     $('.upload-image').each(function(){
     var images = $(this).parents('.div-image').find('.image_data').val();
     console.log(images);
     if(images === ''){
     }else{
         images = images.split(',');
         for(i=0;i < images.length;i++){
            var name = images[i];
            name = name.split('/');
            $(this).parents('.div-image').find('.file-drop-zone').append('<div class="file-preview-frame krajee-default  file-preview-initial file-sortable kv-preview-thumb">'+
                                                                                       '<div class="kv-file-content">'+
                                                                                                    '<img src="'+images[i]+'" class="file-preview-image kv-preview-data" title="'+name[4]+'" alt="'+name[4]+'" style="width:auto;height:auto;max-width:100%;max-height:100%;">'+
                                                                                        '</div>'+
                                                                                        '<div class="file-thumbnail-footer">'+
                                                                                        '<div class="file-footer-caption" title="'+name[4]+'">'+
                                                                                            '<div class="file-caption-info">'+name[4]+'</div>'+
                                                                                            '<div class="file-size-info"></div>'+
                                                                                        '</div>'+
                                                                                        '<div class="file-actions">'+
                                                                                            '<div class="file-footer-buttons">'+
                                                                                                  '<button type="button" class="kv-file-remove btn btn-link btn-icon btn-xs" title="Remove file" data-url="" data-key="1"><i class="icon-trash"></i></button>'+
                                                                                                  '<button type="button" class="kv-file-zoom btn btn-link btn-xs btn-icon" title="View Details"><i class="icon-zoomin3"></i></button>'+
                                                                                              '</div>'+
                                                                                        '</div>'+
                                                                                    '</div>'+
                                                                                    '</div>');
         }
     }
     });

};
$('body').delegate('.upload-image','change', function() {
    var file_data = $(this).prop('files')[0];
    $this=$(this);
    var form_data = new FormData();
    var $input = $(this).parents('.div-image').find('.image_data');
    form_data.append('file', file_data);
    $.ajax({
        url: '/api/recruitment/uploadImage',
        method: 'POST',
        data: form_data,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
            if(response.success == true){
                $input.val(response.image);
                $this.parents('.div-image').find('.file-drop-zone').html('<div class="file-preview-frame krajee-default  file-preview-initial file-sortable kv-preview-thumb">'+
                                                                                   '<div class="kv-file-content">'+
                                                                                                '<img src="'+response.image+'" class="file-preview-image kv-preview-data" title="'+response.name+'" alt="'+response.name+'" style="width:auto;height:auto;max-width:100%;max-height:100%;">'+
                                                                                    '</div>'+
                                                                                    '<div class="file-thumbnail-footer">'+
                                                                                    '<div class="file-footer-caption" title="'+response.name+'">'+
                                                                                        '<div class="file-caption-info">'+response.name+'</div>'+
                                                                                        '<div class="file-size-info"></div>'+
                                                                                    '</div>'+
                                                                                    '<div class="file-actions">'+
                                                                                        '<div class="file-footer-buttons">'+
                                                                                              '<button type="button" class="kv-file-remove btn btn-link btn-icon btn-xs" title="Remove file" data-url="" data-key="1"><i class="icon-trash"></i></button>'+
                                                                                              '<button type="button" class="kv-file-zoom btn btn-link btn-xs btn-icon" title="View Details"><i class="icon-zoomin3"></i></button>'+
                                                                                          '</div>'+
                                                                                    '</div>'+
                                                                                '</div>'+
                                                                                '</div>');
                $input.val(response.image);
            }else{
                alert('File upload không hợp lệ');
            }

        }
     });
});
if($('.upload-images').length){
    $('.upload-images').each(function(){
        var images = $(this).parents('.div-image').find('.image_data').val();
        if(images === ''){
        }else{
            images = images.split(',');
            for(i=0;i < images.length;i++){
               var name = images[i];
               name = name.split('/');
               $(this).parents('.div-image').find('.file-drop-zone').append('<div class="file-preview-frame krajee-default  file-preview-initial file-sortable kv-preview-thumb">'+
                                                                                          '<div class="kv-file-content">'+
                                                                                                       '<img src="'+images[i]+'" class="file-preview-image kv-preview-data" title="'+name[4]+'" alt="'+name[4]+'" style="width:auto;height:auto;max-width:100%;max-height:100%;">'+
                                                                                           '</div>'+
                                                                                           '<div class="file-thumbnail-footer">'+
                                                                                           '<div class="file-footer-caption" title="'+name[4]+'">'+
                                                                                               '<div class="file-caption-info">'+name[4]+'</div>'+
                                                                                               '<div class="file-size-info"></div>'+
                                                                                           '</div>'+
                                                                                           '<div class="file-actions">'+
                                                                                               '<div class="file-footer-buttons">'+
                                                                                                     '<button type="button" class="kv-file-remove btn btn-link btn-icon btn-xs" title="Remove file" data-url="" data-key="1"><i class="icon-trash"></i></button>'+
                                                                                                     '<button type="button" class="kv-file-zoom btn btn-link btn-xs btn-icon" title="View Details"><i class="icon-zoomin3"></i></button>'+
                                                                                                 '</div>'+
                                                                                           '</div>'+
                                                                                       '</div>'+
                                                                                       '</div>');
            }
        }
    });

};

$('body').delegate('.kv-file-zoom','click',function(){
        $('.imagepreview').attr('src', $(this).parents('.file-preview-frame').find('.file-preview-image').attr('src'));
$('#titleimagepreview').html($(this).parents('.file-preview-frame').find('.file-caption-info').html());
        $('#imagemodal').modal('show');
});

$('body').delegate('.upload-images','change', function() {
    $this = $(this);
    var file_data = [];
    var form_data = new FormData();
    var $input = $(this).parents('.div-image').find('.image_data');
    var images = $(this).parents('.div-image').find('.image_data').val();
    if(images === ''){
        images=[];
    }else{
        images = images.split(',');
    }
    for (var i = 0; i < $(this)[0].files.length; i++) {
        form_data.append('file[]', $(this).prop('files')[i]);
    }
    $.ajax({
        url: '/api/recruitment/upload',
        method: 'POST',
        data: form_data,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
            if(response.success == true){
                for(var i=0;i < response.image.length;i++ ){
                    $this.parents('.div-image').find('.file-drop-zone').append('<div class="file-preview-frame krajee-default  file-preview-initial file-sortable kv-preview-thumb">'+
                                                                                       '<div class="kv-file-content">'+
                                                                                                    '<img src="'+response.image[i]+'" class="file-preview-image kv-preview-data" title="'+response.name[i]+'" alt="'+response.name[i]+'" style="width:auto;height:auto;max-width:100%;max-height:100%;">'+
                                                                                        '</div>'+
                                                                                        '<div class="file-thumbnail-footer">'+
                                                                                        '<div class="file-footer-caption" title="'+response.name[i]+'">'+
                                                                                            '<div class="file-caption-info">'+response.name[i]+'</div>'+
                                                                                            '<div class="file-size-info"></div>'+
                                                                                        '</div>'+
                                                                                        '<div class="file-actions">'+
                                                                                            '<div class="file-footer-buttons file-button">'+
                                                                                                  '<button type="button" class="kv-file-remove btn btn-link btn-icon btn-xs" title="Remove file" data-url="" data-key="1"><i class="icon-trash"></i></button>'+

                                                                                              '</div>'+
                                                                                        '</div>'+
                                                                                    '</div>'+
                                                                                    '</div>');
                    images.push(response.image[i]);
                    var res = images.join(',');
                    $input.val(res);
                }
            }else{
                alert('File upload không hợp lệ');
            }
            }
        })
 });
$('body').delegate('.kv-file-remove,.fileinput-remove','click',function(){
   var image = $(this).parents('.file-preview-frame').find('.file-preview-image').attr("src");
    $.ajax({
       url: '/api/recruitment/delete_image',
       method: 'POST',
       data: {link:image},
       success: function(response){
           if(response.success == true){
           }
       }
    });
   var $input = $(this).parents('.div-image').find('.image_data');
   var images = $(this).parents('.div-image').find('.image_data').val();
   images = images.split(',');
   var index = images.indexOf(image);
   if (index > -1) {
     images.splice(index, 1);
   }
   var res = images.join(',');
   $input.val(res);
   $(this).parents('.file-preview-frame').remove();
});
