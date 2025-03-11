var datas;
var pivotGrid;
var pivotGridPieChart;
var viewMode = 'group';
// console.log(datas)
// console.log('date', new Date('2013-01-06'))

function getDataSourceFields() {
    var fields = [
    ];

    if (viewMode === 'group') {
        fields.push(
            {
                caption: 'Vị trí',
                dataField: 'position_name',
                width: 150,
                area: 'row', // Dữ liệu cho mỗi hàng
            },
            {
                caption: 'Nhu cầu phát sinh',
                dataField: 'more',
                dataType: 'number',
                area: 'data', // Dữ liệu tổng hợp
                summaryType: 'sum',
            },
            {
                caption: 'Giảm trừ',
                dataField: 'sub',
                dataType: 'number',
                area: 'data', // Dữ liệu tổng hợp
                summaryType: 'sum',
            },
            {
                caption: 'Đã tuyển',
                dataField: 'recruitmented',
                dataType: 'number',
                area: 'data', // Dữ liệu tổng hợp
                summaryType: 'sum',
            },
            {
                caption: 'Còn cần tuyển',
                dataField: 'totalData',
                dataType: 'number',
                area: 'data', // Dữ liệu tổng hợp
                summaryType: 'sum',
            }
        );

        // ['more', 'sub', 'total', 'recruitmented'].forEach(field => {
        //     fields.push({
        //         caption: field.charAt(0).toUpperCase() + field.slice(1),
        //         dataField: field,
        //         dataType: 'text',
        //         area: 'colunm',
        //         summaryType: 'sum',
        //         cssClass: 'centered-cell',
        //         customizeText: function (data) {
        //             return data.value == 0 || data.value == undefined ? '' : String(data.value);
        //         }
        //     });
        // });
    } else if (viewMode === 'month') {
        fields.push(
            // Các trường cho chế độ xem 'month'
            {
                caption: 'Vị trí',
                dataField: 'position.name',
                width: 150,
                area: 'row',
            },
            {
                dataField: "time",
                dataType: "text",
                area: "column",
            },
            {
                caption: 'Nhu cầu đầu kỳ',
                dataField: 'need',
                dataType: 'number',
                area: 'data',
                summaryType: 'sum',
                cssClass: 'centered-cell',
                customizeText: function (data) {
                    return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                }
            },
            {
                caption: 'Nhu cầu phát sinh',
                dataField: 'more',
                dataType: 'number',
                area: 'data',
                summaryType: 'sum',
                cssClass: 'centered-cell',
                customizeText: function (data) {
                    return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                }
            },

            {
                caption: 'Giảm trừ',
                dataField: 'sub',
                dataType: 'number',
                area: 'data',
                summaryType: 'sum',
                cssClass: 'centered-cell',
                customizeText: function (data) {
                    return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                }
            },
            {
                caption: 'Tổng nhu cầu',
                dataField: 'totalData',
                dataType: 'number',
                area: 'data',
                summaryType: 'sum',
                cssClass: 'centered-cell',
                customizeText: function (data) {
                    return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                }
            },
            {
                caption: 'Đã tuyển',
                dataField: 'recruitmented',
                dataType: 'number',
                area: 'data',
                summaryType: 'sum',
                cssClass: 'centered-cell',
                customizeText: function (data) {
                    return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                }
            },
        );
    }

    return fields;
}


