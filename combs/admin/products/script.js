
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

get_items_table();

let cell_id;
let cell_value;
let current_focus_column;
let current_focus_item_id;
let current_focus_header_id;
let current_focus_header_value;

function transform_input(type){
    switch (type) {
        case 'item':
            save_edited_cell();
            break;

        case 'header':
            save_edited_header();
            break;
    
        default:
            break;
    }
}

function cancel_activity(){
    $('#pageCover').hide();
    $('.confirm-box').hide();
}
function close_inventory(){
    $('#pageCover').hide();
    $('.inventory-section').animate({height: "toggle", opacity: 0}, {duration: 400});
    $('.inventory-btn').prop('disabled', true);
    $('.update-inventory-btn').prop('disabled', true);
    $('#new_inventory').val("");
}

$('#new_p_form').on("submit", function(e){
    e.preventDefault();
    new_item();
});
$('#new_h_form').on("submit", function(e){
    e.preventDefault();
    new_header();
});
$('.inventory-btn').on("click", function(){
    /*THis function listens for the click of one of the inventory adjustment buttons
    and displays the resulting result to the user WITHOUT implementing in the database
    */
    let new_quantity;
    let current_quantity_in_stock;
    let value = parseInt($('#new_inventory').val());
    if($('.inventory-current-qty').text() !== "n/a"){
        current_quantity_in_stock = parseInt($('.inventory-current-qty').text());
    } else {
        current_quantity_in_stock = 0;
    }
    switch ($(this).data('effect')) {
        case 'add':
            new_quantity = current_quantity_in_stock + value;
            break;

        case 'substract':
            if((current_quantity_in_stock - value) >= 0){
                new_quantity = current_quantity_in_stock - value;
            } else {
                alert('New value will be less than 0!');
                return;
            }
            break;
            
        case 'set':
            new_quantity = value;
            break;
    
        default:
            break;
    }
    $('.inventory-new-qty').text(parseInt(new_quantity));
    $('.update-inventory-btn').prop("disabled", false);
});

$('.update-inventory-btn').on('click', function(){
    /* Final inventory update */

    /*Confirm Box Script
    ***************************
    */
    let confirm_text = "<table><tr><td>Item</td><td class=\"confirm-item-name\">" + $('.inventory-item').text() + "</td></tr><tr><td>New Quantity</td><td class=\"confirm-item-name\">" + $('.inventory-new-qty').text() + "</td></tr></table>";
    let confirm_btn_1 = document.createElement('button');
    let confirm_btn_2 = document.createElement('button');
    $('.confirm-header').text("Confirm Inventory Update");
    $('.confirm-text-box').html(confirm_text);
    $(confirm_btn_1).text('Confirm').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;'}).on('click', function(){
        let formdata = new FormData();
        formdata.append("act", "updt_inventory");
        formdata.append("item_id", current_focus_item_id);
        formdata.append("new_quantity", $('.inventory-new-qty').text());
        formdata.append("old_quantity", $('.inventory-current-qty').text());
        ajaxpost("itemsobj.php?", updated_inventory, formdata);
    });
    $(confirm_btn_2).text('Cancel').attr({class: 'confirm-btn', style: 'background-color: darkred; color: white; border-color: darkred;'}).on('click', function(){
        $('.confirm-box').hide();
        $('#pageCover').attr({style: 'z-index: 98'}).show();
    });
    $('.confirm-btn-box').html("").append(confirm_btn_1).append(confirm_btn_2);
    $('#pageCover').attr({style: 'z-index: 100'}).on('click', function(){
        $('.confirm-box').hide();
        $('#pageCover').attr({style: 'z-index: 98'}).show();
    }).show();
    $('.confirm-box').attr('style', 'display: flex;');
    /*Confirm Box Script
    ***************************
    */
});

$("#inventory_status").change(function() {
    if(this.checked) {
        $('#new_inventory').prop('disabled', true);
        $('.inventory-btn').prop('disabled', true);
        $('.inventory-current-qty').text('n/a');
        $('.inventory-new-qty').text('n/a');
    } else {
        $('#new_inventory').prop('disabled', false);
        $('.inventory-current-qty').text(cell_value);
        $('.inventory-new-qty').text(0);
        if($('#new_inventory').val() !== ""){
            $('.inventory-btn').prop('disabled', false);
        }
    }
});

