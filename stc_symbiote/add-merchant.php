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
    <title>Add Merchant - STC</title>
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
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Add Merchant</a></li>
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
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> Add NEW Merchant</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase view-combo" value=""> View All Merchants</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- add Merchant -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Add Merchant</h2>
              </div>
            </div>
            <div class="row tm-edit-Merchant-row">
              <div class="col-xl-6 col-lg-6 col-md-12">
                <form action="" class="stc-add-merchant-form">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Merchant Name
                    </label>
                    <input
                      id="name"
                      name="stcmername"
                      type="text"
                      placeholder="Merchant Name"
                      class="form-control validate"
                      required
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="address"
                      >Merchant Address</label
                    >
                    <textarea
                      class="form-control validate"
                      rows="2"
                      name="stcmeraddress"
                      placeholder="Merchant Name"
                      required
                    ></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="city"
                      >Merchant City</label
                    >
                    <select
                      class="custom-select tm-select-accounts call_city"
                      name="stcmercity"
                    >
                    </select>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="contactperson"
                      >Contact Person
                    </label>
                    <input
                      id="name"
                      name="stcmercontperson"
                      type="text"
                      placeholder="Merchant Contact Person"
                      class="form-control validate"
                      required
                    />
                  </div>
                  
                  <div class="form-group mb-3">
                    <label
                      for="rack"
                      >Merchant Contact Number</label
                    >
                    <input
                      id="name"
                      name="stcmercontnumber"
                      type="number"
                      placeholder="Merchant Contact Number"
                      class="form-control validate"
                      required
                    />
                    </select>

                    <input type="hidden" name="stc_add_merchant_hit">
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="knownfor"
                      >Merchant PAN</label
                    >
                    <input
                      id="name"
                      name="stcmerpan"
                      type="text"
                      placeholder="Merchant PAN"
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
                  <input id="fileInput" type="file" name="stcmerimage" style="display:none;" />
                  <input
                    type="button"
                    class="btn btn-primary btn-block mx-auto"
                    value="UPLOAD MERCHANT IMAGE"
                    onclick="document.getElementById('fileInput').click();"
                  />
                </div>
                <div class="form-group mb-3">
                  <label
                    for="state"
                    >Merchant State</label
                  >
                  <select
                    class="custom-select tm-select-accounts call_state"
                    name="stcmerstate"
                  >
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label
                    for="email"
                    >Merchant Email</label
                  >
                  <input
                    id="name"
                    name="stcmeremail"
                    type="text"
                    placeholder="Merchant Email"
                    class="form-control validate"
                    required
                  />
                </div>
                <div class="form-group mb-3">
                  <label
                    for="knownfor"
                    >Merchant Specially Known For</label
                  >
                  <input
                    id="name"
                    name="stcmerskf"
                    type="text"
                    placeholder="Merchant Specially Known For"
                    class="form-control validate"
                    required
                  />
                </div>
                <div class="form-group mb-3">
                  <label
                    for="knownfor"
                    >Merchant GSTIN</label
                  >
                  <input
                    id="name"
                    name="stcmergstin"
                    type="text"
                    placeholder="Merchant GSTIN"
                    class="form-control validate"
                    required
                  />
                </div>
              </div>
              
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Merchant Now</button>
              </div>
            </form>
            </div>
          </div>
        </div>

        <!-- view Merchant -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">View Merchants</h2>
              </div>
            </div>
            <div class="row stc-view-Merchant-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-Merchant-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">By Name</th>
                          <th scope="col">By Known For</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input
                              id="searchbystcmername"
                              name="stcmername"
                              type="text"
                              placeholder="Merchant Name"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <input
                              id="searchbystcmerskf"
                              name="stcmerskf"
                              type="text"
                              placeholder="Merchant Known FOr"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <!-- <td>28 March 2019</td> -->
                          <!-- <td></td> -->
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>

            <div class="row stc-call-view-Merchant-row">
              
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

        });

        $('.view-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-view').toggle(1000);
           $('.stc-add').fadeOut(500);
        });
      });
    </script>
  </body>
</html>