$(() => {
    pivotGridChart = $('#pivotgrid-chart').dxChart({
        dataSource: {
            fields: getDataSourceFields(),
            store: datas,
        },
        commonSeriesSettings: {
            argumentField: 'time',
            type: "stackedBar",
            label: {
                visible: true,
                format: {
                    type: "decimal"
                },
                connector: {
                    visible: true
                }
            }
        },
        series: [{
                valueField: 'total_need',
                name: 'Nhu cầu',
                stack: "apples"
            },
            {
                valueField: 'total_more',
                name: 'Phát sinh',
                stack: "apples"
            },
            // {
            //     valueField: 'total_sub',
            //     name: 'Giảm trừ',
            //     stack: "bananas"
            // },
            // {
            //     valueField: 'total_total',
            //     name: 'Tổng nhu cầu'
            // },
            {
                valueField: "total_sub",
                name: "Giảm trừ",
            },
            {
                valueField: "recruited_total",
                name: "Đã tuyển",
            }
        ],
        legend: {
            verticalAlignment: 'bottom',
            horizontalAlignment: 'center',
            itemTextPosition: 'top',
        },
        label: {
            visible: true,
            position: 'outside',

            customizeText() {
                return `${this.valueText}`;
            },
        },
        valueAxis: {
            title: {
                text: 'Ứng viên',
            },
            position: 'left',
        },
        title: 'Biểu đồ kế hoạch tuyển dụng',
        export: {
            enabled: true,
        },
        // onPointCustomize: function (pointInfo) {
        //     if (pointInfo.seriesName === "total_total") { // Tên của series cuối cùng trong stack
        //         pointInfo.label.text = (pointInfo.value + pointInfo.stackValue).toString();
        //     }
        // },
        tooltip: {
            enabled: true,
            customizeTooltip: function (arg) {
                return {
                    text: arg.percentText + " - " + arg.valueText
                };
            }
        }
    }).dxChart('instance');

    pivotGrid = $('#pivotgrid').dxPivotGrid({
        allowSortingBySummary: true,
        allowFiltering: true,
        showBorders: false,
        showColumnGrandTotals: true,
        showRowGrandTotals: true,
        showRowTotals: false,
        height: 700,
        showColumnTotals: false,
        texts: {
            grandTotal: "Tổng"
        },
        scrolling: {
            mode: 'virtual',
        },
        export: {
            enabled: true
        },
        showTotalsPrior: {
            rows: true,
            columns: true
        },
        dataSource: {
            fields: getDataSourceFields(),
            store: []
        },
    }).dxPivotGrid('instance');

    GetData()
});

function updatePivotGrid() {
    pivotGrid.option("dataSource.fields", getDataSourceFields());
    pivotGrid.getDataSource().reload();
}

function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/report/recruitment',
        dataType: 'json',
        data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            position_id: $("select[name='position_id[]']").val(),
            display_type: $("select[name='display_type']").val(),
        },
        success: function (resp) {
            viewMode = $("select[name='display_type']").val(); // Cập nhật viewMode dựa trên lựa chọn của người dùng
            // pivotGrid.option("dataSource", {
            //     fields: getDataSourceFields(), // Cập nhật trường dữ liệu
            //     store: preprocessData(resp.data) // Cập nhật dữ liệu
            // });
            updatePivotGridConfiguration(resp.data);
        }
    });
}

function updatePivotGridConfiguration(data) {
    pivotGrid.option({
        dataSource: {
            fields: getDataSourceFields(viewMode),
            store: preprocessData(data)
        },
        showColumnGrandTotals: viewMode === 'group',
        showRowGrandTotals: viewMode === 'group',
    });
}


function preprocessData(data) {
    if(viewMode == 'group')
    {
        return data.map(item => {
            const more = Number(item.more);
            const sub = Number(item.sub);
            const recruitmented = Number(item.recruitmented);

            if (!isNaN(more) && !isNaN(sub) && !isNaN(recruitmented)) {
                item.totalData = more - sub - recruitmented;
            } else {
                item.totalData = NaN;
            }
            return item;
        });
    }
    else{
        return data.map(item => {
            const need = Number(item.need);
            const more = Number(item.more);
            const sub = Number(item.sub);
            //const recruitmented = Number(item.recruitmented);

            if (!isNaN(more) && !isNaN(sub) && !isNaN(need)) {
                item.totalData = more + need - sub;
            } else {
                item.totalData = NaN;
            }
            return item;
        });
    }
}

var data = [
    {
        time: "01-01-2018",
        jobTitle: "Chuyên viên công nghệ thông tin",
        need: 6,
        more: 1,
        sub: 0,
        total: 7,
        recruitmented: 12
    },
    {
        time: "02-02-2018",
        jobTitle: "Chuyên viên công nghệ thông tin",
        need: 6,
        more: 1,
        sub: 0,
        total: 7,
        recruitmented: 12
    },
    {
        time: "01-01-2019",
        jobTitle: "Chuyên viên công nghệ thông tin",
        need: 6,
        more: 1,
        sub: 0,
        total: 7,
        recruitmented: 12
    },
    // Thêm dữ liệu cho các năm khác
];

// Sử dụng dữ liệu này để hiển thị bảng

function GetDataChart() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/report/recruitment/chart',
        dataType: 'json',
        data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            position_id: $("select[name='position_id[]']").val(),
            display_type: $("select[name='display_type']").val(),
        },
        success: function (resp) {
            pivotGridChart.option("dataSource", resp.data);
            pivotGridChart.refresh();
            //datas = resp.data;
        }
    });
}

$('#clearPositionFilter').on('click', function (e) {
    e.preventDefault();
    $('#positionSelect').val(null).trigger('change');
});

$('#filterButton').click(function (e) {
    //GetDataChart()
    GetData()
});