function edit_cell(column, item_id){
    current_focus_column = column;
    current_focus_item_id = item_id;
    cell_id = column + "-" + item_id;
    cell_value = $("#" + cell_id).text();
    //$("#" + cell_id).text("Got Here");
    let cell_id_input = "input-" + cell_id;
    $("#" + cell_id).html("<input class=\"editable-cell\" onfocusout=\"transform_input('item')\" type=\"text\" name=\"" + cell_id_input + "\" id=\"" + cell_id_input + "\" autofocus /> ");
    $("#" + cell_id).attr("ondblclick", "");
    $('#' + cell_id_input).focus();
    $('#' + cell_id_input).val(cell_value); 
    $('#' + cell_id_input).keyup(function(e){
        cell_value = $('#' + cell_id_input).val();
        if(e.which == 13){
            save_edited_cell();
        }
    });
    return;
}

function edit_header(header_id){
    current_focus_header_id = header_id;
    current_focus_header_value = $('#items_header_' + header_id).text();
    $('#items_header_' + header_id).attr("ondblclick", "");
    $('#items_header_' + header_id).html("<input class=\"editable-cell\" onfocusout=\"transform_input('header')\" type=\"text\" name=\"header_input_" + header_id + "\" id=\"header_input_" + header_id + "\" autofocus /> ");
    $('#header_input_' + header_id).focus();
    $('#header_input_' + header_id).val(current_focus_header_value);
    $('#header_input_' + header_id).keyup(function(e){
        current_focus_header_value = $('#header_input_' + header_id).val().toUpperCase();
        $('#header_input_' + header_id).val(current_focus_header_value);
        if(e.which == 13){
            save_edited_header();
        }
    });
}

function dlt_item(item_id){
    let item_name = $("#name-" + item_id).text();

    /*Confirm Box Script
    ***************************
    */
    let confirm_text = "<span>Remove<span class=\"confirm-item-name\">" + item_name + "</span> from offered products</span><span style=\"color:darkred; font-size: 90%\">This is permanent and cannot be reversed!</span>";
    let confirm_btn_1 = document.createElement('button');
    let confirm_btn_2 = document.createElement('button');
    $('.confirm-header').text("Are You Sure?");
    $('.confirm-text-box').html(confirm_text);
    $(confirm_btn_1).text('Confirm').attr({class: 'confirm-btn', style: 'background-color: darkred; color: white; border-color: darkred;', onclick: 'confirm_delete(\'item\', ' + item_id + ')'});
    $(confirm_btn_2).text('Cancel').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'cancel_activity()'});
    $('.confirm-btn-box').html("").append(confirm_btn_1).append(confirm_btn_2);
    $('#pageCover').attr('onclick', 'cancel_activity()').show();
    $('.confirm-box').attr('style', 'display: flex;');
    /*Confirm Box Script
    ***************************
    */
}

function dlt_header(header_id){
    current_focus_header_value = $('#items_header_' + header_id).text();
    current_focus_header_id = header_id;

    /*Confirm Box Script
    ***************************
    */
    let confirm_text = "<span>This is permanent and cannot be reversed!</span><span>If you would rather EDIT this header, click Cancel and double click " + current_focus_header_value + "</span>";
    let confirm_btn_1 = document.createElement('button');
    let confirm_btn_2 = document.createElement('button');
    $('.confirm-header').text("Delete Header?");
    $('.confirm-text-box').html(confirm_text);
    $(confirm_btn_1).text('Confirm').attr({class: 'confirm-btn', style: 'background-color: darkred; color: white; border-color: darkred;', onclick: 'confirm_delete(\'header\', ' + header_id + ')'});
    $(confirm_btn_2).text('Cancel').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'cancel_activity()'});
    $('.confirm-btn-box').html("").append(confirm_btn_1).append(confirm_btn_2);
    $('#pageCover').attr('onclick', 'cancel_activity()').show();
    $('.confirm-box').attr('style', 'display: flex;');
    /*Confirm Box Script
    ***************************
    */
}

function updt_inventory(item_id){
    //show inventory section
    //get item name and current qty in stock
    current_focus_item_id = item_id;
    let item_name = $('#name-' + item_id).text();
    let item_current_qty = $("#current_qty_" + item_id).text();
    if(item_current_qty == ""){
        cell_value = item_current_qty = "n/a";
        $('#new_inventory').prop('disabled', true);
        $("#inventory_status").prop('checked', true);
    } else {
        cell_value = item_current_qty;
        $('#new_inventory').prop('disabled', false);
        $("#inventory_status").prop('checked', false);
    }
    $(".inventory-item").text(item_name);
    $(".inventory-current-qty").text(item_current_qty);
    $(".inventory-new-qty").text(item_current_qty);
    $('.inventory-section').animate({height: "toggle", opacity: 1}, {duration: 400}).attr({style: 'display: flex;'});
    $('#pageCover').prop('onclick', null).show();
}


