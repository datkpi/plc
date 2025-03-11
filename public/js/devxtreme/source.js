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
        pageSize: 10
    },
    loadPanel: {
        enabled: true
    },
    height: window.innerHeight * 0.8,
    pager: {
        showPageSizeSelector: true,
        allowedPageSizes: [10, 50, 100],
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
        allowUpdating: true,
        allowAdding: true,
        allowDeleting: true,
        popup: {
            title: 'Nguồn tuyển dụng',
            showTitle: true,
            width: 700,
            height: 525,
        },
        form: {

        },
    },
    columns: [{
            dataField: "name",
            caption: "Tên",

        },
        {
            dataField: "link",
            caption: "Link nguồn",

        },
        {
            dataField: "contact",
            caption: "Liên hệ",

        },
        {
            dataField: "phone_contact",
            caption: "Số điện thoại liên hệ",

        },
        {
            dataField: "email_register",
            caption: "Email đăng ký",

        },
        {
            dataField: "email_contact",
            caption: "Email liên hệ của nguồn",

        },
        {
            dataField: "description",
            caption: "Mô tả",

        },
        {
            caption: 'Trạng thái',
            cellTemplate: function (container, options) {
                //var statusName = getStatusName(options.data.status, statusArray);
                var active = options.data.active;
                if (active == 1) {
                    activeValue = 'Hoạt động'
                } else {
                    activeValue = 'Không hoạt động'
                }
                $('<div/>')
                    .addClass('badge')
                    .addClass('badge-pill')
                    .addClass(getActiveClass(active))
                    .text(activeValue)
                    .appendTo(container);
            },
        },
        {
            alignment: 'center',
            cellTemplate: function (container, options) {
                $('<a/>').addClass('dx-link')
                    .text('Xem')
                    .attr("href", "/recruitment/source/edit/" + options.data.id)
                    .appendTo(container);
            }
        },
        // {
        //     alignment: 'center',
        //     cellTemplate: function (container, options) {
        //         $('<a/>').addClass('dx-link')
        //             .text('Xoá')
        //             .on('dxclick', function () {
        //                 deleteData(options.data.id)
        //             })
        //             .appendTo(container);
        //     }
        // },
    ],

    onRowInserting: function (e) {

        $.ajax({
            dataType: "JSON",
            type: 'POST',
            url: '/Department/Create',
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
        url: '/recruitment/source/destroy/' + id,
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
        url: '/source/get-data',
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

function getActiveClass(active) {
    switch (active) {
        case 1:
            return 'badge-success';
        case 0:
            return 'badge-secondary';
        default:
            return 'badge-secondary';
    }
}
