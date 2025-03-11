var statusArrray;

$("#addRecruitmentPlanPopup").dxPopup({
    title: "Thêm dữ kế hoạch tuyển dụng từ phiếu đã chọn",
    width: 600,
    height: 'auto',
    contentTemplate: function (contentElement) {
        contentElement.dxForm({
            validationMode: "onBlur",
            formData: {}, // Dữ liệu ban đầu của form
            items: [{
                    dataField: "name",
                    editorType: "dxTextBox",
                    label: {
                        text: "Tên kế hoạch tuyển dụng"
                    },
                    validationRules: [{
                        type: "required",
                        message: "Vui lòng nhập tên"
                    }]
                },
                {
                    dataField: "start_date",
                    editorType: "dxDateBox",
                    label: {
                        text: "Ngày bắt đầu"
                    },
                    validationRules: [{
                        type: "required"
                    }],
                    editorOptions: { // Tùy chọn cho dxDateBox
                        displayFormat: "dd/MM/yyyy",
                        dateSerializationFormat: "yyyy-MM-dd",
                    },
                },
                {
                    dataField: "end_date",
                    editorType: "dxDateBox",
                    label: {
                        text: "Ngày kết thúc"
                    },
                    validationRules: [{
                        type: "required"
                    }],
                    editorOptions: { // Tùy chọn cho dxDateBox
                        displayFormat: "dd/MM/yyyy",
                        dateSerializationFormat: "yyyy-MM-dd",
                    },
                },
                // Thêm các trường khác nếu cần
            ]
        });
    },
    toolbarItems: [{
        toolbar: 'bottom',
        widget: 'dxButton',
        options: {
            text: 'Lưu',
            onClick: function () {
                var formInstance = $("#addRecruitmentPlanPopup").dxPopup('instance').content().dxForm('instance');
                var form = $("#addRecruitmentPlanPopup").dxPopup('instance').content().dxForm('instance');
                var validationResult = formInstance.validate();
                var gridData = $('#gridData').dxDataGrid('instance');
                if (validationResult.isValid) {
                    var data = formInstance.option('formData');
                    var listRequestForm = [];
                    listRequestForm = $.map(gridData.getSelectedRowsData(), function (item, index) {
                        return item.id;
                    });

                    $.ajax({
                        url: "/recruitment-plan/store",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            data: data,
                            listRequestForm: listRequestForm
                        },
                        success: function (data) {
                            $("#gridData").dxDataGrid("instance").refresh();
                            $("#addRecruitmentPlanPopup").dxPopup("instance").hide();
                            if (data.success != true) {
                                DevExpress.ui.notify(data.message, "Error", 4000);
                                return
                            }
                            DevExpress.ui.notify(data.message, "success", 4000);
                            GetData();
                        },
                        error: function (data) {
                            $("#gridData").dxDataGrid("instance").refresh();
                            $("#addRecruitmentPlanPopup").dxPopup("instance").hide();
                            DevExpress.ui.notify(data.data, "Error", 2000);
                            GetData();
                        }
                    });
                } else {
                    // Hiện thông báo lỗi hoặc thực hiện hành động khác
                    alert("Form có lỗi. Vui lòng kiểm tra lại.");
                }
                // Gọi API để lưu dữ liệu
                // $.post("/your-api-url", data).done(function () {
                //     $("#yourGrid").dxDataGrid("instance").refresh();
                //     $("#yourPopup").dxPopup("instance").hide();
                // });
            }
        }
    }]
});
var gridData = $('#gridData').dxDataGrid({
    dataSource: [],
    showRowLines: true,
    showColumnHeaders: true,
    autoExpandAll: true,
    showBorders: true,
    allowColumnResizing: true,
    hoverStateEnabled: true,
    columnAutoWidth: true,
    allowColumnReordering: true, // bốc chuyển cột
    rowAlternationEnabled: true,
    groupPanel: {
        visible: true,
    },
    paging: {
        enabled: true,
        pageSize: 15
    },
    loadPanel: {
        enabled: true
    },
    height: window.innerHeight * 0.8,
    pager: {
        showPageSizeSelector: true,
        allowedPageSizes: [15, 50, 100],
        showNavigationButtons: true
    },
    filterRow: {
        visible: true,
        applyFilter: "auto"
    },
    headerFilter: {
        visible: true,
    },
    scrolling: {
        mode: "standard",
        scrollByContent: true
    },
    export: {
        enabled: true,
    },
    summary: {
        totalItems: [{
            column: 2,
            summaryType: "count"
        }]
    },

    selection: {
        mode: "multiple",
        //showCheckBoxesMode: "always", // Luôn hiển thị checkbox
        allowSelectAll: true // Tắt chế độ select all
    },

    export: {
        enabled: true,
        // Các thuộc tính khác...
    },

    editing: {
        mode: 'popup',
        allowUpdating: false,
        allowAdding: false,
        allowDeleting: false,
        popup: {
            title: 'Nhập tên kế hoạch tuyển dụng',
            showTitle: true,
            width: 700,
            height: 525,
        },
        form: {
            items: [{
                itemType: 'group',
                caption: 'Employee Details',
                colCount: 2,
                items: [{
                    dataField: 'FirstName',
                    editorOptions: {
                        disabled: true,
                    },

                }, {
                    dataField: 'LastName',
                    editorOptions: {
                        disabled: true,
                    },

                }, {
                    dataField: 'HireDate',
                    editorType: 'dxDateBox',
                    editorOptions: {
                        value: null,
                        width: '100%',
                    },
                    validationRules: [{
                        type: 'required',
                        message: 'Hire date is required',
                    }],

                }],
            }],
        },
    },
    onRowPrepared: function (e) {
        if(e.data){
            if(e.data.probation_result == null){
                // if (e.rowType === "data" && e.data && e.data.status == 'approving') {
                var today = new Date();
                var deadline = new Date(e.data.probation_to);
                var diffDays = Math.ceil((deadline - today) / (1000 * 60 * 60 * 24));

                if(diffDays <= 0){
                    e.rowElement.css("color", "#dc3545");
                }
                else if (diffDays <= 3) {
                    e.rowElement.css("color", "#ffc107");
                } else if (diffDays <= 7) {
                    e.rowElement.css("color", "#28a745");
                }
                else if (diffDays <= 10) {
                    e.rowElement.css("color", "#17a2b8");
                }
            }
        }
        // }
    },
    columns: [
        {
            dataField: "status_value",
            caption: 'TÌNH TRẠNG',
        },
        {
            dataField: "user_uid",
            caption: "MÃ NTP",

        },
        {
            dataField: "name",
            caption: "HỌ VÀ TÊN",

        },
        {
            dataField: "position.name",
            caption: "VỊ TRÍ CHỨC DANH",
        },
        {
            dataField: "department.name",
            caption: "PHÒNG BAN",
        },
          {
            dataField: "probation_from",
            caption: 'NGÀY BẮT ĐẦU THỬ VIỆC',
            dataType: 'date',
        },
        {
            dataField: "probation_to",
            caption: 'NGÀY KẾT THÚC THỬ VIỆC',
            dataType: 'date',
        },
        {
            dataField: "email",
            caption: "ĐỊA CHỈ EMAIL",
        },

        {
            dataField: "phone_number",
            caption: "SỐ ĐIỆN THOẠI",
        },
        {
            dataField: "birthday",
            caption: "NGÀY SINH",
            dataType: 'date',
        },
            {
            dataField: "recruitment_result_value",
            caption: "KQ THI TUYỂN",
        },
        {
            dataField: "probation_result_value",
            caption: "KQ THỬ VIỆC",
        },




    ],

    onCellPrepared: function onCellPrepared(e) {
        if (e.rowType == "header") {
            e.cellElement.css("text-align", "center");
            e.cellElement.css("vertical-align", "middle");
            e.cellElement.addClass("RedHeader");
        }
    },

    onContentReady: function (e) {
        if (e.component.shouldSkipNextReady) {
            e.component.shouldSkipNextReady = false;
        } else {
            e.component.shouldSkipNextReady = true;
            e.component.columnOption("command:select", "width", 40);
            e.component.updateDimensions();
        }
    }, //độ dài select box đánh dấu
    onToolbarPreparing: function (e) {

        var dataGrid = e.component;
        var exportButtonIndex = e.toolbarOptions.items.findIndex(function (item) {
            return item.name === "exportButton";
        });
        if (exportButtonIndex >= 0) {
            e.toolbarOptions.items.splice(exportButtonIndex, 1);
        }
        e.toolbarOptions.items.unshift({
                location: 'after',
                widget: 'dxButton',
                options: {
                    visible: true,
                    icon: "add",
                    type: 'default',
                    text: "Xuất excel",
                    hint: "Xuất excel",
                    icon: 'download',
                    onClick: function () {
                        dataGrid.exportToExcel();
                    }
                }
            }, {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    elementAttr: {
                        id: 'btnImportQtyXNVL',
                    },
                    icon: "fas fa-file-import",
                    type: 'success',
                    text: "Nhập dữ liệu excel",
                    hint: "Import Excel",
                    onClick: function (e) {
                        $('#fileInput').click();

                    }
                }
            }, //import file

        );
    },


}).dxDataGrid('instance');




