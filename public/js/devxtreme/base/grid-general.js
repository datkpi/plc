// Khai báo data-grid và các thành phần
var BASIC_GRID_OPTION = {

    dataSource: [],
    //keyExpr: "ID",
    showRowLines: true,
    showColumnLines: true,
    showBorders: true,
    showColumnHeaders: true,
    showBorders: true,
    allowColumnResizing: true,
    hoverStateEnabled: true,
    columnAutoWidth: true,
    noDataText: "Không tìm thấy dữ liệu",
    allowColumnReordering: true,
    rowAlternationEnabled: false,
    wordWrapEnabled: false,
    columnChooser: { enabled: false },
    paging: {
        enabled: true,
        pageSize: 20
    },
    height: window.innerHeight*0.8,
    pager: {
        showPageSizeSelector: true,
        allowedPageSizes: [100, 200, 500, 1000],
        showInfo: true
    },
    paging: { pageSize: 50 },
    selection: {
        mode: "multiple",
        //  showCheckBoxesMode: "always",
        allowSelectAll: true
    },
    loadPanel: {
        enabled: true,
        height: 90,
        shading: true,
        showIndicator: true,
        showPane: true,
        text: "Đang load dữ liệu...",
        width: 200,
        delay: 0
    },
    filterRow: {
        visible: true,
        applyFilter: "auto"
    },
    scrolling: {
        mode: "standard",
        scrollByContent: true
    },
    headerFilter: {
        visible: true
    },
     //filterPanel: { visible: true },
    //summary: {
    //    totalItems: [
    //        {
    //            column: "STT",
    //            summaryType: "count"
    //        }
    //    ]
    //},
    export: {
        enabled: true,
        fileName: "Result " + document.title,
        allowExportSelectedData: true
    },
    editing: {
        mode: "row",
        allowAdding: false,
        allowUpdating: false,
        allowDeleting: false,
        useIcons: true,
    },
    onCellPrepared: function (e) {
        if (e.rowType == "header") {
            e.cellElement.css("font-weight", "bold");
            // e.cellElement.css("background-color", "#6915cf");
        }
        e.cellElement.css("text-align", "left");
        e.cellElement.css("vertical-align", "middle");
    },
    onContentReady: function (e) {
        alert('oke')
        e.component.columnOption("command:edit", "width", 60);

        if (e.component.shouldSkipNextReady) {
            e.component.shouldSkipNextReady = false;
        }
        else {
            e.component.shouldSkipNextReady = true;
            e.component.columnOption("command:select", "width", 30);
            e.component.updateDimensions();
        }
        if (e.element.find(".dx-datagrid-header-panel").find(".dx-toolbar-before .textstyle_1").length == 0) {
            e.element.find(".dx-datagrid-header-panel").find(".dx-toolbar-before").html('<div style="    font-size: 24px;color:red" class="textstyle_1"><B>' + document.title + '</B></div>');
        }

    },
    onRowRemoving: function (e) {
        var removeUrl = e.component.option('removeUrl');
        $.ajax({
            url: removeUrl,
            type: 'POST',
            dataType: 'json',
            async: false,
            data: { item: e.data },
            success: function (response) {
                if (response.result == "Success") {

                    DevExpress.ui.notify('Xóa dữ liệu thành công ', 'success', 1500)
                }
                else {
                    e.cancel = true;
                    DevExpress.ui.notify(response.message, 'error', 1500)
                }
            },
            error: function (err) {
                DevExpress.ui.notify('Error', 'Error', 1500)
                e.cancel = true;
            }
        });
    },
    onRowUpdating: function (e) {
        var updateUrl = e.component.option('updateUrl');
        var newItem = {};
        newItem = Object.assign(newItem, e.oldData);//   Object.create(e.oldData);
        var changedProperty = Object.keys(e.newData);
        $.each(changedProperty, function (i, prop) {
            newItem[prop] = e.newData[prop];
        })
        var newItemProperty = Object.keys(newItem);
        $.each(newItemProperty, function (i, prop) {
            if (newItem[prop] instanceof Date) {
                newItem[prop] = newItem[prop].toISOString();
            }
        })
        $.ajax({
            url: updateUrl,
            type: 'POST',
            dataType: 'json',
            async: false,
            data: { item: newItem },
            success: function (response) {
                if (response.result == "Success") {

                    DevExpress.ui.notify('Cập nhật dữ liệu thành công ', 'success', 1500)
                }
                else {
                    e.cancel = true;
                    DevExpress.ui.notify(response.message, 'error', 1500)
                }
            },
            error: function (err) {
                DevExpress.ui.notify('Error', 'Error', 1500)
                e.cancel = true;
            }
        });
    },
    onRowInserting: function (e) {
        var insertUrl = e.component.option('insertUrl');
        $.ajax({
            url: insertUrl,
            type: 'POST',
            dataType: 'json',
            async: false,
            data: { item: e.data },
            success: function (response) {
                if (response.result == "Success") {
                    var listDateTimeProperty = findDateTimeProperty(response.data);
                    if (listDateTimeProperty.length > 0) {
                        $.each(listDateTimeProperty, function (i, prop) {
                            response.data[prop] = FromJsonToDate(response.data[prop]);
                        })
                    }
                    e.data = response.data;
                    DevExpress.ui.notify('Thêm dữ liệu thành công ', 'success', 1500)
                }
                else {
                    e.cancel = true;
                    DevExpress.ui.notify(response.message, 'error', 1500)
                }
            },
            error: function (err) {
                DevExpress.ui.notify('Error', 'Error', 1500)
                e.cancel = true;
            }
        });
    },
};

