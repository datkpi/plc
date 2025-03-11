var datas;
var funnelData;
var pieData;

GetData();
GetDataFunnel();
// GetDataPie();
// console.log(datas)
// console.log('date', new Date('2013-01-06'))

$(() => {
    const pivotGridChart = $('#pivotgrid-chart').dxChart({
        commonSeriesSettings: {
            type: 'bar',
        },
        tooltip: {
            enabled: true,
            format: 'currency',
            customizeTooltip(args) {
                return {
                    html: `${args.seriesName} | Total<div class='currency'>${args.valueText}</div>`,
                };
            },
        },
        size: {
            height: 200,
        },
        palette: 'Material',
        adaptiveLayout: {
            width: 450,
        },
    }).dxChart('instance');

    const pivotGrid = $('#pivotgrid').dxPivotGrid({
        allowSortingBySummary: true,
        allowFiltering: true,
        showBorders: true,
        showColumnGrandTotals: true,
        showRowGrandTotals: true,
        showRowTotals: false,
        showColumnTotals: false,
        texts: {
            grandTotal: "Tổng"
        },
        export: {
            enabled: true
        },
        showTotalsPrior: {
            rows: true,
            columns: true
        },
        // fieldPanel: {
        //     showColumnFields: true,
        //     showDataFields: true,
        //     showFilterFields: true,
        //     showRowFields: true,
        //     allowFieldDragging: true,
        //     visible: true,
        // },
        dataSource: {
            fields: [{
                    caption: 'Phòng ban',
                    width: 120,
                    dataField: 'department',
                    area: 'row',
                    sortBySummaryField: 'department',
                }, {
                    caption: 'Vị trí',
                    dataField: 'position',
                    width: 150,
                    area: 'row',
                }, {
                    dataField: 'date',
                    dataType: 'date',
                    area: 'column',
                    groupInterval: 'year',
                    headerFilter: {
                        allowSearch: true
                    }
                }, {
                    dataField: 'date',
                    dataType: 'date',
                    area: 'column',
                    groupInterval: 'month',
                    headerFilter: {
                        allowSearch: true
                    }
                },
                {
                    caption: 'Ứng viên',
                    dataField: 'total_count',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell'
                },
                // {
                //     caption: 'Nhân viên',
                //     dataField: 'employee_count',
                //     dataType: 'number',
                //     area: 'data',
                //     summaryType: 'sum'
                // },
                //  {
                //     caption: 'Từ chối',
                //     dataField: 'reject_count',
                //     dataType: 'number',
                //     area: 'data',
                //     summaryType: 'sum'
                // },
                //  {
                //     caption: 'Tổng',
                //     dataField: 'total_count',
                //     dataType: 'number',
                //     area: 'data',
                //     summaryType: 'sum'
                // },
                // {
                //     caption: 'Tổng',
                //     dataField: 'status_count',
                //     dataType: 'number',
                //     summaryType: 'sum',
                //     format: '',
                //     area: 'data',
                // }
            ],
            store: datas,
        },
    }).dxPivotGrid('instance');

    pivotGrid.bindChart(pivotGridChart, {
        dataFieldsDisplayMode: 'splitPanes',
        alternateDataFields: false,
    });

    function expand() {
        const dataSource = pivotGrid.getDataSource();
        dataSource.expandHeaderItem('row', ['North America']);
        dataSource.expandHeaderItem('column', [2013]);
    }

    setTimeout(expand, 0);
});

// Mở tất cả các dropdown khi tải dữ liệu
// pivotGrid.getDataSource().expandAll(0);
// pivotGrid.getDataSource().expandAll(1);

$(() => {
    $('#funnelChart').dxFunnel({
        dataSource: funnelData,
        // title: {
        //     text: 'Website Conversions',
        //     margin: {
        //         bottom: 30
        //     },
        //     visible: false,
        // },
        argumentField: 'status_value',
        valueField: 'value',
        palette: 'Material',
        export: {
            enabled: false,
        },
        tooltip: {
            enabled: true,
            format: '-==',
        },
        item: {
            border: {
                visible: true,
            },
        },
        label: {
            visible: true,
            position: 'inside',
            backgroundColor: 'none',
            customizeText(e) {
                return `<span style='font-size: 28px'>${
          e.percentText
        }</span><br/>${
          e.item.argument}`;
            },
        },
    });
});

$(() => {
    // Fetch data using AJAX
    $.ajax({
        url: '/api/recruitment/dashboard/get-data-pie', // Replace with your API endpoint
        method: "GET",
        dataType: "json",
        success: function(data) {
            // Initialize the pie chart with fetched data
            $('#pieChart').dxPieChart({
                type: 'doughnut',
                palette: 'Material',
                dataSource: data.data,
                //title: 'The Population of Continents and Regions',
                tooltip: {
                    enabled: true,
                    format: 'Tỷ lệ',
                    customizeTooltip(arg) {
                        return {
                            text: `${arg.valueText} - ${(arg.percent * 100).toFixed(2)}%`
                        };
                    }
                },
                legend: {
                    horizontalAlignment: 'right',
                    verticalAlignment: 'top',
                    margin: 0
                },
                export: {
                    enabled: true
                },
                series: [{
                    argumentField: 'position',  // Make sure 'region' is the correct field in your data
                    valueField: 'count_candidate',
                    // label: {
                    //     visible: true,
                    //     format: 'millions',
                    //     connector: {
                    //         visible: true
                    //     }
                    // }
                }]
            });
        },
        error: function(error) {
            console.error("Failed to fetch data", error);
        }
    });
});

const dataSource = [{
        position: "Nhân viên IT",
        val: 4
    },
    {
        position: "Nhân viên kỹ thuật",
        val: 1
    },
    {
        position: "Trưởng Ban NSCL",
        val: 1
    }
];


function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/dashboard/get-data',
        dataType: 'json',
        async: false,
        success: function (resp) {
            datas = resp.data;
        },
        error: function (err) {
            console.error("Có lỗi trong quá trình xử lý", err);
        }
    });
}

function GetDataFunnel() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/dashboard/get-data-funnel',
        dataType: 'json',
        async: false,
        success: function (resp) {
            funnelData = resp.data;
            //initFunnelChart(resp.data);
        }
    });
}


// function GetDataPie() {
//     $.ajax({
//         method: 'get',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         url: '/api/recruitment/dashboard/get-data-pie',
//         dataType: 'json',
//         async: false,
//         success: function (resp) {
//  if (resp && resp.data) {
//             doughnutChart.option("dataSource", resp.data);
//         } else {
//             console.error("Data structure is not as expected");
//         }
//         },
//         error: function (err) {
//             console.error("Có lỗi trong quá trình xử lý", err);
//         }
//     });
// }
