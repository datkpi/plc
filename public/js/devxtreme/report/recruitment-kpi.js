var datas;
var pivotGrid;
var pivotGridChart;


//GetData();
// console.log(datas)
// console.log('date', new Date('2013-01-06'))

$(() => {
    // const pivotGridChart = $('#pivotgrid-chart').dxChart({
    //     commonSeriesSettings: {
    //         type: 'bar',
    //     },
    //     tooltip: {
    //         enabled: true,
    //         format: 'fixedPoint',
    //         customizeTooltip(args) {
    //             return {
    //                 html: `${args.seriesName} | Tổng<div class='number'>${args.valueText}</div>`,
    //             };
    //         },
    //     },
    //     size: {
    //         height: 200,
    //     },
    //     adaptiveLayout: {
    //         width: 450,
    //     },
    // }).dxChart('instance');


    pivotGridChart = $('#pivotgrid-chart').dxChart({
        dataSource: [],
        commonSeriesSettings: {
            argumentField: 'user',
            type: 'bar',
            hoverMode: 'allArgumentPoints',
            selectionMode: 'allArgumentPoints',
            label: {
                visible: true,
                format: {
                    type: 'fixedPoint',
                    precision: 0,
                },
            },
        },
        series: [{
                valueField: 'slhs_total',
                name: 'SLHS'
            },
            {
                valueField: 'pvsb_total',
                name: 'PVSB'
            }, {
                valueField: 'pvv1_total',
                name: 'PVV1'
            },
            {
                valueField: 'pvsb_obtain_total',
                name: 'PVSB đạt'
            },
            {
                valueField: 'pvv1_obtain_total',
                name: 'PVV1 đạt'
            },
        ],
        title: 'Biểu đồ hiệu quả tuyển dụng theo nhân sự',
        legend: {
            verticalAlignment: 'bottom',
            horizontalAlignment: 'center',
        },
        export: {
            enabled: true,
        },
        onPointClick(e) {
            e.target.select();
        },
    }).dxChart('instance');

    pivotGrid = $('#pivotgrid').dxPivotGrid({
        allowSortingBySummary: true,
        allowFiltering: true,
        showBorders: true,
        rowHeaderLayout: "rows",
        // showColumnGrandTotals: true,
        // showRowGrandTotals: true,
        showRowTotals: true,
        showColumnTotals: true,
        // PivotGridTotalsDisplayMode: "rows"
        height: 700,

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
                    caption: 'Nhân sự',
                    dataField: 'user',
                    width: 150,
                    area: 'column',
                    groupInterval: 'user',

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
                    caption: 'Vị trí ứng tuyển',
                    dataField: 'position',
                    area: 'row',
                    width: 200,
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                    sortBySummaryField: 'position',
                },
                {
                    caption: 'SLHS',
                    dataField: 'slhs_total',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                      customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },
                {
                    caption: 'PVSB',
                    dataField: 'pvsb_total',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                    customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },
                {
                    caption: 'PVV1',
                    dataField: 'pvv1_total',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                     customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },
                {
                    caption: 'PVSB đạt',
                    dataField: 'pvsb_obtain_total',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                    customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },
                {
                    caption: 'PVV1 đạt',
                    dataField: 'pvv1_obtain_total',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                     customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
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
    // GetDataChart();


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

function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/recruitment/report/recruitment/recruitment-kpi',
        dataType: 'json',
        //sync: false,
        data: {
           _token: "{{ csrf_token() }}",
            startDate: $("input[name='date_from']").val(),
            endDate: $("input[name='date_to']").val(),
            positionIds: $("select[name='position_id[]']").val(),
            departmentId: $("select[name='department_id']").val(),
            userIds: $("select[name='user_id[]']").val(),
        },
        success: function (resp) {
            pivotGrid.option("dataSource.store", resp.data);
            pivotGrid.getDataSource().reload();
            const dataChart = resp.data;
            dataChart.forEach(item => {
                for (let key in item) {
                    if (key.includes('total')) {
                        item[key] = parseInt(item[key], 10); // chuyển đổi chuỗi sang số
                    }
                }
            });
            pivotGridChart.option("dataSource", dataChart);
            pivotGridChart.refresh();
            //datas = resp.data;
        }
    });
}

function GetDataChart() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            _token: "{{ csrf_token() }}",
            startDate: $("input[name='date_from']").val(),
            endDate: $("input[name='date_to']").val(),
            positionIds: $("select[name='position_id[]']").val(),
            userIds: $("select[name='user_id[]']").val(),
        },
        url: '/api/recruitment/report/recruitment/recruitment-kpi-chart',
        dataType: 'json',
        //async: false,
        success: function (resp) {
            const dataChart = resp.data;
            dataChart.forEach(item => {
                for (let key in item) {
                    if (key.includes('total')) {
                        item[key] = parseInt(item[key], 10); // chuyển đổi chuỗi sang số
                    }
                }
            });
            pivotGridChart.option("dataSource", dataChart);
            pivotGridChart.refresh();
            //datas = resp.data;
        }
    });
}

$('#filter').on('click', function () {
    GetData();
    //GetDataChart();
});

$('#department').on('change', function () {
    var departmentId = $(this).val();
    $.ajax({
        url: '/api/recruitment/user/get-by-department',
        type: 'GET',
        data: {
            departmentId: departmentId,
        },
        dataType: 'json',
        success: function (resp) {
            $('#user').empty();
            $('#user').append('<option value="">Tất cả</option>');
            $.each(resp.data, function (key, value) {
                $('#user').append('<option value="' + value.id + '">' + value.name + '</option>');
            });
        }
    });
});

$('#clearPositionFilter').on('click', function (e) {
    e.preventDefault();
    $('#positionSelect').val(null).trigger('change');
});

$('#clearUserFilter').on('click', function (e) {
    e.preventDefault();
    $('#userSelect').val(null).trigger('change');
});

clearPositionFilter

// const dataSource = [{
//         state: 'Illinois',
//         year2016: 803,
//         year2017: 823,
//         year2018: 863,
//         year2019: 100,
//         year2020: 100,
//     }, {
//         state: 'Indiana',
//         year2016: 316,
//         year2017: 332,
//         year2018: 332,
//         year2019: 100,
//         year2020: 100,
//     }, {
//         state: 'Michigan',
//         year2016: 452,
//         year2017: 459,
//         year2018: 470,
//         year2019: 100,
//         year2020: 100,
//     }, {
//         state: 'Ohio',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'a',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'b',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'c',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'd',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'e',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'f',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'g',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'h',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'y',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'k',
//         year2016: 621,
//         year2017: 642,
//         year2018: 675,
//         year2019: 100,
//         year2020: 100,
//     },
//     {
//         state: 'Wisconsin',
//         year2016: 290,
//         year2017: 294,
//         year2018: 301,
//         year2019: 100,
//         year2020: 100,
//     }
// ];
