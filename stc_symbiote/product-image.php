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
    <title>Product Images - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <!-- <style> .sp-quantity div { display: inline; }</style> -->
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container-fluid tm-mt-big tm-mb-big animated tada">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Product Images</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

        <!-- view Inventory -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Product Images Search</h2>
              </div>
            </div>
            <div class="row">
              <div class="col-10">
                <input type="text" class="form-control prodimgsearchtxt" placeholder="Enter product name">
              </div>
              <div class="col-2">
                <a href="#" class="form-control btn btn-primary prodimgsearch">Search <i class="fa fa-search"></i></a>
              </div>
            </div>            
            <div class="row stc-view-imgproduct-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <br /><br />  
                <div class="container" style="width:900px;">  
                  <br />
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

        $( function() {
          // $( "#expire_date" ).datepicker();
        } );

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });
      });
    </script>
    <script>  
      $(document).ready(function(){
        pdimgload();
        function pdimgload(){
          $.ajax({
            url : "asgard/mjolnir.php",
            method : "post",
            data : {pdimg_call:1},
            dataType : 'JSON',
            success : function(data){
              // console.log(data);
              $('.stc-imgproduct-form').html(data);
              // $('.stc-inventory-callrack').html(data['rack']);
            }
          });
        }
        
        fetch_data();

        function fetch_data() {
          var action = "fetch";
          $.ajax({
           url:"asgard/mjolnir.php",
           method:"POST",
           data:{action:action},
           success:function(data){
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
                url:"asgard/mjolnir.php",
                method:"POST",
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data){
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

        $('body').delegate('.prodimgsearch', 'click', function (e) {
          e.preventDefault();
          var productname=$('.prodimgsearchtxt').val();
          $.ajax({
            url : "asgard/mjolnir.php",
            method : "post",
            data : {
              pdimg_sear:1,
              productname:productname
            },
            dataType : 'JSON',
            success : function(data){
              // console.log(data);
              $('#image_data').html(data);
            }
          })
        });
      });  
    </script>
  </body>
</html>

<div id="imageModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Image</h4>
      </div>
      <div class="modal-body">
        <form id="image_form" method="post" enctype="multipart/form-data">
          <p><label>Select Image</label>
          <input type="file" name="image" id="image" /></p><br />
          <!-- <input type="hidden" name="action" id="action" value="insert" /> -->
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

