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
    <title>Products - STC</title>
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
                        <div class="called-product-page">
                            <?php
                                include_once("../MCU/db.php");
                            if(@$_GET['pd_name']=="null" || @$_GET['pd_name']==""){
                                $rowperpage = 3;
                                $purchase_rate='';
                                $alagsequery="
                                            SELECT DISTINCT * FROM `stc_product` 
                                            LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
                                            LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
                                            LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
                                            ORDER BY RAND() LIMIT 0,8                                            
                                ";
                                $check_loki=mysqli_query($con, $alagsequery);
                                $odin='';               
                                $do_action=mysqli_num_rows($check_loki);
                                if($do_action > 0){
                                    foreach ($check_loki as $row) {
                                        $loki_findratefrompo=mysqli_query($con, "
                                            SELECT `stc_purchase_product_items_rate` FROM `stc_purchase_product_items` 
                                            WHERE 
                                                `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."' 
                                            AND 
                                                `stc_purchase_product_items_id`=(
                                                SELECT MAX(`stc_purchase_product_items_id`) 
                                                FROM `stc_purchase_product_items` 
                                                WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."') 
                                        ");
                                        $purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
                                        if(!empty($purchase_rate['stc_purchase_product_items_rate'])){
                                            $correct_stcpdname=strlen($row["stc_product_name"]);
                                            $percalamount = ($purchase_rate['stc_purchase_product_items_rate'] * 5)/100;
                                            $pdsale_price = $percalamount + $purchase_rate['stc_purchase_product_items_rate'];
                                            $odin.='
                                                    <div class="el-wrapper">
                                                        <div class="box-up">
                                                            <img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$row["stc_product_image"].'" alt="">
                                                            <div class="img-info">
                                                                <div class="info-inner">
                                                                    <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
                                                                    <span class="p-company">'.$row["stc_product_unit"].'</span>
                                                                    
                                                                </div>
                                                                <div class="a-size">
                                                                    <input
                                                                        id="stcpoqty'.$row["stc_product_id"].'"
                                                                        name="stcpoqty"
                                                                        type="number"
                                                                        placeholder="Quantity"
                                                                        class="form-control validate"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="box-down">
                                                            <div class="h-bg">
                                                              <div class="h-bg-inner"></div>
                                                            </div>

                                                            <a class="cart addtocartagent" href="#" id="'.$row["stc_product_id"].'">
                                                              <span class="price">'.$row["stc_sub_cat_name"].'</span>
                                                              <span class="add-to-cart">
                                                                <span class="txt">Add Item</span>
                                                              </span>
                                                            </a>
                                                        </div>
                                                    </div>                  
                                            ';  
                                        }            
                                    }
                                }else{
                                    $odin .= '
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                <a 
                                                  href="#" 
                                                  class="btn btn-primary btn-block text-uppercase mb-3">
                                                 Nahi hai bhai!!!
                                                </a><br>
                                                <p>Try to searh like - for ex:<br>
                                                Copper Pipe : pipe</p>
                                            </div>

                                            ';
                                }
                                echo $odin;
                                $allcount_query = "SELECT count(*) as allcount FROM stc_product";
                                $allcount_result = mysqli_query($con,$allcount_query);
                                $allcount_fetch = mysqli_fetch_array($allcount_result);
                                $allcount = $allcount_fetch['allcount'];
                            }else{
                                $rowperpage = 3;
                                $purchase_rate='';
                                $alagsequery="
                                            SELECT DISTINCT * FROM `stc_product` 
                                            LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
                                            LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
                                            LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
                                            WHERE (
                                                `stc_product_name` REGEXP '".$_GET['pd_name']."'
                                                OR 
                                                `stc_product_desc` REGEXP '".$_GET['pd_name']."'
                                            )
                                            ORDER BY RAND() LIMIT 0,30                                           
                                ";
                                $check_loki=mysqli_query($con, $alagsequery);
                                $odin='';               
                                $do_action=mysqli_num_rows($check_loki);
                                if($do_action > 0){
                                    foreach ($check_loki as $row) {
                                        $loki_findratefrompo=mysqli_query($con, "
                                            SELECT * FROM `stc_purchase_product_items` 
                                            WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."' 
                                            AND `stc_purchase_product_items_id`=(
                                                SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
                                                WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."'
                                            )
                                        ");
                                        $purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
                                        if(!empty($purchase_rate['stc_purchase_product_items_rate'])){
                                            $correct_stcpdname=strlen($row["stc_product_name"]);
                                            $percalamount = ($purchase_rate['stc_purchase_product_items_rate'] * 5)/100;
                                            $pdsale_price = $percalamount + $purchase_rate['stc_purchase_product_items_rate'];
                                            $odin.='
                                                    <div class="el-wrapper">
                                                        <div class="box-up">
                                                            <img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$row["stc_product_image"].'" alt="">
                                                            <div class="img-info">
                                                                <div class="info-inner">
                                                                    <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
                                                                    <span class="p-company">'.$row["stc_product_unit"].'</span>
                                                                    
                                                                </div>
                                                                <div class="a-size">
                                                                    <input
                                                                        id="stcpoqty'.$row["stc_product_id"].'"
                                                                        name="stcpoqty"
                                                                        type="number"
                                                                        placeholder="Quantity"
                                                                        class="form-control validate"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="box-down">
                                                            <div class="h-bg">
                                                              <div class="h-bg-inner"></div>
                                                            </div>

                                                            <a class="cart addtocartagent" href="#" id="'.$row["stc_product_id"].'">
                                                              <span class="price">'.$row["stc_sub_cat_name"].'</span>
                                                              <span class="add-to-cart">
                                                                <span class="txt">Add Item</span>
                                                              </span>
                                                            </a>
                                                        </div>
                                                    </div>                  
                                            ';    
                                        }          
                                    }
                                }else{
                                    $odin .= '
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                <a 
                                                  href="#" 
                                                  class="btn btn-primary btn-block text-uppercase mb-3">
                                                 Nahi hai bhai!!!
                                                </a><br>
                                                <p>Tips - If you want to search "Copper Pipe" then<br>
                                                just type : "pipe"</p>
                                            </div>
                                            ';
                                }
                                echo $odin;
                                $allcount_query = "SELECT count(*) as allcount FROM stc_product";
                                $allcount_result = mysqli_query($con,$allcount_query);
                                $allcount_fetch = mysqli_fetch_array($allcount_result);
                                $allcount = $allcount_fetch['allcount'];
                            }
                            ?>

                            <input type="hidden" id="stc_pro_curr_row" value="0">
                            <input type="hidden" id="stc_pro_all_row" value="<?php echo $allcount; ?>">
                        </div>
                    </div>            
                    <!-- <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card mb-3 widget-content bg-grow-early">
                                    <div class="widget-content-wrapper text-white">
                                        <div class="widget-content-right content-glow">
                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div> -->
                </div>
                <div class="app-wrapper-footer">
                        <div class="app-footer">
                            <div class="app-footer__inner">
                                <div class="app-footer-left">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                Home
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                Order
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="app-footer-right">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                Payments
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link">
                                                <div class="badge badge-success mr-1 ml-0">
                                                    <small>NEW</small>
                                                </div>
                                                Margins
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/scripts/jquery-3.3.1.js"></script>
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
