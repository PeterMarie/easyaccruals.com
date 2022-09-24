
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

let current_user;
let current_user_id;
let confirm_url;
let confirm_function;
let new_cashier_formdata = new FormData();


$('#new_user_form').on('submit', function(e){
    e.preventDefault();
});
$('#new_user_btn').on('click', function(){
    var firstame = $('#new_firstname').val();
    var lastname = $('#new_lastname').val();
    var username = $('#new_username').val();
    var phone = $('#new_phone').val();
    var email = $('#new_email').val();
    var password = $('#new_password').val();
    var password2 = $('#confirm_password').val();
    if((firstname !== "") && (lastname !== "") && (username !== "") && (phone !== "") && (password !== "") && (password2 !== "")){
        if(password == password2){
            new_cashier_formdata.append('firstname', firstname);
            new_cashier_formdata.append('lastname', lastname);
            new_cashier_formdata.append('username', username);
            new_cashier_formdata.append('email', email);
            new_cashier_formdata.append('phone', phone);
            new_cashier_formdata.append('password', password);

            /*Confirm Box Script
            ***************************
            */
            let confirm_btn_1 = document.getElementById('confirm_btn_1');
            let confirm_btn_2 = document.getElementById('confirm_btn_2');
            $('.confirm-header').text("Confirm New Cashier Details");
            $('.confirm-text-1').html("<table><tr><td>Name</td><td>" + firstname + " " + lastname + "</td></tr><tr><td>Username</td><td>" + username + "</td></tr><tr><td>Phone</td><td>" + phone + "</td></tr><tr><td>Email</td><td>" + email + "</td></tr></table>");
            $('.confirm-text-2').html("");
            $('.confirm-text-3').html("");
            $(confirm_btn_1).show();
            $(confirm_btn_2).show();
            $(confirm_btn_1).text('Confirm');
            $(confirm_btn_2).text('Cancel');
            confirm_btn_1.setAttribute('onclick', 'confirm_new_cashier()');
            confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
            //confirm_url;
            //confirm_function;
            $("#pageCover").attr('onclick', 'cancel_activity()');
            $('#pageCover').show();
            $('.confirm-box').show();
            /*Confirm Box Script
            ***************************
            */
        } else {
            //Password fields don't match
            $('#form_status').text("Passwords do not match!");
        }
    } else {
        //Required fields not filled
        $('#form_status').text("Please fill all required fields!");
    }
            
});

