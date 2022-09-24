
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

//Business Details Variables
let full_business_name;
let business_address;
let business_phone_no;

//Cart and sale variables
let current_item;
let current_item_name;
let current_item_price;
let current_item_total_price;
let current_item_quantity = 1;
let cart = {count: 0, total: 0, true_count: 0};

get_business_details();

function cancel_sale(){
    $('#pageCover').hide();
    $('.confirm-box').hide();
}
$('.cont-shopping').on('click', function(){
    $('.cart-section').animate({height: 'toggle', opacity: 0}, {duration: 400});
    $('#pageCover').hide();
});
$('.view-cart-btn').on('click', show_cart);
$('.final-checkout-btn').on('click', function(){
    let confirm_text = "";
    let confirm_btn_1 = document.createElement('button');
    let confirm_btn_2 = document.createElement('button');
    $('.confirm-header').text("Are you Sure?");
    $('.confirm-text-box').html(confirm_text);
    $(confirm_btn_1).text('Confirm').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;'}).on('click', function(){
        $(confirm_btn_1).prop('disabled', true).attr({style: 'background-color: gray; border-color: gray; color: rgb(80,80,80);'});
        $(confirm_btn_2).prop('disabled', true).attr({style: 'background-color: gray; border-color: gray; color: rgb(80,80,80);'});
        checkout();
    });
    $(confirm_btn_2).text('Cancel').attr({class: 'confirm-btn', style: 'background-color: darkred; color: white; border-color: darkred;'}).on('click', function(){
        $('#pageCover').attr({style: 'z-index: 98'}).show();
        $('.confirm-box').hide();
    });
    $('.confirm-btn-box').html("").append(confirm_btn_1).append(confirm_btn_2);
    $('#pageCover').prop('onclick', null).attr('style', 'z-index: 100').show();
    $('.confirm-box').attr('style', 'display: flex;');
});
$('.clear-cart-btn').on('click', function(){
    let src = $(this).data('src');
    let confirm_text = "<span>Remove ALL items from your cart?</span><span style=\"color: red;\">This is permanent and cannot be undone</span>";
    let confirm_btn_1 = document.createElement('button');
    let confirm_btn_2 = document.createElement('button');
    $('.confirm-header').text("Clear Cart?");
    $('.confirm-text-box').html(confirm_text);
    $(confirm_btn_1).text('Confirm').attr({class: 'confirm-btn', style: 'background-color: darkred; color: white; border-color: darkred;'}).on('click', function(){
        clear_cart();
        cart_cleared(src, true);
    });
    $(confirm_btn_2).text('Cancel').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;'}).on('click', function(){
        cart_cleared(src, false);
    });
    $('.confirm-btn-box').html("").append(confirm_btn_1).append(confirm_btn_2);
    $('#pageCover').attr('style', 'z-index: 100').show().on('click', function(){
        cart_cleared(src, false);
    });
    $('.confirm-box').attr('style', 'display: flex;');
});

function dlt_frm_cart(item_count_no){
    if(cart.hasOwnProperty(item_count_no)){
        let item_total = parseInt(cart[item_count_no]['total_price']);
        cart['total'] = cart['total'] - item_total;
        cart['true_count'] = cart['true_count'] - 1;
        delete cart[item_count_no];
        if(cart['true_count'] == 0){
            $('.cart-text-section').html('<span>You have no items in your cart.</span>');
            //disable buttons
            $('.final-checkout-btn').prop("disabled", true);
            $('.clear-cart-btn').prop("disabled", true);
        } else {
            $('#total').text(cart['total']);
            $('#item_row_' + item_count_no).hide();
        }
        $('.top-alert').text("Item Removed successfully").attr({style: 'background-color: darkgreen'}).animate({height: 'toggle', opacity: 1}, {duration: 400});
        setTimeout(function(){
            $('.top-alert').animate({height: 'toggle', opacity: 0}, {duration: 400});
        }, 3000);
    }
};

function clear_cart(){
    for(var old_item in cart){
        if(cart.hasOwnProperty(old_item)){
            delete cart[old_item];
        }
    }
    cart['count'] = cart['total'] = cart['true_count'] = 0;
    
    //disable buttons
    $('.final-checkout-btn').prop("disabled", true);
    $('.clear-cart-btn').prop("disabled", true);

    return;
}