//LoadBasicGridOption($('#gridTest'));
//LoadGridData($('#gridTest'), '/Register/GetRegister')
//Tạo định nghĩa cho các cột dữ liệu
var PROPERTY_CAPTION = {};
function CreateColumns(templateData) {
    var columnsData = [];
    console.log(templateData);
    for (var prop in templateData) {
        console.log(prop);
        var newColumn = {
            dataField: prop,
            caption: PROPERTY_CAPTION[prop] ? PROPERTY_CAPTION[prop] : prop,
        };
        if (prop == "Image") {
            {
                newColumn.width = 90;
                newColumn.
                    cellTemplate = function (container, options) {
                        if (options.data.ImageKind != undefined) {
                            $('<div>')
                                .append(options.value )
                                .click(function (event) {
                                    if ($("#imgPopup").length == 0) {
                                        $('<div>', { id: "imgPopup" })
                                            .appendTo('body');
                                    }
                                    $("#imgPopup").dxPopup({
                                        title: "Hình ảnh chi tiết [" + options.data.ImageKind+"]",
                                        position: 'top',
                                        width: 600,
                                        height: "auto",
                                        visible: true,
                                        contentTemplate: function (contentElement) {
                                            return $("<div />").append($("<img>").attr("id", "myImage").css("width", "100%").css("max-height", "none").css("height", "auto")).css("height", "auto");
                                        }
                                    }).dxPopup("instance");
                                    $("#imgPopup").dxPopup('show');
                                    $("#myImage").attr("src", options.value);
                                })
                                .appendTo(container);
                        }

                    };
                newColumn.editCellTemplate = function (container, options) {
                    if (options.data.ImageKind != undefined) {

                        $('<div>')
                            .append($('<img>', { src: options.value, class: "imageIQ" }).click(function (event) {
                                if ($("#imgPopup").length == 0) {
                                    $('<div>', { id: "imgPopup" })
                                        .appendTo('body');
                                }
                                $("#imgPopup").dxPopup({
                                    title: "Hình ảnh chi tiết [" + options.data.ImageKind + "]",
                                    position: 'top',
                                    width: 800,
                                    height: "auto",
                                    visible: true,
                                    contentTemplate: function (contentElement) {
                                        return $("<div />").append($("<img>").attr("id", "myImage").css("width", "100%").css("max-height", "none").css("height", "auto")).css("height", "auto");
                                    }
                                }).dxPopup("instance");
                                $("#imgPopup").dxPopup('show');
                                $("#myImage").attr("src", options.value);
                            })
                                ).appendTo(container);
                    }
                }

            }
        } else
        if (templateData[prop] instanceof Date) {
            newColumn.dataType = "date"
            if (templateData[prop].getHours() == 0 && templateData[prop].getMinutes() == 0 && templateData[prop].getSeconds() == 0) {
                newColumn.format = {
                    type: "yyyy-MM-dd"
                }
            } else {
                newColumn.format = {
                    type: "yyyy-MM-dd HH:mm:ss"
                }
            }

        }
        else
            if (!isNaN(templateData[prop])) {
                // newColumn.dataType = "number";
                if ((templateData[prop] % 1) != 0) {
                    newColumn.format = {
                        type: "fixedPoint",
                        precision: 2
                    }
                }
            }
            else
                if (typeof (templateData[prop]) === "string" && checkDate(templateData[prop])) {
                    var newDate = new Date(Date.parse(templateData[prop]))
                    newColumn.dataType = "date"
                    if (newDate.getHours() == 0 && newDate.getMinutes() == 0 && newDate.getSeconds() == 0) {
                        newColumn.format = {
                            type: "yyyy-MM-dd"
                        }
                    } else {
                        newColumn.format = {
                            type: "yyyy-MM-dd HH:mm:ss"
                        }
                    }
                }
        columnsData.push(newColumn);
    }
    columnsData.unshift({
        caption: "STT",
        cellTemplate: function (a, b) {
            a.append((b.rowIndex + 1));
        }
    });
    return columnsData;
}

