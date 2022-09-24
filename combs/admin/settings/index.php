<?php
    require_once('inc/functions.php');
    require_once('../inc/db.php');
    //require_once('../inc/functions.php');
    /*require_once("includes/form_functions.php"); */

    start_session('acclzsess');
    $error_redirect = 'login';
    check_log_in($error_redirect);

?>
<!DOCTYPE html>
<html>
<head>
    <title> Combs </title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PM Consolidated">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="og:title" property="og:title" content="">
    <meta name="og:description" property="og:description" content="">
    <meta name="og:image" property="og:image" content="images/logo.png">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="images/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel= "stylesheet" href= "../css/style.css" type= "text/css" />
    <!--<link rel= "stylesheet" href= "../css/localstyle.css" type= "text/css" />--> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div class="pageCover">&nbsp;</div>
    <div class="confirm-box">
        <div class="confirm-header"></div>
        <span class="confirm-text-1"></span><br>
        <span class="confirm-text-2"></span><br>
        <span class="confirm-text-3"></span><br>
        <button id="confirm_btn_1"></button><button id="confirm_btn_2"></button>
    </div>
    <header class="row top-header">
        <span class="col- logo-span"><img src="images/logo3.jpg" alt="logo" class="top-logo" /></span>
        <h1 class="col- title">COMBS</h1>
        <span class="col- top-space-span">&nbsp;</span>
        <a href="#" class="col- menu-btn-span whitelink" style="text-align: right;"><i class="fa fa-bars menu-btn" id="showMenu"></i></a>
        <span class="mobile-nav" id="mobileNav">
            <nav class="">
                <ul class="mobile-menu">
                    <a href="#" title="Close"><li class="mobile-menu-item mobile-menu-x-btn"><i class="fa fa-close" onclick="hideMenu()"></i></li></a>
                    <a href="#" title="Main"><li class="mobile-menu-item active-mobile-menu">Main</li></a>
                    <a href="#services" title="Services"><li class="mobile-menu-item inactive-mobile-menu">End Day</li></a>
                    <a href="overview/" title="Overview"><li class="mobile-menu-item inactive-mobile-menu">Cash Back Transaction</li></a>
                    <a href="policies/" title="Policies"><li class="mobile-menu-item inactive-mobile-menu">Change User</li></a>
                    <a href="policies/" title="Policies"><li class="mobile-menu-item inactive-mobile-menu">Help</li></a>
                    <a href="policies/" title="Policies"><li class="mobile-menu-item inactive-mobile-menu">About</li></a>
                    <a href="contact-us/" title="Contact Us"><li class="mobile-menu-item inactive-mobile-menu">Contact&nbsp;Us</li></a>
                </ul>
            </nav>
        </span>
        <span class="menu-bar col- no-mobile col-">
        <nav class="">
            <ul class="menu">
                <a href="#" title="Main"><li class="menu-item active-menu">Main</li></a>
                <a href="#services" title="Services"><li class="menu-item inactive-menu">End Day</li></a>
                <a href="overview/" title="Overview"><li class="menu-item inactive-menu">Cash Back Transaction</li></a>
                <a href="policies/" title="Policies"><li class="menu-item inactive-menu">Change User</li></a>
                <a href="policies/" title="Policies"><li class="menu-item inactive-menu">Help</li></a>
                <a href="policies/" title="Policies"><li class="menu-item inactive-menu">About</li></a>
            </ul>
        </nav>
        </span>
    </header>
    <main>
        <section>
            <table>
                <tr><th>Item</th><th>Amount</th><th></th></tr>
                <?php
                    // Store the cipher method 
                    $ciphering = "AES-128-CTR"; 
                    
                    // Use OpenSSl Encryption method 
                    $options = 0; 
                    
                    // Non-NULL Initialization Vector for encryption 
                    $encryption_iv = '1234567898765432'; 
                    
                    // Store the encryption key 
                    $encryption_key = "EjiroMoses"; 
     
                    $query = "SELECT * FROM {$items_table}";
                    $get_items = $connection->prepare($query);
                    $get_items->execute();
                    $result = $get_items->get_result();
                    while ($items = $result->fetch_assoc()){
                        $item_name = $items['name'];
                        $item_price = $items['price'];
                        $item_id = $items['id'];
                        // Use openssl_encrypt() function to encrypt the data 
                        $encrypted_id = openssl_encrypt($item_id, $ciphering, $encryption_key, $options, $encryption_iv);

                        if($item_id == 8){
                            echo "<tr><td colspan=\"2\">FEMALE LIST</td><td></td></tr>
                                <tr><td id=\"title-" . $encrypted_id . "\"> " . $item_name . " </td><td id=\"price-" . $encrypted_id . "\">N" . $item_price . "</td><td><button onclick
                                =\"addtocart('" . $encrypted_id . "')\">Select</button></td></tr>";
                        } elseif($item_id == 11){
                            echo "<tr><td colspan=\"2\">OTHER SERVICES</td><td></td></tr>
                                <tr><td id=\"title-" . $encrypted_id . "\"> " . $item_name . " </td><td id=\"price-" . $encrypted_id . "\">N" . $item_price . "</td><td><button onclick
                                =\"addtocart('" . $encrypted_id . "')\">Select</button></td></tr>";
                        } elseif($item_id == 24){
                            echo "<tr><td colspan=\"2\">CHILDREN</td><td></td></tr>
                                <tr><td id=\"title-" . $encrypted_id . "\"> " . $item_name . " </td><td id=\"price-" . $encrypted_id . "\">N" . $item_price . "</td><td><button onclick
                                =\"addtocart('" . $encrypted_id . "')\">Select</button></td></tr>";
                        } else {
                            echo "<tr><td id=\"title-" . $encrypted_id . "\"> " . $item_name . " </td><td id=\"price-" . $encrypted_id . "\">N" . $item_price . "</td><td><button onclick
                                =\"addtocart('" . $encrypted_id . "')\">Select</button></td></tr>";
                        }
                    }

                ?></table>
        </section>
        <hr>
        <section class="cart">
            <header>Cart</header>
            <span class="cart-info"></span>
            <button>Checkout</button>
        </section>
    </main>
    <footer>
    <div class="bottom-footer row"><span class="col-3 col-m-5 col-t-4 s-bottom-footer">Combs</span><span class="col-5 no-mobile col-t-2">&nbsp;</span><span class="col-3 col-m-6 col-t-5" style="text-align: right;">licenced by PM Consolidated</span></div>
    </footer>

    <script type="text/javascript" src="../js/script.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <noscript></noscript>

</body>
</html>