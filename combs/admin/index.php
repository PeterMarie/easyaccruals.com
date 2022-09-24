<?php
    require_once('../includes/tables.php');
    require_once('../../inc/functions.php');
    require_once('../../inc/db.php');

    start_session('acclzsess');
    $error_redirect = '../';
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
    <title> Combs | Admin </title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PM Consolidated">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="og:title" property="og:title" content="">
    <meta name="og:description" property="og:description" content="">
    <meta name="og:image" property="og:image" content="../../images/logos/combs.png">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="../../images/logos/combs.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel= "stylesheet" href= "../../css/style.css" type= "text/css" />
    <!--<link rel= "stylesheet" href= "../../css/localstyle.css" type= "text/css" />--> 
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
        <div class="confirm-text-box">
            <span class="confirm-text-1"></span>
            <span class="confirm-text-2"></span>
            <span class="confirm-text-3"></span>
        </div>
        <div class="confirm-btn-box">
            <button id="confirm_btn_1" class="confirm-btn"></button>
            <button id="confirm_btn_2" class="confirm-btn"></button>
        </div>
    </div>
    <header class="row top-header">
        <span class="col-2 col-m-3 col-t-2 logo-span"><img src=<?php echo "\"../../images/logos/" . $business_name . ".png\"" ?> alt="logo" class="top-logo" /></span>
        <h1 class="col-4 col-m-4 col-t-3 title"><?php echo strtoupper($business_name); ?></h1>
        <span class="mobile-only col-m-3 top-space-span">&nbsp;</span>
        <span class="col-6 col-t-6 no-mobile menu-bar">
            <nav class="top-menu ">
                <ul class="menu">
                    <?php 
                        if(isset($_SESSION['admin']) && ($_SESSION['admin'] > 1)){
                            echo "<a href=\"#\" title=\"Administrative Section\"><li class=\"menu-item active-menu\">Admin Pages</li></a>";
                        }
                    ?>
                    <a href="../" title="Cashier Page"><li class="menu-item inactive-menu">Cashier Section</li></a>
                    <!--<a href="../settings/" title="Settings"><li class="menu-item inactive-menu">Profile Settings</li></a>-->
                    <a href="../logout.php" title="Log Out"><li class="menu-item inactive-menu">Logout</li></a>
                    <!--<a href="documentation/" title="Help"><li class="menu-item inactive-menu">Help</li></a>
                    <a href="../about/" title="About"><li class="menu-item inactive-menu">About</li></a>-->
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
                            echo "<a href=\"#\" title=\"Administrative Section\"><li class=\"mobile-menu-item active-mobile-menu\">Admin Pages</li></a>";
                        }
                    ?>
                    <a href="../" title="Cashier Page"><li class="mobile-menu-item inactive-mobile-menu">Cashier Section</li></a>
                    <!--<a href="../settings/" title="Settings"><li class="mobile-menu-item inactive-mobile-menu">Profile Settings</li></a>-->
                    <a href="../logout.php" title="Log Out"><li class="mobile-menu-item inactive-mobile-menu">Logout</li></a>
                    <!--<a href="documentation/" title="Help"><li class="mobile-menu-item inactive-mobile-menu">Help</li></a>
                    <a href="../about/" title="About"><li class="mobile-menu-item inactive-mobile-menu">About</li></a>-->
                </ul>
            </nav>
        </span>
    </header>
    <main>
        <header><h2 class="admin-home-header">Administrative Pages</h2></header>
        <section class="admin-home-section">
                <a href="reports" class="linkcolour1 admin-links" title="View Sales Reports" ><span class="">View Sales Reports</span></a>
                <a href="products" class="linkcolour1 admin-links" title="Manage Products"><span class="">Manage Products</span></a>
                <a href="manage" class="linkcolour1 admin-links" title="Manage Cashiers"><span class="">Manage Cashiers</span></a>
                <a href="../" class="linkcolour1 admin-links" title="General Cashier Section"><span class="">Go To Cashiers Section</span></a>
                <!--<a href="settings" class="linkcolour1 admin-links" title="Site Settings"><span class="">Site Settings</span></a>-->
        </section>
    </main>
    <footer>
    
    <div class="bottom-footer row"><span class="col-3 col-m-5 col-t-4 s-bottom-footer"><?php echo strtoupper($business_name); ?></span><span class="col-5 no-mobile col-t-2">&nbsp;</span><span class="col-3 col-m-7 col-t-5 s-bottom-footer" style="text-align: right;">licenced by PM Consolidated</span></div>
    </footer>

    <script type="text/javascript" src="../../js/script.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <noscript></noscript>

</body>
</html>