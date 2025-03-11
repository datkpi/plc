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
    columns: [{
            dataField: "name",
            caption: "Tên vị trí chức vụ",

        },
        {
            dataField: "recruiting",
            caption: "Đang tuyển",
        },
        {
            dataField: "contact",
            caption: "Liên hệ",
        },
        {
            dataField: "created_at",
            caption: 'Ngày tạo',
            dataType: 'date',
        },
        {
            alignment: 'center',
            cellTemplate: function (container, options) {
                $('<a/>').addClass('dx-link')
                    .text('Xem')
                    .attr("href", "/recruitment/position/edit/" + options.data.id)
                    .appendTo(container);
            }
        },
        {
            alignment: 'center',
            cellTemplate: function (container, options) {
                $('<a/>').addClass('dx-link')
                    .text('Xoá')
                    .on('dxclick', function () {
                        deleteData(options.data.id)
                    })
                    .appendTo(container);
            }
        },
    ],

    onRowInserting: function (e) {

        $.ajax({
            dataType: "JSON",
            type: 'POST',
            url: '/position/create',
            data: {
                data: e.data
            },
            success: function (data) {
                DevExpress.ui.notify("Success", "success", 2000);
                GetData();
            },
            error: function (data) {
                if (data.status == 403) {
                    DevExpress.ui.notify("Bạn không có quyền truy cập chức năng này", "Error", 2000);
                    return
                }
                DevExpress.ui.notify(data.responseJSON.data, "Error", 2000);
                GetData();
            }
        });
    },
    onRowUpdated: function (e) {
        $.ajax({
            dataType: "JSON",
            type: 'POST',
            url: '/Department/Update',
            data: {
                data: e.data
            },
            success: function (data) {
                DevExpress.ui.notify("Success", "success", 2000);
                GetData();
            },
            error: function (data) {
                if (data.status == 403) {
                    DevExpress.ui.notify("Bạn không có quyền truy cập chức năng này", "Error", 2000);
                    return
                }
                DevExpress.ui.notify(data.responseJSON.data, "Error", 2000);
                GetData();
            }
        });
    },
    onRowRemoved: function (e) {
        $.ajax({
            dataType: "JSON",
            type: 'POST',
            url: '/Department/Delete',
            data: {
                data: e.data
            },
            success: function (data) {
                DevExpress.ui.notify("Success", data.statusText, 2000);
                GetData();
            },
            error: function (data) {
                if (data.status == 403) {
                    DevExpress.ui.notify("Bạn không có quyền truy cập chức năng này", "Error", 2000);
                    return
                }
                DevExpress.ui.notify(data.responseJSON.data, "Error", 2000);
                GetData();
            }
        });
    },
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
            },
            // {
            //     location: "after",
            //     widget: "dxButton",
            //     options: {
            //         visible: true,
            //         icon: "add",
            //         type: 'default',
            //         text: "Tạo kế hoạch từ phiếu đã chọn",
            //         hint: "Tạo kế hoạch từ phiếu đã chọn",
            //         onClick: function () {
            //             $("#addRecruitmentPlanPopup").dxPopup('instance').show();

            //         }

            //     }
            // },

        );

    },


}).dxDataGrid('instance');




GetData();


//console.log("datasv", dataSinhvien);
function deleteData(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "JSON",
        type: 'delete',
        url: '/recruitment/position/destroy/' + id,
        //data: { data: e.data },
        success: function (data) {
            DevExpress.ui.notify("Success", data.message, 2000);
            GetData();
        },
        error: function (data) {
            if (data.status == 403) {
                DevExpress.ui.notify("Bạn không có quyền truy cập chức năng này", "Error", 2000);
                return
            }
            DevExpress.ui.notify(data.responseJSON.data, "Error", 2000);
            GetData();
        }
    });
}

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
        url: '/api/recruitment/position/get-data',
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
