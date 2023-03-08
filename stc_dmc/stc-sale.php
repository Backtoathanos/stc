<?php  
session_start(); 
if(isset($_SESSION["stc_agent_id"])){ 
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
    <title>My Order - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <!-- <link href="assets/css/style.css" rel="stylesheet"> -->
    <style>
        .d-flex {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
        }

        .align-center {
          -webkit-box-align: center;
          -ms-flex-align: center;
          align-items: center;
        }

        .flex-centerY-centerX {
          justify-content: center;
          -webkit-box-pack: center;
          -ms-flex-pack: center;
          justify-content: center;
          -webkit-box-align: center;
          -ms-flex-align: center;
          align-items: center;
        }

        .page-wrapper {
          height: 100%;
          display: table;
        }

        .page-wrapper .page-inner {
          display: table-cell;
          vertical-align: middle;
        }

        .el-wrapper {
          float: left;
          width: 320px;
          padding: 15px;
          margin: 15px auto;
          background-color: #fff;
        }

        @media (max-width: 991px) {
          .el-wrapper {
            width: 345px;
          }
        }

        @media (max-width: 767px) {
          .el-wrapper {
            width: 290px;
            margin: 30px auto;
          }
        }

        .el-wrapper:hover .h-bg {
          left: 0px;
        }

        .el-wrapper:hover .price {
          left: 20px;
          -webkit-transform: translateY(-50%);
          -ms-transform: translateY(-50%);
          -o-transform: translateY(-50%);
          transform: translateY(-50%);
          color: #818181;
        }

        .el-wrapper:hover .add-to-cart {
          left: 50%;
        }

        .el-wrapper:hover .img {
          webkit-filter: blur(7px);
          -o-filter: blur(7px);
          -ms-filter: blur(7px);
          filter: blur(7px);
          filter: progid:DXImageTransform.Microsoft.Blur(pixelradius='7', shadowopacity='0.0');
          opacity: 0.4;
        }

        .el-wrapper:hover .info-inner {
          bottom: 155px;
        }

        .el-wrapper:hover .a-size {
          -webkit-transition-delay: 300ms;
          -o-transition-delay: 300ms;
          transition-delay: 300ms;
          bottom: 50px;
          opacity: 1;
        }

        .el-wrapper .box-down {
          width: 100%;
          height: 60px;
          position: relative;
          overflow: hidden;
        }

        .el-wrapper .box-up {
          width: 100%;
          height: 400px;
          position: relative;
          overflow: hidden;
          text-align: center;
        }

        .el-wrapper .img {
          padding: 20px 0;
          -webkit-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          -moz-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          -o-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
        }

        .h-bg {
          -webkit-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          -moz-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          -o-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          width: 660px;
          height: 100%;
          background-color: #3f96cd;
          position: absolute;
          left: -659px;
        }

        .h-bg .h-bg-inner {
          width: 50%;
          height: 100%;
          background-color: #464646;
        }

        .info-inner {
          -webkit-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
          -moz-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
          -o-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
          transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          position: absolute;
          width: 100%;
          bottom: 25px;
        }

        .info-inner .p-name,
        .info-inner .p-company,
         .info-inner .p-qty {
          display: block;
        }

        .info-inner .p-name {
          font-family: 'PT Sans', sans-serif;
          font-size: 18px;
          color: #252525;
        }

        .info-inner .p-company {
          font-family: 'Lato', sans-serif;
          font-size: 12px;
          text-transform: uppercase;
          color: #8c8c8c;
        }

        .info-inner .p-qty {
          font-family: 'Lato', sans-serif;
          font-size: 25px;
          font-weight: bold;
          text-transform: uppercase;
          color: #020822;;
        }

        .a-size {
          -webkit-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
          -moz-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
          -o-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
          transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          position: absolute;
          width: 100%;
          bottom: -20px;
          font-family: 'PT Sans', sans-serif;
          color: #828282;
          opacity: 0;
        }

        .a-size .size {
          color: #252525;
        }

        .cart {
          display: block;
          position: absolute;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          font-family: 'Lato', sans-serif;
          font-weight: 700;
        }

        .cart .price {
          -webkit-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          -moz-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          -o-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-delay: 100ms;
          -o-transition-delay: 100ms;
          transition-delay: 100ms;
          display: block;
          position: absolute;
          top: 50%;
          left: 50%;
          -webkit-transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          -o-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
          font-size: 16px;
          color: #252525;
        }

        .cart .add-to-cart {
          -webkit-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          -moz-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          -o-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
          /* ease-out */
          -webkit-transition-delay: 100ms;
          -o-transition-delay: 100ms;
          transition-delay: 100ms;
          display: block;
          position: absolute;
          top: 50%;
          left: 110%;
          -webkit-transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          -o-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
        }

        .cart .add-to-cart .txt {
          font-size: 12px;
          color: #fff;
          letter-spacing: 0.045em;
          text-transform: uppercase;
          white-space: nowrap;
        }
    </style>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Customer Name</label>
                                    <input type="text" class="form-control cust-name" placeholder="Please enter customer name">
                                </div>
                                <div class="col-md-6">
                                    <label>Customer Contact No</label>
                                    <input type="number" class="form-control cust-cont" placeholder="Please enter customer contact no">
                                </div>
                                <div class="col-md-6">
                                    <label>Customer Address</label>
                                    <textarea type="text" class="form-control cust-address" placeholder="Please enter customer address"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Customer Whatsapp</label>
                                    <input type="number" class="form-control cust-whats" placeholder="Please enter customer whatsapp no">
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" id="pd-search-txt" placeholder="Please enter product name">
                                    <button class="mb-2 mr-2 btn btn-primary btn-lg btn-block" id="pd-search-btn">
                                        Search
                                    </button>
                                    <div class="call-pd-out"></div>
                                </div>
                                <div class="col-md-12 load_cart_data">
                                    <table class="table">
                                        <thead>
                                            <th>Item Desc</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            <td colspan="3">
                                                Cart empty
                                            </td>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <button class="mb-2 mr-2 btn btn-primary btn-lg btn-block save-ag-cust-sale">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>            

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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script>
        $(document).ready(function(){
            $('body').delegate('#pd-search-btn', 'click', function(e){
                e.preventDefault();
                var pd_name=$('#pd-search-txt').val();
                $.ajax({
                    url : 'sparda/stc_sale_cust.php',
                    method : 'POST',
                    data : {
                        search_pd:1,
                        pd_name:pd_name
                    },
                    success : function(response){
                        // console.log(response);
                        $('.call-pd-out').html(response);
                    }
                });
            });

            load_cart();
            function load_cart(){
                $.ajax({
                    url : 'sparda/stc_sale_cust.php',
                    method : 'POST',
                    data : {
                        load_acart_ag:1
                    },
                    success : function(response){
                        // console.log(response);
                        $('.load_cart_data').html(response);
                    }
                });
            }

            $('body').delegate('.add_to_ag_cust', 'click', function(e){
                e.preventDefault();
                var id=$(this).attr("id");
                var pd_price=$('#stcpdprice'+id).val();
                var pd_qty=$('#stcpoqty'+id).val();
                var inv_qty=$('#stcinvqty'+id).val();
                if(pd_qty>inv_qty){
                    alert("Item quantity cannot be greater than inventory quantity!!!");
                }else{
                    $.ajax({
                        url : 'sparda/stc_sale_cust.php',
                        method : 'POST',
                        data : {
                            add_to_c_ag:1,
                            pd_price:pd_price,
                            pd_qty:pd_qty,
                            id:id
                        },
                        success : function(response){
                            // console.log(response);
                            // $('.call-pd-out').html(response);
                            alert(response);
                            load_cart();
                        }
                    });
                }
            });

            $('body').delegate('.rem_pd_cart', 'click', function(e){
                e.preventDefault();
                var id=$(this).attr("id");              
                $.ajax({
                    url : 'sparda/stc_sale_cust.php',
                    method : 'POST',
                    data : {
                        rem_fro_cart:1,
                        items_id:id
                    },
                    success : function(response){
                        // console.log(response);
                        alert(response);
                        load_cart();
                    }
                });
            });

            $('body').delegate('.cust-cont', 'keyup', function(e){
                e.preventDefault();
                var value=$(this).val();
                $('.cust-whats').val(value);
            });

            $('body').delegate('.save-ag-cust-sale', 'click', function(e){
                e.preventDefault();
                var js_cust_name=$('.cust-name').val();
                var js_cust_cont=$('.cust-cont').val();
                var js_cust_address=$('.cust-address').val();
                var js_cust_whatsapp=$('.cust-whats').val();
                $.ajax({
                    url : 'sparda/stc_sale_cust.php',
                    method : 'POST',
                    data : {
                        cust_goes_on:1,
                        cust_name:js_cust_name,
                        cust_cont:js_cust_cont,
                        cust_address:js_cust_address,
                        cust_whatsapp:js_cust_whatsapp
                    },
                    success : function(response){
                        console.log(response);
                        alert(response);
                    }
                });
            });
        });
    </script>
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
