var datas;
var pivotGrid;
var pivotGridPieChart;


// console.log(datas)
// console.log('date', new Date('2013-01-06'))


$(() => {
    const pivotGridChart = $("#pivotgrid-chart")
        .dxChart({
            commonSeriesSettings: {
                type: "bar",
            },
            loadPanel: {
                enabled: true,
            },
            tooltip: {
                enabled: true,
                format: "fixedPoint",
                customizeTooltip(args) {
                    return {
                        html: `${args.seriesName} | Tổng<div class='number'>${args.valueText}</div>`,
                    };
                },
            },
            size: {
                height: 200,
            },
            adaptiveLayout: {
                width: 450,
            },
        })
        .dxChart("instance");

    // Define a variable for the pie chart
    pivotGridPieChart = $('#pieChart').dxPieChart({
        size: {
            width: 1100,
            height: 450,
        },
        loadPanel: {
            enabled: true
        },
        palette: 'Material',
        title: 'Tỷ lệ ứng viên theo nguồn tuyển dụng', // Set your own title
        legend: {
            visible: true,
        },
        series: [{
            argumentField: 'source_name',
            valueField: 'total_candidate',
            label: {
                visible: true,
                connector: {
                    visible: true,
                    width: 1,
                },
                format: 'fixedPoint',
                customizeText(point) {
                    return `${point.argumentText}: ${point.valueText} (${point.percentText})`;
                },
            }
        }],
        tooltip: {
            enabled: true,
            format: 'fixedPoint',
            customizeTooltip: function (args) {
                return {
                    html: args.argumentText + ': ' + args.valueText + ' (' + args.percentText + ')',
                };
                //return `${point.argumentText}: ${point.valueText}%`;
            }
        },
        smallValuesGrouping: {
            mode: 'smallValueThreshold',
            threshold: 4.5,
        },
        export: {
            enabled: true,
        },
    }).dxPieChart('instance');

    pivotGrid = $('#pivotgrid').dxPivotGrid({
        allowSortingBySummary: true,
        allowFiltering: true,
        showBorders: true,
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
                    caption: 'Vị trí',
                    width: 200,
                    dataField: 'position',
                    area: 'row',
                    sortBySummaryField: 'position',
                },
                {
                    dataField: 'source',
                    area: 'column',
                },
                // {
                //     dataField: 'date',
                //     dataType: 'date',
                //     area: 'column',
                //     groupInterval: 'year',
                //     headerFilter: {
                //         allowSearch: true
                //     }
                // },
                // {
                //     dataField: 'date',
                //     dataType: 'date',
                //     area: 'column',
                //     groupInterval: 'quarter',
                // },
                // {
                //     dataField: 'date',
                //     dataType: 'date',
                //     area: 'column',
                //     groupInterval: 'month',
                //     headerFilter: {
                //         allowSearch: true
                //     }
                // },
                {
                    dataField: 'count',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                },


            ],
            store: [],
        },
    }).dxPivotGrid('instance');

    // pivotGrid.bindChart(pivotGridChart, {
    //     dataFieldsDisplayMode: 'splitPanes',
    //     alternateDataFields: false,
    // });

    GetData();
    GetDataPieChart();

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

$('#filterButton').click(function (e) {
    GetData()
    GetDataPieChart()
});

function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/report/recruitment/candidate-source',
        data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            source_id: $("select[name='source_id[]']").val(),
            position_id: $("select[name='position_id[]']").val(),
            status: $("select[name='status']").val(),
        },
        dataType: 'json',
        success: function (resp) {
            if (pivotGrid) {
                pivotGrid.option("dataSource.store", resp.data);
                pivotGrid.getDataSource().reload();
            } else {
                console.error('Có lỗi xảy ra');
            }
        }
    });
}

function GetDataPieChart() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/report/recruitment/candidate-source-chart',
        data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            source_id: $("select[name='source_id[]']").val(),
            position_id: $("select[name='position_id[]']").val(),
            status: $("select[name='status']").val(),
        },
        dataType: 'json',

        success: function (resp) {
            pivotGridPieChart.option("dataSource", resp.data);
            pivotGridPieChart.refresh();
        }
    });
}

$('#clearPositionFilter').on('click', function (e) {
    e.preventDefault();
    $('#positionSelect').val(null).trigger('change');
});

$('#clearSourceFilter').on('click', function (e) {
    e.preventDefault();
    $('#sourceSelect').val(null).trigger('change');
});

$('#clearStatusFilter').on('click', function (e) {
    e.preventDefault();
    $('#statusSelect').val(null).trigger('change');
});