var bind_to = '#new_inventory';
 
// Prevent double-binding.
$(document.body).off('change', bind_to);

// Bind the event to all body descendants matching the "bind_to" selector.
$(document.body).on('change input keydown keyup mousedown mouseup select contextmenu drop', bind_to, function(event) {
    if(($(this).val() == "") || ($(this).val() == 0)){
        $(this).val('');
        $('.inventory-btn').prop('disabled', true);
        return;
    } else {
        $('.inventory-btn').prop('disabled', false);    
    }

    $("#new_inventory").inputFilter(function(value) {
        return /^\d*$/.test(value);    // Allow digits only, using a RegExp
    });
});

(function($) {
    $.fn.inputFilter = function(inputFilter) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      });
    };
  }(jQuery));
  
function conclude_inventory(){
    cancel_activity();
    $('.inventory-section').hide();
}

/* Controller functions */ 
function get_items_table(){
    //Show loading icon
    //Get Table
    ajax("itemsobj.php?act=item_table", show_table);
}

function save_edited_cell(){
    var formdata = new FormData();
    formdata.append("act", "updt");
    formdata.append("val", cell_value);
    formdata.append("col", current_focus_column);
    formdata.append("item_id", current_focus_item_id);
    var url = "itemsobj.php?";
    ajaxpost(url, edited_cell, formdata);
}

function save_edited_header(){
    var formdata = new FormData();
    formdata.append("act", "updt_header");
    formdata.append("val", current_focus_header_value);
    formdata.append("header_id", current_focus_header_id);
    var url = "itemsobj.php?";
    ajaxpost(url, edited_header, formdata);
}

function confirm_delete(type, id){
    var formdata = new FormData();
    switch (type) {
        case "item":
            formdata.append("act", "dlt");
            formdata.append("item_id", id);
            var url= "itemsobj.php";
            ajaxpost(url, deleted, formdata);
            break;
    
        case "header":
            formdata.append("act", "dlt_header");
            formdata.append("header_id", id);
            var url= "itemsobj.php";
            ajaxpost(url, deleted_header, formdata);
            break;
    
        default:
            break;
    }
}

function new_item(){
    if(($('#new_p_name').val() !== "") && ($('#new_p_price').val() !== "")){
        //$('#new_p_btn').disable();
        var formdata = new FormData();
        formdata.append("name", $('#new_p_name').val());
        formdata.append("price", $('#new_p_price').val());
        formdata.append("position", $('#new_p_position').val());
        formdata.append("act", "crt");
        var url = "itemsobj.php";
        ajaxpost(url, new_item_added, formdata);
    }
}
function new_header(){
    if($('#new_h_header').val() !== ""){
        //check position for presence of header
        check_position($('#new_h_position').val());
    }
}
function check_position(position){
    var formdata = new FormData();
    formdata.append("position", position);
    formdata.append("act", "check_position");
    var url = "itemsobj.php";
    ajaxpost(url, position_checked, formdata);
}

/* Action functions */
function show_table(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        $('#items_table_section').html(data['table']);
    }
}
function edited_cell(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        let edited_cell_id = "#" + data['updated_column'] + "-" + data['item_id'];
        $(edited_cell_id).text(data['val']);
        $(edited_cell_id).attr("ondblclick", "edit_cell(\"" +  data['updated_column'] + "\", " + data['item_id'] + ")");
    }
}

function deleted(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        let deleted_row = "#item_" + data['item_id'] + "_row";
        $(deleted_row).hide();

        /*Confirm Box Script
        ***************************
        */
        let confirm_text = "<span>Product deleted successfully</span>";
        let confirm_btn_1 = document.createElement('button');
        $('.confirm-header').text("");
        $('.confirm-text-box').html(confirm_text);
        $(confirm_btn_1).text('Finish').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'cancel_activity()'});
        $('.confirm-btn-box').html("").append(confirm_btn_1);
        $('#pageCover').attr('onclick', 'cancel_activity()').show();
        $('.confirm-box').attr('style', 'display: flex;');
        /*Confirm Box Script
        ***************************
        */
    }
}

