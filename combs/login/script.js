
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

let username;
let password;

/*Event listeners*/
$('#login-form').on('submit', function(e){
    e.preventDefault();
});
$('#login-btn').on('click', function(){
    $('#login_status').text('Logging in...');
    username = $('#username').val();
    password = $('#password').val();
    var formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);
    var url = 'login.php';
    ajaxpost(url, loggedin, formData);
});

/* Controller functions */ 

/* Action functions */
function loggedin(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        $('#login_status').text('Success');
        window.location.href = data['redirect'];
    } else {
        $('#login_status').text(data['reason']);
    }
}