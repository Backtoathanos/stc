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
                                                      required
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


          var jsfiltercat;
          var jsfiltersubcat;
          var jsfiltername;
          // filter function
          // filter by cat
          $("#filterbycat").change(function(e){
            e.preventDefault();
            $('#image_data').html("Loading...");
            jsfiltercat = $(this).val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by sub cat
          $("#filterbysubcat").change(function(e){
            e.preventDefault();
            $('#image_data').html("Loading...");
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $(this).val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by name
          $("#searchbystcname").on('keyup', function(e){
            e.preventDefault();
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $(this).val();
            var responset='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><a href="#" class="btn btn-primary btn-block mb-3">Please type a product name to search or by category/sub category!!!</a></div>';
            var countlen=jsfiltername.length;
            if((+jsfiltername!='') && countlen>=3){
              stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername);
            }else{
              $('#image_data').html(responset);
            }
          });

          // filter function
          function stc_filter_pro(jsfiltercat, jsfiltersubcat ,jsfiltername){
            $.ajax({
              url     : "kattegat/ragnar_product.php",
              method  : "post",
              data    : {
                pdimg_sear:1,
                phpfiltercatout:jsfiltercat,
                phpfiltersubcatout:jsfiltersubcat,
                phpfilternameout:jsfiltername
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('#image_data').html(data);
              }
            });
          }

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