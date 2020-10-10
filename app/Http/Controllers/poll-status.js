var FINISHED_FLAG = "FINISHED";
var fetch_interval = 2000;

function fetchData(){
    $.ajax({
        url: '/fetch-status',
        type: 'GET',
        contentType: "application/json; charset=UTF-8",
        success: function(response){
            $('#checking-col').css("display", "none");
            if (response.localeCompare(FINISHED_FLAG) == 0) {
                $('#processed-col').css("display", "block");
                $('#processing-col').css("display", "none");
            }
            else {
                $('#processing-col').css("display", "block");
                var jsonObj = JSON.parse(response);
                $("#status-progress-bar").css("width", jsonObj.progress);
                $("#current-count").html(jsonObj.current);
                $("#total-count").html(jsonObj.total);
            }
        }
    });
}


$('document').ready(setInterval(fetchData, fetch_interval));