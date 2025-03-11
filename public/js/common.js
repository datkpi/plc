$('[data-action="delete"]').click(function (e) {
    var elm = this;
    bootbox.confirm("Bạn có chắc chắn muốn xóa?", function (result) {
        if (result === true) {
            $(elm).parents('form').submit();
        }
    });
});

function previewImage(){
    $('#preview_image').attr('src', URL.createObjectURL(event.target.files[0]));
}


$('.session-notify').delay(4000).fadeOut(1500, function() {
    $(this).hide();
});


$('.delete_confirm').click(function(e) {
    if (!confirm('Bạn có muốn xoá bản ghi này?')) {
        e.preventDefault();
    }
});



