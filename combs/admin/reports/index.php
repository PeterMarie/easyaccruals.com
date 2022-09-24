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
    <title> Combs | Reports </title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PM Consolidated">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="og:title" property="og:title" content="">
    <meta name="og:description" property="og:description" content="">
    <meta name="og:image" property="og:image" content=<?php echo "\"../../../images/logos/" . $business_name . ".png\"" ?>>
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href=<?php echo "\"../../../images/logos/" . $business_name . ".png\"" ?> />
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
        <span class="col-2 col-m-3 col-t-2 logo-span"><img src=<?php echo "\"../../../images/logos/" . $business_name . ".png\"" ?> alt="logo" class="top-logo" /></span>
        <h1 class="col-4 col-m-4 col-t-3 title"><?php echo strtoupper($business_name); ?></h1>
        <span class="mobile-only col-m-3 top-space-span">&nbsp;</span>
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
        <!--<a href="../" class="" >Back to Admin Home</a>-->
        <section class="main-sec">
            <header><h2 class="admin-home-header">Sales Reports</h2></header>
            <div class="l-rep-sec">
                <aside class="col-m-10 rep-aside">
                    <div class="rep-mob-only-aside mobile-only">
                        <h3 class="rep-table-header" id="mob_table_header" >Today</h3>
                        <button class="rep-aside-btn" id="change_period_btn" >Change Period</button>
                    </div>
                    <div class="no-mobile" id="l_rep_period_links">
                        <div id="s_rep_period_links">
                            <a href="#" onclick="gen_rep('day')" class="linkcolour3 rep_period_links" ><span>Today</a>
                            <a href="#" onclick="gen_rep('week')" class="linkcolour3 rep_period_links" >This week</a>
                            <a href="#" onclick="gen_rep('preweek')" class="linkcolour3 rep_period_links" >Last week</a>
                            <a href="#" onclick="gen_rep('month')" class="linkcolour3 rep_period_links" >This month</a>
                            <a href="#" onclick="gen_rep('premonth')" class="linkcolour3 rep_period_links" >Last month</a>
                            <a href="#" onclick="gen_rep('year')" class="linkcolour3 rep_period_links" >This year</a>
                            <a href="#" onclick="gen_rep('preyear')" class="linkcolour3 rep_period_links" >Last year</a>
                        </div>
                        <div class="l-gen-rep-form">
                            <h3 class="gen-rep-form-header">Custom Period</h3>
                            <form id="genrepform">
                                <table class="gen-rep-table">
                                    <tr><td>From</td></tr>
                                    <tr><td><input type="date" class="gen-rep-input" name="start_date" id="start_date" required /></td></tr>
                                    <tr><td>To</td></tr>
                                    <td><input type="date" class="gen-rep-input" name="end_date" id="end_date" required /></td>
                                    <tr><td><button id="genrepbtn" class="rep-aside-btn">Generate Report</button></td><td></td></tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </aside>
                <section id="repsec">
                    <h3 class="rep-table-header no-mobile">Today</h3>
                        <?php
                            $time = strtotime('today');
                            $total = 0;
                            
                            $report_table = "<table class=\"items-table\" cellspacing=\"0\"><tr class=\"table-header\"><th class=\"items-cell\">Date</th><th class=\"items-cell\">Time</th><th class=\"items-cell\">Item</th><th class=\"items-cell\">Amount</th></tr>";
                            $query = "SELECT * FROM {$sales_table} WHERE time > ? ";
                            $get_items = $connection->prepare($query);
                            $get_items->bind_param('i', $time);
                            $get_items->execute();
                            $result = $get_items->get_result();
                            $count = 0;
                            while ($items = $result->fetch_assoc()){
                                $count++;
                                $query = "SELECT name, price FROM {$items_table} WHERE id = ?";
                                $get_item_details = $connection->prepare($query);
                                $get_item_details->bind_param('i', $items['item_id']);
                                $get_item_details->execute();
                                $result2 = $get_item_details->get_result();
                                while ($item_details = $result2->fetch_assoc()){
                                    $item_name = $item_details['name'];
                                    $item_price = $item_details['price'];
                                    $amount_paid = $item_details['price'] * $items['amount'];
                                    $total = $total + $amount_paid;
                                }
                                $report_table .= "<tr><td class=\"items-cell\">". date("D, jS M Y", $items['time']) ."</td><td class=\"items-cell\">" . strftime("%I:%M %p", $items['time']) . "</td><td class=\"items-cell\">" . $item_name . "</td><td class=\"items-cell\">" . $amount_paid . "</td></tr>";
                            }
                            if($count > 0){
                                $report_table .= "<tr><td class=\"items-cell\"></td><td class=\"items-cell\"></td><td class=\"items-cell\">TOTAL</td><td class=\"items-cell\">" . $total . "</td></tr></table>";
                            } else {
                                $report_table = "<div class=\"no-sales-report\"> Nothing to show.<br>Make some sales and it will show up here.</div>";
                            }
                            echo $report_table;
                        ?>
                </section>
            </div>
        </section>
    </main>
    <footer>
    <div class="bottom-footer row"><span class="col-3 col-m-5 col-t-4 s-bottom-footer">Combs</span><span class="col-5 no-mobile col-t-2">&nbsp;</span><span class="col-3 col-m-6 col-t-5" style="text-align: right;">licenced by PM Consolidated</span></div>
    </footer>

    <script type="text/javascript" src="../../../js/script.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <noscript></noscript>

</body>
</html>