function suspend(user){
    current_user_id = user;
    var username = ".username-" + user;
    current_user = $(username).text();
    /*Confirm Box Script
    ***************************
    */
    let confirm_btn_1 = document.getElementById('confirm_btn_1');
    let confirm_btn_2 = document.getElementById('confirm_btn_2');
    $('.confirm-header').text("Are You Sure?");
    $('.confirm-text-1').html("Suspend <strong>" + current_user + "</strong> from your business?");
    $('.confirm-text-2').html("");
    $('.confirm-text-3').html("");
    $(confirm_btn_1).show();
    $(confirm_btn_2).show();
    $(confirm_btn_1).text('Confirm Suspension');
    confirm_btn_1.setAttribute('style', 'background-color: darkred; color: white;');
    $(confirm_btn_2).text('Cancel');
    confirm_btn_2.setAttribute('style', 'background-color: darkgreen; color: white;');
    confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
    confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
    confirm_url = "userobj.php?act=blk&u=" + user;
    confirm_function = blocked;
    $("#pageCover").attr('onclick', 'cancel_activity()');
    $('#pageCover').show();
    $('.confirm-box').show();
    /*Confirm Box Script
    ***************************
    */

}
function unsuspend(user){
    //Show confirm box
    current_user_id = user;
    var username = ".username-" + user;
    current_user = $(username).text();
    
    /*Confirm Box Script
    ***************************
    */
    let confirm_btn_1 = document.getElementById('confirm_btn_1');
    let confirm_btn_2 = document.getElementById('confirm_btn_2');
    $('.confirm-header').text("Are You Sure?");
    $('.confirm-text-1').html("Restore <strong>" + current_user + "'s</strong> permissions to your business?");
    $('.confirm-text-2').html("");
    $('.confirm-text-3').html("");
    $(confirm_btn_1).show();
    $(confirm_btn_2).show();
    $(confirm_btn_1).text('End Suspension?');
    confirm_btn_1.setAttribute('style', 'background-color: darkgreen; color: white;');
    $(confirm_btn_2).text('Cancel');
    confirm_btn_2.setAttribute('style', 'background-color: darkred; color: white;');
    confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
    confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
    confirm_url = "userobj.php?act=unblk&u=" + user;
    confirm_function = unblocked;
    $("#pageCover").attr('onclick', 'cancel_activity()');
    $('#pageCover').show();
    $('.confirm-box').show();
    /*Confirm Box Script
    ***************************
    */

}
function remove(user){
    //Show confirm box
    current_user_id = user;
    var username = ".username-" + user;
    current_user = $(username).text();
    
    /*Confirm Box Script
    ***************************
    */
    let confirm_btn_1 = document.getElementById('confirm_btn_1');
    let confirm_btn_2 = document.getElementById('confirm_btn_2');
    $('.confirm-header').text("Are You Sure?");
    $('.confirm-text-1').html("Remove <strong>" + current_user + "</strong> from your business?");
    $('.confirm-text-2').html("<span style=\"color: red;\"> This is permanent and cannot be reversed!</span>");
    $('.confirm-text-3').html("");
    $(confirm_btn_1).show();
    $(confirm_btn_2).show();
    $(confirm_btn_1).text('Remove?');
    confirm_btn_1.setAttribute('style', 'background-color: darkred; color: white;');
    $(confirm_btn_2).text('Cancel');
    confirm_btn_2.setAttribute('style', 'background-color: darkgreen; color: white;');
    confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
    confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
    confirm_url = "userobj.php?act=dlt&u=" + user;
    confirm_function = deleted;
    $("#pageCover").attr('onclick', 'cancel_activity()');
    $('#pageCover').show();
    $('.confirm-box').show();
    /*Confirm Box Script
    ***************************
    */

}
function make_admin(user, new_stat){
    //Show confirm box
    current_user_id = user;
    var username = ".username-" + user;
    current_user = $(username).text();
    
    /*Confirm Box Script
    ***************************
    */
    let confirm_btn_1 = document.getElementById('confirm_btn_1');
    let confirm_btn_2 = document.getElementById('confirm_btn_2');
    $('.confirm-header').text("Are You Sure?");
    if(new_stat == 1){
        $('.confirm-text-1').html("Grant <strong>" + current_user + "</strong> administrative permissions ?");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
       // $('.confirm-text-2').html("<span class=\"\"><small> Admins can view sales records, modify the items for sale, add cashiers and suspend/unsuspend cashiers</small></span>");
      //  $('.confirm-text-3').html("<span><small>Admins, however, cannot create other admins. Only YOU, the super-admin, can do that.</small></span>");
        $(confirm_btn_1).text('Confirm');
        confirm_btn_1.setAttribute('style', 'background-color: darkgreen; color: white;');
        confirm_btn_2.setAttribute('style', 'background-color: darkred; color: white;');
        confirm_url = "userobj.php?act=mk_admin&stat=1&u=" + user;
        confirm_function = new_admin;
    } else {
        $('.confirm-text-1').html("Strip <span>" + current_user + "of all ADMIN privileges?</span> ?");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).text('Confirm');
        confirm_btn_1.setAttribute('style', 'background-color: darkred; color: white;');
        confirm_btn_2.setAttribute('style', 'background-color: darkgreen; color: white;');
        confirm_url = "userobj.php?act=mk_admin&stat=0&u=" + user;
        confirm_function = dlt_admin;
    }
    $(confirm_btn_1).show();
    $(confirm_btn_2).show();
    $(confirm_btn_2).text('Cancel');
    confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
    confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
    $("#pageCover").attr('onclick', 'cancel_activity()');
    $('#pageCover').show();
    $('.confirm-box').show();
    /*Confirm Box Script
    ***************************
    */
}

function check_password(psswrd, psswrd2){
    if(psswrd == psswrd2){
        return 1;
    } else {
        return 0;
    }
}

/* Controller functions */ 
function confirm_new_cashier(){
    ajaxpost("userobj.php", new_cashier, new_cashier_formdata);
}

function confirm_activity(){
    ajax(confirm_url, confirm_function);
}