function cart_cleared(source, truth_value = true){
    switch (source) {
        case 'page':
            $('#pageCover').attr({style: 'z-index: 98'}).hide();
            $('.confirm-box').hide();
            if(truth_value){
                $('.top-alert').text("Cart Cleared successfully").attr({style: 'background-color: darkgreen'}).animate({height: 'toggle', opacity: 1}, {duration: 400});
                setTimeout(function(){
                    $('.top-alert').animate({height: 'toggle', opacity: 0}, {duration: 400});
                }, 3000);
            }
            break;
            
        case 'cart':
            $('#pageCover').attr({style: 'z-index: 98'}).show();
            $('.confirm-box').hide();
            if(truth_value){
                $('.cart-text-section').html('<span>You have no items in your cart.</span>');
            }
            break;
            
        case 'checkout':
            $('.cart-section').animate({height: "toggle", opacity: 0}, {duration: 400});
            $('#pageCover').attr({style: 'z-index: 98'}).hide();
            $('.confirm-box').hide();
            $('.cart-text-section').html('<span>You have no items in your cart.</span>');
            break;
    
        default:
            break;
    }

    return;
}

var bind_to = '#confirm_quantity';
 
// Prevent double-binding.
$(document.body).off('change', bind_to);

// Bind the event to all body descendants matching the "bind_to" selector.
$(document.body).on('change input keydown keyup mousedown mouseup select contextmenu drop', bind_to, function(event) {
    if(($(this).val() == "") || ($(this).val() == 0)){
        $(this).val('');
        $('.final-add-to-cart-btn').prop('disabled', true);
        $('.first-checkout-btn').prop('disabled', true);
        current_item_quantity = 1;
        $('#confirm_item_price').text("-");
        return;
    } else {
        $('.final-add-to-cart-btn').prop('disabled', false);
        $('.first-checkout-btn').prop('disabled', false);    
    }
    
    $("#confirm_quantity").inputFilter(function(value) {
        return /^\d*$/.test(value);    // Allow digits only, using a RegExp
    });
    current_item_quantity = $(this).val();
    current_item_total_price = parseInt($(this).val()) * parseInt(current_item_price);
    $('#confirm_item_price').text(current_item_total_price);
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
  

function addtocart(item){
    //show confirmation alert
    current_item = item;
    let item_name = 'title-' + item;
    let item_price = 'price-' + item;
    current_item_name = document.getElementById(item_name).innerHTML;
    current_item_total_price = current_item_price = parseInt(document.getElementById(item_price).innerHTML);
    let confirm_text = "<table cellspacing= \"0\" style= \"padding: 5%;\"><tr><td>Item</td><td><span class=\"confirm-item-name\">" + current_item_name + "<span></td></tr><tr><td>Quantity</td><td><input type=\"text\" id=\"confirm_quantity\" value=\"1\" style=\"width: 100%;\"/></td></tr><tr><td>Total Price</td><td class=\"confirm-item-price\">N<span id=\"confirm_item_price\">" + current_item_price + "</span></td></tr></table>";
    let confirm_btn_1 = document.createElement('button');
    let confirm_btn_2 = document.createElement('button');
    let confirm_btn_3 = document.createElement('button');
    $('.confirm-header').text("Are You Sure?");
    $('.confirm-text-box').html(confirm_text);
    $(confirm_btn_1).text('Add to Cart').attr({class: 'confirm-btn final-add-to-cart-btn', style: 'background-color: var(--colour1); color: var(--text_colour1); border-color: var(--colour1);', onclick: 'confirm_add_to_cart(\'cont\')'});
    $(confirm_btn_2).text('Checkout').attr({class: 'confirm-btn first-checkout-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'confirm_add_to_cart(\'vcart\')'});
    $(confirm_btn_3).text('Cancel').attr({class: 'confirm-btn', style: 'background-color: darkred; color: white; border-color: darkred;', onclick: 'cancel_activity()'});
    $('.confirm-btn-box').html("").append(confirm_btn_1).append(confirm_btn_2).append(confirm_btn_3);
    $('#pageCover').attr('onclick', 'cancel_activity()').show();
    $('.confirm-box').attr('style', 'display: flex;');
}

function confirm_add_to_cart(next_activity){
    let count = cart['count'];
    count++;
    cart['count'] = count;

    let new_item = {};
    new_item['item'] = current_item;
    new_item['name'] = current_item_name;
    new_item['quantity'] = current_item_quantity;
    new_item['total_price'] = current_item_total_price;
    cart[count] = new_item;

    cart['true_count'] = cart['true_count'] + 1;
    cart['total'] = cart['total'] + current_item_total_price;
    cancel_sale();

    //enable buttons
    $('.final-checkout-btn').prop("disabled", false);
    $('.clear-cart-btn').prop("disabled", false);

    switch (next_activity) {
        case 'cont':
            $('.top-alert').text('Successfully Added to Cart').attr({style: 'background-color: darkgreen; color: white;'}).animate({height: 'toggle', opacity: 1}, {duration: 400});
            setTimeout(function(){
                $('.top-alert').animate({height: 'toggle', opacity: 0}, {duration: 400});
            }, 3000);
            break;
            
        case 'vcart':
            show_cart();
            break;
    
        default:
            break;
    }

    //Reset quick sale form
    $('#quick_item').val("");
    $('.quick-sale-btn').prop('disabled', true);
    $('#quick_item_qty').prop('disabled', true);
    $('#quick_item_price').text("-");
}

function show_cart(){
    if(cart['true_count'] > 0){
        let table = "<table cellspacing=\"0\" ><tr><th class=\"cart-cell\">Qty</th><th class=\"cart-cell\">Item</th><th class=\"cart-cell\">Total Price</th><th class=\"cart-cell\"></th></tr>"
        for (let i = 1; i <= cart['count']; i++) {
            if(cart.hasOwnProperty(i)){
                table = table + "<tr id=\"item_row_" + i + "\"><td class=\"cart-cell\">" + cart[i]['quantity'] + "</td><td class=\"cart-cell\">" + cart[i]['name'] + "</td><td class=\"cart-cell\">" + cart[i]['total_price'] + "</td><td class=\"cart-cell\"><button class=\"cart-dlt-btn dlt-btn\" onclick=\"dlt_frm_cart(" + i + ")\" >Remove</button></td></tr>";
            }
        }
        table = table + "<tr><td class=\"cart-cell total\"></td><td class=\"cart-cell total\">TOTAL</td><td class=\"cart-cell total\" id=\"total\">" + cart['total'] + "</td><td class=\"cart-cell total\"></td></tr></table>";
        $('.cart-text-section').html(table);

        //enable buttons
        $('.final-checkout-btn').prop("disabled", false);
        $('.clear-cart-btn').prop("disabled", false);
    } else {
        $('.cart-text-section').html("<span>You have no items in your cart.</span>");
        $('.final-checkout-btn').prop("disabled", true);
        $('.clear-cart-btn').prop("disabled", true);
    }
    $('.cart-section').animate({height: "toggle", opacity: 1}, {duration: 400}).attr({style: 'display: flex;'});
    $('#pageCover').prop('onclick', null).show();
}

/* Controller functions */ 
function checkout(){
    let cart_string = JSON.stringify(cart);
    var formdata = new FormData();
    formdata.append('act', 'checkout');
    formdata.append('cart', cart_string);
    ajaxpost('makesale.php', checkedout, formdata);
}

function get_business_details(){
    ajax('business.php', business_details);
}

$('#quick_item').on('keyup change', function(){
    if($(this).val() !== ""){
        $('#quick_item').attr({style: 'background-color: rgb(255, 240, 240)'});
        var formdata = new FormData();
        formdata.append('act', 'search');
        formdata.append('entry', $(this).val());
        ajaxpost('makesale.php', show_found_items, formdata);
    } else {
        if($('#found_items').is(':visible')){
            $('#found_items').animate({height: 'toggle', opacity: 0}, {duration: 400});
        } else {
            //Do Nothing
        }
        $('#quick_item_qty').prop('disabled', true);
        $('.quick-sale-btn').prop('disabled', true);
        $('#quick_item_price').text("-");
        $('#quick_item').attr({style: 'background-color: initial;'});
    }
});

/* Action functions */
function checkedout(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        //print receipt
        let receipt;
        let receipt_header = "<div><h1>" + full_business_name + "</h1><p>" + business_address + "</p></div>";
        let receipt_top_div = "<div style=\"border-bottom: 1px dashed black;\"><table><tr><td>" + data['date'] + "</td><td>" + data['time'] + "</td></tr><tr><td>Cashier</td><td>" + data['cashier_name'] + "</td></tr><tr><td>Receipt no:</td><td>" + data['receipt_no'] + "</td></tr></table></div>";
        let receipt_table = "<table cellspacing=\"0\" ><tr><th class=\"cart-cell\">Qty</th><th class=\"cart-cell\">Item</th><th class=\"cart-cell\">Total Price</th></tr>"
        for (let i = 1; i <= cart['count']; i++) {
            if(cart.hasOwnProperty(i)){
                receipt_table = receipt_table + "<tr id=\"item_row_" + i + "\"><td class=\"cart-cell\">" + cart[i]['quantity'] + "</td><td class=\"cart-cell\">" + cart[i]['name'] + "</td><td class=\"cart-cell\">" + cart[i]['total_price'] + "</td></tr>";
            }
        }
        receipt_table = receipt_table + "<tr><td class=\"cart-cell total\"></td><td class=\"cart-cell total\">TOTAL</td><td class=\"cart-cell total\" id=\"total\">" + cart['total'] + "</td></tr></table>";
        let receipt_footer = "<div>Thanks for shopping with us</div>";
        receipt = receipt_header + receipt_table + receipt_footer;
        var win = window.open("", "", "width=320,heigth=320");
        win.document.open();
        win.document.write('<' + 'html' + '><head></head>' + '<' + 'body' + '>');
       // win.document.head.innerHTML = document.head.innerHTML;
        win.document.write(receipt);
        win.document.write('<' + '/body' + '><' + '/html' + '>');
        win.document.close();
        win.print();
        win.close();

        //modify confirm box with reprint receipt option
        confirm_text = "<span>Sale details here</span>";
        let confirm_btn_1 = document.createElement('button');
        let confirm_btn_2 = document.createElement('button');
        $('.confirm-header').text("Sale Completed");
        $('.confirm-text-box').html(confirm_text);
        $(confirm_btn_1).text('Print Receipt').attr({class: 'confirm-btn', style: 'background-color: var(--colour1); color: var(--text_colour1); border-color: var(--colour1);'}).on('click', function(){
            checkout();
        });
        $(confirm_btn_2).text('Finish').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;'}).on('click', function(){
            cart_cleared('checkout');
            clear_cart();
        });
        $('.confirm-btn-box').html("").append(confirm_btn_1).append(confirm_btn_2);
        
    } else {
        confirm_text = "<span>There was an error processing your sale. Please try again.</span><span style=\"font-size: 90%\">If error persists please contact admin.</span>";
        let confirm_btn_1 = document.createElement('button');
        $('.confirm-header').text("Error");
        $('.confirm-text-box').html(confirm_text);
        $(confirm_btn_1).text('Understood').attr({class: 'confirm-btn', style: 'background-color: darkgreen; color: white; border-color: darkgreen;', onclick: 'checkout()'});
        $('.confirm-btn-box').html("").append(confirm_btn_1);
        
    }
}

