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
    <title>Add Product - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
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
   <?php include "header.php";?>

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
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Add Product</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> Add NEW Products</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase view-combo" value=""> View All Products</a></label>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo2" value=""> 
                View product purchase</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase view-combo2" value=""> View product sale</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- add product -->
        <div style="display: none;" class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Add Product</h2>
              </div>
            </div>
            <div class="row tm-edit-product-row">
              <div class="col-xl-6 col-lg-6 col-md-12">
                <form action="" class="stc-add-product-form">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Product Name
                    </label>
                    <textarea
                      class="form-control validate"
                      rows="4"
                      name="stcpdname"
                      placeholder="Product Name"
                      required
                    /></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="description"
                      >Description</label
                    >
                    <textarea
                      class="form-control validate"
                      rows="4"
                      name="stcpddesc"
                      placeholder="Product Description"
                      required
                    ></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="category"
                      >Category</label
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
                      placeholder="Product HSN Code"
                      class="form-control validate"
                      required
                    />
                  </div>
                  
                  <div class="form-group mb-3">

                    <input type="hidden" name="stc_add_product_hit">
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="status"
                      >Percentage</label
                    >
                    <input
                      id="name"
                      name="stcpdpercentage"
                      type="number"
                      placeholder="Product Percentage"
                      class="form-control validate"
                      required
                    />
                  </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                <div class="tm-product-img-dummy mx-auto">
                  <i
                    class="fas fa-cloud-upload-alt tm-upload-icon"
                    onclick="document.getElementById('fileInput').click();"
                  ></i>
                </div>
                <div class="custom-file mt-3 mb-3">
                  <input id="fileInput" type="file" name="stcpdimage" style="display:none;" />
                  <input
                    type="button"
                    class="btn btn-primary btn-block mx-auto"
                    value="UPLOAD PRODUCT IMAGE"
                    onclick="document.getElementById('fileInput').click();"
                  />
                </div>
                <div class="form-group mb-3">
                  <label
                    for="subcategory"
                    >Sub Category</label
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
                    >Unit</label
                  >
                  <select
                    class="custom-select tm-select-accounts"
                    name="stcpdunit"
                  >
                    <option value="NA" selected>Select Unit</option>
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
                    >GST</label
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
                      for="make"
                      >Make </label
                    >
                    <select
                    class="custom-select tm-select-accounts call_brand"
                    name="stcpdbrand"
                  >
                  </select>
                  </div>
              </div>
              
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Product Now</button>
              </div>
              <div class="col-12">
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>

        <!-- view product -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">View Product</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col" colspan="2">By Name</th>
                          <th scope="col">By Rack</th>
                          <th scope="col">By Category</th>
                          <th scope="col">By Sub Category</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input
                              id="searchbystcname"
                              name="stcpdname"
                              type="text"
                              placeholder="Product Name"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <a class="btn btn-success btn-block text-uppercase product-search-hit">
                              Search <i class="fa fa-search"></i>
                            </a>
                          </td>
                          <td>
                            <div class="form-group mb-3">
                              <select
                                class="custom-select tm-select-accounts call_rack"
                                id="filterbyrack"
                                name="stcpdcategory"
                              >
                              </select>
                            </div>
                          </td>
                          <td>
                            <div class="form-group mb-3">
                              <select
                                class="custom-select tm-select-accounts call_cat"
                                id="filterbycat"
                                name="stcpdcategory"
                              >
                              </select>
                            </div>
                          </td>
                          <td>
                            <div class="form-group mb-3">
                              <select
                                class="custom-select tm-select-accounts call_sub_cat"
                                id="filterbysubcat"
                                name="stcpdcategory"
                              >
                              </select>
                            </div>
                          </td>
                          <!-- <td>28 March 2019</td> -->
                          <!-- <td></td> -->
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>

            <div class="row stc-call-view-product-row">
              
            </div>
          </div>
        </div>

        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add2">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">View Single Product Purchased Record</h2>
              </div>
            </div>
            <div class="row stc-view-purhcase-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-purhcase-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col" colspan="2">By Product ID</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input
                              id="searchbystcpdid"
                              name="searchbystcpdid"
                              type="number"
                              placeholder="Product ID"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <a class="btn btn-success btn-block text-uppercase product-id-search-hit">
                              Search <i class="fa fa-search"></i>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>

            <div class="row stc-call-view-product-withid-row">
              
            </div>
          </div>
        </div>

        <div style="display: block;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view2">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">View Single Product Sale Record</h2>
              </div>
            </div>
            <div class="row stc-view-purhcase-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-purhcase-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col" colspan="2">By Product ID</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input
                              id="searchbystcsopdid"
                              name="searchbystcsopdid"
                              type="number"
                              placeholder="Product ID"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <a class="btn btn-success btn-block text-uppercase product-id-sosearch-hit">
                              Search <i class="fa fa-search"></i>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>

            <div class="row stc-call-view-product-sale-withid-row">
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?> 
    <script>
      $(document).ready(function(){
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-add').toggle(1000);
          $('.stc-view').fadeOut(500);
          $('.stc-add2').fadeOut(500);
          $('.stc-view2').fadeOut(500);

        });

        $('.view-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-view').toggle(1000);
           $('.stc-add').fadeOut(500);
           $('.stc-view2').fadeOut(500);
           $('.stc-add2').fadeOut(500);
        });
        
        $('.add-combo2').on('click', function(e){
          e.preventDefault();
          $('.stc-add2').toggle(1000);
           $('.stc-add').fadeOut(500);
           $('.stc-view').fadeOut(500);
           $('.stc-view2').fadeOut(500);
        });

        $('.view-combo2').on('click', function(e){
          e.preventDefault();
          $('.stc-view2').toggle(1000);
           $('.stc-add').fadeOut(500);
           $('.stc-view').fadeOut(500);
           $('.stc-add2').fadeOut(500);
        });
      });
    </script>
  </body>
</html>