//Kiểm tra xem chuỗi có phải ngày hợp lệ không
function checkDate(input) {
    input = input.substring(0, 10);
    input = input.split('-');
    if (input.length != 3) {
        return false;
    }
    var year = input[0];
    var month = input[1];
    var day = input[2];
    if (isNaN(year) || year < 2000 || year > 2050) {
        return false;
    }
    if (isNaN(month) || month > 12 || month < 1) {
        return false;
    }
    if (isNaN(day) || day > 31 || day < 1) {
        return false;
    }
    var val = isNaN(input);
    return val;
}

//Load data ra grid
function LoadBasicGridOption(newgrid) {
    var grid = $(newgrid);
    grid.dxDataGrid(BASIC_GRID_OPTION);
    grid.selectable();
}

//Lấy data từ backend qua ajax
function LoadGridData_AutoColumn(gridID, URL, columnsData = null, async = true, runfunction = null) {
    var gridName = $(gridID);

    $.ajax({
        url: URL,
        type: 'POST',
        dataType: 'json',
        async: async,
        success: function (response) {
            if (response.result == "Success") { //

                var isJson = false;
                if (typeof (response.data) === "string" && response.data != "") {
                    response.data = JSON.parse(`` + response.data + ``);
                    isJson = true;
                }
                if (response.data.length == 0) {
                    return;
                }
                var templateData = {};
                templateData = Object.assign(templateData, response.data[0]);
                $.each(Object.keys(templateData), function (i, item) {
                    if (templateData[item] == null) {
                        var prop = response.data.filter(x => x[item] != null)[0];
                        if (prop != null) {
                            templateData[item] = prop[item];
                        }
                    }
                });

                if (!isJson) {
                    var listDateTimeProperty = findDateTimeProperty(templateData);
                    if (listDateTimeProperty.length > 0) {
                        $.each(response.data, function (i, item) {
                            for (i = 0; i < listDateTimeProperty.length; i++) {
                                item[listDateTimeProperty[i]] = FromJsonToDate(item[listDateTimeProperty[i]]);
                            }
                        })
                        for (i = 0; i < listDateTimeProperty.length; i++) {
                            templateData[listDateTimeProperty[i]] = FromJsonToDate(templateData[listDateTimeProperty[i]]);
                        }
                    }
                }
                else {

                }
                if (columnsData == null) {
                    gridName.dxDataGrid("instance").option("columns", CreateColumns(templateData));
                }
                else {
                    gridName.dxDataGrid("instance").option("columns", columnsData);
                }
                gridName.dxDataGrid("instance").option("dataSource", response.data);
                // gridName.dxDataGrid("instance").refresh();
                if (runfunction != null) {
                    runfunction();
                }

            }
        },
        error: function (err) {
            DevExpress.ui.notify(data.responseJSON.data, "Error", 2000);
        }
    });
}

//Tìm trong data đối tượng có định dạng date
function findDateTimeProperty(object) {
    var listDateTimeProperty = [];
    for (var prop in object) {
        if ((typeof object[prop] == "string") && (object[prop].includes('/Date('))) {
            listDateTimeProperty.push(prop);
        }
    }
    return listDateTimeProperty;
}










// Hiển thị và ẩn 1 icon - Require Font-Awesome
function loadingButton(element) {
    element.append(`  <i class="fa fa-spinner fa-spin"></i>`);
}

function endLoadingButton(element) {
    element.find('.fa-spin').remove();
}



// Add Array Prototype - Require Jquery
function SelectColumn(arr, n) {
    return arr.map(x => x[n]);
}
// Sample Unique
//var unique = myArray.filter((v, i, a) => a.indexOf(v) === i);







// Json Date to JS Date
function FromJsonToDate(value) {
    if (value != null) {
        return new Date(parseInt(value.replace('/Date(', '')));
    }
    return;
}


