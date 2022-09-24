<?php
    require_once('../../includes/tables.php');
    require_once('../../../inc/functions.php');
    require_once('../../../inc/db.php');

    start_session('acclzsess');
    $error_redirect = '../../';
    check_admin($error_redirect);

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
    <title> Combs | Manage Products </title>
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
    <link rel= "stylesheet" href= "../../../css/style.css" type= "text/css" />
    <!--<link rel= "stylesheet" href= "../css/localstyle.css" type= "text/css" />--> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
        <span class="col-1 col-m-3 col-t-1 logo-span"><img src=<?php echo "\"../../../images/logos/" . $business_name . ".png\"" ?> alt="logo" class="top-logo" /></span>
        <h1 class="col-4 col-m-4 col-t-4 title"><?php echo strtoupper($business_name); ?></h1>
        <span class="col-1 col-m-3 col-t-1 top-space-span">&nbsp;</span>
        <span class="col-6 col-t-6 no-mobile menu-bar">
            <nav class="top-menu ">
                <ul class="menu">
                    <?php 
                        if(isset($_SESSION['admin']) && ($_SESSION['admin'] > 1)){
                            echo "<a href=\"../\" title=\"Administrative Section\"><li class=\"menu-item inactive-menu\">Admin Pages</li></a>";
                        }
                    ?>
                    <a href="../../" title="Cashier Page"><li class="menu-item inactive-menu">Cashier Page</li></a>
                    <!--<a href="../../settings/" title="Settings"><li class="menu-item inactive-menu">Profile Settings</li></a>-->
                    <a href="../../logout.php" title="Log Out"><li class="menu-item inactive-menu">Logout</li></a>
                    <!--<a href="../../documentation/" title="Help"><li class="menu-item inactive-menu">Help</li></a>
                    <a href="../../about/" title="About"><li class="menu-item inactive-menu">About</li></a>-->
                </ul>
            </nav>
        </span>
        <a href="#" class="col-m-1 mobile-only menu-btn-span linkcolour1" style="text-align: right;"><i class="fa fa-bars menu-btn" id="showMenu"></i></a>
        <span class="mobile-nav" id="mobileNav">
            <nav class="">
                <ul class="mobile-menu">
                    <a href="#" title="Close"><li class="mobile-menu-item mobile-menu-x-btn"><i class="fa fa-close" onclick="hideMenu()"></i></li></a>
                    <?php 
                        if(isset($_SESSION['admin']) && ($_SESSION['admin'] > 1)){
                            echo "<a href=\"../\" title=\"Administrative Section\"><li class=\"mobile-menu-item inactive-mobile-menu\">Admin Pages</li></a>";
                        }
                    ?>
                    <a href="../../" title="Cashier Page"><li class="mobile-menu-item inactive-mobile-menu">Cashier Page</li></a>
                    <!--<a href="../../settings/" title="Settings"><li class="mobile-menu-item inactive-mobile-menu">Profile Settings</li></a>-->
                    <a href="../../logout.php" title="Log Out"><li class="mobile-menu-item inactive-mobile-menu">Logout</li></a>
                    <!--<a href="../../documentation/" title="Help"><li class="mobile-menu-item inactive-mobile-menu">Help</li></a>
                    <a href="../../about/" title="About"><li class="mobile-menu-item inactive-mobile-menu">About</li></a>-->
                </ul>
            </nav>
        </span>
    </header>
    <main>
        <section class="main-sec row">
            <section class="inventory-section">
                <header class="inventory-header-section"><h2>Update Inventory</h2></header>
                <div class="inventory-text-section">
                    <table cellspacing="0">
                        <tr><td class="cart-cell">Item</td><td class="cart-cell"><span class="inventory-item"></span></td></tr>
                        <tr><td class="cart-cell">Current Quantity in Stock</td><td class="cart-cell"><span class="inventory-current-qty"></span></td></tr>
                        <tr><td colspan="2" class="cart-cell">
                            <span class="inventory-input-span">
                                <span><input type="text" id="new_inventory" placeholder="Enter value here" style="width: 100%; height:2em;"></span>
                                <span class="inventory-input-btn-section">
                                    <button class="inventory-btn add_inventory" id="" data-effect="add" disabled>Add</button>
                                    <button class="inventory-btn substract_inventory" id="" data-effect="substract" disabled>Substract</button>
                                    <button class="inventory-btn set_inventory" id="" data-effect="set" disabled>Set</button>
                                </span>
                            </span>
                        </td></tr>
                        <tr><td colspan="2"><input type="checkbox" id="inventory_status" />&nbsp;<span>Set as inexhaustible product</span></td></tr>
                        <tr><td class="cart-cell total">New Quantity</td><td class="cart-cell total"><span class="inventory-new-qty"></span></td></tr>
                    </table>
                </div>
                <div class="inventory-btn-section">
                    <button class="cart-btn update-inventory-btn" disabled >Update</button>
                    <button class="cart-btn cancel-update-inventory-btn" onclick="close_inventory()" >Cancel</button>
                </div>
            </section>
            <header><h2 class="admin-home-header">Manage Products</h2></header>
            <section id="items_table_section" ></section>
            <section class="">
                <div class="col-1 col-m-1 col-t-1">&nbsp;</div>
                <div class="col-11 col-m-11 col-t-11">
                    <header><h3 class="rep-table-header">Add Product</h3></header>
                    <form id="new_p_form">
                        <table class="items-table">
                            <tr><td>Name</td><td><input type="text" name="new_p_name" id="new_p_name" required /></td></tr>
                            <tr><td>Price</td><td><input type="number" name="new_p_price" id="new_p_price" required /></td></tr>
                            <tr><td>Insert After</td><td><select name="new_p_position" id="new_p_position" class="new-item-select" >
                            <?php
                                $count = 0;
                                $count2 = 0;
                                //Get Items Table
                                $query = "SELECT * FROM {$items_table}";
                                $get_items = $connection->prepare($query);
                                $get_items->execute();
                                $result = $get_items->get_result();
                                while ($items = $result->fetch_assoc()){
                                    $count++;
                                }

                                //Get Items Table AGAIN?!!
                                $query = "SELECT * FROM {$items_table}";
                                $get_items = $connection->prepare($query);
                                $get_items->execute();
                                $result = $get_items->get_result();
                                while ($items = $result->fetch_assoc()){
                                    $count2++;
                                    if($count !== $count2){
                                        $item_name = $items['name'];
                                        $item_id = $items['id'];
                                        $option = "<option value=\"" . $item_id . "\">" . $item_name . "</option>";
                                    } else {
                                        $item_name = $items['name'];
                                        $item_id = $items['id'];
                                        $option = "<option value=\"" . $item_id . "\" selected >" . $item_name . "</option>";
                                    }
                                    echo $option;
                                }
                            ?>
                            </select>
                            <tr><td><button type="submit" id="new_p_btn" class="item-select-btn" >Submit</button></td></tr>
                        </table>
                    </form>
                </div>
                <div class="col-1 col-m-1 col-t-1">&nbsp;</div>
            </section>
            <section>
                <div class="col-1 col-m-1 col-t-1">&nbsp;</div>
                <div class="col-11 col-m-11 col-t-11">
                    <header><h3 class="rep-table-header">Insert Header</h3></header>
                    <form id="new_h_form">
                        <table class="items-table">
                            <tr><td>Header</td><td><input type="text" name="new_h_header" id="new_h_header" required /></td></tr>
                            <tr><td>Insert Before</td><td><select name="new_h_position" id="new_h_position" class="new-item-select" >
                            <?php
                                $count = 0;
                                $count2 = 0;
                                //Get Items Table
                                $query = "SELECT * FROM {$items_table}";
                                $get_items = $connection->prepare($query);
                                $get_items->execute();
                                $result = $get_items->get_result();
                                while ($items = $result->fetch_assoc()){
                                    $count++;
                                }

                                //Get Items Table AGAIN?!!
                                $query = "SELECT * FROM {$items_table}";
                                $get_items = $connection->prepare($query);
                                $get_items->execute();
                                $result = $get_items->get_result();
                                while ($items = $result->fetch_assoc()){
                                    $count2++;
                                    if($count !== $count2){
                                        $item_name = $items['name'];
                                        $item_id = $items['id'];
                                        $option = "<option value=\"" . $item_id . "\">" . $item_name . "</option>";
                                    } else {
                                        $item_name = $items['name'];
                                        $item_id = $items['id'];
                                        $option = "<option value=\"" . $item_id . "\" selected >" . $item_name . "</option>";
                                    }
                                    echo $option;
                                }
                            ?>
                            </select>
                            <tr><td><button type="submit" id="new_h_btn" class="item-select-btn">Submit</button></td></tr>
                        </table>
                    </form>
                </div>
                <div class="col-1 col-m-1 col-t-1">&nbsp;</div>
            </section>
        </section>
    </main>
    <footer>
    <div class="bottom-footer row"><span class="col-3 col-m-5 col-t-4 s-bottom-footer">Combs</span><span class="col-5 no-mobile col-t-2">&nbsp;</span><span class="col-3 col-m-6 col-t-5" style="text-align: right;">licenced by PM Consolidated</span></div>
    </footer>

    <script type="text/javascript" src="../../../js/script.js"></script>
    <script type="text/javascript" src="script.js"></script>
        <script>
   
        </script>
    <noscript></noscript>

</body>
</html>