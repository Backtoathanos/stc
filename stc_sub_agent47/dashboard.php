<?php  
session_start(); 
if(isset($_SESSION["stc_agent_sub_id"])){ 
    // if(time()-$_SESSION["login_time_stamp"] >600)   
    // { 
    //     session_unset(); 
    //     session_destroy(); 
    //     header("Location:index.html"); 
    // } 
}else{ 
    header("Location:index.html"); 
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
    <link href="assets/css/style.css" rel="stylesheet"><!-- Bootstrap CSS -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        .objective-list {
            list-style-type: none; /* Removes bullet points */
            font-family: 'Arial', sans-serif; /* Professional font */
            font-size: 16px; /* Readable size */
            color: #ffffff; /* White text */
            margin: 0;
            padding: 0;
            line-height: 1.6; /* Improves spacing between lines */
        }

        .objective-list li {
            margin-bottom: 10px; /* Adds space between list items */
            padding-left: 20px; /* Indents the list items */
            position: relative;
        }

        .objective-list li::before {
            content: '•'; /* Custom bullet point */
            color: #ffcc00; /* Accent color for bullet */
            position: absolute;
            left: 0;
            top: 0;
            font-size: 20px; /* Bigger bullet size for better visual */
            line-height: 1.2;
        }
        .objective-list span{
            font-weight: bold;
            font-size: 25px;
        }
    </style>

</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <?php // include_once("ui-setting.php");?>        
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
                            <div class="col-md-4">
                                <div id="stcCarousel" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ul class="carousel-indicators">
                                        <li data-target="#stcCarousel" data-slide-to="0" class="active"></li>
                                        <li data-target="#stcCarousel" data-slide-to="1"></li>
                                        <li data-target="#stcCarousel" data-slide-to="2"></li>
                                        <li data-target="#stcCarousel" data-slide-to="5"></li>
                                        <li data-target="#stcCarousel" data-slide-to="6"></li>
                                        <li data-target="#stcCarousel" data-slide-to="7"></li>
                                        <li data-target="#stcCarousel" data-slide-to="8"></li>
                                        <li data-target="#stcCarousel" data-slide-to="9"></li>
                                    </ul>

                                    <!-- The slideshow -->
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="./images/1.avif" style="height:400px;" alt="First Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="./images/2.avif" style="height:400px;" alt="Second Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="./images/3.avif" style="height:400px;" alt="Second Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="./images/4.avif" style="height:400px;" alt="Second Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="./images/6.webp" style="height:400px;" alt="Second Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="./images/7.jpg" style="height:400px;" alt="Third Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="./images/8.avif" style="height:400px;" alt="Second Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="./images/9.avif" style="height:400px;" alt="Second Slide" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Left and right controls -->
                                    <a class="carousel-control-prev" href="#stcCarousel" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#stcCarousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <div class="card mb-3 widget-content bg-amy-crisp">
                                    <div class="widget-content-wrapper text-white">
                                        <ul class="objective-list">
                                            <span>Our objective:</span>
                                            <li>No harm to anyone.</li>
                                            <li>No harm to machinery.</li>
                                            <li>No harm to environment.</li>
                                            <li>Survive zero accidental life.</li>
                                            <li>Our organization always wishes your long and better comfort life.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <!-- <script type="text/javascript" src="./assets/scripts/loginopr.js"></script> -->
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