function new_item_added(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        //Reload Table
        get_items_table();
        $('#new_p_name').val("");
        $('#new_p_price').val("");
    } else {

    }
}

function edited_header(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        $('#items_header_' + current_focus_header_id).text(current_focus_header_value);
        $('#items_header_' + current_focus_header_id).attr("ondblclick", "edit_header(" + current_focus_header_id + ")")
    }
}

function deleted_header(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        let deleted_row = "#header_" + current_focus_header_id + "_row";
        $(deleted_row).hide();

        /*Confirm Box Script
        ***************************
        */
        let confirm_text = "<span>Header deleted successfully</span>";
        let confirm_btn_1 = document.createElement('button');
        $('.confirm-header').text("");
        $('.confirm-text-box').html(confirm_text);
        $(confirm_btn_1).text('Finish').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'cancel_activity()'});
        $('.confirm-btn-box').html("").append(confirm_btn_1);
        $('#pageCover').attr('onclick', 'cancel_activity()').show();
        $('.confirm-box').attr('style', 'display: flex;');
        /*Confirm Box Script
        ***************************
        */
    }
}

function position_checked(response){
    if(response.responseText == 0){
        var formdata = new FormData();
        formdata.append("header", $('#new_h_header').val());
        formdata.append("position", $('#new_h_position').val());
        formdata.append("act", "ins_header");
        var url = "itemsobj.php";
        ajaxpost(url, new_header_added, formdata);
    } else {
        /*Confirm Box Script
        ***************************
        */
        let confirm_text = "<span>A header has already been placed at the desired position of your products table</span><span style=\"font-size: 80%;\">To edit the header instead, click CANCEL and double-click on the desired header.</span>";
        let confirm_btn_1 = document.createElement('button');
        $('.confirm-header').text("Redundancy Alert!");
        $('.confirm-text-box').html(confirm_text);
        $(confirm_btn_1).text('Understood').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'cancel_activity()'});
        $('.confirm-btn-box').html("").append(confirm_btn_1);
        $('#pageCover').attr('onclick', 'cancel_activity()').show();
        $('.confirm-box').attr('style', 'display: flex;');
        /*Confirm Box Script
        ***************************
        */
    /*Confirm Box Script
    ***************************
    
    let confirm_btn_1 = document.getElementById('confirm_btn_1');
    let confirm_btn_2 = document.getElementById('confirm_btn_2');
    $('.confirm-header').text("Redundancy Alert!");
    $('.confirm-text-1').html("A header has already been placed at the desired position of your products table");
    $('.confirm-text-2').html("");
    //$('.confirm-text-2').html("<small>To edit the current header instead, click CANCEL and double-click on the desired header.</small>");// To insert new header regardless, click CONTINUE.</small>");
    $('.confirm-text-3').html("");
    $(confirm_btn_1).hide();
    $(confirm_btn_2).show();
    //$(confirm_btn_1).text('Continue');
    //confirm_btn_1.setAttribute('style', 'background-color: darkgreen; color: white;');
    $(confirm_btn_2).text('Understood');
    confirm_btn_2.setAttribute('style', 'background-color: darkred; color: white;');
    $(confirm_btn_1).click(function(){
        var formdata = new FormData();
        formdata.append("header", $('#new_h_header').val());
        formdata.append("position", $('#new_h_position').val());
        formdata.append("act", "ins_header");
        var url = "itemsobj.php";
        ajaxpost(url, new_header_added, formdata);
    });
    // 'confirm_insert(\'header\', ' + header_id + ')');
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

function new_header_added(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        //Reload Table
        get_items_table();
        $('#new_h_header').val("");
    } else {

    }
}

function updated_inventory(response){
    var data = JSON.parse(response.responseText);
    $('#current_qty_' + data['item_id']).text(data['new_quantity']);
    /*Confirm Box Script
    ***************************
    */
    let confirm_text = "<span>Inventory Updated</span>";
    let confirm_btn_1 = document.createElement('button');
    $('.confirm-header').text("");
    $('.confirm-text-box').html(confirm_text);
    $(confirm_btn_1).text('Finish').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'conclude_inventory()'});
    $('.confirm-btn-box').html("").append(confirm_btn_1);
    $('#pageCover').attr('onclick', 'conclude_inventory()').show();
    $('.confirm-box').attr('style', 'display: flex;');
    /*Confirm Box Script
    ***************************
    */

}