<?php
    require_once('includes/tables.php');
    require_once('../inc/functions.php');
    require_once('../inc/db.php');
    //require_once('../inc/functions.php');
    /*require_once("includes/form_functions.php"); */

    start_session('acclzsess');
    $error_redirect = 'login';
    check_log_in($error_redirect);

    $business_name = BUSINESS_NAME_DB;
    $query = "SELECT colour1, colour2, colour3 FROM clients WHERE business_name_short = ? ";
    $get_theme = $connection->prepare($query);
    $get_theme->bind_param("s", $business_name);
    $get_theme->execute();
    $result = $get_theme->get_result();
    $theme = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
    <title> <?php echo $business_name . " | Cashier" ?> </title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="easyaccruals, business, sales, recorder, report">
    <meta name="author" content="PM Consolidated">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="og:title" property="og:title" content=<?php echo "\"" . $business_name . " | Cashier\"" ?> >
    <meta name="og:description" property="og:description" content="">
    <meta name="og:image" property="og:image" content=<?php echo "\"../images/logos/" . $business_name . ".png\"" ?>>
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href=<?php echo "\"../images/logos/" . $business_name . ".png\"" ?> />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Archivo:500|Open+Sans:300,700|Work+Sans:400,700|Montserrat:500,900" rel="stylesheet">
    <link rel= "stylesheet" href= "../css/style.css" type= "text/css" />
    <!--<link rel= "stylesheet" href= "../css/localstyle.css" type= "text/css" />--> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
<style>
    <?php
        for ($i=1; $i <= 3; $i++) { 
            $colour_variable = "colour" . $i;
            $colour = $theme[$colour_variable];
            $query = "SELECT css_name, css_text_colour, css_hover_colour, css_text_hover_colour FROM colours where id = ? ";
            $get_colour = $connection->prepare($query);
            $get_colour->bind_param("i", $colour);
            $get_colour->execute();
            $result = $get_colour->get_result();
            $$colour_variable = $result->fetch_assoc();
        }

        $css_colour_variables = ":root {--colour1: " . $colour1['css_name'] . "; --colour2: " . $colour2['css_name'] . "; --text_colour1: " . $colour1['css_text_colour'] . "; --text_colour2: " . $colour2['css_text_colour'] . "; --colour3: " . $colour3['css_name'] . "; --text_colour3: " . $colour3['css_text_colour'] . "; --hover_colour1: " . $colour1['css_hover_colour'] . "; --hover_text_colour1: " . $colour1['css_text_hover_colour'] .  "; --hover_colour2: " . $colour2['css_hover_colour'] . "; --hover_text_colour2: " . $colour2['css_text_hover_colour'] .  "; --hover_colour3: " . $colour3['css_hover_colour'] . "; --hover_text_colour3: " . $colour3['css_text_hover_colour'] . "; }";
        echo $css_colour_variables;
    ?>
    /*colour 1: header background, auto text colour
    colour 2: nav background, darken on hover, auto text colour
    colour 3: body background, auto text colour*/


