var dataSource;
var dataPosition;
var dataDepartment;

// GetDepartmentData();
// GetPositionData();
// GetSourceData();


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
        pageSize: 20
    },
    loadPanel: {
        enabled: true
    },
    height: window.innerHeight * 0.8,
    pager: {
        showPageSizeSelector: true,
        allowedPageSizes: [20, 50, 100],
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
    // export: {
    //     enabled: true,
    // },
    summary: {
        totalItems: [{
            column: 2,
            summaryType: "count"
        }]
    },
    columnAutoWidth: true,

    // selection: {
    //     mode: "multiple",
    //     allowSelectAll: false
    // },

    // editing: {
    //     mode: 'popup',
    //     allowUpdating: false,
    //     allowAdding: false,
    //     allowDeleting: false,
    //     // confirmDeleteMessage="Bạn xóa muốn xóa trường này",
    //     popup: {
    //         title: 'Thông tin ứng tuyển',
    //         showTitle: true,
    //         width: 900,
    //         height: 725,
    //     },
    //     form: {
    //         items: [{
    //                 itemType: 'group',
    //                 caption: 'Thông tin cơ bản',
    //                 colCount: 2,
    //                 colSpan: 2,
    //                 items: [{
    //                         dataField: 'email',
    //                         validationRules: [{
    //                                 type: 'required',
    //                                 message: 'Email là bắt buộc',
    //                             },
    //                             {
    //                                 type: 'email',
    //                                 message: 'Email sai định dạng',
    //                             }
    //                         ]
    //                     },
    //                     {
    //                         dataField: 'candidateName',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Email là bắt buộc',
    //                         }]
    //                     },

    //                     {
    //                         dataField: 'birthDay',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },

    //                     {
    //                         dataField: 'positionId',
    //                         editorType: 'dxSelectBox',
    //                         editorOptions: {
    //                             dataSource: new DevExpress.data.ArrayStore({
    //                                 data: dataPosition,
    //                                 key: 'id',
    //                             }),
    //                             displayExpr: 'name',
    //                             valueExpr: 'id',

    //                         },
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Trường này là bắt buộ',
    //                         }],
    //                     },

    //                     {
    //                         dataField: 'departmentId',
    //                         editorType: 'dxSelectBox',
    //                         editorOptions: {
    //                             dataSource: new DevExpress.data.ArrayStore({
    //                                 data: dataDepartment,
    //                                 key: 'id',
    //                             }),
    //                             displayExpr: 'name',
    //                             valueExpr: 'id',

    //                         },
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Trường này là bắt buộc',
    //                         }],
    //                     },

    //                     {
    //                         dataField: 'sourceId',
    //                         editorType: 'dxSelectBox',
    //                         editorOptions: {
    //                             dataSource: new DevExpress.data.ArrayStore({
    //                                 data: dataSource,
    //                                 key: 'id',
    //                             }),
    //                             displayExpr: 'name',
    //                             valueExpr: 'id',

    //                         },
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Trường này là bắt buộ',
    //                         }],
    //                     },

    //                     {
    //                         dataField: 'receiveAt',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },

    //                     {
    //                         dataField: 'relation',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },


    //                     {
    //                         dataField: 'receiveBy',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },

    //                     {
    //                         dataField: 'gender',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },
    //                     {
    //                         dataField: 'phoneNumber',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },


    //                     {
    //                         dataField: 'household',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },

    //                     {
    //                         dataField: 'address',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },
    //                     {
    //                         dataField: 'addressDetail',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },
    //                     {
    //                         dataField: 'locality',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },
    //                     {
    //                         dataField: 'level',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },
    //                     {
    //                         dataField: 'trainingSystem',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },
    //                     {
    //                         dataField: 'branch',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },

    //                     {
    //                         dataField: 'school',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },

    //                     {
    //                         dataField: 'rank',
    //                         validationRules: [{
    //                             type: 'required',
    //                             message: 'Đây là trường là bắt buộc',
    //                         }, ]
    //                     },


    //                     {
    //                         dataField: 'english'

    //                     },
    //                     {
    //                         dataField: 'otherLanguage'

    //                     },
    //                     {
    //                         dataField: 'ortherSoftware'

    //                     },
    //                     {
    //                         dataField: 'info1'

    //                     },
    //                     {
    //                         dataField: 'info2'

    //                     },
    //                     {
    //                         dataField: 'experience'

    //                     },



    //                 ],

    //             }, {
    //                 itemType: 'group',
    //                 colCount: 2,
    //                 colSpan: 2,
    //                 caption: 'Phỏng vấn sơ bộ',
    //                 items: ['interview0', 'interviewBy0', 'interviewDate0', 'interviewResult0'],
    //             },
    //             {
    //                 itemType: 'group',
    //                 colCount: 2,
    //                 colSpan: 2,
    //                 caption: 'Phỏng vấn vòng 1',
    //                 items: ['interview1', 'interviewBy1', 'interviewDate1', 'interviewResult1'],
    //             },
    //             {
    //                 itemType: 'group',
    //                 colCount: 2,
    //                 colSpan: 2,
    //                 caption: 'Phỏng vấn vòng 2',
    //                 items: ['interview2', 'interviewBy2', 'interviewDate2', 'interviewResult2'],
    //             },
    //             {
    //                 itemType: 'group',
    //                 colCount: 2,
    //                 colSpan: 2,
    //                 caption: 'Phỏng vấn vòng 3',
    //                 items: ['interview3', 'interviewBy3', 'interviewDate3', 'interviewResult3'],
    //             },
    //             {
    //                 itemType: 'group',
    //                 colCount: 2,
    //                 colSpan: 2,
    //                 caption: 'Kết quả',
    //                 items: [{
    //                     dataField: 'testScore',
    //                     dataType: "number"

    //                 }, 'testResult', 'candidateResult', 'probationResults'],
    //             }
    //         ],
    //     },
    // },

    columns: [{
            alignment: 'center',
            cellTemplate: function (container, options) {
                $('<a/>').addClass('dx-link')
                    .text('Xem')
                    .attr("href", "/recruitment/candidate/edit/" + options.data.id)
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
        {
            dataField: "name",
            caption: "Họ tên",
            required: true

        },
        {
            dataField: "position.name",
            caption: "Vị trí",

        },
        {
            dataField: "status_value",
            caption: "Trạng thái",

        },
        {
            dataField: "household.name",
            caption: "Hộ khẩu",

        },
        // {
        //     dataField: "address.name",
        //     caption: "Địa chỉ",

        // },

        {
            dataField: "birthday",
            caption: "Ngày sinh",
            dataType: "date"

        },
        {
            dataField: "gender_value",
            caption: "Giới tính",
            dataType: "text"
        },
        {
            dataField: "phone_number",
            caption: "Số điện thoại",

        },
        {
            dataField: "experience",
            caption: "Kinh nghiệm",
            dataType: "text",
            width: 500,
            cellTemplate: function(container, options) {
                $('<div />')
                    .text(options.value)
                    .css('white-space', 'pre-wrap')
                    .appendTo(container);
            }
        },
        {
            dataField: "interview_comment0",
            caption: "Nhận xét PVSB",
            width: 500,
            cellTemplate: function(container, options) {
                $("<div>")
                    .appendTo(container)
                    .text(options.value)
                    .css({
                        "text-overflow": "ellipsis",
                        "overflow": "hidden",
                        "white-space": "nowrap"
                    })
                    .attr("title", options.value);

            // cellTemplate: function(container, options) {
            //     $("<div>")
            //         .appendTo(container)
            //         .text(options.value)
            //         .attr("title", options.value); // Thêm tooltip ở đây
            // }
            // cellTemplate: function(container, options) {
            //     $("<div>")
            //         .text(options.value.substring(0, 10)) // Hiển thị một phần của dữ liệu (10 ký tự đầu tiên)
            //         .attr("title", options.value) // Thiết lập toàn bộ dữ liệu cho Tooltip
            //         .appendTo(container);
            // }
            },
        },
        {
            dataField: "email",
            caption: "Email",
            dataType: "text"

        },
        {
            dataField: "source.name",
            caption: "Nguồn hồ sơ",
            visible: true

        },
        {
            dataField: "created_by.name",
            caption: "Người tạo",

        },
        {
            dataField: "created_at",
            caption: "Ngày tạo",
            dataType: 'date',
        },
        {
            dataField: "info2",
            caption: "Ghi chú ứng viên",
            dataType: 'text',
        },
        // {
        //     dataField: "department.name",
        //     caption: "Đơn vị",

        // },
        // {
        //     dataField: "household.name",
        //     caption: "Hộ khẩu",
        // },
        // {
        //     dataField: "address.name",
        //     caption: "Địa chỉ",
        // },
        // {
        //     dataField: "interview_result_value",
        //     caption: "Kết quả SLHS",

        // },
        // {
        //     dataField: "interview_result0_value",
        //     caption: "Kết quả PVSB",

        // },
        // {
        //     dataField: "interview_result1_value",
        //     caption: "Kết quả PV1",

        // },
        // {
        //     dataField: "interview_result2_value",
        //     caption: "Kết quả PV2",

        // },
        // {
        //     dataField: "interview_result3_value",
        //     caption: "Kết quả PV3",

        // },
        // {
        //     dataField: "branch_value",
        //     caption: "Hệ học",

        // },
        // {
        //     dataField: "major",
        //     caption: "Ngành học",

        // },
        // {
        //     dataField: "rank_value",
        //     caption: "Xếp loại",

        // },
        // {
        //     dataField: "training_place",
        //     caption: "Trường đạo tạo",

        // },
        // {
        //     dataField: "english",
        //     caption: "Tiếng anh",

        // },
        // {
        //     dataField: "other_language",
        //     caption: "Ngôn ngữ khác",

        // },
        // {
        //     dataField: "other_software",
        //     caption: "Phần mềm khác",

        // },

        // {
        //     dataField: "info2",
        //     caption: "Thông tin tham chiếu 2",

        // },
        // {
        //     dataField: "experience",
        //     caption: "Kinh nghiệm làm việc",

        // },
        // {
        //     dataField: "training_process",
        //     caption: "Quá trình đào tạo",

        // },
        // {
        //     dataField: "language",
        //     caption: "Ngôn ngữ",

        // },
        // {
        //     dataField: "skill",
        //     caption: "Phần mềm đặc thù",

        // },
        // {
        //     dataField: "info1",
        //     caption: "Thông tin tham chiếu",

        // },

        //      {
        //     dataField: "interview",
        //     caption: "Nhận xét SLHS"

        // },
        // {
        //     dataField: "interviewer",
        //     caption: "Người SLHS",

        // },
        // {
        //     dataField: "interview_result",
        //     caption: "Kết quả SLHS",

        // },
        // {
        //     dataField: "interview_date",
        //     caption: "Ngày SLHS",
        //     dataType: "date",

        // },
        // {
        //     dataField: "interview_comment0",
        //     caption: "Nhận xét pvsb"

        // },
        // {
        //     dataField: "interviewer0",
        //     caption: "Người pvsb",

        // },
        // {
        //     dataField: "interview_result0",
        //     caption: "Kết quả pvsb",

        // },
        // {
        //     dataField: "interviewDate0",
        //     caption: "Ngày pvsb",
        //     dataType: "date",

        // },

        // {
        //     dataField: "interview1",
        //     caption: "Nhận xét vòng 1",

        // },
        // {
        //     dataField: "interviewBy1",
        //     caption: "Người pvv1",

        // },
        // {
        //     dataField: "interviewResult1",
        //     caption: "Két quả pvv1",

        // },
        // {
        //     dataField: "interviewDate1",
        //     caption: "Ngày pvv1",
        //     dataType: "date",

        // },

        // {
        //     dataField: "interview2",
        //     caption: "Nhận xét vòng 2",

        // },
        // {
        //     dataField: "interviewBy2",
        //     caption: "Người pvv2",

        // },
        // {
        //     dataField: "interviewResult2",
        //     caption: "Két quả pvv2",

        // },
        // {
        //     dataField: "interviewDate2",
        //     caption: "Nhận xét pvv2",
        //     dataType: "date",

        // },
        // {
        //     dataField: "interviewer3",
        //     caption: "Người pvv3",

        // },
        // {
        //     dataField: "interview_date3",
        //     caption: "Ngày pvv3",
        //     dataType: "date",

        // },
        // {
        //     dataField: "interview_result3",
        //     caption: "Két quả pvv3",

        // },
        // {
        //     dataField: "interview_comment3",
        //     caption: "Nhận xét vòng 3",

        // },
        // {
        //     dataField: "score",
        //     caption: "Điểm thi tuyển",
        //     dataType: "text",
        // },


    ],

    /*    onEditorPreparing: function (e) {
            if (e.row.inserted) {
                if (e.dataField == "password") {
                    this.itemVisible = false;
                }
            }
        },  */
    onRowInserting: function (e) {
        console.log("data-delete", e.data)
        $.ajax({
            dataType: "JSON",
            type: 'POST',
            url: '/candidate/Create',
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
            url: '/candidate/Update',
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
        console.log(e.data)
        $.ajax({
            dataType: "JSON",
            type: 'POST',
            url: '/candidate/Delete',
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
        // if (e.rowType === 'data' && e.column.dataField === 'interview_comment0') {
        //     // Đặt thuộc tính cần thiết cho Bootstrap Tooltip
        //     $(e.cellElement).attr({
        //         'data-toggle': 'tooltip',
        //         'data-placement': 'top', // bạn có thể thay đổi vị trí
        //         'title': e.data.interview_comment0
        //     });

        //     // Kích hoạt Tooltip Bootstrap
        //     $(e.cellElement).tooltip();
        // }

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
        e.toolbarOptions.items.unshift(
            /*  {
                  location: "after",
                  widget: "dxButton",
                  options: {
                      visible: true,
                      elementAttr: { id: 'btnDelete', },
                      icon: "floppy",
                      type: 'default',
                      text: "Export excel các dòng đã chọn",
                      hint: "Export các dòng đã chọn",
                      onClick: function (e) {



                          var gridSinhvien = $('#gridData').dxDataGrid('instance');
                          var lst = [];
                          lst = $.map(gridSinhvien.getSelectedRowsData(), function (item, index) {
                              return item.id;
                          });
                          let a = document.createElement('a');
                          a.href = '/candidate/Export_Multity_Item?lst=' + lst;
                          a.click();
                      }
                  }
              },*/
            {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    elementAttr: {
                        id: "btnSendMailRecruiment",
                    },
                    icon: "fa fa-pen",
                    type: "default",
                    text: "Danh sách form thông tin",
                    hint: "Tạo link thông tin",
                    onClick: function (e) {
                        $("#modal-form").modal("show");
                    },
                },
            },
            {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    elementAttr: {
                        id: "btnSendMailRecruiment",
                    },
                    icon: "fa fa-pen",
                    type: "default",
                    text: "Tạo form điền thông tin",
                    hint: "Tạo link thông tin",
                    onClick: function (e) {
                        $("#modal-lg").modal("show");
                    },
                },
            },
            {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    elementAttr: {
                        id: "btnGetImportHistory",
                    },
                    icon: "fa fa-history",
                    type: "primary",
                    text: "Lịch sử import",
                    hint: "Import Excel History",
                    onClick: function (e) {
                        let a = document.createElement("a");
                        a.target = "_blank";
                        a.href = "/recruitment/candidate/import-history";
                        a.click();
                    },
                },
            }, //import file
            {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    elementAttr: {
                        id: "btnImportQtyXNVL",
                    },
                    icon: "fas fa-file-import",
                    type: "success",
                    text: "Nhập dũ liệu excel",
                    hint: "Import Excel",
                    onClick: function (e) {
                        $("#fileInput").click();
                    },
                },
            }, //import file
            {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    elementAttr: {
                        id: "btnCoverFormlate",
                    },
                    icon: "floppy",
                    type: "default",

                    text: "Mẫu file nhập",
                    hint: "Template",
                    onClick: function (e) {
                        let a = document.createElement("a");
                        a.target = "_blank";
                        a.href = "/storage/files/shares/template/mẫu file excel nhập ứng viên.xlsx";
                        a.click();
                    },
                },
            },
            {
                location: "after",
                widget: "dxButton",
                options: {
                    visible: true,
                    elementAttr: {
                        id: "btnCoverFormlate",
                    },
                    icon: "export",
                    type: "default",

                    text: "Xuất excel",
                    hint: "Template",
                    onClick: function (e) {
                        let a = document.createElement("a");
                        a.target = "_blank";
                        a.href = "/recruitment/candidate/export-candidate";
                        a.click();
                    },
                },
            }
        );
    },

}).dxDataGrid('instance');


