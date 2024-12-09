<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=308;
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
    <title>Add Product Supports - STC</title>
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
                                <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Add Category</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Add Sub Category</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>Add Brand</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                                    <span>Add Rack</span>
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
                                              >Add Category
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 tm-block-col">
                                      <form action="" class="stc-add-category-form">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="name"
                                            >Category Name
                                          </h5>
                                          <input
                                            id="name"
                                            name="category_name"
                                            type="text"
                                            class="form-control validate"
                                            placeholder="Enter Category Name"
                                            required
                                          />
                                          <input type="hidden" name="category_hit">
                                          <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Category Now</button>
                                          <div> <h5 class="form-control" id="carshow" style="display: none;"></h5></div>
                                        </div>                                        
                                      </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Add Sub Category
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 tm-block-col">
                                      <form action="" class="stc-sub-category-form">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="name"
                                            >Sub Category Name
                                          </h5>
                                          <input
                                            id="name"
                                            name="sub_category_name"
                                            type="text"
                                            class="form-control validate"
                                            placeholder="Enter Sub Category Name"
                                            required
                                          />
                                          <input type="hidden" name="sub_category_hit">
                                          <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Sub Category Now</button>
                                          <div> <h5 class="form-control" id="subcatshow" style="display: none;"></h5></div>
                                        </div>
                                        
                                      </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                              <div class="row">
                                <div class="col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="description" align="center"
                                          >Add Brand
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 tm-block-col">
                                  <form action="" class="stc-brand-form">
                                    <div class="card-border mb-3 card card-body border-primary">
                                      <h5
                                        for="name"
                                        >Brand Name
                                      </h5>
                                      <input
                                        id="name"
                                        name="brand_name"
                                        type="text"
                                        class="form-control validate"
                                        placeholder="Enter Brand Name"
                                        required
                                      />
                                      <input type="hidden" name="brand_hit">
                                      <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Brand</button>
                                      <div> <h5 class="form-control" id="brandshow" style="display: none;"></h5></div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Add Rack
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 tm-block-col">
                                    <form action="" class="stc-rack-form">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="name"
                                          >Rack Name
                                        </h5>
                                        <input
                                          id="name"
                                          name="rack_name"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Enter Rack Name"
                                          required
                                        />
                                        <input type="hidden" name="rack_hit">
                                        <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Rack Now</button>
                                      </div>
                                      <div> <h5 class="form-control" id="rackshow" style="display: none;"></h5></div>
                                    </form>
                                  </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <table class="table table-bordered">
                                            <tbody>
                                              <?php 
                                                include_once("../MCU/db.php");

                                                $query = mysqli_query($con, "SELECT stc_rack_id, stc_rack_name FROM stc_rack ORDER BY LEFT(stc_rack_name, 1) ASC, CAST(SUBSTRING(stc_rack_name, 2) AS UNSIGNED) ASC");

                                                $columnCount = 20; // Number of columns per row
                                                $counter = 0; // Initialize counter

                                                $data = '<tr>'; // Start the first row
                                                foreach ($query as $row) {
                                                  $quer_nested=mysqli_query($con, "SELECT DISTINCT `stc_purchase_product_adhoc_itemdesc` FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_rackid`='".$row['stc_rack_id']."' AND `stc_purchase_product_adhoc_status`=1");
                                                  $style="style='Background-color: #ff6565;'";
                                                  $productsContainer='';
                                                  if(mysqli_num_rows($quer_nested)>0){
                                                    foreach($quer_nested as $quer_row){
                                                      $productsContainer.=$productsContainer!=""?"</br>".$quer_row['stc_purchase_product_adhoc_itemdesc']:$quer_row['stc_purchase_product_adhoc_itemdesc'];
                                                    }
                                                    $style='style="Background-color:#76ff76;" data-toggle="modal" data-target=".bd-modal-showproductdetails"';
                                                  }
                                                  $data .= '<td class="text-center tdclick" '.$style.'><b>' . $row['stc_rack_name'] . '</b><span style="display:none;" class="tdspanproducts">'.$productsContainer.'</span></td>';
                                                  $counter++;

                                                  // Start a new row after 10 columns
                                                  if ($counter % $columnCount == 0) {
                                                    $data .= '</tr><tr>'; // Close current row and start a new one
                                                  }
                                                }

                                                // Close the last row if it's not a complete set of columns
                                                if ($counter % $columnCount != 0) {
                                                  $data .= '</tr>';
                                                }

                                                echo $data;

                                              ?>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>  
                </div>
        </div>
    </div>
    <div class="modal fade bd-modal-showproductdetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-s ">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Show Products Detail</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card-border mb-3 card card-body border-success">
                      <p>Products in Rack <span class="showrack_name"></span>: <br><span class='showproducts' style="font-size:20px; font-weight:bold"></span></p>
                    </div>
                  </div>  
                </div>
              </div>
              <div class="modal-footer">
                <div class="row">
                  <div class="col-xl-6 col-md-6 col-sm-6">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
          // Attach event handler
          $('body').on('click', '.tdclick', function (e) {
              e.preventDefault();
              var rack_name=$(this).find('b').html();
              // Retrieve the text from the .tdspanproducts element
              var products_name = $(this).find('.tdspanproducts').html();

              // Update the .showproducts element's content
              $('.showrack_name').html('<b>' + rack_name + '</b>');
              $('.showproducts').html(products_name);
          });

          // rack directions
          $('.stc-rack-form').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url             : "kattegat/ragnar_product.php",
              method          : "post",
              data            : new FormData(this),
              contentType     : false,
              processData     : false,
              dataType        : 'JSON',
              success         : function (argument) {
                $('#rackshow').html(argument);
                $("#rackshow").show().delay(3000).fadeOut();
                $('.stc-rack-form')[0].reset();
                stc_product_reload();
              }
            });   
          });

          // category direction
          $('.stc-add-category-form').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url             : "kattegat/ragnar_product.php",
              method          : "post",
              data            : new FormData(this),
              contentType     : false,
              processData     : false,
              dataType        : 'JSON',
              success         : function (argument) {
                $('#carshow').html(argument);
                $("#carshow").show().delay(3000).fadeOut();
                $('.stc-add-category-form')[0].reset();
                stc_product_reload();
              }
            });   
          });

          // sub category direction
          $('.stc-sub-category-form').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url             : "kattegat/ragnar_product.php",
              method          : "post",
              data            : new FormData(this),
              contentType     : false,
              processData     : false,
              dataType        : 'JSON',
              success         : function (argument) {
                $('#subcatshow').html(argument);
                $("#subcatshow").show().delay(3000).fadeOut();
                $('.stc-sub-category-form')[0].reset();
                stc_product_reload();
              }
            });   
          }); 

          // brand direction
          $('.stc-brand-form').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url             : "kattegat/ragnar_product.php",
              method          : "post",
              data            : new FormData(this),
              contentType     : false,
              processData     : false,
              dataType        : 'JSON',
              success         : function (argument) {
                $('#brandshow').html(argument);
                $("#brandshow").show().delay(3000).fadeOut();
                $('.stc-brand-form')[0].reset();
                stc_product_reload();
              }
            });   
          }); 
        });
    </script>
</body>
</html>