GetData();


$(function () {
    $("#my-popup").dxPopup({
        title: "Input data",
        closeOnOutsideClick: true,
        showTitle: true,
        width: "auto",
        height: "auto",
        contentTemplate: function (contentElement) {
            contentElement.append($("#my-popup").html());
        }
    });
});

function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/report/recruitment/get-candidate',
        dataType: 'json',
        async: false,
        success: function (resp) {
            gridData.option("dataSource", resp.data);
        }
    });
}

function getStatusValue(statusName, statusArray) {
    var statusObject = statusArray.find(function (status) {
        return status.name.toLowerCase() === statusName.toLowerCase();
    });
    return statusObject ? statusObject.value : 'unknown';
}

function getBootstrapBadgeClass(statusName) {
    switch (statusName.toLowerCase()) {
        case 'process':
            return 'badge-primary';
        case 'end':
            return 'badge-warning';
        default:
            return 'badge-secondary';
    }
}

function ImportExcel(event) {
    //upload excel and insert to BD
    var file = event.target.files[0];

    // Bạn có thể tiếp tục xử lý file tại đây, ví dụ gửi nó đến server thông qua AJAX
    var formData = new FormData();
    formData.append('excel', file);

    $.ajax({
        url: '/recruitment/candidate/import-excel-update-data',
        type: "POST",
        contentType: false,
        processData: false,
        data: formData,
        success: function (resp) {
            if (resp.success != true) {
                DevExpress.ui.notify(resp.message, "success", 3000);
                return;
            } else {
                if (resp.data.total_row != resp.data.record_imported) {
                    DevExpress.ui.notify('Đã import: ' + resp.data.record_imported + ' trên tổng số ' + resp.data.total_row + ' hàng', "error", 3000);
                } else {
                    DevExpress.ui.notify('Đã import: ' + resp.data.record_imported + ' trên tổng số ' + resp.data.total_row + ' hàng', "success", 3000);
                }
            }
            GetData();
        },
        error: function (resp) {
            DevExpress.ui.notify(resp.message, "Error", 3000);
            GetData();
        }
    });
    $("#inputFileCandidate").val('');

}
thôi