/* Action functions */
function blocked(response){
    if(response.responseText == "success"){
        let susp_btn = '#susp_btn_' + current_user_id;
        $(susp_btn).attr('onclick', 'unsuspend(' + current_user_id + ')');
        $(susp_btn).attr('class', 'inv-susp-btn');
        $(susp_btn).text('End Suspension');

        /*Confirm Box Script
        ***************************
        */
        let confirm_btn_1 = document.getElementById('confirm_btn_1');
        let confirm_btn_2 = document.getElementById('confirm_btn_2');
        $('.confirm-header').text("");
        $('.confirm-text-1').html("<span>" + current_user + "</span> suspended successfully.");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).hide();
        $(confirm_btn_2).show();
        $(confirm_btn_2).text('Finish');
        //confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
        confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
        //confirm_url;
        //confirm_function;
        $('#pageCover').show();
        $('.confirm-box').show();
        /*Confirm Box Script
        ***************************
        */

    }else{

    }
}

function unblocked(response){
    if(response.responseText == "success"){
        let susp_btn = '#susp_btn_' + current_user_id;
        $(susp_btn).attr('onclick', 'suspend(' + current_user_id + ')');
        $(susp_btn).attr('class', 'susp-btn');
        $(susp_btn).text('Suspend');

        /*Confirm Box Script
        ***************************
        */
        let confirm_btn_1 = document.getElementById('confirm_btn_1');
        let confirm_btn_2 = document.getElementById('confirm_btn_2');
        $('.confirm-header').text("");
        $('.confirm-text-1').html("<span>" + current_user + "'s</span> suspension has been ended successfully.");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).hide();
        $(confirm_btn_2).show();
        $(confirm_btn_2).text('Finish');
        //confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
        confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
        //confirm_url;
        //confirm_function;
        $('#pageCover').show();
        $('.confirm-box').show();
        /*Confirm Box Script
        ***************************
        */

    }else{

    }
}

function deleted(response){
    if(response.responseText == "success"){
        let user_row = '#user_' + current_user_id + "row";
        $(user_row).hide();

        /*Confirm Box Script
        ***************************
        */
        let confirm_btn_1 = document.getElementById('confirm_btn_1');
        let confirm_btn_2 = document.getElementById('confirm_btn_2');
        $('.confirm-header').text("");
        $('.confirm-text-1').html("<span>" + current_user + "</span> removed successfully.");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).hide();
        $(confirm_btn_2).show();
        $(confirm_btn_2).text('Finish');
        //confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
        confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
        //confirm_url;
        //confirm_function;
        $('#pageCover').show();
        $('.confirm-box').show();
        /*Confirm Box Script
        ***************************
        */

    }else{

    }
}

function new_admin(response){
    if(response.responseText == "success"){
        let admin_btn = '#admin_btn_' + current_user_id;
        $(admin_btn).text("Delete Admin");
        $(admin_btn).attr("onclick", "make_admin(" + current_user_id + ", 0)");
        $(admin_btn).attr('class', 'rep-aside-btn');
        let susp_btn = "#susp_btn_" + current_user_id;
        let dlt_btn = "#dlt_btn_" + current_user_id;
        $(susp_btn).hide();
        $(dlt_btn).hide();

        /*Confirm Box Script
        ***************************
        */
        let confirm_btn_1 = document.getElementById('confirm_btn_1');
        let confirm_btn_2 = document.getElementById('confirm_btn_2');
        $('.confirm-header').text("");
        $('.confirm-text-1').html("<span>" + current_user + "</span> is now an Admin.");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).hide();
        $(confirm_btn_2).show();
        $(confirm_btn_2).text('Finish');
        //confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
        confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
        //confirm_url;
        //confirm_function;
        $('#pageCover').show();
        $('.confirm-box').show();
        /*Confirm Box Script
        ***************************
        */

    }else{

    }
}