</style>

    <div id="pageCover" >&nbsp;</div>
    <div class="confirm-box">
        <div class="confirm-header"></div>
        <div class="confirm-text-box"></div>
        <div class="confirm-btn-box"></div>
    </div>
    <div class="top-alert"></div>
    <header class="row top-header">
        <span class="col-2 col-m-3 col-t-2 logo-span"><img src=<?php echo "\"../images/logos/" . $business_name . ".png\"" ?> alt="logo" class="top-logo" /></span>
        <h1 class="col-4 col-m-4 col-t-3 title"><?php echo strtoupper($business_name); ?></h1>
        <span class="mobile-only col-m-3 top-space-span">&nbsp;</span>
        <span class="col-6 col-t-6 no-mobile menu-bar">
            <nav class="top-menu ">
                <ul class="menu">
                    <a href="#" title="Main"><li class="menu-item active-menu">Main</li></a>
                    <?php 
                        if(isset($_SESSION['admin']) && ($_SESSION['admin'] > 1)){
                            echo "<a href=\"admin\" title=\"Administrative Section\"><li class=\"menu-item inactive-menu\">Admin Pages</li></a>";
                        }
                    ?>
                    <!--<a href="settings/" title="Settings"><li class="menu-item inactive-menu">Profile Settings</li></a>-->
                    <a href="logout.php" title="Log Out"><li class="menu-item inactive-menu">Logout</li></a>
                    <!--<a href="documentation/" title="Help"><li class="menu-item inactive-menu">Help</li></a>
                    <a href="about/" title="About"><li class="menu-item inactive-menu">About</li></a>-->
                </ul>
            </nav>
        </span>
        <a href="#" class="col-m-1 mobile-only menu-btn-span linkcolour1" style="text-align: right;"><i class="fa fa-bars menu-btn" id="showMenu"></i></a>
        <span class="mobile-nav" id="mobileNav">
            <nav class="">
                <ul class="mobile-menu">
                    <a href="#" title="Close"><li class="mobile-menu-item mobile-menu-x-btn"><i class="fa fa-close" onclick="hideMenu()"></i></li></a>
                    <a href="#" title="Main"><li class="mobile-menu-item active-mobile-menu">Main</li></a>
                    <?php 
                        if(isset($_SESSION['admin']) && ($_SESSION['admin'] > 1)){
                            echo "<a href=\"admin\" title=\"Administrative Section\"><li class=\"mobile-menu-item inactive-mobile-menu\">Admin Pages</li></a>";
                        }
                    ?>
                    <!--<a href="settings/" title="Settings"><li class="mobile-menu-item inactive-mobile-menu">Profile Settings</li></a>-->
                    <a href="logout.php" title="Log Out"><li class="mobile-menu-item inactive-mobile-menu">Logout</li></a>
                    <!--<a href="documentation/" title="Help"><li class="mobile-menu-item inactive-mobile-menu">Help</li></a>
                    <a href="about/" title="About"><li class="mobile-menu-item inactive-mobile-menu">About</li></a>-->
                </ul>
            </nav>
        </span>
    </header>
    <main class="cashier-main">
        <section class="cart-section">
            <header class="cart-header-section"><h2>Your Cart</h2></header>
            <div class="cart-text-section"></div>
            <div class="cart-btn-section">
                <button class="cont-shopping cart-btn">Continue Shopping</button>
                <button class="final-checkout-btn cart-btn" disabled >Checkout</button>
                <button class="clear-cart-btn cart-btn" data-src="cart" disabled >Clear Cart</button>
            </div>
        </section>
        <aside class="cashier-aside">
            <section class="quick-sale-section">
                <header class="quick-sale-header admin-home-header"><h2>Quick Sale</h2></header>
                <div class="quick-sale-text-section">
                    <table cellspacing="0">
                        <tr><td class="quick-sale-cell"><label for="quick_item">Item</label></td><td class="quick-sale-cell"><input type="text" name="quick_item" id="quick_item" style="display: flex; flex-direction: column" required /><span id="found_items"></span></td></tr>
                        <tr><td class="quick-sale-cell"><label for="quick_item_qty">Quantity</label></td><td class="quick-sale-cell"><input type="text" name="quick_item_qty" id="quick_item_qty" value="1" required disabled /></td></tr>
                        <tr><td class="quick-sale-cell"><label for="">Total Price</label></td><td class="quick-sale-cell"><span id="quick_item_price">-</span></td></tr>
                    </table>
                </div>
                <div class="quick-sale-btn-box">
                    <button class="final-add-to-cart-btn quick-sale-btn item-select-btn" onclick= "confirm_add_to_cart('cont')" disabled>Add to Cart</button>
                </div>
            </section>
            <div class="side-btn-box no-mobile"><button class="side-btn view-cart-btn">View Cart&nbsp;<i class="fa fa-shopping-cart" aria-hidden="true"></i></button><button data-src="page" class="side-btn clear-cart-btn" disabled >Clear Cart&nbsp;<i class="fa fa-trash" aria-hidden="true"></i></button></div>
        </aside>
        <section class="all-items-section">
            <header class="admin-home-header"><h2>All Items</h2></header>
            <table class="items-table" cellspacing="0" style="margin-bottom: 15vh;">
               <!-- <tr><th class="items-cell table-header" >Item</th><th class="items-cell table-header">Amount</th><th></th></tr>-->
                <?php
                    //Get Items Headers
                    $items_headers = array();
                    $query = "SELECT * FROM {$items_headers_table}";
                    $get_headers = $connection->prepare($query);
                    $get_headers->execute();
                    $result = $get_headers->get_result();
                    while($headers = $result->fetch_assoc()){
                        //Store each header in array
                        $items_headers[$headers['position']] = $headers['header'];
                    }

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
                        //Check for presense of header at position
                        if(isset($items_headers[$item_id]) && !empty($items_headers[$item_id])){
                            echo "<tr class=\"items-row\"><td colspan=\"2\" class=\"items-cell items-table-header\" >" . strtoupper($items_headers[$item_id]) . "</td><td class=\"items-cell\"></td></tr>
                                <tr class=\"items-row\"><td id=\"title-" . $encrypted_id . "\" class=\"items-cell\"> " . $item_name . " </td><td class=\"items-cell\">N<span id=\"price-" . $encrypted_id . "\">" . $item_price . "</span></td>";
                        } else {
                            echo "<tr class=\"items-row\"><td id=\"title-" . $encrypted_id . "\" class=\"items-cell\"> " . $item_name . " </td><td class=\"items-cell\">N<span id=\"price-" . $encrypted_id . "\">" . $item_price . "</span></td>";
                        }

                        //Check if item is in stock
                        if(!is_null($items['quantity_in_stock']) && ($items['quantity_in_stock'] == 0)){
                            echo "<td class=\"items-cell\"><button class=\"item-select-btn table-btn\" onclick
                            =\"addtocart('" . $encrypted_id . "')\" disabled >Out of Stock</button></td></tr>";
                        } else {
                            echo "<td class=\"items-cell\"><button class=\"item-select-btn table-btn\" onclick
                            =\"addtocart('" . $encrypted_id . "')\">Select</button></td></tr>";
                        }
                    }

                ?></table>
        </section>
    </main>
    <footer>
        <div class="bottom-btn-box mobile-only"><button class="bottom-btn view-cart-btn">View Cart&nbsp;<i class="fa fa-shopping-cart" aria-hidden="true"></i></button><button data-src="page" class="bottom-btn clear-cart-btn" disabled >Clear Cart&nbsp;<i class="fa fa-trash" aria-hidden="true"></i></button></div>
    <div class="bottom-footer row"><span class="col-3 col-m-5 col-t-4 s-bottom-footer"><?php echo strtoupper($business_name); ?></span><span class="col-5 no-mobile col-t-2">&nbsp;</span><span class="col-3 col-m-7 col-t-5 s-bottom-footer" style="text-align: right;">licenced by PM Consolidated</span></div>
    </footer>

    <script type="text/javascript" src="../js/script.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <noscript></noscript>

</body>
</html>