
        function ajax(url, cFunction) {
            if (window.XMLHttpRequest) {
                // code for modern browsers
                xhttp = new XMLHttpRequest();
            } else {
                // code for old IE browsers
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                cFunction(this);
                }
            };
            xhttp.open("GET", url, true);
            $('.confirm-box-item').text(url);
           /* xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");*/
            xhttp.send();
        }

        function ajaxpost(url, cFunction, formData){
            var xhr = new XMLHttpRequest();
            xhr.open("POST", url);
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    cFunction(this);
                }
            };
            xhr.send(formData);
        }

/*Event listeners*/
$('#change_period_btn').on('click', function(){
    $('#l_rep_period_links').animate({height: "toggle", opacity: 1}, {duration: 600});
});
$('#genrepform').on('submit', function(e){
    e.preventDefault();
});
$('#genrepbtn').on('click', function(){
    if(($('#start_date').val() !== "") && ($('#end_date').val() !== "")){
        //replace current report with loading sign
        putloadingonreportsection();

        var url = "genrep.php";
        var formdata = new FormData();
        formdata.append('start_date', $('#start_date').val());
        formdata.append('end_date', $('#end_date').val());
        ajaxpost(url, genned_report, formdata);
    }
});

function putloadingonreportsection(){
    var span = document.createElement('span');
    $(span).text('Loading...');
    $('#repsec').append(span);
    $('#repsec').html(span);
    return;
}

/* Controller functions */ 
function gen_rep(period){
    //replace current report with loading sign
    putloadingonreportsection();

    var url = "genrep.php?simprd=" + period;
    ajax(url, genned_report);
}

/* Action functions */
function genned_report(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        $('#repsec').html(data['report']);
        $('#mob_table_header').html(data['header']);
        if($('#change_period_btn').is(':visible')){
            $('#change_period_btn').click();
        }
        return;
    } else {

    }
}