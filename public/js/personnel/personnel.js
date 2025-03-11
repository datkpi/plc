$(document).ready(function() {

    // Khởi tạo Select2 cho các trường select sau khi modal hoàn toàn tải xong
    $('#baseModal').on('shown.bs.modal', function() {
        $('.select2').select2({
            dropdownParent: $('#baseModal')
        });
    });

    $('#editModal').on('shown.bs.modal', function() {
        $('.select2').select2({
            dropdownParent: $('#editModal')
        });
    });

    // $('#editModal').on('shown.bs.modal', function () {
    //     $('.select2').select2({
    //         dropdownParent: $('#editModal')
    //     });
    // });

    function convertTableNameToRouteName(tableName) {
        return tableName.replace(/_/g, '-');
    }

    $('.editBtn').on('click', function() {
        loadData.call(this);
    });

    function loadData(){
        var id = $(this).data('id');
        var apiUrl = $(this).data('edit');
        var updateRoute = $(this).data('route');
        var model = $(this).data('model');
        // Cập nhật action của form
        $('#editForm').attr('action', updateRoute);

        $.ajax({
            // url:'/'+ model.toLowerCase() + '/edit/' + id,
            url: apiUrl,
            type: 'GET',
            success: function(resp) {
                // Điền dữ liệu vào form chỉnh sửa
                fillEditModal(resp.data);
                $('#editModal').modal('show');
            },
            error: function() {
                alert('Không thể tải dữ liệu');
            }
        });
    }

    function fillEditModal(userData) {
        if (!userData) {
            alert('Dữ liệu không tồn tại');
            return;
        }
        Object.keys(userData).forEach(function(key) {
            var value = userData[key];
            var inputSelector = '[name="' + key + '"]';
            var input = $('#editModal ' + inputSelector);
            var isMultiple = input.prop('multiple');

            if (input.is(':file')) {
                var imagePreviewId = 'preview_image_' + key;
                if (value) {
                    var imageUrl = value;
                    //document.getElementById('preview_image_favicon').src = '/' + imageUrl;
                    $("." + imagePreviewId).attr("src", "/" + imageUrl);
                }
                return;
            }

            // Xử lý trường hợp select multiple
            if (!input.length && !isMultiple) {
                inputSelector = '[name="' + key + '[]"]';
                input = $('#editModal ' + inputSelector);
                isMultiple = input.prop('multiple');
            }

            // Xử lý các loại input khác
            if (input.is(':checkbox')) {
                input.prop('checked', value);
            } else if (input.is('select')) {
                // if (isMultiple && typeof value === 'string') {
                //     value = value.split(',');
                // }
                input.val(value).trigger('change');
            } else if (input.is('textarea') || input.is('input')) {
                input.val(value);
            }
        });

        $('#editModal .select2').select2({
            dropdownParent: $('#editModal')
        });
    }

    $(document).on('change', 'input[type="file"]', function() {
        previewImage(this);
    });

    // Hàm bổ sung để cập nhật một trường select2 từ dữ liệu tải về
    function updateSelect2Field(selector, data) {
        $(selector).empty(); // Xóa các lựa chọn hiện tại
        // Thêm các lựa chọn mới từ data
        data.forEach(function(item) {
            $(selector).append(new Option(item.text, item.id, false, false));
        });
        $(selector).trigger('change'); // Kích hoạt cập nhật select2
    }

});