function show_found_items(response){
    var data = JSON.parse(response.responseText);
    if(data['status'] == "success"){
        $('#found_items').html('');
        if(data['count'] > 0){
            for (let i = 1; i <= data['count']; i++) {
                if(data['items'].hasOwnProperty(i)){
                    var span = document.createElement('span');
                    $(span).text(data['items'][i]['item_name']).attr({class: 'found_item_span'}).on('click', function(){
                        current_item = data['items'][i]['item'];
                        current_item_name = data['items'][i]['item_name'];
                        current_item_price = current_item_total_price = data['items'][i]['item_price'];
                        current_item_quantity = 1;
    
                        $('#quick_item').val(current_item_name);
                        $('#quick_item_qty').prop('disabled', false);
                        $('.quick-sale-btn').prop('disabled', false);
                        $('#quick_item_price').text(current_item_total_price);

                        $('#quick_item').attr({style: 'background-color: rgb(240, 255, 240)'});
                        $('#found_items').animate({height: 'toggle', opacity: 0}, {duration: 400});
                    });
                    $('#found_items').append(span);
                    
                    if($('#quick_item').val() == data['items'][i]['item_name']){
                        current_item = data['items'][i]['item'];
                        current_item_name = data['items'][i]['item_name'];
                        current_item_price = current_item_total_price = data['items'][i]['item_price'];
                        current_item_quantity = 1;
    
                        $('#quick_item').val(current_item_name);
                        $('#quick_item_qty').prop('disabled', false);
                        $('.quick-sale-btn').prop('disabled', false);
                        $('#quick_item_price').text(current_item_total_price);

                        $('#quick_item').attr({style: 'background-color: rgb(240, 255, 240)'});
                    }
                }
            }
            if($('#found_items').is(':visible')){
                //Do Nothing
            } else {
                $('#found_items').animate({height: 'toggle', opacity: 1}, {duration: 400}).attr({style: 'display: flex'});
            }
        } else {
            if($('#found_items').is(':visible')){
                $('#found_items').animate({height: 'toggle', opacity: 0}, {duration: 400});
            } else {
                //Do Nothing
            } 
            $('.quick-sale-btn').prop('disabled', true);
            $('#quick_item_qty').prop('disabled', true);
            $('#quick_item_price').text("-");

        }
    }
}

function business_details(response){
    var data = JSON.parse(response.responseText);
    full_business_name = data['business_name_full'];
    business_address = data['business_address'];
    business_phone_no = data['phone_business'];
}