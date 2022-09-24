
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

let current_item;
let current_item_name;
let current_item_price;
let current_item_amount;

function confirm_sale(){
    var url = "makesale.php?itm=" + current_item;
    ajax(url, madesale);
}
function cancel_sale(){
    $('.pageCover').hide();
    $('.confirm-box').hide();
}

/* Controller functions */ 

function addtocart(item){
    //show confirmation alert
    current_item = item;
    let item_name = 'title-' + item;
    let item_price = 'price-' + item;
    current_item_name = "Item: <span class=\"confirm-item-name\">" + document.getElementById(item_name).innerText;
    //let current_item_amount = "</span><br>Amount: " + document.getElementById().val;
    current_item_price = "Total Price: <span class=\"confirm-item-price\"> " + document.getElementById(item_price).innerText + "</span>";
    let confirm_btn_1 = document.getElementById('confirm_btn_1');
    let confirm_btn_2 = document.getElementById('confirm_btn_2');
    $('.confirm-header').text("Are You Sure?");
    $('.confirm-text-1').text("Sale Details:");
    $('.confirm-text-2').html(current_item_name);
    $('.confirm-text-3').html(current_item_price);
    $(confirm_btn_1).text('Confirm');
    $(confirm_btn_2).text('Cancel');
    confirm_btn_1.setAttribute('onclick', 'confirm_sale()');
    confirm_btn_2.setAttribute('onclick', 'cancel_sale()');
    $('.pageCover').show();
    $('.confirm-box').show();
}

/* Action functions */
function madesale(response){
    $('.pageCover').hide();
    $('.confirm-box').hide();
}