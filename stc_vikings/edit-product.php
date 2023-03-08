<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=303;
include("kattegat/role_check.php");
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Product - STC</title>
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
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>                   
            <div class="app-main__outer">
                    <div class="app-main__inner"> 
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Edit Product</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Edit Product
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="" class="stc-edit-product-form">
                                          <?php
                                            include "../MCU/db.php";
                                            if(isset($_GET['pdid'])){
                                              $stcretquery=mysqli_query($con, "
                                                SELECT * FROM `stc_product`
                                                INNER JOIN `stc_category`
                                                ON `stc_product_cat_id`=`stc_cat_id`
                                                INNER JOIN `stc_sub_category`
                                                ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                                                WHERE `stc_product_id`='".$_GET['pdid']."'
                                              ");
                                              $stcretresult=mysqli_fetch_assoc($stcretquery);  
                                            ?>
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="description"
                                              >Product Name
                                            </h5>
                                            <textarea
                                              id="pdname"
                                              name="stcpdname"
                                              rows="6"
                                              placeholder="Product Name"
                                              class="form-control validate"
                                            /><?php echo $stcretresult['stc_product_name'];?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-secondary">
                                            <h5
                                              for="description"
                                              >Description</h5
                                            >
                                            <textarea
                                              class="form-control validate"
                                              rows="6"
                                              name="stcpddesc"
                                              placeholder="Product Name"
                                              required
                                            ><?php echo $stcretresult['stc_product_desc'];?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="category"
                                              >Category : <?php echo $stcretresult['stc_cat_name'];?></h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts call_cat"
                                              name="stcpdcategory"
                                            ></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="subcategory"
                                              >Sub Category : <?php echo $stcretresult['stc_sub_cat_name'];?></h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts call_sub_cat"
                                              name="stcpdsubcategory"
                                            >
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="unit"
                                              >Unit : <?php echo $stcretresult['stc_product_unit'];?></h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts"
                                              name="stcpdunit"
                                            >
                                              <option selected>Select Unit</option>
                                              <option value="Nos">Nos</option>
                                              <option value="Set">Set</option>
                                              <option value="Feet">Feet</option>
                                              <option value="Mtr">Mtr</option>
                                              <option value="Sqmt">Sqmt</option>
                                              <option value="Ltr">Ltr</option>
                                              <option value="Bag">Bag</option>
                                              <option value="Roll">Roll</option>
                                              <option value="Lot">Lot</option>
                                              <option value="Kgs">Kgs</option>
                                              <option value="Pkt">Pkt</option>
                                              <option value="Case">Case</option>
                                              <option value="Bundle">Bundle</option>
                                              <option value="Pair">Pair</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="status"
                                              >GST : <?php echo $stcretresult['stc_product_gst'];?>%</h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts"
                                              name="stcpdgst"
                                            >
                                              <option selected>Select GST</option>
                                              <option value="5">5%</option>
                                              <option value="12">12%</option>
                                              <option value="18">18%</option>
                                              <option value="28">28%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="quantity"
                                            >HSN Code
                                          </h5>
                                          <input
                                            id="name"
                                            name="stcpdhsncode"
                                            type="number"
                                            placeholder="Product Name"
                                            class="form-control validate"
                                            required
                                            value="<?php echo $stcretresult['stc_product_hsncode'];?>"
                                          />
                                        </div>
                                          <input type="hidden" name="stc_edit_product_hit">
                                          <input type="hidden" name="stc_set_product_id" value="<?php echo $stcretresult['stc_product_id'];?>">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="status"
                                              >Percentage</h5
                                            >
                                            <input
                                              id="name"
                                              name="stcpdpercentage"
                                              type="text"
                                              placeholder="Product Percentage"
                                              class="form-control validate"                      
                                              value="<?php echo $stcretresult['stc_product_sale_percentage'];?>"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-primary">
                                            <h5
                                              for="make"
                                              >Make </h5
                                            >
                                            <select
                                              class="custom-select tm-select-accounts call_brand"
                                              name="stcpdbrand"
                                            >
                                            </select>
                                        </div>
                                    </div>
                                          <?php }?>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                          <button type="submit" class="btn btn-primary btn-block text-uppercase">Update Now</button>
                                        </div>
                                        </form>
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
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script>
        $(document).ready(function(){
          $('.stc-edit-product-form').on('submit',function(e){
            e.preventDefault();
            $.ajax({
              url : "kattegat/ragnar_product.php",
              method : "post",
              data : new FormData(this),
              contentType : false,
              cache: false,
              processData : false,
              success : function(data){
                data=data.trim();  
                if(data=="Product Updated Successfully!!!"){
                 $('.stc-edit-product-form')[0].reset();
                }else{
                  alert(data);
                }
                // $('.stc-edit-product-form')[0].reset();
              }
            });
          });
        });
    </script>
</body>
</html>
