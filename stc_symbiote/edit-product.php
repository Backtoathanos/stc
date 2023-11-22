<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_admin_info_id'])){
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Product - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700"
    />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  </head>

  <body>
   <?php include"header.php";?>
    <div class="container tm-mt-big tm-mb-big">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="add-product.php" class="active" value="">Add Product</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Edit Product</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Edit Product</h2>
              </div>
            </div>
            <div class="row stc-edit-product-row">
              <div class="col-xl-6 col-lg-6 col-md-12">
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
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Product Name - <span></span>
                    </label>
                    <textarea
                      id="pdname"
                      name="stcpdname"
                      rows="6"
                      placeholder="Product Name"
                      class="form-control validate"
                    /><?php echo $stcretresult['stc_product_name'];?></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="description"
                      >Description</label
                    >
                    <textarea
                      class="form-control validate"
                      rows="6"
                      name="stcpddesc"
                      placeholder="Product Name"
                      required
                    ><?php echo $stcretresult['stc_product_desc'];?></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="category"
                      >Category : <?php echo $stcretresult['stc_cat_name'];?></label
                    >
                    <select
                      class="custom-select tm-select-accounts call_cat"
                      name="stcpdcategory"
                    >
                    </select>
                  </div>   
                  <div class="form-group mb-3">
                    <label
                      for="quantity"
                      >HSN Code
                    </label>
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
                  <div class="form-group mb-3">

                    <input type="hidden" name="stc_edit_product_hit">
                    <input type="hidden" name="stc_set_product_id" value="<?php echo $stcretresult['stc_product_id'];?>">
                  </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                <div class="stc-product-img-dummy mx-auto stc-edit-page" style="height: 25%">
                  <img src="stc_product_image/<?php echo $stcretresult['stc_product_image'];?>">
                </div>
                <div class="form-group mb-3">
                  <label
                    for="subcategory"
                    >Sub Category : <?php echo $stcretresult['stc_sub_cat_name'];?></label
                  >
                  <select
                    class="custom-select tm-select-accounts call_sub_cat"
                    name="stcpdsubcategory"
                  >
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label
                    for="unit"
                    >Unit : <?php echo $stcretresult['stc_product_unit'];?></label
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
                <div class="form-group mb-3">
                  <label
                    for="status"
                    >GST : <?php echo $stcretresult['stc_product_gst'];?>%</label
                  >
                  <select
                    class="custom-select tm-select-accounts"
                    name="stcpdgst"
                  >
                    <option selected>Select GST</option>
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="12">12%</option>
                    <option value="18">18%</option>
                    <option value="28">28%</option>
                  </select>
                </div>
                  <div class="form-group mb-3">
                    <label
                      for="status"
                      >Percentage</label
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
                  <div class="form-group mb-3">
                    <label
                      for="brand"
                      >Brand : </label
                    >
                    <select
                      class="custom-select tm-select-accounts call_brand"
                      name="stcpdbrand"
                    >
                    </select>
                  </div>
              </div>
              <?php }?>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase">Update Now</button>
              </div>
              <div class="col-12">
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?>
    <script>
      $(function() {
        $("#expire_date").datepicker({
          defaultDate: "10/22/2020"
        });
      });
    </script>
  </body>
</html>
