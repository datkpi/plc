var statusArrray;
var currentUserId;



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
                        url: "/request-form/add-plan",
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
        showCheckBoxesMode: "always", // Luôn hiển thị checkbox
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
        if (e.rowType === "data" && e.data && e.data.status == 'approving') {
            var today = new Date();
            var deadline = new Date(e.data.approve_deadline);
            var diffDays = Math.ceil((deadline - today) / (1000 * 60 * 60 * 24));

            if (diffDays < 0) {
                e.rowElement.css("color", "red ");
            } else if (diffDays <= 3) {
                e.rowElement.css("color", "red");
            }
        }
    },
    columns: [{
            dataField: "name",
            caption: "Tên phiếu yêu cầu",

        },
        {
            dataField: "request_date",
            caption: "Ngày đề nghị",
            dataType: 'date',
        },
        {
            dataField: "department.name",
            caption: "Phòng ban",

        },
        {
            dataField: "position.name",
            caption: "Vị trí",

        },
        {
            dataField: "quantity",
            caption: "Số lượng tuyển",
        },
        {
            dataField: 'stage',
            caption: 'Tiến trình duyệt',
            dataType: 'number',
            format: 'percent',
            alignment: 'right',
            allowGrouping: false,
            cellTemplate: function (container, options) {
                $('<div/>').dxBullet({
                    onIncidentOccurred: null,
                    size: {
                        width: 150,
                        height: 20,
                    },
                    tooltip: {
                        enabled: true // false
                    },
                    // targetColor:"#333333",
                    showTarget: true,
                    showZeroLevel: true,
                    value: options.data.stage * 25,
                    startScaleValue: 0,
                    endScaleValue: 100,
                    target: 100,
                    tooltip: {
                        enabled: true,
                        font: {
                            size: 18,
                        },
                        paddingTopBottom: 2,
                        customizeTooltip() {
                            return {
                                text: "Đã duyệt " + options.data.stage * 25 + "%"
                            };
                        },
                        zIndex: 5,
                    },
                }).appendTo(container);
            },
            cssClass: 'bullet',
        },
        {
            dataField: "created_at",
            caption: "Ngày Tạo",
            dataType: 'date',
        },
        {
            caption: 'Hạn duyệt',
            dataField: "approve_deadline",
            dataType: 'date',
            // cellTemplate: function (container, options) {
            //     var today = new Date();
            //     var deadline = new Date(options.data.approve_deadline);
            //     var diffDays = Math.ceil((deadline - today) / (1000 * 60 * 60 * 24)); // Số ngày còn lại

            //     var displayText = deadline.getDate() + '/' + (deadline.getMonth() + 1) + '/' + deadline.getFullYear();
            //     if (options.data.status != "approving" || options.data.status == "success") {
            //         if (diffDays <= 3 && diffDays >= 0) { // Ngày hết hạn sắp đến
            //             $('<div/>')
            //                 .addClass('badge badge-warning')
            //                 .addClass('badge-pill')
            //                 .text(displayText)
            //                 .appendTo(container);
            //         } else if (diffDays < 0) { // Ngày hết hạn đã qua
            //             $('<div/>')
            //                 .addClass('badge badge-danger')
            //                 .addClass('badge-pill')
            //                 .text(displayText)
            //                 .appendTo(container);
            //         } else { // Còn thời gian
            //             $('<div/>')
            //                 .addClass('badge badge-primary')
            //                 .addClass('badge-pill')
            //                 .text(displayText)
            //                 .appendTo(container);
            //         }
            //     } else {
            //         $('<div/>')
            //             .addClass('badge badge-primary')
            //             .addClass('badge-pill')
            //             .text(displayText)
            //             .appendTo(container);
            //     }
            // },
        },
        {
            caption: 'Trạng thái',
            cellTemplate: function (container, options) {
                //var statusName = getStatusName(options.data.status, statusArray);
                var statusName = options.data.status;
                $('<div/>')
                    .addClass('badge')
                    .addClass('badge-pill')
                    .addClass(getBootstrapBadgeClass(statusName)) // Định nghĩa function để chuyển đổi status sang class tương ứng của Bootstrap
                    .text(getStatusValue(statusName, statusArray))
                    .appendTo(container);
            },
        },
        {
            alignment: 'center',
            cellTemplate: function (container, options) {
                const rowUserId = options.data.current_approve;
                if (rowUserId === currentUserId && options.data.status == 'approving') {
                    $("<a>")
                        .addClass('dx-link')
                        .text("Duyệt")
                        .on("click", function () {

                        })
                        .appendTo(container);
                }
            }
        },
        {
            alignment: 'center',
            cellTemplate: function (container, options) {
                $('<a/>').addClass('dx-link')
                    .text('Xem')
                    .attr("href", "/recruitment/request-form/edit/" + options.data.id)
                    .appendTo(container);
            }
        },
        {
            alignment: 'center',
            cellTemplate: function (container, options) {
                $('<a/>').addClass('dx-link')
                    .text('Xoá')
                    .on('dxclick', function () {
                        if (confirm('Bạn có chắc chắn muốn xoá dữ liệu này không?')) {
                            deleteData(options.data.id);
                        }
                    })
                    .appendTo(container);
            }
        },
    ],

    onEditorPreparing: function (e) {
        if (e.parentType === 'dataRow' && e.dataField === 'ThisRow') {
            if (e.row.data.status !== 'approved' || e.row.data.recruitment_id != null) {
                e.editorOptions.disabled = true;
                e.editorElement.addClass('dx-checkbox-disabled');
            }
        }
    },
    onSelectionChanged: function (e) {
        e.currentSelectedRowKeys.forEach((row) => {
            if (row.status !== 'approved') {
                e.component.deselectRows([row]);
            }
        });
    },
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
            }, {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    icon: "add",
                    type: 'default',
                    text: "Tạo kế hoạch từ phiếu đã chọn",
                    hint: "Tạo kế hoạch từ phiếu đã chọn",
                    onClick: function () {
                        $("#addRecruitmentPlanPopup").dxPopup('instance').show();

                    }

                }
            },

            // {
            //      location: "before",
            //      widget: "dxButton",
            //  options: {
            //      visible: true,
            //      elementAttr: { id: 'btnDelete', },
            //      icon: "fa-solid fa-trash-can",
            //      type: 'danger',
            //      text: "Delete multiple lines",
            //      hint: "Xóa các dòng đã chọn",
            //      onClick: function (e) {



            //          var gridData = $('#gridData').dxDataGrid('instance');
            //          var lst = [];
            //          lst = $.map(gridData.getSelectedRowsData(), function (item, index) {
            //              return item.masinhvien;
            //          });




            //          $.ajax({
            //              url: "/Department/Delete_Multity_Item",
            //              type: "POST",
            //              dataType: 'json',
            //              data: { lst },
            //              success: function (data) {
            //                  DevExpress.ui.notify("Success", "success", 2000);
            //                  GetData(1);
            //              },
            //              error: function (data) {
            //                  DevExpress.ui.notify(data.data, "Error", 2000);
            //                  GetData(1);
            //              }
            //          });
            //      }
            //  }
            //  },

        );
        // Loại bỏ button xuất Excel mặc định

    },


}).dxDataGrid('instance');