//Sample MasterDetail
var masterDetailSample = {
    autoExpandAll: false,
    enabled: true,
    template: function (container, option) {
        var staffID = option.data.StaffID;
        container.append(`<div id="grid` + staffID + `"></div>`);
        LoadBasicGridOption($('#grid' + staffID + ''));
        LoadGridData($('#grid' + staffID + ''), '/Supplier/GetUserAnswerData?staffID=' + staffID);
    }
};

// Sample ContextMenu : onContextMenuPreparing
var contextMenuSample = function (e) {
    if (e.row != null) {
        if (e.row.rowType == "data") {
            let boxSubmenuItems = [
                {
                    text: 'Xem chi tiết',
                    icon: 'search',
                    onItemClick: function (ev) {
                    }
                },
            ];
            e.items = boxSubmenuItems;
        }

    }
};


// Sample ToolBar
var onToolbarPreparingIMP = function (e) {
    var toolbarItems = e.toolbarOptions.items;

    e.toolbarOptions.items.unshift(
        {
            location: "center",
            widget: "dxButton",
            options: {
                icon: "save",
                hint: "Save Box JHK Use Data",
                text: "Save Box JHK Use Data",
                onClick: function (ev) {
                    SaveBoxJHKUse(e);
                }
            }
        },
    );
};

// Date Compare
function DateCompare(date1, date2) {
    if (date1 == null || date2 == null || !(date1 instanceof Date) || !(date2 instanceof Date)) return;
    if (date1.getFullYear() == date2.getFullYear() && date1.getMonth() == date2.getMonth() && date1.getDate() == date2.getDate()) return 0; //date1 == date 2
    if (date1.getTime() > date2.getTime()) return 1; // date1 >  date 2
    return -1;
}


// Ajax Loading
$(document).ajaxStart(function () {
    $('#loading').fadeIn('fast');
    $('button').attr("disabled", true);
    $('a').addClass("disabled");
    // Disable buttons
});
$(document).ajaxStop(function () {
    $('#loading').fadeOut('fast');
    $('button').attr("disabled", false);
    $('a').removeClass("disabled");
    // Enable buttons
});


// Timer Countdown
function startTimerSample(duration, display, func) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(minutes + " phút " + seconds + " giây ");

        if (--timer < 0) {
            timer = duration;
            // Run Function
            func();
        }
    }, 1000);
}

//jQuery(function ($) {
//    var fiveMinutes = 60 * 5,
//        display = $('#time');
//    startTimer(fiveMinutes, display);
//});



//Require CSS
//.full-screen-view{
//    position:fixed;
//    left:0;
//    top:0;
//    z-index:99999;
//}

function FullScreenView(newgrid, enable = true) {
    var grid = $(newgrid);
    if (enable) {
        grid.dxDataGrid('instance').option('width', "100%");
        grid.dxDataGrid('instance').option('height', "100%");
        grid.addClass('full-screen-view')
    }
    else {
        grid.dxDataGrid('instance').option('width', "100%");
        grid.dxDataGrid('instance').option('height', window.innerHeight * 0.8);
        grid.removeClass('full-screen-view')
    }
}


var Fullscreenbutton = [
    {
        location: "after",
        widget: "dxButton",
        options: {
            icon: "fa fa-expand",
            hint: "Full Screen",
            text: "Full Screen",
            onClick: function (ev) {
                FullScreenView(e.element, true);
            }
        }
    },
    {
        location: "after",
        widget: "dxButton",
        options: {
            icon: "fa fa-compress",
            hint: "Minimize",
            text: "Minimize",
            onClick: function (ev) {
                FullScreenView(e.element, false);
            }
        }
    }];



function parseDate(str) {
    var mdy = str.split('-');
    return new Date(mdy[0], mdy[1] - 1, mdy[2]);
}

function datediff(first, second) {
    // Take the difference between the dates and divide by milliseconds per day.
    // Round to nearest whole number to deal with DST.
    return Math.round((second - first) / (1000 * 60 * 60 * 24));
}


