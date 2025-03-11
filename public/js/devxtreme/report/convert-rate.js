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

    pivotGridChart = $("#pivotgrid-chart")
        .dxChart({
            dataSource: [],
            loadPanel: {
                enabled: true,
            },
            series: {
                argumentField: "data",
                valueField: "value",
                name: "Biểu đồ tỷ lệ chuyển đổi ứng viên",
                type: "bar",
                color: "#e63946",
                label: {
                    visible: true,
                    format: {
                        type: "fixedPoint",
                        precision: 0,
                    },
                    customizeText() {
                        return `${this.valueText}`;
                    },
                },
            },
            legend: {
                verticalAlignment: "bottom",
                horizontalAlignment: "center",
                visible: false,
            },

            export: {
                enabled: true,
            },
            title: "Biểu đồ tỷ lệ chuyển đổi ứng viên",
        })
        .dxChart("instance");

    pivotGrid = $("#pivotgrid")
        .dxPivotGrid({
            allowSortingBySummary: true,
            allowFiltering: true,
            showBorders: true,
            showColumnGrandTotals: true,
            showRowGrandTotals: true,
            showRowTotals: true,
            showColumnTotals: true,
            height: 700,
            loadPanel: {
                enabled: true,
            },
            texts: {
                grandTotal: "Tổng",
            },
            export: {
                enabled: true,
            },
            showTotalsPrior: {
                rows: true,
                columns: true,
            },
            // scrolling: {
            //     mode: "virtual",
            // },
            // fieldPanel: {
            //     showColumnFields: true,
            //     showDataFields: true,
            //     showFilterFields: true,
            //     showRowFields: true,
            //     allowFieldDragging: true,
            //     visible: true,
            // },
            dataSource: {
                fields: [
                    {
                        caption: "Vị trí",
                        dataField: "position_name",
                        width: 150,
                        area: "row",
                        sortBySummaryField: "position_name",
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
                        caption: "Hồ sơ ứng tuyển",
                        dataField: "total_candidates",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Sơ lược hồ sơ",
                        dataField: "slhs",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Đạt sơ lược hồ sơ",
                        dataField: "slhs_obtain",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Phỏng vấn sơ bộ",
                        dataField: "pvsb",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Đạt phỏng vấn sơ bộ",
                        dataField: "pvsb_obtain",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Phỏng vấn vòng 1",
                        dataField: "pvv1",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Đạt phỏng vấn vòng 1",
                        dataField: "pvv1_obtain",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Phỏng vấn vòng 2",
                        dataField: "pvv2",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Đạt phỏng vấn vòng 2",
                        dataField: "pvv2_obtain",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Phỏng vấn vòng 3",
                        dataField: "pvv3",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Đạt phỏng vấn vòng 3",
                        dataField: "pvv3_obtain",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    // {
                    //     caption: "Đạt thi tuyển",
                    //     dataField: "exam_obtain",
                    //     dataType: "number",
                    //     area: "data",
                    //     summaryType: "sum",
                    //     cssClass: "centered-cell",
                    //     customizeText: function (data) {
                    //         return data.value == 0 || data.value == undefined
                    //             ? ""
                    //             : String(data.value);
                    //     },
                    // },
                    {
                        caption: "Nhận việc",
                        dataField: "take_job",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                    {
                        caption: "Không nhận việc",
                        dataField: "reject_take_job",
                        dataType: "number",
                        area: "data",
                        summaryType: "sum",
                        cssClass: "centered-cell",
                        customizeText: function (data) {
                            return data.value == 0 || data.value == undefined
                                ? ""
                                : String(data.value);
                        },
                    },
                ],
                store: [],
            },
        })
        .dxPivotGrid("instance");

    // pivotGrid.bindChart(pivotGridChart, {
    //     dataFieldsDisplayMode: 'splitPanes',
    //     alternateDataFields: false,
    // });
    GetData();
    GetDataChart();


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
        url: '/api/recruitment/report/recruitment/convert-rate',
        dataType: 'json',
            data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            position_id: $("select[name='position_id[]']").val(),
        },
        success: function (resp) {
            pivotGrid.option("dataSource.store", resp.data);
            pivotGrid.getDataSource().reload();
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
        url: '/api/recruitment/report/recruitment/convert-rate-chart',
        dataType: 'json',
        data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            position_id: $("select[name='position_id[]']").val(),
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
    GetDataChart()
    GetData()
});
