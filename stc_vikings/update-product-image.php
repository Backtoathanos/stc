<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=309;
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
    <style>
      .stc-purchase-view-table th{
        text-align: center;
      }

      .stc-purchase-view-table{
        font-size: 10px;
      }

      .stc-purchase-view-table td p{
        font-size: 10px;
      }
      
      .fade:not(.show) {
        opacity: 10;
      }

      /* Toolbar under filters — avoid inline label + select overlap */
      .stc-pdimg-toolbar {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #e8e8e8;
      }
      /*
       * style.css sets ALL labels to position:absolute (signup forms). That stacks
       * "Page size" on top of the <select>. Reset labels inside this form only.
       */
      .stc-view-product-form .stc-pdimg-toolbar label {
        position: static !important;
        left: auto !important;
        top: auto !important;
        right: auto !important;
        transform: none !important;
        -webkit-transform: none !important;
        -moz-transform: none !important;
        -ms-transform: none !important;
        -o-transform: none !important;
        width: auto !important;
        height: auto !important;
        margin-top: 0 !important;
      }
      .stc-view-product-form .stc-pdimg-toolbar .form-group {
        overflow: visible !important;
        position: static;
      }
      .stc-pdimg-toolbar .checkbox {
        margin: 10px 0 0;
      }
      .stc-pdimg-toolbar .checkbox label {
        font-weight: normal;
        font-size: 13px;
        cursor: pointer;
        display: inline-block;
      }
      .stc-pdimg-toolbar .form-group {
        margin-bottom: 0;
      }
      .stc-pdimg-toolbar .control-label.stc-pdimg-compact-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
      }
      .stc-pdimg-toolbar select#pdimg_per_page {
        display: block;
        width: 100%;
        max-width: 110px;
        height: 34px;
        line-height: 1.42857143;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        box-sizing: border-box;
      }
      .stc-pdimg-toolbar .stc-pdimg-search-wrap {
        padding-top: 22px;
        text-align: right;
      }
      @media (max-width: 767px) {
        .stc-pdimg-toolbar .stc-pdimg-search-wrap {
          padding-top: 12px;
          text-align: left;
        }
        .stc-pdimg-toolbar select#pdimg_per_page {
          max-width: none;
        }
      }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>                   
            <div class="app-main__outer">
              <div class="app-main__inner"> 
                  <div class="row">
                      <div class="col-md-12">
                          <div class="card-border mb-3 card card-body border-success">
                              <h5
                                for="description" align="center"
                                >Update Product Image
                              </h5>
                          </div>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12">
                          <div class="card-border mb-3 card card-body border-success">
                              <form action="" class="stc-view-product-form">
                                  <table class="table table-hover ">
                                      <thead>
                                          <tr>
                                              <th scope="col">By Category</th>
                                              <th scope="col">By Name</th>
                                              <th scope="col">By Sub Category</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td>
                                                  <div class="card-border mb-3 card card-body border-success">
                                                    <select
                                                      class="custom-select form-control tm-select-accounts call_cat stcprosearchsame"
                                                      id="filterbycat"
                                                      name="stcpdcategory"
                                                    >
                                                    </select>
                                                  </div>
                                              </td>
                                              <td>
                                                  <div class="card-border mb-3 card card-body border-success">
                                                    <input
                                                      id="searchbystcname"
                                                      name="stcsearchpdname"
                                                      type="text"
                                                      placeholder="Product Name"
                                                      class="form-control validate stcprosearchsame"
                                                    />
                                                    <input type="hidden" name="search_alo_in">
                                                  </div>
                                              </td>
                                              <td>
                                                  <div class="card-border mb-3 card card-body border-success">
                                                      <select
                                                        class="custom-select form-control tm-select-accounts call_sub_cat stcprosearchsame"
                                                        id="filterbysubcat"
                                                        name="stcpdsubcategory"
                                                      >
                                                      </select>
                                                  </div>
                                              </td>
                                          </tr>
                                      </tbody>
                                  </table>
                                  <div class="row stc-pdimg-toolbar">
                                      <div class="col-sm-12 col-md-5">
                                          <div class="checkbox">
                                              <label for="pdimg_only_no_image" title="Limit results to products that have no image file stored">
                                                  <input type="checkbox" id="pdimg_only_no_image" name="pdimg_only_no_image" value="1" />
                                                  Products without image only
                                              </label>
                                          </div>
                                      </div>
                                      <div class="col-sm-6 col-md-3">
                                          <div class="form-group">
                                              <label class="control-label stc-pdimg-compact-label" for="pdimg_per_page">Page size</label>
                                              <select id="pdimg_per_page" name="pdimg_per_page" class="form-control">
                                                  <option value="10" selected>10</option>
                                                  <option value="25">25</option>
                                                  <option value="50">50</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-sm-6 col-md-4 stc-pdimg-search-wrap">
                                          <button type="button" id="pdimg_search_btn" class="btn btn-primary">
                                              <i class="fa fa-search"></i> Search
                                          </button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
                  <div class="row stc-view-imgproduct-row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                      <div class="card-border mb-3 card card-body border-success">
                          <div class="container" style="width:900px;">  
                            <div align="right">
                            </div>
                            <br />
                            <div id="image_data">
                              <p class="text-muted text-center">Choose filters and click <strong>Search</strong>.</p>
                            </div>
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
    <script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
         
          // fetch_data();

          function fetch_data() {
            var action = "fetch";
            $.ajax({
              url      : "kattegat/ragnar_product.php",
              method   :"POST",
              data     :{action:action},
              success  :function(data){
               $('#image_data').html(data);
              }
            });
          }

          $('#add').click(function(){
            $('#imageModal').modal('show');
            $('#image_form')[0].reset();
            $('.modal-title').text("Add Image");
            $('#image_id').val('');
            $('#action').val('insert');
            $('#insert').val("Insert");
          });
          
          $('#image_form').submit(function(event){
            event.preventDefault();
            var image_name = $('#image').val();
            if(image_name == '') {
             alert("Please Select Image");
             return false;
            }else{
              var extension = $('#image').val().split('.').pop().toLowerCase();
              if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1){
               alert("Invalid Image File");
               $('#image').val('');
               return false;
              }else{
                $.ajax({
                  url          : "kattegat/ragnar_product.php",
                  method       : "POST",
                  data         : new FormData(this),
                  contentType  : false,
                  processData  : false,
                  success      : function(data){
                    console.log(data);
                    alert(data);
                    // fetch_data();
                    $('#image_form')[0].reset();
                    $('#imageModal').modal('hide');
                  }
                });
              }
            }
          });

          $(document).on('click', '.update', function(){
            $('#image_id').val($(this).attr("id"));
            $('#action').val("update");
            $('.modal-title').text("Update Image");
            $('#insert').val("Update");
            $('#imageModal').modal("show");
          });


          var jsfiltercat = 'NA';
          var jsfiltersubcat = 'NA';
          var jsfiltername = '';

          function stc_pdimg_validateSearch() {
            var cat = $('#filterbycat').val();
            var sub = $('#filterbysubcat').val();
            var name = $('#searchbystcname').val().trim();
            var onlyNo = $('#pdimg_only_no_image').is(':checked');
            if (!onlyNo && name.length < 3 && cat === 'NA' && sub === 'NA') {
              alert('Select a category or sub-category, enter at least 3 characters in product name, or enable "Products without image only".');
              return false;
            }
            return true;
          }

          function stc_filter_pro(jsfiltercat, jsfiltersubcat, jsfiltername, page) {
            page = page || 1;
            var perPage = $('#pdimg_per_page').val() || 10;
            var onlyNo = $('#pdimg_only_no_image').is(':checked') ? 1 : 0;
            $.ajax({
              url     : "kattegat/ragnar_product.php",
              method  : "post",
              data    : {
                pdimg_sear: 1,
                phpfiltercatout: jsfiltercat,
                phpfiltersubcatout: jsfiltersubcat,
                phpfilternameout: jsfiltername,
                pdimg_page: page,
                pdimg_per_page: perPage,
                pdimg_only_no_image: onlyNo
              },
              success : function(data){
                $('#image_data').html(data);
              },
              error   : function(){
                $('#image_data').html('<p class="text-danger text-center">Request failed. Try again.</p>');
              }
            });
          }

          $('#pdimg_search_btn').on('click', function(e){
            e.preventDefault();
            if (!stc_pdimg_validateSearch()) return;
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $('#searchbystcname').val().trim();
            $('#image_data').html('<p class="text-center text-muted">Loading…</p>');
            stc_filter_pro(jsfiltercat, jsfiltersubcat, jsfiltername, 1);
          });

          $(document).on('click', '.stc-pdimg-page-link', function(e){
            e.preventDefault();
            if ($(this).prop('disabled')) return;
            var p = parseInt($(this).data('page'), 10);
            if (!p || p < 1) return;
            $('#image_data').html('<p class="text-center text-muted">Loading…</p>');
            stc_filter_pro(jsfiltercat, jsfiltersubcat, jsfiltername, p);
          });

          // $('body').delegate('.prodimgsearch', 'click', function (e) {
          //   e.preventDefault();
          //   var pdimgcategory=$('#stc_category_forpdimg').val();
          //   var pdimgsubcategory=$('#stc_sub_category_forpdimg').val();
          //   var productname=$('.prodimgsearchtxt').val();
          //   $.ajax({
          //     url       : "kattegat/ragnar_product.php",
          //     method    : "post",
          //     data      : {
          //       pdimg_sear:1,
          //       productname:productname,
          //       pdimgcategory:pdimgcategory,
          //       pdimgsubcategory:pdimgsubcategory
          //     },
          //     dataType  : 'JSON',
          //     success   : function(data){
          //       // console.log(data);
          //       $('#image_data').html(data);
          //     }
          //   })
          // });
        });
    </script>
</body>
</html>

<div id="imageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Image</h4>
      </div>
      <div class="modal-body">
        <form id="image_form" method="post" enctype="multipart/form-data">
          <p><h5>Select Image</h5>
          <input type="file" name="image" id="image" /></p><br />
          <input type="hidden" name="image_id" id="image_id" />
          <input type="submit" name="insert" value="Update" class="btn btn-info" />         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>