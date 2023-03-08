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

    .modal-backdrop{
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
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Agent Order</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#check-order" role="tab" aria-controls="check-order" aria-selected="true">Check Orders</a>
              <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#check-requisition" role="tab" aria-controls="check-requisition" aria-selected="false">Check Requisitions</a>
            </div>
          </nav>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="check-order" role="tabpanel" aria-labelledby="nav-home-tab">
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
                          id="order-challan"
                          name="stcmername"
                          type="text"
                          placeholder="Purchase Order Number"
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
                    <div class="col-xl-12 col-md-12 col-sm-12">
                      <div class="form-group mb-3">
                        <label
                          for="name"
                          >Customer Name
                        </label>
                        <input
                          id="cust_name"
                          name="cust_name"
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
                          <label>Customer Agent Id</label>
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
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>Refrence</label>
                          <input
                            id="ag-refrence"
                            name="ag-refrence"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                            disabled
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>Site</label>
                          <input
                            id="site-name"
                            name="site-name"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                            disabled
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>Site Address</label>
                          <textarea 
                            class="form-control"
                            id="site-address"
                            name="site-address"
                            style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                            rows="1" 
                            disabled
                          ></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>Way Bill No</label>
                          <input
                            id="ag-way-bill-no"
                            name="ag-way-bill-no"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            placeholder="Enter Way Boll No"
                            value="NA"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>LR No</label>
                          <input
                            id="ag-lr-no"
                            name="ag-lr-no"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            placeholder="Enter LR No"
                            value="NA"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group mb-3">
                        <label
                          for=""
                          >Notes
                        </label>
                        <textarea
                          class="form-control validate"
                          rows="2"
                          id="ag-notes"
                          placeholder="Notes"
                        ></textarea>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group mb-3">
                        <label
                          for=""
                          >Terms & Conditions
                        </label>
                        <textarea
                          class="form-control validate"
                          rows="2"
                          id="ag-tandc"
                          placeholder="Terms & Conditions"
                        ></textarea>
                        <input type="hidden" id="stc-proj-id">
                        <input type="hidden" id="stc-super-id">
                        <input type="hidden" id="stc-requis-id">
                      </div>
                    </div>

                    <!-- line item table -->
                    <div class="col-xl-12 col-md-12 col-sm-12 ag-order-items" style="width: auto;overflow-x: auto; white-space: nowrap;">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <button class="btn btn-primary form-control set-to-cleaned">Cleaned</button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <button class="btn btn-primary form-control save-ag-challan">Save Challan</button>
                    </div>
                  </div>
                </form>
                </div>
              </div>

              <!-- view Purchase Orders -->
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
                                <th scope="col" width="50%">By Order Number/ Customer/ Agents/ Status</th>
                                <th scope="col">Search</th>
                                <th scope="col">By Products</th>
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
                                  <!-- <input type="text" id="tags" class="form-control" data-role="tagsinput" /> -->
                                </td>
                                <td>
                                  <button type="button" name="search" class="btn btn-primary" id="ag-search">Search</button>
                                </td>
                                <td>
                                  <input
                                    id="stcfilterbypdname"
                                    type="text"
                                    placeholder="Product Name"
                                    class="form-control validate"
                                    required
                                  />
                                </td>
                              </tr>
                            </tbody>
                          </table>
                      </form>
                    </div>
                  </div>
                  <div class="row stc-view-purchase-row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                      <form action="" class="stc-view-ag-order-form">
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

                  <div class="row stc-call-view-Merchant-row">
                    <span id="total_records"></span>
                  </div>
                </div>
              </div> 
            </div>
            <div class="tab-pane fade" id="check-requisition" role="tabpanel" aria-labelledby="nav-profile-tab">
              <!-- modal call beg -->
              <div class="modal fade bd-example-modal-lg req-product-Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
                id="reqproductModal">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add Product</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                          <input type="hidden" id="stc-req-list-item-id">
                          <input type="hidden" id="stc-req-list-id">
                          <div class="row">
                            <div class="col-10">
                              <input type="text" class="form-control prodsearchtxt" placeholder="Enter product name">
                            </div>
                            <div class="col-2">
                              <a href="#" class="form-control btn btn-primary prodbtnsearch">Search <i class="fa fa-search"></i></a>
                            </div>
                            <div class="col-12">
                              <div class="row stc-req-product-show">
                                
                              </div>
                            </div>
                          </div>   
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-req-product-sess-show">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- modal call end -->
              <!-- Create order -->
              <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-req-add">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                  <div class="row">
                    <div class="col-12">
                      <h2 class="tm-block-title d-inline-block">Requisition Point</h2> <a id="stc_req_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
                    </div>
                  </div>
                  <div class="row stc-agent-requistion-row">
                    <div class="col-xl-6 col-md-6 col-sm-12">
                      <form action="" class="stc-add-po-product-form"> 
                      <div class="form-group mb-3">
                        <label
                          for="name"
                          >Order Number
                        </label>
                        <input
                          id="order-req-nos"
                          name="stcmername"
                          type="text"
                          placeholder="Requisition Number"
                          class="form-control validate"
                          style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                          disabled
                        />
                        <input type="hidden" id="ag-challan-req">
                      </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-12">
                      <div class="form-group mb-3">
                        <label
                          for=""
                          >Order Date
                        </label>
                        <input
                          id="ag-req-date"
                          name="ag-req-date"
                          type="text"
                          class="form-control validate"
                          data-large-mode="true"
                          style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                          disabled
                        />
                      </div>
                    </div>
                    <div class="col-xl-12 col-md-12 col-sm-12">
                      <div class="form-group mb-3">
                        <label
                          for="name"
                          >Customer Name
                        </label>
                        <input
                          id="cust_name_req"
                          name="cust_name_req"
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
                          <label>Customer Agent Id</label>
                          <input
                            id="ag-req-info"
                            name="ag-req-info"
                            type="text"
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
                          <label>Refrence</label>
                          <input
                            id="ag-req-refrence"
                            name="ag-req-refrence"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                            disabled
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>Site</label>
                          <input
                            id="req-site-name"
                            name="req-site-name"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                            disabled
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>Site Address</label>
                          <textarea 
                            class="form-control"
                            id="site-req-address"
                            name="site-req-address"
                            style="background: #1ad24a;font-size: 20px;font-weight: bold;color: black;"
                            rows="1" 
                            disabled
                          ></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>Way Bill No</label>
                          <input
                            id="ag-req-way-bill-no"
                            name="ag-req-way-bill-no"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            placeholder="Enter Way Boll No"
                            value="NA"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="page-title-right">
                        <div class="form-group mb-3">
                          <label>LR No</label>
                          <input
                            id="ag-req-lr-no"
                            name="ag-req-lr-no"
                            type="text"
                            class="form-control validate"
                            data-large-mode="true"
                            placeholder="Enter LR No"
                            value="NA"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group mb-3">
                        <label
                          for=""
                          >Notes
                        </label>
                        <textarea
                          class="form-control validate"
                          rows="2"
                          id="ag-req-notes"
                          placeholder="Notes"
                        ></textarea>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group mb-3">
                        <label
                          for=""
                          >Terms & Conditions
                        </label>
                        <textarea
                          class="form-control validate"
                          rows="2"
                          id="ag-req-tandc"
                          placeholder="Terms & Conditions"
                        ></textarea>
                        <input type="hidden" id="stc-req-proj-id">
                        <input type="hidden" id="stc-req-super-id">
                        <input type="hidden" id="stc-req-requis-id">
                      </div>
                    </div>

                    <!-- line item table -->
                    <div class="col-xl-12 col-md-12 col-sm-12 ag-requis-items" style="width: auto;overflow-x: auto; white-space: nowrap;">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <button class="btn btn-primary form-control set-req-to-cleaned">Cleaned</button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <button class="btn btn-primary form-control save-ag-req-challan">Save Challan</button>
                    </div>
                  </div>
                </form>
                </div>
              </div>

              <!-- view Purchase Requisition -->
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-req-view">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="overflow-x: auto; white-space: nowrap;">
                  <div class="row">
                    <div class="col-12">
                      <h2 class="tm-block-title d-inline-block">Requisition</h2>
                    </div>
                  </div>
                  <div class="row stc-view-product-row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                      <form action="" class="stc-view-product-form">
                          <table class="table table-hover ">
                            <thead>
                              <tr>
                                <th scope="col" width="100%">By Requisition Number/ Customer/ Agents/ Status</th>
                                <th scope="col">Search</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <input
                                    id="ag-req-accept-tags"
                                    data-role="tagsinput"
                                    type="text"
                                    placeholder="Agent Requisition Number/ Customer/ Status"
                                    class="form-control validate stcfilterbyponumber"
                                    required
                                  />
                                </td>
                                <td>
                                  <button type="button" class="btn btn-primary" id="ag-req-accept-tags-search">Search</button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                      </form>
                    </div>
                  </div>
                  <div class="row stc-view-purchase-row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                      <form action="" class="stc-view-ag-requisition-form">
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
          // $('.stc-add-po-product-form')[0].reset();
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
      $(document).ready(function(){
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-req-add').toggle(1000);
          $('.stc-req-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
        });

        $('#stc_req_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-req-view').toggle(1000);
            $('.stc-req-add').fadeOut(500);
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

        $('body').delegate('#ag-req-accept-tags-search', 'click', function(e){
          var querybox=$('#ag-req-accept-tags').val();
          $.ajax({
            url:"asgard/mjolnirgetorder.php",
            method:"POST",
            data:{
              stcagentquery:1,
              querybox:querybox
            },
            // dataType:"json",
            success:function(data){
              // console.log(data);
             $('.stc-view-ag-requisition-form').html(data);
            }
          });
        });

        function load_ag_order(query) {
          $.ajax({
            url:"asgard/mjolnirgetorder.php",
            method:"POST",
            data:{stcfilteragord:query},
            dataType:"json",
            success:function(data){
              // console.log(data);
             $('.stc-view-ag-order-form').html(data);
            }
          });
        }

        $('#ag-search').click(function(){
          var query = $('#ag-tags').val();
          load_ag_order(query);
        });

        function load_ag_requisition(requist) {
          $.ajax({
            url:"asgard/mjolnirgetorder.php",
            method:"POST",
            data:{stcfilteragrequis:requist},
            dataType:"json",
            success:function(requist){
              // console.log(data);
             $('.stc-view-ag-requisition-form').html(requist);
            }
          });
        }

        $('#ag-req-search').click(function(){
          var requist = $('#ag-req-tags').val();
          load_ag_requisition(requist);
        });

        $(document).on('click', '.req-product-Modal', function(e){
          e.preventDefault();
          $('#stc-req-list-id').val($(this).attr("id"));
          $('#stc-req-list-item-id').val($(this).attr("list-id"));
          $('#reqproductModal').modal("show");
        });

        $('body').delegate('.prodbtnsearch', 'click', function(e){
          e.preventDefault();
          $('.stc-req-product-show').html("Loading...");
          var product_name=$('.prodsearchtxt').val();
          $.ajax({
            url       : "asgard/mjolnirgetorder.php",
            method    : "POST",
            data      : {
              js_go_for_pro_action:1,
              product_name:product_name
            },
            dataType  : 'JSON',
            success   : function(products){
              $('.stc-req-product-show').html(products);
            }
          });
        });

        show_requisition_cart();
        function show_requisition_cart(){
          $.ajax({
            url       : "asgard/mjolnirgetorder.php",
            method    : "POST",
            data      : {
              js_show_requi_cart:1
            },
            // dataType  : "JSON",
            success   : function(requition_pro){
              // console.log(requition_pro);
              $('.stc-req-product-sess-show').html(requition_pro);
            }
          });
        }

        $('body').delegate('.add_to_requist_cart', 'click', function(e){
          e.preventDefault();
          var product_id=$(this).attr("id");
          var list_id=$('#stc-req-list-id').val();
          var product_item_tab_id=$('#stc-req-list-item-id').val();
          var product_quantity=$('#stcreqqty'+product_id).val();
          var product_price=$('#stcpdreqprice'+product_id).val();
          var js_requi_sess="addrequistcart";
          $.ajax({
            url       : "asgard/mjolnirgetorder.php",
            method    : "POST",
            data      : {
              js_requi_sess:js_requi_sess,
              product_id:product_id,
              list_id:list_id,
              product_item_tab_id:product_item_tab_id,
              product_quantity:product_quantity,
              product_price:product_price
            },
            // dataType  : "JSON",
            success   : function(product_sesso){
              alert(product_sesso);
              show_requisition_cart();
            }
          });
        });

        $('body').delegate('.stcdelrequibtn', 'click', function(e){
          e.preventDefault();
          var product_id=$(this).attr("id");
          $.ajax({
            url       : "asgard/mjolnirgetorder.php",
            method    : "POST",
            data      : {
              js_req_delitems:1,
              product_id:product_id
            },
            // dataType  : "JSON",
            success   : function(product_sesso){
              alert(product_sesso);
              show_requisition_cart();
            }
          });
        });

        $('body').delegate('.save-ag-req-challan', 'click', function(e){
          e.preventDefault();
          var stc_ch_id=$('#ag-challan-req').val();
          var stc_ch_notes=$('#ag-req-notes').val();
          var stc_ch_tandc=$('#ag-req-tandc').val();
          var stc_ch_waybilno=$('#ag-req-way-bill-no').val();
          var stc_ch_lrno=$('#ag-req-lr-no').val();
          var proj_id=$('#stc-req-proj-id').val();
          var super_id=$('#stc-req-super-id').val();
          var requise_id=$('#stc-req-requis-id').val();
          $.ajax({
            url       : "asgard/mjolnirgetorder.php",
              method    :'POST',
              data    :{
                go_to_req_challan:1,
                stc_ch_id:stc_ch_id,
                stc_ch_notes:stc_ch_notes,
                stc_ch_tandc:stc_ch_tandc,
                stc_ch_waybilno:stc_ch_waybilno,
                stc_ch_lrno:stc_ch_lrno,
                proj_id:proj_id, 
                super_id:super_id,
                requise_id:requise_id
              },
              // dataType  : 'JSON',
              success:function(response){
                // console.log(response);
                alert(response);
                show_requisition_cart();
                load_ag_requisition();
                $('.stc-req-add').fadeOut(1000);
                $('.stc-req-view').toggle(500);
                // window.location.reload();
              }
          });
        });

        $('body').delegate('.set-req-to-cleaned', 'click', function(e){
          e.preventDefault();
          var stc_req_no=$('#ag-challan-req').val();
          $.ajax({
              url     :"asgard/mjolnirgetorder.php",
              method    :'POST',
              data    :{
                req_set_to_clean:1,
                stc_req_no:stc_req_no
              },
              dataType  : 'JSON',
              success:function(response){
                // console.log(response);
                alert(response);
                load_ag_requisition();
                $('.stc-req-add').fadeOut(1000);
                $('.stc-req-view').toggle(500);
              }
          });
        });
      });
    </script>
  </body>
</html>