// Post Grid DataSource To URL
function SaveData(e, URL, element) {
    alert('1')
    var dataSource = e.component.option('dataSource');
    if (dataSource.length == 0) {
        alert('Không có dữ liệu , xin kiểm tra lại !!!!!!!');
        return;
    }
    var listDateTimeProperty = FindDateProperty(dataSource[0]);
    if (listDateTimeProperty.length > 0) {
        $.each(dataSource, function (i, item) {
            for (i = 0; i < listDateTimeProperty.length; i++) {
                item[listDateTimeProperty[i]] = item[listDateTimeProperty[i]].toISOString();
            }
        })
    }
    loadingButton(element);
    $.ajax({
        url: URL,
        type: "POST",
        data: { listData: dataSource },
        success: function (response) {
            if (response.result == "Success") {
                gridNewCostDown.option('dataSource', []);
                DevExpress.ui.notify("Success", "success", 5000);
            }
            else {
                alert(response.message);
            }
            $("#excelinput").val('');
            endLoadingButton(element);
        },
        error: function (err) {
            DevExpress.ui.notify("Error!!!!", "error", 5000);
            $("#excelinput").val('');
            endLoadingButton(element);
        }
    })
}



function checkDate(input) {
    input = input.substring(0, 10);
    input = input.split('-');
    if (input.length != 3) {
        return false;
    }
    var year = input[0];
    var month = input[1];
    var day = input[2];
    if (isNaN(year) || year < 2000 || year > 2050) {
        return false;
    }
    if (isNaN(month) || month > 12 || month < 1) {
        return false;
    }
    if (isNaN(day) || day > 31 || day < 1) {
        return false;
    }
    var val = isNaN(input);
    return val;
}


//#region selectDragTable
///selectDragTable
//<![CDATA[

//.selected {
//    background-color: #CECECE;
//}

//.selectionbox {
//    position: absolute;
//    background: transparent;
//    border: 1px dotted #000;
//}
// Chainable jQuery method to add range select functionality to an HTML table
// ToDo: support shift+select
// ToDo: disable text selection selectively
// ToDo: constrain to parent boundaries

