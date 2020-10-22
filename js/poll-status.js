var FINISHED_FLAG = "FINISHED";
var fetch_interval = 2000;

function fetchData(){
    $.ajax({
        url: '/fetch-progress',
        type: 'GET',
        contentType: "application/json; charset=UTF-8",
        success: function(response){
            var jsonObj = JSON.parse(response);
            console.log(jsonObj);
            var completed = jsonObj.completed;
            var submitted = jsonObj.submitted;
            var processing = jsonObj.processing;
            var total = completed + submitted + processing;
            
            $('#checking-col').css("display", "none");
            if (submitted == 0 && processing == 0) {
                $('#processed-col').css("display", "block");
                $('#processing-col').css("display", "none");
            }
            else {

                var completedPercent = Math.trunc((completed / total) * 100);
                var processingPercent = Math.trunc((processing / total) * 100);

                $('#processing-col').css("display", "block");
                $("#completed-progress-bar").css("width", completedPercent.toString() + "%");
                $("#processing-progress-bar").css("width", processingPercent.toString() + "%");

                $("#completed-progress-bar").html(completedPercent.toString() + "%");
                $("#processing-progress-bar").html(processingPercent.toString() + "%");
                $("#processing").html(processing);
                $("#submitted").html(submitted);
                $("#completed").html(completed);
                $("#total").html(total);
            }

            // $('#checking-col').css("display", "none");
            // if (response.localeCompare(FINISHED_FLAG) == 0) {
            //     $('#processed-col').css("display", "block");
            //     $('#processing-col').css("display", "none");
            // }
            // else {
            //     $('#processing-col').css("display", "block");
            //     var jsonObj = JSON.parse(response);
            //     $("#status-progress-bar").css("width", jsonObj.progress);
            //     $("#current-count").html(jsonObj.current);
            //     $("#total-count").html(jsonObj.total);
            // }
        }
    });
}

$('document').ready(setInterval(fetchData, fetch_interval));