GetData();

function deleteData(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "JSON",
        type: 'delete',
        url: '/recruitment/candidate/destroy/' + id,
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

$('#statusList li a').on('click', function (e) {

    e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
    // Xóa lớp 'active' khỏi tất cả các thẻ <a>
    $('#statusList li a').removeClass('active');

    // Thêm lớp 'active' vào thẻ <a> được nhấp vào
    $(this).addClass('active');
    var statusToFilter = $(this).data('status');
    if (statusToFilter === 'all') {
        gridData.clearFilter();
    } else {
        gridData.filter(["status", "=", statusToFilter]);
    }
});

$('#createForm').on("click", function (e) {
    var name = $('input[name="name"]').val();
    var gender = $('select[name="gender"]').val();
    var position_id = $('select[name="position_id"]').val();
    var email = $('input[name="email"]').val();
    var is_send_mail = $('input[name="is_send_mail"]').is(":checked");
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "JSON",
        type: "post",
        url: "/api/candidate/create-form",
        data: { name, gender, position_id, email, is_send_mail },
        success: function (resp) {
            $("#formUrl").html(
                'Link truy cập form điền thông tin là: <a href="' +
                    resp.data +
                    '">' +
                    resp.data +
                    "</a>"
            );

            // Thêm dữ liệu mới vào tbody
            var newRow = `
                <tr>
                    <td>
                        <a>
                            ${name}
                        </a>
                        <br />
                        <small>
                            ${gender}
                        </small>
                    </td>
                    <td></td>
                    <td>
                        ${email}
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm copyLink" href="${resp.data}">
                            <i class="fas fa-copy"></i>
                            Sao chép
                        </a>
                    </td>
                    <td>
                        ${is_submit ? '<span class="badge badge-success">Đã điền thông tin</span>' : '<span class="badge badge-danger">Chưa điền thông tin</span>'}
                    </td>
                    <td class="project-actions">
                        <!-- Chú ý rằng không có ID từ dữ liệu trả về, nên data-id có thể không chính xác -->
                        <a class="btn btn-info btn-sm canEdit" data-id="ID_UNKNOWN">
                            <i class="fas fa-pencil-alt"></i>
                            Cấp quyền sửa
                        </a>
                        <a class="btn btn-primary btn-sm changeCandidate" data-id="ID_UNKNOWN">
                            <i class="fas fa-pencil-alt"></i>
                            Chuyển ứng viên
                        </a>
                    </td>
                </tr>
            `;
            $("tbody").append(newRow);

            DevExpress.ui.notify(resp.mesage, "Success", 2000);
        },
        error: function (resp) {
            DevExpress.ui.notify("Cõ lỗi xảy ra", "Error", 2000);
            return;
        },
    });
});