//1 hàm cho phép chọn các hàng trong grid và sao chép vào clipboard
$.fn.extend({
    selectable: function (options) {
        // Internal methods
        var defaults = {
            highlightClass: 'selected'
        };
        options = $.extend(defaults, options);
        var getOverlappingElements = function (overlay, rootElement) {
            return $(rootElement)
                .find('*')
                .filter(function () {
                    var rect1 = this.getBoundingClientRect(),
                        rect2 = overlay.getBoundingClientRect();
                    var overlap = !(rect1.right < rect2.left || rect1.left > rect2.right || rect1.bottom < rect2.top || rect1.top > rect2.bottom);
                    return overlap;
                });
        }

        var getSelections = function (rootElement) {
            // Build delimited array from selections for paste
            var $elements = $(rootElement).find('td.' + options.highlightClass),
                selections = '';
            if (!$elements || !$elements.length) {
                return selections;
            }
            var firstRow = $elements[0].closest('tr').rowIndex,
                rowArray = [];
            for (var i = 0; i < $elements.length; i++) {
                var thisRowIndex = $elements[i].closest('tr').rowIndex - firstRow;
                if (!rowArray[thisRowIndex]) {
                    rowArray[thisRowIndex] = [];
                }
                var $visibleInputs = $($elements[i]).find('input:visible')
                var hasInput = $visibleInputs.length > 0;
                if (!hasInput) {
                    rowArray[thisRowIndex].push($elements[i].innerText);
                } else {
                    rowArray[thisRowIndex].push($visibleInputs[0].value);
                }
            }
            // Build delimited string from array
            for (var i = 0; i < rowArray.length; i++) {
                for (var j = 0; j < rowArray[i].length; j++) {
                    selections += rowArray[i][j];
                    if (j < rowArray[i].length - 1) {
                        selections += '\t';
                    }
                }
                if (i < rowArray.length - 1) {
                    selections += '\n';
                }
            }
            return selections;
        }

        var clearAll = function (rootElement) {
            $(rootElement).find('.' + options.highlightClass).removeClass(options.highlightClass);
        }

        var TrelloClipboard = function () {
            var me = this;

            var utils = {
                nodeName: function (node, name) {
                    return !!(node.nodeName.toLowerCase() === name)
                }
            }
            var textareaId = 'simulate-trello-clipboard',
                containerId = textareaId + '-container',
                container, textarea

            var createTextarea = function () {
                container = document.querySelector('#' + containerId)
                if (!container) {
                    container = document.createElement('div')
                    container.id = containerId
                    container.setAttribute('style', [, 'position: fixed;', 'left: 0px;', 'top: 0px;', 'width: 0px;', 'height: 0px;', 'z-index: 100;', 'opacity: 0;'].join(''))
                    document.body.appendChild(container)
                }
                container.style.display = 'block'
                textarea = document.createElement('textarea')
                textarea.setAttribute('style', [, 'width: 1px;', 'height: 1px;', 'padding: 0px;'].join(''))
                textarea.id = textareaId
                container.innerHTML = ''
                container.appendChild(textarea)

                textarea.appendChild(document.createTextNode(me.value))
                textarea.focus()
                textarea.select()
            }

            var keyDonwMonitor = function (e) {
                var code = e.keyCode || e.which;
                if (!(e.ctrlKey || e.metaKey)) {
                    return
                }
                var target = e.target
                if (utils.nodeName(target, 'textarea') || utils.nodeName(target, 'input')) {
                    return
                }
                if (window.getSelection && window.getSelection() && window.getSelection().toString()) {
                    return
                }
                if (document.selection && document.selection.createRange().text) {
                    return
                }
                setTimeout(createTextarea, 0)
            }

            var keyUpMonitor = function (e) {
                var code = e.keyCode || e.which;
                if (e.target.id !== textareaId) {
                    return
                }
                container.style.display = 'none'
            }

            document.addEventListener('keydown', keyDonwMonitor)
            document.addEventListener('keyup', keyUpMonitor)
        }

        TrelloClipboard.prototype.setValue = function (value) {
            this.value = value;
        }
        // End internal methods
        // Return chainable function, so calls like $('#myDiv).selectable() will work
        return $(this).each(function () {
            var $container = $(this),
                $selection = $('<div>').addClass('selectionbox');
            $container.on('mousedown', function (e) {
                if (!e.shiftKey) {
                    return true;
                }
                $('.dx-scrollable-wrapper').attr('class', 'classtmpwrapper');
                $('.dx-scrollable-container').attr('class', 'classtmpcontainer');
                $('.dx-scrollable-content').attr('class', 'classtmpcontent');


                // All bets are off if click target is an input or similar such, so just short-circuit.
                // This will keep features like select, cut, and paste
                // ... within the input working as expected.
                if ($(e.target).is(':input,textarea,a,button')) {
                    clearAll($container[0]);
                    return true;
                }
                // Globally preventing default will be problematic, and
                // may cause components embedded in the control to fail to respond to click events
                // Instead, we attempt to suss intent in the mouseup handler
                var startY = e.pageY,
                    startX = e.pageX,
                    newX,
                    newY,
                    height,
                    width;

                $selection.css({
                    top: startY,
                    left: startX,
                    width: 0,
                    height: 0
                });


                $selection.appendTo($container);
                $container.on('mousemove', function (e) {
                    e.preventDefault();


                    var moveX = e.pageX,
                        moveY = e.pageY;

                    width = Math.abs(moveX - startX),
                        height = Math.abs(moveY - startY);
                    newX = (moveX < startX) ? (startX - width) : startX;
                    newY = (moveY < startY) ? (startY - height) : startY;

                    $selection.css({
                        width: width,
                        height: height,
                        top: newY,
                        left: newX
                    });
                }).one('mouseup', function (e) {
                    $container.off('mousemove');
                    var top = newY,
                        bottom = newY + height,
                        left = newX,
                        right = newX + width;
                    // distinguish between drag and click
                    if (Math.max(height, width) > 5) {
                        // Non-trivial movement, process event as drag
                        clearAll($container[0]);
                        var originalEvent = e.originalEvent;
                        if (originalEvent) {
                            originalEvent.preventDefault();
                        }
                        elements = getOverlappingElements($selection[0], $container[0])
                            .filter('td').addClass(options.highlightClass);
                        $selection.remove();
                    } else {
                        // Minimal movement. Process as click.
                        $selection.remove();
                        // The event target is actually the overlay. Get the element underneath to process click
                        var targetElement = document.elementFromPoint(e.clientX, e.clientY);
                        var $thisTd = $(targetElement).closest('td');
                        $(this).find('td').not($thisTd).removeClass(options.highlightClass);
                        $thisTd.toggleClass(options.highlightClass);
                        $(targetElement).click();
                    }
                    var selectionString = getSelections($container[0]);
                    if (selectionString) {
                        var clip = new TrelloClipboard();
                        clip.setValue(selectionString);
                    }
                    $('.classtmpwrapper').attr('class', 'dx-scrollable-wrapper');
                    $('.classtmpcontainer').attr('class', 'dx-scrollable-container');
                    $('.classtmpcontent').attr('class', 'dx-scrollable-content');


                });
            });
        });
    }
});

//#endregion
