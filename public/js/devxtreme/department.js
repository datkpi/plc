var datas;




function deleteData(id) {
    $.ajax({
        method: 'delete',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/recruitment/department/destroy/'+id,
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

GetData()



function initializeDiagram(datas) {
    let generatedID = 100;
       const transformedData = datas.map(data => {
        return {
            ID: data.id,
            Head_ID: data.parent_id,
            Full_Name: data.name,
            // Bạn có thể thêm các trường khác nếu cần
        };
    });
    const store = new DevExpress.data.ArrayStore({
        key: 'ID',
        data: datas,
        onInserting(values) {
            values.ID = values.id || (generatedID += 1);
            values.Full_Name = values.name || "Employee's Name";
            values.Title = values.name || "Employee's Title";
        },
    });
    var diagramInstance = $('#diagram').dxDiagram({
        customShapes: [{
            type: 'datas',
            category: 'employee',
            baseType: 'rectangle',
            title: 'New Employee',
            defaultWidth: 1.5,
            defaultHeight: 1,
            toolboxWidthToHeightRatio: 2,
            minWidth: 1.5,
            minHeight: 1,
            maxWidth: 3,
            maxHeight: 2,
            allowEditText: false,
        }],
        customShapeTemplate(item, $container) {
            const employee = item.dataItem;
            const svgNS = 'http://www.w3.org/2000/svg';
            const $content = $(document.createElementNS(svgNS, 'svg')).addClass('template');
            // $(document.createElementNS(svgNS, 'text'))
            //     .addClass('template-name')
            //     .attr({
            //         x: '50%',
            //         y: '20%'
            //     })
            //     .text(employee ? employee.Full_Name : "Employee's Name")
            //     .appendTo($content);
            $(document.createElementNS(svgNS, 'text'))
                .addClass('template-title')
                .attr({
                    x: '50%',
                    y: '45%'
                })
                .text(employee ? employee.Full_Name : "Employee's Title")
                .appendTo($content);

            $(document.createElementNS(svgNS, 'text'))
                .addClass('template-button')
                .attr({
                    id: 'employee-edit',
                    x: '40%',
                    y: '85%'
                })
                .text('Edit')
                .click(() => {
                    editEmployee(employee);
                })
                .appendTo($content);
            $(document.createElementNS(svgNS, 'text'))
                .addClass('template-button')
                .attr({
                    id: 'employee-delete',
                    x: '62%',
                    y: '85%'
                })
                .text('Delete')
                .click(() => {
                    deleteEmployee(employee);
                })
                .appendTo($content);
            $container.append($content);
        },
        customShapeToolboxTemplate(item, $container) {
            const $content = $("<svg class='template'>" +
                "<text x='50%' y='40%'>New</text>" +
                "<text x='50%' y='70%'>Employee</text>" +
                '</svg >');
            $container.append($content);
        },
        nodes: {
            dataSource: transformedData,
            keyExpr: 'ID',
            typeExpr: function (item) {
                return 'datas';
            },
            parentKeyExpr: 'Head_ID',
            // customDataExpr(obj, value) {
            //     if (value === undefined) {
            //         return {
            //             Full_Name: obj.Full_Name,
            //             Prefix: obj.Prefix,
            //             Title: obj.Title,
            //             City: obj.City,
            //             State: obj.State,
            //             Email: obj.Email,
            //             Skype: obj.Skype,
            //             Mobile_Phone: obj.Mobile_Phone,
            //         };
            //     }
            //     obj.Full_Name = value.Full_Name;
            //     obj.Prefix = value.Prefix;
            //     obj.Title = value.Title;
            //     obj.City = value.City;
            //     obj.State = value.State;
            //     obj.Email = value.Email;
            //     obj.Skype = value.Skype;
            //     obj.Mobile_Phone = value.Mobile_Phone;
            //     return null;
            // },
            styleExpr: itemStyleExpr,
            autoLayout: {
                type: 'tree',
            },
        },
        onRequestLayoutUpdate(e) {
            alert('update')
            for (let i = 0; i < e.changes.length; i += 1) {
                if (e.changes[i].type === 'remove') {
                    e.allowed = true;
                } else if (e.changes[i].data.Head_ID !== undefined && e.changes[i].data.Head_ID !==
                    null) {
                    e.allowed = true;
                }
            }
        },
        contextToolbox: {
            enabled: false, // tắt nút thêm
            // shapeIconsPerRow: 1,
            // width: 100,
        },
        toolbox: {
            visibility: 'false',  //tắt tool box thêm phòng ban
            // shapeIconsPerRow: 1,
            // showSearch: false,
            // groups: [{
            //     category: 'employee',
            //     title: 'Employee',
            //     expanded: true
            // }, ],
        },
        propertiesPanel: {
            tabs: [{
                groups: [{
                    title: 'Page Properties',
                    commands: ['pageSize', 'pageOrientation', 'pageColor']
                }],
            }, ],
        },
    }).dxDiagram('instance');
}

const popupContentTemplate = function ($container) {
    const $editorsContainer = $('<div class="dx-fieldset" />').appendTo($container);
    const $nameField = $(
        '<div class="dx-field"><div class="dx-field-label">Name</div><div class="dx-field-value" data-field="Full_Name" /></div>'
    );
    $nameField.find('.dx-field-value').append('<div />').dxTextBox();
    const $titleField = $(
        '<div class="dx-field"><div class="dx-field-label">Title</div><div class="dx-field-value" data-field="Title" /></div>'
    );
    $titleField.find('.dx-field-value').append('<div /').dxTextBox();
    const $cityField = $(
        '<div class="dx-field"><div class="dx-field-label">City</div><div class="dx-field-value" data-field="City" /></div>'
    );
    $cityField.find('.dx-field-value').append('<div /').dxTextBox();
    const $stateField = $(
        '<div class="dx-field"><div class="dx-field-label">State</div><div class="dx-field-value" data-field="State" /></div>'
    );
    $stateField.find('.dx-field-value').append('<div /').dxTextBox();
    const $emailField = $(
        '<div class="dx-field"><div class="dx-field-label">Email</div><div class="dx-field-value" data-field="Email" /></div>'
    );
    $emailField.find('.dx-field-value').append('<div /').dxTextBox();
    const $skypeField = $(
        '<div class="dx-field"><div class="dx-field-label">Skype</div><div class="dx-field-value" data-field="Skype" /></div>'
    );
    $skypeField.find('.dx-field-value').append('<div /').dxTextBox();
    const $phoneField = $(
        '<div class="dx-field"><div class="dx-field-label">Phone</div><div class="dx-field-value" data-field="Mobile_Phone" /></div>'
    );
    $phoneField.find('.dx-field-value').append('<div /').dxTextBox();

    $editorsContainer.append(
        $nameField,
        $titleField,
        $cityField,
        $stateField,
        $emailField,
        $skypeField,
        $phoneField,
    );
    const $buttonsContainer = $('<div class="dx-fieldset buttons" />').appendTo($container);
    $buttonsContainer.append(
        $('<button />').dxButton({
            text: 'Update',
            type: 'default',
            onClick: updateEmployee,
        }),
        $('<button />').dxButton({
            text: 'Cancel',
            onClick: cancelEditEmployee,
        }),
    );
};
const popup = $('#popup').dxPopup({
    width: 400,
    height: 480,
    showTitle: true,
    title: 'Edit Employee',
    visible: false,
    dragEnabled: false,
    contentTemplate: popupContentTemplate.bind(this),
}).dxPopup('instance');

function itemStyleExpr(obj) {
    return {
        fill: '#F0FFF0',
    };
}

let currentEmployee = {};

const editEmployee = function (employee) {
    console.log('e',employee);
    const url = `/recruitment/department/edit/${employee.ID}`;
    window.location.href = url;
    // currentEmployee = {
    //     ...employee
    // };

    // popup.show();
    // popup.content().find('.dx-field-value').each(function () {
    //     const field = $(this).attr('data-field');
    //     const edit = $(this).dxTextBox('instance');
    //     edit.option({
    //         value: currentEmployee[field],
    //         onValueChanged(e) {
    //             handleChange(field, e.value);
    //         },
    //     });
    // });
};
const deleteEmployee = function (employee) {
    deleteData(employee.ID)
    // store.push([{
    //     type: 'remove',
    //     key: employee.ID
    // }]);
};
// const updateEmployee = function () {
//     store.push([{
//         type: 'update',
//         key: currentEmployee.ID,
//         data: {
//             Full_Name: currentEmployee.Full_Name,
//             Title: currentEmployee.Title,
//             City: currentEmployee.City,
//             State: currentEmployee.State,
//             Email: currentEmployee.Email,
//             Skype: currentEmployee.Skype,
//             Mobile_Phone: currentEmployee.Mobile_Phone,
//         },
//     }]);
//     popup.hide();
// };
function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/department/get-data',
        dataType: 'json',
        async: false,
        success: function (resp) {
            datas = resp.data;
            initializeDiagram(datas)
            diagramInstance.fitToContent();
        },
        error: function (error) {
            console.error("Lỗi khi gọi API: ", error);
        }
    });
}
const cancelEditEmployee = function () {
    currentEmployee = {};
    popup.hide();
};
const handleChange = function (field, value) {
    currentEmployee[field] = value;
};


