<?php
session_start();

// Set session and cookie parameters
$session_duration = 24 * 60; // 24 minutes (cPanel limit)
$cookie_duration = 7 * 24 * 60 * 60; // 7 days in seconds

// If session exists
if(isset($_SESSION["stc_agent_id"])) {
    // Check if remember cookie exists, if not set it
    if(!isset($_COOKIE["stc_agent_remember"])) {
        setcookie("stc_agent_remember", $_SESSION["stc_agent_id"], time() + $cookie_duration, "/");
        setcookie("stc_agent_name", $_SESSION["stc_agent_name"], time() + $cookie_duration, "/");
        setcookie("stc_agent_role", $_SESSION["stc_agent_role"], time() + $cookie_duration, "/");
    }
} 
// If session doesn't exist but cookie does
elseif(isset($_COOKIE["stc_agent_remember"])) {
    // Restore session from cookie
    $_SESSION["stc_agent_id"] = $_COOKIE["stc_agent_remember"];
    $_SESSION["stc_agent_name"] = $_COOKIE["stc_agent_name"];
    $_SESSION["stc_agent_role"] = $_COOKIE["stc_agent_role"];
    // Optionally refresh the cookie
    setcookie("stc_agent_remember", $_COOKIE["stc_agent_remember"], time() + $cookie_duration, "/");
    setcookie("stc_agent_name", $_COOKIE["stc_agent_name"], time() + $cookie_duration, "/");
    setcookie("stc_agent_role", $_COOKIE["stc_agent_role"], time() + $cookie_duration, "/");
}
// Neither session nor cookie exists
else {
    header("Location: index.html");
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Dashboard - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <?php include_once("ui-setting.php");?>        
        <div class="app-main">
                <?php include_once("sidebar-nav.php");?>                   
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="called-product">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card mb-3 widget-content bg-midnight-bloom">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-left">Welcome to STC Associates.
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers text-warning"><span></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script>
        $(document).ready(function(){
            $('body').delegate('.search-icon', 'click', function(e){
                e.preventDefault();
                $(this).hide();
                $('.search-icon-2').show();
                // var pd_title=$('.agent-pro-search').val();
                // window.location.href="stc-product.php?pd_name="+pd_title;
            });

            $('body').delegate('.search-icon-2', 'click', function(e){
                var pd_title=$('.agent-pro-search').val();
                if(pd_title!=""){
                    window.location.href="stc-product.php?pd_name="+pd_title;
                }
            });    
        });
    </script>
</body>
</html>
