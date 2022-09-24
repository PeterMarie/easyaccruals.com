<?php
    require_once('../includes/tables.php');
    require_once('../../inc/functions.php');
    require_once('../../inc/db.php');
    require_once('../../inc/form_functions.php');
    
    start_session('acclzsess');
    $error_redirect = '';
    check_log_in_for_login_page();

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
    <title> Combs | log-In </title>
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
    <link rel= "stylesheet" href= "../../css/style.css" type= "text/css" />
    <link rel= "stylesheet" href= "../../css/localstyle.css" type= "text/css" />
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
    <div id="pageCover">&nbsp;</div>
    <header class="row top-header">
        <span class="col-1 col-m-3 col-t-1 logo-span"><img src=<?php echo "\"../../images/logos/" . $business_name . ".png\"" ?> alt="logo" class="top-logo" /></span>
        <h1 class="col-4 col-m-4 col-t-4 title"><?php echo strtoupper($business_name); ?></h1>
        <span class="col-7 col-m-5 col-t-7 top-space-span">&nbsp;</span>
    </header>
    <main>
        <section class="login-section">
            <header><h2 class="admin-home-header">Login to COMBS</h2></header>
            <form id="login-form">
                <span id="login_status"></span>
                <table>
                    <tr><td class="login-form-cell">Username</td><td class="login-form-cell"><input type="text" name="username" id="username" required /></td></tr>
                    <tr><td class="login-form-cell">Password</td><td class="login-form-cell"><input type="password" name="password" id="password" required /></td></tr>
                    <tr><td colspan="2" class="login-form-cell"><button type="submit" id="login-btn" class="item-select-btn">Log In</button></td></tr>
                </table>
            </form>
        </section>
    </main>
    <footer>
    <div class="bottom-footer row"><span class="col-3 col-m-5 col-t-4 s-bottom-footer">Combs</span><span class="col-5 no-mobile col-t-2">&nbsp;</span><span class="col-3 col-m-6 col-t-5" style="text-align: right;">licenced by PM Consolidated</span></div>
    </footer>

    <script type="text/javascript" src="../../js/script.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <noscript></noscript>

</body>
</html>