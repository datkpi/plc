var data;
$(() => {
    const dayOfWeekNames = ['CN', 'Th 2', 'Th 3', 'Th 4', 'Th 6', 'Th 7', 'Sat'];

    var currentDate = new Date();
    currentDate.setHours(0, 0, 0, 0); // Đặt thời gian về 00:00:00.000

    var eventTypes = ["Type 1", "Type 2", "Type 3"];
    var customEventTypes = [];
    $("#scheduler")
        .dxScheduler({
            timeZone: "Asia/Ho_Chi_Minh",
            height: 600,
            dataSource: data,
            showAllDayPanel: false,
            views: ["day", "week", "month"],
            currentView: "month",
            currentDate: new Date(),
            editing: {
                allowAdding: false,
                allowDeleting: false,
                allowUpdating: false,
                allowResizing: false,
                allowDragging: false,
            },
            // resources: [{
            //     fieldExpr: "now",
            //     dataSource: [{
            //             id: 1,
            //             text: "Today",
            //             color: "#cd73e1",
            //         },
            //         {
            //             id: 0,
            //             text: "Other",
            //             color: "#e9d2fd",
            //         },
            //     ],
            // }, ],
            onAppointmentRendered: function (args) {
                if (args.appointmentData.now === 1) {
                    args.appointmentElement.css("backgroundColor", "#cd73e1");
                    args.appointmentElement.css("color", "white");
                    // args.appointmentElement.css("border-radius", "7px");
                } else {
                    args.appointmentElement.css("backgroundColor", "#e9d2fd");
                    args.appointmentElement.css("color", "black");
                    // args.appointmentElement.css("border-radius", "7px");
                }
            },
            onCellClick: function (e) {
                $("#myModal").modal("show");
                // var currentTime = new Date().getTime();
                // if (currentTime - scheduler.previousClickTime < 300) {
                //     // Double click action
                //     if (e.cellData.startDate) {
                //         //var eventText = e.cellData.text;
                //         var eventStartDate = e.cellData.startDate;
                //         var eventEndDate = e.cellData.endDate;
                //         var eventStartDate = new Date(eventStartDate).toLocaleString();
                //         var eventEndDate = new Date(eventEndDate).toLocaleString();
                //         window.location.href = "/interview-schedule/create?start=" + encodeURIComponent(eventStartDate) + "&end=" + encodeURIComponent(eventEndDate);
                //     }
                // } else {

                // }
                scheduler.previousClickTime = currentTime;
            },

            onAppointmentClick: function (e) {
                var currentTime = new Date().getTime();
                if (currentTime - scheduler.previousClickTime < 300) {
                    var candidateId = e.appointmentData.candidate_id;
                    window.location.href = "/recruitment/candidate/edit/" + candidateId;
                } else {}
                scheduler.previousClickTime = currentTime;
            },
            onAppointmentFormOpening: function (e) {
                e.cancel = true;
                var candidateId = e.appointmentData.candidate_id; // Lấy ID của sự kiện (hoặc một trường dữ liệu khác nếu cần)

                // Chuyển hướng đến trang mới với ID của sự kiện
                window.location.href = "/recruitment/candidate/edit/" + candidateId;
                //     var form = e.form;

                //     form.option('items', [{
                //         label: {
                //             text: 'Tên lịch trình'
                //         },
                //         editorType: 'dxTextBox',
                //         dataField: 'name' // Giả sử 'name' là trường tên của Schedule
                //     }, {
                //         label: {
                //             text: 'Thời gian bắt đầu'
                //         },
                //         editorType: 'dxDateBox',
                //         dataField: 'startDate' // 'startDate' là trường mặc định cho ngày bắt đầu trong Scheduler
                //     }, {
                //         label: {
                //             text: 'Thời gian kết thúc'
                //         },
                //         editorType: 'dxDateBox',
                //         dataField: 'endDate' // 'endDate' là trường mặc định cho ngày kết thúc trong Scheduler
                //     }, {
                //         label: {
                //             text: 'Ứng viên'
                //         },
                //         editorType: 'dxTagBox', // Sử dụng TagBox để hiển thị danh sách các ứng viên
                //         dataField: 'candidates', // 'candidates' là trường tùy chỉnh, bạn cần thêm nó vào nguồn dữ liệu của mình
                //         editorOptions: {
                //             displayExpr: function (candidate) {
                //                 interview_time = candidate.interview_date+candidate.stage;
                //                 return candidate.name + " (" + interview_time + ")"; // Hiển thị tên và giờ phỏng vấn
                //             },
                //             valueExpr: "id", // Giả sử mỗi ứng viên có một trường 'id' duy nhất
                //             dataSource: e.appointmentData.candidates
                //         }
                //     },

                // ]);
            },

            onAppointmentAdded(e) {
                showToast("Added", e.appointmentData.text, "success");
            },
            onAppointmentUpdated(e) {
                showToast("Updated", e.appointmentData.text, "info");
            },
            onAppointmentDeleted(e) {
                showToast("Deleted", e.appointmentData.text, "warning");
            },
            height: 600,
        })
        .dxScheduler("instance");
});

GetData();

function GetData() {

    $.ajax({
        url: '/api/recruitment/interview-schedule/get-data',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (resp) {
            data = resp.data;
        }
    });

}

$("#create_schedule").click(function () {
    var candidateId = $("select[name='candidate_id']").val();

    // Kiểm tra xem có giá trị đã được chọn hay không
    if (candidateId) {
        // Thực hiện chuyển hướng đến liên kết khác với giá trị candidate_id
        window.location.href = "/recruitment/candidate/edit/" + candidateId + '?is_create=true';
    }
});



const priorityData = [{
    id: 1,
    color: '#92A8D1',
}, {
    id: 2,
    color: '#F7CAC9',
}, ];

const nameData = [{
    id: 1,
    name: 'oke',
}, {
    id: 2,
    name: 'oke2',
}, ];

const typeData = [{
    id: 1,
    color: '#b6d623',
}, {
    id: 2,
    color: '#FF6F61',
}, ];
