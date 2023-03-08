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
    <title>Agent Order - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <style>
      .bootstrap-tagsinput {
       width: 100%;
       color: blue;
      }
      .bootstrap-tagsinput .tag {
         color: black;
      }

      .search-box,.close-icon,.search-wrapper {
        position: relative;
        padding: 10px;
      }
      .search-wrapper {
        width: 500px;
        margin: auto;
        margin-top: 50px;
      }
      .search-box {
        width: 98%;
        border: 1px solid #ccc;
        outline: 0;
        border-radius: 15px;
      }
      .search-box:focus {
        box-shadow: 0 0 15px 5px #b0e0ee;
        border: 2px solid #bebede;
      }
      .close-icon {
        border:1px solid transparent;
        background-color: transparent;
        display: inline-block;
        vertical-align: middle;
        outline: 0;
        cursor: pointer;
      }
      .close-icon:after {
        content: "X";
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: #FA9595;
        z-index:1;
        right: 35px;
        top: 0;
        bottom: 0;
        margin: auto;
        padding: 0px;
        border-radius: 50%;
        text-align: center;
        color: white;
        font-weight: normal;
        font-size: 12px;
        box-shadow: 0 0 2px #E50F0F;
        cursor: pointer;
      }
      .search-box:not(:valid) ~ .close-icon {
        display: none;
      }
    </style>
  </head>

  <body>
        <nav class="navbar navbar-expand-xl">
          <div class="container h-100">
            <a class="navbar-brand animated bounceInRight" href="index.html">
              <img style="width: 50%;border-radius: 34px;" src="img/stc_logo.png">
            </a>
            <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars tm-nav-icon"></i>
            </button>
            <div class="collapse navbar-collapse animated bounceInDown" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto h-100">
                  <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                      <i class="fas fa-tachometer-alt"></i>
                      Dashboard  
                      <span class="sr-only">(current)</span>            
                    </a>
                  </li>                        
                  <li class="nav-item">
                    <a class="nav-link" href="erp.php">
                      <i class="fas fa-cog"></i>
                        ERP 
                          
                    </a>
                  </li>                
                  <li class="nav-item">
                    <a class="nav-link" href="accounts.php">
                      <i class="far fa-user"></i>
                      Accounts
                    </a>
                  </li>
                  <li class="nav-item dropdown">
                  <a href="agent-order.php" class="nav-link active dropdown-toggle">
                      <span style="float: left;position: absolute;top: 20px;left: 40px;font-size: 12px;background: #bb5656;padding: 5px;border-radius: 50%;" class="badge"></span>
                      <i class="far fa-bell"></i>
                    </a>
                  </li>
                </ul>
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link d-block" href="asgard/logout.php">
                      <?php echo @$_SESSION["stc_admin_info_name"];?>, <b>Logout</b>
                    </a>
                  </li>
                </ul>
            </div>
          </div>
        </nav>

    <div class="container-fluid tm-mt-big tm-mb-big animated flip">
      <div class="row ">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Agent Management</a></li>
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
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase">
                  <a href="#" class="btn btn-success btn-block text-uppercase slide-row" value=""> <b>+</b>Create New Agent</a>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Create order -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Order Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-agent-order-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-add-po-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Order Number
                  </label>
                  <input
                    id="ag-onumber"
                    name="stcmername"
                    type="text"
                    placeholder="Cust Order Number"
                    class="form-control validate"
                    style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                    disabled
                  />
                  <input type="hidden" id="ag-challan-order">
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Order Date
                  </label>
                  <input
                    id="ag-order-date"
                    name="ag-order-date"
                    type="text"
                    class="form-control validate"
                    data-large-mode="true"
                    style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="page-title-right">
                  <div class="form-group mb-3">
                    <label>Agent Id</label>
                    <input
                      id="ag-info"
                      name="ag-info"
                      type="text"
                      class="form-control validate"
                      data-large-mode="true"
                      style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                    />
                  </div>
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 ag-order-items" style="width: auto;overflow-x: auto; white-space: nowrap;">
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <button class="btn btn-primary form-control set-o-to-cleaned">Cleaned</button>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <input type="hidden" id="ag-single-number" value="">
                <button class="btn btn-primary form-control save-own-ag-challan">Save Challan</button>
              </div>
            </div>
                </form>
          </div>
        </div>

        <!-- view Agent Orders -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="overflow-x: auto; white-space: nowrap;">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Order</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col" width="50%">By Order Number/ Agents/ Status</th>
                          <th scope="col">Search</th>
                          <th scope="col">Total Agent Sale</th>
                          <th scope="col">Total Paid & <br>Dues</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input
                              id="ag-tags"
                              data-role="tagsinput"
                              type="text"
                              placeholder="Agent Order Number/ Customer/ Status"
                              class="form-control validate stcfilterbyponumber"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="search" class="btn btn-primary" id="ag-search">Search</button>
                          </td>
                          <td>
                            <label class="btn btn-primary tot-ag-sale-amt" style="background-color: #219628">
                              <i class="fas fa-rupee"></i>
                            </label>
                          </td>
                          <td>
                            <label class="btn btn-info">5400</label></br>
                            <label class="btn btn-danger">5400</label>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row stc-view-purchase-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-own-ag-order-form">
                    <table class="table table-hover ">
                      <tr>
                        <td>
                          Loading.....
                        </td>
                      </tr>
                    </table>
                </form>
              </div>
            </div>
          </div>
        </div> 

        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-cagentv">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Agent Creation</h2> <a id="stc_agup" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-agent-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-add-create-agent-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Agent Name
                  </label>
                  <input
                    id="cr-ag-name"
                    name="cr-ag-name"
                    type="text"
                    placeholder="Enter customer name"
                    class="form-control validate"
                    style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Agent Email
                  </label>
                  <input
                    id="cr-ag-email"
                    name="cr-ag-email"
                    type="email"
                    placeholder="Enter email address"
                    class="form-control validate"
                    data-large-mode="true"
                    style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="page-title-right">
                  <div class="form-group mb-3">
                    <label>Agent Contact</label>
                    <input
                      id="cr-ag-contact"
                      name="cr-ag-contact"
                      type="number"
                      placeholder="Enter phone number"
                      class="form-control validate"
                      data-large-mode="true"
                      style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                    />
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="page-title-right">
                  <div class="form-group mb-3">
                    <label>Agent Address</label>
                    <textarea 
                      name="cr-ag-address" 
                      class="form-control validate"
                      placeholder="Enter address"
                      >
                    </textarea>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="page-title-right">
                  <div class="form-group mb-3">
                    <label>Agent State</label>
                    <select class="form-control" name="cr-ag-state" id="cr-ag-state">
                    <?php
                      include("../MCU/db.php");
                      $stateqry=mysqli_query($con, "SELECT * FROM `stc_state`");
                      foreach($stateqry as $staterow){
                        echo '<option value="'.$staterow['stc_state_id'].'">'.$staterow['stc_state_name'].'</option>';
                      }
                    ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="page-title-right">
                  <div class="form-group mb-3">
                    <label>Agent City</label>
                    <select class="form-control" name="cr-ag-city" id="cr-ag-city">
                    <?php
                      include("../MCU/db.php");
                      $stateqry=mysqli_query($con, "SELECT * FROM `stc_city`");
                      foreach($stateqry as $staterow){
                        echo '<option value="'.$staterow['stc_city_id'].'">'.$staterow['stc_city_name'].'</option>';
                      }
                    ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="page-title-right">
                  <div class="form-group mb-3">
                    <label>Agent Pincode</label>
                    <input
                      id="cr-ag-pincode"
                      name="cr-ag-pincode"
                      type="text"
                      class="form-control validate"
                      placeholder="Enter pincode"
                      data-large-mode="true"
                      style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                    />
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="page-title-right">
                  <div class="form-group mb-3">
                    <label>Agent Image</label>
                    <input
                      id="cr-ag-image"
                      name="cr-ag-image"
                      type="file"
                      class="form-control"
                      placeholder="Image format"
                    />
                  </div>
                </div>
              </div>

              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <input type="hidden" name="ag-create-perticular">
                <button class="btn btn-primary form-control save-own-ag-record">Save</button>
              </div>
            </div>
                </form>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.slide-row').on('click', function(e){
          e.preventDefault();
          $('.stc-add').hide(500);
          $('.stc-view').hide(500);
          $('.stc-cagentv').toggle(500);
        });
        
        $('#stc_agup').on('click', function(e){
          e.preventDefault();
            $('.stc-cagentv').toggle(1000);
            $('.stc-view').show(500);
        });

        $('#stc_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-view').toggle(1000);
            $('.stc-add').fadeOut(500);
        });

        $('.upward').on('click', function(e){
          e.preventDefault();
            $('.downward').toggle(500);
            $('.thishypo').fadeOut(500);
            $('.upward').fadeOut(500);
        });

        $('.downward').on('click', function(e){
          e.preventDefault();
            $('.upward').toggle(500);
            $('.thishypo').toggle(500);
            $('.downward').fadeOut(500);
        });
      });
    </script>
    <script>
      $(document).ready(function(){
        // load Challan
        // load_data();
        function load_ag_order(query) {
          $.ajax({
            url:"asgard/mjolnirownagorder.php",
            method:"POST",
            data:{stcfilteragord:query},
            dataType:"json",
            success:function(data){
              console.log(data);
             $('.stc-view-own-ag-order-form').html(data['ag_all_record']);
             $('.tot-ag-sale-amt').html(data['total_value']);
            }
          });
        }

        $('#ag-search').click(function(){
          var query = $('#ag-tags').val();
          load_ag_order(query);
        });

        $('.stc-add-create-agent-form').on('submit', function(e){
          e.preventDefault();
          $.ajax({
            url : "asgard/mjolnirownagorder.php",
            method : "post",
            data : new FormData(this),
            contentType : false,
            processData : false,
            // dataType : 'JSON',
            success : function (argument) {
              // console.log(argument);
                alert(argument);
              $('.stc-add-create-agent-form')[0].reset();
            }
          });
        });
      });
    </script>
  </body>
</html>