GetAuthUserId();

GetData();


//console.log("datasv", dataSinhvien);
// function deleteData(id) {
//     var url =  '/recruitment/request-form/destroy/' + id
//     $.ajax({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         dataType: "JSON",
//         method: 'delete',
//         url: url,
//         //data: { data: e.data },
//         success: function (data) {
//             DevExpress.ui.notify("Success", data.message, 2000);
//             GetData();
//         },
//         error: function (data) {
//             if (data.status == 403) {
//                 DevExpress.ui.notify("Bạn không có quyền truy cập chức năng này", "Error", 2000);
//                 return
//             }
//             DevExpress.ui.notify(data.responseJSON.data, "Error", 2000);
//             GetData();
//         }
//     });
// }
function deleteData(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/recruitment/request-form/destroy/' + id,
        type: 'delete',
        dataType: 'json',
        async: false,
        success: function (resp) {
            DevExpress.ui.notify("Xoá thành công", "Success", 4000);
            GetData();

        },
        error: function (error) {
            DevExpress.ui.notify("Có lỗi xảy ra", "Error", 4000);
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

function GetAuthUserId() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/user/get-data',
        dataType: 'json',
        async: false,
        success: function (resp) {
            currentUserId = resp.data.id
        }
    });
}

function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/request-form/get-data',
        dataType: 'json',
        async: false,
        success: function (resp) {

            statusArray = resp.data.requestFormEnum;

            gridData.option("dataSource", resp.data.datas);
        }
    });
}

// function getStatusName(statusValue, statusArray) {
//     var statusObject = statusArray.find(function(status) {
//         return status.value === statusValue;
//     });
//     return statusObject ? statusObject.name : 'Unknown';
// }



function getStatusValue(statusName, statusArray) {
    var statusObject = statusArray.find(function (status) {
        return status.name.toLowerCase() === statusName.toLowerCase();
    });
    return statusObject ? statusObject.value : 'unknown';
}

function getBootstrapBadgeClass(statusName) {
    switch (statusName.toLowerCase()) {
        case 'plan':
            return 'badge-light';
        case 'approving':
            return 'badge-secondary';
        case 'approved':
            return 'badge-info';
        case 'process':
            return 'badge-primary';
        case 'success':
            return 'badge-success';
        case 'cancel':
            return 'badge-danger';
        default:
            return 'badge-light'; // Mặc định nếu không tìm thấy trạng thái
    }
}

function UploadFile2Diem() {
    //upload excel and insert to BD
    if (window.FormData != undefined) {
        var fileUpload = $("#inputfileDiem").get(0);
        var files = fileUpload.files;
        var fileData = new FormData();
        for (var i = 0; i < files.length; i++) {
            fileData.append(files[i].name, files[i]);
        }



        $.ajax({
            url: '/Department/Import_Item_Sinhvien',
            type: "POST",
            contentType: false,
            processData: false,
            data: fileData,
            success: function (data) {
                DevExpress.ui.notify("Success", "success", 2000);
                GetData();
            },
            error: function (data) {
                DevExpress.ui.notify(data.responseJSON.data, "Error", 2000);
                GetData();
            }
        });
        $("#inputfileDiem").val('');
    }
}

 var activeStatus = $('#statusList li a.active').data('status');

    // Nếu activeStatus là "current_approve" và $totalApprove > 0 thì lọc gridData
    if (activeStatus === "current_approve") {
         gridData.filter([
        ["status", "=", 'approving'],
        ["current_approve", "=", currentUserId]
    ]);
    }

$('#statusList li a').on('click', function (e) {

    e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
    // Xóa lớp 'active' khỏi tất cả các thẻ <a>
    $('#statusList li a').removeClass('active');

    // Thêm lớp 'active' vào thẻ <a> được nhấp vào
    $(this).addClass('active');
    var statusToFilter = $(this).data('status');
    if (statusToFilter === 'all') {
        gridData.clearFilter();
    }
    else if(statusToFilter === 'current_approve'){
         gridData.filter([
        ["status", "=", 'approving'],
        ["current_approve", "=", currentUserId]
    ]);
    }
    else {
        gridData.filter(["status", "=", statusToFilter]);
    }
});