function dlt_admin(response){
    if(response.responseText == "success"){
        let admin_btn = '#admin_btn_' + current_user_id;
        $(admin_btn).text("Make Admin");
        $(admin_btn).attr("onclick", "make_admin(" + current_user_id + ", 1)");
        $(admin_btn).attr('class', 'item-select-btn');
        let susp_btn_cell = "#susp_btn_cell_" + current_user_id;
        let susp_btn = "<button class=\"susp-btn\" id=\"susp_btn_" + current_user_id + "\" onclick=\"suspend(" + current_user_id + ")\" >Suspend</button>";
        $(susp_btn_cell).html(susp_btn);
        let dlt_btn_cell = "#dlt_btn_cell_" + current_user_id;
        let dlt_btn = "<button class=\"dlt-btn\" id=\"dlt_btn_" + current_user_id + "\" onclick=\"remove(" + current_user_id + ")\" >Remove</button>";
        $(dlt_btn_cell).html(dlt_btn);

        /*Confirm Box Script
        ***************************
        */
        let confirm_btn_1 = document.getElementById('confirm_btn_1');
        let confirm_btn_2 = document.getElementById('confirm_btn_2');
        $('.confirm-header').text("");
        $('.confirm-text-1').html("<span>" + current_user + "</span> has been removed as an Admin successfully.");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).hide();
        $(confirm_btn_2).show();
        $(confirm_btn_2).text('Finish');
        //confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
        confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
        //confirm_url;
        //confirm_function;
        $('#pageCover').show();
        $('.confirm-box').show();
        /*Confirm Box Script
        ***************************
        */

    }else{

    }
}

function new_cashier(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        //Add new roll to cashiers table
        var new_roll = document.createElement("tr");
        $(new_roll).attr("id", "user_" + data['new_id'] + "row");
        if(data['admin_level'] < 2){
            var new_roll_html = "<td><span class=\"username-" + data['new_id'] + "\">" + $('#new_name').val() + "</span></td>";
            new_roll_html += "<td>Never</td><td id=\"susp_btn_cell_" + data['new_id'] + "\"><button id=\"susp_btn_" + data['new_id'] + "\" onclick=\"suspend(" + data['new_id'] + ")\" >Suspend</button></td>";
        }else{
            var new_roll_html = "<td><span class=\"username-" + data['new_id'] + "\">" + $('#new_name').val() + "</span></td>";
            new_roll_html += "<td>Never</td><td id=\"susp_btn_cell_" + data['new_id'] + "\"><button id=\"susp_btn_" + data['new_id'] + "\" onclick=\"suspend(" + data['new_id'] + ")\" >Suspend</button></td>";
            new_roll_html += "<td id=\"dlt_btn_cell_" + data['new_id'] + "\"><button id=\"dlt_btn_" + data['new_id'] + "\" onclick=\"remove(" + data['new_id'] + ")\">Remove</button></td>";
            new_roll_html += "<td><button id=\"admin_btn_" + data['new_id'] + "\" onclick=\"make_admin(" + data['new_id'] + ", 1)\">Make Admin</button></td>";
        }
        $(new_roll).html(new_roll_html);
        $('#cashiers_table').append(new_roll);

        /*Confirm Box Script
        ***************************
        */
        let confirm_btn_1 = document.getElementById('confirm_btn_1');
        let confirm_btn_2 = document.getElementById('confirm_btn_2');
        $('.confirm-header').text("");
        $('.confirm-text-1').html("<span>" + $('#new_name').val() + "</span> has been added successfully.");
        $('.confirm-text-2').html("");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).hide();
        $(confirm_btn_2).show();
        $(confirm_btn_2).text('Finish');
        //confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
        confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
        //confirm_url;
        //confirm_function;
        $('#pageCover').show();
        $('.confirm-box').show();
        /*Confirm Box Script
        ***************************
        */

        //Empty new_cashiers_form
        $('#new_name').val("");
        $('#new_username').val("");
        $('#new_phone').val("");
        $('#new_email').val("");
        $('#new_password').val("");
        $('#confirm_password').val("");

    }else{
        /*Confirm Box Script
        ***************************
        */
        let confirm_btn_1 = document.getElementById('confirm_btn_1');
        let confirm_btn_2 = document.getElementById('confirm_btn_2');
        $('.confirm-header').text("Error");
        $('.confirm-text-1').text(data['error']);
        $('.confirm-text-2').html("Please try again");
        $('.confirm-text-3').html("");
        $(confirm_btn_1).hide();
        $(confirm_btn_2).show();
        $(confirm_btn_2).text('Dismiss');
        //confirm_btn_1.setAttribute('onclick', 'confirm_activity()');
        confirm_btn_2.setAttribute('onclick', 'cancel_activity()');
        //confirm_url;
        //confirm_function;
        $('#pageCover').show();
        $('.confirm-box').show();
        /*Confirm Box Script
        ***************************
        */


    }
    
}