$(".canEdit").on("click", function (e) {
    var dataId = $(this).attr("data-id");
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "JSON",
        type: "post",
        url: "/api/candidate/change-can-edit/" + dataId,
        success: function (resp) {
            if (resp.success == true) {
                DevExpress.ui.notify(resp.message, "Success", 2000);
            } else {
                DevExpress.ui.notify(resp.message, "Error", 2000);
            }
        },
        error: function (resp) {
            DevExpress.ui.notify("Cõ lỗi xảy ra", "Error", 2000);
            return;
        },
    });
});

$(".changeCandidate").on("click", function (e) {
    var dataId = $(this).attr("data-id");
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "JSON",
        type: "post",
        url: "/api/recruitment/candidate/change-to-candidate/" + dataId,
        success: function (resp) {
            if (resp.success == true) {
                var currentRow = $(this).closest("tr");
                currentRow.remove();
                GetData();
                DevExpress.ui.notify(resp.message, "Success", 2000);
            } else {
                DevExpress.ui.notify(
                    resp.message,
                    "Error",
                    2000
                );
            }
        },
        error: function (resp) {
            DevExpress.ui.notify("Cõ lỗi xảy ra", "Error", 2000);
            return;
        },
    });
});

$(".copyLink").click(function (event) {
    // Ngăn chặn sự kiện mặc định của thẻ <a>
    event.preventDefault();

    // Lấy giá trị href
    var hrefValue = $(this).attr("href");

    // Sao chép giá trị href vào clipboard
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(hrefValue).select();
    document.execCommand("copy");
    $temp.remove();

    alert("Sao chép link thành công!");
});


//console.log("datasv", dataSinhvien);



// function GetPositionData() {

//     $.ajax({
//         url: '/Position/get-data',
//         type: 'post',
//         dataType: 'json',
//         async: false,
//         success: function (data) {
//             dataPosition = data;
//         }
//     });
// }

// function GetDepartmentData() {

//     $.ajax({
//         url: '/Department/get-data',
//         type: 'post',
//         dataType: 'json',
//         async: false,
//         success: function (data) {
//             dataDepartment = data;
//         }
//     });
// }

// function GetSourceData() {

//     $.ajax({
//         url: '/Source/get-data',
//         type: 'post',
//         dataType: 'json',
//         async: false,
//         success: function (data) {
//             dataSource = data;
//         }
//     });
// }

function GetData() {

    $.ajax({
        url: '/api/recruitment/candidate/get-data',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (resp) {
            gridData.option("dataSource", resp.data);
        }
    });

}

function ImportExcel(event) {
    //upload excel and insert to BD
    var file = event.target.files[0];

    // Bạn có thể tiếp tục xử lý file tại đây, ví dụ gửi nó đến server thông qua AJAX
    var formData = new FormData();
    formData.append('excel', file);

    $.ajax({
        url: '/recruitment/candidate/import-excel',
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
