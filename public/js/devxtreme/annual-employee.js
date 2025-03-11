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
                        url: "/annual-employee/store",
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



